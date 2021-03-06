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

/**
 * @file
 * @ingroup EnhancedRetrievalSynsets
 * 
 * @author Ingo Steinbauer
 */
global $wgExtensionFunctions, $wgHooks;  
// Define a setup function for the {{ ws:}} Syntax Parser
$wgExtensionFunctions[] ='synsetPF_Setup';
//Add a hook to initialise the magic word for the {{ ws:}} Syntax Parser
$wgHooks['LanguageGetMagic'][] = 'synsetPF_Magic';

/**
 * Set a function hook associating the "webServiceUsage" magic word with our function
 */
function synsetPF_Setup() {
	global $wgParser;
	$wgParser->setFunctionHook( 'synsetPF', 'synsetPF_Render' );
}

/**
 * maps the magic word "webServiceUsage"to occurences of "ws:" in the wiki text
 */
function synsetPF_Magic( &$magicWords, $langCode ) {
	$magicWords['synsetPF'] = array( 0, 'synonyms' );
	return true;
}

/**
 * Parses the {{ synonyms: }} syntax and returns the resulting wikitext
 *
 * @param $parser
 * @return string
 * 		the rendered wikitext
 */
function synsetPF_Render( &$parser) {
	$parameters = func_get_args();
	$term = trim($parameters[1]);
	
	global $IP;
	require_once($IP."/extensions/EnhancedRetrieval/synsets/storage/SMW_SynsetStorageSQL.php");
	
	$st = new SynsetStorageSQL();
	$synonyms = $st->getSynsets($term);
	
	$result = "";
	
	$nFirst = false;
	foreach($synonyms as $synset){
		foreach($synset as $syn){
			if(strlen($syn) > 0){
				if($nFirst){
					$result .= ", "; 	
				}
				$result .= $syn;
				$nFirst = true;
			}
		}
	}
	return $result; 
}


