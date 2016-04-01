<?php
/**
 * @package      EmailTemplates
 * @subpackage   Emails
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Emailtemplates;

use Prism\Database;

defined('JPATH_PLATFORM') or die;

/**
 * This class provides functionality for managing a placeholder.
 *
 * @package      EmailTemplates
 * @subpackage   Placeholders
 */
class Placeholder extends Database\TableImmutable
{
    protected $id = 0;
    protected $name;
    protected $description;
    protected $catid = 0;

    /**
     * This method loads data of a placeholder from the database.
     *
     * <code>
     * $placeholderId = 1;
     *
     * $placeholder   = new Emailtemplates\Placeholder(JFactory::getDbo());
     * $placeholder->load($placeholderId);
     * </code>
     *
     * @param int|array $keys
     * @param array $options
     */
    public function load($keys, array $options = array())
    {
        $query = $this->db->getQuery(true);
        $query
            ->select('a.id, a.name, a.description, a.catid')
            ->from($this->db->quoteName('#__emailtemplates_placeholders', 'a'));

        if (is_array($keys)) {
            foreach ($keys as $key => $value) {
                $query->where($this->db->quoteName('a.'.$key) .' = ' . $this->db->quote($value));
            }
        } else {
            $query->where('a.id = ' . (int)$keys);
        }

        $this->db->setQuery($query);
        $result = $this->db->loadAssoc();

        $this->bind($result);
    }

    /**
     * It returns an id of a placeholder.
     *
     * <code>
     * $placeholderId  = 1;
     *
     * $placeholder   = new Emailtemplates\Placeholder(JFactory::getDbo());
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
     * $placeholder   = new Emailtemplates\Placeholder(JFactory::getDbo());
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
     * $placeholder   = new Emailtemplates\Placeholder(JFactory::getDbo());
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
     * $placeholder   = new Emailtemplates\Placeholder(JFactory::getDbo());
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
