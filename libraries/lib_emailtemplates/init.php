<?php
/**
 * @package      EmailTemplates
 * @subpackage   Library
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

if (!defined('COM_EMAILTEMPLATES_PATH_COMPONENT_ADMINISTRATOR')) {
    define('COM_EMAILTEMPLATES_PATH_COMPONENT_ADMINISTRATOR', JPATH_ADMINISTRATOR .'/components/com_emailtemplates');
}

if (!defined('COM_EMAILTEMPLATES_PATH_LIBRARY')) {
    define('COM_EMAILTEMPLATES_PATH_LIBRARY', JPATH_LIBRARIES .'/Emailtemplates');
}

JLoader::registerNamespace('Emailtemplates', JPATH_LIBRARIES);

// Register helpers
JLoader::register('EmailTemplatesHelper', COM_EMAILTEMPLATES_PATH_COMPONENT_ADMINISTRATOR .'/helpers/emailtemplates.php');

// Load language.
$lang = JFactory::getLanguage();
$lang->load('lib_emailtemplates', COM_EMAILTEMPLATES_PATH_LIBRARY);
