<?php defined('_JEXEC') or die('Restricted access'); ?>

<?php defined('_JEXEC') or die('Restricted access'); ?>

<script type="text/javascript">

function fetchcounter()
{
	var url = "index.php?option=com_flexicontent&amp;controller=items&amp;task=getorphans&amp;format=raw";
	var ajax = new Ajax(url, {
		method: 'get',
		update: $('count'),
		onComplete:function(v) {
			if(v==0)
				if(confirm("<?php echo JText::_( 'FLEXI_ITEMS_REFRESH_CONFIRM',true ); ?>"))
					location.href = 'index.php?option=com_flexicontent&amp;view=items';
		}
	});
	ajax.request();
}

// the function overloads joomla standard event
function submitform(pressbutton)
{
	form = document.adminForm;
	// If formvalidator activated
	if( pressbutton == 'remove' ) {
		var answer = confirm('<?php echo JText::_( 'FLEXI_ITEMS_DELETE_CONFIRM',true ); ?>')
		if (!answer){
			new Event(e).stop();
			return;
		} else {
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
	} else {
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
	delFilter('search'); delFilter('filter_itemscount');
	delFilter('filter_logged'); delFilter('filter_usergrp');
	delFilter('startdate'); delFilter('enddate');
	delFilter('filter_id');
}

window.addEvent('domready', function(){
	var startdate	= $('startdate');
	var enddate 	= $('enddate');
	if(MooTools.version>="1.2.4") {
		var sdate = startdate.value;
		var edate = enddate.value;
	}else{
		var sdate = startdate.getValue();
		var edate = enddate.getValue();
	}
	if (sdate == '') {
		startdate.setProperty('value', '<?php echo JText::_( 'FLEXI_FROM',true ); ?>');
	}
	if (edate == '') {
		enddate.setProperty('value', '<?php echo JText::_( 'FLEXI_TO',true ); ?>');
	}
	$('startdate').addEvent('focus', function() {
		if (sdate == '<?php echo JText::_( 'FLEXI_FROM',true ); ?>') {
			startdate.setProperty('value', '');
		}
	});
	$('enddate').addEvent('focus', function() {
		if (edate == '<?php echo JText::_( 'FLEXI_TO',true ); ?>') {
			enddate.setProperty('value', '');
		}
	});
	$('startdate').addEvent('blur', function() {
		if (sdate == '') {
			startdate.setProperty('value', '<?php echo JText::_( 'FLEXI_FROM',true ); ?>');
		}
	});
	$('enddate').addEvent('blur', function() {
		if (edate == '') {
			enddate.setProperty('value', '<?php echo JText::_( 'FLEXI_TO',true ); ?>');
		}
	});

/*
	$('show_filters').setStyle('display', 'none');
	$('hide_filters').addEvent('click', function() {
		$('filterline').setStyle('display', 'none');
		$('show_filters').setStyle('display', '');
		$('hide_filters').setStyle('display', 'none');
	});
	$('show_filters').addEvent('click', function() {
		$('filterline').setStyle('display', '');
		$('show_filters').setStyle('display', 'none');
		$('hide_filters').setStyle('display', '');
	});
*/
});
</script>



<div class="flexicontent">
<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2 hidden-phone">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>
<form action="index.php?option=com_flexicontent&amp;controller=users&amp;view=users" method="post" name="adminForm" id="adminForm"  class="form-horizontal">
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
               <div class="clear"></div>
            </div>
            <!--/ FILTER--> 
          </div>
        </div>
      </div>
      <!--CAT BOX -->

	
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
	<table class="users table no-border hover">
		<thead class="no-border">
			<tr class="header">
				<th class="center">
					<?php echo JText::_( 'NUM' ); ?>
				</th>
				<th class="center">
					<input type="checkbox" name="toggle" value="" onclick="<?php echo FLEXI_J30GE ? 'Joomla.checkAll(this);' : 'checkAll('.count( $this->items).');'; ?>" />
				</th>
				<th class="left">
					<?php echo JHTML::_('grid.sort',   'Name', 'a.name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					<?php if ($this->search) : ?>
					<span class="hasTip filterdel" title="<?php echo JText::_('FLEXI_REMOVE_THIS_FILTER_DESC') ?>">
						<img src="templates/<?php echo $template;?>/images/flexi/bullet_delete.png" alt="<?php echo JText::_('FLEXI_REMOVE_THIS_FILTER') ?>" onclick="delFilter('search');document.adminForm.submit();" />
					</span>
					<?php endif; ?>
				</th>
				<th class="center" >
					<?php echo JHTML::_('grid.sort',   'FLEXI_ITEMS', 'itemscount', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					<?php if ($this->filter_itemscount) : ?>
					<span class="hasTip filterdel" title="<?php echo JText::_('FLEXI_REMOVE_THIS_FILTER_DESC') ?>">
						<img src="templates/<?php echo $template;?>/images/flexi/bullet_delete.png" alt="<?php echo JText::_('FLEXI_REMOVE_THIS_FILTER') ?>" onclick="delFilter('filter_itemscount');document.adminForm.submit();" />
					</span>
					<?php endif; ?>
				</th>
				<th class="left" >
					<?php echo JHTML::_('grid.sort',   'FLEXI_USER_NAME', 'a.username', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th class="center nowrap">
					<?php echo JHTML::_('grid.sort',   'FLEXI_USER_LOGIN', 'loggedin', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					<?php if ($this->filter_logged) : ?>
					<span class="hasTip filterdel" title="<?php echo JText::_('FLEXI_REMOVE_THIS_FILTER_DESC') ?>">
						<img src="templates/<?php echo $template;?>/images/flexi/bullet_delete.png" alt="<?php echo JText::_('FLEXI_REMOVE_THIS_FILTER') ?>" onclick="delFilter('filter_logged');document.adminForm.submit();" />
					</span>
					<?php endif; ?>
				</th>
				<th class="center nowrap">
					<?php echo JHTML::_('grid.sort',   'FLEXI_ENABLED', 'a.block', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th class="center">
					<?php echo FLEXI_J16GE ? JText::_( 'FLEXI_USERGROUPS' ) : JHTML::_('grid.sort',   'Group', 'groupname', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					<?php if ($this->filter_usergrp) : ?>
					<span class="hasTip filterdel" title="<?php echo JText::_('FLEXI_REMOVE_THIS_FILTER_DESC') ?>">
						<img src="templates/<?php echo $template;?>/images/flexi/bullet_delete.png" alt="<?php echo JText::_('FLEXI_REMOVE_THIS_FILTER') ?>" onclick="delFilter('filter_usergrp');document.adminForm.submit();" />
					</span>
					<?php endif; ?>
				</th>
				<th class="left">
					<?php echo JHTML::_('grid.sort',   'FLEXI_USER_EMAIL', 'a.email', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
				</th>
				<th class="center">
					<?php echo JHTML::_('grid.sort',    'FLEXI_REGISTRED_DATE', 'a.registerDate', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					<?php
					if ($this->date == '1') :
						if (($this->startdate && ($this->startdate != JText::_('FLEXI_FROM'))) || ($this->enddate && ($this->startdate != JText::_('FLEXI_TO')))) :
					?>
					<span class="hasTip filterdel" title="<?php echo JText::_('FLEXI_REMOVE_THIS_FILTER_DESC') ?>">
						<img src="templates/<?php echo $template;?>/images/flexi/bullet_delete.png" alt="<?php echo JText::_('FLEXI_REMOVE_THIS_FILTER') ?>" onclick="delFilter('startdate');delFilter('enddate');document.adminForm.submit();" />
					</span>
					<?php
						endif;
					endif;
					?>
				</th>
				<th class="center">
					<?php echo JHTML::_('grid.sort',   'FLEXI_USER_LAST_VISIT', 'a.lastvisitDate', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					<?php
					if ($this->date == '2') :
						if (($this->startdate && ($this->startdate != JText::_('FLEXI_FROM'))) || ($this->enddate && ($this->startdate != JText::_('FLEXI_TO')))) :
					?>
					<span class="hasTip filterdel" title="<?php echo JText::_('FLEXI_REMOVE_THIS_FILTER_DESC') ?>">
						<img src="templates/<?php echo $template;?>/images/flexi/bullet_delete.png" alt="<?php echo JText::_('FLEXI_REMOVE_THIS_FILTER') ?>" onclick="delFilter('startdate');delFilter('enddate');document.adminForm.submit();" />
					</span>
					<?php
						endif;
					endif;
					?>
				</th>
				<th class="center nowrap">
					<?php echo JHTML::_('grid.sort', 'FLEXI_ID', 'a.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?>
					<?php if ($this->filter_id) : ?>
					<span class="hasTip filterdel" title="<?php echo JText::_('FLEXI_REMOVE_THIS_FILTER_DESC') ?>">
						<img src="templates/<?php echo $template;?>/images/flexi/bullet_delete.png" alt="<?php echo JText::_('FLEXI_REMOVE_THIS_FILTER') ?>" onclick="delFilter('filter_id');document.adminForm.submit();" />
					</span>
					<?php endif; ?>
				</th>
			</tr>

			<tr id="filterline">
				<td class="left col_title" colspan="3">
					
				</td>
				<td class="left col_itemscount">
					<?php echo $this->lists['filter_itemscount']; ?>
				</td>
				<td class="left"></td>
				<td class="left col_logged">
					<?php echo $this->lists['filter_logged']; ?>
				</td>
				<td class="left"></td>
				<td class="left col_usergrp">
					<?php echo $this->lists['filter_usergrp']; ?>
				</td>
				<td class="left"></td>
				<td class="left col_registered col_visited" colspan="2">
					<span class="radio"><?php echo $this->lists['date']; ?></span>
					<br><div class="m5"><?php echo $this->lists['startdate']; ?>&nbsp;<?php echo $this->lists['enddate']; ?></div>
				</td>
				<td class="center col_id">
					<input type="text" name="filter_id" id="filter_id" value="<?php echo $this->lists['filter_id']; ?>" class="inputbox" />
				</td>
			</tr>

			


		</thead>
		<tfoot>
			<tr>
				<td colspan="12">
					<?php echo $pagination_footer; ?>
				</td>
			</tr>
		</tfoot>
		<tbody>
		<?php
			$k = 0;
			for ($i=0, $n=count( $this->items ); $i < $n; $i++)
			{
				$row 	=& $this->items[$i];
				if (FLEXI_J16GE) {
					$row->groupname = array();
					foreach($row->usergroups as $row_ugrp_id) {
						$row->groupname[] = $this->usergroups[$row_ugrp_id]->title;
					}
					$row->groupname = implode(', ', $row->groupname);
				}

				$img_path  = 'templates/'.$template.'/images/flexi/';
				$tick_img  = $img_path . 'tick.png';
				$block_img = $img_path . ($row->block ? 'publish_x.png' : 'tick.png');
				$task_block= (FLEXI_J16GE ? 'users.' : '') . ($row->block ? 'unblock' : 'block');
				$users_task = FLEXI_J16GE ? 'task=users.' : 'controller=users&amp;task=';
				$alt   = $row->block ? JText::_( 'Enabled' ) : JText::_( 'Blocked' );
				$link  = 'index.php?option=com_flexicontent&amp;controller=users&amp;view=user&amp;'.$users_task.'edit&amp;cid[]='. $row->id. '';

				if ($row->lastvisitDate == "0000-00-00 00:00:00") {
					$lvisit = JText::_( 'Never' );
				} else {
					$lvisit	= JHTML::_('date', $row->lastvisitDate, FLEXI_J16GE ? 'Y-m-d H:i:s' : '%Y-%m-%d %H:%M:%S');
				}
				$registered	= JHTML::_('date', $row->registerDate, FLEXI_J16GE ? 'Y-m-d H:i:s' : '%Y-%m-%d %H:%M:%S');

				if ($row->itemscount) {
					$itemslink 	= 'index.php?option=com_flexicontent&amp;view=items&amp;filter_authors='. $row->id;
					$itemscount = '[<a onclick="delAllFilters();"  href="'.$itemslink.'">'.$row->itemscount.'</a>]';
				} else {
					$itemscount = '['.$row->itemscount.']';
				}
			?>
			<tr class="<?php echo "row$k"; ?>">
				<td class="center">
					<?php echo $i+1+$this->pagination->limitstart;?>
				</td>
				<td class="center">
					<?php echo JHTML::_('grid.id', $i, $row->id ); ?>
				</td>
				<td class="col_title">
					<a href="<?php echo $link; ?>">
						<?php echo $row->name; ?></a>
				</td>
				<td class="center col_itemscount">
					<?php echo $itemscount; ?>
				</td>
				<td>
					<!-- <a class="modal" rel="{handler: 'iframe', size: {x: 800, y: 500}, onClose: function() {alert('hello');} }" href="<?php echo $link; ?>"> -->
					<?php echo $row->username; ?>
					<!-- </a> -->
				</td>
				<td class="center col_logged">
					<?php echo $row->loggedin ? '<img src="'.$tick_img.'" width="16" height="16" border="0" alt="" />': ''; ?>
				</td>
				<td class="center">
					<a href="javascript:void(0);" onclick="return listItemTask('cb<?php echo $i;?>','<?php echo $task_block;?>')">
						<img src="<?php echo $block_img;?>" width="16" height="16" border="0" alt="<?php echo $alt; ?>" /></a>
				</td>
				<td class="center col_usergrp">
					<?php echo JText::_( $row->groupname ); ?>
				</td>
				<td align="left">
					<a href="mailto:<?php echo $row->email; ?>">
						<?php echo $row->email; ?></a>
				</td>
				<td class="center nowrap col_registered">
					<?php echo $registered; ?>
				</td>
				<td class="center nowrap col_visited">
					<?php echo $lvisit; ?>
				</td>
				<td class="center col_id">
					<?php echo $row->id; ?>
				</td>
			</tr>
			<?php
				$k = 1 - $k;
				}
			?>
		</tbody>
	</table>
	
	
</div>

</div>
<p><sup>[1]</sup> <?php echo JText::_('FLEXI_BY_DEFAULT_ONLY_AUTHORS_WITH_ITEMS_SHOWN'); ?></p>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_flexicontent" />
	<input type="hidden" name="controller" value="users" />
	<input type="hidden" name="view" value="users" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div></div>