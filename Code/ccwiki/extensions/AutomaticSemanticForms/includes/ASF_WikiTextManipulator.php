<?php
/*
 * Copyright (C) Vulcan Inc.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program.If not, see <http://www.gnu.org/licenses/>.
 *
 */


include_once('extensions/SMWHalo/DataAPI/PageCRUD_Plus/PCP.php');
include_once('extensions/SMWHalo/DataAPI/PageObjectModel/POM.php');

//todo: docu

class ASFWikiTextManipulator {
	
	private static $instance;
	
	public static function getInstance(){
		if(self::$instance == null){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	private function __construct(){}
	
	public function getWikiTextAndAnnotationsForSF($titleString, $text){
		
		///todo: maybe add notes for glitches in tooltips
		
		if($text == null) $text = '';
		
		//deal with ontology imjport conflict sections and other tags 
		$pattern = array(
			'/<!--\s*BEGIN ontology:([^>]*)-->([^<]*)<!--\s*END ontology([^>]*)-->/',
			'/<\s*onlyinclude\s*>/',
			'/<\s*\/onlyinclude\s*>/',
			'/<\s*includeonly\s*>/',
			'/<\s*\/includeonly\s*>/'
			);
		$replacement = array(
			'<nowiki> <!--asf--> $0 <!--asf--> </nowiki>',
			'<nowiki><onlyinclude>',
			'</onlyinclude></nowiki>',
			'<nowiki><includeonly>',
			'</includeonly></nowiki>'
			);
		$text = preg_replace($pattern, $replacement, $text);
		
		POMElement::$elementCounter = 0;
		$pomPage = new POMPage($titleString, $text);
		
		$collectedAnnotations = array();
		
		$elements = $pomPage->getElements()->listIterator();
		while($elements->hasNext()){
			$element = $elements->getNext()->getNodeValue();
				
			if($element instanceof POMProperty){
				
				if($this->isPropertyIgnored($element->getName())){
					continue;
				} else {
					
					$this->rememberAnnotation($element->getName(), $element->getValue(), $collectedAnnotations);
						
					$newElement = new POMSimpleText($this->getWikiTextReplacementForAnnotation(
						$element->getName(), $element->getValue(), $element->getRepresentation()));
					$newElement->id = $element->id;
					$pomPage->update($newElement);
				}
			
			} else if ($element instanceof POMExtensionParserFunction){
				if(strpos($element->nodeText, '{{#set:') === 0){
					$sets = trim(substr($element->nodeText, strlen('{{#set:')));
					$sets = substr($sets, 0, strlen($sets)-2);
					$sets = explode('|', $sets);
					$ignoredProperties = array();
					foreach($sets as $set){
						$set = explode('=', $set, 2);
						if(count($set) == 2){
							if($this->isPropertyIgnored(trim($set[0]))){
								$ignoredProperties[] = $set[0].'='.$set[1]; 
							} else {
								$this->rememberAnnotation(trim($set[0]), trim($set[1]), $collectedAnnotations);
							}
						} else {
							$ignoredProperties[] = $set[0];
						}
					}
					
					$newText = ''.implode('| ', $ignoredProperties);
					if(strlen($newText ) > 0) $newText = '{{#set:'.$newText.'}}';
					$newElement = new POMSimpleText($newText);
					$newElement->id = $element->id;
					$pomPage->update($newElement);
				} 
			} else if ($element instanceof POMBuiltInParserFunction){
				if(strpos($element->nodeText, '{{CreateSilentAnnotations:') === 0){
					$silents = trim(substr($element->nodeText, strlen('{{CreateSilentAnnotations:')));
					$silents = substr($silents, 0, strlen($silents)-2);
					$silents = explode('|', $silents);
					$ignoredProperties = array();
					foreach($silents as $silent){
						if(strlen($silent) == 0) continue;
						$silent = explode('=', $silent, 2);
						if(count($silent) == 2){
							if($this->isPropertyIgnored(trim($silent[0]))){
								$ignoredProperties[] = $silent[0].'='.$silent[1]; 
							} else {
								//check if value must be split with a delimiter
								if($delimiter = $this->getSilentAnnotationsDelimiter(trim($silent[0]))){
									$values = explode($delimiter, $silent[1]);
								} else {
									$values = array($silent[1]);
								}
							
								foreach($values as $val){
									$this->rememberAnnotation(trim($silent[0]), trim($val), $collectedAnnotations);
								}
							}
						}
					}
					
					$newText = ''.implode('| ', $ignoredProperties);
					if(strlen($newText ) > 0) $newText = '{{#set:'.$newText.'}}';
					$newElement = new POMSimpleText($newText);
					$newElement->id = $element->id;
					$pomPage->update($newElement);
				} 
			}
		}
		
		$text = '{{CreateSilentAnnotations:';
		foreach($collectedAnnotations as $label => $annotation){
			$delimiter = $this->getSilentAnnotationsDelimiter($label);
			if(!$delimiter) $delimiter = ', ';
			$text .= '|'.ucfirst($label).'='.implode($delimiter, $annotation['values']);
		}
		$text .= '}}';
		
		$pomPage->sync();
		$text.= trim($pomPage->text);
		
		//deal with ontology imjport conflict sections and other tags again
		$pattern = array(
			'/<nowiki> <!--asf--> <!--\s*BEGIN ontology:/',
			'/--> <!--asf--> <\/nowiki>/',
			'/<nowiki><onlyinclude>/',
			'/<\/onlyinclude><\/nowiki>/',
			'/<nowiki><includeonly>/',
			'/<\/includeonly><\/nowiki>/',
			); 
		$replacement = array(
			'<!-- BEGIN ontology:',
			'-->',
			'<onlyinclude>',
			'</onlyinclude>',
			'<includeonly>',
			'</includeonly>',
			);
		$text = preg_replace($pattern, $replacement, $text);
		
		return array($text, $collectedAnnotations );
	}
	
	
	public function getWikiTextForSaving($titleString, $text, $existingAnnotations){

		if($text == null) $text = '';
		
		POMElement::$elementCounter = 0;
		$pomPage = new POMPage($titleString, $text);

		$collectedAnnotations = array();
		
		$elements = $pomPage->getElements()->listIterator();
		while($elements->hasNext()){
			$element = $elements->getNext()->getNodeValue();
				
			if ($element instanceof POMBuiltInParserFunction){
				if(strpos($element->nodeText, '{{CreateSilentAnnotations:') === 0){
					$silents = trim(substr($element->nodeText, strlen('{{CreateSilentAnnotations:')));
					$silents = substr($silents, 0, strlen($silents)-2);
					$silents = explode('|', $silents);
					foreach($silents as $silent){
						if(strlen($silent) == 0) continue;
						$silent = explode('=', $silent, 2);
						if(count($silent) == 2){
							//check if value must be split with a delimiter
							if($delimiter = $this->getSilentAnnotationsDelimiter(trim($silent[0]), $existingAnnotations)){
								$values = explode($delimiter, $silent[1]);
							} else {
								$values = array($silent[1]);
							}
								
							foreach($values as $val){
								$this->rememberAnnotation(trim($silent[0]), trim($val), $collectedAnnotations);
							}
						}
					}
					
					$newElement = new POMSimpleText('');
					$newElement->id = $element->id;
					$pomPage->update($newElement);
				} 
			}
		}
		
		$text = '';
		foreach($collectedAnnotations as $label => $annotation){
			foreach($annotation['values'] as $value){
				$text .= '[['.$label.'::'.$value.'| ]]';
			}
		}
		
		$pomPage->sync();
		$text = trim($pomPage->text).trim($text);
		
		return $text;
	}
	
	private function rememberAnnotation($name, $value, &$collectedAnnotations){
		$name = ucfirst($name);
		if(!array_key_exists($name, $collectedAnnotations)){
			$collectedAnnotations[$name] = array();
			$collectedAnnotations[$name]['values'] = array();
		}

		if(!in_array($value, $collectedAnnotations[$name]['values'])){
			$collectedAnnotations[$name]['values'][] = $value;
		}
	}
	
	private function getSilentAnnotationsDelimiter($propertyName, $existingAnnotations = array()){
		
		$title = Title::newFromText($propertyName, SMW_NS_PROPERTY);
		if(!($title instanceof Title) || !$title->exists()){
			return ',';
		}
		
		$semanticData = ASFFormGeneratorUtils::getSemanticData($title);
		
		$maxCardinality = ASFFormGeneratorUtils::getPropertyValue(
			$semanticData, ASF_PROP_HAS_MAX_CARDINALITY, false, false);
		$delimiter = ASFFormGeneratorUtils::getPropertyValue(
			$semanticData, ASF_PROP_DELIMITER, false, false);
		
		if($maxCardinality != 1 || $delimiter){
			if(!$delimiter){ 
				return ',';
			} else {
				return $delimiter;
			}
		} else if (array_key_exists($propertyName, $existingAnnotations)){
			if(count($existingAnnotations[$propertyName]['values']) > 1){
				return ',';
			}
		}
		
		return false;
	}
	
	private function getWikiTextReplacementForAnnotation($propertyName, $value, $replacement){

		if(trim($replacement) == ''){
			return '';
		}
		
		$title = Title::newFromText($propertyName, SMW_NS_PROPERTY);
		if(!($title instanceof Title) || !$title->exists()){
			return '[['.$value.'| '.$replacement.']]';
		}
		
		$semanticData = ASFFormGeneratorUtils::getSemanticData($title);
		
		$res = ASFFormGeneratorUtils::getPropertyValue(
			$semanticData, ASF_PROP_HAS_TYPE, false, false);
		
		if($res== 'http://semantic-mediawiki.org/swivt/1.0#_wpg' || !$res){
			return '[['.$value.'| '.$replacement.']]';
		}
		
		return trim($replacement);
	}
	
	
	private function isPropertyIgnored($propertyName){
		$title = Title::newFromText($propertyName, SMW_NS_PROPERTY);
		if(!($title instanceof Title) || !$title->exists()){
			return false;
		}
		
		$semanticData = ASFFormGeneratorUtils::getSemanticData($title);
		
		$res = ASFFormGeneratorUtils::getPropertyValue(
			$semanticData, ASF_PROP_NO_AUTOMATIC_FORMEDIT, false, false);
			
		if(strtolower($res) == 'false') $res = false;
		
		return $res;
	}
	
	
}
