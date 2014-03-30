<?php
/* Extension for creating annotations


*/

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Annotator',
	'descriptionmsg' => 'annotator-desc',
	'version' => '0.2.0',
	'author' => array( 'Richa Jain' ),
	'url' => 'https://mediawiki.org/wiki/Extension:Annotator',
);

$dir = dirname( __FILE__ ) . '/';
$wgMessagesDirs['Annotator'] = __DIR__ . '/i18n';
$wgExtensionMessagesFiles['Annotator'] = $dir . 'Annotator.i18n.php';

//Resource Modules
$wgAnnotatorResourcePaths = array(
	'localBasePath' => __DIR__ . '/modules',
	'remoteExtPath' => "Annotator/modules",
);

$wgResourceModules['mediawiki.libs.okfn'] = array(
	'scripts' => 'mediawiki.libs.okfn/Annotator-full.js',
	'styles' => 'mediawiki.libs.okfn/Annotator.css',
	) + $wgAnnotatorResourcePaths;
$wgResourceModules['ext.annotator'] = array(
	'scripts' => 'Annotator.js', //mediawiki.libs.okfn is loaded dynamically with mw.loader.using when the user views the annotations
	'messages' => array(
		'annotator-view-annotations'
		),
	'dependencies' => array(
		'mediawiki.user',
		),
	) + $wgAnnotatorResourcePaths;

//Autoloading
$wgAutoloadClasses['AnnotatorHooks'] = $dir . 'Annotator.hooks.php';
$wgAutoloadClasses['ApiAnnotatorCreate'] = $dir . 'api/ApiAnnotatorCreate.php';
$wgAutoloadClasses['ApiAnnotatorRead'] = $dir . 'api/ApiAnnotatorRead.php';
$wgAutoloadClasses['ApiAnnotatorSearch'] = $dir . 'api/ApiAnnotatorSearch.php';
$wgAutoloadClasses['ApiAnnotatorDestroy'] = $dir . 'api/ApiAnnotatorDestroy.php';
$wgAutoloadClasses['ApiAnnotatorUpdate'] = $dir . 'api/ApiAnnotatorUpdate.php';
$wgAutoloadClasses['AnnotationRepository'] = $dir . 'AnnotationRepository.php';

//Hooks
$wgHooks['BeforePageDisplay'][] = 'AnnotatorHooks::onBeforePageDisplay';
$wgHooks['LoadExtensionSchemaUpdates'][] = 'AnnotatorHooks::loadExtensionSchemaUpdates';

$wgAPIModules['annotator-create'] = 'ApiAnnotatorCreate';
$wgAPIModules['annotator-read'] = 'ApiAnnotatorRead';
$wgAPIModules['annotator-search'] = 'ApiAnnotatorSearch';
$wgAPIModules['annotator-destroy'] = 'ApiAnnotatorDestroy';
$wgAPIModules['annotator-update'] = 'ApiAnnotatorUpdate';

unset( $dir );
