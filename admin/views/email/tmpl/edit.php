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
?>
<div class="row-fluid">
    <div class="span8 form-horizontal">
        <form action="<?php echo JRoute::_('index.php?option=com_emailtemplates'); ?>" method="post" name="adminForm" id="adminForm" class="form-validate" >
            
            <fieldset class="adminform">

                <?php echo $this->form->getControlGroup('title'); ?>
                <?php echo $this->form->getControlGroup('subject'); ?>
                <?php echo $this->form->getControlGroup('sender_name'); ?>
                <?php echo $this->form->getControlGroup('sender_email'); ?>
                <?php echo $this->form->getControlGroup('catid'); ?>
                <?php echo $this->form->getControlGroup('body'); ?>
                <?php echo $this->form->getControlGroup('id'); ?>
                
            </fieldset>
            
            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
    <?php if (!empty($this->categories)) {?>
    <div class="span4">
        <h3><?php echo JText::_("COM_EMAILTEMPLATES_PLACEHOLDERS_LIST");?></h3>
        <p class="small"><?php echo JText::_("COM_EMAILTEMPLATES_PLACEHOLDERS_INFO");?></p>

        <?php echo JHtml::_('bootstrap.startAccordion', 'category-group', array('active' => 'category_'.$this->categories[0]["id"])); ?>

        <?php foreach ($this->categories as $category) {?>
        <?php echo JHtml::_('bootstrap.addSlide', 'category-group', $this->escape($category["title"]), 'category_'.$category["id"]); ?>
        <dl class="dl-horizontal">
            <?php foreach ($category["placeholders"] as $placeholder) { ?>
            <dt><?php echo $placeholder["name"]; ?></dt>
            <dd><?php echo $placeholder["description"]; ?></dd>
            <?php } ?>
        </dl>

        <?php echo JHtml::_('bootstrap.endSlide'); ?>
        <?php } ?>

        <?php echo JHtml::_('bootstrap.endAccordion'); ?>

        <p class="small alert"><?php echo JText::_("COM_EMAILTEMPLATES_EMAIL_EXTRA_LINE");?></p>
    </div>
    <?php } ?>
</div>