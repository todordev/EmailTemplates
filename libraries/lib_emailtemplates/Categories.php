<?php
/**
 * @package      EmailTemplates
 * @subpackage   Categories
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

namespace EmailTemplates;

use Joomla\String\String;

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
     * $categories   = new EmailTemplates\Categories($options);
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
     * $categories   = new EmailTemplates\Categories();
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
     * $categories   = new EmailTemplates\Categories();
     * $categories->setDb(JFactory::getDbo());
     *
     * $categories->load($parentId);
     * </code>
     *
     * @param null|int $parentId Parent ID or "root".
     * @param array $options
     */
    public function load($parentId = null, $options = array())
    {
        $offset    = (isset($options["offset"])) ? $options["offset"] : 0;
        $limit     = (isset($options["limit"])) ? $options["limit"] : 20;
        $orderBy   = (isset($options["order_by"])) ? $options["order_by"] : "a.title";
        $orderDir  = (isset($options["order_dir"])) ? $options["order_dir"] : "ASC";
        $loadPlaceholders  = (isset($options["load_placeholders"])) ? (bool)$options["load_placeholders"] : false;

        $orderDir  = String::strtoupper($orderDir);

        if (!in_array($orderDir, array("ASC", "DESC"))) {
            $orderDir = "ASC";
        }

        $query = $this->db->getQuery(true);
        $query
            ->select(
                "a.id, a.title, a.alias, a.description, a.params, " .
                $query->concatenate(array("a.id", "a.alias"), ":") . " AS slug"
            )
            ->from($this->db->quoteName("#__categories", "a"))
            ->where("a.extension = ". $this->db->quote($this->_extension));

        if (!is_null($parentId)) {
            $query->where("a.parent_id = ". (int)$parentId);
        }

        $query->order($this->db->quoteName($orderBy) . " " . $orderDir);

        $this->db->setQuery($query, (int)$offset, (int)$limit);

        $results = (array)$this->db->loadAssocList();

        if (!empty($results) and $loadPlaceholders) {
            $this->loadPlaceholders($results);
        }

        $this->data = $results;
    }

    /**
     * Load the placeholders of the categories from database.
     *
     * @param array $categories
     */
    protected function loadPlaceholders(&$categories)
    {
        $ids = array();
        foreach ($categories as &$category) {
            $ids[] = $category["id"];

            if (!isset($category["placeholders"])) {
                $category["placeholders"] = array();
            }
        }
        unset($category);

        $query = $this->db->getQuery(true);
        $query
            ->select(
                "a.id, a.name, a.description, a.catid, " .
                "b.title AS category"
            )
            ->from($this->db->quoteName("#__emailtemplates_placeholders", "a"))
            ->leftJoin($this->db->quoteName("#__categories", "b") . " ON a.catid = b.id")
            ->where("a.catid IN (". implode(",", $ids) . ")")
            ->order("a.name ASC");

        $this->db->setQuery($query);
        $results = $this->db->loadAssocList();

        foreach ($categories as &$category) {

            foreach ($results as $placeholder) {
                if ($category["id"] == $placeholder["catid"]) {
                    $category["placeholders"][] = $placeholder;
                }
            }

        }
        unset($category);
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
