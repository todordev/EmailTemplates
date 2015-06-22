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
    define("COM_EMAILTEMPLATES_PATH_LIBRARY", JPATH_LIBRARIES ."/EmailTemplates");
}

JLoader::registerNamespace('EmailTemplates', JPATH_LIBRARIES);

// Register helpers
JLoader::register("EmailTemplatesHelper", COM_EMAILTEMPLATES_PATH_COMPONENT_ADMINISTRATOR ."/helpers/emailtemplates.php");

// Load language.
$lang = JFactory::getLanguage();
$lang->load('lib_emailtemplates', COM_EMAILTEMPLATES_PATH_LIBRARY);
