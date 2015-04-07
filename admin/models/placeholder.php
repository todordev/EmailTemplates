<?php
/**
 * @package      EmailTemplates
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

use Joomla\Utilities\ArrayHelper;

// no direct access
defined('_JEXEC') or die;

class EmailTemplatesModelPlaceholder extends JModelAdmin
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
    public function getTable($type = 'Placeholder', $prefix = 'EmailTemplatesTable', $config = array())
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
        $form = $this->loadForm($this->option . '.placeholder', 'placeholder', array('control' => 'jform', 'load_data' => $loadData));
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
        $data = $app->getUserState($this->option . '.edit.placeholder.data', array());

        if (empty($data)) {
            $data = $this->getItem();

            // Prime some default values.
            if ($this->getState('placeholder.id') == 0) {
                $filters = (array) $app->getUserState('com_emailtemplates.placeholders.filter');
                $filterCatId = isset($filters['category_id']) ? $filters['category_id'] : null;

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
        $id          = ArrayHelper::getValue($data, "id");
        $name        = ArrayHelper::getValue($data, "name");
        $description = ArrayHelper::getValue($data, "description");
        $categoryId  = ArrayHelper::getValue($data, "catid");

        // Load a record from the database
        $row = $this->getTable();
        /** @var $row EmailTemplatesTablePlaceholder */

        $row->load($id);

        $row->set("name", $name);
        $row->set("description", $description);
        $row->set("catid", $categoryId);

        $this->prepareTable($row);

        $row->store();

        return $row->get("id");
    }

    /**
     * Prepare project images before saving.
     *
     * @param   object $table
     *
     * @throws Exception
     *
     * @since    1.6
     */
    protected function prepareTable($table)
    {
        // Set order value
        if (!$table->get("id") and !$table->get("ordering")) {

            $db    = $this->getDbo();
            $query = $db->getQuery(true);

            $query
                ->select("MAX(ordering)")
                ->from($db->quoteName("#__emailtemplates_placeholders"));

            $db->setQuery($query, 0, 1);
            $max = $db->loadResult();

            $table->set("ordering", $max + 1);
        }

    }
}
