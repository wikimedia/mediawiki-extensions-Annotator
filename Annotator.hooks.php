<?php
/* Extension hooks

*/
class AnnotatorHooks {
	/*adds the annotator js and css

	*/
	public static function onBeforePageDisplay( OutputPage &$output, Skin &$skin ) {
		/*
		 module is added only when then namespace matches
		*/
		 if( $skin->getTitle()->inNamespaces( array( NS_MAIN, NS_TALK, NS_CATEGORY ) ) ) {
			$output->addModules( 'ext.annotator' );
		}
		return true;		
	}
}