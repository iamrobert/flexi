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
$end_text = '<div class="icon-arrow-up-2" title="'.JText::_('FLEXI_HIDE').'" style="cursor: pointer;" onclick="fc_toggle_box_via_btn(\\\'mainChooseColBox\\\', document.getElementById(\\\'fc_mainChooseColBox_btn\\\'), \\\'btn-primary\\\');"></div>';
flexicontent_html::jscode_to_showhide_table('mainChooseColBox', 'adminListTableFCcats', $start_text, $end_text);

$listOrder  = $this->lists['order'];
$listDirn   = $this->lists['order_Dir'];
$saveOrder  = ($listOrder == 'c.lft' && $listDirn == 'asc');

$user    = JFactory::getUser();
$cparams = JComponentHelper::getParams( 'com_flexicontent' );
$autologin = '';//$cparams->get('autoflogin', 1) ? '&amp;fcu='.$user->username . '&amp;fcp='.$user->password : '';

$attribs_preview = ' class="fc-man-icon-s '.$tip_class.'" title="'.flexicontent_html::getToolTip( 'FLEXI_PREVIEW', 'FLEXI_DISPLAY_ENTRY_IN_FRONTEND_DESC', 1, 1).'" ';
$attribs_rsslist = ' class="fc-man-icon-s '.$tip_class.'" title="'.flexicontent_html::getToolTip( 'FLEXI_FEED', 'FLEXI_DISPLAY_RSS_IN_FRONTEND_DESC', 1, 1).'" ';
$attribs_editlayout = ' style="float:right;" class="fc-man-icon-s" title="'.flexicontent_html::getToolTip( 'FLEXI_EDIT_LAYOUT', null, 1, 1).'" ';

$image_preview = JHTML::image( 'administrator/templates/'.$template.'/images/flexi/monitor_go.png', JText::_('FLEXI_PREVIEW'),  $attribs_preview);
$image_rsslist = JHTML::image( 'administrator/templates/'.$template.'/images/flexi/livemarks.png', JText::_('FLEXI_FEED'), $attribs_rsslist );
$image_editlayout = JHTML::image( 'components/com_flexicontent/assets/images/'.'layout_edit.png', JText::_('FLEXI_EDIT_LAYOUT'),  $attribs_editlayout);

$image_flag_path = !FLEXI_J16GE ? "../components/com_joomfish/images/flags/" : "../media/mod_languages/images/";
$infoimage  = JHTML::image ( 'administrator/components/com_flexicontent/assets/images/comment.png', JText::_( 'FLEXI_NOTES' ), ' class="fc-man-icon-s" ' );

//http://localhost/t2015/administrator/templates/flexi3/images/flexi/
$img_path = ''.JURI::base().'templates/'.$template.'/images/flexi/';
$state_names = array('ALL_P'=>JText::_('FLEXI_PUBLISHED'), 'ALL_U'=>JText::_('FLEXI_UNPUBLISHED'), 'A'=>JText::_('FLEXI_ARCHIVED'), 'T'=>JText::_('FLEXI_TRASHED'));
$state_imgs = array('ALL_P'=>'tick.png', 'ALL_U'=>'publish_x.png', 'A'=>'archive.png', 'T'=>'trash.png');

$edit_entry = JText::_('FLEXI_EDIT_CATEGORY', true);
$edit_layout = JText::_('FLEXI_EDIT_LAYOUT', true);

$list_total_cols = 16;
?>
<script type="text/javascript">

