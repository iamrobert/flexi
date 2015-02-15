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
$btn_class = FLEXI_J30GE ? 'btn' : 'fc_button fcsimple';

$start_text = '<span class="label">'.JText::_('FLEXI_COLUMNS', true).'</span>';
$end_text = '<div class="icon-cancel" title="'.JText::_('FLEXI_HIDE').'" style="cursor: pointer;" onclick="fc_toggle_box_via_btn(\\\'mainChooseColBox\\\', document.getElementById(\\\'fc_mainChooseColBox_btn\\\'), \\\'active\\\');"></div>';
flexicontent_html::jscode_to_showhide_table('mainChooseColBox', 'adminListTableFCcats', $start_text, $end_text);

$listOrder  = $this->lists['order'];
$listDirn   = $this->lists['order_Dir'];
$saveOrder  = ($listOrder == 'c.lft' && $listDirn == 'asc');

$user    = JFactory::getUser();
$cparams = JComponentHelper::getParams( 'com_flexicontent' );
$autologin = '';//$cparams->get('autoflogin', 1) ? '&amp;fcu='.$user->username . '&amp;fcp='.$user->password : '';

$attribs_preview = ' style="float:right;" class="hasTip" title="'.JText::_('FLEXI_PREVIEW').':: Click to display the frontend view of this category in a new browser window" ';
$attribs_rsslist = ' style="float:right;" class="hasTip" title="'.JText::_('FLEXI_FEED')   .':: Click to display the frontend RSS listing of this category in a new browser window" ';

$image_preview = FLEXI_J16GE ?
	JHTML::image( 'administrator/templates/'.$template.'/images/flexi/'.'monitor_go.png', JText::_('FLEXI_PREVIEW'),  $attribs_preview) :
	JHTML::_('image.site', 'monitor_go.png', 'components/com_flexicontent/assets/images/', NULL, NULL, JText::_('FLEXI_PREVIEW'), $attribs_preview) ;
$image_rsslist = FLEXI_J16GE ?
	JHTML::image( 'administrator/templates/'.$template.'/images/flexi/'.'livemarks.png', JText::_('FLEXI_FEED'), $attribs_rsslist ) :
	JHTML::_('image.site', 'livemarks.png', 'components/com_flexicontent/assets/images/', NULL, NULL, JText::_('FLEXI_FEED'), $attribs_rsslist ) ;

$image_flag_path = !FLEXI_J16GE ? "../components/com_joomfish/images/flags/" : "../media/mod_languages/images/";
$infoimage  = JHTML::image ( 'administrator/templates/'.$template.'/images/flexi/lightbulb.png', JText::_( 'FLEXI_NOTES' ) );
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
	delFilter('search'); delFilter('filter_state'); delFilter('filter_cats');
	delFilter('filter_level'); delFilter('filter_access'); delFilter('filter_language');
}

</script>

