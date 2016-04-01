<?php
/**
 * @package      EmailTemplates
 * @subpackage   Categories
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Emailtemplates;

use Prism\Database;

defined('JPATH_PLATFORM') or die;

/**
 * This class provides functionality for managing a category.
 *
 * @package      EmailTemplates
 * @subpackage   Categories
 */
class Category extends Database\TableImmutable
{
    protected $id;
    protected $title;
    protected $description;
    protected $slug;

    /**
     * This method loads data about category from a database.
     *
     * <code>
     * $categoryId = 1;
     *
     * $category   = new Emailtemplates\Category(JFactory::getDbo());
     * $category->load($categoryId);
     * </code>
     *
     * @param int|array $keys
     * @param array $options
     */
    public function load($keys, array $options = array())
    {
        $query = $this->db->getQuery(true);
        $query
            ->select(
                'a.id, a.title, a.description, ' .
                $query->concatenate(array('a.id', 'a.alias'), ':') . ' AS slug'
            )
            ->from($this->db->quoteName('#__categories', 'a'))
            ->where('a.id = ' . (int)$keys)
            ->where('a.extension = ' . $this->db->quote('com_emailtemplates'));

        $this->db->setQuery($query);
        $result = (array)$this->db->loadAssoc();

        $this->bind($result);
    }

    /**
     * Return category ID.
     *
     * <code>
     * $categoryId = 1;
     *
     * $category   = new Emailtemplates\Category(JFactory::getDbo());
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
        return (int)$this->id;
    }

    /**
     * Return category title.
     *
     * <code>
     * $categoryId = 1;
     *
     * $category   = new Emailtemplates\Category(JFactory::getDbo());
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
     * $category   = new Emailtemplates\Category(JFactory::getDbo());
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
     * $category   = new Emailtemplates\Category(JFactory::getDbo());
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
