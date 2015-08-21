<?php
/**
 * @package      EmailTemplates
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
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
    /**
     * Method to get a model object, loading it if required.
     *
     * @param    string $name   The model name. Optional.
     * @param    string $prefix The class prefix. Optional.
     * @param    array  $config Configuration array for model. Optional.
     *
     * @return    object    The model.
     * @since    1.5
     */
    public function getModel($name = 'Email', $prefix = 'EmailTemplatesModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
    }

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

    public function batch()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $response = new Prism\Response\Json();

        // Get the input
        $itemsIds  = $this->input->post->getString('ids');
        $itemsIds  = explode(",", $itemsIds);

        Joomla\Utilities\ArrayHelper::toInteger($itemsIds);

        $action    = $this->input->post->get('action');

        // Get the model
        $model = $this->getModel();
        /** @var $model EmailTemplatesModelEmail */

        // Check for selected packages.
        if (!$itemsIds) {
            $response
                ->setTitle(JText::_("COM_EMAILTEMPLATES_FAIL"))
                ->setText(JText::_("COM_EMAILTEMPLATES_EMAILS_NOT_SELECTED"))
                ->failure();

            echo $response;
            JFactory::getApplication()->close();
        }

        try {

            switch ($action) {
                case "copy":

                    $categoryId  = $this->input->post->get('catid');

                    // Check for valid category.
                    if (!$categoryId) {
                        $response
                            ->setTitle(JText::_("COM_EMAILTEMPLATES_FAIL"))
                            ->setText(JText::_("COM_EMAILTEMPLATES_CATEGORY_NOT_SELECTED"))
                            ->failure();

                        echo $response;
                        JFactory::getApplication()->close();
                    }

                    $model->copyEmails($itemsIds, $categoryId);

                    $response
                        ->setTitle(JText::_("COM_EMAILTEMPLATES_SUCCESS"))
                        ->setText(JText::_("COM_EMAILTEMPLATES_EMAILS_COPIED_SUCCESSFULLY"))
                        ->success();

                    break;

            }

        } catch (Exception $e) {
            JLog::add($e->getMessage());
            throw new Exception(JText::_('COM_EMAILTEMPLATES_ERROR_SYSTEM'));
        }

        echo $response;
        JFactory::getApplication()->close();
    }
}
