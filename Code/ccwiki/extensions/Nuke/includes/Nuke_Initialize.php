<?php

if ( !defined( 'MEDIAWIKI' ) ) {
	die( "This file is part of the AutomaticSemanticForms extension. It is not a valid entry point.\n" );
}


/*
 * This method must be called in Local Settings
 * 
 * It sets up the Nuke Extension
 */
function enableNuke() {
	require_once("$IP/extensions/Nuke/SpecialNuke.php");
}

