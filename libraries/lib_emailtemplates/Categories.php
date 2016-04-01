<?php
/**
 * @package      EmailTemplates
 * @subpackage   Categories
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Emailtemplates;

defined('_JEXEC') or die;

/**
 * This class provides functionality for managing categories.
 *
 * @package EmailTemplates
 * @subpackage   Categories
 */
class Categories extends \JCategories
{
    /**
     * The property that contains categories.
     *
     * @var array
     */
    protected $data = array();

    /**
     * @var \JDatabaseDriver
     */
    protected $db;

    /**
     * Initialize the object.
     *
     * <code>
     * $options = array(
     *     "extension" => 'com_emailtemplates',
     *     "table" => 'email',
     *     "field" => 'catid',
     *     "key" => 'id',
     *     "statefield" => 'state',
     *     "statefield" => 'state',
     *     "published" => 1,
     * );
     *
     * $categories   = new Emailtemplates\Categories($options);
     * </code>
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        $options['table']     = '#__emailtemplates_emails';
        $options['extension'] = 'com_emailtemplates';

        parent::__construct($options);
    }

    /**
     * This method sets a database driver.
     *
     * <code>
     * $categories   = new Emailtemplates\Categories();
     * $categories->setDb(JFactory::getDbo());
     * </code>
     *
     * @param \JDatabaseDriver $db
     *
     * @return self
     */
    public function setDb(\JDatabaseDriver $db)
    {
        $this->db = $db;

        return $this;
    }

    /**
     * Load categories.
     *
     * <code>
     * $parentId = 2;
     *
     * $options = array(
     *    "offset" => 0,
     *    "limit" => 10,
     *    "order_by" => "a.name",
     *    "order_dir" => "DESC",
     * );
     *
     * $categories   = new Emailtemplates\Categories();
     * $categories->setDb(JFactory::getDbo());
     *
     * $categories->load($parentId);
     * </code>
     *
     * @param null|int $parentId Parent ID or "root".
     * @param array $options
     */
    public function load($parentId = null, array $options = array())
    {
        $offset    = (isset($options['offset'])) ? $options['offset'] : 0;
        $limit     = (isset($options['limit'])) ? $options['limit'] : 20;
        $orderBy   = (isset($options['order_by'])) ? $options['order_by'] : 'a.title';
        $orderDir  = (isset($options['order_dir'])) ? $options['order_dir'] : 'ASC';
        $loadPlaceholders  = (isset($options['load_placeholders'])) ? (bool)$options['load_placeholders'] : false;

        $orderDir  = strtoupper($orderDir);

        if (!in_array($orderDir, array('ASC', 'DESC'), true)) {
            $orderDir = 'ASC';
        }

        $query = $this->db->getQuery(true);
        $query
            ->select(
                'a.id, a.title, a.alias, a.description, a.params, ' .
                $query->concatenate(array('a.id', 'a.alias'), ':') . ' AS slug'
            )
            ->from($this->db->quoteName('#__categories', 'a'))
            ->where('a.extension = '. $this->db->quote($this->_extension));

        if ($parentId !== null) {
            $query->where('a.parent_id = '. (int)$parentId);
        }

        $query->order($this->db->quoteName($orderBy) . ' ' . $orderDir);

        $this->db->setQuery($query, (int)$offset, (int)$limit);

        $results = (array)$this->db->loadAssocList();

        if (count($results) > 0 and $loadPlaceholders) {
            $results = $this->preparePlaceholders($results);
        }

        $this->data = $results;
    }

    /**
     * Load the placeholders of the categories from database.
     *
     * @param array $categories
     */
    protected function preparePlaceholders($categories)
    {
        $ids = array();
        foreach ($categories as $key => $category) {
            $ids[] = $category['id'];

            if (!array_key_exists('placeholders', $category) or !is_array($category['placeholders'])) {
                $categories[$key]['placeholders'] = array();
            }
        }

        $query = $this->db->getQuery(true);
        $query
            ->select(
                'a.id, a.name, a.description, a.catid, ' .
                'b.title AS category'
            )
            ->from($this->db->quoteName('#__emailtemplates_placeholders', 'a'))
            ->leftJoin($this->db->quoteName('#__categories', 'b') . ' ON a.catid = b.id')
            ->where('a.catid IN ('. implode(',', $ids) . ')')
            ->order('a.name ASC');

        $this->db->setQuery($query);
        $results = $this->db->loadAssocList();

        foreach ($categories as $key => $category) {

            foreach ($results as $placeholder) {
                if ((int)$category['id'] === (int)$placeholder['catid']) {
                    $categories[$key]['placeholders'][] = $placeholder;
                }
            }

        }

        return $categories;
    }

    /**
     * Return the elements as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return (array)$this->data;
    }
}
