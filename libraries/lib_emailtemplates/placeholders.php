<?php
/**
 * @package         EmailTemplates
 * @subpackage      Placeholders
 * @author          Todor Iliev
 * @copyright       Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license         http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

namespace EmailTemplates;

defined('JPATH_PLATFORM') or die;

/**
 * This class provides functionality for managing a placeholders.
 *
 * @package      EmailTemplates
 * @subpackage   Placeholders
 */
class Placeholders implements \Iterator, \Countable, \ArrayAccess
{
    protected $items = array();

    protected $position = 0;

    /**
     * @var \JDatabaseDriver
     */
    protected $db;

    /**
     * This method initializes the object.
     *
     * <code>
     * $placeholders = new EmailTemplates\Placeholders(JFactory::getDbo());
     * </code>
     *
     * @param \JDatabaseDriver $db
     */
    public function __construct(\JDatabaseDriver $db = null)
    {
        $this->db = $db;
    }

    /**
     * Load placeholders data from the database.
     *
     * <code>
     * $options = array(
     *    "category_id"    => 1,
     * );
     *
     * $placeholders = new EmailTemplates\Placeholders(JFactory::getDbo());
     * $placeholders->load($options);
     * </code>
     *
     * @param array $options
     */
    public function load(array $options = array())
    {
        $categoryId = (!isset($options["category_id"])) ? 0 : (int)$options["category_id"];

        // Create a new query object.
        $query = $this->db->getQuery(true);
        $query
            ->select("a.id, a.name, a.description, a.catid")
            ->from($this->db->quoteName("#__emailtemplates_placeholders", "a"))
            ->order("a.name ASC");

        if (!empty($categoryId)) {
            $query->where("a.catid = ". (int)$categoryId);
        }

        $this->db->setQuery($query);

        $results = (array)$this->db->loadAssocList();

        if (!empty($results)) {
            $this->items = $results;
        }
    }

    /**
     * Rewind the Iterator to the first element.
     *
     * @see Iterator::rewind()
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * Return the current element.
     *
     * @see Iterator::current()
     */
    public function current()
    {
        return (!isset($this->items[$this->position])) ? null : $this->items[$this->position];
    }

    /**
     * Return the key of the current element.
     *
     * @see Iterator::key()
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * Move forward to next element.
     *
     * @see Iterator::next()
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * Checks if current position is valid.
     *
     * @see Iterator::valid()
     */
    public function valid()
    {
        return isset($this->items[$this->position]);
    }

    /**
     * Count elements of an object.
     *
     * @see Countable::count()
     */
    public function count()
    {
        return (int)count($this->items);
    }

    /**
     * Offset to set.
     *
     * @param $offset
     * @param $value
     * 
     * @see ArrayAccess::offsetSet()
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * Whether a offset exists.
     *
     * @param $offset
     *
     * @return bool
     *
     * @see ArrayAccess::offsetExists()
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * Offset to unset.
     *
     * @param $offset
     *
     * @see ArrayAccess::offsetUnset()
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * Offset to retrieve.
     *
     * @param $offset
     *
     * @return mixed
     *
     * @see ArrayAccess::offsetGet()
     */
    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }
}
