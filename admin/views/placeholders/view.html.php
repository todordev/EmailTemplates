<?php
/**
 * @package      EmailTemplates
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class EmailTemplatesViewPlaceholders extends JViewLegacy
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

    protected $sidebar;

    public $filterForm;
    public $activeFilters;

    public function display($tpl = null)
    {
        $this->option = JFactory::getApplication()->input->get('option');
        
        $this->state      = $this->get('State');
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Prepare filters and sorting.
        $this->prepareSorting();

        // Prepare sidebar.
        $this->prepareSidebar();

        // Prepare the document.
        $this->addToolbar();
        $this->setDocument();

        parent::display($tpl);
    }

    /**
     * Prepare sortable fields, sort values and filters.
     */
    protected function prepareSorting()
    {
        $this->listOrder = $this->escape($this->state->get('list.ordering'));
        $this->listDirn  = $this->escape($this->state->get('list.direction'));
        $this->saveOrder = (strcmp($this->listOrder, 'a.ordering') === 0);

        $this->filterForm    = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');
    }

    /**
     * Prepare sidebar.
     */
    protected function prepareSidebar()
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
        JToolbarHelper::title(JText::_('COM_EMAILTEMPLATES_PLACEHOLDER_MANAGER'));
        JToolbarHelper::addNew('placeholder.add');
        JToolbarHelper::editList('placeholder.edit');
        JToolbarHelper::divider();

        // Add custom buttons
        $bar = JToolbar::getInstance('toolbar');
        
        // Import
        $link = JRoute::_('index.php?option=com_emailtemplates&view=import&type=placeholders');
        $bar->appendButton('Link', 'upload', JText::_('COM_EMAILTEMPLATES_IMPORT'), $link);

        JToolbarHelper::custom('export.placeholders', 'download', '', JText::_('COM_EMAILTEMPLATES_EXPORT'));
        
        JToolbarHelper::divider();
        JToolbarHelper::deleteList(JText::_('COM_EMAILTEMPLATES_DELETE_ITEMS_QUESTION'), 'placeholders.delete');
        JToolbarHelper::divider();

        $layoutData = array(
            'title' => JText::_('JTOOLBAR_BATCH')
        );

        // Instantiate a new JLayoutFile instance and render the batch button
        $layout = new JLayoutFile('joomla.toolbar.batch');
        $html = $layout->render($layoutData);
        $bar->appendButton('Custom', $html, 'batch');

        JToolbarHelper::divider();
        JToolbarHelper::custom('placeholders.backToDashboard', 'dashboard', '', JText::_('COM_EMAILTEMPLATES_DASHBOARD'), false);
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $this->document->setTitle(JText::_('COM_EMAILTEMPLATES_PLACEHOLDER_MANAGER'));

        // Load language string in JavaScript
        JText::script('COM_EMAILTEMPLATES_PLACEHOLDERS_NOT_SELECTED');

        // Scripts
        JHtml::_('behavior.multiselect');
        JHtml::_('bootstrap.tooltip');

        JHtml::_('formbehavior.chosen', 'select');

        JHtml::_('Prism.ui.pnotify');
        JHtml::_('Prism.ui.joomlaHelper');

        $this->document->addScript('../media/' . $this->option . '/js/admin/' . JString::strtolower($this->getName()) . '.js');
    }
}
