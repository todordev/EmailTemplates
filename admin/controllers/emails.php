<?php
/**
 * @package      EmailTemplates
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// No direct access
defined('_JEXEC') or die;

use Prism\Controller\Admin;

/**
 * EmailTemplates emails controller class
 *
 * @package     EmailTemplates
 * @subpackage  Components
 */
class EmailTemplatesControllerEmails extends Admin
{
    public function getModel($name = 'Email', $prefix = 'EmailTemplatesModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }
}
