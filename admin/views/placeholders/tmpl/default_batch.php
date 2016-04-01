<?php
/**
 * @package      ITPTransifex
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl-3.0.en.html GNU/GPL
 */

defined('_JEXEC') or die;
?>
<div class="modal hide fade" id="collapseModal">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">&#215;</button>
		<h3><?php echo JText::_('COM_EMAILTEMPLATES_BATCH_OPTIONS'); ?></h3>
	</div>
	<div class="modal-body modal-batch">
        <p class="sticky">
            <?php echo JText::_('COM_EMAILTEMPLATES_BATCH_NOTE'); ?>
        </p>
        <form action="<?php echo JRoute::_("index.php?option=com_emailtemplates"); ?>" method="post" id="js-placeholders-batch-form">

            <div class="well well-small">
                <label class="radio">
                    <input type="radio" name="action" value="copy" checked />
                    <strong><?php echo JText::_('COM_EMAILTEMPLATES_COPY_PLACEHOLDERS'); ?></strong>
                </label>
                <label><?php echo JText::_('COM_EMAILTEMPLATES_CATEGORY'); ?></label>
                <?php echo JHtml::_("select.genericlist", JHtml::_("category.options", "com_emailtemplates"), "catid");?>
            </div>
            <input type="hidden" name="task" value="placeholders.batch" />
            <input type="hidden" name="format" value="raw" />
            <?php echo JHtml::_('form.token'); ?>

        </form>
	</div>
	<div class="modal-footer">
        <img src="../media/com_emailtemplates/images/ajax-loader.gif" width="16" height="16" style="display: none;" id="js-batch-ajaxloader" />
        <button class="btn btn-primary" type="submit" id="js-placeholders-btn-batch">
            <i class="icon icon-ok"></i>
            <?php echo JText::_('JGLOBAL_BATCH_PROCESS'); ?>
        </button>
		<button class="btn " type="button" data-dismiss="modal">
            <i class="icon icon-cancel"></i>
			<?php echo JText::_('JCANCEL'); ?>
		</button>
	</div>
</div>
