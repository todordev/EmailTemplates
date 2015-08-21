<?php
/**
 * @package      EmailTemplates
 * @subpackage   Emails
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace EmailTemplates;

defined('JPATH_PLATFORM') or die;

/**
 * This class contains methods that are used for managing emails.
 *
 * @package      EmailTemplates
 * @subpackage   Emails
 */
class Emails implements \Iterator, \Countable, \ArrayAccess
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
     * $emails = new EmailTemplates\Emails(JFactory::getDbo());
     * </code>
     *
     * @param \JDatabaseDriver $db
     */
    public function __construct(\JDatabaseDriver $db = null)
    {
        $this->db = $db;
    }

    /**
     * Load emails data from the database.
     *
     * <code>
     * $options = array(
     *    "category_id"    => 1,
     * );
     *
     * $emails = new EmailTemplates\Emails(JFactory::getDbo());
     * $emails->load($options);
     * </code>
     *
     * @param array $options
     */
    public function load($options)
    {
        $categoryId = (!isset($options["category_id"])) ? 0 : (int)$options["category_id"];

        // Create a new query object.
        $query = $this->db->getQuery(true);
        $query
            ->select("a.id, a.title, a.subject, a.body, a.sender_name, a.sender_email, a.catid")
            ->from($this->db->quoteName("#__emailtemplates_emails", "a"))
            ->order("a.title ASC");

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

    /**
     * This method prepares and returns the statuses as an array,
     * which can be used as options.
     *
     * <code>
     * $emails = new EmailTemplates\Emails(JFactory::getDbo());
     * $emails->load($options);
     *
     * $options = $emails->toOptions();
     * </code>
     *
     * @return array
     */
    public function toOptions()
    {
        $options = array();

        foreach ($this->items as $email) {
            $options[] = array("text" => $email["title"], "value" => $email["id"]);
        }

        return $options;
    }
}
