<?php
/**
 * @version 1.5 stable $Id: default.php 1807 2013-11-14 01:43:15Z ggppdk $
 * @package Joomla
 * @subpackage FLEXIcontent
 * @copyright (C) 2009 Emmanuel Danan - www.vistamedia.fr
 * @license GNU/GPL v2
 * 
 * FLEXIcontent is a derivative work of the excellent QuickFAQ component
 * @copyright (C) 2008 Christoph Lukes
 * see www.schlu.net for more information
 *
 * FLEXIcontent is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

defined('_JEXEC') or die('Restricted access');

$user      = JFactory::getUser();

?>

<div class="flexicontent m20">



<form action="index.php" method="post" name="adminForm" id="adminForm">

<div class="row-fluid">
<div class="span12">

<div class="block-flat">
<!--Content-->
<div class="row-fluid">
<div class="span7">
<!--SEARCH-->
<label class="label searchx hidden-tablet hidden-phone"><?php echo JText::_( 'FLEXI_SEARCH' ); ?></label>
<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="form-control" placeholder="<?php echo JText::_( 'FLEXI_SEARCH' ); ?>" onchange="document.adminForm.submit();" />
				
					<button class="btn btn-primary btn-rad" onclick="this.form.submit();"><?php echo JText::_( 'FLEXI_GO' ); ?></button>
					<button class="btn btn-rad hidden-tablet hidden-phone" onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'FLEXI_RESET' ); ?></button>
<!--/SEARCH-->

</div>
<!--STATE SEARCH-->
<div class="span5 text-right smallx">
<div class="smallx">
<div class="s-state">
<i class="icon-search"></i> <?php echo $this->lists['state']; ?>
</div>
</div>
</div>
</div>
<!--STATE SEARCH-->
<!--/Content-->
</div>
</div>
</div>

  <!--LIMIT-->
<div class="row-fluid">
<div class="span12  text-right">
<div class="limit m10l hidden-tablet hidden-phone">
					<?php
					echo JText::_(FLEXI_J16GE ? 'JGLOBAL_DISPLAY_NUM' : 'DISPLAY NUM');
					$pagination_footer = $this->pagination->getListFooter();
					if (strpos($pagination_footer, '"limit"') === false) echo $this->pagination->getLimitBox();
					?>
                    	<span class="fc_item_total_data fc_nice_box m10l" >
					<?php echo @$this->resultsCounter ? $this->resultsCounter : $this->pagination->getResultsCounter(); // custom Results Counter ?>
				</span>
				
				<span class="fc_pages_counter">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</span>
				</div>
</div>
</div>
<!--/LIMIT-->  
<!--MAIN TABLE-->    
<div class="row-fluid">
<div class="span12">
<div class="block-flat">
<div class="table-responsive">
	<table class="table no-border no-border-x hover">
	<thead class="no-border">
		<tr class="header">
			<th  class="hidden-phone"><?php echo JText::_( 'FLEXI_Num' ); ?></th>
			<th><input type="checkbox" name="toggle" value="" onclick="<?php echo FLEXI_J30GE ? 'Joomla.checkAll(this);' : 'checkAll('.count( $this->rows).');'; ?>" /></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'FLEXI_TYPE_NAME', 't.name', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="hidden-tablet hidden-phone"><?php echo JText::_( 'FLEXI_TEMPLATE' )."<br/><small class=\"hidden-tablet hidden-phone\">(".JText::_( 'FLEXI_PROPERTY_DEFAULT' )." ".JText::_( 'FLEXI_TEMPLATE_ITEM' ).")</small>"; ?></th>
			<th  class="hidden-tablet hidden-phone"><?php echo JHTML::_('grid.sort', 'FLEXI_ALIAS', 't.alias', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th><?php echo JHTML::_('grid.sort', 'FLEXI_FIELDS', 'fassigned', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th><?php echo JHTML::_('grid.sort', 'FLEXI_ITEMS', 'iassigned', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<!--th width="7%"><?php // echo JHTML::_('grid.sort', 'ITEMS', 'iassigned', $this->lists['order_Dir'], $this->lists['order'] ); ?></th-->
			<th><?php echo JHTML::_('grid.sort', 'FLEXI_ACCESS', 't.access', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="center nowrap"><?php echo JText::_( 'FLEXI_PUBLISHED' ); ?></th>
			<th class="center nowrap hidden-tablet hidden-phone"><?php echo JHTML::_('grid.sort', 'FLEXI_ID', 't.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<td>
				<?php echo $pagination_footer; ?>
			</td>
		</tr>
	</tfoot>

	<tbody>
		<?php
		$k = 0;
		for ($i=0, $n=count($this->rows); $i < $n; $i++)
		{
			$row = & $this->rows[$i];
			if (FLEXI_J16GE) {
				$link 		= 'index.php?option=com_flexicontent&amp;task=types.edit&amp;cid[]='. $row->id;
				$published 	= JHTML::_('jgrid.published', $row->published, $i, 'types.' );
				$access		= flexicontent_html::userlevel('access['.$row->id.']', $row->access, 'onchange="return listItemTask(\'cb'.$i.'\',\'types.access\')"');
			} else {
				$link 		= 'index.php?option=com_flexicontent&amp;controller=types&amp;task=edit&amp;cid[]='. $row->id;
				$published 	= JHTML::_('grid.published', $row, $i );
				$access 	= JHTML::_('grid.access', $row, $i );
			}
			
			$checked	= @ JHTML::_('grid.checkedout', $row, $i );
			$fields		= 'index.php?option=com_flexicontent&amp;view=fields&amp;filter_type='. $row->id;
			$items		= 'index.php?option=com_flexicontent&amp;view=items&amp;filter_type='. $row->id;
			$canEdit    = 1;
			$canEditOwn = 1;
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td class="hidden-phone"><?php echo $this->pagination->getRowOffset( $i ); ?></td>
			<td><?php echo $checked; ?></td>
			<td class="hidden-tablet hidden-phone">
				<?php
				
				// Display an icon with checkin link, if current user has checked out current item
				if ($row->checked_out) {
					if (FLEXI_J16GE) {
						$canCheckin = $user->authorise('core.admin', 'checkin');
					} else if (FLEXI_ACCESS) {
						$canCheckin = ($user->gid < 25) ? FAccess::checkComponentAccess('com_checkin', 'manage', 'users', $user->gmid) : 1;
					} else {
						$canCheckin = $user->gid >= 24;
					}
					if ($canCheckin) {
						//if (FLEXI_J16GE && $row->checked_out == $user->id) echo JHtml::_('jgrid.checkedout', $i, $row->editor, $row->checked_out_time, 'types.', $canCheckin);
						$task_str = FLEXI_J16GE ? 'types.checkin' : 'checkin';
						if ($row->checked_out == $user->id) {
							echo JText::sprintf('FLEXI_CLICK_TO_RELEASE_YOUR_LOCK', $row->editor, $row->checked_out_time, '"cb'.$i.'"', '"'.$task_str.'"');
						} else {
							echo '<input id="cb'.$i.'" type="checkbox" value="'.$row->id.'" name="cid[]" style="display:none;">';
							echo JText::sprintf('FLEXI_CLICK_TO_RELEASE_FOREIGN_LOCK', $row->editor, $row->checked_out_time, '"cb'.$i.'"', '"'.$task_str.'"');
						}
					}
				}
				
				// Display title with no edit link ... if row checked out by different user -OR- is uneditable
				if ( ( $row->checked_out && $row->checked_out != $user->id ) || ( !$canEdit ) ) {
					echo htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8');
				
				// Display title with edit link ... (row editable and not checked out)
				} else {
				?>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'FLEXI_EDIT_ITEM' );?>::<?php echo $row->name; ?>">
					<a href="<?php echo $link; ?>">
					<?php echo htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8'); ?>
					</a></span>
				<?php
				}
				?>
			</td>
			<td  class="hidden-tablet hidden-phone">
				<?php echo $row->config->get("ilayout"); ?>
			</td>
			<td>
				<?php
				if (JString::strlen($row->alias) > 25) {
					echo JString::substr( htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8'), 0 , 25).'...';
				} else {
					echo htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8');
				}
				?>
			</td>
			<td>
				<?php echo $row->fassigned; ?>
				<a href="<?php echo $fields; ?>">
				[<?php echo JText::_( 'FLEXI_VIEW_FIELDS' );?>]
				</a>
			</td>
			<td>
				<?php echo $row->iassigned; ?>
				<a href="<?php echo $items; ?>">
				[<?php echo JText::_( 'FLEXI_VIEW_ITEMS' );?>]
				</a>
			</td>
			<td class="access">
				<?php echo $access; ?>
			</td>
			<td class="center nowrap">
				<?php echo $published; ?>
			</td>
			<td class="center nowrap hidden-tablet hidden-phone"><?php echo $row->id; ?></td>
		</tr>
		<?php $k = 1 - $k; } ?>
	</tbody>

	</table>
</div>
</div>
</div>
</div>

	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_flexicontent" />
	<input type="hidden" name="controller" value="types" />
	<input type="hidden" name="view" value="types" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

</div><!--/.flexicontent-->