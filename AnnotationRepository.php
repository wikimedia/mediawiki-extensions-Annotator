<?php
class AnnotationRepository {
	public function get( $annotation_id ) {

		$dbr = wfGetDB( DB_SLAVE );
		//select the annotation object from the database
		$res = $dbr->select(
			array('annotator', 'user'),
			array(
				'annotation_json',
				'annotation_user_id',
				'user_name'
				),
			array(
				'annotation_id' => $annotation_id
				),
			__METHOD__,
			array(),
			array(
				'user' => array(
					'INNER JOIN',
					array(
						'user_id = annotation_user_id'
						)
					)
				)
			);

		$result = $dbr->fetchObject( $res );
		if( !$result ) {
			return null;
		}

		$annotation = AnnotationRepository::populateAnnotation( $result->annotation_json, $annotation_id, $result->annotation_user_id, $result->user_name );
		return $annotation;
	}

	public function getAllByRevid( $revid ) {
		//selects annotations of a particular revision ID
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
			array('annotator', 'user'),
			array(
				'annotation' => 'annotation_json',
				'id' => 'annotation_id',
				'userId' => 'annotation_user_id',
				'userName' => 'user_name'
				),
			array(
				'rev_id' => $revid
				),
			__METHOD__,
			array(),
			array(
				'user' => array(
					'INNER JOIN',
					array(
						'user_id = annotation_user_id'
						)
					)
				)
			);

		$annotations = array();
		$annotations['rows'] = array();
		$total = 0;
		foreach($res as $result) {
			$annotations['rows'][] = AnnotationRepository::populateAnnotation( $result->annotation, $result->id, $result->userId, $result->userName );
			$total = $total + 1;
		}
		$annotations['total'] = $total;
		return $annotations;
	}

	protected function populateAnnotation( $annotation_json, $annotation_id, $userId, $userName ) {
		$annotation = json_decode($annotation_json);
		$annotation->id = $annotation_id; //update the annotation object with the ID
		$annotation->user->id = $userId;
		$annotation->user->username = $userName;
		return $annotation;
	}
}