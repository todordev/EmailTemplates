<?php
/**
 * @package      Crowdfunding
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// No direct access
defined('_JEXEC') or die;
?>
<div class="row-fluid">
    <div class="span6 form-horizontal">
        <form action="<?php echo JRoute::_('index.php?option=com_emailtemplates'); ?>" method="post" name="adminForm"
              id="adminForm" class="form-validate" enctype="multipart/form-data">

            <fieldset>
                <legend><?php echo $this->legend; ?></legend>

                <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('data'); ?></div>
                    <div class="controls">

                        <div class="fileupload fileupload-new" data-provides="fileupload">
                            <span class="btn btn-file">
                                <span class="fileupload-new"><i class="icon-folder-open"></i> <?php echo JText::_("COM_EMAILTEMPLATES_SELECT_FILE"); ?></span>
                                <span class="fileupload-exists"><i class="icon-edit"></i> <?php echo JText::_("COM_EMAILTEMPLATES_CHANGE"); ?></span>
                                <?php echo $this->form->getInput('data'); ?>
                            </span>
                            <span class="fileupload-preview"></span>
                            <a href="#" class="close fileupload-exists" data-dismiss="fileupload" style="float: none">×</a>
                        </div>
                    </div>
                </div>

                <?php echo $this->form->getControlGroup('catid'); ?>

            </fieldset>

            <input type="hidden" name="task" value="" id="task"/>
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
</div>