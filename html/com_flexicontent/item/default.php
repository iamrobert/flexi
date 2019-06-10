<?php
/**
 * @package         FLEXIcontent
 * @version         3.3
 *
 * @author          Emmanuel Danan, Georgios Papadakis, Yannick Berges, others, see contributor page
 * @link            https://flexicontent.org
 * @copyright       Copyright © 2018, FLEXIcontent team, All Rights Reserved
 * @license         http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');

use Joomla\String\StringHelper;

$task_items = 'task=items.';
$ctrl_items = 'items.';
$tags_task  = 'task=tags.';

// For tabsets/tabs ids (focusing, etc)
$tabSetCnt = -1;
$tabSetMax = -1;
$tabCnt = array();
$tabSetStack = array();

$tags_displayed = $this->item->type_id && ( $this->perms['cantags'] || count(@$this->usedtagsdata) ) ;

$close_btn = '<a class="close" data-dismiss="alert">&#215;</a>';  // '<a class="fc-close" onclick="this.parentNode.parentNode.removeChild(this.parentNode);">&#215;</a>';
$alert_box = '<div %s class="alert alert-%s %s">'.$close_btn.'%s</div>';  // '<div %s class="fc-mssg fc-%s %s">'.$close_btn.'%s</div>';
$btn_class = 'btn';  // 'fc_button';
$tip_class = ' hasTooltip';
$lbl_class = ' ' . $this->params->get('form_lbl_class_be', '');
$noplugin = '<div class="fc-mssg-inline fc-warning" style="margin:0 2px 6px 2px; max-width: unset;">'.JText::_( 'FLEXI_PLEASE_PUBLISH_THIS_PLUGIN' ).'</div>';

$hint_image = '<i class="icon-info"></i>';//JHtml::image ( 'administrator/components/com_flexicontent/assets/images/comments.png', JText::_( 'FLEXI_NOTES' ), 'style="vertical-align:top;"' );
$warn_image = '<i class="icon-warning"></i>';//JHtml::image ( 'administrator/components/com_flexicontent/assets/images/note.gif', JText::_( 'FLEXI_NOTES' ), 'style="vertical-align:top;"' );
$conf_image = '<i class="icon-cog"></i>';

$add_on_class    = $this->params->get('bootstrap_ver', 2)==2  ?  'add-on' : 'input-group-addon';
$input_grp_class = $this->params->get('bootstrap_ver', 2)==2  ?  'input-append input-prepend' : 'input-group';

// add extra css/js for the edit form
if ($this->params->get('form_extra_css'))    $this->document->addStyleDeclaration($this->params->get('form_extra_css'));
if ($this->params->get('form_extra_css_be')) $this->document->addStyleDeclaration($this->params->get('form_extra_css_be'));
if ($this->params->get('form_extra_js'))     $this->document->addScriptDeclaration($this->params->get('form_extra_js'));
if ($this->params->get('form_extra_js_be'))  $this->document->addScriptDeclaration($this->params->get('form_extra_js_be'));

// Load JS tabber lib
$this->document->addScriptVersion(JUri::root(true).'/components/com_flexicontent/assets/js/tabber-minimized.js', FLEXI_VHASH);
$this->document->addStyleSheetVersion(JUri::root(true).'/components/com_flexicontent/assets/css/tabber.css', FLEXI_VHASH);
$this->document->addScriptDeclaration(' document.write(\'<style type="text/css">.fctabber{display:none;}<\/style>\'); ');  // temporarily hide the tabbers until javascript runs

if ($this->perms['cantags'] || $this->perms['canversion'])
{
	//$this->document->addScriptVersion(JUri::root(true).'/components/com_flexicontent/librairies/jquery-autocomplete/jquery.bgiframe.min.js', FLEXI_VHASH);
	//$this->document->addScriptVersion(JUri::root(true).'/components/com_flexicontent/librairies/jquery-autocomplete/jquery.ajaxQueue.js', FLEXI_VHASH);
	//$this->document->addScriptVersion(JUri::root(true).'/components/com_flexicontent/librairies/jquery-autocomplete/jquery.autocomplete.min.js', FLEXI_VHASH);
	$this->document->addScriptVersion(JUri::root(true).'/components/com_flexicontent/assets/js/jquery.pager.js', FLEXI_VHASH);     // e.g. pagination for item versions
	$this->document->addScriptVersion(JUri::root(true).'/components/com_flexicontent/assets/js/jquery.autogrow.js', FLEXI_VHASH);  // e.g. autogrow version comment textarea

	//$this->document->addStyleSheetVersion(JUri::root(true).'/components/com_flexicontent/librairies/jquery-autocomplete/jquery.autocomplete.css', FLEXI_VHASH);

	JText::script("FLEXI_DELETE_TAG", true);
	JText::script("FLEXI_ENTER_TAG", true);

	$this->document->addScriptDeclaration("
		jQuery(document).ready(function(){

			jQuery('.deletetag').click(function(e){
				jQuery(this).parent().remove();
				return false;
			});

			var tagInput = jQuery('#input-tags');

			tagInput.keydown(function(event)
			{
				if (event.keyCode == 13)
				{
					var el = jQuery(event.target);
					if (el.val()=='') return false; // No tag to assign / create

					var selection_isactive = jQuery('.ui-autocomplete .ui-state-focus').length != 0;
					if (selection_isactive) return false; // Enter pressed, while autocomplete item is focused, autocomplete \'select\' event handler will handle this

					var data_id   = el.data('tagid');
					var data_name = el.data('tagname');
					//window.console.log( 'User input: '+el.val() + ' data-tagid: ' + data_id + ' data-tagname: \"'+ data_name + '\"');

					if (el.val() == data_name && data_id != '' && data_id != '0')
					{
						//window.console.log( 'Assigning found tag: (' + data_id + ', \"' + data_name + '\")');
						addToList(data_id, data_name);
						el.autocomplete('close');
					}
					else {
						//window.console.log( 'Retrieving (create-if-missing) tag: \"' + el.val() + '\"');
						addtag(0, el.val());
						el.autocomplete('close');
					}

					el.val('');  //clear existing value
					return false;
				}
			});

			var fcTagsCache = {};

			jQuery.ui.autocomplete({
				source: function( request, response )
				{
					var el   = jQuery(this.element);
					var term = request.term;

					if (term in fcTagsCache)
					{
						response(fcTagsCache[term]);
						return;
					}

					//window.console.log( 'Getting tags for \"' + term + '\" ...');
					jQuery.ajax({
						url: '".JUri::root(true)."/components/com_flexicontent/tasks/core.php?". JSession::getFormToken() ."=1',
						dataType: 'json',
						data: {
							q: term,
							task: 'viewtags',
							format: 'json'
						},
						success: function(data)
						{
							//window.console.log( '... received tags for \"' + term + '\"');
							var response_data = jQuery.map(data, function(item)
							{
								if (el.val() == item.name)
								{
									//window.console.log( 'Found exact TAG match, (' + item.id + ', \"' + item.name + '\")');
									el.data('tagid',   item.id);
									el.data('tagname', item.name);
								}
								return jQuery('#ultagbox').find('input[value=\"'+item.id+'\"]').length > 0 ? null : { label: item.name, value: item.id };
							});

							fcTagsCache[term] = response_data;
							response(response_data);
						}
					});
				},

				delay: 200,
				minLength: 0,

				focus: function ( event, ui )
				{
					//window.console.log( (ui.item  ?  'current ID: ' + ui.item.value + ' , current Label: ' + ui.item.label :  'Nothing selected') );

					var el = jQuery(event.target);
					if (ui.item.value!='' && ui.item.value!='0')
					{
						el.val(ui.item.label);
					}
					el.data('tagid',   ui.item.value);
					el.data('tagname', ui.item.label);

					event.preventDefault();  // Prevent default behaviour of setting 'ui.item.value' into the input
				},

				select: function( event, ui )
				{
					//window.console.log( 'Selected: ' + ui.item.label + ', input was \'' + this.value + '\'');

					var el = jQuery(event.target);
					if (ui.item.value != '' && ui.item.value != '0')
					{
						addToList(ui.item.value, ui.item.label);
						el.val('');  //clear existing value
					}

					event.preventDefault();  // Prevent default behaviour of setting 'ui.item.value' into the input and triggering change event
				},

				//change: function( event, ui ) { window.console.log( 'autocomplete change()' ); },
				//open: function() { window.console.log( 'autocomplete open()' ); },
				//close: function() { window.console.log( 'autocomplete close()' ); },
				//search: function() { window.console.log( 'autocomplete search()' ); }
			}, tagInput.get(0) );

			// Call search method on focus to allow immediate search
			tagInput.focus(function () {
				jQuery(this).autocomplete('search', this.value);
			});

			// Call autocomplete.search method to load and cache all tags up to a maximum ... e.g. 500
			tagInput.attr('readonly', 'readonly').autocomplete('search', '').autocomplete('close').removeAttr('readonly');
		});


		function addToList(id, name)
		{
			// Prefer quick tag selector if it exists
			var cmtag = jQuery('#quick-tag-'+id);

			if (cmtag.length)
			{
				cmtag.attr('checked', 'checked').trigger('change');
			}
			else
			{
				var obj = jQuery('#ultagbox');
				if (obj.find('input[value=\"'+id+'\"]').length > 0)
				{
					return;
				}
				obj.append('<li class=\"tagitem\"><span>'+name+'</span><input type=\"hidden\" name=\"jform[tag][]\" value=\"'+id+'\" /><a href=\"javascript:;\" class=\"deletetag\" onclick=\"javascript:deleteTag(this);\" title=\"' + Joomla.JText._('FLEXI_DELETE_TAG') + '\"></a></li>');
			}
		}


		function addtag(id, tagname)
		{
			id = id==null ? 0 : id;

			if (tagname == '')
			{
				alert(\" + Joomla.JText._('FLEXI_ENTER_TAG') + \");
				return;
			}

			var tag = new itemscreen();
			tag.addtag( id, tagname, '".JUri::base(true)."/index.php?option=com_flexicontent&".$tags_task."addtag&format=raw&". JSession::getFormToken() ."=1');
		}

		function deleteTag(obj)
		{
			var parent = obj.parentNode;
			parent.innerHTML = '';
			parent.parentNode.removeChild(parent);
		}


		PageClick = function(pageclickednumber) {
			jQuery.ajax({ url: '".JUri::base(true)."/index.php?option=com_flexicontent&".$task_items."getversionlist&id=".$this->item->id."&active=".$this->item->version."&". JSession::getFormToken() ."=1&format=raw&page='+pageclickednumber, context: jQuery('#version_tbl'), success: function(str){
				jQuery(this).html(\"\\
				<table class='fc-table-list fc-tbl-short' style='margin:10px;'>\\
				\"+str+\"\\
				</table>\\
				\");
				jQuery('#version_tbl').find('.hasTooltip').tooltip({html: true, container: jQuery('#version_tbl')});
				jQuery('#version_tbl').find('.hasPopover').popover({html: true, container: jQuery('#version_tbl'), trigger : 'hover focus'});

				// Attach click event to version compare links of the newly created page
				jQuery(this).find('a.modal-versions').each(function(index, value) {
					jQuery(this).on('click', function() {
						// Load given URL in an popup dialog
						var url = jQuery(this).attr('href');
						fc_showDialog(url, 'fc_modal_popup_container');
						return false;
					});
				});
			}});

			// Reattach pagination inside the newly created page
			jQuery('#fc_pager').pager({ pagenumber: pageclickednumber, pagecount: ".$this->pagecount.", buttonClickCallback: PageClick });
		}

		jQuery(document).ready(function()
		{
			// For the initially displayed versions page:  Add onclick event that opens compare in popup
			jQuery('a.modal-versions').each(function(index, value) {
				jQuery(this).on('click', function() {
					// Load given URL in an popup dialog
					var url = jQuery(this).attr('href');
					fc_showDialog(url, 'fc_modal_popup_container');
					return false;
				});
			});

			// Attach pagination for versions listing
			jQuery('#fc_pager').pager({
				pagenumber: " . $this->current_page . ",
				pagecount: " . $this->pagecount . ",
				buttonClickCallback: PageClick
			});

			// Attach textarea autogrow height (while typing)
			jQuery('#versioncomment').autogrow({
				minHeight: 32,
				maxHeight: 250,
				lineHeight: 16
			});
		})
	");
}

// version variables
$this->document->addScriptDeclaration
("
	jQuery(document).ready(function(){
		var hits = new itemscreen('hits', {id:".($this->item->id ? $this->item->id : 0).", task:'".$ctrl_items."gethits', sess_token:'" . JSession::getFormToken() . "'});
		//hits.fetchscreen();

		var votes = new itemscreen('votes', {id:".($this->item->id ? $this->item->id : 0).", task:'".$ctrl_items."getvotes', sess_token:'" . JSession::getFormToken() . "'});
		//votes.fetchscreen();
	});

	function reseter(task, id, div)
	{
		var res = new itemscreen();
		task = '".$ctrl_items."' + task;
		res.reseter(task, id, div, '" . JUri::base(true) . "/index.php?option=com_flexicontent&controller=items&" . JSession::getFormToken() . "=1');
	}

	function clickRestore(link)
	{
		if (confirm('".JText::_( 'FLEXI_CONFIRM_VERSION_RESTORE',true )."'))
		{
			location.href=link;
		}

		return false;
	}
");

array_push( $tabSetStack, $tabSetCnt );
$tabSetCnt = ++$tabSetMax;
$tabCnt[ $tabSetCnt ] = 0;

// Create info images
$infoimage    = JHtml::image ( 'administrator/templates/iamrobert3/images/flexi/comments.svg', JText::_( 'FLEXI_NOTES' ) );
$revertimage  = JHtml::image ( 'administrator/templates/iamrobert3/images/flexi/arrow_rotate_anticlockwise.svg', JText::_( 'FLEXI_REVERT' ) );
$viewimage    = JHtml::image ( 'administrator/templates/iamrobert3/images/flexi/magnifier.svg', JText::_( 'FLEXI_VIEW' ) );
$commentimage = JHtml::image ( 'administrator/templates/iamrobert3/images/flexi/comments.svg', JText::_( 'FLEXI_COMMENT' ) );

// Create some variables
$itemlang = substr($this->item->language ,0,2);
if (isset($this->item->item_translations)) foreach ($this->item->item_translations as $t) if ($t->shortcode==$itemlang) {$itemlangname = $t->name; break;}
?>

		<script>
/* tab memory */
jQuery(function($) {
	var json, tabsState;
	$('a[data-toggle="pill"], a[data-toggle="tab"]').on('shown', function(e) {
		var href, json, parentId, tabsState;
		tabsState = localStorage.getItem("tabs-state");
		json = JSON.parse(tabsState || "{}");
		parentId = $(e.target).parents("ul.nav.nav-pills, ul.nav.nav-tabs").attr("id");
		href = $(e.target).attr('href');
		json[parentId] = href;
		return localStorage.setItem("tabs-state", JSON.stringify(json));
	});
	tabsState = localStorage.getItem("tabs-state");
	json = JSON.parse(tabsState || "{}");
	$.each(json, function(containerId, href) {
		if (!$("body").hasClass("task-add")) {
			return $("#" + containerId + " a[href=" + href + "]").tab('show');
		}
	});
	$("ul.nav.nav-pills, ul.nav.nav-tabs").each(function() {
		var $this = $(this);
		if (!json[$this.attr("id")]) {
			return $this.find("a[data-toggle=tab]:first, a[data-toggle=pill]:first").tab("show");
		}
	});
	////VALIDATOR SWITCH TO TAB
	$("#toolbar a").click(function() {
		$('.invalid').each(function() {
			var id = $('.tab-pane').find(':required:invalid').closest('.tab-pane').attr('id');
			$('.nav a[href="#' + id + '"]').tab('show');
		});
      //
//       var newTitle = $('#jform_title').val();
//      $( ".contentx" ).text(newTitle);
      
	});
   
          var clockResetIndex = 0 ;
        // this is the input we are tracking
        var tarGetInput = $('input#jform_title');

        tarGetInput.on( 'keyup keypress paste' , ()=>{
            // reset any privious clock:
            if (clockResetIndex !== 0) clearTimeout(clockResetIndex);

            // set a new clock ( timeout )
            clockResetIndex = setTimeout(() => {
                // your code goes here :
                //console.log( new Date() , tarGetInput.val())
              
               
               
                if(tarGetInput.val() == "") {
                   $( ".contentx" ).text('Title is Required');
                   
                } else {
                   $( ".contentx" ).text(tarGetInput.val());
                   
               }
            }, 1000);
        });
   

});
			
			///
			

			
		</script>
