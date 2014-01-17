<?php

class OsfLink extends DataObject {
	function getArticleId() {
		return $this->getData('articleId');
	}

	function setArticleId($articleId) {
		return $this->setData('articleId', $articleId);
	}

	function getOsfUrl() {
		return $this->getData('osfUrl');
	}

	function setOsfUrl($osfUrl) {
		return $this->setData('osfUrl', $osfUrl);
	}
}