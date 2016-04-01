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
 * This class contains methods that are used for managing emails.
 *
 * @package      EmailTemplates
 * @subpackage   Emails
 */
class Emails extends Database\Collection
{
    /**
     * Load emails data from the database.
     *
     * <code>
     * $options = array(
     *    "category_id"    => 1,
     * );
     *
     * $emails = new Emailtemplates\Emails(JFactory::getDbo());
     * $emails->load($options);
     * </code>
     *
     * @param array $options
     */
    public function load(array $options = array())
    {
        $categoryId = (!array_key_exists('category_id', $options)) ? 0 : (int)$options['category_id'];

        // Create a new query object.
        $query = $this->db->getQuery(true);
        $query
            ->select('a.id, a.title, a.subject, a.body, a.sender_name, a.sender_email, a.catid')
            ->from($this->db->quoteName('#__emailtemplates_emails', 'a'))
            ->order('a.title ASC');

        if (!empty($categoryId)) {
            $query->where('a.catid = '. (int)$categoryId);
        }

        $this->db->setQuery($query);

        $this->items = (array)$this->db->loadAssocList();
    }
}
