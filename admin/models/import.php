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

class EmailTemplatesModelImport extends JModelForm
{
    protected function populateState()
    {
        $app = JFactory::getApplication();
        /** @var $app JApplicationAdministrator */

        // Load the filter state.
        $value = $app->getUserStateFromRequest('import.context', 'type', "placeholders");
        $this->setState('import.context', $value);

    }

    /**
     * Method to get the record form.
     *
     * @param   array   $data     An optional array of data for the form to interrogate.
     * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return  JForm   A JForm object on success, false on failure
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm($this->option . '.import', 'import', array('control' => 'jform', 'load_data' => $loadData));
        if (empty($form)) {
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
        $filePath = "";

        // extract type
        $zipAdapter = JArchive::getAdapter('zip');
        $zipAdapter->extract($file, $destFolder);

        $dir = new DirectoryIterator($destFolder);

        foreach ($dir as $fileinfo) {

            $fileExtension = JFile::getExt($fileinfo->getFilename());
            if (!$fileinfo->isDot() and strcmp("xml", $fileExtension) == 0) {
                $filePath = JPath::clean($destFolder . DIRECTORY_SEPARATOR . JFile::makeSafe($fileinfo->getFilename()));
                break;
            }

        }

        return $filePath;
    }

    public function uploadFile($fileData, $type)
    {
        $app = JFactory::getApplication();
        /** @var $app JApplicationAdministrator */

        jimport('joomla.filesystem.archive');

        $uploadedFile = JArrayHelper::getValue($fileData, 'tmp_name');
        $uploadedName = JArrayHelper::getValue($fileData, 'name');
        $errorCode    = JArrayHelper::getValue($fileData, 'error');

        $destination = JPath::clean($app->get("tmp_path") . DIRECTORY_SEPARATOR . JFile::makeSafe($uploadedName));

        $file = new Prism\File\File();

        // Prepare size validator.
        $KB       = 1024 * 1024;
        $fileSize = (int)$app->input->server->get('CONTENT_LENGTH');

        $mediaParams   = JComponentHelper::getParams("com_media");
        /** @var $mediaParams Joomla\Registry\Registry */

        $uploadMaxSize = $mediaParams->get("upload_maxsize") * $KB;

        // Prepare size validator.
        $sizeValidator = new Prism\File\Validator\Size($fileSize, $uploadMaxSize);

        // Prepare server validator.
        $serverValidator = new Prism\File\Validator\Server($errorCode, array(UPLOAD_ERR_NO_FILE));

        $file->addValidator($sizeValidator);
        $file->addValidator($serverValidator);

        // Validate the file
        if (!$file->isValid()) {
            throw new RuntimeException($file->getError());
        }

        // Prepare uploader object.
        $uploader = new Prism\File\Uploader\Local($uploadedFile);
        $uploader->setDestination($destination);

        // Upload the file
        $file->setUploader($uploader);
        $file->upload();

        $fileName = basename($destination);

        // Extract file if it is archive.
        $ext = Joomla\String\String::strtolower(JFile::getExt($fileName));
        if (strcmp($ext, "zip") == 0) {

            $destFolder = JPath::clean($app->get("tmp_path"). "/". $type);
            if (is_dir($destFolder)) {
                JFolder::delete($destFolder);
            }

            $filePath = $this->extractFile($destination, $destFolder);

        } else {
            $filePath = $destination;
        }

        return $filePath;
    }

    /**
     * Import placeholders from XML file.
     *
     * @param string $file    A path to file
     * @param int    $categoryId
     */
    public function importPlaceholders($file, $categoryId)
    {
        $xmlstr  = file_get_contents($file);
        $content = new SimpleXMLElement($xmlstr);

        $items = array();

        $db = $this->getDbo();

        // Generate data for importing.
        foreach ($content as $item) {

            $name = Joomla\String\String::trim($item->name);
            if (!$name) {
                continue;
            }

            $items[] = $db->quote($name) . "," . $db->quote(Joomla\String\String::trim($item->description)) . "," . (int)$item->ordering . "," . (int)$categoryId;
        }

        unset($content);

        $query = $db->getQuery(true);

        $query
            ->insert("#__emailtemplates_placeholders")
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
     */
    public function importEmails($file, $categoryId)
    {
        $xmlstr  = file_get_contents($file);
        $content = new SimpleXMLElement($xmlstr);

        $items = array();

        $db = $this->getDbo();

        // Generate data for importing.
        foreach ($content as $item) {

            $title = Joomla\String\String::trim($item->title);
            if (!$title) {
                continue;
            }

            $items[] = $db->quote($title) . "," . $db->quote(Joomla\String\String::trim($item->subject)) . "," . $db->quote(Joomla\String\String::trim($item->body)) . "," . (int)$categoryId;
        }

        unset($content);

        $query = $db->getQuery(true);

        $query
            ->insert("#__emailtemplates_emails")
            ->columns($db->quoteName(array('title', 'subject', 'body', 'catid')))
            ->values($items);

        $db->setQuery($query);
        $db->execute();
    }
}
