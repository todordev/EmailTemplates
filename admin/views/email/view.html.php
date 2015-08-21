<?php
/**
 * @package      EmailTemplates
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

use \Joomla\String\String;

// no direct access
defined('_JEXEC') or die;

class EmailTemplatesViewEmail extends JViewLegacy
{
    /**
     * @var JDocumentHtml
     */
    public $document;

    /**
     * @var Joomla\Registry\Registry
     */
    protected $state;

    protected $item;
    protected $form;

    protected $option;
    protected $documentTitle;

    protected $categories;

    public function __construct($config)
    {
        parent::__construct($config);
        $this->option = JFactory::getApplication()->input->get("option");
    }

    public function display($tpl = null)
    {
        $this->state = $this->get('State');
        $this->item  = $this->get('Item');
        $this->form  = $this->get('Form');

        $this->categories = new \EmailTemplates\Categories;
        $this->categories->setDb(JFactory::getDbo());
        $this->categories->load(null, array("load_placeholders" => true));

        $this->categories = $this->categories->toArray();

        // Prepare actions, behaviors, scripts and document
        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    /**
     * Add the page title and toolbar.
     * @since   1.6
     */
    protected function addToolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);
        $isNew = ($this->item->id == 0);

        $this->documentTitle = $isNew ? JText::_('COM_EMAILTEMPLATES_ADD_EMAIL_TEMPLATE') : JText::_('COM_EMAILTEMPLATES_EDIT_EMAIL_TEMPLATE');

        JToolbarHelper::title($this->documentTitle);

        JToolbarHelper::apply('email.apply');
        JToolbarHelper::save2new('email.save2new');
        JToolbarHelper::save('email.save');

        JToolbarHelper::cancel('email.cancel', 'JTOOLBAR_CANCEL');
    }

    /**
     * Method to set up the document properties
     * @return void
     */
    protected function setDocument()
    {
        $this->document->setTitle($this->documentTitle);

        // Add behaviors
        JHtml::_('behavior.tooltip');
        JHtml::_('behavior.formvalidation');

        // Add scripts
        $this->document->addScript('../media/' . $this->option . '/js/admin/' . String::strtolower($this->getName()) . '.js');
    }
}
