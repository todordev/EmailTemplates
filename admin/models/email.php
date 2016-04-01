<?php
/**
 * @package      EmailTemplates
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

use Joomla\Utilities\ArrayHelper;

// no direct access
defined('_JEXEC') or die;

class EmailTemplatesModelEmail extends JModelAdmin
{
    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param   string $type   The table type to instantiate
     * @param   string $prefix A prefix for the table class name. Optional.
     * @param   array  $config Configuration array for model. Optional.
     *
     * @return  JTable  A database object
     * @since   1.6
     */
    public function getTable($type = 'Email', $prefix = 'EmailTemplatesTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param   array   $data     An optional array of data for the form to interrogate.
     * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return  JForm   A JForm object on success, false on failure
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm($this->option . '.email', 'email', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed   The data for the form.
     * @since   1.6
     */
    protected function loadFormData()
    {
        $app = JFactory::getApplication();

        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.email.data', array());

        if (empty($data)) {
            $data = $this->getItem();

            // Prime some default values.
            if ((int)$this->getState('email.id') === 0) {
                $filters = (array) $app->getUserState('com_emailtemplates.emails.filter');
                $filterCatId = array_key_exists('category_id', $filters) ? (int)$filters['category_id'] : null;

                $data->set('catid', $app->input->getInt('catid', $filterCatId));
            }
        }

        return $data;
    }

    /**
     * Save data into the DB.
     *
     * @param array $data
     *
     * @return  int
     */
    public function save($data)
    {
        $id          = ArrayHelper::getValue($data, 'id', 0, 'int');
        $title       = ArrayHelper::getValue($data, 'title');
        $subject     = ArrayHelper::getValue($data, 'subject');
        $senderName  = ArrayHelper::getValue($data, 'sender_name');
        $senderEmail = ArrayHelper::getValue($data, 'sender_email');
        $body        = ArrayHelper::getValue($data, 'body');
        $categoryId  = ArrayHelper::getValue($data, 'catid', 0, 'int');

        // Load a record from the database
        $row = $this->getTable();
        /** @var $row EmailTemplatesTableEmail */

        $row->load($id);

        $row->set('title', $title);
        $row->set('subject', $subject);
        $row->set('sender_name', $senderName);
        $row->set('sender_email', $senderEmail);
        $row->set('body', $body);
        $row->set('catid', $categoryId);

        $this->prepareTable($row);

        $row->store(true);

        return $row->get('id');
    }

    /**
     * Prepare and sanitise the table prior to saving.
     *
     * @param EmailTemplatesTableEmail $table
     *
     * @since    1.6
     */
    protected function prepareTable($table)
    {
        if (!$table->get('sender_name')) {
            $table->set('sender_name', null);
        }

        if (!$table->get('sender_email')) {
            $table->set('sender_email', null);
        }
    }

    public function copyEmails(array $ids, $categoryId)
    {
        foreach ($ids as $id) {

            if ((int)$id > 0) {
                $table = $this->getTable();
                $table->load($id);

                if ($table->get('id')) {
                    $table->set('id', null);
                    $table->set('catid', (int)$categoryId);

                    $table->store();
                }
            }
        }

    }
}
