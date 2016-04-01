<?php
/**
 * @package      EmailTemplates
 * @subpackage   Placeholders
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

namespace Emailtemplates;

use Prism\Database;

defined('JPATH_PLATFORM') or die;

/**
 * This class provides functionality for managing a placeholders.
 *
 * @package      EmailTemplates
 * @subpackage   Placeholders
 */
class Placeholders extends Database\Collection
{
    /**
     * Load placeholders data from the database.
     *
     * <code>
     * $options = array(
     *    "category_id"    => 1,
     * );
     *
     * $placeholders = new Emailtemplates\Placeholders(JFactory::getDbo());
     * $placeholders->load($options);
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
            ->select('a.id, a.name, a.description, a.catid')
            ->from($this->db->quoteName('#__emailtemplates_placeholders', 'a'))
            ->order('a.name ASC');

        if ($categoryId > 0) {
            $query->where('a.catid = ' . (int)$categoryId);
        }

        $this->db->setQuery($query);

        $this->items = (array)$this->db->loadAssocList();
    }
}
