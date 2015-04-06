<?php
/**
 * @package		Joomla.Administrator
 * @subpackage	com_flexicontent
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// Include the component HTML helpers.
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');

// Load the tooltip behavior.
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$user		= JFactory::getUser();
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));

JText::script('COM_USERS_GROUPS_CONFIRM_DELETE');
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'groups.delete')
		{
			var f = document.adminForm;
			var cb='';
<?php foreach ($this->items as $i=>$item):?>
<?php if ($item->user_count > 0):?>
			cb = f['cb'+<?php echo $i;?>];
			if (cb && cb.checked) {
				if (confirm(Joomla.JText._('COM_USERS_GROUPS_CONFIRM_DELETE'))) {
					Joomla.submitform(task);
				}
				return;
			}
<?php endif;?>
<?php endforeach;?>
		}
		Joomla.submitform(task);
	}
</script>

<div class="flexicontent">
<form action="index.php?option=com_flexicontent" method="post" name="adminForm" id="adminForm">

<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

  <?php /*?>    <!--CAT BOX -->
      <div class="row-fluid">
        <div class="span12">
          <div class="block-flat"> 
            
            <!--FILTER-->
            <div id="fc-filters-header">
              <div class="btn-wrapper input-append leftfloat">
                <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_( 'FLEXI_SEARCH' ); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_USERS_SEARCH_IN_GROUPS'); ?>" class="inputbox" />
                <button type="submit" class="btn hasTooltip" title="" data-original-title="<?php echo JText::_( 'FLEXI_SEARCH' ); ?>" onclick="this.form.submit();"><i class="icon-search"></i> </button>
                <button type="button" class="btn hasTooltip" onclick="this.form.getElementById('search').value='';this.form.submit();" value="Clear"  data-original-title="Clear All"> <i class="icon-delete"></i></button>
              </div><div class="clear"></div>
              <!--/FILTER-->
              
              </div>
              </div>
              </div>
               <!--/CAT BOX --><?php */?>
               
               
     <!--LIMIT-->
      <div class="row-fluid">
        <div class="span6 limit text-right">
          <label class="label"> <?php echo JText::_(FLEXI_J16GE ? 'JGLOBAL_DISPLAY_NUM' : 'DISPLAY NUM'); ?> </label>
          <?php
					$pagination_footer = $this->pagination->getListFooter();
					if (strpos($pagination_footer, '"limit"') === false) echo $this->pagination->getLimitBox();
					?>
          <span class="fc_item_total_data fc_nice_box"> <?php echo @$this->resultsCounter ? $this->resultsCounter : $this->pagination->getResultsCounter(); // custom Results Counter ?> </span>
          <?php if (($getPagesCounter = $this->pagination->getPagesCounter())): ?>
          <span class="fc_pages_counter"> <?php echo $this->pagination->getPagesCounter(); ?> </span>
          <?php endif; ?>
        </div>
      </div>
      <!--/LIMIT--> 
      
      <div class="row-fluid">
<div class="span12">

<div class="block-flat">
<div class="table-responsive">  

	<table class="adminlist" cellspacing="1">
	<thead>
		<tr>
        
        
        	<table class="users table no-border hover">
		<thead class="no-border">
			<tr class="header">
				<th class="center w30px">
				<input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
			</th>
			<th>
				<?php echo JText::_('COM_USERS_HEADING_GROUP_TITLE'); ?>
			</th>
			<th class="center">
				<?php echo JText::_('COM_USERS_HEADING_USERS_IN_GROUP'); ?>
			</th>
			<th class="center w30px">
				<?php echo JText::_('JGRID_HEADING_ID'); ?>
			</th>
		</tr>
		
	</thead>

	<tfoot>
		<tr>
			<td colspan="4">
				<?php echo $pagination_footer; ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php foreach ($this->items as $i => $item) :
		$canCreate	= $user->authorise('core.create',		'com_users');
		$canEdit	= $user->authorise('core.edit',			'com_users');
		// If this group is super admin and this user is not super admin, $canEdit is false
		if (!$user->authorise('core.admin') && (JAccess::checkGroup($item->id, 'core.admin'))) {
			$canEdit = false;
		}
		$canChange	= $user->authorise('core.edit.state',	'com_users');
	?>
		<tr class="row<?php echo $i % 2; ?>">
			<td class="center w30px">
				<?php if ($canEdit) : ?>
					<?php echo JHtml::_('grid.id', $i, $item->id); ?>
				<?php endif; ?>
			</td>
			<td>
				<?php echo str_repeat('<span class="gi">|&mdash;</span>', $item->level) ?>
				<?php if ($canEdit) : ?>
				<a href="<?php echo JRoute::_('index.php?option=com_flexicontent&task=group.edit&id='.$item->id);?>">
					<?php echo $this->escape($item->title); ?></a>
				<?php else : ?>
					<?php echo $this->escape($item->title); ?>
				<?php endif; ?>
				<?php if (JDEBUG) : ?>
					<div class="fltrt"><div class="button2-left smallsub"><div class="blank"><a href="<?php echo JRoute::_('index.php?option=com_flexicontent&view=debuggroup&group_id='.(int) $item->id);?>">
					<?php echo JText::_('COM_USERS_DEBUG_GROUP');?></a></div></div></div>
				<?php endif; ?>
			</td>
			<td class="center">
				<?php echo $item->user_count ? $item->user_count : ''; ?>
			</td>
			<td class="center w30px">
				<?php echo (int) $item->id; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>

	</table>

</div>
</div>
</div>
</div>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
	
	</div>
</form>
</div>