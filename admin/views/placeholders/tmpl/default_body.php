<?php
/**
 * @package      EmailTemplates
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

if ($this->saveOrder) {
    $saveOrderingUrl = 'index.php?option=com_emailtemplates&task=placeholders.saveOrderAjax&format=raw';
    JHtml::_('sortablelist.sortable', 'placeholderList', 'adminForm', strtolower($this->listDirn), $saveOrderingUrl);
}
?>
<?php foreach ($this->items as $i => $item) {
    $ordering   = ($this->listOrder == 'a.ordering');
    ?>
	<tr class="row<?php echo $i % 2;?>"  sortable-group-id="<?php echo $item->catid; ?>">
        <td class="order nowrap center hidden-phone">
            <?php
            $iconClass = '';
            if (!$this->saveOrder) {
                $iconClass = ' inactive tip-top hasTooltip" title="' . JHtml::tooltipText('JORDERINGDISABLED');
            }
            ?>
            <span class="sortable-handler<?php echo $iconClass ?>">
                <i class="icon-menu"></i>
            </span>
            <?php if ($this->saveOrder) : ?>
                <input type="text" style="display:none" name="order[]" size="5" value="<?php echo $item->ordering; ?>" class="width-20 text-area-order " />
            <?php endif; ?>
        </td>

		<td class="center hidden-phone">
            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td class="title has-context">
			<a href="<?php echo JRoute::_("index.php?option=com_emailtemplates&view=placeholder&layout=edit&id=".$item->id); ?>" >
		        <?php echo $this->escape($item->name); ?>
	        </a>
            <div class="small">
                <?php echo JText::sprintf("COM_EMAILTEMPLATES_CATEGORY_S", $item->category); ?>
            </div>
	    </td>
		<td>
			<?php echo $this->escape($item->description); ?>
		</td>
        <td class="center hidden-phone"><?php echo $item->id;?></td>
	</tr>
<?php } ?>
	  