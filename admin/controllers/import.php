<?php
/**
 * @package      EmailTemplates
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class EmailTemplatesControllerImport extends Prism\Controller\Form\Backend
{
    public function getModel($name = 'Import', $prefix = 'EmailTemplatesModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

    public function placeholders()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $data = $this->input->post->get('jform', array(), 'array');
        $file = $this->input->files->get('jform', array(), 'array');
        $data = array_merge($data, $file);

        $redirectOptions = array(
            'view' => 'placeholders',
        );

        $model = $this->getModel();
        /** @var $model EmailTemplatesModelImport */

        $form = $model->getForm($data, false);
        /** @var $form JForm */

        if (!$form) {
            throw new Exception(JText::_('COM_EMAILTEMPLATES_ERROR_FORM_CANNOT_BE_LOADED'));
        }

        // Validate the form
        $validData = $model->validate($form, $data);

        // Check for errors.
        if ($validData === false) {
            $this->displayNotice($form->getErrors(), $redirectOptions);

            return;
        }

        $fileData = Joomla\Utilities\ArrayHelper::getValue($data, 'data');
        if (!$fileData or empty($fileData['name'])) {
            $this->displayNotice(JText::_('COM_EMAILTEMPLATES_ERROR_FILE_CANT_BE_UPLOADED'), $redirectOptions);
            return;
        }

        try {

            $filePath = $model->uploadFile($fileData, 'placeholders');

            $categoryId = Joomla\Utilities\ArrayHelper::getValue($data, 'catid', 0, 'int');

            $model->importPlaceholders($filePath, $categoryId);

        } catch (Exception $e) {
            JLog::add($e->getMessage());
            throw new Exception(JText::_('COM_EMAILTEMPLATES_ERROR_SYSTEM'));
        }

        $this->displayMessage(JText::_('COM_EMAILTEMPLATES_PLACEHOLDERS_IMPORTED'), $redirectOptions);
    }

    public function emails()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $data = $this->input->post->get('jform', array(), 'array');
        $file = $this->input->files->get('jform', array(), 'array');
        $data = array_merge($data, $file);

        $redirectOptions = array(
            'view' => 'emails',
        );

        $model = $this->getModel();
        /** @var $model EmailTemplatesModelImport */

        $form = $model->getForm($data, false);
        /** @var $form JForm */

        if (!$form) {
            throw new Exception(JText::_('COM_EMAILTEMPLATES_ERROR_FORM_CANNOT_BE_LOADED'));
        }

        // Validate the form
        $validData = $model->validate($form, $data);

        // Check for errors.
        if ($validData === false) {
            $this->displayNotice($form->getErrors(), $redirectOptions);

            return;
        }

        $fileData = Joomla\Utilities\ArrayHelper::getValue($data, 'data');
        if (!$fileData or empty($fileData['name'])) {
            $this->displayNotice(JText::_('COM_EMAILTEMPLATES_ERROR_FILE_CANT_BE_UPLOADED'), $redirectOptions);
            return;
        }

        try {

            $filePath = $model->uploadFile($fileData, 'emails');

            $categoryId = Joomla\Utilities\ArrayHelper::getValue($data, 'catid', 0, 'int');

            $model->importEmails($filePath, $categoryId);

        } catch (Exception $e) {
            JLog::add($e->getMessage());
            throw new Exception(JText::_('COM_EMAILTEMPLATES_ERROR_SYSTEM'));
        }

        $this->displayMessage(JText::_('COM_EMAILTEMPLATES_EMAILS_IMPORTED'), $redirectOptions);

    }

    public function cancel($key = null)
    {
        $app = JFactory::getApplication();
        /** @var $app JApplicationAdministrator */

        $view = $app->getUserState('import.context', 'emails');

        $link = $this->defaultLink . '&view=' . $view;
        $this->setRedirect(JRoute::_($link, false));
    }
}
