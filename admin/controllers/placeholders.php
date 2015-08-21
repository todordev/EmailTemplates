<?php
/**
 * @package      EmailTemplates
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

use Prism\Controller\Admin;

/**
 * EmailTemplates comments controller class
 *
 * @package     EmailTemplates
 * @subpackage  Components
 */
class EmailTemplatesControllerPlaceholders extends Admin
{
    /**
     * Proxy for getModel.
     * @since   1.6
     */
    public function getModel($name = 'Placeholder', $prefix = 'EmailTemplatesModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }
}