// delete active filter
function delFilter(name)
{
	//if(window.console) window.console.log('Clearing filter:'+name);
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
  <form action="index.php?option=<?php echo $this->option; ?>&view=<?php echo $this->view; ?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">
    <?php if (!empty( $this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2"> <?php echo $this->sidebar; ?> </div>
    <div id="j-main-container" class="span10">
    <?php else : ?>
    <div id="j-main-container">
      <?php endif;?>
      <div id="fc-filters-header">
        <span class="btn-wrapper input-append fc-filter"><span class="filter-search btn-group">
          <input type="text" name="search" id="search" placeholder="<?php echo JText::_( 'FLEXI_SEARCH' ); ?>" value="<?php echo htmlspecialchars($this->lists['search'], ENT_QUOTES, 'UTF-8'); ?>" class="inputbox" />
          </span> <span class="btn-group">
          <button title="<?php echo JText::_( 'FLEXI_SEARCH' ); ?>" class="<?php echo $btn_class; ?> <?php echo $tip_class; ?>" onclick="document.adminForm.limitstart.value=0; Joomla.submitform();"  data-original-title="<?php echo JText::_( 'FLEXI_SEARCH' ); ?>"><?php echo FLEXI_J30GE ? '<i class="icon-search"></i>' : JText::_('FLEXI_GO'); ?></button>
          <button title="<?php echo JText::_('FLEXI_RESET_FILTERS'); ?>" class="<?php echo $btn_class; ?> hidden-phone" onclick="document.adminForm.limitstart.value=0; delAllFilters(); Joomla.submitform();"><?php echo FLEXI_J30GE ? '<i class="icon-remove"></i>' : JText::_('FLEXI_CLEAR'); ?></button>
          </span></span>
        <?php $_class = FLEXI_J30GE ? ' btn' : ' fc_button fcsimple fcsmall'; ?>
        <div class="btn-wrapper btn-group hidden-phone">
          <input type="button" id="fc_filters_box_btn" class="<?php echo $_class.($this->count_filters ? ' btn-primary' : ''); ?>" onclick="fc_toggle_box_via_btn('fc-filters-box', this, 'btn-primary');" value="<?php echo JText::_( 'FLEXI_FILTERS' ); ?>" />
          <input type="button" id="fc_mainChooseColBox_btn" class="<?php echo $_class; ?>" onclick="fc_toggle_box_via_btn('mainChooseColBox', this, 'btn-primary');" value="<?php echo JText::_( 'FLEXI_COLUMNS' ); ?>" />
        </div>
        <span class="limit btn-group pull-right hidden-phone">
        <?php
				$pagination_footer = $this->pagination->getListFooter();
				if (strpos($pagination_footer, '"limit"') === false) echo $this->pagination->getLimitBox();
				?>
        <?php if (($getPagesCounter = $this->pagination->getPagesCounter())): ?>
        <?php echo $getPagesCounter; ?>
        <?php endif; ?>
        </span> </div>
      <div id="fc-filters-box" <?php if (!$this->count_filters) echo 'style="display:none;"'; ?> class=""> 
        <!--<span class="label"><?php echo JText::_( 'FLEXI_FILTERS' ); ?></span>--> 
        
        <span class="fc-filter nowrap_box"> <?php echo $this->lists['cats']; ?> </span> <span class="fc-filter nowrap_box"> <?php echo $this->lists['level']; ?> </span> <span class="fc-filter nowrap_box"> <?php echo $this->lists['state']; ?> </span> <span class="fc-filter nowrap_box"> <?php echo $this->lists['access']; ?> </span> <span class="fc-filter nowrap_box"> <?php echo $this->lists['language']; ?> </span>
        <div class="icon-arrow-up-2" title="<?php echo JText::_('FLEXI_HIDE'); ?>" style="cursor: pointer;" onclick="fc_toggle_box_via_btn('fc-filters-box', document.getElementById('fc_filters_box_btn'), 'btn-primary');"></div>
      </div>
      <div id="mainChooseColBox" class="well well-small" style="display:none;"></div>
      <div class="fcclear"></div>
     
    <div class="block-flat">
      <table id="adminListTableFCcats" class="adminlist fcmanlist table no-border hover">
        <thead>
          <tr class="header">
            <th class="col_no hidden-tablet hidden-phone center"><?php echo JText::_( 'FLEXI_NUM' ); ?></th>
            <th class="center"><input type="checkbox" name="toggle" value="" onclick="<?php echo FLEXI_J30GE ? 'Joomla.checkAll(this);' : 'checkAll('.count( $this->rows).');'; ?>" /></th>
            <th class="nowrap hidden-tablet hidden-phone">&nbsp;</th>
            <th class="nowrap h1200">&nbsp;</th>
            <th class="hideOnDemandClass title"><?php echo JHTML::_('grid.sort', 'FLEXI_CATEGORY', 'c.title', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
            <th class="hideOnDemandClass col_alias h1200"><?php echo JHTML::_('grid.sort', 'FLEXI_ALIAS', 'c.alias', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
            <th class="hideOnDemandClass col_templ hidden-tablet hidden-phone" colspan="2"><?php echo JText::_( 'FLEXI_TEMPLATE' ); ?></th>
            
            <th class="hideOnDemandClass center"> <span class="column_toggle_lbl" style="display:none;"><small class="badge"><?php echo $state_names['ALL_P']; ?></small></span> <?php echo '<img src="'.$img_path.$state_imgs['ALL_P'].'" title="'.$state_names['ALL_P'].'">'; ?> </th>
            <th class="hideOnDemandClass center"> <span class="column_toggle_lbl" style="display:none;"><small class="badge"><?php echo $state_names['ALL_U']; ?></small></span> <?php echo '<img src="'.$img_path.$state_imgs['ALL_U'].'" title="'.$state_names['ALL_U'].'">'; ?> </th>
            <th class="hideOnDemandClass center"> <span class="column_toggle_lbl" style="display:none;"><small class="badge"><?php echo $state_names['A']; ?></small></span> <?php echo '<img src="'.$img_path.$state_imgs['A'].'" title="'.$state_names['A'].'">'; ?> </th>
            <th class="hideOnDemandClass center"> <span class="column_toggle_lbl" style="display:none;"><small class="badge"><?php echo $state_names['T']; ?></small></span> <?php echo '<img src="'.$img_path.$state_imgs['T'].'" title="'.$state_names['T'].'">'; ?> </th>
            <th class="hideOnDemandClass center"><?php echo JText::_( 'FLEXI_PUBLISHED' ); ?></th>
            <th class="hideOnDemandClass center hidden-phone"><?php echo JHTML::_('grid.sort', 'FLEXI_ACCESS', 'c.access', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
            <th class="order center hideOnDemandClass hidden-phone"> <?php echo JHTML::_('grid.sort', 'FLEXI_REORDER', 'c.lft', $this->lists['order_Dir'], $this->lists['order'] ); ?> <?php echo $this->orderingx ? JHTML::_('grid.order', $this->rows, 'filesave.png', 'categories.saveorder' ) : ''; ?> </th>
            <th class="hideOnDemandClass col_lang center nowrap hidden-phone hlang"> <?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'language', $this->lists['order_Dir'], $this->lists['order'] ); ?> </th>
            <th class="hideOnDemandClass col_id nowrap h1200 hid center"> <?php echo JHTML::_('grid.sort', 'FLEXI_ID', 'c.id', $this->lists['order_Dir'], $this->lists['order'] ); ?> </th>
          </tr>
        </thead>
        
        <tbody>
          <?php
		$canCheckinRecords = $user->authorise('core.admin', 'checkin');
		
		$originalOrders = array();
		$extension	= 'com_content';
		
		$k = 0;
		$i = 0;
		$clayout_bycatid = array();
		$cat_ancestors = array();
		$inheritcid_comp = $cparams->get('inheritcid', -1);
		
		if (!count($this->rows)) echo '<tr class="collapsed_row"><td colspan="'.$list_total_cols.'"></td></tr>';  // Collapsed row to allow border styling to apply		$k = 0;
		foreach ($this->rows as $row)
		{
			$canEdit		= $user->authorise('core.edit', $extension.'.category.'.$row->id);
			$canEditOwn	= $user->authorise('core.edit.own', $extension.'.category.'.$row->id) && $row->created_user_id == $user->get('id');
			$canEditState			= $user->authorise('core.edit.state', $extension.'.category.'.$row->id);
			$canEditStateOwn	= $user->authorise('core.edit.state.own', $extension.'.category.'.$row->id) && $row->created_user_id==$user->get('id');
			$recordAvailable	= ($canCheckinRecords && $row->checked_out == $user->id) || !$row->checked_out;
			$canChange  = ($canEditState || $canEditStateOwn ) && $recordAvailable;
			
			$published = JHTML::_('jgrid.published', $row->published, $i, 'categories.', $canChange );
			
			$orderkey = array_search($row->id, $this->ordering[$row->parent_id]);
			$link	= 'index.php?option=com_flexicontent&amp;task=category.edit&amp;cid[]='. $row->id;

			
			$inheritcid = $row->config->get('inheritcid', '');
			$inherit_parent = $inheritcid==='-1' || ($inheritcid==='' && $inheritcid_comp);
			
			if (!$inherit_parent || $row->parent_id==='1')
				$row_clayout = $row->config->get('clayout', $cparams->get('clayout', 'blog'));
			else {
				$row_clayout = $row->config->get('clayout', '');
				
				if (!$row_clayout)
				{
					if (isset($clayout_bycatid[$row->parent_id])) {
						$row_clayout = $clayout_bycatid[$row->parent_id];
					}
					else
					{
						$_ancestors = $this->getModel()->getParentParams($row->id);  // This is ordered by level ASC
						$row_clayout = $cparams->get('clayout', 'blog');
						foreach($_ancestors as $_cid => $_cat)
						{
							if (!isset($cats_params[$_cid]))
							{
								$cats_params[$_cid] = new JRegistry($_cat->params);
							}
							$row_clayout = $cats_params[$_cid]->get('clayout', '') ? $cats_params[$_cid]->get('clayout', '') : $row_clayout;
							$clayout_bycatid[$_cid] = $row_clayout;
						}
					}
				}
			}
			$clayout_bycatid[$row->id] = $row_clayout;
			
			$layout_url = 'index.php?option=com_flexicontent&amp;view=template&amp;type=category&amp;tmpl=component&amp;ismodal=1&amp;folder='. $row_clayout;
			
			if (($canEdit || $canEditOwn) && $this->perms->CanAccLvl) {
				$access = flexicontent_html::userlevel('access['.$row->id.']', $row->access, 'onchange="return listItemTask(\'cb'.$i.'\',\'categories.access\')"');
			} else {
				$access = $this->escape($row->access_level);
			}
			
			$checked 	= @ JHTML::_('grid.checkedout', $row, $i );
			$items_link = 'index.php?option=com_flexicontent&amp;view=items&amp;filter_catsinstate=99&amp;filter_subcats=0&amp;filter_cats='. $row->id.'&amp;fcform=1&amp;filter_state=';
   		?>
          <tr class="<?php echo "row$k"; ?>">
            <td class="col_no hidden-tablet hidden-phone center"><?php echo $this->pagination->getRowOffset( $i ); ?></td>
            <td class="center"><?php echo $checked; ?></td>
            <td class="hidden-tablet hidden-phone center preview"><?php
				$cat_link    = str_replace('&', '&amp;', FlexicontentHelperRoute::getCategoryRoute($row->id));
				$cat_link    = JRoute::_(JURI::root().$cat_link, $xhtml=false);  // xhtml to false we do it manually above (at least the ampersand) also it has no effect because we prepended the root URL ?
				$previewlink = $cat_link . $autologin;
				echo '<a class="preview" href="'.$previewlink.'" target="_blank">'.$image_preview.'</a>';
				?></td>
            <td class="h1200 center rss"><?php
				$rsslink     = $cat_link . '&amp;format=feed&amp;type=rss';
				echo '<a class="preview" href="'.$rsslink.'" target="_blank">'.$image_rsslist.'</a>';
				?></td>
            <td class="col_title"><?php
				if ($row->level>1) echo str_repeat('.&nbsp;&nbsp;&nbsp;', $row->level-1)."<sup>|_</sup>";
				
				// Display an icon with checkin link, if current user has checked out current item
				if ($row->checked_out) {
					// Record check-in is allowed if either (a) current user has Global Checkin privilege OR (b) record checked out by current user
					$canCheckin = $canCheckinRecords || $row->checked_out == $user->id;
					if ($canCheckin) {
						//if (FLEXI_J16GE && $row->checked_out == $user->id) echo JHtml::_('jgrid.checkedout', $i, $row->editor, $row->checked_out_time, 'categories.', $canCheckin);
						$task_str = FLEXI_J16GE ? 'categories.checkin' : 'checkin';
						if ($row->checked_out == $user->id) {
							$_tip_title = JText::sprintf('FLEXI_CLICK_TO_RELEASE_YOUR_LOCK_DESC', $row->editor, $row->checked_out_time);
						} else {
							echo '<input id="cb'.$i.'" type="checkbox" value="'.$row->id.'" name="cid[]" style="display:none!important;">';
							$_tip_title = JText::sprintf('FLEXI_CLICK_TO_RELEASE_FOREIGN_LOCK_DESC', $row->editor, $row->checked_out_time);
						}
						?>
              <a class="jgrid <?php echo $tip_class; ?>" title="<?php echo $_tip_title; ?>" href="javascript:;" onclick="var ccb=document.getElementById('cb<?php echo $i;?>'); ccb.checked=1; ccb.form.task.value='<?php echo $task_str; ?>'; ccb.form.submit();"> <img src="components/com_flexicontent/assets/images/lock_delete.png" alt="Check-in" /> </a>
              <?php
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
              <a href="<?php echo $link; ?>" title="<?php echo $edit_entry; ?>"> <?php echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8'); ?> </a>
              <?php
				}
				?>
              <?php	if (!empty($row->note)) : /* Display J1.6+ category note in a tooltip */ ?>
              <span class="<?php echo $tip_class; ?>" title="<?php echo flexicontent_html::getToolTip( JText::_ ('FLEXI_NOTES'), $row->note, 0, 1); ?>"> <?php echo $infoimage; ?> </span>
              <?php endif; ?></td>
            <td class="col_alias h1200"><?php
				if (JString::strlen($row->alias) > 25) {
					echo JString::substr( htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8'), 0 , 25).'...';
				} else {
					echo htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8');
				}
				?></td>
                <!--Don't want my clients touching templates-->
            <td class="none"><?php if ($this->CanTemplates && $row_clayout) : ?>
              <a href="<?php echo $layout_url; ?>" title="<?php echo $edit_layout; ?>" onclick="var url = jQuery(this).attr('href'); fc_showDialog(url, 'fc_modal_popup_container'); return false;" > <?php echo $image_editlayout;?> </a>
              <?php endif; ?></td>
            <td colspan="2" class="col_templ hidden-tablet hidden-phone"><?php echo $row->config->get('clayout') ? $row->config->get('clayout') : ($row_clayout ? $row_clayout : '...').'<span class="badge m10l">inherited</span>'; ?></td>
           <!--/Don't want my clients touching templates-->
            
            <td class="center"><a href="<?php echo $items_link.'ALL_P'; ?>" title="<?php echo JText::_( 'FLEXI_VIEW_ITEMS' );?>" style="color:unset; display:inline-block;"> <span class="badge badge-success">
              <?php $c = (int)@$row->byStateTotals[1] + (int)@$row->byStateTotals[-5]; echo $c ? $c : '.'; ?>
              </span> </a></td>
            <td class="center"><a href="<?php echo $items_link.'ALL_U'; ?>" title="<?php echo JText::_( 'FLEXI_VIEW_ITEMS' );?>" style="color:unset; display:inline-block;"> <span class="badge badge-warning">
              <?php $c = (int)@$row->byStateTotals[0] + (int)@$row->byStateTotals[-3] + (int)@$row->byStateTotals[-4]; echo $c ? $c : '.'; ?>
              </span> </a></td>
            <td class="center"><a href="<?php echo $items_link.'A'; ?>" title="<?php echo JText::_( 'FLEXI_VIEW_ITEMS' );?>" style="color:unset; display:inline-block;"> <span class="badge badge-info">
              <?php $c = (int)@$row->byStateTotals[2]; echo $c ? $c : '.'; ?>
              </span> </a></td>
            <td class="center"><a href="<?php echo $items_link.'T'; ?>" title="<?php echo JText::_( 'FLEXI_VIEW_ITEMS' );?>" style="color:unset; display:inline-block;"> <span class="badge">
              <?php $c = (int)@$row->byStateTotals[-2]; echo $c ? $c : '.'; ?>
              </span> </a></td>
            <td class="center"><?php echo $published; ?></td>
            <td class="center hidden-phone"><?php echo $access; ?></td>
            <td class="order hidden-phone"><?php if ($canChange) : ?>
              <?php if ($saveOrder) : ?>
              <span><?php echo $this->pagination->orderUpIcon($i, isset($this->ordering[$row->parent_id][$orderkey - 1]), 'categories.orderup', 'JLIB_HTML_MOVE_UP', $this->orderingx); ?></span> <span><?php echo $this->pagination->orderDownIcon($i, $this->pagination->total, isset($this->ordering[$row->parent_id][$orderkey + 1]), 'categories.orderdown', 'JLIB_HTML_MOVE_DOWN', $this->orderingx); ?></span>
              <?php endif; ?>
              <?php $disabled = $saveOrder ?  '' : 'disabled="disabled"'; ?>
              <input type="text" name="order[]" size="5" value="<?php echo $orderkey + 1;?>" <?php echo $disabled ?> class="text-area-order" />
              <?php $originalOrders[] = $orderkey + 1; ?>
              <?php else : ?>
              <?php echo $orderkey + 1;?>
              <?php endif; ?></td>
            <td class="col_lang center nowrap hidden-phone hlang"><?php if ($row->language=='*'):?>
              <?php echo JText::alt('JALL','language'); ?>
              <?php else:?>
              <?php echo $row->language_title ? $this->escape($row->language_title) : JText::_('JUNDEFINED'); ?>
              <?php endif;?></td>
            <td class="col_id nowrap h1200 hid center"><span title="<?php echo sprintf('%d-%d', $row->lft, $row->rgt);?>"> <?php echo $row->id; ?> </span></td>
          </tr>
          <?php 
			$k = 1 - $k;
			$i++;
		} 
		?>
        </tbody>
      </table>
      
     
      </div>
     
    <p><?php echo $pagination_footer; ?></p>
    
      <?php /*echo JText::_( 'FLEXI_PARAMS_CAT' );*/ ?>
      <input type="hidden" name="boxchecked" value="0" />
      <input type="hidden" name="option" value="com_flexicontent" />
      <!---input type="hidden" name="controller" value="categories" /-->
      <input type="hidden" name="view" value="categories" />
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
      <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
      <input type="hidden" name="original_order_values" value="<?php echo implode($originalOrders, ','); ?>" />
      <input type="hidden" name="fcform" value="1" />
      <?php echo JHTML::_( 'form.token' ); ?> </div>
  </form>
</div>