<?php /* echo "Version: ". $this->item->version."<br/>\n"; */?>
<?php /* echo "id: ". $this->item->id."<br/>\n"; */?>
<?php /* echo "type_id: ". @$this->item->type_id."<br/>\n"; */?>


<div id="flexicontent" class="flexi_edit full_body_box flexicontent" >

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal" enctype="multipart/form-data" >
   
				<div class="row-fluid">
					<div class="span12">
						<h1 class="contentx">
							<?php if ($this->form->getValue('title') == '') {
echo JText::_('FLEXI_NEW_ITEM');
}
else {
	echo $this->form->getValue('title');
};?>
						</h1>
					</div>
				</div>
   
<div class="row-fluid">

   <div class="span12">
<!-- 
####################### 
MAIN COLUMN
####################### 
-->
						<?php 
$options = array('active'    => 'tab'.$tabCnt[$tabSetCnt].'');
echo JHtml::_('bootstrap.startTabSet', 'ID-Tabs-'.$tabSetCnt.'', $options, array('useCookie'=>1));?>



						<!--FLEXI_BASIC TAB1 -->
						<?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', '<i class="icon-star"></i><br class="hidden-phone">BASIC'); ?>
						<!--FLEXI_BASIC CONTENT -->
					
									<!--LETS SPLIT INTO 2 COLUMNS-->
						<div class="row-fluid">
							<div class="span8 full_width_980">
							
							<?php
								$field = isset( $this->fields[ 'title' ] ) ? $this->fields[ 'title' ] : false;
								if ( $field ) {
									$field_description = $field->description ? $field->description :
										JText::_( $this->form->getField( 'title' )->description );
									$label_attrs = 'class="' . $tip_class . $lbl_class . ' required" title="' . flexicontent_html::getToolTip( $field_description, 0, 1 ) . '"';
								} else {
									$label_attrs = 'class="' . $lbl_class . ' required"';
								}
								?>

								<div class="control-group">
									<div class="control-label" id="jform_title-lbl-outer">
										<label id="jform_title-lbl" for="jform_title" <?php echo $label_attrs; ?> >
				<?php echo $field ? $field->label : JText::_( 'FLEXI_TITLE' ); ?>
			</label>
									</div>
									<?php /*echo $this->form->getLabel('title');*/ ?>

									<div class="controls container_fcfield_id_6 container_fcfield_name_title" id="container_fcfield_6">

										<?php if ( $this->params->get('auto_title', 0) ): ?>
										<?php echo $this->item->title . ' <div class="fc-nobgimage fc-info fc-mssg-inline hasTooltip" title="' . JText::_('FLEXI_SET_TO_AUTOMATIC_VALUE_ON_SAVE', true) . '"><span class="icon-info"></span> ' . JText::_('FLEXI_AUTO', true) . '</div>' ; ?>
										<?php	elseif ( isset($this->item->item_translations) ) :?>
										<?php
										array_push( $tabSetStack, $tabSetCnt );
										$tabSetCnt = ++$tabSetMax;
										$tabCnt[ $tabSetCnt ] = 0;
										?>
										<!-- tabber start -->
										<div class="fctabber tabber-inline s-gray tabber-lang" id="fcform_tabset_<?php echo $tabSetCnt; ?>">
											<div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" style="padding: 0px;">
												<h3 class="tabberheading">
													<?php echo '-'.$itemlangname.'-'; // $itemlang; ?> </h3>
												<?php echo $this->form->getInput('title');?>
											</div>
											<?php foreach ($this->item->item_translations as $t): ?>
											<?php if ($itemlang!=$t->shortcode && $t->shortcode!='*') : ?>
											<div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" style="padding: 0px;">
												<h3 class="tabberheading">
													<?php echo $t->name; // $t->shortcode; ?> </h3>
												<?php
												$ff_id = 'jfdata_' . $t->shortcode . '_title';
												$ff_name = 'jfdata[' . $t->shortcode . '][title]';
												?>
												<input class="fc_form_title" style='margin:0px;' type="text" id="<?php echo $ff_id; ?>" name="<?php echo $ff_name; ?>" value="<?php echo @$t->fields->title->value; ?>" size="36" maxlength="254"/>
											</div>
											<?php endif; ?>
											<?php endforeach; ?>
										</div>
										<!-- tabber end -->
										<?php $tabSetCnt = array_pop($tabSetStack); ?>

										<?php else : ?>
										<?php echo $this->form->getInput('title');?>
										<?php endif; ?>

									</div>
								</div>

								<div class="fcclear"></div>
								<?php
								$field_description = JText::_( $this->form->getField( 'alias' )->description );
								$label_attrs = 'class="' . $tip_class . $lbl_class . ' label-fcinner label-toplevel" title="' . flexicontent_html::getToolTip( $field_description, 0, 1 ) . '"';
								?>
								<div class="control-group">
									<div class="control-label" id="jform_alias-lbl-outer">
										<label id="jform_alias-lbl" for="jform_alias" <?php echo $label_attrs; ?> >
				<?php echo JText::_( 'FLEXI_ALIAS' ); ?>
			</label>
									</div>

									<div class="controls container_fcfield_name_alias">
										<?php	if ( isset($this->item->item_translations) ) :?>
										<?php
										array_push( $tabSetStack, $tabSetCnt );
										$tabSetCnt = ++$tabSetMax;
										$tabCnt[ $tabSetCnt ] = 0;
										?>
										<!-- tabber start -->
										<div class="fctabber tabber-inline s-gray tabber-lang" id="fcform_tabset_<?php echo $tabSetCnt; ?>">
											<div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" style="padding: 0px;">
												<h3 class="tabberheading">
													<?php echo '-'.$itemlangname.'-'; // $itemlang; ?> </h3>
												<?php echo $this->form->getInput('alias');?>
											</div>
											<?php foreach ($this->item->item_translations as $t): ?>
											<?php if ($itemlang!=$t->shortcode && $t->shortcode!='*') : ?>
											<div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" style="padding: 0px;">
												<h3 class="tabberheading">
													<?php echo $t->name; // $t->shortcode; ?> </h3>
												<?php
												$ff_id = 'jfdata_' . $t->shortcode . '_alias';
												$ff_name = 'jfdata[' . $t->shortcode . '][alias]';
												?>
												<input class="fc_form_alias" style='margin:0px;' type="text" id="<?php echo $ff_id; ?>" name="<?php echo $ff_name; ?>" value="<?php echo @$t->fields->alias->value; ?>" size="36" maxlength="254"/>
											</div>
											<?php endif; ?>
											<?php endforeach; ?>
										</div>
										<!-- tabber end -->
										<?php $tabSetCnt = array_pop($tabSetStack); ?>

										<?php else : ?>
										<?php echo $this->form->getInput('alias');?>
										<?php endif; ?>
									</div>
								</div>



								<!--DOCUMENT TYPE-->

								<?php
								$field = isset( $this->fields[ 'document_type' ] ) ? $this->fields[ 'document_type' ] : false;
								if ( $field ) {
									$field_description = $field->description ? $field->description :
										JText::_( $this->form->getField( 'type_id' )->description );
									$label_attrs = 'class="' . $tip_class . $lbl_class . ' label-fcinner label-toplevel required' . ( !$this->item->type_id ? ' label label-warning' : '' ) . '" title="' . flexicontent_html::getToolTip( $field_description, 0, 1 ) . '"';
								} else {
									$label_attrs = 'class="' . $lbl_class . ' label-fcinner label-toplevel required"';
								}
								?>


								<div class="control-group">
									<div class="control-label">
										<label id="jform_type_id-lbl" for="jform_type_id" data-for="jform_type_id" <?php echo $label_attrs; ?> > <?php echo $field ? $field->label : JText::_( 'FLEXI_TYPE' ); ?> </label>
									</div>
									<div class="controls container_fcfield_id_8 container_fcfield_name_type" id="container_fcfield_8">
										<?php echo $this->lists['type']; ?>
										<?php //echo $this->form->getInput('type_id'); ?>
										<?php
										$label_tooltip = 'class="' . $tip_class . '" title="' . flexicontent_html::getToolTip( 'FLEXI_NOTES', 'FLEXI_TYPE_CHANGE_WARNING', 1, 1 ) . '"';
										?>
										<i class="icon-info hasTooltip icon-middle" <?php echo $label_tooltip; ?>></i>
									
										<div id="fc-change-warning" class="fc-mssg fc-warning" style="display:none;">
											<?php echo JText::_( 'FLEXI_TAKE_CARE_CHANGING_FIELD_TYPE' ); ?>
										</div>
									</div>
								</div>
								<!--DOCUMENT TYPE-->

								<!--STATE-->
								<?php
								$field = isset( $this->fields[ 'state' ] ) ? $this->fields[ 'state' ] : false;
								if ( $field ) {
									$field_description = $field->description ? $field->description :
										JText::_( $this->form->getField( 'state' )->description );
									$label_attrs = 'class="' . $tip_class . $lbl_class . ' label-fcinner label-toplevel" title="' . flexicontent_html::getToolTip( $field_description, 0, 1 ) . '"';
								} else {
									$label_attrs = 'class="' . $lbl_class . ' label-fcinner label-toplevel"';
								}
								?>
								<div class="fcclear"></div>
								<div class="control-group">
									<div class="control-label">
										<label id="jform_state-lbl" data-for="jform_state" <?php echo $label_attrs; ?> >
				<?php echo $field ? $field->label : JText::_( 'FLEXI_STATE' ); ?>
				<i class="icon-file-check"></i>
			</label>
									</div>
									<?php /*echo $this->form->getLabel('state');*/ ?>
									<?php
									if ( $this->perms[ 'canpublish' ] ): ?>
									<div class="controls container_fcfield_id_10 container_fcfield_name_state" id="container_fcfield_10">
										<?php echo $this->lists['state']; ?>
										<?php //echo $this->form->getInput('state'); ?>
										<span class="<?php echo $tip_class; ?>" style="display:inline-block;" title="<?php echo flexicontent_html::getToolTip( 'FLEXI_NOTES', 'FLEXI_STATE_CHANGE_WARNING', 1, 1);?>">
											<?php echo $infoimage; ?>
										</span>
									</div>
									<?php else :
				echo $this->published;
				echo '<input type="hidden" name="jform[state]" id="jform_vstate" value="'.$this->item->state.'" />';
			endif;?>


									<?php if ( $this->perms['canpublish'] ) { ?>

									<?php if (!$this->params->get('auto_approve', 1)) : ?>
									<div class="fcclear"></div>
									<?php
									//echo "<br/>".$this->form->getLabel('vstate') . $this->form->getInput('vstate');
									$label_attrs = 'class="' . $tip_class . ' label label-success label-fcinner" title="' . flexicontent_html::getToolTip( 'FLEXI_PUBLIC_DOCUMENT_CHANGES', 'FLEXI_PUBLIC_DOCUMENT_CHANGES_DESC', 1, 1 ) . '"';
									?>
									<div class="control-label" id="jform_vstate-lbl-outer">
										<label id="jform_vstate-lbl" data-for="jform_vstate" <?php echo $label_attrs; ?> >
						<?php echo JText::_( 'FLEXI_PUBLIC_DOCUMENT_CHANGES' ); ?>
					</label>
									</div>
									<div class="controls container_fcfield_name_vstate">
										<?php echo $this->lists['vstate']; ?>
									</div>
									<?php else : ?>
									<?php echo '<input type="hidden" name="jform[vstate]" id="jform_vstate" value="2" />'; ?>
									<?php endif; ?>

									<?php } else if (!$this->params->get('auto_approve', 1)) {
				// Enable approval if versioning disabled, this make sense,
				// since if use can edit item THEN item should be updated !!!
				$item_vstate = $this->params->get('use_versioning', 1) ? 1 : 2;
				echo '<input type="hidden" name="jform[vstate]" id="jform_vstate" value="'.$item_vstate.'" />';
			} else {
				echo '<input type="hidden" name="jform[vstate]" id="jform_vstate" value="2" />';
} ?>
								</div>
								<!--/STATE-->


								<!--SET Categories-->
								<h3 class="ruler primary-color"><i class="icon-folder"></i> <span class="mr-10"></span><?php echo JText::_( 'FLEXI_CATEGORIES' ); ?></h3>

								<!--MAIN CATEGORY-->
								<div class="control-group">
									<div class="control-label">
										<label id="jform_catid-lbl" for="jform_catid" data-for="jform_catid" class="<?php echo $tip_class; ?> required" title="<?php echo flexicontent_html::getToolTip( 'FLEXI_NOTES', 'FLEXI_SEC_FEAT_CATEGORIES_NOTES', 1, 1);?>">
											<?php echo JText::_( 'FLEXI_CATEGORY' ); ?> </label>
									</div>
									<div class="controls container_fcfield_name_catid">
										<?php echo $this->lists['catid']; ?> </div>
								</div>
								<!--MAIN CATEGORY-->

								<?php if (1) : ?>


								<div class="control-group">
									<div class="control-label">
										<label id="jform_cid-lbl" for="jform_cid">
											<?php echo JText::_( 'FLEXI_SECONDARY_CATEGORIES' ); ?> </label>
									</div>
									<div class="controls container_fcfield_name_catid">
										<?php echo $this->lists['cid']; ?> </div>
								</div>
								<?php endif; ?>

								<?php if ( !empty($this->lists['featured_cid']) ) : ?>
								<div class="control-group">
									<div class="control-label">
										<label id="jform_featured_cid-lbl" for="jform_featured_cid">
											<?php echo JText::_( 'FLEXI_FEATURED_CATEGORIES' ); ?> </label>
									</div>
									<div class="controls container_fcfield_name_featured_cid">
										<?php echo $this->lists['featured_cid']; ?> </div>
								</div>
								<?php endif; ?>
								<div class="control-group h_feat">
									<div class="control-label">
										<label>
											<?php echo JText::_( 'FLEXI_FEATURED' ); ?> <br>
											<small style="float:right; clear:both;">
												<?php echo JText::_( 'FLEXI_JOOMLA_FEATURED_VIEW' ); ?>
											</small>
										</label>
									</div>
									<div class="controls container_fcfield_name_featured">
										<?php echo $this->lists['featured']; ?>
									</div>
								</div>

								<!--SET LANGUAGE-->
								<div class="h_lang">
									<h3 class="ruler primary-color"><i class="icon-comments-2"></i> <span class="mr-10"></span><?php echo JText::_( 'FLEXI_LANGUAGE' ); ?></h3>

									<div class="control-group">
										<div class="control-label" id="jform_language-lbl-outer">
											<label id="jform_language-lbl" for="jform_language" class="<?php echo $lbl_class; ?> label-fcinner label-toplevel">
												<?php echo JText::_( 'FLEXI_LANGUAGE' ); ?>
												<i class="icon-comments-2"></i>
											</label>
										</div>
										<?php /*echo $this->form->getLabel('language');*/ ?>

										<div class="controls container_fcfield_name_language">
											<?php echo $this->lists['languages']; ?>
										</div>
									</div>
									
									
											<fieldset class="basicfields_set" id="fcform_language_container">
			<legend>
				<span class="fc_legend_header_text"><?php echo JText::_('FLEXI_LANGUAGE') .' '. JText::_('FLEXI_ASSOCIATIONS'); ?></span>
			</legend>
			
			<!-- BOF of language / language associations section -->
			<?php if ( flexicontent_db::useAssociations() ) : ?>

				<div class="fcclear"></div>

				<?php if ($this->item->language!='*'): ?>
					<?php echo $this->loadTemplate('associations'); ?>
				<?php else: ?>
					<?php echo JText::_( 'FLEXI_ASSOC_NOT_POSSIBLE' ); ?>
				<?php endif; ?>

			<?php endif; ?>
			<!-- EOF of language / language associations section -->
			
		</fieldset>
									
								</div>
								<!--/SET LANGUAGE-->




						
								<?php if ($this->subscribers) : ?>
						
								<h3 class="ruler primary-color"><i class="icon-envelope"></i> <span class="mr-10"></span><?php echo JText::_( 'FLEXI_NOTIFY_SUBSCRIBERS' ); ?></h3>
								<div class="fcclear"></div>
								<div class="control-group">
									<div class="control-label">&nbsp;</div>

									<?php
									$label_attrs = 'class="' . $tip_class . ' label label-info label-fcinner" title="' . flexicontent_html::getToolTip( 'FLEXI_NOTIFY_FAVOURING_USERS', 'FLEXI_NOTIFY_NOTES', 1, 1 ) . '"';
									?>
									<div class="controls" id="jform_notify-msg-outer">
										<label id="jform_notify-msg" <?php echo $label_attrs; ?> >
					<?php echo JText::_( 'FLEXI_NOTIFY_SUBSCRIBERS' ); ?>
				</label>
										<div class="container_fcfield container_fcfield_name_notify">
											<?php echo $this->lists['notify']; ?>
										</div>
									</div>
								</div>
				
								<?php endif; ?>
								
							</div><!--/.span9  full_width_980-->
							<div class="span4 full_width_980 off-white">
							<!--RIGHT COLUMN-->
							<?php echo JHtml::_('bootstrap.startAccordion', 'right-accordion-1', array('active' => 'slide1_id', 'parent' => 'right-accordion-1')); ?>
								
								<!--1. Publishing  -->
								<?php echo JHtml::_('bootstrap.addSlide', 'right-accordion-1', '<i class="icon-calendar mr-5"></i>'.JText::_('FLEXI_PUBLISHING'), 'slide1_id', 'accordion-toggle'); ?>
									
									<div class="pad16 form-vertical">								
										
				

										
											<div class="control-group">
											<div class="control-label" id="created_by-lbl-outer">
												<?php echo str_replace('class="', 'class="' . $lbl_class . ' label-fcinner ', $this->form->getLabel('created_by')); ?>
											</div>
											<div class="controls container_fcfield">
												<?php echo /*$this->perms['editcreator']*/ $this->form->getInput('created_by'); ?>
											</div>
										</div>

										<div class="control-group">
											<div class="control-label" id="created-lbl-outer">
												<?php echo str_replace('class="', 'class="' . $lbl_class . ' label-fcinner ', $this->form->getLabel('created')); ?>
											</div>
											<div class="controls container_fcfield">
												<?php echo /*$this->perms['editcreationdate']*/ $this->form->getInput('created'); ?>
											</div>
										</div>

										<div class="control-group">
											<div class="control-label" id="created_by_alias-lbl-outer">
												<?php echo str_replace('class="', 'class="' . $lbl_class . ' label-fcinner ', $this->form->getLabel('created_by_alias')); ?>
											</div>
											<div class="controls container_fcfield">
												<?php echo /*$this->perms['canpublish']*/ $this->form->getInput('created_by_alias'); ?>
											</div>
										</div>

										<div class="control-group">
											<div class="control-label" id="publish_up-lbl-outer">
												<?php echo str_replace('class="', 'class="' . $lbl_class . ' label-fcinner ', $this->form->getLabel('publish_up')); ?>
											</div>
											<div class="controls container_fcfield">
												<?php echo /*$this->perms['canpublish']*/ $this->form->getInput('publish_up'); ?>
											</div>
										</div>

										<div class="control-group">
											<div class="control-label" id="publish_down-lbl-outer">
												<?php echo str_replace('class="', 'class="' . $lbl_class . ' label-fcinner ', $this->form->getLabel('publish_down')); ?>
											</div>
											<div class="controls container_fcfield">
												<?php echo /*$this->perms['canpublish']*/ $this->form->getInput('publish_down'); ?>
											</div>
										</div>

										<div class="control-group">
											<div class="control-label" id="access-lbl-outer">
												<?php echo str_replace('class="', 'class="' . $lbl_class . ' label-fcinner ', $this->form->getLabel('access')); ?>
											</div>
											<div class="controls container_fcfield">
												<?php echo /*$this->perms['canacclvl']*/ $this->form->getInput('access'); ?>
											</div>
										</div>
										
							<div class="alert alert-info fc-iblock small">
											<?php
											// Dates displayed in the item form, are in user timezone for J2.5, and in site's default timezone for J1.5
											$site_zone = JFactory::getApplication()->getCfg( 'offset' );
											$user_zone = JFactory::getUser()->getParam( 'timezone', $site_zone );

											$tz = new DateTimeZone( $user_zone );
											$tz_offset = $tz->getOffset( new JDate() ) / 3600;
											$tz_info = $tz_offset > 0 ? ' UTC +' . $tz_offset : ' UTC ' . $tz_offset;

											$tz_info .= ' (' . $user_zone . ')';
											echo JText::sprintf( FLEXI_J16GE ? 'FLEXI_DATES_IN_USER_TIMEZONE_NOTE' : 'FLEXI_DATES_IN_SITE_TIMEZONE_NOTE', ' ', $tz_info );
											?>
										</div>
										
										</div>
								<?php echo JHtml::_('bootstrap.endSlide'); ?>
								<!--/1. Publishing  -->
								
								<!--2. VERSIONS-->
								<?php echo JHtml::_('bootstrap.addSlide', 'right-accordion-1', '<i class="icon-stack mr-5"></i>'.JText::_('FLEXI_VERSIONS'), 'slide2_id', 'accordion-toggle'); ?>
							<div class="pad16">
						
					<?php
										// used to hide "Reset Hits" when hits = 0
										$visibility = !$this->item->hits ? 'style="display: none; visibility: hidden;"' : '';
										$visibility2 = !$this->item->rating_count ? 'style="display: none; visibility: hidden;"' : '';
										$default_label_attrs = 'class="fc-prop-lbl"';
										?>
										
										<?php if ($this->params->get('use_versioning', 1)) : ?>
										<?php if ( $this->perms['canversion'] ) : ?>

											<h3 class="primary-color text-center no-margin-top">
													<?php echo JText::_( 'FLEXI_VERSIONS_HISTORY' ); ?>
													</h3>
										<table class="off-white w100">
																			<tr>
												<td>
													<table id="version_tbl" class="fc-table-list fc-tbl-short">
														<?php if ($this->item->id == 0) : ?>
														<tr>
															<td class="versions-first">
																<?php echo JText::_( 'FLEXI_NEW_ARTICLE' ); ?>
															</td>
														</tr>
														<?php
														else :
															$date_format = ( ( $date_format = JText::_( 'FLEXI_DATE_FORMAT_FLEXI_VERSIONS_J16GE' ) ) == 'FLEXI_DATE_FORMAT_FLEXI_VERSIONS_J16GE' ) ? "d/M H:i" : $date_format;
														foreach ( $this->versions as $version ):
															$class = ( $version->nr == $this->item->version ) ? ' id="active-version" class="success"' : '';
														if ( ( int )$version->nr > 0 ):
															?>
														<tr<?php echo $class; ?>>
															<td class="versions">
																<span style="padding: 0 5px 0 0;">
																	<?php echo '#' . $version->nr; ?>
																</span>
															</td>
															<td class="versions">
																<span style="padding: 0 5px 0 0;">
																	<?php echo JHtml::_('date', (($version->nr == 1) ? $this->item->created : $version->date), $date_format ); ?>
																</span>
															</td>
															<td class="versions">
																<span style="padding: 0 5px 0 0;">
																	<?php echo ($version->nr == 1) ? flexicontent_html::striptagsandcut($this->item->creator, 25) : flexicontent_html::striptagsandcut($version->modifier, 25); ?>
																</span>
															</td>
															<td class="versions">
																<a href="javascript:;" class="hasTooltip" title="<?php echo JHtml::tooltipText( JText::_( 'FLEXI_COMMENT' ), ($version->comment ? $version->comment : 'No comment written'), 0, 1); ?>">
																	<?php echo $commentimage;?>
																</a>
																<?php
																if ( ( int )$version->nr == ( int )$this->item->current_version ) {
																	?>
																<a onclick="javascript:return clickRestore('<?php echo JUri::base(true); ?>/index.php?option=com_flexicontent&amp;view=item&amp;<?php echo $task_items;?>edit&amp;cid=<?php echo $this->item->id;?>&amp;version=<?php echo $version->nr; ?>');" href="javascript:;">
																	<?php echo JText::_( 'FLEXI_CURRENT' ); ?>
																</a>
																<?php }else{
					?>
																<a class="modal-versions" href="index.php?option=com_flexicontent&amp;view=itemcompare&amp;cid=<?php echo $this->item->id; ?>&amp;version=<?php echo $version->nr; ?>&amp;tmpl=component" title="<?php echo JText::_( 'FLEXI_COMPARE_WITH_CURRENT_VERSION' ); ?>">
																	<?php echo $viewimage; ?>
																</a>
																<a onclick="javascript:return clickRestore('<?php echo JUri::base(true); ?>/index.php?option=com_flexicontent&amp;task=items.edit&amp;cid=<?php echo $this->item->id; ?>&amp;version=<?php echo $version->nr; ?>&amp;<?php echo JSession::getFormToken();?>=1');" href="javascript:;" title="<?php echo JText::sprintf( 'FLEXI_REVERT_TO_THIS_VERSION', $version->nr ); ?>">
																	<?php echo $revertimage; ?>
																</a>
																<?php }?>
															</td>
											</tr>
											<?php
											endif;
											endforeach;
											endif;
											?>
											</table>
											</td>
											</tr>
											<tr style="background:unset;"  class="no-border-bottom">
												<td style="background:unset;" class="no-border-bottom pb-10">
													<div id="fc_pager" class="pt-10"></div>
												</td>
											</tr>
										</table>
				
										<?php endif; ?>
										<?php endif; ?>	
										
