<?php
/**
 * @package      EmailTemplates
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
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
        if (!defined("COM_EMAILTEMPLATES_PATH_COMPONENT_ADMINISTRATOR")) {
            define("COM_EMAILTEMPLATES_PATH_COMPONENT_ADMINISTRATOR", JPATH_ADMINISTRATOR . "/components/com_emailtemplates");
        }

        // Register Component helpers
        JLoader::register("EmailTemplatesInstallHelper", COM_EMAILTEMPLATES_PATH_COMPONENT_ADMINISTRATOR . "/helpers/install.php");

        // Start table with the information
        EmailTemplatesInstallHelper::startTable();

        // Requirements
        EmailTemplatesInstallHelper::addRowHeading(JText::_("COM_EMAILTEMPLATES_MINIMUM_REQUIREMENTS"));

        // Display result about verification Magic Quotes
        $title = JText::_("COM_EMAILTEMPLATES_MAGIC_QUOTES");
        $info  = "";
        if (get_magic_quotes_gpc()) {
            $info   = JText::_("COM_EMAILTEMPLATES_MAGIC_QUOTES_INFO");
            $result = array("type" => "important", "text" => JText::_("JON"));
        } else {
            $result = array("type" => "success", "text" => JText::_("JOFF"));
        }
        EmailTemplatesInstallHelper::addRow($title, $result, $info);

        // Display result about PHP version.
        $title = JText::_("COM_EMAILTEMPLATES_PHP_VERSION");
        $info  = "";
        if (version_compare(PHP_VERSION, '5.3.0') < 0) {
            $result = array("type" => "important", "text" => JText::_("COM_EMAILTEMPLATES_WARNING"));
        } else {
            $result = array("type" => "success", "text" => JText::_("JYES"));
        }
        EmailTemplatesInstallHelper::addRow($title, $result, $info);

        // Display result about verification of installed Prism Library
        jimport("itprism.version");
        $title = JText::_("COM_EMAILTEMPLATES_PRISM_LIBRARY");
        $info  = "";
        if (!class_exists("ITPrismVersion")) {
            $info   = JText::_("COM_EMAILTEMPLATES_PRISM_LIBRARY_DOWNLOAD");
            $result = array("type" => "important", "text" => JText::_("JNO"));
        } else {
            $result = array("type" => "success", "text" => JText::_("JYES"));
        }
        EmailTemplatesInstallHelper::addRow($title, $result, $info);

        // End table
        EmailTemplatesInstallHelper::endTable();

        echo JText::sprintf("COM_EMAILTEMPLATES_MESSAGE_REVIEW_SAVE_SETTINGS", JRoute::_("index.php?option=com_emailtemplates"));

        jimport("prism.version");
        if (!class_exists("\\Prism\\Version")) {
            echo JText::_("COM_EMAILTEMPLATES_MESSAGE_INSTALL_PRISM_LIBRARY");
        }
    }
}
