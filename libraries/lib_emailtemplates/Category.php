<?php
/**
 * @package      EmailTemplates
 * @subpackage   Categories
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace EmailTemplates;

defined('JPATH_PLATFORM') or die;

/**
 * This class provides functionality for managing a category.
 *
 * @package      EmailTemplates
 * @subpackage   Categories
 */
class Category
{
    protected $id;
    protected $title;
    protected $description;
    protected $slug;

    /**
     * @var \JDatabaseDriver
     */
    protected $db;

    /**
     * This method initializes the object.
     *
     * <code>
     * $category   = new EmailTemplates\Category(JFactory::getDbo());
     * </code>
     *
     * @param \JDatabaseDriver $db
     */
    public function __construct(\JDatabaseDriver $db = null)
    {
        $this->db = $db;
    }

    /**
     * This method loads data about category from a database.
     *
     * <code>
     * $categoryId = 1;
     *
     * $category   = new EmailTemplates\Category(JFactory::getDbo());
     * $category->load($categoryId);
     * </code>
     *
     * @param int $id
     */
    public function load($id)
    {
        $query = $this->db->getQuery(true);
        $query
            ->select(
                "a.id, a.title, a.description, " .
                $query->concatenate(array("a.id", "a.alias"), ":") . " AS slug"
            )
            ->from($this->db->quoteName("#__categories", "a"))
            ->where("a.id = " . (int)$id)
            ->where("a.extension = " . $this->db->quote("com_emailtemplates"));

        $this->db->setQuery($query);
        $result = $this->db->loadAssoc();

        if (!empty($result)) {
            $this->bind($result);
        }
    }

    /**
     * This method sets data to object parameters.
     *
     * <code>
     * $data = array(
     *      "id"    => 1,
     *      "title" => "My title"
     * );
     *
     * $category   = new EmailTemplates\Category(JFactory::getDbo());
     * $category->bind($data);
     * </code>
     *
     * @param array $data
     * @param array $ignored
     */
    public function bind($data, $ignored = array())
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, $ignored)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Return category ID.
     *
     * <code>
     * $categoryId = 1;
     *
     * $category   = new EmailTemplates\Category(JFactory::getDbo());
     * $category->load($categoryId);
     *
     * if (!$category->getId) {
     * ...
     * }
     * </code>
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Return category title.
     *
     * <code>
     * $categoryId = 1;
     *
     * $category   = new EmailTemplates\Category(JFactory::getDbo());
     * $category->load($categoryId);
     *
     * $title = $category->getTitle();
     * </code>
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Return category description.
     *
     * <code>
     * $categoryId = 1;
     *
     * $category   = new EmailTemplates\Category(JFactory::getDbo());
     * $category->load($categoryId);
     *
     * $description = $category->getDescription();
     * </code>
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Return URL slug ( id + alias ) of the category.
     *
     * <code>
     * $categoryId = 1;
     *
     * $category   = new EmailTemplates\Category(JFactory::getDbo());
     * $category->load($categoryId);
     *
     * $slug = $category->getSlug();
     * </code>
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }
}
