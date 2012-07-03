<?php

/**
 * Main entry point for the ccSRF extension.
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

// Show a warning if Semantic MediaWiki is not loaded.
if ( ! defined( 'SMW_VERSION' ) ) {
	die( '<b>Error:</b> You need to have <a href="http://semantic-mediawiki.org/wiki/Semantic_MediaWiki">Semantic MediaWiki</a> installed in order to use <a href="https://www.mediawiki.org/wiki/Extension:Semantic Result Formats">Semantic Result Formats</a>.<br />' );
}

$smwgResultFormats['ccperson'] = 'SRFCcPerson';
$wgAutoloadClasses['SRFCcPerson'] = $IP.'/extensions/ccgroup/ccSRF/SRF_CcPerson.php';
