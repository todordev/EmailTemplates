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

class EmailTemplatesViewDashboard extends JViewLegacy
{
    /**
     * @var JDocumentHtml
     */
    public $document;

    public $latest;
    public $popular;
    public $mostVoted;

    protected $option;

    protected $totalItems;
    protected $totalVotes;
    protected $totalComments;

    protected $version;
    protected $prismVersion;
    protected $prismVersionLowerMessage;

    protected $sidebar;

    public function display($tpl = null)
    {
        $this->option = JFactory::getApplication()->input->get('option');
        
        $this->version = new Emailtemplates\Version;

        // Load ITPrism library version
        if (!class_exists('\\Prism\\Version')) {
            $this->prismVersion = JText::_('COM_EMAILTEMPLATES_PRISM_LIBRARY_DOWNLOAD');
        } else {
            $prismVersion       = new Prism\Version();
            $this->prismVersion = $prismVersion->getShortVersion();

            if (version_compare($this->prismVersion, $this->version->requiredPrismVersion, '<')) {
                $this->prismVersionLowerMessage = JText::_('COM_EMAILTEMPLATES_PRISM_LIBRARY_LOWER_VERSION');
            }
        }

        // Add submenu
        EmailTemplatesHelper::addSubmenu($this->getName());

        $this->addToolbar();
        $this->addSidebar();
        $this->setDocument();

        parent::display($tpl);
    }

    /**
     * Add a menu on the sidebar of page
     */
    protected function addSidebar()
    {
        $this->sidebar = JHtmlSidebar::render();
    }

    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        JToolbarHelper::title(JText::_("COM_EMAILTEMPLATES_DASHBOARD"));

        JToolbarHelper::divider();

        // Help button
        $bar = JToolBar::getInstance('toolbar');
        $bar->appendButton('Link', 'help', JText::_('JHELP'), JText::_('COM_EMAILTEMPLATES_HELP_URL'));
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $this->document->setTitle(JText::_('COM_EMAILTEMPLATES_DASHBOARD'));
    }
}
