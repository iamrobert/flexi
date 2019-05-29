<?php
/**
 * @version 1.5 stable $Id: default.php 1793 2013-10-20 02:22:05Z ggppdk $
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

use Joomla\String\StringHelper;
JHtml::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_flexicontent/helpers/html');

global $globalcats;
$app      = JFactory::getApplication();
$jinput   = $app->input;
$config   = JFactory::getConfig();
$user     = JFactory::getUser();
$session  = JFactory::getSession();
$document = JFactory::getDocument();
$cparams  = JComponentHelper::getParams('com_flexicontent');
//$ctrl     = 'templates.';
//$hlpname  = 'fctemplates';
$isAdmin  = $app->isAdmin();
$useAssocs= flexicontent_db::useAssociations();


$ico_class = 'btn'; //'fc-man-icon-s';


$tip_class = ' hasTooltip';
$commentimage = JHtml::image ( 'administrator/components/com_flexicontent/assets/images/comments.png', JText::_( 'FLEXI_COMMENT' ), ' class="fc-man-icon-s" style="vertical-align:top;" ');
$loading_msg = flexicontent_html::encodeHTML(JText::_('FLEXI_LOADING') .' ... '. JText::_('FLEXI_PLEASE_WAIT'), 2);

JText::script("FLEXI_UPDATING_CONTENTS", true);
JFactory::getDocument()->addScriptDeclaration('
	function fc_template_modal_close()
	{
		window.location.reload(false);
		document.body.innerHTML = Joomla.JText._("FLEXI_UPDATING_CONTENTS") + \' <img id="page_loading_img" src="components/com_flexicontent/assets/images/ajax-loader.gif">\';
	}
');


$basetemplates = array('default', 'blog', 'faq', 'items-tabbed', 'presentation');
$ctrl_task = FLEXI_J16GE ? 'task=templates.' : 'controller=templates&task=';
$form_token = JSession::getFormToken();
$js = "
var fc_tmpls_modal;

jQuery(document).ready(function() {
	jQuery('span.deletable-template').click(function( event ) {
		var answer = confirm('".JText::_( 'FLEXI_TEMPLATE_DELETE_CONFIRM',true )."')
		if (!answer) return;
		var el = jQuery(this);
		var tmpl_name = el.attr('id').replace('del-','');
		
		el.html('<img src=\"components/com_flexicontent/assets/images/ajax-loader.gif\" style=\"vertical-align: middle;\">');
		jQuery.ajax({
			type: \"GET\",
			url:  \"index.php?option=com_flexicontent&".$ctrl_task."remove&format=raw&dir=\" + tmpl_name + \"&".$form_token."=1\",
			success: function(str) {
				el.parent().css('width','200px');
				el.parent().html(str);
			}
		});
	});
});
";
JFactory::getDocument()->addScriptDeclaration($js);

$edit_layout = htmlspecialchars(JText::_('FLEXI_EDIT_LAYOUT_N_GLOBAL_PARAMETERS', true), ENT_QUOTES, 'UTF-8');
$edit_icon         = '<span class="icon-edit"></span>';
$editSingle_icon   = $edit_icon . ' <span class="icon-file"></span>';
	//JHtml::image ( 'components/com_flexicontent/assets/images/page_single_edit.png', $edit_layout, ' style="min-width:22px;" class="'.$ico_class.' '.$tip_class.'" title="'.$edit_layout.'" ' );
$editMultiple_icon = $edit_icon . ' <span class="icon-stack"></span>';
	//JHtml::image ( 'components/com_flexicontent/assets/images/page_multiple_edit.png', $edit_layout, ' style="min-width:22px;" class="'.$ico_class.' '.$tip_class.'" title="'.$edit_layout.'" '  );
$editLayout_icon   = $editSingle_icon;
$noEditLayout_icon = '<span class="icon-edit" title="' . JText::_( 'FLEXI_NOEDIT_LAYOUT', true ) .'"></span>';
$copyTmpl_icon     = '<span class="icon-copy"></span>';
	//JHtml::image ( 'administrator/components/com_flexicontent/assets/images/layout_add.png', JText::_( 'FLEXI_DUPLICATE', true ), ' style="min-width:16px;" class="'.$ico_class.' '.$tip_class.'" title="'.JText::_( 'FLEXI_DUPLICATE', true ).'" '  );
$delTmpl_icon      = '<span class="icon-delete"></span>';
	//JHtml::image ( 'administrator/components/com_flexicontent/assets/images/layout_delete.png', JText::_( 'FLEXI_REMOVE', true ), ' style="min-width:16px;" class="'.$ico_class.' '.$tip_class.'" title="'.JText::_( 'FLEXI_REMOVE', true ).'" '  );

$list_total_cols = 8;
?>


<div id="flexicontent" class="flexicontent">

<form action="index.php" method="post" name="adminForm" id="adminForm">


<div class="<?php echo FLEXI_J40GE ? 'row' : 'row-fluid'; ?>">

<?php if (!empty( $this->sidebar)) : ?>

	<div id="j-sidebar-container" class="span2 col-md-2">
		<?php echo str_replace('type="button"', '', $this->sidebar); ?>
	</div>
	<div id="j-main-container" class="span10 col-md-10">

<?php else : ?>

	<div id="j-main-container" class="span12 col-md-12">

<?php endif;?>


<div id="outer_templates">

   
   <div class="alert alert-info">
      <div class="alert-message">
  <h4>Configure display of your fields <strong>item</strong> view and <strong>multi-item</strong> views</h4>
 <p><span class="label label-info">ITEM Layout</span> Select this in configuration of <strong>types</strong> and (optionally) in <strong>items</strong></p>
      <p><span class="label label-info">CATEGORY Layout</span> Select this in configuration of <strong>categories / content lists</strong> except for <b>search view</b></p>
         </div>
</div>
   

	
	<!--
	<?php $_class = FLEXI_J30GE ? ' btn' : ' fc_button fcsimple fcsmall'; ?>
	<div class="btn-group" style="margin: 2px 32px 6px -3px; display:inline-block;">
		<input type="button" id="fc_howto_box_btn" class="<?php echo $_class; ?> btn-warning" onclick="fc_toggle_box_via_btn('howto_box', this, 'btn-primary');" value="<?php echo JText::_( 'FLEXI_HOW_TO' ); ?>" />
	</div>
	-->
	
	<table id="adminListTableFCtemplates" class="adminlist table fcmanlist" style="width: 100% !important;">
	
	<thead>
		<tr>
			<th class="col_num"><?php echo JText::_( 'FLEXI_NUM' ); ?></th>
			<th class="col_cb">      
            <div class="group-fcset">
					<input type="checkbox" name="checkall-toggle" id="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
					<label for="checkall-toggle" class="green single"></label>
				</div>
			</th>
			<th class="col_status"></th>
			<th class="col_title"><?php echo JText::_( 'FLEXI_TEMPLATE_NAME' ); ?></th>
			<th colspan="2" class="col_item">
				<?php echo JText::_( 'FLEXI_SINGLE_CONTENT' ); ?><br/>
				<span class="label label-info">ITEM Layout</span>
			</th>
			<th colspan="2" class="col_cat">
				<?php echo JText::_( 'FLEXI_CONTENT_LISTS' ); ?><br/>
				<span class="label label-info">CATEGORY Layout</span>
			</th>

		</tr>
	</thead>

	<tbody>
		<?php
		$k = 0;
		$i = 0;
		// Get cached texts to avoid reloading all language files
		$item_texts = flexicontent_tmpl::getLayoutTexts('items');
		$cats_texts = flexicontent_tmpl::getLayoutTexts('category');
		foreach ($this->rows as $row) :
			$copylink 	= 'index.php?option=com_flexicontent&amp;view=templates&amp;layout=duplicate&amp;tmpl=component&amp;source='. $row->name;
			$itemlink	= 'index.php?option=com_flexicontent&amp;view=template&amp;type=items&amp;folder=' . $row->name . '&amp;' . JSession::getFormToken() . '=1';
			$catlink	= 'index.php?option=com_flexicontent&amp;view=template&amp;type=category&amp;folder=' . $row->name . '&amp;' . JSession::getFormToken() . '=1';
			
			$defaulttitle_item = !empty($row->items)    ? @ $item_texts->{$row->name}->title    : '';
			$defaulttitle_cat  = !empty($row->category) ? @ $cats_texts->{$row->name}->title : '';
			
			$description_item = !empty($row->items)    ? @ $item_texts->{$row->name}->description    : '';
			$description_cat  = !empty($row->category) ? @ $cats_texts->{$row->name}->description : '';
			
      
      
			$row->id = $row->name;
			$checked	= JHtml::_('grid.checkedout', $row, $i );
			?>
		<tr class="<?php echo "row$k"; ?>" id="<?php echo 'up-'.$row->name ?>">
			<td class="col_num">
				<?php echo $i+1; ?>
			</td>
			<td class="col_cb">
				<!--div class="adminlist-table-row"></div-->         
     
            
            				<div class="group-fcset"><?php echo JHtml::_('grid.id', $i, $row->id); ?>
								<label for="cb<?php echo $i; ?>" class="green single">
					<span class="sr-only"><?php JText::_('JSELECT'); ?></span>
				</label>
                           
                           
            </div>
   
							
            
            
			</td>
			<td class="col_status">
				<?php if (!in_array($row->name, $basetemplates)) :?>
					<span class="btn btn-small hasTooltip deletable-template" title="<?php echo JText::_('FLEXI_REMOVE_TEMPLATE', true); ?>" id="<?php echo 'del-' . $row->name ?>">
						<?php echo $delTmpl_icon; ?>
					</span>
			 	<?php endif; ?>
				<?php /*<a class="modal" onclick="javascript:;" rel="{handler: 'iframe', size: {x: 390, y: 210}}" href="<?php echo $copylink; ?>"><?php echo $copyTmpl_icon; ?></a>*/ ?>
				<span class="btn btn-small hasTooltip" title="<?php echo JText::_('FLEXI_DUPLICATE_TEMPLATE', true); ?>" onclick="var url = jQuery(this).attr('data-href'); fc_tmpls_modal = fc_showDialog(url, 'fc_modal_popup_container', 0, 440, 300, fc_template_modal_close); return false;" data-href="<?php echo $copylink; ?>">
					<?php echo $copyTmpl_icon; ?>
				</span>
			</td>
			<td class="col_title">
				<?php echo htmlspecialchars($row->name, ENT_QUOTES, 'UTF-8'); ?>
				<?php if (in_array($row->name, $basetemplates)) :?>
					<!--<span class="icon-lock"></span>-->
				<?php else: ?>
					<span class="icon-user"></span><span class="badge"><?php echo JText::_('FLEXI_USER').' - '.JText::_('FLEXI_CREATED'); ?></span>
				<?php endif; ?>
			</td>
			<td class="w80 no-padding-right">
				<?php echo !empty($row->items)
					? '<a class="btn hasTooltip" href="'.$itemlink.'" title="'.$edit_layout.'">'.$editSingle_icon.'</a>'
					: '<span class="btn disabled">'.$noEditLayout_icon.'</span>'; ?>
			</td>
			<td>
				<?php if ($defaulttitle_item): ?>
					<span data-placement="top" class="<?php echo $tip_class; ?>" title="<?php echo flexicontent_html::getToolTip('', $description_item, 0, 1); ?>" >
						<i class="icon-info"></i>
					</span>
					<?php echo $defaulttitle_item; ?>
				<?php endif; ?>
			</td>
			<td class="w80 no-padding-right">
				<?php echo !empty($row->category)
					? '<a class="btn hasTooltip" href="'.$catlink.'" title="'.$edit_layout.'">'.$editMultiple_icon.'</a>'
					: '<span class="btn disabled">'.$noEditLayout_icon.'</span>'; ?>
			</td>
			<td>
				<?php if ($defaulttitle_cat): ?>
					<span data-placement="top" class="<?php echo $tip_class; ?>" title="<?php echo flexicontent_html::getToolTip('', $description_cat, 0, 1); ?>" >
						<i class="icon-info"></i>
					</span>
					<?php echo $defaulttitle_cat; ?>
				<?php endif; ?>
			</td>

		</tr>
		<?php
		$k = 1 - $k;
		$i++;
		endforeach;
		?>
	</tbody>

	<tfoot>
		<tr>
			<td colspan="<?php echo $list_total_cols; ?>" style="text-align: left;">
				<table class="admintable" style="margin: 0 auto !important;">
					<tr>
						<td>
						<?php echo '<span style="font-size: 14px;">'.$copyTmpl_icon.'</span>'; ?>
						</td>
						<td>
						<?php echo JText::_( 'FLEXI_DUPLICATE_TEMPLATE' ); ?>
						</td>
						<td>
						<?php echo '<span style="font-size: 14px;">'.$edit_icon.'</span>'; ?>
						</td>
						<td>
						<?php echo JText::_( 'FLEXI_EDIT_LAYOUT_N_GLOBAL_PARAMETERS' ); ?>
						</td>
					</tr>
					<tr>
						<td>
						<?php echo '<span style="font-size: 14px;">'.$delTmpl_icon.'</span>'; ?>
						</td>
						<td>
						<?php echo JText::_( 'FLEXI_REMOVE_TEMPLATE' ); ?>
						</td>
						<td>
						<?php echo '<span  style="font-size: 14px;" class="disabled">'.$noEditLayout_icon.'</span>'; ?>
						</td>
						<td>
						<?php echo JText::_( 'FLEXI_NOEDIT_LAYOUT' ); ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</tfoot>

	</table>
</div>


	<div class="fcclear"></div>
	
	<!-- Common management form fields -->
	<input type="hidden" name="option" value="com_flexicontent" />
	<input type="hidden" name="controller" value="templates" />
	<input type="hidden" name="view" value="templates" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="fcform" value="1" />
	<?php echo JHtml::_('form.token'); ?>

	<!-- fc_perf -->

	</div>  <!-- j-main-container -->
</div>  <!-- row / row-fluid-->

</form>
</div><!-- #flexicontent end -->
