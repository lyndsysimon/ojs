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

            HookRegistry::register(
            	'SubmitHandler::saveSubmit',
            	array(&$this, 'handleSave')
            );
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

	function handleSave($step, &$article, &$submitForm) {
		die('yay!');
		return false;
	}

	function metadataFormCallback($hookName, $args) {
		$params =& $args[0];
		$templateManager =& $args[1];
		$output =& $args[2];

		$output = '<div id="submissionSupportingAgencies">';
		$output .= '<h3>OSF Project</h3>';
		$output .= '<p>The Open Science Framework is a ....</p>';
		$output .= '<table width="100%" class="data">';
		$output .= '<tr valign="top">';
		$output .= '<td width="20%" class="label">';
		$output .= '<label for="sponsor" >';
		$output .= 'Link </label>';
		$output .= '</td>';
		$output .= '<td width="80%" class="value"><input type="text" class="textField" name="sponsor[en_US]" id="sponsor" value="" size="60" maxlength="255" /></td>';
		$output .= '</tr>';
		$output .= '</table>';
		$output .= '</div>';
		$output .= '<div class="separator"></div>';
		return false;
	}
}

?>