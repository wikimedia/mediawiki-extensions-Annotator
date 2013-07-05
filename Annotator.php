<?php
/* Extension for creating annotations


*/
$dir = dirname( __FILE__ ) . '/';
$wgExtensionMessagesFiles['Annotator'] = $dir . 'Annotator.i18n.php';

//Resource Modules
$wgAnnotatorResourcePaths = array(
	'localBasePath' => __DIR__ . '/modules',
	'remoteExtPath' => "Annotator/modules",
);
$wgResourceModules['ext.annotator'] = array(
	'scripts' => array(
		'Annotator-full.js',
		'Annotator.js',
		),
	'styles' => 'Annotator.css',
	) + $wgAnnotatorResourcePaths;

//Autoloading
$wgAutoloadClasses['AnnotatorHooks'] = $dir . 'Annotator.hooks.php';
$wgAutoloadClasses['ApiAnnotatorCreate'] = $dir . 'api/ApiAnnotatorCreate.php';

//Hooks
$wgHooks['BeforePageDisplay'][] = 'AnnotatorHooks::onBeforePageDisplay';
$wgHooks['LoadExtensionSchemaUpdates'][] = 'AnnotatorHooks::loadExtensionSchemaUpdates';

$wgAPIModules['annotator-create'] = 'ApiAnnotatorCreate';