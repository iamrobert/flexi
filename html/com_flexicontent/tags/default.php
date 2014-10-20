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
$cparams   = JComponentHelper::getParams( 'com_flexicontent' );
$autologin = $cparams->get('autoflogin', 1) ? '&amp;fcu='.$user->username . '&amp;fcp='.$user->password : '';


$attribs_preview = ' style="float:right;" class="hasTip" title="'.JText::_('FLEXI_PREVIEW').':: Click to display the frontend view of this category in a new browser window" ';

$image_preview = FLEXI_J16GE ?
	JHTML::image( 'administrator/templates/flexi/images/flexi/'.'monitor_go.png', JText::_('FLEXI_PREVIEW'),  $attribs_preview) :
	JHTML::_('image.site', 'monitor_go.png', 'administrator/templates/flexi/images/flexi/', NULL, NULL, JText::_('FLEXI_PREVIEW'), $attribs_preview) ;
?>

<div class="flexicontent m20">
<form action="index.php" method="post" name="adminForm" id="adminForm">


<div class="row-fluid">
<div class="span12">

<div class="block-flat">
<!--Content-->
<div class="row-fluid">
<div class="span5 w100">
<!--SEARCH-->
<label class="label searchx hidden-tablet hidden-phone"><?php echo JText::_( 'FLEXI_SEARCH' ); ?></label>
<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" onchange="document.adminForm.submit();" class="form-control" placeholder="<?php echo JText::_( 'FLEXI_SEARCH' ); ?>"> 
<button class="btn btn-primary btn-rad" onclick="this.form.submit();"><?php echo JText::_( 'FLEXI_GO' ); ?></button>
<button class="btn btn-rad" onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'FLEXI_RESET' ); ?></button>
<!--/SEARCH--> 
      </div>
  <div class="span7 text-right line w100">
  <!--FILTERS-->
<div class="smallx">               
                	<div class="s-filter"><i class="icon-filter"></i> <?php echo $this->lists['assigned']; ?></div>
					<div class="s-state"><i class="icon-search"></i> <?php echo $this->lists['state']; ?></div>
</div>
  <!--/FILTERS-->
  </div>  
  </div>
     <!--Content-->   



       

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
			<th class="hidden-tablet hidden-phone"><?php echo JText::_( 'FLEXI_NUM' ); ?></th>
			<th><input type="checkbox" name="toggle" value="" onclick="<?php echo FLEXI_J30GE ? 'Joomla.checkAll(this);' : 'checkAll('.count( $this->rows).');'; ?>" /></th>
			<th class="nowrap hidden-tablet hidden-phone">&nbsp;</th>
			<th><?php echo JHTML::_('grid.sort', 'FLEXI_TAG_NAME', 't.name', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th><?php echo JHTML::_('grid.sort', 'FLEXI_ALIAS', 't.alias', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th><?php echo JHTML::_('grid.sort', 'FLEXI_ASSIGNED_TO', 'nrassigned', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="nowrap"><?php echo JText::_( 'FLEXI_PUBLISHED' ); ?></th>
			<th class="nowrap hidden-tablet hidden-phone"><?php echo JHTML::_('grid.sort', 'FLEXI_ID', 't.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<td colspan="8">
				<?php echo $pagination_footer; ?>
			</td>
		</tr>
	</tfoot>

	<tbody>
		<?php
		$edit_task = FLEXI_J16GE ? 'task=tags.' : 'controller=tags&amp;task=';
		$k = 0;
		for ($i=0, $n=count($this->rows); $i < $n; $i++)
		{
			$row = & $this->rows[$i];

			$link 		= 'index.php?option=com_flexicontent&amp;'.$edit_task.'edit&amp;cid[]='. $row->id;
			if (FLEXI_J16GE)	$published	= JHTML::_('jgrid.published', $row->published, $i, 'tags.' );
			else							$published	= JHTML::_('grid.published', $row, $i );
			
			$checked	= @ JHTML::_('grid.checkedout', $row, $i );
			$canEdit    = 1;
			$canEditOwn = 1;
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td class="hidden-tablet hidden-phone"><?php echo $this->pagination->getRowOffset( $i ); ?></td>
			<td><?php echo $checked; ?></td>
			<td class="hidden-tablet hidden-phone">
				<?php
				$tag_link    = str_replace('&', '&amp;', FlexicontentHelperRoute::getTagRoute($row->id));
				$tag_link    = JRoute::_(JURI::root().$tag_link, $xhtml=false);  // xhtml to false we do it manually above (at least the ampersand) also it has no effect because we prepended the root URL ?
				$previewlink = $tag_link . $autologin;
				echo '<a class="preview" href="'.$previewlink.'" target="_blank">'.$image_preview.'</a>';
				?>
			</td>
			<td>
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
						//if (FLEXI_J16GE && $row->checked_out == $user->id) echo JHtml::_('jgrid.checkedout', $i, $row->editor, $row->checked_out_time, 'tags.', $canCheckin);
						$task_str = FLEXI_J16GE ? 'tags.checkin' : 'checkin';
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
						</a>
					</span>
				<?php
				}
				?>
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
			<td class="center"><?php echo $row->nrassigned?></td>
			<td class="center">
				<?php echo $published; ?>
			</td>
			<td class="center hidden-tablet hidden-phone"><?php echo $row->id; ?></td>
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
	<input type="hidden" name="controller" value="tags" />
	<input type="hidden" name="view" value="tags" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>