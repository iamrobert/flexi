<?php
/**
 * @version 1.5 stable $Id: default.php 1803 2013-11-05 03:10:36Z ggppdk $
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

$items_task = FLEXI_J16GE ? 'task=items.' : 'controller=items&amp;task=';
?>
<script type="text/javascript">

// the function overloads joomla standard event
function submitform(pressbutton)
{
	form = document.adminForm;
	
	// Store the button task into the form
	if (pressbutton) {
		form.task.value=pressbutton;
	}

	// Execute onsubmit
	if (typeof form.onsubmit == "function") {
		form.onsubmit();
	}
	// Submit the form
	form.submit();
}

// delete active filter
function delFilter(name)
{
	var myForm = $('adminForm');
	if ($(name).type=='checkbox')
		$(name).checked = '';
	else
		$(name).setProperty('value', '');
}

function delAllFilters() {
	delFilter('filter_fieldtype'); delFilter('filter_itemtype'); delFilter('filter_itemstate');
	delFilter('search_index'); delFilter('search_itemtitle'); delFilter('search_itemid');
}
</script>

<div class="flexicontent">
  <form action="index.php?option=com_flexicontent&amp;view=search" method="post" name="adminForm" id="adminForm" class="form-horizontal">
    <?php if (!empty( $this->sidebar)) : ?>
    <div id="j-sidebar-container" class="span2  hidden-phone"> <?php echo $this->sidebar; ?> </div>
    <div id="j-main-container" class="span10">
    <?php else : ?>
    <div id="j-main-container">
      <?php endif;?>
      <!--SEARCH BLOCK-->
      <div class="row-fluid">
      <div class="span12">
      <div class="block-flat">
      
      <!--FILTER-->
      <div id="filterline">
      <div class="row-fluid filterbuttons_head">
      <div class="span12 w100">
       <div class="control-group filterbuttons">
      <?php echo '<label class="control-label left'.($this->f_active['search_index'] ? " highlight":"").'">'.JText::_('FLEXI_FILTER').'</label> '; ?>
      <div class="controls">
      <div class="input-append m500">
      <input type="text" name="search_index" id="search_index" value="<?php echo $this->lists['search_index']; ?>" class="text_area" onchange="document.adminForm.submit();" />
      <button type="submit" class="btn btn-success btn-flat btn-rad" onclick="this.form.submit();" value="<?php echo JText::_( 'FLEXI_APPLY_FILTERS' ); ?>"><i class="fa icon-filter"></i> <?php echo JText::_( 'FLEXI_APPLY_FILTERS' ); ?></button>
      <button type="button" class="btn btn-danger btn-flat btn-rad" onclick="delAllFilters();this.form.submit();" value="<?php echo JText::_( 'FLEXI_RESET_FILTERS' ); ?>">
      <i class="fa icon-cancel"></i> <?php echo JText::_( 'FLEXI_RESET_FILTERS' ); ?>
      </div>
      </div>
      </div>
      
      
      <div class="control-group"> <?php echo '<label class="control-label left'.($this->f_active['search_itemtitle'] ? " highlight":"").'">'.JText::_('FLEXI_TITLE').'</label> '; ?>
        <div class="controls">
          <div class="input-append">
            <input type="text" name="search_itemtitle" id="search_itemtitle" value="<?php echo $this->lists['search_itemtitle']; ?>" class="text_area inputbox" onchange="document.adminForm.submit();" />
            <button type="submit" class="btn hasTooltip" title="" data-original-title="Search"> <i class="icon-search"></i> </button>
            <button type="button" class="btn hasTooltip" onclick="delAllFilters();this.form.submit();" value="Clear"  data-original-title="Clear All"> <i class="icon-delete"></i></button>
          </div>
        </div>
      </div>
     
      <div class="control-group"> <?php echo '<label class="control-label left'.($this->f_active['filter_itemtype'] ? " highlight":"").'">'.JText::_('FLEXI_TYPE_NAME').'</label> '; ?>
        <div class="controls"> <?php echo $this->lists['filter_itemtype']; ?> </div>
      </div>
      <div class="control-group"> <?php echo '<label class="control-label left'.($this->f_active['filter_itemstate'] ? " highlight":"").'">'.JText::_('FLEXI_STATE').'</label> '; ?>
        <div class="controls"> <?php echo $this->lists['filter_itemstate']; ?> </div>
      </div>
      <div class="control-group col_fieldtype"> <?php echo '<label class="control-label left'.($this->f_active['filter_fieldtype'] ? " highlight":"").'">'.JText::_('FLEXI_FILTER').'</label> '; ?>
        <div class="controls"> <?php echo $this->lists['filter_fieldtype']; ?> </div>
      </div>
       <!--ID-->
      <div class="control-group"> <?php echo '<label class="control-label left'.($this->f_active['search_itemid'] ? " highlight":"").'">'.JText::_('FLEXI_ID').'</label> '; ?>
        <div class="controls">
          <input type="text" name="search_itemid" id="search_itemid" value="<?php echo $this->lists['search_itemid']; ?>" class="text_area inputbox" onchange="document.adminForm.submit();" size="6" />
        </div>
      </div>
      
      <!--ID-->
      </div>
      </div>
      </div>
       <!--/FILTER BLOCK-->
      </div>
      </div>
      </div>
       <!--/SEARCH BLOCK-->

      
      
      
       <!--LIMIT-->
      <div class="row-fluid">
       
      
        <div class="span6 limit text-right">
        <?php /*?><div class='fc_mini_note_box'> <span class="radio flexi_tabbox" style="margin-left:60px;"><?php echo '<span class="flexi_tabbox_label">'.JText::_('FLEXI_LISTING_RECORDS').': </span>'.$this->lists['filter_indextype']; ?></span> </div> </span><?php */?>
        
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
      <table class="adminlist table no-border hover">
        <thead>
          <tr>
            <th class="h1000"> <?php echo JText::_('NUM'); ?> </th>
            <th class="center nowrap h900"> 
          <input type="checkbox" name="toggle" value="" onclick="<?php echo FLEXI_J30GE ? 'Joomla.checkAll(this);' : 'checkAll('.count( $this->data).');'; ?>" />
        
          </th>
  
        
          <th> <?php echo JHTML::_('grid.sort', JText::_('FLEXI_ITEMS'), 'a.title', $this->lists['order_Dir'], $this->lists['order'] ); ?> </th>
          <th class="nowrap"> <?php echo JHTML::_('grid.sort', JText::_('FLEXI_FIELD_INDEX').' '.JText::_('FLEXI_FIELD_LABEL'), 'f.label', $this->lists['order_Dir'], $this->lists['order'] ); ?> </th>
          <th class="nowrap"> <?php echo JHTML::_('grid.sort', JText::_('FLEXI_FIELD_INDEX').' '.JText::_('FLEXI_FIELD_NAME'), 'f.name', $this->lists['order_Dir'], $this->lists['order'] ); ?> </th>
          <th class="nowrap h500"> <?php echo JHTML::_('grid.sort', JText::_('FLEXI_FIELD_TYPE'), 'f.field_type', $this->lists['order_Dir'], $this->lists['order'] ); ?> </th>
          <th class="nowrap center h900"> <?php echo JHTML::_('grid.sort', JText::_('FLEXI_INDEX_VALUE_COUNT'), 'ai.extraid', $this->lists['order_Dir'], $this->lists['order'] ); ?> </th>
          <th class="nowrap"> <?php echo JHTML::_('grid.sort', JText::_('FLEXI_SEARCH_INDEX'), 'ai.search_index', $this->lists['order_Dir'], $this->lists['order'] ); ?> </th>
          <th class="nowrap center h900"> <?php echo JHTML::_('grid.sort', JText::_('FLEXI_INDEX_VALUE_ID'), 'ai.value_id', $this->lists['order_Dir'], $this->lists['order'] ); ?> </th>
        </tr>
          </thead>
        
        <tfoot>
          <tr>
            <td colspan="9" class="nowrap"><?php echo $pagination_footer; ?></td>
          </tr>
        </tfoot>
        <tbody>
          <?php if (count($this->data) == 0): ?>
          <tr class="row0">
            <td align="center" colspan="9"><?php
					if ($this->total == 0) {
						echo JText::_('FLEXI_NO_DATA');
						//echo JText::_('FINDER_INDEX_TIP');
					} else {
						echo JText::_('FINDER_INDEX_NO_CONTENT');
					}
					?></td>
          </tr>
          <?php endif; ?>
          <?php
			$n = 0; $o = 0;
			foreach ($this->data as $row): ?>
          <tr class="<?php echo 'row', $o; ?>">
            <td  class="h1000"><?php echo $this->pagination->getRowOffset( $n ); ?></td>
            <td class="center h900"><?php echo JHTML::_('grid.id', $n, $row->field_id.'|'.$row->item_id); ?></td>
            <td><span class="editlinktip hasTip" title="<?php echo JText::_( 'FLEXI_EDIT_ITEM' );?>::<?php echo $row->title; ?>">
              <?php
						$link = 'index.php?option=com_flexicontent&amp;'.$items_task.'edit&amp;cid[]='. $row->id;
						echo '<a target="_blank" href="'.$link.'">'.$this->escape($row->title).'</a>';
					?>
              </span></td>
            <td><?php echo $this->escape($row->label); ?></td>
            <td><?php echo $this->escape($row->name); ?></td>
            <td class="nowrap center h500" class="col_fieldtype"><?php echo $row->field_type; ?></td>
            <td class="nowrap center h900"><?php echo $row->extraid; ?></td>
            <td class="col_search_index"><?php
						if(iconv_strlen($row->search_index, "UTF-8")>400)
							echo iconv_substr($row->search_index, 0, 400, "UTF-8").'...';
						else
							echo $row->search_index;
					?></td>
            <td class="nowrap center h900"><?php echo $row->value_id; ?></td>
            <?php /*<td class="nowrap" style="text-align: center;">
					<?php //echo JHtml::date($row->indexdate, '%Y-%m-%d %H:%M:%S'); ?>
				</td>*/ ?>
          </tr>
          <?php $n++; $o = ++$o % 2; ?>
          <?php endforeach; ?>
        </tbody>
      </table>
      <input type="hidden" name="task" value="display" />
      <input type="hidden" name="boxchecked" value="0" />
      <input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
      <input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
      <input type="hidden" name="controller" value="search" />
      <?php echo JHTML::_('form.token'); ?> </div>
  </form>
</div>
