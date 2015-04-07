<?php
/**
 * @package      EmailTemplates
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('_JEXEC') or die;

class EmailTemplatesTableEmail extends JTable
{
    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__emailtemplates_emails', 'id', $db);
    }
}
