<?php

/**
 * Main entry point for the MashupWiki extension.
 * http://www.mediawiki.org/wiki/Extension:MashupWiki
 * 
 * @file MashupWiki.php
 * @ingroup MashupWiki
 * 
 * @licence GNU GPL v3+
 */

/**
 * This documentation group collects source code files belonging to MashupWiki.
 * 
 * @defgroup MashupWiki MashupWiki
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

// Show a warning if Semantic MediaWiki is not loaded.
if ( ! defined( 'SMW_VERSION' ) ) {
	die( '<b>Error:</b> You need to have <a href="http://semantic-mediawiki.org/wiki/Semantic_MediaWiki">Semantic MediaWiki</a> installed in order to use <a href="https://www.mediawiki.org/wiki/Extension:Semantic Result Formats">Semantic Result Formats</a>.<br />' );
}

define( 'MUW_VERSION', '0.1' );

// Require the settings file.
//require dirname( __FILE__ ) . '/Mashup_Settings.php';

/*if ( defined( 'MW_SUPPORTS_RESOURCE_MODULES' ) ) {
	require dirname( __FILE__ ) . '/SRF_Resources.php';
}*/


$srfgFormats = array( 'bidetail','bigallery','bisns','biweibo','bicomment','bijiepang','bitaobao','biperson');

$srfgScriptPath = ( $wgExtensionAssetsPath === false ? $wgScriptPath . '/extensions' : $wgExtensionAssetsPath ) . '/mashupwiki'; 
$srfgIP = dirname( __FILE__ );

$wgExtensionCredits['semantic'][] = array(
	'path' => __FILE__,
	'name' => 'MashupWiki',
	'version' => MUW_VERSION,
	'author' => array(
		'[http://www.mediawiki.org/wiki/User:slingma sling ma]',
		'...'
	),
	'url' => 'https://www.mediawiki.org/wiki/Extension:MashupWiki',
	'descriptionmsg' => ''
);

$formatDir = dirname( __FILE__ ) . '/';


include_once $formatDir . 'DynamicArticleList.php';
unset( $formatDir );

$wgExtensionFunctions[] = 'muwInitFormats';
$wgExtensionFunctions[] = 'efMUWSetup';


function muwInitFormats() {
	global $srfgFormats, $smwgResultFormats, $wgAutoloadClasses;
	
        $formatDir = dirname( __FILE__ ) . '/';
	
	$wgAutoloadClasses['MUWbIGallery'] = $formatDir . 'bIGallery/MUW_bIGallery.php';
        $wgAutoloadClasses['MUWbIDetail'] = $formatDir . 'bIDetail/MUW_bIDetail.php';
        $wgAutoloadClasses['MUWbIWeiBo'] = $formatDir . 'bIWeibo/MUW_bIWeibo.php';
        $wgAutoloadClasses['MUWbISns'] = $formatDir . 'bISns/MUW_bISns.php';
        $wgAutoloadClasses['MUWbIComment'] = $formatDir . 'bIComment/MUW_bIComment.php';
        $wgAutoloadClasses['MUWbIJiepang'] = $formatDir . 'bIJiepang/MUW_bIJiepang.php';
		$wgAutoloadClasses['MUWbITaobao'] = $formatDir . 'bITaobao/MUW_bITaobao.php'; //liuna
		$wgAutoloadClasses['MUWbIPerson'] = $formatDir . 'bIPerson/MUW_bIPerson.php';

	$formatClasses = array(
		'bigallery' => 'MUWbIGallery',
		'bidetail' => 'MUWbIDetail',
		'biweibo' => 'MUWbIWeiBo',
        'bisns'=>'MUWbISns',
        'bicomment'=>'MUWbIComment',
        'bijiepang'=>'MUWbIJiepang',
		'bitaobao'=>'MUWbITaobao',
		'biperson'=>'MUWbIPerson',
	);

 
	
	foreach ( $srfgFormats as $format ) {
		if ( array_key_exists( $format, $formatClasses ) ) {
			$smwgResultFormats[$format] = $formatClasses[$format];
                        
                        if ( defined( 'MW_SUPPORTS_RESOURCE_MODULES' ) && method_exists( $formatClasses[$format], 'registerResourceModules' ) ) {
                                    call_user_func( array( $formatClasses[$format], 'registerResourceModules' ) );
                        }
		}
		else {
			wfDebug( "There is not result format class associated with the format '$format'." );
		}
	}
        
        
}
function efMUWSetup() {
	global $wgVersion,$wgParser;
	// This function has been deprecated in 1.16, but needed for earlier versions.
	if ( version_compare( $wgVersion, '1.16', '<' ) ) {
		wfLoadExtensionMessages( 'MashupWiki' );
	}
        $wgParser->setHook("dynamicarticlelist", "DynamicArticleListRender" );
	return true;
}
