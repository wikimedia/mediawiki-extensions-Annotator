<?php
/* Extension hooks

*/
class AnnotatorHooks {
	/** LoadExtensionSchemaUpdates hook
	 *
	 * @param $updater DatabaseUpdater
	 *
	 * @return bool
	 */
	public static function loadExtensionSchemaUpdates( $updater = null ) {
		$updater->addExtensionTable(
			'annotator',
			__DIR__ . '/sql/annotator.sql'
		);

		$updater->addExtensionIndex(
			'annotator',
			'annotator_rev_id',
			__DIR__ . '/sql/index_rev_id.sql'
		);

		if( $updater->getDB()->getType() === 'sqlite' ) {
			$updater->modifyExtensionField( 'annotator', 'user_id', __DIR__ . "/sql/db_patches/patch-user_id-rename.sqlite.sql" );
			$updater->modifyExtensionField( 'annotator', 'rev_id', __DIR__ . "/sql/db_patches/patch-rev_id-rename.sqlite.sql" );
		}
		else {
			$updater->modifyExtensionField( 'annotator', 'user_id', __DIR__ . "/sql/db_patches/patch-user_id-rename.sql" );
			$updater->modifyExtensionField( 'annotator', 'rev_id', __DIR__ . "/sql/db_patches/patch-rev_id-rename.sql" );
		}
		return true;
	}
	/*adds the annotator js and css

	*/
	public static function onBeforePageDisplay( OutputPage &$output, Skin &$skin ) {
		/*
		 module is added only when then namespace matches and user is logged in
		*/
		 if( $skin->getTitle()->inNamespaces( array( NS_MAIN, NS_TALK, NS_CATEGORY ) ) ) {
			$output->addModules( 'ext.annotator' );
		}
		return true;		
	}
}