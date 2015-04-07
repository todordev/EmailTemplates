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
 * It is EmailTemplates helper class
 */
class EmailTemplatesHelper
{
    protected static $extension = "com_emailtemplates";

    /**
     * Configure the Linkbar.
     *
     * @param    string  $vName  The name of the active view.
     *
     * @since    1.6
     */
    public static function addSubmenu($vName = 'dashboard')
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_EMAILTEMPLATES_DASHBOARD'),
            'index.php?option=' . self::$extension . '&view=dashboard',
            $vName == 'dashboard'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_EMAILTEMPLATES_CATEGORIES'),
            'index.php?option=com_categories&extension=' . self::$extension . '',
            $vName == 'categories'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_EMAILTEMPLATES_EMAIL_TEMPLATES'),
            'index.php?option=' . self::$extension . '&view=emails',
            $vName == 'emails'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_EMAILTEMPLATES_PLACEHOLDERS'),
            'index.php?option=' . self::$extension . '&view=placeholders',
            $vName == 'placeholders'
        );
    }
}
