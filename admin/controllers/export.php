<?php
/**
 * @package      EmailTemplates
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;

/**
 * EmailTemplates expoxrt controller class.
 *
 * @package        EmailTemplates
 * @subpackage     Component
 * @since          1.6
 */
class EmailTemplatesControllerExport extends Prism\Controller\Form\Backend
{
    public function placeholders()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        /** @var $app JApplicationAdministrator */

        $keys   = $this->input->post->get('cid', array(), 'array');
        $keys   = Joomla\Utilities\ArrayHelper::toInteger($keys);
        
        $model = $this->getModel();
        /** @var $model EmailTemplatesModelExport */

        try {

            $output   = $model->extractPlaceholders($keys);
            $fileName = 'placeholders.xml';

        } catch (Exception $e) {
            JLog::add($e->getMessage());
            throw new Exception(JText::_('COM_EMAILTEMPLATES_ERROR_SYSTEM'));
        }

        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.path');
        jimport('joomla.filesystem.archive');

        $tmpFolder = JPath::clean($app->get('tmp_path'));

        $date = new JDate();
        $date = $date->format('d_m_Y_H_i_s');

        $archiveName = JFile::stripExt(basename($fileName)) . '_' . $date;
        $archiveFile = $archiveName . '.zip';
        $destination = $tmpFolder . DIRECTORY_SEPARATOR . $archiveFile;

        // compression type
        $zipAdapter   = JArchive::getAdapter('zip');
        $filesToZip[] = array(
            'name' => $fileName,
            'data' => $output
        );

        $zipAdapter->create($destination, $filesToZip, array());

        $filesize = filesize($destination);

        $app = JFactory::getApplication();

        $app->setHeader('Content-Type', 'application/octet-stream', true);
        $app->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $app->setHeader('Content-Transfer-Encoding', 'binary', true);
        $app->setHeader('Pragma', 'no-cache', true);
        $app->setHeader('Expires', '0', true);
        $app->setHeader('Content-Disposition', 'attachment; filename=' . $archiveFile, true);
        $app->setHeader('Content-Length', $filesize, true);

        $doc = JFactory::getDocument();
        $doc->setMimeEncoding('application/octet-stream');

        $app->sendHeaders();

        echo file_get_contents($destination);
        JFactory::getApplication()->close();
    }

    public function emails()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();
        /** @var $app JApplicationAdministrator */

        $keys   = $this->input->post->get('cid', array(), 'array');
        $keys   = Joomla\Utilities\ArrayHelper::toInteger($keys);

        $model = $this->getModel();
        /** @var $model EmailTemplatesModelExport */

        try {

            $output   = $model->extractEmails($keys);
            $fileName = 'emails.xml';

        } catch (Exception $e) {
            JLog::add($e->getMessage());
            throw new Exception(JText::_('COM_EMAILTEMPLATES_ERROR_SYSTEM'));
        }

        jimport('joomla.filesystem.folder');
        jimport('joomla.filesystem.file');
        jimport('joomla.filesystem.path');
        jimport('joomla.filesystem.archive');

        $tmpFolder = JPath::clean($app->get('tmp_path'));

        $date = new JDate();
        $date = $date->format('d_m_Y_H_i_s');

        $archiveName = JFile::stripExt(basename($fileName)) . '_' . $date;
        $archiveFile = $archiveName . '.zip';
        $destination = $tmpFolder . DIRECTORY_SEPARATOR . $archiveFile;

        // compression type
        $zipAdapter   = JArchive::getAdapter('zip');
        $filesToZip[] = array(
            'name' => $fileName,
            'data' => $output
        );

        $zipAdapter->create($destination, $filesToZip, array());

        $filesize = filesize($destination);

        $app = JFactory::getApplication();

        $app->setHeader('Content-Type', 'application/octet-stream', true);
        $app->setHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0', true);
        $app->setHeader('Content-Transfer-Encoding', 'binary', true);
        $app->setHeader('Pragma', 'no-cache', true);
        $app->setHeader('Expires', '0', true);
        $app->setHeader('Content-Disposition', 'attachment; filename=' . $archiveFile, true);
        $app->setHeader('Content-Length', $filesize, true);

        $doc = JFactory::getDocument();
        $doc->setMimeEncoding('application/octet-stream');

        $app->sendHeaders();

        echo file_get_contents($destination);
        JFactory::getApplication()->close();
    }
}
