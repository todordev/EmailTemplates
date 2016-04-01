<?php
/**
 * @package      EmailTemplates
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

class EmailTemplatesViewImport extends JViewLegacy
{
    /**
     * @var JDocumentHtml
     */
    public $document;

    /**
     * @var Joomla\Registry\Registry
     */
    protected $state;

    protected $form;

    protected $option;

    protected $importType;
    protected $legend;
    protected $uploadTask;

    public function display($tpl = null)
    {
        $this->option = JFactory::getApplication()->input->get('option');
        
        $this->state = $this->get('State');
        $this->form  = $this->get('Form');

        $this->importType = $this->state->get('import.context');

        switch ($this->importType) {
            case 'placeholders':
                $this->legend     = JText::_('COM_EMAILTEMPLATES_IMPORT_PLACEHOLDERS');
                $this->uploadTask = 'import.placeholders';
                break;

            default: // Emails
                $this->legend     = JText::_('COM_EMAILTEMPLATES_IMPORT_EMAILS');
                $this->uploadTask = 'import.emails';
                break;
        }

        // Add submenu
        EmailTemplatesHelper::addSubmenu($this->importType);

        // Prepare actions
        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        // Set toolbar items for the page
        JToolbarHelper::title(JText::_('COM_EMAILTEMPLATES_IMPORT_MANAGER'));

        // Upload
        JToolbarHelper::custom($this->uploadTask, 'upload', '', JText::_('COM_EMAILTEMPLATES_UPLOAD'), false);

        JToolbarHelper::divider();
        JToolbarHelper::cancel('import.cancel', 'JTOOLBAR_CANCEL');
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $this->document->setTitle(JText::_('COM_EMAILTEMPLATES_IMPORT_MANAGER'));

        // Scripts
        JHtml::_('behavior.formvalidation');

        JHtml::_('bootstrap.tooltip');
        JHtml::_('Prism.ui.bootstrap2FileInput');

        $this->document->addScript('../media/' . $this->option . '/js/admin/' . JString::strtolower($this->getName()) . '.js');
    }
}
