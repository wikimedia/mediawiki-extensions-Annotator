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
			dirname( __FILE__ ) . '/sql/annotator.sql'
		);

		$updater->addExtensionIndex(
			'annotator',
			'annotator_rev_id',
			dirname( __FILE__ ) . '/sql/index_rev_id.sql'
		);
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