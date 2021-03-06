<?php

/**
 * @file plugins/generic/usageStats/UsageStatsSettingsForm.inc.php
 *
 * Copyright (c) 2003-2013 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class UsageStatsSettingsForm
 * @ingroup plugins_generic_usageStats
 *
 * @brief Form for journal managers to modify usage statistics plugin settings.
 */

import('lib.pkp.classes.form.Form');

class UsageStatsSettingsForm extends Form {

	/** @var object */
	var $plugin;

	/**
	 * Constructor
	 * @param $plugin object
	 */
	function UsageStatsSettingsForm(&$plugin) {
		$this->plugin =& $plugin;

		parent::Form($plugin->getTemplatePath() . 'usageStatsSettingsForm.tpl');
		$this->addCheck(new FormValidatorPost($this));
	}

	/**
	 * Initialize form data.
	 */
	function initData() {
		$plugin =& $this->plugin;

		$this->setData('createLogFiles', $plugin->getSetting(0, 'createLogFiles'));
		$this->setData('accessLogFileParseRegex', $plugin->getSetting(0, 'accessLogFileParseRegex'));
	}

	/**
	 * Assign form data to user-submitted data.
	 */
	function readInputData() {
		$this->readUserVars(array('createLogFiles','accessLogFileParseRegex'));
	}

	/**
	 * @see Form::fetch()
	 */
	function fetch($request) {
		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pluginName', $this->plugin->getName());
		return parent::fetch($request);
	}

	/**
	 * Save settings.
	 */
	function execute() {
		$plugin =& $this->plugin;

		$plugin->updateSetting(0, 'createLogFiles', $this->getData('createLogFiles'));
		$plugin->updateSetting(0, 'accessLogFileParseRegex', $this->getData('accessLogFileParseRegex'));
	}

}

?>
