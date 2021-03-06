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
 * @ingroup SemanticGardeningSpecials
 * 
 * Created on 19.10.2007
 *
 * @author Kai K�hn
 */
if (!defined('MEDIAWIKI')) die();
global $sgagIP;
require_once("$sgagIP/specials/Gardening/SGA_Gardening.php");

function smwfDoSpecialLogPage() {
	wfProfileIn('smwfDoSpecialLogPage (SMW Halo)');
	list( $limit, $offset ) = wfCheckLimits();
	$rep = new SGAGardeningLogPage();
	$result = $rep->doQuery( $offset, $limit );
	wfProfileOut('smwfDoSpecialLogPage (SMW Halo)');
	return $result;
}

define('SMW_GARD_INVISIBLE_ISSUE', 100000);

class SGAGardeningLogPage extends SMWQueryPage {
	
	private $filter;
	private $showAll = false;
	
	function __construct() {
		global $wgRequest, $registeredBots;
		$bot_id = $wgRequest->getVal("bot");
		if ($bot_id == NULL) {
			$this->filter = new ConsistencyBotFilter();
		} else {
			$className = get_class($registeredBots[$bot_id]).'Filter';
			$this->filter = new $className();
		}
		$this->showAll = $wgRequest->getVal("pageTitle") != NULL;
	}
	function getName() {
		return "GardeningLog";
	}

	function isExpensive() {
		return false;
	}

	function isSyndicated() { return false; }

	function getPageHeader() {
		global $wgRequest;
		$html = '<p>' . wfMsg('smw_gardeninglogs_docu') . "</p><br />\n";
		$specialAttPage = Title::newFromText(wfMsg('gardeninglog'), NS_SPECIAL);
		return $html.$this->filter->getFilterControls($specialAttPage, $wgRequest);
	}
	
	function doQuery( $offset = false, $limit = false, $shownavigation = true ) {
		global $wgRequest, $wgOut;
		if ($wgRequest->getVal('limit') == NULL) $limit = 20;
		parent::doQuery($offset, $limit, $shownavigation);
		$wgOut->addHTML("<button type=\"button\" id=\"showall\" onclick=\"window.gardeningLogPage.toggleAll()\">Expand All</button>");
	}
	
	function linkParameters() {
		global $wgRequest;
		$bot_id = $wgRequest->getVal("bot") == NULL ? '' : $wgRequest->getVal("bot");
		$gi_class = $wgRequest->getVal("class") == NULL ? '' : $wgRequest->getVal("class");
		$params = array('bot' => $bot_id, 'class' => $gi_class);
		return array_merge($params, $this->filter->linkUserParameters($wgRequest));
	}
	
	function sortDescending() {
		return false;
	}

	function formatResult( $skin, $result ) {
		global $wgServer, $wgScriptPath;
	    if ($result instanceof GardeningIssueContainer){
			
			$bound = $result->getBound();
			$gis = $result->getGardeningIssues();
			if (is_array($bound)) {
				$escapedDBkey = preg_replace("/'/", "&quot;", htmlspecialchars($bound[0]->getPrefixedDBkey())).preg_replace("/'/", "&quot;", htmlspecialchars($bound[1]->getPrefixedDBkey()));
				$text = $skin->makeLinkObj($bound[0]).' <-> '.$skin->makeLinkObj($bound[1]).': <img class="clickable" src="'.$wgServer.$wgScriptPath.'/extensions/SMWHalo/skins/info.gif" onclick="gardeningLogPage.toggle(\''.$escapedDBkey.'\')"/>' .
					'<div class="gardeningLogPageBox" id="'.$escapedDBkey.'" style="display:'.($this->showAll ? "block" : "none").';">';
			} else {
				$escapedDBkey = preg_replace("/'/", "&quot;", htmlspecialchars($bound->getPrefixedDBkey()));
				$text = $skin->makeLinkObj($bound).': <img class="clickable" src="'.$wgServer.$wgScriptPath.'/extensions/SMWHalo/skins/info.gif" onclick="window.gardeningLogPage.toggle(\''.$escapedDBkey.'\')"/>' .
					'<div class="gardeningLogPageBox" id="'.$escapedDBkey.'" style="display:'.($this->showAll ? "block" : "none").';">';
			}
						
			foreach($gis as $gi) {
				if ($gi->getType() == SMW_GARD_INVISIBLE_ISSUE) continue; // an invisible issue if type > 100000 is not shown textually
				$text .= $gi->getRepresentation($skin).'<br>';
			}
			return $text.'</div>';
			
		} else {
			return 'unknown data of type: '.get_class($result);
		}
	}

	function getResults($options) {
		global $wgRequest;
		return $this->filter->getData($options, $wgRequest);
	}
}

