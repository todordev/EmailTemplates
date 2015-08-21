<?php
/**
 * @package      EmailTemplates
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class EmailTemplatesModelExport extends JModelLegacy
{
    public function extractPlaceholders($keys)
    {
        $output = null;

        if (!empty($keys)) {
            $db = $this->getDbo();
            /** @var $db JDatabaseDriver */

            // Create a new query object.
            $query = $db->getQuery(true);

            // Select the required fields from the table.
            $query
                ->select('a.name, a.description, a.ordering')
                ->from($db->quoteName('#__emailtemplates_placeholders', 'a'))
                ->where("a.id IN (" . implode(",", $keys) . ")");


            $db->setQuery($query);
            $results = $db->loadAssocList();

            $output = $this->prepareXML($results, "placeholders", "placeholder");
        }

        return $output;
    }

    public function extractEmails($keys)
    {
        $output = null;

        if (!empty($keys)) {
            $db = $this->getDbo();
            /** @var $db JDatabaseDriver */

            // Create a new query object.
            $query = $db->getQuery(true);

            // Select the required fields from the table.
            $query
                ->select('a.title, a.subject, a.body')
                ->from($db->quoteName('#__emailtemplates_emails', 'a'))
                ->where("a.id IN (" . implode(",", $keys) . ")");


            $db->setQuery($query);
            $results = $db->loadAssocList();

            $output = $this->prepareXML($results, "emails", "email");
        }

        return $output;
    }

    protected function prepareXML($results, $root, $child)
    {
        $xml = new Prism\Xml\Simple('<?xml version="1.0" encoding="utf-8" ?><' . $root . '/>');
        $xml->addAttribute("generator", "com_emailtemplates");

        if (!empty($root) and !empty($child)) {

            foreach ($results as $data) {

                $item = $xml->addChild($child);

                foreach ($data as $key => $value) {
                    if (strcmp("body", $key) != 0) {
                        $item->addChild($key, $value);
                    } else {
                        $cdataItem = $item->addChild($key);
                        $cdataItem->addCData($value);
                    }
                }
            }
        }

        $dom               = dom_import_simplexml($xml)->ownerDocument;
        $dom->formatOutput = true;

        return $dom->saveXML();
    }
}
