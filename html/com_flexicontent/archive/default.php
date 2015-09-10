<?php
/**
 * @version 1.5 stable $Id: default.php 1614 2013-01-04 03:57:15Z ggppdk $
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
$ctrl = FLEXI_J16GE ? 'archive.' : '';
$items_task = FLEXI_J16GE ? 'task=items.' : 'controller=items&amp;task=';
$categories_task = FLEXI_J16GE ? 'task=categories.' : 'controller=categories&amp;task=';
$tip_class = FLEXI_J30GE ? ' hasTooltip' : ' hasTip';
$btn_class = FLEXI_J30GE ? 'btn' : 'fc_button fcsimple';
?>

<div class="flexicontent">
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">

<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>


<div id="fc-filters-header">

 <span class="btn-wrapper input-append fc-filter"><span class="filter-search btn-group">
          <input type="text" name="search" id="search" placeholder="<?php echo JText::_( 'FLEXI_SEARCH' ); ?>" value="<?php echo htmlspecialchars($this->lists['search'], ENT_QUOTES, 'UTF-8'); ?>" class="text_area" onChange="document.adminForm.submit();" />
          </span> <span class="btn-group">
          <button title="<?php echo JText::_( 'FLEXI_SEARCH' ); ?>" class="<?php echo $btn_class; ?> <?php echo $tip_class; ?>" onclick="this.form.submit();"  data-original-title="<?php echo JText::_( 'FLEXI_SEARCH' ); ?>"><?php echo FLEXI_J30GE ? '<i class="icon-search"></i>' : JText::_('FLEXI_GO'); ?></button>
          <button title="<?php echo JText::_('FLEXI_RESET_FILTERS'); ?>" class="<?php echo $btn_class; ?> hidden-phone" onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo FLEXI_J30GE ? '<i class="icon-remove"></i>' : JText::_('FLEXI_CLEAR'); ?></button>
          </span></span>
          
</div>

<div class="block-flat">
	<table class="adminlist fcmanlist table no-border hover">
	<thead>
		<tr class="header">
			<th class="col_no hidden-tablet hidden-phone center"><?php echo JText::_( 'FLEXI_NUM' ); ?></th>
			<th class="center"><input type="checkbox" name="toggle" value="" onClick="<?php echo FLEXI_J30GE ? 'Joomla.checkAll(this);' : 'checkAll('.count( $this->rows).');'; ?>" /></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'FLEXI_TITLE', 'i.title', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="center hidden-tablet hidden-phone"><?php echo JHTML::_('grid.sort', 'FLEXI_ALIAS', 'i.alias', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="center"><?php echo JText::_( 'FLEXI_CATEGORIES' ); ?></th>
			<th class="center hidden-tablet hidden-phone"><?php echo JHTML::_('grid.sort', 'FLEXI_ID', 'i.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		</tr>
	</thead>



	<tbody>
		<?php
		$k = 0;
		for ($i=0, $n=count($this->rows); $i < $n; $i++) {
			$row = $this->rows[$i];

			$link 		= 'index.php?option=com_flexicontent&amp;'.$items_task.'edit&amp;cid[]='. $row->id;
			$checked 	= @ JHTML::_('grid.checkedout', $row, $i );
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td class="col_no hidden-tablet hidden-phone center"><?php echo $this->pageNav->getRowOffset( $i ); ?></td>
			<td class="center"><?php echo $checked; ?></td>
			<td class="title">
				<?php
				if ( $row->checked_out && ( $row->checked_out != $this->user->get('id') ) ) {
					echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8');
				} else {
				?>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'FLEXI_EDIT_ITEM' );?>::<?php echo $row->title; ?>">
					<a href="<?php echo $link; ?>">
					<?php echo htmlspecialchars($row->title, ENT_QUOTES, 'UTF-8'); ?>
					</a></span>
				<?php
				}
				?>
			</td>
			<td class="center hidden-tablet hidden-phone">
				<?php
				if (JString::strlen($row->alias) > 25) {
					echo JString::substr( htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8'), 0 , 25).'...';
				} else {
					echo htmlspecialchars($row->alias, ENT_QUOTES, 'UTF-8');
				}
				?>
			</td>
			<td class="center">
				<?php 
				$nr = count($row->categories);
				$ix = 0;
				$row->categories = is_array($row->categories) ? $row->categories : array();
				foreach ($row->categories as $key => $category) :
				
					$catlink	= 'index.php?option=com_flexicontent&amp;'.$categories_task.'edit&amp;cid[]='. $category->id;
					$title = htmlspecialchars($category->title, ENT_QUOTES, 'UTF-8');
				?>
					<span class="editlinktip hasTip" title="<?php echo JText::_( 'FLEXI_EDIT_CATEGORY' );?>::<?php echo $title; ?>">
					<a href="<?php echo $catlink; ?>">
						<?php 
						if (JString::strlen($title) > 20) {
							echo JString::substr( $title , 0 , 20).'...';
						} else {
							echo $title;
						}
						?></a></span><?php
					$ix++;
					if ($ix != $nr) :
						echo ', ';
					endif;
				endforeach;
				?>
			</td>
			<td class="center hidden-tablet hidden-phone"><?php echo $row->id; ?></td>
		</tr>
		<?php $k = 1 - $k; } ?>
	</tbody>

	</table>
    
    <p><?php echo $this->pageNav->getListFooter(); ?></p>
</div>


	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_flexicontent" />
	<input type="hidden" name="controller" value="archive" />
	<input type="hidden" name="view" value="archive" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
	
	</div>
</form>
</div>