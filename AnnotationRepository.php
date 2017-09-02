<?php
class AnnotationRepository {
	public function get( $annotation_id ) {

		$dbr = wfGetDB( DB_REPLICA );
		//select the annotation object from the database
		$res = $dbr->select(
			array('annotator'),
			array(
				'annotation_json',
				'annotation_user_id',
				'annotation_user_text'
				),
			array(
				'annotation_id' => $annotation_id
				),
			__METHOD__
			);

		$result = $dbr->fetchObject( $res );
		if( !$result ) {
			return null;
		}

		$annotation = AnnotationRepository::populateAnnotation( $result->annotation_json, $annotation_id, $result->annotation_user_id, $result->annotation_user_text );
		return $annotation;
	}

	public function getAllByRevid( $revid ) {
		//selects annotations of a particular revision ID
		$dbr = wfGetDB( DB_REPLICA );
		$res = $dbr->select(
			array('annotator'),
			array(
				'annotation' => 'annotation_json',
				'id' => 'annotation_id',
				'userId' => 'annotation_user_id',
				'userText' => 'annotation_user_text'
				),
			array(
				'annotation_rev_id' => $revid
				),
			__METHOD__
			);

		$annotations = array();
		$annotations['rows'] = array();
		$total = 0;
		foreach($res as $result) {
			$annotations['rows'][] = AnnotationRepository::populateAnnotation( $result->annotation, $result->id, $result->userId, $result->userText );
			$total = $total + 1;
		}
		$annotations['total'] = $total;
		return $annotations;
	}

	protected function populateAnnotation( $annotation_json, $annotation_id, $userId, $userText ) {
		$annotation = json_decode($annotation_json);
		$annotation->id = $annotation_id; //update the annotation object with the ID
		$annotation->user->id = $userId;
		$annotation->user->userText = $userText;
		return $annotation;
	}
}