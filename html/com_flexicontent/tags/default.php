<?php
/**
 * @version 1.5 stable $Id$
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

$tip_class = FLEXI_J30GE ? ' hasTooltip' : ' hasTip';
$btn_class = FLEXI_J30GE ? 'btn' : 'btn btn-primary';

$start_text = '<span class="label">'.JText::_('FLEXI_COLUMNS', true).'</span>';
$end_text = '<div class="icon-cancel" title="'.JText::_('FLEXI_HIDE').'" style="cursor: pointer;" onclick="fc_toggle_box_via_btn(\\\'mainChooseColBox\\\', document.getElementById(\\\'fc_mainChooseColBox_btn\\\'), \\\'active\\\');"></div>';
flexicontent_html::jscode_to_showhide_table('mainChooseColBox', 'adminListTableFCtags', $start_text, $end_text);


$user    = JFactory::getUser();
$cparams = JComponentHelper::getParams( 'com_flexicontent' );
$autologin = '';//$cparams->get('autoflogin', 1) ? '&amp;fcu='.$user->username . '&amp;fcp='.$user->password : '';

$attribs_preview = ' style="float:right;" class="hasTip" title="'.JText::_('FLEXI_PREVIEW').':: Click to display the frontend view of this category in a new browser window" ';

$image_preview = FLEXI_J16GE ?
	JHTML::image( 'administrator/templates/'.$template.'/images/flexi/'.'monitor_go.png', JText::_('FLEXI_PREVIEW'),  $attribs_preview) :
	JHTML::_('image.site', 'monitor_go.png', 'administrator/templates/'.$template.'/images/flexi/', NULL, NULL, JText::_('FLEXI_PREVIEW'), $attribs_preview) ;

$list_total_cols = 8;
?>
<script type="text/javascript">

// delete active filter
function delFilter(name)
{
	//window.console.log('Clearing filter:'+name);
	var myForm = jQuery('#adminForm');
	var filter = jQuery('#'+name);
	if (filter.attr('type')=='checkbox')
		filter.checked = '';
	else
		filter.val('');
}

function delAllFilters() {
	delFilter('search'); delFilter('filter_assigned');  delFilter('filter_state');
}

</script>

<div class="flexicontent">

<form action="index.php?option=<?php echo $this->option; ?>&view=<?php echo $this->view; ?>" method="post" name="adminForm" id="adminForm">

<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

 <!--CAT BOX -->
      <div class="row-fluid">
        <div class="span12">
          <div class="block-flat"> 
            <!--FILTER-->
            <div id="fc-filters-header">
              <div class="btn-wrapper input-append leftfloat">
                <input type="text" name="search" id="search" placeholder="<?php echo JText::_( 'FLEXI_SEARCH' ); ?>" value="<?php echo $this->lists['search']; ?>" class="inputbox" />
                <button type="submit" class="btn hasTooltip" title="" data-original-title="Search"> <i class="icon-search"></i> </button>
                <button type="button" class="btn hasTooltip" onclick="delAllFilters();this.form.submit();" value="Clear"  data-original-title="Clear All"> <i class="icon-delete"></i></button>
              </div>
              <?php $_class = FLEXI_J30GE ? ' btn btn-primary btn-rad' : ' btn btn-primary btn-rad'; ?>
              <div class="btn-group ml40">
                <button type="button" id="fc_filters_box_btn" class="<?php echo $_class.($this->count_filters ? ' btn-primary' : ''); ?>" onclick="fc_toggle_box_via_btn('fc-filters-box', this, 'active');" value="<?php echo JText::_( 'FLEXI_FILTERS' ); ?>" />
                <i class="fa icon-filter"></i> <?php echo JText::_( 'FLEXI_FILTERS' ); ?>
                </button>
                <button type="button" id="fc_mainChooseColBox_btn" class="<?php echo $_class; ?> h1000" onclick="fc_toggle_box_via_btn('mainChooseColBox', this, 'active');" value="<?php echo JText::_( 'FLEXI_COLUMNS' ); ?>" />
                <i class="fa icon-eye"></i> <?php echo JText::_( 'FLEXI_COLUMNS' ); ?>
                </button>
              </div>
              <div class="clear"></div>
            </div>
            <!--/ FILTER--> 
          </div>
        </div>
      </div>
      <!--CAT BOX -->
	
	
	<div id="fc-filters-box"  <?php if (!$this->count_filters) echo 'style="display:none;"'; ?>  class="flexheader">
		<!--<span class="label"><?php echo JText::_( 'FLEXI_FILTERS' ); ?></span>-->
		
        <div class="row-fluid">
          <div class="span10"> 
            <!--FILTERS-->
            <div class="cat-filters">
              <div class="smallx">
        
		<span class="boxy">
			<i class="icon-checkmark-2"></i> <?php echo $this->lists['assigned']; ?>
		</span>
		
		<span class="boxy">
			<i class="icon-search"></i> <?php echo $this->lists['state']; ?>
		</span>
		
	 <div class="break-sm">
                <button class="btn btn-success btn-flat btn-rad" type="submit" onclick="this.form.submit();" value="<?php echo JText::_( 'FLEXI_APPLY_FILTERS' ); ?>" ><i class="fa icon-filter"></i> <?php echo JText::_( 'FLEXI_APPLY_FILTERS' ); ?></button>
                <button class="btn btn-danger btn-flat btn-rad" type="submit" onclick="delAllFilters();this.form.submit();" value="<?php echo JText::_( 'FLEXI_CLEAR' ); ?>" ><i class="fa icon-cancel"></i> <?php echo JText::_( 'FLEXI_CLEAR' ); ?></button>
              </div>
            </div>
			
  <!--/FILTERS--> 
          </div>
        </div>
        <div class="icon-cancel" title="<?php echo JText::_('FLEXI_HIDE'); ?>" style="cursor: pointer;" onclick="fc_toggle_box_via_btn('fc-filters-box', document.getElementById('fc_filters_box_btn'), 'active');"></div>
      </div>
      </div>
	<div id="mainChooseColBox" class="fc_mini_note_box well well-small" style="display:none;"></div>
	
	<div class="fcclear"></div>
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

	<table id="adminListTableFCtags" class="adminlist fcmanlist table no-border no-border-x hover cats">
	<thead>
		<tr>
			<th class="center nowrap h500"><?php echo JText::_( 'FLEXI_NUM' ); ?></th>
			<th class="center nowrap h400"><input type="checkbox" name="toggle" value="" onclick="<?php echo FLEXI_J30GE ? 'Joomla.checkAll(this);' : 'checkAll('.count( $this->rows).');'; ?>" /></th>
			<th class="nowrap h800"></th>
			<th class="hideOnDemandClass title"><?php echo JHTML::_('grid.sort', 'FLEXI_TAG_NAME', 't.name', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="hideOnDemandClass h400"><?php echo JHTML::_('grid.sort', 'FLEXI_ALIAS', 't.alias', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="center hideOnDemandClass"><?php echo JHTML::_('grid.sort', 'FLEXI_ASSIGNED_TO', 'nrassigned', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="center hideOnDemandClass nowrap"><?php echo JHTML::_('grid.sort', 'FLEXI_PUBLISHED', 't.published', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="center hideOnDemandClass nowrap h500"><?php echo JHTML::_('grid.sort', 'FLEXI_ID', 't.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<td colspan="<?php echo $list_total_cols; ?>">
				<?php echo $pagination_footer; ?>
			</td>
		</tr>
	</tfoot>

	<tbody>
		<?php
		if (FLEXI_J16GE) {
			$canCheckinRecords = $user->authorise('core.admin', 'checkin');
		} else if (FLEXI_ACCESS) {
			$canCheckinRecords = ($user->gid < 25) ? FAccess::checkComponentAccess('com_checkin', 'manage', 'users', $user->gmid) : 1;
		} else {
			$canCheckinRecords = $user->gid >= 24;
		}
		$edit_task = FLEXI_J16GE ? 'task=tags.' : 'controller=tags&amp;task=';
		
		$k = 0;
		
		if (!count($this->rows)) echo '<tr class="collapsed_row"><td colspan="'.$list_total_cols.'"></td></tr>';  // Collapsed row to allow border styling to apply		$k = 0;
		for ($i=0, $n=count($this->rows); $i < $n; $i++)
		{
			$row = & $this->rows[$i];

			$link 		= 'index.php?option=com_flexicontent&amp;'.$edit_task.'edit&amp;cid[]='. $row->id;
			if (FLEXI_J16GE)	$published	= JHTML::_('jgrid.published', $row->published, $i, 'tags.' );
			else							$published	= JHTML::_('grid.published', $row, $i );
			
			$checked 	= @ JHTML::_('grid.checkedout', $row, $i );
			$canEdit    = 1;
			$canEditOwn = 1;
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td class="center h500"><?php echo $this->pagination->getRowOffset( $i ); ?></td>
			<td class="center h400"><?php echo $checked; ?></td>
			<td class="center h800">
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
					// Record check-in is allowed if either (a) current user has Global Checkin privilege OR (b) record checked out by current user
					$canCheckin = $canCheckinRecords || $row->checked_out == $user->id;
					if ($canCheckin) {
						//if (FLEXI_J16GE && $row->checked_out == $user->id) echo JHtml::_('jgrid.checkedout', $i, $row->editor, $row->checked_out_time, 'tags.', $canCheckin);
						$task_str = FLEXI_J16GE ? 'tags.checkin' : 'checkin';
						if ($row->checked_out == $user->id) {
							echo JText::sprintf('FLEXI_CLICK_TO_RELEASE_YOUR_LOCK', $row->editor, $row->checked_out_time, '"cb'.$i.'"', '"'.$task_str.'"');
						} else {
							echo '<input id="cb'.$i.'" type="checkbox" value="'.$row->id.'" name="cid[]" style="display:none!important;">';
							echo JText::sprintf('FLEXI_CLICK_TO_RELEASE_FOREIGN_LOCK', $row->editor, $row->checked_out_time, '"cb'.$i.'"', '"'.$task_str.'"');
						}
					} else {
						echo '<span class="fc-noauth">'.JText::sprintf('FLEXI_RECORD_CHECKED_OUT_DIFF_USER').'</span><br/>';
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
			<td class="h400">
				<?php
				if (JString::strlen($row->alias) > 25) {
					echo JString::substr( htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8'), 0 , 25).'...';
				} else {
					echo htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8');
				}
				?>
			</td>
			<td class="center"><span class="badge badge-info"><?php echo $row->nrassigned ? $row->nrassigned : 0; ?></span></td>
			<td class="center">
				<?php echo $published; ?>
			</td>
			<td class="center h500"><?php echo $row->id; ?></td>
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
	
	</div>
</form>
</div>