<h3 class="primary-color text-center mt-20">
														<?php echo JText::_( 'FLEXI_VERSION_INFO' ); ?>
													</h3>
						<table class="fc-form-tbl off-white w100" id="fc_versions">
									
											<?php
											if ( $this->item->id ) {
												?>
											<tr>
												<td class="key">
													<label class="fc-prop-lbl">
														<?php echo JText::_( 'FLEXI_ITEM_ID' ); ?>
													</label>
												</td>
												<td>
													<?php echo $this->item->id; ?>
												</td>
											</tr>
											<?php
											}
											?>
											<tr>
												<td class="key">
													<?php
													$field = isset( $this->fields[ 'state' ] ) ? $this->fields[ 'state' ] : false;
													if ( $field ) {
														$label_attrs = 'class="' . $tip_class . ' fc-prop-lbl" title="' . flexicontent_html::getToolTip( $field->description, 0, 1 ) . '"';
													} else {
														$label_attrs = $default_label_attrs;
													}
													?>
													<label <?php echo $label_attrs; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_STATE' ); ?></label>
												</td>
												<td>
													<?php echo $this->published;?>
												</td>
											</tr>
											<tr>
												<td class="key">
													<?php
													$field = isset( $this->fields[ 'hits' ] ) ? $this->fields[ 'hits' ] : false;
													if ( $field ) {
														$label_attrs = 'class="' . $tip_class . ' fc-prop-lbl" title="' . flexicontent_html::getToolTip( $field->description, 0, 1 ) . '"';
													} else {
														$label_attrs = $default_label_attrs;
													}
													?>
													<label <?php echo $label_attrs; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_HITS' ); ?></label>
												</td>
												<td>
													<div id="hits" style="display:inline-block;">
														<?php echo $this->item->hits; ?>
													</div> &nbsp;
													<span <?php echo $visibility; ?>>
					<input name="reset_hits" type="button" class="button btn-small btn-warning" value="<?php echo JText::_( 'FLEXI_RESET' ); ?>" onclick="reseter('<?php echo $ctrl_items; ?>resethits', '<?php echo $this->item->id; ?>', 'hits')" />
				</span>
												</td>
											</tr>
											<tr>
												<td class="key">
													<?php
													$field = isset( $this->fields[ 'voting' ] ) ? $this->fields[ 'voting' ] : false;
													if ( $field ) {
														$label_attrs = 'class="' . $tip_class . ' fc-prop-lbl" title="' . flexicontent_html::getToolTip( $field->description, 0, 1 ) . '"';
													} else {
														$label_attrs = $default_label_attrs;
													}
													?>
													<label <?php echo $label_attrs; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_SCORE' ); ?></label>
												</td>
												<td>
													<div id="votes" style="float:left;">
														<?php echo $this->ratings; ?>
													</div> &nbsp;
													<span <?php echo $visibility2; ?>>
					<input name="reset_votes" type="button" class="button btn-small btn-warning" value="<?php echo JText::_( 'FLEXI_RESET' ); ?>" onclick="reseter('<?php echo $ctrl_items; ?>resetvotes', '<?php echo $this->item->id; ?>', 'votes')" />
				</span>
												</td>
											</tr>

											<tr>
												<td class="key">
													<label <?php echo $default_label_attrs; ?>><?php echo JText::_( 'FLEXI_REVISED' ); ?></label>
												</td>
												<td>
													<?php echo $this->item->last_version;?>
													<?php echo JText::_( 'FLEXI_TIMES' ); ?>
												</td>
											</tr>
											<tr>
												<td class="key">
													<label <?php echo $default_label_attrs; ?>><?php echo JText::_( 'FLEXI_FRONTEND_ACTIVE_VERSION' ); ?></label>
												</td>
												<td>
													#
													<?php echo $this->item->current_version;?>
												</td>
											</tr>
											<tr>
												<td class="key">
													<label <?php echo $default_label_attrs; ?>><?php echo JText::_( 'FLEXI_FORM_LOADED_VERSION' ); ?></label>
												</td>
												<td>
													#
													<?php echo $this->item->version;?>
												</td>
											</tr>
											<tr>
												<td class="key">
													<?php
													$field = isset( $this->fields[ 'created' ] ) ? $this->fields[ 'created' ] : false;
													if ( $field ) {
														$label_attrs = 'class="' . $tip_class . ' fc-prop-lbl" title="' . flexicontent_html::getToolTip( $field->description, 0, 1 ) . '"';
													} else {
														$label_attrs = $default_label_attrs;
													}
													?>
													<label <?php echo $label_attrs; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_CREATED' ); ?></label>
												</td>
												<td>
													<?php
													if ( $this->item->created == $this->nullDate ) {
														echo JText::_( 'FLEXI_NEW_ITEM' );
													} else {
														echo JHtml::_( 'date', $this->item->created, JText::_( 'DATE_FORMAT_LC2' ) );
													}
													?>
												</td>
											</tr>
											<tr>
												<td class="key">
													<?php
													$field = isset( $this->fields[ 'modified' ] ) ? $this->fields[ 'modified' ] : false;
													if ( $field ) {
														$label_attrs = 'class="' . $tip_class . ' fc-prop-lbl" title="' . flexicontent_html::getToolTip( $field->description, 0, 1 ) . '"';
													} else {
														$label_attrs = $default_label_attrs;
													}
													?>
													<label <?php echo $label_attrs; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_MODIFIED' ); ?></label>
												</td>
												<td>
													<?php
													if ( $this->item->modified == $this->nullDate ) {
														echo JText::_( 'FLEXI_NOT_MODIFIED' );
													} else {
														echo JHtml::_( 'date', $this->item->modified, JText::_( 'DATE_FORMAT_LC2' ) );
													}
													?>
												</td>
											</tr>
											<?php if ($this->params->get('use_versioning', 1)) : ?>
											<tr>
												<td class="key">
													<label <?php echo $default_label_attrs; ?>><?php echo JText::_( 'FLEXI_VERSION_COMMENT' ); ?></label>
												</td>
												<td></td>
											</tr>
											<tr>
												<td colspan="2" class="text-center">
													<textarea name="jform[versioncomment]" id="versioncomment" rows="4"></textarea>
												</td>
											</tr>
											<?php endif; ?>
										</table>
	
							
								
										
								</div>
								<?php echo JHtml::_('bootstrap.endSlide'); ?>
								
								<!--/2. VERSIONS-->
								<?php echo JHtml::_('bootstrap.endAccordion'); ?>
							<!--/RIGHT COLUMN-->
							</div><!--/.span3 full_width_980-->
						</div>
						<!--/FLEXI_BASIC CONTENT -->
						<?php echo JHtml::_('bootstrap.endTab');?>
						<!--/FLEXI_BASIC TAB1 -->

		

   <!--IF TAB2 -->
        
