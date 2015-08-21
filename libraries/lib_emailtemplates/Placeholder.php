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
 * This class provides functionality for managing a placeholder.
 *
 * @package      EmailTemplates
 * @subpackage   Placeholders
 */
class Placeholder
{
    protected $id = 0;
    protected $name = "";
    protected $description = "";
    protected $catid = 0;

    /**
     * @var \JDatabaseDriver
     */
    protected $db;

    /**
     * This method initializes the object.
     *
     * <code>
     * $placeholder = new EmailTemplates\Placeholder(JFactory::getDbo());
     * </code>
     * 
     * @param \JDatabaseDriver $db
     */
    public function __construct(\JDatabaseDriver $db)
    {
        $this->db = $db;
    }

    /**
     * This method loads data of a placeholder from the database.
     *
     * <code>
     * $placeholderId = 1;
     *
     * $placeholder   = new EmailTemplates\Placeholder(JFactory::getDbo());
     * $placeholder->load($placeholderId);
     * </code>
     * 
     * @param int $id
     */
    public function load($id)
    {
        $query = $this->db->getQuery(true);
        $query
            ->select("a.id, a.name, a.description, a.catid")
            ->from($this->db->quoteName("#__emailtemplates_placeholders", "a"))
            ->where("a.id = " . (int)$id);

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
     *      "name"          => "{NAME}",
     *      "description"   => "This is...",
     *      "catid"         => 1
     * );
     *
     * $placeholder   = new EmailTemplates\Placeholder(JFactory::getDbo());
     * $placeholder->bind($data);
     * </code>
     *
     * @param array $data
     * @param array $ignored
     *
     * @return self
     */
    public function bind($data, $ignored = array())
    {
        foreach ($data as $key => $value) {
            if (!in_array($key, $ignored)) {
                $this->$key = $value;
            }
        }

        return $this;
    }

    /**
     * It returns an id of a placeholder.
     *
     * <code>
     * $placeholderId  = 1;
     *
     * $placeholder   = new EmailTemplates\Placeholder(JFactory::getDbo());
     * $placeholder->load($placeholderId);
     *
     * if (!$placeholder->getId()) {
     * ....
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
     * Return placeholder description.
     *
     * <code>
     * $placeholderId  = 1;
     *
     * $placeholder   = new EmailTemplates\Placeholder(JFactory::getDbo());
     * $placeholder->load($placeholderId);
     *
     * $description = $placeholder->getDescription();
     * </code>
     *
     * @return string
     */
    public function getDescription()
    {
        return (string)$this->description;
    }

    /**
     * Return category ID.
     *
     * <code>
     * $placeholderId = 1;
     *
     * $placeholder   = new EmailTemplates\Placeholder(JFactory::getDbo());
     * $placeholder->load($placeholderId);
     *
     * $categoryId = $placeholder->getCategoryId();
     * </code>
     *
     * @return int
     */
    public function getCategoryId()
    {
        return (int)$this->catid;
    }

    /**
     * Return title of an e-mail template.
     *
     * <code>
     * $placeholderId = 1;
     *
     * $placeholder   = new EmailTemplates\Placeholder(JFactory::getDbo());
     * $placeholder->load($placeholderId);
     *
     * $name = $placeholder->getName();
     * </code>
     *
     * @return string
     */
    public function getName()
    {
        return (string)$this->name;
    }
}
