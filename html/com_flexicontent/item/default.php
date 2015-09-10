<?php
/**
 * @version 1.5 stable $Id: default.php 1904 2014-05-20 12:21:09Z ggppdk $
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

$task_items = 'task=items.';
$ctrl_items = 'items.';
$tags_task  = 'task=tags.';

// For tabsets/tabs ids (focusing, etc)
$tabSetCnt = -1;
$tabSetMax = -1;
$tabCnt = array();
$tabSetStack = array();

$useAssocs = flexicontent_db::useAssociations();
$tags_displayed = $this->row->type_id && ( $this->perms['cantags'] || count(@$this->usedtags) ) ;

$close_btn = FLEXI_J30GE ? '<a class="close" data-dismiss="alert">&#215;</a>' : '<a class="fc-close" onclick="this.parentNode.parentNode.removeChild(this.parentNode);">&#215;</a>';
$alert_box = FLEXI_J30GE ? '<div %s class="alert alert-%s %s">'.$close_btn.'%s</div>' : '<div %s class="fc-mssg fc-%s %s">'.$close_btn.'%s</div>';
$btn_class = FLEXI_J30GE ? 'btn' : 'fc_button';
$tip_class = FLEXI_J30GE ? ' hasTooltip' : ' hasTip';
$noplugin = '<div class="fc-mssg fc-warning">'. JText::_( 'FLEXI_PLEASE_PUBLISH_PLUGIN' ) .'</div>';

// add extra css/js for the edit form
if ($this->params->get('form_extra_css'))    $this->document->addStyleDeclaration($this->params->get('form_extra_css'));
if ($this->params->get('form_extra_css_be')) $this->document->addStyleDeclaration($this->params->get('form_extra_css_be'));
if ($this->params->get('form_extra_js'))     $this->document->addScriptDeclaration($this->params->get('form_extra_js'));
if ($this->params->get('form_extra_js_be'))  $this->document->addScriptDeclaration($this->params->get('form_extra_js_be'));

// Load JS tabber lib
$this->document->addScript(JURI::root(true).'/components/com_flexicontent/assets/js/tabber-minimized.js');
$this->document->addStyleSheet(JURI::root(true).'/components/com_flexicontent/assets/css/tabber.css');
$this->document->addScriptDeclaration(' document.write(\'<style type="text/css">.fctabber{display:none;}<\/style>\'); ');  // temporarily hide the tabbers until javascript runs

if ($this->perms['cantags'] || $this->perms['canversion']) {
	$this->document->addScript(JURI::root(true).'/components/com_flexicontent/librairies/jquery-autocomplete/jquery.bgiframe.min.js');
	$this->document->addScript(JURI::root(true).'/components/com_flexicontent/librairies/jquery-autocomplete/jquery.ajaxQueue.js');
	$this->document->addScript(JURI::root(true).'/components/com_flexicontent/librairies/jquery-autocomplete/jquery.autocomplete.min.js');
	$this->document->addScript(JURI::root(true).'/components/com_flexicontent/assets/js/jquery.pager.js');     // e.g. pagination for item versions
	$this->document->addScript(JURI::root(true).'/components/com_flexicontent/assets/js/jquery.autogrow.js');  // e.g. autogrow version comment textarea

	$this->document->addStyleSheet(JURI::root(true).'/components/com_flexicontent/librairies/jquery-autocomplete/jquery.autocomplete.css');
	$this->document->addScriptDeclaration("
		jQuery(document).ready(function () {
			jQuery('#input-tags').autocomplete('".JURI::base(true)."/index.php?option=com_flexicontent&".$task_items."viewtags&format=raw&".(FLEXI_J30GE ? JSession::getFormToken() : JUtility::getToken())."=1', {
				width: 260,
				max: 100,
				matchContains: false,
				mustMatch: false,
				selectFirst: false,
				dataType: 'json',
				parse: function(data) {
					return jQuery.map(data, function(row) {
						return {
							data: row,
							value: row.name,
							result: row.name
						};
					});
				},
				formatItem: function(row) {
					return row.name;
				}
			}).result(function(e, row) {
				jQuery('#input-tags').attr('data-tagid',row.id);
				jQuery('#input-tags').attr('data-tagname',row.name);
				addToList(row.id, row.name);
			}).keydown(function(event) {
				if((event.keyCode==13)&&(jQuery('#input-tags').attr('data-tagid')=='0') ) {//press enter button
					addtag(0, jQuery('#input-tags').attr('value'));
					resetField();
					return false;
				}else if(event.keyCode==13) {
					resetField();
					return false;
				}
			});
			function resetField() {
				jQuery('#input-tags').attr('data-tagid',0);
				jQuery('#input-tags').attr('data-tagname','');
				jQuery('#input-tags').attr('value','');
			}
		});
		
		jQuery(document).ready(function() {
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
			jQuery('#fc_pager').pager({ pagenumber: ".$this->current_page.", pagecount: ".$this->pagecount.", buttonClickCallback: PageClick });
		});
		
		PageClick = function(pageclickednumber) {
			jQuery.ajax({ url: 'index.php?option=com_flexicontent&".$task_items."getversionlist&id=".$this->row->id."&active=".$this->row->version."&".(FLEXI_J30GE ? JSession::getFormToken() : JUtility::getToken())."=1&format=raw&page='+pageclickednumber, context: jQuery('#version_tbl'), success: function(str){
				jQuery(this).html(\"\\
				<table class='fc-table-list fc-tbl-short' style='margin:10px;'>\\
				\"+str+\"\\
				</table>\\
				\");
				var JTooltips = new Tips($$('table.versionlist tr td a.hasTip'), { maxTitleChars: 50, fixed: false});
				
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
		
		jQuery(document).ready(function(){
			jQuery('#versioncomment').autogrow({
				minHeight: 26,
				maxHeight: 250,
				lineHeight: 12
			});
		})
		
	");
}

// version variables
$tags_fieldname = 'jform[tag][]';

$this->document->addScriptDeclaration("
	jQuery(document).ready(function(){
		var hits = new itemscreen('hits', {id:".($this->row->id ? $this->row->id : 0).", task:'".$ctrl_items."gethits'});
		hits.fetchscreen();
	
		var votes = new itemscreen('votes', {id:".($this->row->id ? $this->row->id : 0).", task:'".$ctrl_items."getvotes'});
		votes.fetchscreen();
	});

	function addToList(id, name) {
		obj = jQuery('#ultagbox');
		obj.append(\"<li class='tagitem'><span>\"+name+\"</span><input type='hidden' name='".$tags_fieldname."' value='\"+id+\"' /><a href='javascript:;' class='deletetag' onclick='javascript:deleteTag(this);' title='". JText::_( 'FLEXI_DELETE_TAG',true ) ."'></a></li>\");
	}
	function addtag(id, tagname) {
		if (id==null) id = 0;
	
		if(tagname == '') {
			alert('".JText::_( 'FLEXI_ENTER_TAG', true)."');
			return;
		}
	
		var tag = new itemscreen();
		tag.addtag( id, tagname, 'index.php?option=com_flexicontent&".$tags_task."addtag&format=raw&".(FLEXI_J30GE ? JSession::getFormToken() : JUtility::getToken())."=1');
	}

	function reseter(task, id, div){
		var res = new itemscreen();
		task = '".$ctrl_items."' + task;
		res.reseter( task, id, div, 'index.php?option=com_flexicontent&controller=items' );
	}
	function clickRestore(link) {
		if(confirm('".JText::_( 'FLEXI_CONFIRM_VERSION_RESTORE',true )."')) {
			location.href=link;
		}
		return false;
	}
	function deleteTag(obj) {
		var parent = obj.parentNode;
		parent.innerHTML = '';
		parent.parentNode.removeChild(parent);
	}
");




// Create info images
$infoimage    = JHTML::image ( 'administrator/templates/'.$template.'/images/flexi/information.png', JText::_( 'FLEXI_NOTES' ) );
$revertimage  = JHTML::image ( 'administrator/templates/'.$template.'/images/flexi/arrow_rotate_anticlockwise.png', JText::_( 'FLEXI_REVERT' ) );
$viewimage    = JHTML::image ( 'administrator/templates/'.$template.'/images/flexi/magnifier.png', JText::_( 'FLEXI_VIEW' ) );
$commentimage = JHTML::image ( 'administrator/templates/'.$template.'/images/flexi/comment.png', JText::_( 'FLEXI_COMMENT' ) );

// Create some variables
$itemlang = substr($this->row->language ,0,2);
if (isset($this->row->item_translations)) foreach ($this->row->item_translations as $t) if ($t->shortcode==$itemlang) {$itemlangname = $t->name; break;}
?>
<?php /* echo "Version: ". $this->row->version."<br/>\n"; */?>
<?php /* echo "id: ". $this->row->id."<br/>\n"; */?>
<?php /* echo "type_id: ". @$this->row->type_id."<br/>\n"; */?>