<!--
=======================
+ FLEXI_CONTENT 
=======================
--> 
       

     
       <!--BOOTSTRAP 1-->  
 
       
     
               
 <?php
//$type_lbl = $this->item->type_id ? JText::_( 'FLEXI_ITEM_TYPE' ) . ' : ' . $this->typesselected->name : JText::_( 'FLEXI_TYPE_NOT_DEFINED' );
if ($this->item->type_id) {
	$_str = JText::_('FLEXI_DETAILS');
	$_str = StringHelper::strtoupper(StringHelper::substr($_str, 0, 1)) . StringHelper::substr($_str, 1);
	
	$type_lbl = $this->typesselected->name;
	$type_lbl = $type_lbl ? JText::_($type_lbl) : JText::_('FLEXI_CONTENT_TYPE');
	$type_lbl = $type_lbl .' ('. $_str .')';
} else {
	$type_lbl = '<i class="icon-pending warning"></i><br class="hidden-phone">'.JText::_('FLEXI_TYPE_NOT_DEFINED');
}
?>
<?php if ($this->fields && $this->item->type_id) : ?>
	
	<!-- Field manager tab -->
<?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', '<i class="icon-pencil"></i><br class="hidden-phone">Content') ?>
		
		<?php
			$this->document->addScriptDeclaration("
				jQuery(document).ready(function() {
					jQuery('#jform_type_id').change(function() {
						if (jQuery('#jform_type_id').val() != '".$this->item->type_id."')
							jQuery('#fc-change-warning').css('display', 'block');
						else
							jQuery('#fc-change-warning').css('display', 'none');
					});
				});
			");
		?>
		
		
			
			<?php
			$hide_ifempty_fields = array('fcloadmodule', 'fcpagenav', 'toolbar', 'comments');
			$row_k = 0;

			foreach ($this->fields as $field)
			{
				if (
					// SKIP backend hidden fields from this listing
					($field->iscore && $field->field_type!='maintext')   ||   $field->parameters->get('backend_hidden')  ||   in_array($field->formhidden, array(2,3))   ||
					
					// Skip hide-if-empty fields from this listing
					( empty($field->html) && ($field->formhidden==4 || in_array($field->field_type, $hide_ifempty_fields)) )
				) continue;
				
				// check to SKIP (hide) field e.g. description field ('maintext'), alias field etc
				if ( $this->tparams->get('hide_'.$field->field_type) ) continue;
				
				$not_in_tabs = "";
				if ($field->field_type=='groupmarker')
				{
					echo $field->html;
					continue;
				}


				else if ($field->field_type=='coreprops')
				{
					// not used in backend (yet?)
					continue;
				}


				else if ($field->field_type=='maintext')
				{
					// WE NEED THIS IN CONTENT
					echo $field->html;
					continue;
				}


				else if ($field->field_type=='image')
				{
					if ($field->parameters->get('image_source')==-1)
					{
						$replace_txt = !empty($FC_jfields_html['images']) ? $FC_jfields_html['images'] : '<span class="alert alert-warning fc-small fc-iblock">'.JText::_('FLEXI_ENABLE_INTRO_FULL_IMAGES_IN_TYPE_CONFIGURATION').'</span>';
						unset($FC_jfields_html['images']);
						$field->html = str_replace('_INTRO_FULL_IMAGES_HTML_', $replace_txt, $field->html);
					}
				}


				else if ($field->field_type=='weblink')
				{
					if ($field->parameters->get('link_source')==-1)
					{
						$replace_txt = !empty($FC_jfields_html['urls']) ? $FC_jfields_html['urls'] : '<span class="alert alert-warning">'.JText::_('FLEXI_ENABLE_LINKS_IN_TYPE_CONFIGURATION').'</span>';
						unset($FC_jfields_html['urls']);
						$field->html = str_replace('_JOOMLA_ARTICLE_LINKS_HTML_', $replace_txt, $field->html);
					}
				}


				// field has tooltip
				$edithelp = $field->edithelp ? $field->edithelp : 1;
				if ( $field->description && ($edithelp==1 || $edithelp==2) )
				{
					$label_attrs = 'class="' . $tip_class . ($edithelp==2 ? ' fc_tooltip_icon' : '') . $lbl_class . ' label-fcinner label-toplevel" title="'.flexicontent_html::getToolTip(null, $field->description, 0, 1).'"';
				}
				else
				{
					$label_attrs = 'class="' . $lbl_class . ' label-fcinner label-toplevel"';
				}
				
				// Some fields may force a container width ?
				$display_label_form = $field->parameters->get('display_label_form', 1);
				$row_k = 1 - $row_k;
				$full_width = $display_label_form==0 || $display_label_form==2 || $display_label_form==-1;
				$width = $field->parameters->get('container_width', ($full_width ? '100%!important;' : false) );
				$container_width = empty($width) ? '' : 'width:' .$width. ($width != (int)$width ? 'px!important;' : '');
				$container_class = "fcfield_row".$row_k." controls container_fcfield_id_".$field->id." container_fcfield_name_".$field->name;
				?>
				
				<div class="control-group <?php if($display_label_form==2):  ?>lbreak<?php endif; ?>"  id="<?php echo 'id-'.$field->id;?>">
				<div class="control-label" id="label_outer_fcfield_<?php echo $field->id; ?>" style="<?php echo $display_label_form < 1 ? 'display:none;' : '' ?>">
				<label id="label_fcfield_<?php echo $field->id; ?>" data-for="<?php echo 'custom_'.$field->name;?>" <?php echo $label_attrs;?> >
					<?php echo $field->label; ?>
				</label>
				</div>
				<?php if($display_label_form==2):  ?>
					<div class="fcclear"></div>
				<?php endif; ?>
								
				<div style="<?php echo $container_width; ?>" class="<?php echo $container_class;?>" id="container_fcfield_<?php echo $field->id;?>">
					<?php echo ($field->description && $edithelp==3) ? '<div class="alert fc-small fc-iblock">'.$field->description.'</div>' : ''; ?>
				
				<?php if ( !isset($field->html) || !is_array($field->html) ) : /* CASE 2: NORMAL FIELD non-tabbed */ ?>
					
					<?php echo isset($field->html) ? $field->html : $noplugin; ?>
					
				<?php else : /* MULTI-TABBED FIELD e.g textarea, description */ ?>
					
					<?php
					array_push($tabSetStack, $tabSetCnt);
					$tabSetCnt = ++$tabSetMax;
					$tabCnt[$tabSetCnt] = 0;
					?>
					<!-- tabber start -->
					<div class="fctabber" id="fcform_tabset_<?php echo $tabSetCnt; ?>">
					<?php foreach ($field->html as $i => $fldhtml): ?>
						<?php
							// Hide field when it has no label, and skip creating tab
							$not_in_tabs .= !isset($field->tab_labels[$i]) ? "<div style='display:none!important'>".$field->html[$i]."</div>" : "";
							if (!isset($field->tab_labels[$i]))	continue;
						?>
						
						<div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" style="padding: 0px;">
							<h3 class="tabberheading"> <?php echo $field->tab_labels[$i]; // Current TAB LABEL ?> </h3>
							<?php
								echo $not_in_tabs;      // Output hidden fields (no tab created), by placing them inside the next appearing tab
								$not_in_tabs = "";      // Clear the hidden fields variable
								echo $field->html[$i];  // Current TAB CONTENTS
							?>
						</div>
								
					<?php endforeach; ?>
					</div>
					<!-- tabber end -->
					<?php $tabSetCnt = array_pop($tabSetStack); ?>
					
					<?php echo $not_in_tabs;      // Output ENDING hidden fields, by placing them outside the tabbing area ?>
							
				<?php endif; /* END MULTI-TABBED FIELD */ ?>
				
				</div></div>
				
						
						
			<?php
			}
			?>
			
	<div class="clear"></div>

<?php echo JHtml::_('bootstrap.endTab');?> 
	
<?php else : /* NO TYPE SELECTED */ ?>

<?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', $type_lbl) ?>
		
		<div class="fc_tabset_inner">
			<?php if ($this->item->type_id == 0) : ?>
				<input name="jform[type_id_not_set]" value="1" type="hidden" />
				<div class="fc-mssg-inline fc-info"><?php echo JText::_( 'FLEXI_CHOOSE_ITEM_TYPE' ); ?></div>
			<?php else : ?>
				<div class="fc-mssg-inline fc-info"><?php echo JText::_( 'FLEXI_NO_FIELDS_TO_TYPE' ); ?></div>
			<?php	endif; ?>
		</div>
		
<?php echo JHtml::_('bootstrap.endTab');?> 
	
<?php endif; ?>


						

<?php
	//echo "<pre>"; print_r(array_keys($this->form->getFieldsets('attribs'))); echo "</pre>";
	//echo "<pre>"; print_r(array_keys($this->form->getFieldsets())); echo "</pre>";

	$fieldSets = $this->form->getFieldsets();
	foreach ($fieldSets as $name => $fieldSet) :
		if (substr($name, 0, 7) != 'fields-') continue;

		$label = !empty($fieldSet->label) ? $fieldSet->label : 'COM_FLEXICONTENT_'.$name.'_FIELDSET_LABEL';
		if ( JText::_($label)=='COM_FLEXICONTENT_'.$name.'_FIELDSET_LABEL' ) $label = 'COM_CONTENT_'.$name.'_FIELDSET_LABEL';

		$icon_class = 'icon-pencil';
		//echo JHtml::_('sliders.panel', JText::_($label), $name.'-options');
		//echo "<h2>".$label. "</h2> " . "<h3>".$name. "</h3> ";
	?>
	<!-- CUSTOM parameters TABs -->
<?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', JText::_($label)) ?>

		
						<h1>adsdassd</h1>

			<?php foreach ($this->form->getFieldset($name) as $field) : ?>
				
				<?php if ($field->hidden): ?>
					<span style="display:none !important;">
						<?php echo $field->input; ?>
					</span>
				<?php else: ?>
					<fieldset class="panelform">
						<?php echo ($field->label ? '
							<span class="label-fcouter" id="jform_attribs_'.$field->fieldname.'-lbl-outer">'.str_replace('class="', 'class="' . $lbl_class . ' label-fcinner ', str_replace(' for="', ' data-for="', $field->label)).'</span>
							<div class="container_fcfield">'.$field->input.'</div>
						' : $field->input); ?>
					</fieldset>
				<?php endif; ?>
				
			<?php endforeach; ?>

		             <br class="clear">
<?php echo JHtml::_('bootstrap.endTab');?> 
	
	<?php endforeach; ?> 
     

   
        
 
	<!-- META/SEO tab -->
<?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', '<i class="icon-bookmark"></i><br class="hidden-phone">'.JText::_('FLEXI_META_SEO').''); ?>
		

<h3 class="ruler"><?php echo JText::_( 'FLEXI_META' ); ?></h3>
		
		<div class="control-group">
			<div class="control-label" id="metadesc-lbl-outer">
				<?php echo str_replace('class="', 'class="' . $lbl_class . ' label-fcinner ', $this->form->getLabel('metadesc')); ?>
			</div>

			<div class="controls">
				<?php	if ( isset($this->item->item_translations) ) : ?>
					<?php
					array_push($tabSetStack, $tabSetCnt);
					$tabSetCnt = ++$tabSetMax;
					$tabCnt[$tabSetCnt] = 0;
					?>
					<!-- tabber start -->
					<div class="fctabber tabber-inline s-gray tabber-lang" id="fcform_tabset_<?php echo $tabSetCnt; ?>">
						<div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" style="padding: 0px;">
							<h3 class="tabberheading"> <?php echo '-'.$itemlangname.'-'; // $itemlang; ?> </h3>
							<?php echo $this->form->getInput('metadesc'); ?>
						</div>
						<?php foreach ($this->item->item_translations as $t): ?>
							<?php if ($itemlang!=$t->shortcode && $t->shortcode!='*') : ?>
								<div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" style="padding: 0px;">
									<h3 class="tabberheading"> <?php echo $t->name; // $t->shortcode; ?> </h3>
									<?php
									$ff_id = 'jfdata_'.$t->shortcode.'_metadesc';
									$ff_name = 'jfdata['.$t->shortcode.'][metadesc]';
									?>
									<textarea id="<?php echo $ff_id; ?>" class="fcfield_textareaval" rows="3" cols="46" name="<?php echo $ff_name; ?>"><?php echo @$t->fields->metadesc->value; ?></textarea>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<!-- tabber end -->
					<?php $tabSetCnt = array_pop($tabSetStack); ?>
					
				<?php else : ?>
					<?php echo $this->form->getInput('metadesc'); ?>
				<?php endif; ?>
				
			</div>
		</div>

		<div class="control-group" id="<?php echo 'h-'.$field->id;?>">
			<div class="control-label" id="metakey-lbl-outer"><?php echo str_replace('class="', 'class="' . $lbl_class . ' label-fcinner ', $this->form->getLabel('metakey')); ?></div>
			
			<div class="controls">
				<?php	if ( isset($this->item->item_translations) ) :?>
					<?php
					array_push($tabSetStack, $tabSetCnt);
					$tabSetCnt = ++$tabSetMax;
					$tabCnt[$tabSetCnt] = 0;
					?>
					<!-- tabber start -->
					<div class="fctabber tabber-inline s-gray tabber-lang" id="fcform_tabset_<?php echo $tabSetCnt; ?>">
						<div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" style="padding: 0px;">
							<h3 class="tabberheading"> <?php echo '-'.$itemlangname.'-'; // $itemlang; ?> </h3>
							<?php echo $this->form->getInput('metakey'); ?>
						</div>
						<?php foreach ($this->item->item_translations as $t): ?>
							<?php if ($itemlang!=$t->shortcode && $t->shortcode!='*') : ?>
								<div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" style="padding: 0px;">
									<h3 class="tabberheading"> <?php echo $t->name; // $t->shortcode; ?> </h3>
									<?php
									$ff_id = 'jfdata_'.$t->shortcode.'_metakey';
									$ff_name = 'jfdata['.$t->shortcode.'][metakey]';
									?>
									<textarea id="<?php echo $ff_id; ?>" class="inputbox" rows="3" cols="46" name="<?php echo $ff_name; ?>"><?php echo @$t->fields->metakey->value; ?></textarea>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<!-- tabber end -->
					<?php $tabSetCnt = array_pop($tabSetStack); ?>
					
				<?php else : ?>
					<?php echo $this->form->getInput('metakey'); ?>
				<?php endif; ?>
				
				</div>
		</div>
		
	
		<?php foreach($this->form->getGroup('metadata') as $field): ?>
				

		
			<?php if ($field->hidden): ?>
				<span style="display:none !important;">
					<?php echo $field->input; ?>
				</span>
			<?php else: ?>
			<div class="control-group" id="<?php echo 'h-'.$field->id;?>">
		

		
		<?php echo ($field->label ? '<div class="control-label">'.str_replace('class="', 'class="' . $lbl_class . ' label-fcinner ', str_replace(' for="', ' data-for="', $field->label)).'</div>
		<div class="controls">'.$field->input.'</div>' : $field->input); ?>
					
	
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
		
		<h2 class="ruler"><?php echo JText::_( 'FLEXI_SEO' ); ?></h2>


		
		<?php foreach ($this->form->getFieldset('params-seoconf') as $field) : ?>
			<?php if ($field->hidden): ?>
				<span style="display:none !important;">
					<?php echo $field->input; ?>
				</span>
			<?php else: ?>			


				
				<div class="control-group" id="<?php echo 'h-'.$field->id;?>">
			<?php echo ($field->label ? '<div class="control-label">'.str_replace('class="', 'class="' . $lbl_class . ' label-fcinner ', str_replace(' for="', ' data-for="', $field->label)).'</div>
		<div class="controls">'.$field->input.'</div>' : $field->input); ?>
				</div>
				
			<?php endif; ?>
		<?php endforeach; ?>
		
	<?php echo JHtml::_('bootstrap.endTab');?>  <!-- end SEO tab -->
        <!--/FLEXI_CONTENT TAB3 -->
        
        

        
                <?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', '<i class="icon-palette"></i><br class="hidden-phone">Display'); ?>
        

       	<h3 class="ruler"><i class="icon-eye-open"></i> <span class="m10r"></span><?php echo JText::_('FLEXI_DISPLAYING'); ?></h3>
		<?php
			$fieldSets = $this->form->getFieldsets('attribs');
			foreach ($fieldSets as $name => $fieldSet) :
			
				if (substr($name, 0, 7) != 'params-' || $name == 'params-seoconf') continue;
				
				$label = !empty($fieldSet->label) ? $fieldSet->label : 'FLEXI_'.$name.'_FIELDSET_LABEL';
				//echo JHtml::_('sliders.panel', JText::_($label), $name.'-options');
				//echo "<h2>".$label. "</h2> " . "<h3>".$name. "</h3> ";
				?>
				<?php foreach ($this->form->getFieldset($name) as $field) : ?>
					
					<?php if ($field->hidden): ?>
						<span style="display:none !important;">
							<?php echo $field->input; ?>
						</span>
					<?php else: ?>
					
					<div class="control-group" id="<?php echo 'h-'.$field->id;?>">
					<?php echo ($field->label ? '<div class="control-label">'.str_replace('class="', 'class="' . $lbl_class . ' label-fcinner ', str_replace(' for="', ' data-for="', $field->label)).'</div>
		<div class="controls">'.$field->input.'</div>' : $field->input); ?>	
				</div>
					<?php endif; ?>
				
				<?php endforeach; ?>
				
		<?php endforeach; ?>
       
       <!--TEMPLATE CHOOSER-->
       <h3 class="ruler"><i class="icon-palette"></i> <span class="m10r"></span><?php echo JText::_('FLEXI_LAYOUT'); ?></h3>
       	

			<span class="btn-group input-append" style="margin: 2px 0px 6px;">
				<span id="fc-layouts-help_btn" class="btn" onclick="fc_toggle_box_via_btn('fc-layouts-help', this, 'btn-primary');" ><span class="icon-help"></span><?php echo JText::_('JHELP'); ?></span>
			</span>
			<div class="fcclear"></div>

			<div class="fc-info fc-nobgimage fc-mssg-inline" id="fc-layouts-help" style="margin: 2px 0px!important; font-size: 12px; display: none;">
				<h3 class="themes-title">
					<?php echo JText::_( 'FLEXI_PARAMETERS_LAYOUT_EXPLANATION' ); ?>
				</h3>
				<b>NOTE:</b> Common method for -displaying- fields is by <b>editing the template layout</b> in template manager and placing the fields into <b>template positions</b>
			</div>
			<div class="fcclear"></div>
			
			<?php
			foreach ($this->form->getFieldset('themes') as $field):
				if (!$field->label || $field->hidden)
				{
					echo $field->input;
					continue;
				}
				elseif ($field->input)
				{
					$_depends = $field->getAttribute('depend_class');
					echo '
					<fieldset class="panelform'.($_depends ? ' '.$_depends : '').'" id="'.$field->id.'-container">
						<span class="label-fcouter">
							'.str_replace('class="', 'class="' . $lbl_class . ' label-fcinner ', $field->label).'
						</span>
						<div class="container_fcfield">
							'.$field->input.'
						</div>
					</fieldset>
					';
				}
			endforeach; ?>

			<div class="fc-success fc-mssg-inline" style="font-size: 12px; margin: 8px 0 !important;" id="__content_type_default_layout__">
				<?php /*echo JText::sprintf( 'FLEXI_USING_CONTENT_TYPE_LAYOUT', $this->tparams->get('ilayout') ) . "<br/><br/>";*/ ?>
				<?php echo JText::_( 'FLEXI_RECOMMEND_CONTENT_TYPE_LAYOUT' ); ?>
			</div>
			<div class="fcclear"></div>
			


			<div class="fc-sliders-plain-outer">
				<?php
				echo JHtml::_('sliders.start','theme-sliders-'.$this->form->getValue("id"), array('useCookie'=>1));
				$groupname = 'attribs';  // Field Group name this is for name of <fields name="..." >
				$item_layout = $this->item->itemparams->get('ilayout');
				
				foreach ($this->tmpls as $tmpl) :
					
					$form_layout = $tmpl->params;
					$label = '<span class="btn"><i class="icon-edit"></i>'.JText::_( 'FLEXI_PARAMETERS_THEMES_SPECIFIC' ) . ' : ' . $tmpl->name.'</span>';
					echo JHtml::_('sliders.panel', $label, $tmpl->name.'-'.$groupname.'-options');
					
					if (!$item_layout || $tmpl->name != $item_layout) continue;
					
					$fieldSets = $form_layout->getFieldsets($groupname);
					foreach ($fieldSets as $fsname => $fieldSet) : ?>
						<div class="panelform">
						
						<?php
						if (isset($fieldSet->label) && trim($fieldSet->label)) :
							echo '<div class="fcsep_level0">'.JText::_($fieldSet->label).'</div>';
						endif;
						if (isset($fieldSet->description) && trim($fieldSet->description)) :
							echo '<div class="fc-mssg fc-info">'.JText::_($fieldSet->description).'</div>';
						endif;
						
						foreach ($form_layout->getFieldset($fsname) as $field) :
							
							if ($field->getAttribute('not_inherited')) continue;
							if ($field->getAttribute('cssprep')) continue;
							
							$fieldname = $field->fieldname;
							//$value = $form_layout->getValue($fieldname, $groupname, $this->item->itemparams->get($fieldname));
							
							$input_only = !$field->label || $field->hidden;
							echo 
								($input_only ? '' :
								str_replace('class="', 'class="' . $lbl_class . ' label-fcinner ',
									str_replace(' for="', ' data-for="',
										str_replace('jform_attribs_', 'jform_layouts_'.$tmpl->name.'_',
											'<div class="control-group"><div class="control-label">'.$form_layout->getLabel($fieldname, $groupname)))).'
								</div><div class="controls">
								').
								
								str_replace('jform_attribs_', 'jform_layouts_'.$tmpl->name.'_', 
									str_replace('[attribs]', '[layouts]['.$tmpl->name.']',
										$form_layout->getInput($fieldname, $groupname/*, $value*/)   // Value already set, no need to pass it
									)
								).
								
								($input_only ? '' : '
								</div></div>
								');
						endforeach; ?>
						
						</fieldset>
						
					<?php endforeach; //fieldSets ?>
				<?php endforeach; //tmpls ?>
				
				<?php echo JHtml::_('sliders.end'); ?>
			</div>
       <!--/TEMPLATE CHOOSER-->
        <?php echo JHtml::_('bootstrap.endTab');?>
         <!--/FLEXI_CONTENT TAB4 -->
			<?php echo JHtml::_('bootstrap.endTabSet'); ?>
				<?php echo JHtml::_( 'form.token' ); ?>
				<input type="hidden" name="option" value="com_flexicontent"/>
				<input type="hidden" name="jform[id]" value="<?php echo $this->item->id; ?>"/>
				<input type="hidden" name="controller" value="items"/>
				<input type="hidden" name="view" value="item"/>
				<input type="hidden" name="task" value=""/>
				<input type="hidden" name="unique_tmp_itemid" value="<?php echo substr(JFactory::getApplication()->input->get('unique_tmp_itemid', '', 'string'), 0, 1000);?>"/>
				<?php echo $this->form->getInput('hits'); ?>
				</div></div>
   
   
</div>

</form>

</div>

<?php
//keep session alive while editing
JHtml::_('behavior.keepalive');
?>
