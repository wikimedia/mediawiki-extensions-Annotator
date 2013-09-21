<?php
class ApiAnnotatorCreate extends ApiBase {
	public function execute() {
		//get the annotations
		$annotation = $this->getRawPostString();

		//If the annotation object is null, then die
		if( $annotation == null ) {
			$this->dieUsage( "No annotation object received", 'annotation_not_received', 500 );
		}

		//get the user object
		$user = $this->getUser();

		//get the current user's ID
		$user_id = $user->getId();

		//get the current user's name
		$user_text = $user->getName();

		//get the Revision ID of the page, sent as a parameter in the API request
		$params = $this->extractRequestParams();
		$revid = $params['revid'];
		$revision = Revision::newFromId( $revid );

		//die if the revision id is not valid
		if( $revision === null ) {
			$this->dieUsage( "The revision ID is not valid", 'invalid_revision_id', 404 );
		}

		$annotation = json_decode($annotation);
		unset($annotation->user); //strip out the user object
		$annotation = json_encode($annotation);

		//insert the annotations into the database
		$dbw = wfGetDB( DB_MASTER );
		$dbw->insert(
			'annotator',
			array(
				'annotation_json' => $annotation,
				'annotation_rev_id' => $revid,
				'annotation_user_id' => $user_id,
				'annotation_user_text' => $user_text
				)
			);
		$annotation_id = $dbw->insertId(); //get the annotation ID
		$url = wfExpandUrl( wfScript( 'api' ) . '?action=annotator-read&format=json&id=' . $annotation_id, PROTO_CURRENT );
		$response = $this->getRequest()->response();
		$response->header( "Location: {$url}", true, 303 );

	}

	public function getRawPostString() {
		return file_get_contents('php://input');
	}

	public function getAllowedParams() {
		return array(
			'revid' =>array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => true
				)
			);
	}
}
