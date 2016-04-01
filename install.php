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

/**
 * Script file of the component
 */
class pkg_emailTemplatesInstallerScript
{
    /**
     * Method to install the component.
     *
     * @param string $parent
     *
     * @return void
     */
    public function install($parent)
    {
    }

    /**
     * Method to uninstall the component.
     *
     * @param string $parent
     *
     * @return void
     */
    public function uninstall($parent)
    {
    }

    /**
     * Method to update the component.
     *
     * @param string $parent
     *
     * @return void
     */
    public function update($parent)
    {
    }

    /**
     * Method to run before an install/update/uninstall method.
     *
     * @param string $type
     * @param string $parent
     *
     * @return void
     */
    public function preflight($type, $parent)
    {
    }

    /**
     * Method to run after an install/update/uninstall method.
     *
     * @param string $type
     * @param string $parent
     *
     * @return void
     */
    public function postflight($type, $parent)
    {
        if (!defined('EMAILTEMPLATES_PATH_COMPONENT_ADMINISTRATOR')) {
            define('EMAILTEMPLATES_PATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR . '/components/com_emailtemplates');
        }

        // Register Component helpers
        JLoader::register('EmailTemplatesInstallHelper', EMAILTEMPLATES_PATH_COMPONENT_ADMINISTRATOR . '/helpers/install.php');

        jimport('Prism.init');
        jimport('Emailtemplates.init');

        // Start table with the information
        EmailTemplatesInstallHelper::startTable();

        // Requirements
        EmailTemplatesInstallHelper::addRowHeading(JText::_('COM_EMAILTEMPLATES_MINIMUM_REQUIREMENTS'));

        // Display result about verification Magic Quotes
        $title = JText::_('COM_EMAILTEMPLATES_MAGIC_QUOTES');
        $info  = '';
        if (get_magic_quotes_gpc()) {
            $info   = JText::_('COM_EMAILTEMPLATES_MAGIC_QUOTES_INFO');
            $result = array('type' => 'important', 'text' => JText::_('JON'));
        } else {
            $result = array('type' => 'success', 'text' => JText::_('JOFF'));
        }
        EmailTemplatesInstallHelper::addRow($title, $result, $info);

        // Display result about PHP version.
        $title = JText::_('COM_EMAILTEMPLATES_PHP_VERSION');
        $info  = '';
        if (version_compare(PHP_VERSION, '5.4.0') < 0) {
            $result = array('type' => 'important', 'text' => JText::_('COM_EMAILTEMPLATES_WARNING'));
        } else {
            $result = array('type' => 'success', 'text' => JText::_('JYES'));
        }
        EmailTemplatesInstallHelper::addRow($title, $result, $info);

        // Display result about MySQL Version.
        $title = JText::_('COM_EMAILTEMPLATES_MYSQL_VERSION');
        $info  = '';
        $dbVersion = JFactory::getDbo()->getVersion();
        if (version_compare($dbVersion, '5.5.3', '<')) {
            $result = array('type' => 'important', 'text' => JText::_('COM_EMAILTEMPLATES_WARNING'));
        } else {
            $result = array('type' => 'success', 'text' => JText::_('JYES'));
        }
        EmailTemplatesInstallHelper::addRow($title, $result, $info);

        // Display result about verification of installed Prism Library
        jimport('Prism.version');
        $title = JText::_('COM_EMAILTEMPLATES_PRISM_LIBRARY');
        $info  = '';
        if (!class_exists('Prism\\Version')) {
            $info   = JText::_('COM_EMAILTEMPLATES_PRISM_LIBRARY_DOWNLOAD');
            $result = array('type' => 'important', 'text' => JText::_('JNO'));
        } else {
            $result = array('type' => 'success', 'text' => JText::_('JYES'));
        }
        EmailTemplatesInstallHelper::addRow($title, $result, $info);

        // End table
        EmailTemplatesInstallHelper::endTable();

        echo JText::sprintf('COM_EMAILTEMPLATES_MESSAGE_REVIEW_SAVE_SETTINGS', JRoute::_('index.php?option=com_emailtemplates'));

        if (!class_exists('Prism\\Version')) {
            echo JText::_('COM_EMAILTEMPLATES_MESSAGE_INSTALL_PRISM_LIBRARY');
        } else {

            if (class_exists('Crowdfunding\\Version')) {
                $prismVersion     = new Prism\Version();
                $componentVersion = new Emailtemplates\Version();
                if (version_compare($prismVersion->getShortVersion(), $componentVersion->requiredPrismVersion, '<')) {
                    echo JText::_('COM_EMAILTEMPLATES_MESSAGE_INSTALL_PRISM_LIBRARY');
                }
            }
        }
    }
}
