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
$end_text = '<div class="icon-cancel" title="'.JText::_('FLEXI_HIDE').'" style="cursor: pointer;" onclick="fc_toggle_box_via_btn(\\\'mainChooseColBox\\\', document.getElementById(\\\'fc_mainChooseColBox_btn\\\'), \\\'btn-primary\\\');"></div>';
flexicontent_html::jscode_to_showhide_table('mainChooseColBox', 'adminListTableFCtypes', $start_text, $end_text);

$user    = JFactory::getUser();
$cparams = JComponentHelper::getParams( 'com_flexicontent' );

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
	delFilter('search'); delFilter('filter_state');  delFilter('filter_access');
}

</script>

<div class="flexicontent">
  <form action="index.php" method="post" name="adminForm" id="adminForm">
    <?php if (!empty( $this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2 hidden-phone"> <?php echo $this->sidebar; ?> </div>
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
      
      <div id="fc-filters-box" <?php if (!$this->count_filters) echo 'style="display:none;"'; ?> class="flexheader"> 
        <!--<span class="label"><?php echo JText::_( 'FLEXI_FILTERS' ); ?></span>-->
        
        <div class="row-fluid">
          <div class="span10"> 
            <!--FILTERS-->
            <div class="cat-filters">
              <div class="smallx"> <span class="boxy"> <i class="icon-search"></i> <?php echo $this->lists['state']; ?> </span> <span class="boxy"> <i class="icon-key"></i> <?php echo $this->lists['access']; ?> </span> </div>
              <div class="break-sm">
                <button class="btn btn-success btn-flat btn-rad" type="submit" onclick="this.form.submit();" value="<?php echo JText::_( 'FLEXI_APPLY_FILTERS' ); ?>" ><i class="fa icon-filter"></i> <?php echo JText::_( 'FLEXI_APPLY_FILTERS' ); ?></button>
                <button class="btn btn-danger btn-flat btn-rad" type="submit" onclick="delAllFilters();this.form.submit();" value="<?php echo JText::_( 'FLEXI_CLEAR' ); ?>" ><i class="fa icon-cancel"></i> <?php echo JText::_( 'FLEXI_CLEAR' ); ?></button>
              </div>
            </div>
            <!--/FILTERS--> 
          </div>
        </div>
        <div class="icon-cancel" title="<?php echo JText::_('FLEXI_HIDE'); ?>" style="cursor: pointer;" onclick="fc_toggle_box_via_btn('fc-filters-box', document.getElementById('fc_filters_box_btn'), 'btn-primary');"></div>
      </div>
      <div id="mainChooseColBox" class="fc_mini_note_box well well-small" style="display:none;"></div>
      
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
      <!--MAIN TABLE-->
      <div class="row-fluid">
        <div class="span12">
          <div class="block-flat">
            <div class="table-responsive">
              <table id="adminListTableFCtypes" class="adminlist table no-border no-border-x hover cats">
                <thead class="no-border">
                  <tr class="header">
                    <th class="hidden-phone"><?php echo JText::_( 'FLEXI_NUM' ); ?></th>
                    <th><input type="checkbox" name="toggle" value="" onclick="<?php echo FLEXI_J30GE ? 'Joomla.checkAll(this);' : 'checkAll('.count( $this->rows).');'; ?>" /></th>
                    <th class="hideOnDemandClass title"><?php echo JHTML::_('grid.sort', 'FLEXI_TYPE_NAME', 't.name', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                    <th class="hideOnDemandClass hidden-phone"><?php echo JText::_( 'FLEXI_TEMPLATE' )."<br/><small>(".JText::_( 'FLEXI_PROPERTY_DEFAULT' )." ".JText::_( 'FLEXI_TEMPLATE_ITEM' ).")</small>"; ?></th>
                    <th class="hideOnDemandClass h1000"><?php echo JHTML::_('grid.sort', 'FLEXI_ALIAS', 't.alias', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                    <th class="hideOnDemandClass h550"><?php echo JHTML::_('grid.sort', 'FLEXI_FIELDS', 'fassigned', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                    <th class="hideOnDemandClass h550"><?php echo JHTML::_('grid.sort', 'FLEXI_ITEMS', 'iassigned', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                    <!--th class="hideOnDemandClass"><?php // echo JHTML::_('grid.sort', 'ITEMS', 'iassigned', $this->lists['order_Dir'], $this->lists['order'] ); ?></th-->
                    <th class="hideOnDemandClass center h400"><?php echo JHTML::_('grid.sort', 'FLEXI_ACCESS', 't.access', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                    <th class="hideOnDemandClass center nowrap"><?php echo JText::_( 'FLEXI_PUBLISHED' ); ?></th>
                    <th class="hideOnDemandClass center nowrap hidden-tablet hidden-phone"><?php echo JHTML::_('grid.sort', 'FLEXI_ID', 't.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
                  </tr>
                </thead>
                <tfoot>
                  <tr>
                    <td colspan="10"><?php echo $pagination_footer; ?></td>
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
                    <td class="text-left"><?php
				
				// Display an icon with checkin link, if current user has checked out current item
				if ($row->checked_out) {
					// Record check-in is allowed if either (a) current user has Global Checkin privilege OR (b) record checked out by current user
					$canCheckin = $canCheckinRecords || $row->checked_out == $user->id;
					if ($canCheckin) {
						//if (FLEXI_J16GE && $row->checked_out == $user->id) echo JHtml::_('jgrid.checkedout', $i, $row->editor, $row->checked_out_time, 'types.', $canCheckin);
						$task_str = FLEXI_J16GE ? 'types.checkin' : 'checkin';
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
                      <span class="editlinktip hasTip" title="<?php echo JText::_( 'FLEXI_EDIT_ITEM' );?>::<?php echo $row->name; ?>"> <a href="<?php echo $link; ?>"> <?php echo htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8'); ?> </a></span>
                      <?php
				}
				?></td>
                    <td class="text-left hidden-phone"><?php echo $row->config->get("ilayout"); ?></td>
                    <td class="h1000"><?php
				if (JString::strlen($row->alias) > 25) {
					echo JString::substr( htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8'), 0 , 25).'...';
				} else {
					echo htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8');
				}
				?></td>
                    <td class="text-left h550"><?php echo $row->fassigned; ?> <a href="<?php echo $fields; ?>"> [<?php echo JText::_( 'FLEXI_VIEW_FIELDS' );?>] </a></td>
                    <td class="text-left h550"><?php echo $row->iassigned; ?> <a href="<?php echo $items; ?>"> [<?php echo JText::_( 'FLEXI_VIEW_ITEMS' );?>] </a></td>
                    <td class="center h400"><?php echo $access; ?></td>
                    <td class="center"><?php echo $published; ?></td>
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
      <input type="hidden" name="controller" value="types" />
      <input type="hidden" name="view" value="types" />
      <input type="hidden" name="task" value="" />
      <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
      <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
      <?php echo JHTML::_( 'form.token' ); ?> </div>
  </form>
</div>
