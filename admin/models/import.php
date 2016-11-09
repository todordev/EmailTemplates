<?php
/**
 * @package      EmailTemplates
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

use Joomla\String\StringHelper;
use Joomla\Utilities\ArrayHelper;

// no direct access
defined('_JEXEC') or die;

class EmailTemplatesModelImport extends JModelForm
{
    protected function populateState()
    {
        $app = JFactory::getApplication();
        /** @var $app JApplicationAdministrator */

        // Load the filter state.
        $value = $app->getUserStateFromRequest('import.context', 'type', 'placeholders');
        $this->setState('import.context', $value);
    }

    /**
     * Method to get the record form.
     *
     * @param   array   $data     An optional array of data for the form to interrogate.
     * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return  JForm|bool   A JForm object on success, false on failure
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm($this->option . '.import', 'import', array('control' => 'jform', 'load_data' => $loadData));
        if (!$form) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed   The data for the form.
     * @since   1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.import.data', array());

        return $data;
    }

    public function extractFile($file, $destFolder)
    {
        $filePath = '';

        // extract type
        $zipAdapter = JArchive::getAdapter('zip');
        $zipAdapter->extract($file, $destFolder);

        $dir = new DirectoryIterator($destFolder);

        foreach ($dir as $fileinfo) {
            $fileExtension = JFile::getExt($fileinfo->getFilename());
            if (!$fileinfo->isDot() and strcmp('xml', $fileExtension) === 0) {
                $filePath = JPath::clean($destFolder . DIRECTORY_SEPARATOR . JFile::makeSafe($fileinfo->getFilename()));
                break;
            }
        }

        return $filePath;
    }

    public function uploadFile($uploadedFileData, $type)
    {
        $app = JFactory::getApplication();
        /** @var $app JApplicationAdministrator */

        $uploadedFile = ArrayHelper::getValue($uploadedFileData, 'tmp_name');
//        $uploadedName = ArrayHelper::getValue($uploadedFileData, 'name');
        $errorCode    = ArrayHelper::getValue($uploadedFileData, 'error');

        // Prepare size validator.
        $KB       = pow(1024, 2);
        $fileSize = ArrayHelper::getValue($uploadedFileData, 'size', 0, 'int');

        $mediaParams   = JComponentHelper::getParams('com_media');
        /** @var $mediaParams Joomla\Registry\Registry */

        $uploadMaxSize = $mediaParams->get('upload_maxsize') * $KB;

        // Prepare size validator.
        $sizeValidator = new Prism\File\Validator\Size($fileSize, $uploadMaxSize);

        // Prepare server validator.
        $serverValidator = new Prism\File\Validator\Server($errorCode, array(UPLOAD_ERR_NO_FILE));

        $file = new Prism\File\File($uploadedFile);
        $file
            ->addValidator($sizeValidator)
            ->addValidator($serverValidator);

        // Validate the file
        if (!$file->isValid()) {
            throw new RuntimeException($file->getError());
        }

        // Upload the file.
        $rootFolder      = JPath::clean($app->get('tmp_path'), '/');
        $filesystemLocal = new Prism\Filesystem\Adapter\Local($rootFolder);
        $sourceFile      = $filesystemLocal->upload($uploadedFileData);

        // Extract file if it is archive.
        $ext = StringHelper::strtolower(JFile::getExt(basename($sourceFile)));
        if (strcmp($ext, 'zip') === 0) {
            $destinationFolder = JPath::clean($app->get('tmp_path'). '/'. $type, '/');
            if (JFolder::exists($destinationFolder)) {
                JFolder::delete($destinationFolder);
            }

            $filePath = $this->extractFile($sourceFile, $destinationFolder);
        } else {
            $filePath = $sourceFile;
        }

        return $filePath;
    }

    /**
     * Import placeholders from XML file.
     *
     * @param string $file    A path to file
     * @param int    $categoryId
     *
     * @throws \RuntimeException
     */
    public function importPlaceholders($file, $categoryId)
    {
        $xmlstr  = file_get_contents($file);
        $content = new SimpleXMLElement($xmlstr);

        $items = array();

        $db = $this->getDbo();

        // Generate data for importing.
        foreach ($content as $item) {
            $name = StringHelper::trim($item->name);
            if (!$name) {
                continue;
            }

            $items[] = $db->quote($name) . ',' . $db->quote(JString::trim($item->description)) . ',' . (int)$item->ordering . ',' . (int)$categoryId;
        }

        unset($content);

        $query = $db->getQuery(true);

        $query
            ->insert('#__emailtemplates_placeholders')
            ->columns($db->quoteName(array('name', 'description', 'ordering', 'catid')))
            ->values($items);

        $db->setQuery($query);
        $db->execute();
    }

    /**
     * Import emails from XML file.
     *
     * @param string $file    A path to file
     * @param int    $categoryId
     *
     * @throws \RuntimeException
     */
    public function importEmails($file, $categoryId)
    {
        $xmlstr  = file_get_contents($file);
        $content = new SimpleXMLElement($xmlstr);

        $items = array();

        $db = $this->getDbo();

        // Generate data for importing.
        foreach ($content as $item) {
            $title = StringHelper::trim($item->title);
            if (!$title) {
                continue;
            }

            $items[] = $db->quote($title) . ',' . $db->quote(JString::trim($item->subject)) . ',' . $db->quote(JString::trim($item->body)) . ',' . (int)$categoryId;
        }

        unset($content);

        $query = $db->getQuery(true);

        $query
            ->insert('#__emailtemplates_emails')
            ->columns($db->quoteName(array('title', 'subject', 'body', 'catid')))
            ->values($items);

        $db->setQuery($query);
        $db->execute();
    }
}
