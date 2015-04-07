<?php
/**
 * @package      EmailTemplates
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

jimport("emailtemplates.init");

class JFormFieldEmailTemplate extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     *
     * @since  11.1
     */
    protected $type = 'emailtemplate';

    /**
     * Method to get the field input markup.
     *
     * @return  string  The field input markup.
     *
     * @since   11.1
     */
    protected function getInput()
    {
        $html = array();
        $link = 'index.php?option=com_emailtemplates&amp;view=emails&amp;layout=modal&amp;tmpl=component&amp;field=' . $this->id;

        // Initialize some field attributes.
        $attr = !empty($this->class) ? ' class="' . $this->class . '"' : '';
        $attr .= !empty($this->size) ? ' size="' . $this->size . '"' : '';
        $attr .= $this->required ? ' required' : '';

        // Load the modal behavior script.
        JHtml::_('behavior.modal', 'a.modal_' . $this->id);

        // Build the script.
        $script   = array();
        $script[] = '	function jSelectEmailTemplate_' . $this->id . '(id, title) {';
        $script[] = '		var old_id = document.getElementById("' . $this->id . '_id").value;';
        $script[] = '		if (old_id != id) {';
        $script[] = '			document.getElementById("' . $this->id . '_id").value = id;';
        $script[] = '			document.getElementById("' . $this->id . '").value = title;';
        $script[] = '			document.getElementById("'
            . $this->id . '").className = document.getElementById("' . $this->id . '").className.replace(" invalid" , "");';
        $script[] = '			' . $this->onchange;
        $script[] = '		}';
        $script[] = '		jModalClose();';
        $script[] = '	}';

        // Add the script to the document head.
        JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));

        if (is_numeric($this->value) and !empty($this->value)) {
            $email = new EmailTemplates\Email();
            $email->setDb(JFactory::getDbo());
            $email->load($this->value);

            $title = $email->getTitle();
        } else {
            $title = JText::_('LIB_EMAILTEMPLATES_SELECT_EMAIL_TEMPLATE');
        }

        // Create a dummy text field with the email template title.
        $html[] = '<div class="input-append">';
        $html[] = '	<input type="text" id="' . $this->id . '" value="' . htmlspecialchars($title, ENT_COMPAT, 'UTF-8') . '" readonly' . $attr . ' />';

        // Create the user select button.
        if ($this->readonly === false) {
            $html[] = '		<a class="btn modal_' . $this->id . '" title="' . JText::_('LIB_EMAILTEMPLATES_CHANGE_EMAIL_TEMPLATE') . '" href="' . $link . '"'
                . ' rel="{handler: \'iframe\', size: {x: 800, y: 500}}">';
            $html[] = '<i class="icon-envelope"></i></a>';
        }

        $html[] = '</div>';

        // Create the real field, hidden, that stored the user id.
        $html[] = '<input type="hidden" id="' . $this->id . '_id" name="' . $this->name . '" value="' . $this->value . '" />';

        return implode("\n", $html);
    }
}
