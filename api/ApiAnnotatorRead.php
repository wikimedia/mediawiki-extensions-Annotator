<?php
class ApiAnnotatorRead extends ApiBase {
	public function execute() {
		$params = $this->extractRequestParams();
		$annotation_id = $params['id']; //get the annotation ID

		$repository = new AnnotationRepository();
		$annotation = $repository->get( $annotation_id );

		if( $annotation === null ) {
			$this->dieUsage( "No annotation found", 'annotation_not_found', 404 );
		}
		$result = $this->getResult();
		
		foreach( $annotation as $k => &$v ) {
			$result->addValue( null, $k, $v );
		}
		return true;
	}

	public function getAllowedParams() {
		return array(
			'id' =>array(
				ApiBase::PARAM_TYPE => 'integer',
				ApiBase::PARAM_REQUIRED => true
				)
			);
	}
}