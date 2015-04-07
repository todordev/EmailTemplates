<?php
/**
 * @package      EmailTemplates
 * @subpackage   Library
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

if (!defined("COM_EMAILTEMPLATES_PATH_COMPONENT_ADMINISTRATOR")) {
    define("COM_EMAILTEMPLATES_PATH_COMPONENT_ADMINISTRATOR", JPATH_ADMINISTRATOR ."/components/com_emailtemplates");
}

if (!defined("COM_EMAILTEMPLATES_PATH_LIBRARY")) {
    define("COM_EMAILTEMPLATES_PATH_LIBRARY", JPATH_LIBRARIES ."/emailtemplates");
}

// Register libraries
JLoader::register("EmailTemplates\\Categories", COM_EMAILTEMPLATES_PATH_LIBRARY ."/categories.php");
JLoader::register("EmailTemplates\\Category", COM_EMAILTEMPLATES_PATH_LIBRARY ."/category.php");
JLoader::register("EmailTemplates\\Email", COM_EMAILTEMPLATES_PATH_LIBRARY ."/email.php");
JLoader::register("EmailTemplates\\Emails", COM_EMAILTEMPLATES_PATH_LIBRARY ."/emails.php");
JLoader::register("EmailTemplates\\Placeholder", COM_EMAILTEMPLATES_PATH_LIBRARY ."/placeholder.php");
JLoader::register("EmailTemplates\\Placeholders", COM_EMAILTEMPLATES_PATH_LIBRARY ."/placeholders.php");
JLoader::register("EmailTemplates\\Version", COM_EMAILTEMPLATES_PATH_LIBRARY ."/version.php");

// Register helpers
JLoader::register("EmailTemplatesHelper", COM_EMAILTEMPLATES_PATH_COMPONENT_ADMINISTRATOR ."/helpers/emailtemplates.php");

// Load language.
$lang = JFactory::getLanguage();
$lang->load('lib_emailtemplates', COM_EMAILTEMPLATES_PATH_LIBRARY);
