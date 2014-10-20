<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_flexicontent
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
$canDo = UsersHelper::getActions();
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'group.cancel' || document.formvalidator.isValid(document.id('group-form'))) {
			Joomla.submitform(task, document.getElementById('group-form'));
		}
	}
</script>
<div class="m20x">
<form action="<?php echo JRoute::_('index.php?option=com_flexicontent&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="group-form" class="form-validate">
<div class="row-fluid">
<div class="span12">
<div class="block-flat">
		<fieldset class="form-horizontal">
			<legend><?php echo JText::_('COM_USERS_USERGROUP_DETAILS');?></legend>
<div class="form-inline m10x"><div class="control-group"><?php echo $this->form->getLabel('title'); ?>
				<div class="controls"><?php echo $this->form->getInput('title'); ?>
                </div></div></div>

				<?php $parent_id = $this->form->getField('parent_id');?>
					
<div class="form-inline m10x"><div class="control-group"><?php if (!$parent_id->hidden) echo $parent_id->label; ?>
				<div class="controls"><?php echo $parent_id->input; ?>
                </div></div></div>
		</fieldset>
        
		<input type="hidden" name="task" value="" />
		<?php echo JHtml::_('form.token'); ?>
</div>
</div></div>
</form>
</div>
<div class="clr"></div>
