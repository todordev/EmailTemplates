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

/**
 * EmailTemplates placeholder controller
 *
 * @package     EmailTemplates
 * @subpackage  Components
 */
class EmailTemplatesControllerPlaceholders extends JControllerAdmin
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
    public function getModel($name = 'Placeholder', $prefix = 'EmailTemplatesModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);

        return $model;
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
        /** @var $model EmailTemplatesModelPlaceholder */

        // Check for selected packages.
        if (!$itemsIds) {
            $response
                ->setTitle(JText::_("COM_EMAILTEMPLATES_FAIL"))
                ->setText(JText::_("COM_EMAILTEMPLATES_PLACEHOLDERS_NOT_SELECTED"))
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

                    $model->copyPlaceholders($itemsIds, $categoryId);

                    $response
                        ->setTitle(JText::_("COM_EMAILTEMPLATES_SUCCESS"))
                        ->setText(JText::_("COM_EMAILTEMPLATES_PLACEHOLDERS_COPIED_SUCCESSFULLY"))
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
