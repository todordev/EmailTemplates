<?php
/**
 * @package      EmailTemplates
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class EmailTemplatesTablePlaceholder extends JTable
{
    public function __construct(JDatabaseDriver $db)
    {
        parent::__construct('#__emailtemplates_placeholders', 'id', $db);
    }
}
