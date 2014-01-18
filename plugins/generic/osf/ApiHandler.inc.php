<?php

import('classes.handler.Handler');

class ApiHandler extends Handler {

	function articles($args, $request) {
		$articleId = $args[0];
		$authorSubmissionDAO = DAORegistry::getDAO('AuthorSubmissionDAO');
		
		$article =& $authorSubmissionDAO->getAuthorSubmission($articleId);

		$rv = array();
		$rv['status'] = $this->getTextStatus($article->getSubmissionStatus());

		

		echo $request->getUserVar('callback') . '(' . json_encode($rv) . ')';

	}

	private function getTextStatus($status_code) {
		switch($status_code) {
			case 0:
				return 'Archived';

			case 3:
				return 'Published';
			case 4:
				return 'Declined';

			case 1:
			case 5:
			case 6:
			case 7:
				return 'Queued';
			case 8:
				return 'Incomplete';
			default:
				return 'Unknown Status';
		}
	}
}