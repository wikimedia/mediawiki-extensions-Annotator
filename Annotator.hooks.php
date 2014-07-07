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

		// If the new name are already in use (e.g. if it's a clean install, or up to date), all of these renames will be skipped.
		if( $updater->getDB()->getType() === 'sqlite' ) {
			$updater->modifyExtensionField( 'annotator', 'user_id', __DIR__ . "/sql/db_patches/patch-user_id-rename.sqlite.sql" );
			$updater->modifyExtensionField( 'annotator', 'rev_id', __DIR__ . "/sql/db_patches/patch-rev_id-rename.sqlite.sql" );
		}
		else {
			$updater->modifyExtensionField( 'annotator', 'user_id', __DIR__ . "/sql/db_patches/patch-user_id-rename.sql" );
			$updater->modifyExtensionField( 'annotator', 'rev_id', __DIR__ . "/sql/db_patches/patch-rev_id-rename.sql" );
		}

		$updater->addExtensionIndex(
			'annotator',
			'annotator_rev_id',
			__DIR__ . '/sql/db_patches/index_rev_id.sql'
		);

		$updater->addExtensionField(
			'annotator',
			'annotation_user_text',
			__DIR__ . "/sql/db_patches/patch-annotation_user_text-add.sql"
		);
		return true;
	}
	/*adds the annotator js and css

	*/
	public static function onBeforePageDisplay( OutputPage &$output, Skin &$skin ) {
		/*
		 module is added only when then namespace matches
		*/
		global $wgAnnotatorNamespaces;
		if( $skin->getTitle()->inNamespaces( $wgAnnotatorNamespaces ) ) {
			$output->addModules( 'ext.annotator' );
		}
		return true;
	}
}