<div id="flexicontent" class="flexi_edit" >
  <form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal form-validate" enctype="multipart/form-data" >
    
    <!-- ##################
+ TITLE
####################### -->
    <div class="row-fluid">
      <div class="span12">
        <h1 class="contentx">
          <?php if ($this->form->getValue('title') == '') {
echo 'New item';}
else {
	echo $this->form->getValue('title');
};?>
        </h1>
      </div>
    </div>
    
    <!--Content-->
    <div class="row-fluid">
    
    <!-- 
####################### 
MAIN COLUMN
####################### 
-->
    <div class="span8">
      <?php
// *****************
// MAIN TABSET START
// *****************
array_push($tabSetStack, $tabSetCnt);
$tabSetCnt = ++$tabSetMax;
$tabCnt[$tabSetCnt] = 0;
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
    return $("#" + containerId + " a[href=" + href + "]").tab('show');
  });

  $("ul.nav.nav-pills, ul.nav.nav-tabs").each(function() {
    var $this = $(this);
    if (!json[$this.attr("id")]) {
      return $this.find("a[data-toggle=tab]:first, a[data-toggle=pill]:first").tab("show");
    }
  });
});
</script>
      <?php 
$options = array('active'    => 'tab'.$tabCnt[$tabSetCnt].'');
echo JHtml::_('bootstrap.startTabSet', 'ID-Tabs-'.$tabSetCnt.'', $options, array('useCookie'=>1));?>
      
      <!--FLEXI_BASIC TAB1 --> 
      <?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', '<i class="icon-star"></i><br class="hidden-phone">BASIC'); ?> 
      
      <!--TITLE -->
      <?php
				$field = isset($this->fields['title']) ? $this->fields['title'] : false;
				if ($field) {
					$field_description = $field->description ? $field->description :
						JText::_($this->form->getField('title')->description);
					$label_tooltip = 'class="'.$tip_class.' required" title="'.flexicontent_html::getToolTip(null, $field_description, 0, 1).'"';
				} else {
					$label_tooltip = '';
				}
			?>
      <div class="control-group">
        <div class="control-label">
          <label id="jform_title-lbl" for="jform_title" <?php echo $label_tooltip; ?> > <?php echo $field ? $field->label : JText::_( 'FLEXI_TITLE' ); ?> </label>
        </div>
        <?php /*echo $this->form->getLabel('title');*/ ?>
        <div class="controls container_fcfield_name_title input-fcmax" id="container_fcfield_6">
          <?php if ( $this->params->get('auto_title', 0) ): ?>
          <?php echo '<span class="badge badge-info">'.($this->row->id ? $this->row->id : JText::_('FLEXI_AUTO')).'</span>'; ?>
          <?php	elseif ( isset($this->row->item_translations) ) :?>
          <?php
				array_push($tabSetStack, $tabSetCnt);
				$tabSetCnt = ++$tabSetMax;
				$tabCnt[$tabSetCnt] = 0;
				?>
          <!-- tabber start -->
          <div class="fctabber tabber-inline tabber-lang" id="fcform_tabset_<?php echo $tabSetCnt; ?>">
            <div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" style="padding: 0px;">
              <h3 class="tabberheading"> <?php echo '-'.$itemlangname.'-'; // $itemlang; ?> </h3>
              <?php echo $this->form->getInput('title');?> </div>
            <?php foreach ($this->row->item_translations as $t): ?>
            <?php if ($itemlang!=$t->shortcode && $t->shortcode!='*') : ?>
            <div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" style="padding: 0px;">
              <h3 class="tabberheading"> <?php echo $t->name; // $t->shortcode; ?> </h3>
              <?php
								$ff_id = 'jfdata_'.$t->shortcode.'_title';
								$ff_name = 'jfdata['.$t->shortcode.'][title]';
								?>
              <input class="fc_form_title fcfield_textval" style='margin:0px;' type="text" id="<?php echo $ff_id; ?>" name="<?php echo $ff_name; ?>" value="<?php echo @$t->fields->title->value; ?>" size="36" maxlength="254" />
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
      <!--/TITLE--> 
      
      <!--ALIAS -->
      <?php
				$field_description = JText::_($this->form->getField('alias')->description);
				$label_tooltip = 'class="'.$tip_class.'" title="'.flexicontent_html::getToolTip(null, $field_description, 0, 1).'"';
			?>
      <div class="control-group">
        <div class="control-label">
          <label id="jform_alias-lbl" for="jform_alias" <?php echo $label_tooltip; ?> > <?php echo JText::_( 'FLEXI_ALIAS' ); ?> </label>
        </div>
        <div class="controls container_fcfield_name_alias input-fcmax">
          <?php	if ( isset($this->row->item_translations) ) :?>
          <?php
				array_push($tabSetStack, $tabSetCnt);
				$tabSetCnt = ++$tabSetMax;
				$tabCnt[$tabSetCnt] = 0;
				?>
          <!-- tabber start -->
          <div class="fctabber tabber-inline tabber-lang" id="fcform_tabset_<?php echo $tabSetCnt; ?>">
            <div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" style="padding: 0px;">
              <h3 class="tabberheading"> <?php echo '-'.$itemlangname.'-'; // $itemlang; ?> </h3>
              <?php echo $this->form->getInput('alias');?> </div>
            <?php foreach ($this->row->item_translations as $t): ?>
            <?php if ($itemlang!=$t->shortcode && $t->shortcode!='*') : ?>
            <div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" style="padding: 0px;">
              <h3 class="tabberheading"> <?php echo $t->name; // $t->shortcode; ?> </h3>
              <?php
								$ff_id = 'jfdata_'.$t->shortcode.'_alias';
								$ff_name = 'jfdata['.$t->shortcode.'][alias]';
								?>
              <input class="fc_form_alias fcfield_textval" style='margin:0px;' type="text" id="<?php echo $ff_id; ?>" name="<?php echo $ff_name; ?>" value="<?php echo @$t->fields->alias->value; ?>" size="36" maxlength="254" />
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
      <!--/ALIAS --> 
      
      <!--DOCUMENT TYPE-->
      <?php
				$field = isset($this->fields['document_type']) ? $this->fields['document_type'] : false;
				if ($field) {
					$field_description = $field->description ? $field->description :
						JText::_($this->form->getField('type_id')->description);
					$label_tooltip = 'class="'.$tip_class.' required" title="'.flexicontent_html::getToolTip(null, $field_description, 0, 1).'"';
				} else {
					$label_tooltip = 'class="required"';
				}
			?>
      <div class="control-group">
        <div class="control-label">
          <label id="jform_type_id-lbl" for="jform_type_id" for_bck="jform_type_id" <?php echo $label_tooltip; ?> > <?php echo $field ? $field->label : JText::_( 'FLEXI_TYPE' ); ?> </label>
        </div>
        <div class="controls container_fcfield_id_8 container_fcfield_name_type" id="container_fcfield_8"> <?php echo $this->lists['type']; ?>
          <?php //echo $this->form->getInput('type_id'); ?>
          <?php
				$label_tooltip = 'class="'.$tip_class.'" title="'.flexicontent_html::getToolTip('FLEXI_NOTES', 'FLEXI_TYPE_CHANGE_WARNING', 1, 1).'"';
				?>
          <span style="display:inline-block;" <?php echo $label_tooltip; ?> > <?php echo $infoimage; ?> </span>
          <div id="fc-change-warning" class="fc-mssg fc-warning" style="display:none;"><?php echo JText::_( 'FLEXI_TAKE_CARE_CHANGING_FIELD_TYPE' ); ?></div>
        </div>
      </div>
      <!--DOCUMENT TYPE--> 
      
      <!--STATE-->
      <?php
				$field = isset($this->fields['state']) ? $this->fields['state'] : false;
				if ($field) {
					$field_description = $field->description ? $field->description :
						JText::_($this->form->getField('state')->description);
					$label_tooltip = 'class="'.$tip_class.' label required" title="'.flexicontent_html::getToolTip(null, $field_description, 0, 1).'"';
				} else {
					$label_tooltip = 'class="label required"';
				}
			?>
      <div class="control-group">
        <div class="control-label">
          <label id="jform_state-lbl" for="jform_state" <?php echo $label_tooltip; ?> > <?php echo $field ? $field->label : JText::_( 'FLEXI_STATE' ); ?> </label>
        </div>
        <?php /*echo $this->form->getLabel('state');*/ ?>
        <?php
			if ( $this->perms['canpublish'] ) : ?>
        <div class="controls container_fcfield_id_10 container_fcfield_name_state" id="container_fcfield_10" > <?php echo $this->lists['state']; ?>
          <?php //echo $this->form->getInput('state'); ?>
          <span class="<?php echo $tip_class; ?>" style="display:inline-block;" title="<?php echo flexicontent_html::getToolTip( 'FLEXI_NOTES', 'FLEXI_STATE_CHANGE_WARNING', 1, 1);?>"> <?php echo $infoimage; ?> </span> </div>
        <?php else :
				echo $this->published;
				echo '<input type="hidden" name="jform[state]" id="jform_vstate" value="'.$this->row->state.'" />';
			endif;?>
        <?php if ( $this->perms['canpublish'] ) { ?>
        <?php if (!$this->params->get('auto_approve', 1)) : ?>
        <div class="control-group">
          <?php
						$label_tooltip = 'class="'.$tip_class.' label required" title="'.flexicontent_html::getToolTip('FLEXI_PUBLIC_DOCUMENT_CHANGES', 'FLEXI_PUBLIC_DOCUMENT_CHANGES_DESC', 1, 1).'"';
					?>
          <div class="control-label">
            <label id="jform_vstate-lbl" for="jform_vstate" <?php echo $label_tooltip; ?> > <?php echo JText::_( 'FLEXI_PUBLIC_DOCUMENT_CHANGES' ); ?> </label>
          </div>
          <div class="controls container_fcfield_name_vstate"> <?php echo $this->lists['vstate']; ?> </div>
          <?php else : ?>
          <?php echo '<div class="controls"><input type="hidden" name="jform[vstate]" id="jform_vstate" value="2" /></div>'; ?>
          <?php endif; ?>
          <?php } else if (!$this->params->get('auto_approve', 1)) {
				// Enable approval if versioning disabled, this make sense,
				// since if use can edit item THEN item should be updated !!!
				$item_vstate = $this->params->get('use_versioning', 1) ? 1 : 2;
				echo '<div class="controls"><input type="hidden" name="jform[vstate]" id="jform_vstate" value="'.$item_vstate.'" /></div>';
			} else {
				echo '<div class="controls"><input type="hidden" name="jform[vstate]" id="jform_vstate" value="2" /></div>';
			} ?>
        </div>
        <!--STATE--> 
        
        <!--SET Categories-->
        <h3 class="ruler"><i class="icon-folder"></i> <span class="m10r"></span><?php echo JText::_( 'FLEXI_CATEGORIES' ); ?></h3>
        
        <!--MAIN CATEGORY-->
        <div class="control-group">
          <div class="control-label">
            <label id="jform_catid-lbl" for="jform_catid" for_bck="jform_catid" class="<?php echo $tip_class; ?> required" title="<?php echo flexicontent_html::getToolTip( 'FLEXI_NOTES', 'FLEXI_SEC_FEAT_CATEGORIES_NOTES', 1, 1);?>"> <?php echo JText::_( 'FLEXI_CATEGORY' ); ?> </label>
          </div>
          <div class="controls container_fcfield_name_catid"> <?php echo $this->lists['catid']; ?> </div>
        </div>
        <!--MAIN CATEGORY-->
        
        <?php if (1) : ?>
        <div class="control-group">
          <div class="control-label">
            <label id="jform_cid-lbl" for="jform_cid"> <?php echo JText::_( 'FLEXI_SECONDARY_CATEGORIES' ); ?> </label>
          </div>
          <div class="controls container_fcfield_name_catid"> <?php echo $this->lists['cid']; ?> </div>
        </div>
        <?php endif; ?>
        <?php if ( !empty($this->lists['featured_cid']) ) : ?>
        <div class="control-group">
          <div class="control-label">
            <label id="jform_featured_cid-lbl" for="jform_featured_cid"> <?php echo JText::_( 'FLEXI_FEATURED_CATEGORIES' ); ?> </label>
          </div>
          <div class="controls container_fcfield_name_featured_cid"> <?php echo $this->lists['featured_cid']; ?> </div>
        </div>
        <?php endif; ?>
        <div class="control-group hfeat">
          <div class="control-label">
            <label> <?php echo JText::_( 'FLEXI_FEATURED' ); ?> <br>
              <small style="float:right; clear:both;"><?php echo JText::_( 'FLEXI_JOOMLA_FEATURED_VIEW' ); ?></small> </label>
          </div>
          <div class="controls container_fcfield_name_featured"> <?php echo $this->lists['featured']; ?>
            <?php //echo $this->form->getInput('featured');?>
          </div>
        </div>
        
        <!--/SET CATEGORIES--> 
        
        <!--SET LANGUAGE-->
        <div class="hlang">
          <h3 class="ruler"><i class="icon-comments-2"></i> <span class="m10r"></span><?php echo JText::_( 'FLEXI_LANGUAGE' ); ?></h3>
          <div class="control-group">
            <div class="control-label required">
              <label id="jform_language-lbl" for="jform_language"> <?php echo JText::_( 'FLEXI_LANGUAGE' ); ?> </label>
            </div>
            <div class="controls container_fcfield_name_language"> <?php echo $this->lists['languages']; ?> </div>
          </div>
          
          <!-- BOF of language / language associations section -->
          <?php if ( $useAssocs ) : ?>
          <legend> <span class="fc_legend_text"><?php echo JText::_('FLEXI_LANGUAGE') .' '. JText::_('FLEXI_ASSOCIATIONS'); ?></span> </legend>
          <div class="fcclear"></div>
          <?php echo $this->loadTemplate('associations'); ?>
          <div class="fcclear"></div>
          <?php
					$label_tooltip = 'class="'.$tip_class.' " title="'.flexicontent_html::getToolTip(null, 'FLEXI_ORIGINAL_CONTENT_ITEM_DESC', 1, 1).'"';
				?>
          <div class="control-group">
            <div class="control-label">
              <label id="jform_lang_parent_id-lbl" for="jform_lang_parent_id" <?php echo $label_tooltip; ?> > <?php echo JText::_( 'FLEXI_ORIGINAL_CONTENT_ITEM' );?> </label>
            </div>
            <div class="control container_fcfield_name_originalitem">
              <?php if ( $this->row->id  && (substr(flexicontent_html::getSiteDefaultLang(), 0,2) == substr($this->row->language, 0,2) || $this->row->language=='*') ) : ?>
              <br/>
              <small><?php echo JText::_( $this->row->language=='*' ? 'FLEXI_ORIGINAL_CONTENT_ALL_LANGS' : 'FLEXI_ORIGINAL_TRANSLATION_CONTENT' );?></small>
              <input type="hidden" name="jform[lang_parent_id]" id="jform_lang_parent_id" value="<?php echo $this->row->id; ?>" />
              <?php else : ?>
              <?php
					if (1) { // currently selecting associated item, is always allowed in backend
						$jAp= JFactory::getApplication();
						$option = JRequest::getVar('option');
						$jAp->setUserState( $option.'.itemelement.langparent_item', 1 );
						$jAp->setUserState( $option.'.itemelement.type_id', $this->row->type_id);
						$jAp->setUserState( $option.'.itemelement.created_by', $this->row->created_by);
						//echo '<small>'.JText::_( 'FLEXI_ORIGINAL_CONTENT_IGNORED_IF_DEFAULT_LANG' ).'</small><br/>';
						echo $this->form->getInput('lang_parent_id');
					?>
              <span class="editlinktip hasTip" style="display:inline-block;" title="<?php echo htmlspecialchars(JText::_( 'FLEXI_NOTES' ), ENT_COMPAT, 'UTF-8'); ?>::<?php echo htmlspecialchars(JText::_( 'FLEXI_ORIGINAL_CONTENT_IGNORED_IF_DEFAULT_LANG' ), ENT_COMPAT, 'UTF-8');?>"> <?php echo $infoimage; ?> </span>
              <?php
					} else {
						echo JText::_( 'FLEXI_ORIGINAL_CONTENT_ALREADY_SET' );
					}
					?>
              <?php endif; ?>
            </div>
          </div>
          <div class="control-group">
            <div class="control-label">
              <label id="langassocs-lbl" for="langassocs" class="labelx" > <?php echo JText::_( 'FLEXI_ASSOC_TRANSLATIONS' );?> </label>
            </div>
            <div class="controls container_fcfield_name_langassocs">
              <?php
				if ( !empty($this->lang_assocs) )
				{
					$row_modified = 0;
					foreach($this->lang_assocs as $assoc_item) {
						if ($assoc_item->id == $this->row->lang_parent_id) {
							$row_modified = strtotime($assoc_item->modified);
							if (!$row_modified)  $row_modified = strtotime($assoc_item->created);
						}
					}
					
					foreach($this->lang_assocs as $assoc_item)
					{
						if ($assoc_item->id==$this->row->id) continue;
						
						$assoc_modified = strtotime($assoc_item->modified);
						if (!$assoc_modified)  $assoc_modified = strtotime($assoc_item->created);
						$_class = ( $assoc_modified < $row_modified ) ? ' fc_assoc_outdated' : '';
						
						$_link  = 'index.php?option=com_flexicontent&'.$task_items.'edit&cid='. $assoc_item->id;
						$_title = flexicontent_html::getToolTip(
							JText::_( $assoc_modified < $row_modified ? 'FLEXI_OUTDATED' : 'FLEXI_UPTODATE'),
							//JText::_( 'FLEXI_EDIT_ASSOC_TRANSLATION').
							($assoc_item->lang=='*' ? JText::_("All") : $this->langs->{$assoc_item->lang}->name).' <br/><br/> '.
							$assoc_item->title, 0, 1
						);
						
						echo '<a class="fc_assoc_translation '.$tip_class.$_class.'" target="_blank" href="'.$_link.'" title="'.$_title.'" >';
						if ( !empty($assoc_item->lang) && !empty($this->langs->{$assoc_item->lang}->imgsrc) ) {
							echo ' <img src="'.$this->langs->{$assoc_item->lang}->imgsrc.'" alt="'.$assoc_item->lang.'" />';
						} else if( !empty($assoc_item->lang) ) {
							echo $assoc_item->lang=='*' ? JText::_("All") : $assoc_item->lang;
						}
						echo "</a>";
					}
				}
				?>
            </div>
          </div>
          <?php endif; ?>
          <!-- EOF of language / language associations section --> 
          
        </div>
        <!-- end hlang --> 
        <!--/END LANGUAGE--> 
        
        <!--TAGS-->
        <div class="htags">
          <h3 class="ruler"><i class="icon-tag"></i><span class="m10r"></span>Tags</h3>
          
          <!--TAGS-->
          
          <?php
     $field = isset($this->fields['tags']) ? $this->fields['tags'] : false;
     if ($field) {
      $label_tooltip = 'class="'.$tip_class.' control-label" title="'.flexicontent_html::getToolTip(null, $field->description, 0, 1).'"';
     } else {
      $label_tooltip = 'class="control-label"';
     }
    ?>
          <div class="control-group">
            <div class="control-label">
              <label><?php echo JText::_( 'FLEXI_TAGS' ); ?></label>
            </div>
            <div class="controls container_fcfield_name_tags">
              <div class="qf_tagbox" id="qf_tagbox">
                <ul id="ultagbox">
                  <?php
						$nused = count($this->usedtags);
						for( $i = 0, $nused; $i < $nused; $i++ ) {
							$tag = $this->usedtags[$i];
							if ( $this->perms['cantags'] ) {
								echo '
								<li class="tagitem">
									<span>'.$tag->name.'</span>
									<input type="hidden" name="jform[tag][]" value="'.$tag->tid.'" />
									<a href="javascript:;" class="deletetag" onclick="javascript:deleteTag(this);" align="right" title="'.JText::_('FLEXI_DELETE_TAG').'"></a>
								</li>';
							} else {
								echo '
								<li class="tagitem plain">
									<span>'.$tag->name.'</span>
									<input type="hidden" name="jform[tag][]" value="'.$tag->tid.'" />
								</li>';
							}
						}
					?>
                </ul>
              </div>
              <?php if ( $this->perms['cantags'] ) : ?>
              <div class="fcclear"></div>
              <div id="tags">
                <?php /*<label for="input-tags">
						<?php echo JText::_( 'FLEXI_ADD_TAG' ); ?>
					</label> */ ?>
                <input type="text" id="input-tags" name="tagname" data-tagid="0" data-tagname="" />
                <span id='input_new_tag' ></span> <span class="<?php echo $tip_class; ?>" style="display:inline-block;" title="<?php echo flexicontent_html::getToolTip( 'FLEXI_NOTES', 'FLEXI_TAG_EDDITING_FULL', 1, 1);?>"> <?php echo $infoimage; ?> </span> </div>
              <?php endif; ?>
            </div>
          </div>
        </div>
        
        <!--/TAGS--> 
        
        <?php echo JHtml::_('bootstrap.endTab');?> 
        <!--/FLEXI_BASIC TAB1 --> 
        
        <!--IF TAB2 -->
        <?php

	$field = $this->fields['text'];
	if ($field) {
		$field_description = $field->description ? $field->description :
			JText::_($this->form->getField('text')->description);
		$_desc = flexicontent_html::getToolTip(null, $field_description, 0, 1);
	} else {
		$_desc = '';
	}
	
