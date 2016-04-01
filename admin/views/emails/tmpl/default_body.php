<?php
/**
 * @package      EmailTemplates
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
?>
<?php foreach ($this->items as $i => $item) {?>
	<tr class="row<?php echo $i % 2;?>">
		<td class="center hidden-phone">
            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
        </td>
        <td class="title has-context">
			<a href="<?php echo JRoute::_("index.php?option=com_emailtemplates&view=email&layout=edit&id=".$item->id); ?>" >
		        <?php echo $this->escape($item->title); ?>
	        </a>
            <div class="small">
                <?php echo JText::sprintf("COM_EMAILTEMPLATES_CATEGORY_S", $item->category); ?>
            </div>
	    </td>
        <td class="hidden-phone">
            <?php echo $this->escape($item->subject); ?>
	    </td>
		<td class="center hidden-phone"><?php echo $this->escape($item->sender_name); ?></td>
		<td class="center hidden-phone"><?php echo $this->escape($item->sender_email); ?></td>
        <td class="center hidden-phone"><?php echo $item->id;?></td>
	</tr>
<?php } ?>
	  