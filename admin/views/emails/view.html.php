<?php
/**
 * @package      EmailTemplates
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

use Joomla\String\String;

// no direct access
defined('_JEXEC') or die;

class EmailTemplatesViewEmails extends JViewLegacy
{
    /**
     * @var JDocumentHtml
     */
    public $document;

    /**
     * @var Joomla\Registry\Registry
     */
    protected $state;

    protected $items;
    protected $pagination;

    protected $option;

    protected $listOrder;
    protected $listDirn;
    protected $saveOrder;
    protected $saveOrderingUrl;

    public $filterForm;

    protected $sidebar;

    public function __construct($config)
    {
        parent::__construct($config);
        $this->option = JFactory::getApplication()->input->get("option");
    }

    public function display($tpl = null)
    {
        $this->state      = $this->get('State');
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Prepare sorting data
        $this->prepareSorting();

        // Prepare actions
        $this->addToolbar();
        $this->addSidebar();
        $this->setDocument();

        parent::display($tpl);
    }

    /**
     * Prepare sortable fields, sort values and filters.
     */
    protected function prepareSorting()
    {
        // Prepare filters
        $this->listOrder = $this->escape($this->state->get('list.ordering'));
        $this->listDirn  = $this->escape($this->state->get('list.direction'));
        $this->saveOrder = (strcmp($this->listOrder, 'a.ordering') != 0) ? false : true;

        $this->filterForm    = $this->get('FilterForm');
    }

    /**
     * Prepare sidebar.
     */
    protected function addSidebar()
    {
        EmailTemplatesHelper::addSubmenu($this->getName());
        $this->sidebar = JHtmlSidebar::render();
    }

    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        // Set toolbar items for the page
        JToolbarHelper::title(JText::_('COM_EMAILTEMPLATES_EMAIL_TEMPLATES_MANAGER'));
        JToolbarHelper::addNew('email.add');
        JToolbarHelper::editList('email.edit');
        JToolbarHelper::divider();
        JToolbarHelper::deleteList(JText::_("COM_EMAILTEMPLATES_DELETE_ITEMS_QUESTION"), "emails.delete");
        JToolbarHelper::divider();
        JToolbarHelper::custom('emails.backToDashboard', "dashboard", "", JText::_("COM_EMAILTEMPLATES_DASHBOARD"), false);
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $this->document->setTitle(JText::_('COM_EMAILTEMPLATES_EMAIL_TEMPLATES_MANAGER'));

        // Scripts
        JHtml::_('jquery.framework');
        JHtml::_('behavior.multiselect');
        JHtml::_('bootstrap.tooltip');

        JHtml::_('formbehavior.chosen', 'select');

        if ($this->getLayout() == "modal") {
            $this->document->addScript('../media/' . $this->option . '/js/admin/emails_modal.js');
        }
    }
}
