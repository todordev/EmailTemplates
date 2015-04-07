<?php
/**
 * @package      EmailTemplates
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

use Prism\Response\Json;
use EmailTemplates\Emails;

/**
 * EmailTemplates emails raw controller
 *
 * @package     EmailTemplates
 * @subpackage  Components
 */
class EmailTemplatesControllerEmails extends JControllerAdmin
{
    public function getEmailOptions()
    {
        // Create response object
        $response = new Json();

        $categoryId = $this->input->getInt("id");

        try {

            $emails = new Emails(JFactory::getDbo());
            $emails->load(array("category_id" => $categoryId));

            $response
                ->setTitle(JText::_('COM_EMAILTEMPLATES_SUCCESS'))
                ->setData($emails->toOptions())
                ->success();

            echo $response;
            JFactory::getApplication()->close();

        } catch (Exception $e) {
            JLog::add($e->getMessage());
            $response
                ->setTitle(JText::_('COM_EMAILTEMPLATES_FAIL'))
                ->setText(JText::_('COM_EMAILTEMPLATES_ERROR_SYSTEM'))
                ->failure();

            echo $response;
            JFactory::getApplication()->close();
        }

    }
}
