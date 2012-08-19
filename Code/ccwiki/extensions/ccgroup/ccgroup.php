<?php
require_once ( 'conf.php' );

if (!defined( 'MEDIAWIKI' )) {
        echo <<<EOT
To install my extension, put the folloeing line in LocalSettings.php:
require_once( "\$IP/extensions/ccgroup/ccgroup.php" );
EOT;
        exit( 1 );
}


$url = 'http://' .$ccHost. ':' .$ccPort. '/' .$ccSite. '/index.php';

$dir = dirname(__FILE__) . '/';
$specialdir = $dir . 'special/';

//Invite
$wgAutoloadClasses['SpecialInvite'] = $specialdir . 'SpecialInvite.php';
$wgExtensionMessagesFiles['Invite'] = $dir . 'ccgroup.i18n.php';
$wgSpecialPages['Invite'] =  'SpecialInvite';
$wgSpecialPageGroups['Invite'] = 'other';

//liuna SpecialMapping×¢²á
$wgExtensionCredits['specialpage']['Mapping'] = array(
        'name' => 'Mapping',
        'author' => 'Liuna',
        'url' => $url .'special:Mapping',
        'description' => 'This is CC Mapping Specialpage',
        'descriptionmsg' => 'Mapping-desc',
        'version' => '0.0.0',
);
$dir = dirname(__FILE__) . '/';
$specialdir = $dir . 'special/';
$wgAutoloadClasses['SpecialMapping'] = $specialdir . 'SpecialMapping.php';
$wgExtensionMessagesFiles['Mapping'] = $dir . 'ccgroup.i18n.php';
$wgSpecialPages['Mapping'] =  'SpecialMapping';
$wgSpecialPageGroups['Mapping'] = 'other';

//chen SpecialUserInfo×¢²á
$wgExtensionCredits['specialpage']['UserInfo'] = array(
        'name' => 'UserInfo',
        'author' => 'chen',
        'url' => $url .'special:UserInfo',
        'description' => 'This is CC UserInfo Specialpage',
        'descriptionmsg' => 'UserInfo-desc',
        'version' => '0.0.0',
);
$dir = dirname(__FILE__) . '/';
$specialdir = $dir . 'special/';
$wgAutoloadClasses['SpecialUserInfo'] = $specialdir . 'SpecialUserInfo.php';
$wgExtensionMessagesFiles['UserInfo'] = $dir . 'ccgroup.i18n.php';
$wgSpecialPages['UserInfo'] =  'SpecialUserInfo';
$wgSpecialPageGroups['UserInfo'] = 'other';

//CreatePage
$wgExtensionCredits['specialpage']['CreatePage'] = array(
        'name' => 'CreatePage',
        'author' => 'chen',
        'url' => $url .'special:CreatePage',
        'description' => 'This is CC CreatePage Specialpage',
        'descriptionmsg' => 'UserInfo-desc',
        'version' => '0.0.0',
);
$dir = dirname(__FILE__) . '/';
$specialdir = $dir . 'special/';
$wgAutoloadClasses['SpecialCreatePage'] = $specialdir . 'SpecialCreatePage.php';
$wgExtensionMessagesFiles['CreatePage'] = $dir . 'ccgroup.i18n.php';
$wgSpecialPages['CreatePage'] =  'SpecialCreatePage';
$wgSpecialPageGroups['CreatePage'] = 'other';

?>
