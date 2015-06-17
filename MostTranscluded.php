<?php
/**
 * MostTranscludedImages extension - see which pages have the most images
 *
 * For more info see http://mediawiki.org/wiki/Extension:MostTranscludedImages
 *
 * @file
 * @ingroup Extensions
 * @author Frances Hocutt, 2015
 */

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'MostTranscludedImages',
	'author' => array(
		'Frances Hocutt',
	),
	'version'  => '0.0.1',
	'url' => 'https://www.mediawiki.org/wiki/Extension:MostTranscludedImages',
	'descriptionmsg' => 'mosttranscludedimages-desc',
	'license-name' => 'MIT',
);

/* Setup */

// Register files
#$wgAutoloadClasses['BoilerPlateHooks'] = __DIR__ . '/BoilerPlate.hooks.php';
//this one's ok; do I actually need anything else here?
$wgAutoloadClasses['SpecialMosttranscludedimages'] = __DIR__ . '/specials/SpecialMosttranscludedimages.php';
$wgMessagesDirs['MostTranscludedImages'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['MostTranscludedImagesAlias'] = __DIR__ . '/MostTranscludedImages.i18n.alias.php';

// Register hooks
#$wgHooks['NameOfHook'][] = 'BoilerPlateHooks::onNameOfHook';

// Register special pages
$wgSpecialPages['MostTranscludedImages'] = 'SpecialMosttranscludedimages';

// Register modules
#$wgResourceModules['ext.boilerPlate.foo'] = array(
#	'scripts' => array(
#		'modules/ext.boilerPlate.js',
#		'modules/ext.boilerPlate.foo.js',
#	),
#	'styles' => array(
#		'modules/ext.boilerPlate.foo.css',
#	),
#	'messages' => array(
#	),
#	'dependencies' => array(
#	),
#
#	'localBasePath' => __DIR__,
#	'remoteExtPath' => 'examples/BoilerPlate',
#);


/* Configuration */

// Enable Foo
#$wgBoilerPlateEnableFoo = true;
