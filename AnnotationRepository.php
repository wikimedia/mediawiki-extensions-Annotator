<?php
class AnnotationRepository {
	public function get( $annotation_id ) {

		$dbr = wfGetDB( DB_SLAVE );
		//select the annotation object from the database
		$annotation_json = $dbr->selectField(
			'annotator',
			'annotation_json',
			array(
				'annotation_id' => $annotation_id
				)
			);

		if( $annotation_json === false ) {
			return null;
		}

		$annotation = AnnotationRepository::populateAnnotation( $annotation_json, $annotation_id );
		return $annotation;
	}

	public function getAllByRevid( $revid ) {
		//selects annotations of a particular revision ID
		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->select(
			'annotator',
			array(
				'annotation' => 'annotation_json',
				'id' => 'annotation_id'
				),
			array(
				'rev_id' => $revid
				)
			);

		$annotations = array();
		$annotations['rows'] = array();
		$total = 0;
		foreach($res as $result) {
			$annotations['rows'][] = AnnotationRepository::populateAnnotation( $result->annotation, $result->id );
			$total = $total + 1;
		}
		$annotations['total'] = $total;
		return $annotations;
	}

	protected function populateAnnotation( $annotation_json, $annotation_id ) {
		$annotation = json_decode($annotation_json);
		$annotation->id = $annotation_id; //update the annotation object with the ID
		return $annotation;
	}
}