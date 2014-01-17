<?php
/**
 * @file plugins/generic/osf/OsfLinksDAO.inc.php
 *
 * Copyright (c) 2013 Center for Open Science
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @package plugins.generic.osf
 * @class OsfLinksDAO
 *
 * Operations for retrieving and modifying StaticPages objects.
 *
 */
import('lib.pkp.classes.db.DAO');

class OsfLinksDAO extends DAO {
	/** @var $parentPluginName Name of parent plugin */
	var $parentPluginName;

	/**
	 * Constructor
	 */
	function OsfLinksDAO($parentPluginName) {
		$this->parentPluginName = $parentPluginName;
		parent::DAO();
	}

	function getOsfLinkByArticleId($articleId) {
		$result =& $this->retrieve(
			'SELECT * FROM osf_links WHERE article_id = ?',
			$articleId
		);

		$returner = null;
		if ($result->RecordCount() != 0) {
			$returner =& $this->_returnLinkFromRow($result->GetRowAssoc(false));
		}
		$result->Close();
		return $returner;
	}

	function &_returnLinkFromRow(&$row) {
		$parentPlugin =& PluginRegistry::getPlugin('generic', $this->parentPluginName);
		$parentPlugin->import('OsfLink');

		$osfLink = new OsfLink();
		$osfLink->setArticleId($row['article_id']);
		$osfLink->setOsfUrl($row['osf_url']);

		return $osfLink;
	}

	function insertOrUpdateOsfLink(&$osfLink) {
		$existingOsfLink = $this->getOsfLinkByArticleId($osfLink->getArticleId());
		if ($existingOsfLink === null) {
			return $this->insertOsfLink($osfLink);
		}
		elseif ($existingOsfLink->getOsfUrl() !== $osfLink->getOsfUrl()) {
			return $this->updateOsfLink($osfLink);
		}
	}

	function insertOsfLink(&$osfLink) {
		return $this->update(
			'INSERT INTO osf_links (osf_url, article_id) VALUES (?, ?)',
			array(
				$osfLink->getOsfUrl(),
				$osfLink->getArticleId()
			)
		);
	}

	function updateOsfLink(&$osfLink) {
		return $this->update(
			'UPDATE osf_links SET osf_url = ? WHERE article_id = ?',
			array(
				$osfLink->getOsfUrl(),
				$osfLink->getArticleId()
			)
		);
	}
}
?>