//$type_lbl = $this->row->type_id ? JText::_( 'FLEXI_ITEM_TYPE' ) . ' : ' . $this->typesselected->name : JText::_( 'FLEXI_TYPE_NOT_DEFINED' );
if ($this->row->type_id) {
	$_str = JText::_('FLEXI_DETAILS');
	$_str = mb_strtoupper(mb_substr($_str, 0, 1, 'UTF-8')) . mb_substr($_str, 1, NULL, 'UTF-8');
	
	$type_lbl = $this->typesselected->name;
	$type_lbl = $type_lbl ? JText::_($type_lbl) : JText::_('FLEXI_CONTENT_TYPE');
	$type_lbl = $type_lbl .' ('. $_str .')';
} else {
	$type_lbl = JText::_('FLEXI_TYPE_NOT_DEFINED');
}



?>
        <?php if ($this->fields && $this->row->type_id) : ?>
        
        <!--FLEXI_CONTENT TAB2 --> 
        <?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', '<i class="icon-pencil"></i><br class="hidden-phone">Content') ?> 
        
        <!-- Field manager tab -->
        <div class="contentx">
        <h3 class="tabberheading"> <?php echo $type_lbl; ?> </h3>
          <?php
			$this->document->addScriptDeclaration("
				jQuery(document).ready(function() {
					jQuery('#jform_type_id').change(function() {
						if (jQuery('#jform_type_id').val() != '".$this->row->type_id."')
							jQuery('#fc-change-warning').css('display', 'block');
						else
							jQuery('#fc-change-warning').css('display', 'none');
					});
				});
			");
		?>
          <div class="inner">
            <?php
			$hide_ifempty_fields = array('fcloadmodule', 'fcpagenav', 'toolbar');
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
				if ($field->field_type=='groupmarker') {
					echo $field->html;
					continue;
				} else if ($field->field_type=='coreprops') {
					// not used in backend (yet?)
					continue;
				/* i want to be able to sort
				} else if ($field->field_type=='maintext') {
					// placed in separate TAB
					continue;*/
				}
				
				// Decide label classes, tooltip, etc
				$lbl_class = 'labelx';
				$lbl_title = '';
				
				// field is required
				$lbl_class = $field->parameters->get('required', 0 ) ? 'required' : '';
				
				// field has tooltip
				$edithelp = $field->edithelp ? $field->edithelp : 1;
				if ( $field->description && ($edithelp==1 || $edithelp==2) ) {
					$lbl_class .= ($edithelp==2 ? ' fc_tooltip_icon' : '');
					$label_tooltip = 'class="'.$tip_class.' '.$lbl_class.' labelx" title="'.flexicontent_html::getToolTip(null, $field->description, 0, 1).'"';
				} else {
					$label_tooltip = 'class="'.$lbl_class.' labelx"';
				}
				
				// Some fields may force a container width ?
				$display_label_form = $field->parameters->get('display_label_form', 1);
				$row_k = 1 - $row_k;
				$full_width = $display_label_form==0 || $display_label_form==2 || $display_label_form==-1;
				$width = $field->parameters->get('container_width', ($full_width ? '100%!important;' : false) );
				$container_width = empty($width) ? '' : 'width:' .$width. ($width != (int)$width ? 'px!important;' : '');
				$container_class = "fcfield_row".$row_k." controls container_fcfield_id_".$field->id." container_fcfield_name_".$field->name;
				?>
            <div class="control-group <?php if($display_label_form==2):  ?>lbreak<?php endif; ?>">
            <div class="control-label" style="<?php echo $display_label_form < 1 ? 'display:none;' : '' ?>">
            <label id="label_fcfield_<?php echo $field->id; ?>" for="<?php echo 'custom_'.$field->name;?>" for_bck="<?php echo 'custom_'.$field->name;?>" <?php echo $label_tooltip;?> > <?php echo $field->label; ?> </label>
            </div>
            <?php if($display_label_form==2):  ?>
            <div class='fcclear'></div>
            <?php endif; ?>
            <div style="<?php echo $container_width; ?>" class="<?php echo $container_class;?>" id="container_fcfield_<?php echo $field->id;?>"> <?php echo ($field->description && $edithelp==3) ? '<div class="alert fc-small fc-iblock">'.$field->description.'</div>' : ''; ?>
              <?php if ( !is_array($field->html) ) : /* CASE 2: NORMAL FIELD non-tabbed */ ?>
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
          </div>
        </div>
        <!-- end tab -->
        
        <?php else : /* NO TYPE SELECTED */ ?>
        <div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" >
          <h3 class="tabberheading"> <?php echo $type_lbl; ?> </h3>
          <div class="fc_tabset_inner">
            <?php if ($this->row->id == 0) : ?>
            <input name="jform[type_id_not_set]" value="1" type="hidden" />
            <div class="fc-mssg fc-note"><?php echo JText::_( 'FLEXI_CHOOSE_ITEM_TYPE' ); ?></div>
            <?php else : ?>
            <div class="fc-mssg fc-warning"><?php echo JText::_( 'FLEXI_NO_FIELDS_TO_TYPE' ); ?></div>
            <?php	endif; ?>
          </div>
        </div>
        <!-- end tab -->
        
        <?php	endif; ?>
        <br class="clear">
        <?php echo JHtml::_('bootstrap.endTab');?> 
        <!--/FLEXI_CONTENT TAB2 --> 
        
        <!--FLEXI_SEO TAB3 --> 
        <?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', '<i class="icon-bookmark"></i><br class="hidden-phone">SEO / Meta'); ?>
        
       
       

		<h3 class="ruler"> <?php echo JText::_('FLEXI_META_SEO'); ?> </h3>
		
		<?php
		//echo $this->form->getLabel('metadesc');
		//echo $this->form->getInput('metadesc');
		//echo $this->form->getLabel('metakey');
		//echo $this->form->getInput('metakey');
		?>
		
		<h3 class="ruler"><?php echo JText::_( 'FLEXI_META' ); ?></h3>

		
<div class="control-group">
			<div class="control-label">
						<?php echo str_replace('class="', 'class="labelx ', $this->form->getLabel('metadesc')); ?>
			</div>
			<div class="controls">
				
				<?php	if ( isset($this->row->item_translations) ) : ?>
					<?php
					array_push($tabSetStack, $tabSetCnt);
					$tabSetCnt = ++$tabSetMax;
					$tabCnt[$tabSetCnt] = 0;
					?>
					<!-- tabber start -->
					<div class="fctabber tabber-inline tabber-lang" id="fcform_tabset_<?php echo $tabSetCnt; ?>">
						<div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" style="padding: 0px;">
							<h3 class="tabberheading"> <?php echo '-'.$itemlangname.'-'; // $itemlang; ?> </h3>
							<?php echo $this->form->getInput('metadesc');?>
						</div>
						<?php foreach ($this->row->item_translations as $t): ?>
							<?php if ($itemlang!=$t->shortcode && $t->shortcode!='*') : ?>
								<div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" style="padding: 0px;">
									<h3 class="tabberheading"> <?php echo $t->name; // $t->shortcode; ?> </h3>
									<?php
									$ff_id = 'jfdata_'.$t->shortcode.'_metadesc';
									$ff_name = 'jfdata['.$t->shortcode.'][metadesc]';
									?>
									<textarea id="<?php echo $ff_id; ?>" class="inputbox" rows="3" cols="46" name="<?php echo $ff_name; ?>"><?php echo @$t->fields->metadesc->value; ?></textarea>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<!-- tabber end -->
					<?php $tabSetCnt = array_pop($tabSetStack); ?>
				
				<?php else : ?>
					<?php echo $this->form->getInput('metadesc'); ?>
				<?php endif; ?>
				
			</div></div>

			
<!--META KEYWORDS-->
			<div class="control-group hkey">
			<div class="control-label">
			<?php echo str_replace('class="', 'class="labelx ', $this->form->getLabel('metakey')); ?>
            </div>
			
			<div class="controls">
				<?php	if ( isset($this->row->item_translations) ) :?>
					<?php
					array_push($tabSetStack, $tabSetCnt);
					$tabSetCnt = ++$tabSetMax;
					$tabCnt[$tabSetCnt] = 0;
					?>
					<!-- tabber start -->
					<div class="fctabber tabber-inline tabber-lang" id="fcform_tabset_<?php echo $tabSetCnt; ?>">
						<div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>" style="padding: 0px;">
							<h3 class="tabberheading"> <?php echo '-'.$itemlangname.'-'; // $itemlang; ?> </h3>
							<?php echo $this->form->getInput('metakey');?>
						</div>
						<?php foreach ($this->row->item_translations as $t): ?>
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
				
				</div></div>
<!--/META KEYWORDS-->
	
    
    <!--META ADVANCED-->	
		<?php foreach($this->form->getGroup('metadata') as $field): ?>
			<?php if ($field->hidden): ?>
				<span style="display:none !important;">
					<?php echo $field->input; ?>
				</span>
			<?php else: ?>
				<fieldset class="control-group">
					<?php echo ($field->label ? '
						<div class="control-label">'.str_replace('class="', 'class="labelx ', $field->label).'</div>
						<div class="controls">'.$field->input.'</div>
					' : $field->input); ?>
				</fieldset>
			<?php endif; ?>
		<?php endforeach; ?>
		
		
		<h3 class="ruler"><?php echo JText::_( 'FLEXI_SEO' ); ?></h3>
	
		
		<?php foreach ($this->form->getFieldset('params-seoconf') as $field) : ?>
			<?php if ($field->hidden): ?>
				<span style="display:none !important;">
					<?php echo $field->input; ?>
				</span>
			<?php else: ?>
				<fieldset class="control-group">
					<?php echo ($field->label ? '
						<div class="control-label">'.str_replace('class="', 'class="labelx ', $field->label).'</div>
						<div class="controls">'.$field->input.'</div>
					' : $field->input); ?>
				</fieldset>
			<?php endif; ?>
		<?php endforeach; ?>

		

        <?php echo JHtml::_('bootstrap.endTab');?> 
        <!--/FLEXI_SEO TAB3 --> 
        
        <!--FLEXI_DISPLAY TAB4 --> 
        <?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', '<i class="icon-palette"></i><br class="hidden-phone">Display'); ?>
        
       
       	<h3 class="ruler"><?php echo JText::_('FLEXI_DISPLAYING'); ?></h3>
		
		
		<?php
			$fieldSets = $this->form->getFieldsets('attribs');
			foreach ($fieldSets as $name => $fieldSet) :
				if ( $name=='themes' || $name=='params-seoconf'  || $name=='images' ||  $name=='urls' ) continue;

				//$label = !empty($fieldSet->label) ? $fieldSet->label : 'FLEXI_'.$name.'_FIELDSET_LABEL';
				//echo JHtml::_('sliders.panel', JText::_($label), $name.'-options');
				?>
				<?php foreach ($this->form->getFieldset($name) as $field) : ?>
					
					<?php if ($field->hidden): ?>
						<span style="display:none !important;">
							<?php echo $field->input; ?>
						</span>
					<?php else: ?>
					<fieldset class="control-group">
					<?php echo ($field->label ? '
						<div class="control-label">'.str_replace('class="', 'class="labelx ', $field->label).'</div>
						<div class="controls">'.$field->input.'</div>
					' : $field->input); ?>
				</fieldset>
					<?php endif; ?>
					
				<?php endforeach; ?>
				
		<?php endforeach; ?>
        
        <?php echo JHtml::_('bootstrap.endTab');?> 
        <!--/FLEXI_DISPLAY TAB4 --> 
        
        <!-- / TABS --> 
        <?php echo JHtml::_('bootstrap.endTabSet');?> </div>
      <!--/ .span8 main column--> 
      <!-- 
####################### 
RIGHT COLUMN
####################### 
-->
      <div class="span4 mheight"> 
        
        <!--ACCORDION-->
        <div class="panel-group accordion accordion-semi" id="accordion2"> 
          <!--PANEL-->
          <?php 
if ($this->perms['canparams']) : ?>
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne"> <i class="fa icon-arrow-right"></i> <i class="icon-calendar">&nbsp;</i> <?php echo JText::_('FLEXI_PUBLISHING'); ?> </a></h4>
            </div>
            <div id="collapseOne" class="accordion-body in collapse">
              <div class="panel-body form-horizontal"> 
                <!--CONTENT-->
                
                
                <div class="fc-info fc-nobgimage fc-mssg-inline">
			<?php
				// Dates displayed in the item form, are in user timezone for J2.5, and in site's default timezone for J1.5
				$site_zone = JFactory::getApplication()->getCfg('offset');
				$user_zone = JFactory::getUser()->getParam('timezone', $site_zone);
				$tz = new DateTimeZone( $user_zone );
				$tz_offset = $tz->getOffset(new JDate()) / 3600;
				$tz_info =  $tz_offset > 0 ? ' UTC +' . $tz_offset : ' UTC ' . $tz_offset;
				$tz_info .= ' ('.$user_zone.')';
				echo JText::sprintf( FLEXI_J16GE ? 'FLEXI_DATES_IN_USER_TIMEZONE_NOTE' : 'FLEXI_DATES_IN_SITE_TIMEZONE_NOTE', ' ', $tz_info );
			?>
			</div>
            
                <?php
		$hide_style = $this->perms['canparams'] ? '' : 'visibility:hidden;';
		/*if (isset($fieldSet->description) && trim($fieldSet->description)) :
			echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
		endif;*/
		?>
                <fieldset class="flexi_params fc_edit_container_full" style="<?php echo $hide_style; ?>" >
                  <?php /*if ($this->perms['isSuperAdmin']) :*/ ?>
                  <div class="form-inline">
                    <div class="control-group"><?php echo $this->form->getLabel('created_by'); ?>
                      <div class="controls"><?php echo $this->form->getInput('created_by'); ?></div>
                    </div>
                  </div>
                  <?php /*endif;*/ ?>
                  <?php if ($this->perms['editcreationdate']) : ?>
                  <div class="form-inline">
                    <div class="control-group"><?php echo $this->form->getLabel('created'); ?>
                      <div class="controls"><?php echo $this->form->getInput('created'); ?></div>
                    </div>
                  </div>
                  <?php endif; ?>
                  <div class="form-inline">
                    <div class="control-group"><?php echo $this->form->getLabel('created_by_alias'); ?>
                      <div class="controls"><?php echo $this->form->getInput('created_by_alias'); ?></div>
                    </div>
                  </div>
                  <div class="form-inline">
                    <div class="control-group"><?php echo $this->form->getLabel('publish_up'); ?>
                      <div class="controls"><?php echo $this->form->getInput('publish_up'); ?></div>
                    </div>
                  </div>
                  <div class="form-inline">
                    <div class="control-group"><?php echo $this->form->getLabel('publish_down'); ?>
                      <div class="controls"><?php echo $this->form->getInput('publish_down'); ?></div>
                    </div>
                  </div>
                  <div class="form-inline">
                    <div class="control-group"><?php echo $this->form->getLabel('access'); ?>
                      <?php if ($this->perms['canacclvl']) :?>
                      <div class="controls"><?php echo $this->form->getInput('access'); ?></div>
                    </div>
                  </div>
                  <?php else :?>
                  <div class="controls"><span class="label"><?php echo $this->row->access_level; ?></span></div>
                  <?php endif; ?>
                </fieldset>
                
			
			
                <!--/CONTENT--> 
                
              </div>
            </div>
          </div>
          <?php endif; ?>
          <!--/PANEL--> 
          <!--PANEL2-->
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title"><a class="collapsed collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo"> <i class="fa icon-arrow-right"></i> <i class="icon-info"></i> Info</a></h4>
            </div>
            <div id="collapseTwo" class="accordion-body collapsed collapse">
              <div class="panel-body"> 
                <!--  VERSIONING-->
                <?php
		// used to hide "Reset Hits" when hits = 0
		if ( !$this->row->hits ) {
			$visibility = 'style="display: none; visibility: hidden;"';
		} else {
			$visibility = '';
		}

		if ( !$this->row->score ) {
			$visibility2 = 'style="display: none; visibility: hidden;"';
		} else {
			$visibility2 = '';
		}

		?>
                <table class="responsive table no-strip plain-label">
                  <?php
		if ( $this->row->id ) {
		?>
                  <tr>
                    <td><span class="label"><?php echo JText::_( 'FLEXI_ITEM_ID' ); ?></span></td>
                    <td><?php echo $this->row->id; ?></td>
                  </tr>
                  <?php
		}
		?>
                  <tr>
                    <td><?php
					$field = isset($this->fields['state']) ? $this->fields['state'] : false;
					if ($field) {
						$label_tooltip = 'class="'.$tip_class.' label" title="'.flexicontent_html::getToolTip(null, $field->description, 0, 1).'"';
					} else {
						$label_tooltip = 'class="label"';
					}
				?>
                      <span <?php echo $label_tooltip; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_STATE' ); ?></span></td>
                    <td><?php echo $this->published;?></td>
                  </tr>
                  <tr>
                    <td><?php
					$field = isset($this->fields['hits']) ? $this->fields['hits'] : false;
					if ($field) {
						$label_tooltip = 'class="'.$tip_class.' label" title="'.flexicontent_html::getToolTip(null, $field->description, 0, 1).'"';
					} else {
						$label_tooltip = 'class="label"';
					}
				?>
                      <span <?php echo $label_tooltip; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_HITS' ); ?></span></td>
                    <td><div id="hits" style="display: inline-block;vertical-align: middle;"></div>
                      &nbsp; <span <?php echo $visibility; ?>>
                      <input name="reset_hits" type="button" class="button" value="<?php echo JText::_( 'FLEXI_RESET' ); ?>" onclick="reseter('<?php echo $ctrl_items; ?>resethits', '<?php echo $this->row->id; ?>', 'hits')" />
                      </span></td>
                  </tr>
                  <tr>
                    <td><?php
					$field = isset($this->fields['voting']) ? $this->fields['voting'] : false;
					if ($field) {
						$label_tooltip = 'class="'.$tip_class.' label" title="'.flexicontent_html::getToolTip(null, $field->description, 0, 1).'"';
					} else {
						$label_tooltip = 'class="label"';
					}
				?>
                      <span <?php echo $label_tooltip; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_SCORE' ); ?></span></td>
                    <td><div id="votes" style="float:left;"></div>
                      &nbsp; <span <?php echo $visibility2; ?>>
                      <input name="reset_votes" type="button" class="button" value="<?php echo JText::_( 'FLEXI_RESET' ); ?>" onclick="reseter('<?php echo $ctrl_items; ?>resetvotes', '<?php echo $this->row->id; ?>', 'votes')" />
                      </span></td>
                  </tr>
                  <tr>
                    <td><?php
					$field = isset($this->fields['modified']) ? $this->fields['modified'] : false;
					if ($field) {
						$label_tooltip = 'class="'.$tip_class.' label" title="'.flexicontent_html::getToolTip(null, $field->description, 0, 1).'"';
					} else {
						$label_tooltip = 'class="label"';
					}
				?>
                      <span <?php echo $label_tooltip; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_REVISED' ); ?></span></td>
                    <td><?php echo $this->row->last_version;?> <?php echo JText::_( 'FLEXI_TIMES' ); ?></td>
                  </tr>
                  <tr>
                    <td><span class="label"><?php echo JText::_( 'FLEXI_FRONTEND_ACTIVE_VERSION' ); ?></span></td>
                    <td> #<?php echo $this->row->current_version;?></td>
                  </tr>
                  <tr>
                    <td><span class="label"><?php echo JText::_( 'FLEXI_FORM_LOADED_VERSION' ); ?></span></td>
                    <td> #<?php echo $this->row->version;?></td>
                  </tr>
                  <tr>
                    <td><?php
					$field = isset($this->fields['created']) ? $this->fields['created'] : false;
					if ($field) {
						$label_tooltip = 'class="'.$tip_class.' label" title="'.flexicontent_html::getToolTip(null, $field->description, 0, 1).'"';
					} else {
						$label_tooltip = 'class="label"';
					}
				?>
                      <span <?php echo $label_tooltip; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_CREATED' ); ?></span></td>
                    <td><?php
				if ( $this->row->created == $this->nullDate ) {
					echo JText::_( 'FLEXI_NEW_ITEM' );
				} else {
					echo JHTML::_('date',  $this->row->created,  JText::_( 'DATE_FORMAT_LC2' ) );
				}
				?></td>
                  </tr>
                  <tr>
                    <td><?php
					$field = isset($this->fields['modified']) ? $this->fields['modified'] : false;
					if ($field) {
						$label_tooltip = 'class="'.$tip_class.' label" title="'.flexicontent_html::getToolTip(null, $field->description, 0, 1).'"';
					} else {
						$label_tooltip = 'class="label"';
					}
				?>
                      <span <?php echo $label_tooltip; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_MODIFIED' ); ?></span></td>
                    <td><?php
					if ( $this->row->modified == $this->nullDate ) {
						echo JText::_( 'FLEXI_NOT_MODIFIED' );
					} else {
						echo JHTML::_('date',  $this->row->modified, JText::_( 'DATE_FORMAT_LC2' ));
					}
				?></td>
                  </tr>
                </table>
              </div>
            </div>
          </div>
          <!--/PANEL2--> 
          
          <!--PANEL3-->
          <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title"><a class="collapsed collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree"> <i class="fa icon-arrow-right"></i> <i class="icon-clock"></i> Revisions</a></h4>
            </div>
            <div id="collapseThree" class="accordion-body collapsed collapse">
              <div class="panel-body form-horizontal">
                <?php if ($this->params->get('use_versioning', 1)) : ?>
                <h3 class="text-center p10"><?php echo JText::_( 'FLEXI_VERSION_COMMENT' ); ?></h3>
                <div class="row-fluid">
                  <div class="span12">
                    <div class="m20x text-center">
                      <textarea name="jform[versioncomment]" id="versioncomment"  class="text-center" rows="5" cols="32"></textarea>
                    </div>
                  </div>
                  <?php if ( $this->perms['canversion'] ) : ?>
                  <hr>
                  <div id="result" >
                    <h3 class="text-center p10"><?php echo JText::_( 'FLEXI_VERSIONS_HISTORY' ); ?></h3>
                    <table class="responsive hideborder">
                      <tbody>
                        <?php if ($this->row->id == 0) : ?>
                        <tr>
                          <td class="versions-first text-center" colspan="4"><?php echo JText::_( 'FLEXI_NEW_ARTICLE' ); ?></td>
                        </tr>
                        <?php
			else :
			$date_format = (($date_format = JText::_( 'FLEXI_DATE_FORMAT_FLEXI_VERSIONS_J16GE' )) == 'FLEXI_DATE_FORMAT_FLEXI_VERSIONS_J16GE') ? "d/M H:i" : $date_format;
			foreach ($this->versions as $version) :
				$class = ($version->nr == $this->row->version) ? ' class="active-version"' : '';
				if ((int)$version->nr > 0) :
			?>
                        <tr>
                          <td class="versions text-center"><?php echo '#' . $version->nr; ?></td>
                          <td class="versions"><?php echo JHTML::_('date', (($version->nr == 1) ? $this->row->created : $version->date), $date_format ); ?></td>
                          <td class="versions"><?php echo ($version->nr == 1) ? flexicontent_html::striptagsandcut($this->row->creator, 25) : flexicontent_html::striptagsandcut($version->modifier, 25); ?></td>
                          <td class="versions text-center"><a href="javascript:;" class="hasTip" title="Comment::<?php echo htmlspecialchars($version->comment, ENT_COMPAT, 'UTF-8');?>"><?php echo $commentimage;?></a>
                            <?php
				if((int)$version->nr==(int)$this->row->current_version) { ?>
                            <a onclick="javascript:return clickRestore('index.php?option=com_flexicontent&view=item&<?php echo $task_items;?>edit&cid=<?php echo $this->row->id;?>&version=<?php echo $version->nr; ?>');" href="#"><?php echo JText::_( 'FLEXI_CURRENT' ); ?></a>
                            <?php }else{
				?>
                            <a class="modal-versions"
						href="index.php?option=com_flexicontent&view=itemcompare&cid=<?php echo $this->row->id; ?>&version=<?php echo $version->nr; ?>&tmpl=component"
						title="<?php echo JText::_( 'FLEXI_COMPARE_WITH_CURRENT_VERSION' ); ?>"
					> <?php echo $viewimage; ?> </a> <a onclick="javascript:return clickRestore('index.php?option=com_flexicontent&task=items.edit&cid=<?php echo $this->row->id; ?>&version=<?php echo $version->nr; ?>&<?php echo (FLEXI_J30GE ? JSession::getFormToken() : JUtility::getToken());?>=1');"
						href="javascript:;"
						title="<?php echo JText::sprintf( 'FLEXI_REVERT_TO_THIS_VERSION', $version->nr ); ?>"
					> <?php echo $revertimage; ?> </a>
                            <?php }?></td>
                        </tr>
                        <?php
				endif;
			endforeach;
			endif; ?>
                      </tbody>
                    </table>
                  </div>
                  <div class="m10">
                    <div id="fc_pager"></div>
                    <div class="clear"></div>
                  </div>
                  <?php endif; ?>
                  <?php endif; ?>
                </div>
              </div>
            </div>
            <!--/PANEL3--> 
            
          </div>
        </div>
        <!--/ACCORDION--> 
        
      </div>
      <!--/ .span4 right column--> 
      
    </div>
    <!--/ .row-fluid--> 
    
    <?php echo JHTML::_( 'form.token' ); ?>
    <input type="hidden" name="option" value="com_flexicontent" />
    <input type="hidden" name="jform[id]" value="<?php echo $this->row->id; ?>" />
    <input type="hidden" name="controller" value="items" />
    <input type="hidden" name="view" value="item" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="unique_tmp_itemid" value="<?php echo JRequest::getVar( 'unique_tmp_itemid' );?>" />
    <?php echo $this->form->getInput('hits'); ?>
  </form>
</div>
<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>