<div class="flexicontent">
  <form action="index.php" method="post" name="adminForm" id="adminForm">
    <?php if (!empty( $this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2"> <?php echo $this->sidebar; ?> </div>
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
                		<button type="button" id="fc_filters_box_btn" class="<?php echo $_class.($this->count_filters ? ' btn-primary' : ''); ?>" onclick="fc_toggle_box_via_btn('fc-filters-box', this, 'active');" value="<?php echo JText::_( 'FLEXI_FILTERS' ); ?>" /><i class="fa icon-filter"></i> <?php echo JText::_( 'FLEXI_FILTERS' ); ?></button>
		<button type="button" id="fc_mainChooseColBox_btn" class="<?php echo $_class; ?>" onclick="fc_toggle_box_via_btn('mainChooseColBox', this, 'active');" value="<?php echo JText::_( 'FLEXI_COLUMNS' ); ?>" /><i class="fa icon-eye"></i> <?php echo JText::_( 'FLEXI_COLUMNS' ); ?></button>
              </div>
              <div class="clear"></div>
            </div>
            <!--/ FILTER--> 
            
          </div>
        </div>
      </div>
      <!--/ CAT BOX -->
      <div id="fc-filters-box" <?php if (!$this->count_filters) echo 'style="display:none;"'; ?> class="flexheader"> 
        <!--<span class="label"><?php echo JText::_( 'FLEXI_FILTERS' ); ?></span>-->
        
        <div class="row-fluid">
          <div class="span10"> 
            <!--FILTERS-->
            <div class="cat-filters">
              <div class="smallx"> <span class="boxy"><i class="icon-folder"></i> <?php echo $this->lists['cats']; ?></span> <span class="boxy"><i class="icon-tree-2"></i> <?php echo $this->lists['level']; ?></span> <span class="boxy"><i class="icon-search"></i> <?php echo $this->lists['state']; ?></span> <span class="boxy"><i class="icon-key"></i> <?php echo $this->lists['access']; ?></span> 
              
              <span class="boxy hlang"><i class="icon-comments-2"></i>
                <?php if (FLEXI_J16GE) echo $this->lists['language']; ?>
                </span> </div>
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
      
      <div id="mainChooseColBox" class="fc_mini_note_box well well-small" style="display:none;"></div>


<!--LIMIT-->
<div class="row-fluid">


<div class="span6 limit text-right">
<label class="label">
						<?php echo JText::_(FLEXI_J16GE ? 'JGLOBAL_DISPLAY_NUM' : 'DISPLAY NUM'); ?>
					</label>
<?php
					$pagination_footer = $this->pagination->getListFooter();
					if (strpos($pagination_footer, '"limit"') === false) echo $this->pagination->getLimitBox();
					?>
                    
                    <span class="fc_item_total_data fc_nice_box">
					<?php echo @$this->resultsCounter ? $this->resultsCounter : $this->pagination->getResultsCounter(); // custom Results Counter ?>
				</span>
	<?php if (($getPagesCounter = $this->pagination->getPagesCounter())): ?>			
				<span class="fc_pages_counter">
					<?php echo $this->pagination->getPagesCounter(); ?>
				</span>
        	<?php endif; ?>            
</div>
</div>
<!--/LIMIT-->
      <!--MAIN TABLE-->
    <div class="row-fluid">
      <div class="span12">
        <div class="block-flat">
          <div class="table-responsive"> 
	<table id="adminListTableFCcats" class="adminlist table no-border no-border-x hover cats">
              <thead class="no-border">
                <tr class="header">
                  <th class="col_no hidden-tablet hidden-phone"><?php echo JText::_( 'FLEXI_NUM' ); ?></th>
                  <th><input type="checkbox" name="toggle" value="" onclick="<?php echo FLEXI_J30GE ? 'Joomla.checkAll(this);' : 'checkAll('.count( $this->rows).');'; ?>" /></th>
                  <th class="nowrap hidden-tablet hidden-phone ">&nbsp;</th>
                  <th class="nowrap h1200">&nbsp;</th>
                  <th class="hideOnDemandClass title"><?php echo JHTML::_('grid.sort', 'FLEXI_CATEGORY', 'c.title', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                  <th class="hideOnDemandClass col_alias h1200"><?php echo JHTML::_('grid.sort', 'FLEXI_ALIAS', 'c.alias', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                  <th class="hideOnDemandClass col_templ hidden-tablet hidden-phone"><?php echo JText::_( 'FLEXI_TEMPLATE' ); ?></th>
                  <th class="hideOnDemandClass hidden-tablet hidden-phone"><?php echo JHTML::_('grid.sort', 'FLEXI_ITEMS_ASSIGNED', 'nrassigned', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                  <th class="hideOnDemandClass center nowrap"><?php echo JText::_( 'FLEXI_PUBLISHED' ); ?></th>
                  <th class="hideOnDemandClass center"><?php echo JHTML::_('grid.sort', 'FLEXI_ACCESS', 'c.access', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                  <th class="hideOnDemandClass center hidden-phone"> <?php echo JHTML::_('grid.sort', 'FLEXI_REORDER', 'c.lft', $this->lists['order_Dir'], $this->lists['order'] ); ?> <?php echo $this->orderingx ? JHTML::_('grid.order', $this->rows, 'filesave.png', 'categories.saveorder' ) : ''; ?> </th>
                  <th class="hideOnDemandClass col_lang center nowrap  hidden-phone hlang"> <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $this->lists['order_Dir'], $this->lists['order'] ); ?> </th>
                  <th class="hideOnDemandClass col_id nowrap h1200"> <?php echo JHTML::_('grid.sort', 'FLEXI_ID', 'c.id', $this->lists['order_Dir'], $this->lists['order'] ); ?> </th>
                </tr>
              </thead>
              <tfoot>
                <tr>
                  <td colspan="13"><?php echo $pagination_footer; ?></td>
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
		
		if (FLEXI_J16GE) {
			$originalOrders = array();
			$extension	= 'com_content';
		}
		
		$k = 0;
		$i = 0;
		foreach ($this->rows as $row)
		{
			if (FLEXI_J16GE) {
				$canEdit		= $user->authorise('core.edit', $extension.'.category.'.$row->id);
				$canEditOwn	= $user->authorise('core.edit.own', $extension.'.category.'.$row->id) && $row->created_user_id == $user->get('id');
				$canEditState			= $user->authorise('core.edit.state', $extension.'.category.'.$row->id);
				$canEditStateOwn	= $user->authorise('core.edit.state.own', $extension.'.category.'.$row->id) && $row->created_user_id==$user->get('id');
				$recordAvailable	= ($canCheckinRecords && $row->checked_out == $user->id) || !$row->checked_out;
				$canChange		= ($canEditState || $canEditStateOwn ) && $recordAvailable;
			} else if (FLEXI_ACCESS) {
				$rights = FAccess::checkAllItemAccess('com_content', 'users', $user->gmid, 0,$row->id);
				$canEdit = ($user->gid < 25) ? (in_array('edit', $rights) || in_array('editown', $rights)) : 1;
				$canEditOwn	= 0;  // edit.own ACL applies only to items, no category ownership in J1.5
			} else {
				$canEdit		= 1;  // No category edit ACL in J1.5
				$canEditOwn	= 0;  // No category edit.own ACL in J1.5, set to zero because there is no category ownership J1.5
			}
			
			if (FLEXI_J16GE) {
				$published		= JHTML::_('jgrid.published', $row->published, $i, 'categories.', $canChange );
			} else {
				$published 	= JHTML::_('grid.published', $row, $i );
			}
			
			if (FLEXI_J16GE) {
				$orderkey = array_search($row->id, $this->ordering[$row->parent_id]);
				$link	= 'index.php?option=com_flexicontent&amp;task=category.edit&amp;cid[]='. $row->id;
			} else {
				$link	= 'index.php?option=com_flexicontent&amp;controller=categories&amp;task=edit&amp;cid[]='. $row->id;
			}
			
			if (FLEXI_J16GE) {
				if (($canEdit || $canEditOwn) && $this->perms->CanAccLvl) {
					$access = flexicontent_html::userlevel('access['.$row->id.']', $row->access, 'onchange="return listItemTask(\'cb'.$i.'\',\'categories.access\')"');
				} else {
					$access = $this->escape($row->access_level);
				}
			} else if (FLEXI_ACCESS) {
				if (($canEdit || $canEditOwn) && $this->perms->CanAccLvl) {
					$access = FAccess::accessswitch('category', $row, $i);
				} else {
					$access = FAccess::accessswitch('category', $row, $i, 'content', 1);
				}
			} else {
				$access 	= JHTML::_('grid.access', $row, $i );
			}
			
			$checked 	= @ JHTML::_('grid.checkedout', $row, $i );
			$items		= 'index.php?option=com_flexicontent&amp;view=items&amp;filter_cats='. $row->id;
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td class="hidden-tablet hidden-phone"><?php echo $this->pagination->getRowOffset( $i ); ?></td>
			<td><?php echo $checked; ?></td>
			<td class="nowrap hidden-tablet hidden-phone">
				<?php
				$cat_link    = str_replace('&', '&amp;', FlexicontentHelperRoute::getCategoryRoute($row->id));
				$cat_link    = JRoute::_(JURI::root().$cat_link, $xhtml=false);  // xhtml to false we do it manually above (at least the ampersand) also it has no effect because we prepended the root URL ?
				$previewlink = $cat_link . $autologin;
				echo '<a class="preview" href="'.$previewlink.'" target="_blank">'.$image_preview.'</a>';
				?>
			</td>
			<td class="h1200">
				<?php
				$rsslink     = $cat_link . '&amp;format=feed&amp;type=rss';
				echo '<a class="preview" href="'.$rsslink.'" target="_blank">'.$image_rsslist.'</a>';
				?>
			</td>
			<td class="col_title">
				<?php
				if (FLEXI_J16GE) {
					if ($row->level>1) echo str_repeat('.&nbsp;&nbsp;&nbsp;', $row->level-1)."<sup>|_</sup>";
				} else {
					echo $row->treename.' ';
				}
				
				// Display an icon with checkin link, if current user has checked out current item
				if ($row->checked_out) {
					// Record check-in is allowed if either (a) current user has Global Checkin privilege OR (b) record checked out by current user
					$canCheckin = $canCheckinRecords || $row->checked_out == $user->id;
					if ($canCheckin) {
						//if (FLEXI_J16GE && $row->checked_out == $user->id) echo JHtml::_('jgrid.checkedout', $i, $row->editor, $row->checked_out_time, 'categories.', $canCheckin);
						$task_str = FLEXI_J16GE ? 'categories.checkin' : 'checkin';
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
				if ( ( $row->checked_out && $row->checked_out != $user->id ) || ( !$canEdit && !$canEditOwn ) ) {
					echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8');
				
				// Display title with edit link ... (row editable and not checked out)
				} else {
				?>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'FLEXI_EDIT_CATEGORY', true );?>::<?php echo $row->alias; ?>">
						<a href="<?php echo $link; ?>">
						<?php echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8'); ?>
						</a>
					</span>
                    
                    
                    
				<?php
				}
				?>
				
				<?php	if (!empty($row->note)) : /* Display J1.6+ category note in a tooltip */ ?>
					<span class="hasTip" title="<?php echo JText::_ ( 'FLEXI_NOTES', true ); ?>::<?php echo $this->escape($row->note);?>"> <?php echo $infoimage; ?> </span>
				<?php endif; ?>
				
			</td>
			
			<td class="col_alias h1200">
				<?php
				if (JString::strlen($row->alias) > 25) {
					echo JString::substr( htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8'), 0 , 25).'...';
				} else {
					echo htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8');
				}
				?>
			</td>
			<td class="col_templ hidden-tablet hidden-phone">
				<?php echo ($row->config->get('clayout') ? $row->config->get('clayout') : "blog <sup>[1]</sup>") ?>
			</td>
			<td class="hidden-tablet hidden-phone">
				<?php echo $row->nrassigned?>
				<a href="<?php echo $items; ?>">
				[<?php echo JText::_( 'FLEXI_VIEW_ITEMS' );?>]
				</a>
			</td>
			<td class="center">
				<?php echo $published; ?>
			</td>
			<td class="center">
				<?php echo $access; ?>
			</td>
			<td class="order center hidden-phone">
			 <?php if ($canChange) : ?>
				<?php if ($saveOrder) : ?>
					<span><?php echo $this->pagination->orderUpIcon($i, isset($this->ordering[$row->parent_id][$orderkey - 1]), 'categories.orderup', 'JLIB_HTML_MOVE_UP', $this->orderingx); ?></span>
					<span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, isset($this->ordering[$row->parent_id][$orderkey + 1]), 'categories.orderdown', 'JLIB_HTML_MOVE_DOWN', $this->orderingx); ?></span>
				<?php endif; ?>
				
				<?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
				<input type="text" name="order[]" size="5" value="<?php echo $orderkey + 1;?>" <?php echo $disabled ?> class="text-area-order" />
				<?php $originalOrders[] = $orderkey + 1; ?>
			<?php else : ?>
				<?php echo $orderkey + 1;?>
			<?php endif; ?>
			</td>
			<td class="col_lang center nowrap hidden-phone hlang">
			<?php if ($row->language=='*'):?>
				<?php echo JText::alt('JALL','language'); ?>
			<?php else:?>
				<?php echo $row->language_title ? $this->escape($row->language_title) : JText::_('JUNDEFINED'); ?>
			<?php endif;?>
			</td>
			<td class="col_id center h1200">
				<span title="<?php echo sprintf('%d-%d', $row->lft, $row->rgt);?>">
				<?php echo $row->id; ?>
				</span>
			</td>
		</tr>
		<?php 
			$k = 1 - $k;
			$i++;
		} 
		?>
	</tbody>

	</table>
	</div>
    </div>
    </div>
    </div>
	<?php echo JText::_( 'FLEXI_PARAMS_CAT' );?>
	
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_flexicontent" />
	<!---input type="hidden" name="controller" value="categories" /-->
	<input type="hidden" name="view" value="categories" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<input type="hidden" name="original_order_values" value="<?php echo implode($originalOrders, ','); ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	
	</div>
</form>
</div>