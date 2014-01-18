<?php

import('classes.plugins.GenericPlugin');


class OsfPlugin extends GenericPlugin {
	function register($category, $path) {
		$success = parent::register($category, $path);
		if ($success && $this->getEnabled()) {

			// Add field to step3 of the submission form
            HookRegistry::register( 
                'Templates::Author::Submit::AdditionalMetadata', 
                array(&$this, 'metadataFormCallback') 
            ); 

            // Handle submission of added field in step3
            HookRegistry::register(
            	'SubmitHandler::saveSubmit',
            	array(&$this, 'handleSave')
            );

            // Handle page requests
            HookRegistry::register('LoadHandler', array(&$this, 'callbackHandleContent'));

            // Load OsfLinksDAO
			$this->import('OsfLinksDAO');
			$osfLinksDAO = new OsfLinksDAO($this->getName());
            DAORegistry::registerDAO('OsfLinksDAO', $osfLinksDAO);

            return true;
        } 

        return $success; 
	}

	function getName() {
		return 'OsfPlugin';
	}

	function getDisplayName() {
		return 'Osf Plugin';
	}

	function getDescription() {
		return 'OSF Plugin Description';
	}

	function callbackHandleContent($hookName, $args) {
		$path =& $args[0];
		$op =& $args[1];

		if ($path == 'api') {
			define('HANDLER_CLASS', 'ApiHandler');
			$this->import('ApiHandler');
			return true;
		}
		return false;
	}



	function handleSave($hookName, $args) {
		$step = $args[0];
		$article =& $args[1];
		$submitForm =& $args[2];

		if ($step == 3) {

			$submitForm->readUserVars(array('osfproject'));
			$osfProjectLink = $submitForm->getData('osfproject');
			//TODO: Validate that this is in fact a link.

			
			
			$this->import('OsfLink');
			$osfLink = new OsfLink();
			$osfLink->setArticleId($article->getData('id'));
			$osfLink->setOsfUrl($osfProjectLink);

			$OsfLinksDAO =& DAORegistry::getDAO('OsfLinksDAO');
			$OsfLinksDAO->insertOrUpdateOsfLink($osfLink);

		}
		return false;
	}

	function metadataFormCallback($hookName, $args) {
		$params =& $args[0];
		$templateManager =& $args[1];
		$output =& $args[2];

		$OsfLinksDAO =& DAORegistry::getDAO('OsfLinksDAO');
		$osfLink = $OsfLinksDAO->getOsfLinkByArticleId($templateManager->get_template_vars()['articleId']);


		$output = '<div id="submissionSupportingAgencies">';
		$output .= '<h3>OSF Project</h3>';
		$output .= '<p>The Open Science Framework is a ....</p>';
		$output .= '<table width="100%" class="data">';
		$output .= '<tr valign="top">';
		$output .= '<td width="20%" class="label">';
		$output .= '<label for="sponsor" >';
		$output .= 'Link </label>';
		$output .= '</td>';
		$output .= '<td width="80%" class="value"><input type="text" class="textField" name="osfproject" id="osfproject" value="';
		$output .= $osfLink === null ? '' : $osfLink->getOsfUrl();
		$output .= '" size="60" maxlength="255" /></td>';
		$output .= '</tr>';
		$output .= '</table>';
		$output .= '</div>';
		$output .= '<div class="separator"></div>';

		return false;
	}

	/**
	 * Get the filename of the ADODB schema for this plugin.
	 */
	function getInstallSchemaFile() {
		return $this->getPluginPath() . '/' . 'schema.xml';
	}
}

?>