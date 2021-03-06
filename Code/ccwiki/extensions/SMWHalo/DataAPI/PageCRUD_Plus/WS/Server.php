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
  * @ingroup DAPCP
  *
  * @author Dian
 */

/**
 * @deprecated Use the ServerAPI instead.
 */
require_once('Zend/Rest/Server.php');


##
# functions calling directly the PCPServer functions.
##
/**
 * Enter description here...
 *
 * @param unknown_type $username
 * @param unknown_type $password
 * @param unknown_type $id
 * @param unknown_type $loginToken
 * @param unknown_type $editToken
 * @return unknown
 */
function login($username=NULL, $password=NULL, $id=NULL, $loginToken = NULL, $editToken = NULL){
	$__pcpServer = new PCPServer();
	$__userCredentils = new PCPUserCredentials($username, $password, $id, $loginToken, $editToken);
	return $__pcpServer->login($__userCredentils)->toXML();
}
/**
 * Enter description here...
 *
 * @param unknown_type $username
 * @param unknown_type $password
 * @param unknown_type $id
 * @param unknown_type $loginToken
 * @param unknown_type $editToken
 */
function logout($username=NULL, $password=NULL, $id=NULL, $loginToken = NULL, $editToken = NULL){
	$__pcpServer = new PCPServer();
	$__userCredentils = new PCPUserCredentials($username, $password, $id, $loginToken, $editToken);
	$__pcpServer->logout($__userCredentils);
}


function createPage($username=NULL, $password=NULL, $id=NULL, $loginToken = NULL, $editToken = NULL, $title=NULL, $text=NULL, $summary=NULL){
	$__pcpServer = new PCPServer();
	$__userCredentials = new PCPUserCredentials($username, $password, $id, $loginToken, $editToken);
	print ("Testing$username, $password, $id, $loginToken, $editToken$title, $text, $summary->".$__pcpServer->createPage($__userCredentials,$title, $text, $summary));
	print(simplexml_load_string(PCPUtil::createXMLResponse($__pcpServer->createPage($__userCredentials,$title, $text, $summary)),'SimpleXMLElement'));
	return simplexml_load_string(PCPUtil::createXMLResponse($__pcpServer->createPage($__userCredentials,$title, $text, $summary)),'SimpleXMLElement');
}

function readPage($username=NULL, $password=NULL, $id=NULL, $loginToken = NULL, $editToken = NULL, $title= NULL, $revisionID = NULL){
	$__pcpServer = new PCPServer();
	$__userCredentials = new PCPUserCredentials($username, $password, $id, $loginToken, $editToken);
	
	$_REQUEST = array();
	$__pcpServer->login($__userCredentials);
	
//	var_dump($__userCredentials,$title, $revisionID);
//	die;
	return simplexml_load_string(PCPUtil::createXMLResponse($__pcpServer->readPage($__userCredentials,$title, $revisionID)->toXML()),'SimpleXMLElement', LIBXML_NOCDATA);
}


##
# Initialize server and register functions
##
if (array_key_exists('action', $_REQUEST) && $_REQUEST['action'] == 'wspcp' ) {
	$__wsServer = new Zend_Rest_Server();
	$__wsServer->addFunction('login');
	$__wsServer->addFunction('logout');
	$__wsServer->addFunction('createPage');
	$__wsServer->addFunction('readPage');
	$__wsServer->handle();
	
	exit; // stop immediately
}


