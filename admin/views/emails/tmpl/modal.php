<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$input     = JFactory::getApplication()->input;
$field     = $input->getCmd('field');
$function  = 'jSelectEmailTemplate_' . $field;
?>
<form action="<?php echo JRoute::_('index.php?option=com_emailtemplates&view=emails');?>" method="post" name="adminForm" id="adminForm">
    <?php
    // Search tools bar
    echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));
    ?>
    <br />
    <button type="button" class="btn" onclick="if (window.parent) window.parent.<?php echo $this->escape($function); ?>('', '<?php echo JText::_('LIB_EMAILTEMPLATES_SELECT_EMAIL_TEMPLATE'); ?>');">
        <i class="icon-cancel"></i>
        <?php echo JText::_('COM_EMAILTEMPLATES_NO_EMAIL_TEMPLATE'); ?>
    </button>

	<table class="table table-striped table-condensed" id="emailsList">

		<thead>
			<tr>
				<th class="left">
                    <?php echo JHtml::_('searchtools.sort', 'COM_EMAILTEMPLATES_TITLE', 'a.title', $this->listDirn, $this->listOrder); ?>
				</th>
                <th width="3%" class="center nowrap hidden-phone">
                    <?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $this->listDirn, $this->listOrder); ?>
                </th>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<td colspan="2">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>

		<tbody>
		<?php
			$i = 0;

			foreach ($this->items as $item) : ?>
			<tr class="row<?php echo $i % 2; ?>">
				<td>
					<a style="cursor: pointer;" onclick="if (window.parent) window.parent.<?php echo $this->escape($function);?>('<?php echo $item->id; ?>', '<?php echo $this->escape(addslashes($item->title)); ?>');">
                        <?php echo $this->escape($item->title); ?>
                    </a>
				</td>
                <td class="center hidden-phone">
                    <?php echo $item->id;?>
                </td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
	<div>
		<input type="hidden" name="task" value="" />
		<input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="field" value="<?php echo $this->escape($field); ?>" />
        <input type="hidden" name="layout" value="modal" />
        <input type="hidden" name="tmpl" value="component" />
		<?php echo JHtml::_('form.token'); ?>
	</div>
</form>
