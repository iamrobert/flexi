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

$task_items = FLEXI_J16GE ? 'task=items.' : 'controller=items&task=';
$ctrl_items = FLEXI_J16GE ? 'items.' : '';
$tags_task = FLEXI_J16GE ? 'task=tags.' : 'controller=tags&task=';

// For tabsets/tabs ids (focusing, etc)
$tabSetCnt = -1;
$tabCnt = array();

$tags_displayed = $this->row->type_id && ( $this->perms['cantags'] || count(@$this->usedtags) ) ;
$close_btn = FLEXI_J30GE ? '<a class="close" data-dismiss="alert">&#215;</a>' : '<a class="fc-close" onclick="this.parentNode.parentNode.removeChild(this.parentNode);">&#215;</a>';
$alert_box = FLEXI_J30GE ? '<div %s class="alert alert-%s %s">'.$close_btn.'%s</div>' : '<div %s class="fc-mssg fc-%s %s">'.$close_btn.'%s</div>';
$btn_class = FLEXI_J30GE ? 'btn' : 'fc_button';
$tip_class = FLEXI_J30GE ? ' hasTooltip' : ' hasTip';

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
			jQuery('#input-tags').autocomplete('".JURI::base()."index.php?option=com_flexicontent&".$task_items."viewtags&format=raw&".(FLEXI_J30GE ? JSession::getFormToken() : JUtility::getToken())."=1', {
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
				jQuery('#input-tags').attr('tagid',row.id);
				jQuery('#input-tags').attr('tagname',row.name);
				addToList(row.id, row.name);
			}).keydown(function(event) {
				if((event.keyCode==13)&&(jQuery('#input-tags').attr('tagid')=='0') ) {//press enter button
					addtag(0, jQuery('#input-tags').attr('value'));
					resetField();
					return false;
				}else if(event.keyCode==13) {
					resetField();
					return false;
				}
			});
			function resetField() {
				jQuery('#input-tags').attr('tagid',0);
				jQuery('#input-tags').attr('tagname','');
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
			jQuery.ajax({ url: 'index.php?option=com_flexicontent&".$task_items."getversionlist&id=".$this->row->id."&active=".$this->row->version."&".(FLEXI_J30GE ? JSession::getFormToken() : JUtility::getToken())."=1&format=raw&page='+pageclickednumber, context: jQuery('#result'), success: function(str){
				jQuery(this).html(\"<table width='100%' class='versionlist' cellpadding='0' cellspacing='0'>\\
				<tr>\\
					<th colspan='4'>".JText::_( 'FLEXI_VERSIONS_HISTORY',true )."</th>\\
				</tr>\\
				\"+str+\"\\
				</table>\");
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
$tags_fieldname = FLEXI_J16GE ? 'jform[tag][]' : 'tag[]';

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
$infoimage    = JHTML::image ( 'administrator/templates/'.$template.'/images/flexi/lightbulb.png', JText::_( 'FLEXI_NOTES' ) );
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
  <div id="fc_modal_popup_container"></div>


    
    
    
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate" enctype="multipart/form-data" >
    <div class="container-fluid">
      <div class="row-fluid">
        <div class="m20"> 
          <!--Content-->
          <div class="row-fluid">
            <div class="span8 "> 
              <!--Main Column-->

<h1 class="contentx">


<?php if ($this->form->getValue('title') == '') {
echo 'New item';}
else {
	echo $this->form->getValue('title');
};?>
</h1>

<?php
// *****************
// MAIN TABSET START
// *****************
$tabSetCnt++;
$tabCnt[$tabSetCnt] = 0;
?>

<!-- tabber start -->
<div class="fctabber fields_tabset" id="fcform_tabset_<?php echo $tabSetCnt; ?>">
	<div class="tabbertab" id="fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>">
     <div class="m20x"> 
		<h3 class="tabberheading"><?php echo JText::_( 'FLEXI_BASIC' ); ?></h3>
		
		<?php $fset_lbl = $tags_displayed ? 'FLEXI_CATEGORIES_TAGS' : 'FLEXI_CATEGORIES';?>
		
		<div class="fcclear"></div>
<!--TAB1 CONTENT-->
<div class="form-horizontal">
 <div class="form-inline">
                    <div class="control-group">
                      <?php
				$field = $this->fields['title'];
				$field_description = $field->description ? $field->description :
					JText::_(FLEXI_J16GE ? $this->form->getField('title')->__get('description') : 'TIPTITLEFIELD');
				$label_tooltip = 'class="control-label required"';
				
			?>
                      <label id="jform_title-lbl" for="jform_title" <?php echo $label_tooltip; ?> > 
					  <?php echo $field->label;  ?> </label>
                      <?php ?>
                      <div class="controls">
                        <?php	if ( isset($this->row->item_translations) ) :?>
                        
                        <!-- tabber start -->
                        <h3 class="tabberheading"><?php echo '-'.$itemlangname.'-'; // $itemlang; ?></h3>
                        <?php echo $this->form->getInput('title');?>
                        <?php foreach ($this->row->item_translations as $t): ?>
                        <?php if ($itemlang!=$t->shortcode && $t->shortcode!='*') : ?>
                        <h3 class="tabberheading"><?php echo $t->name; // $t->shortcode; ?></h3>
                        <?php
								$ff_id = 'jfdata_'.$t->shortcode.'_title';
								$ff_name = 'jfdata['.$t->shortcode.'][title]';
								?>
                        <input class="fc_form_title fcfield_textval required span8" type="text" id="<?php echo $ff_id; ?>" name="<?php echo $ff_name; ?>" value="<?php echo @$t->fields->title->value; ?>" maxlength="254" aria-required="true" aria-invalid="true" />
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php else : ?>
                        <?php echo $this->form->getInput('title');?>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  <!--/TITLE--> 
                  <!--ALIAS-->
                  <?php
				$field_description = JText::_(FLEXI_J16GE ? $this->form->getField('alias')->__get('description') : 'ALIASTIP');
				$label_tooltip = 'class="control-label"';
			?>
                  <div class="form-inline">
                    <div class="control-group">
                      <label id="jform_alias-lbl" for="jform_alias" <?php echo $label_tooltip; ?> > <?php echo JText::_( 'FLEXI_ALIAS' ); ?> </label>
                      <div class="controls">
                        <div class="container_fcfield container_fcfield_name_alias">
                          <?php	if ( isset($this->row->item_translations) ) :?>
                          
                          <!-- tabber start -->
                          <h3 class="tabberheading"><?php echo '-'.$itemlangname.'-'; // $itemlang; ?></h3>
                          <?php echo $this->form->getInput('alias');?>
                          <?php foreach ($this->row->item_translations as $t): ?>
                          <?php if ($itemlang!=$t->shortcode && $t->shortcode!='*') : ?>
                          <h3 class="tabberheading"> <?php echo $t->name; // $t->shortcode; ?> </h3>
                          <?php
								$ff_id = 'jfdata_'.$t->shortcode.'_alias';
								$ff_name = 'jfdata['.$t->shortcode.'][alias]';
								?>
                          <input class="fc_form_alias fcfield_textval form-control span6 w10" type="text" id="<?php echo $ff_id; ?>" name="<?php echo $ff_name; ?>" value="<?php echo @$t->fields->alias->value; ?>"  maxlength="254" />
                          <?php endif; ?>
                          <?php endforeach; ?>
                          <?php else : ?>
                          <?php echo $this->form->getInput('alias');?>
                          <?php endif; ?>
                          <p class="help-block hidden-phone hidden-tablet"><small><em>Alias is the URL-friendly version of the title. It is usually all lowercase and contains only letters, numbers, and hyphens.</em></small></p>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--/ALIAS-->
         
                  <!--DOCUMENT TYPE-->
                  <?php
				$field = $this->fields['document_type'];
				$field_description = $field->description ? $field->description :
					JText::_(FLEXI_J16GE ? $this->form->getField('type_id')->__get('description') : 'FLEXI_TYPE_DESC');
				$label_tooltip = 'class="control-label required"';
			?>
                  <div class="form-inline">
                    <div class="control-group">
                      <label id="jform_type_id-lbl" for="jform_type_id" for_bck="jform_type_id" <?php echo $label_tooltip; ?> > <?php echo $field->label; ?>
                        <?php /*echo JText::_( 'FLEXI_TYPE' );*/ ?>
                      </label>
                      <?php /*echo $this->form->getLabel('type_id');*/ ?>
                      <div class="controls">
                        <div class="container_fcfield container_fcfield_id_8 container_fcfield_name_type" id="container_fcfield_8"> <?php echo $this->lists['type']; ?>
                          <?php //echo $this->form->getInput('type_id'); ?>
                          <i class="icon-info hasTip m3l" title="<?php echo htmlspecialchars(JText::_( 'FLEXI_NOTES' ), ENT_COMPAT, 'UTF-8'); ?>::<?php echo htmlspecialchars(JText::_( 'FLEXI_TYPE_CHANGE_WARNING' ), ENT_COMPAT, 'UTF-8');?>"></i>
                          <div id="fc-change-warning" class="fc-mssg fc-warning" style="display:none;"><?php echo JText::_( 'FLEXI_TAKE_CARE_CHANGING_FIELD_TYPE' ); ?></div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--DOCUMENT TYPE-->
          
                  <!--STATE-->
                  <?php
				$field = $this->fields['state'];
				$field_description = $field->description ? $field->description :
					JText::_(FLEXI_J16GE ? $this->form->getField('state')->__get('description') : 'FLEXI_STATE_DESC');
				$label_tooltip = 'class="control-label"';
			?>
                  <div class="form-inline">
                    <div class="control-group">
                      <label id="jform_state-lbl" for="jform_state" <?php echo $label_tooltip; ?> > <?php echo $field->label; ?>
                        <?php /*echo JText::_( 'FLEXI_STATE' );*/ ?>
                      </label>
                      <div class="controls">
                        <?php
			if ( $this->perms['canpublish'] ) : ?>
                        <div class="container_fcfield container_fcfield_id_10 container_fcfield_name_state fcdualline" id="container_fcfield_10" > <?php echo $this->lists['state']; ?>
                          <?php //echo $this->form->getInput('state'); ?>
                          <i class="icon-info hasTip m3l" title="<?php echo htmlspecialchars(JText::_( 'FLEXI_NOTES' ), ENT_COMPAT, 'UTF-8'); ?>::<?php echo htmlspecialchars(JText::_( 'FLEXI_STATE_DESC' ), ENT_COMPAT, 'UTF-8');?>"></i> </div>
                        <?php else :
				echo $this->published;
				echo '<input type="hidden" name="jform[state]" id="jform_vstate" value="'.$this->row->state.'" />';
			endif;
			?>
            
      
                        <?php if ( $this->perms['canpublish'] ) : ?>
                        <?php if (!$this->params->get('auto_approve', 1)) : ?>
                        <?php
					//echo "<br/>".$this->form->getLabel('vstate') . $this->form->getInput('vstate');
					$label_tooltip = 'class="control-label hasTip flexi_label fcdualline" title="'.htmlspecialchars(JText::_( 'FLEXI_PUBLIC_DOCUMENT_CHANGES' ), ENT_COMPAT, 'UTF-8').'::'.htmlspecialchars(JText::_( 'FLEXI_PUBLIC_DOCUMENT_CHANGES_DESC' ), ENT_COMPAT, 'UTF-8').'"';
				?>
                        <div style="float:left; width:50%; margin:0px; padding:0px;">
                          <label id="jform_vstate-lbl" for="jform_vstate" <?php echo $label_tooltip; ?> > <?php echo JText::_( 'FLEXI_PUBLIC_DOCUMENT_CHANGES' ); ?> </label>
                          <div class="container_fcfield container_fcfield_name_vstate fcdualline"> <?php echo $this->lists['vstate']; ?> </div>
                        </div>
                        <?php else :
				echo '<input type="hidden" name="jform[vstate]" id="jform_vstate" value="2" />';
			endif;
		elseif (!$this->params->get('auto_approve', 1)) :
			// Enable approval if versioning disabled, this make sense,
			// since if use can edit item THEN item should be updated !!!
			$item_vstate = $this->params->get('use_versioning', 1) ? 1 : 2;
			echo '<input type="hidden" name="jform[vstate]" id="jform_vstate" value="'.$item_vstate.'" />';
		else :
			echo '<input type="hidden" name="jform[vstate]" id="jform_vstate" value="2" />';
		endif;
		?>
                        <?php if ($this->subscribers) : ?>
                        <div class="fcclear"></div>
                        <?php
				$label_tooltip = 'class="control-label hasTip flexi_label" title="'.'::'.htmlspecialchars(JText::_( 'FLEXI_NOTIFY_NOTES' ), ENT_COMPAT, 'UTF-8').'"';
			?>
                        <label id="jform_notify-lbl" for="jform_notify" <?php echo $label_tooltip; ?> > <?php echo JText::_( 'FLEXI_NOTIFY_FAVOURING_USERS' ); ?> </label>
                        <div class="container_fcfield container_fcfield_name_notify"> <?php echo $this->lists['notify']; ?> </div>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  <!--/STATE--> 
                  
                  <!--BASIC-->
                       <h3 class="ruler"><i class="icon-folder m20r"></i><?php echo JText::_( 'FLEXI_CATEGORIES' ); ?></h3>
                  
                  <!--Main Category-->
                  <div class="form-inline hmaincat">
                    <div class="control-group">
                      <label id="jform_catid-lbl" for="jform_catid" for_bck="jform_catid" class="control-label  required"> <?php echo JText::_( 'FLEXI_CATEGORIES_MAIN' ); ?> </label>
                      <div class="controls">
                        <div class="container_fcfield container_fcfield_name_catid"> <?php echo $this->lists['catid']; ?> <i class="icon-info hasTip m3l" title="<?php echo htmlspecialchars(JText::_ ( 'FLEXI_NOTES' ), ENT_COMPAT, 'UTF-8'); ?>::<?php echo htmlspecialchars(JText::_ ( 'FLEXI_CATEGORIES_NOTES' ), ENT_COMPAT, 'UTF-8');?>"></i> </div>
                      </div>
                    </div>
                  </div>
                  <!--/Main Category-->
                  <?php if (1) : /* secondary categories always available in backend */ ?>
                  <div class="form-inline hmaincat">
                    <div class="control-group">
                      <label id="jform_cid-lbl" for="jform_cid" for_bck="jform_cid" class="control-label"> <?php echo JText::_( 'FLEXI_CATEGORIES' ); ?> </label>
                      <div class="controls">
                        <div class="container_fcfield container_fcfield_name_catid"> <?php echo $this->lists['cid']; ?> </div>
                      </div>
                    </div>
                  </div>
                  <?php endif; ?>
                  <?php if ( !empty($this->lists['featured_cid']) ) : ?>
                  <div class="form-inline hfeatcat">
                    <div class="control-group">
                      <label id="jform_featured_cid-lbl" for="jform_featured_cid" for_bck="jform_featured_cid" class="control-label"> <?php echo JText::_( 'FLEXI_FEATURED_CATEGORIES' ); ?> </label>
                      <div class="controls">
                        <div class="container_fcfield container_fcfield_name_featured_cid"> <?php echo $this->lists['featured_cid']; ?> </div>
                      </div>
                    </div>
                  </div>
                  <?php endif; ?>
                  <div class="form-inline hfeat">
                    <div class="control-group">
                      <label class="control-label"><?php echo JText::_( 'FLEXI_FEATURED' ); ?><br>
                        <span class="help-block hidden-phone hidden-tablet"><small><em><?php echo JText::_( 'FLEXI_JOOMLA_FEATURED_VIEW' ); ?></em></small></span></label>
                      <div class="controls">
                        <div class="container_fcfield container_fcfield_name_featured"> <?php echo $this->lists['featured']; ?>
                          <?php //echo $this->form->getInput('featured');?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php if (1) : /* tags always available in backend */ ?>
                  <!--TAGS-->
                  
                <?php
     $field = isset($this->fields['tags']) ? $this->fields['tags'] : false;
     if ($field) {
      $label_tooltip = 'class="'.$tip_class.' control-label" title="'.flexicontent_html::getToolTip(null, $field->description, 0, 1).'"';
     } else {
      $label_tooltip = 'class="control-label"';
     }
    ?>
    
             
                <h3 class="ruler htags"><i class="icon-tag m20r"></i><?php echo JText::_( 'FLEXI_TAGS' ); ?></h3>
                  <div class="form-inline htags">
                    <div class="control-group">
             <label class="control-label"><?php echo JText::_( 'FLEXI_TAGS' ); ?></label>
                      <div class="controls">
                        <div class="container_fcfield container_fcfield_name_tags">
                          <div class="qf_tagbox" id="qf_tagbox">
                            <ul id="ultagbox">
                              <?php
							$nused = count($this->usedtags);
							for( $i = 0, $nused; $i < $nused; $i++ ) {
								$tag = $this->usedtags[$i];
								if ( $this->perms['cantags'] ) {
									echo '<li class="tagitem"><span>'.$tag->name.'</span>';
									echo '<input type="hidden" name="jform[tag][]" value="'.$tag->tid.'" /><a href="javascript:;" class="deletetag" onclick="javascript:deleteTag(this);" align="right" title="'.JText::_('FLEXI_DELETE_TAG').'"></a></li>';
								} else {
									echo '<li class="tagitem plain"><span>'.$tag->name.'</span>';
									echo '<input type="hidden" name="jform[tag][]" value="'.$tag->tid.'" /></li>';
								}
							}
						?>
                            </ul>
                          </div>
                          <?php if ( $this->perms['cantags'] ) : ?>
                          <div class="clear"></div>
                          <div id="tags">
                            <label for="input-tags" class="control-label"> <?php echo JText::_( 'FLEXI_ADD_TAG' ); ?> </label>
                            <input type="text" id="input-tags" name="tagname" tagid='0' tagname='' />
                            <span id="input_new_tag"></span> <i class="icon-info hasTip m3l" title="<?php echo htmlspecialchars(JText::_( 'FLEXI_NOTES' ), ENT_COMPAT, 'UTF-8'); ?>::<?php echo htmlspecialchars(JText::_( 'FLEXI_TAG_EDDITING_FULL' ), ENT_COMPAT, 'UTF-8');?>"></i> </div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!--/TAGS-->
                  <?php endif; ?>
                  <?php if (FLEXI_FISH || FLEXI_J16GE) : ?>
                  <div class="hlang">
                    <h3 class="ruler"><i class="icon-comments-2 m20r"></i><?php echo JText::_( 'FLEXI_LANGUAGE' ); ?></h3>
                    <div class="form-inline">
                      <div class="control-group">
                         <?php /*?><?php echo $this->form->getLabel('language'); ?><?php */?> 
                        <div class="controls">
                          <div class="container_fcfield container_fcfield_name_language"> <?php echo $this->lists['languages']; ?> </div>
                        </div>
                      </div>
                    </div>
                    <?php if ( $this->params->get('enable_translation_groups') ) : ?>
                    <?php
					$label_tooltip = 'class="control-label hasTip" title="'.htmlspecialchars(JText::_( 'FLEXI_ORIGINAL_CONTENT_ITEM_DESC' ), ENT_COMPAT, 'UTF-8').'"';
				?>
                    <div class="form-inline">
                      <div class="control-group">
                        <label id="jform_lang_parent_id-lbl" for="jform_lang_parent_id" <?php echo $label_tooltip; ?> > <?php echo JText::_( 'FLEXI_ORIGINAL_CONTENT_ITEM' );?> </label>
                        <div class="controls">
                          <div class="container_fcfield container_fcfield_name_originalitem">
                            <?php if ( $this->row->id  && (substr(flexicontent_html::getSiteDefaultLang(), 0,2) == substr($this->row->language, 0,2) || $this->row->language=='*') ) : ?>
                            <br/>
                            <small><?php echo JText::_( $this->row->language=='*' ? 'FLEXI_ORIGINAL_CONTENT_ALL_LANGS' : 'FLEXI_ORIGINAL_TRANSLATION_CONTENT' );?></small>
                            <input type="hidden" name="jform[lang_parent_id]" id="jform_lang_parent_id" value="<?php echo $this->row->id; ?>" class="use_prettycheckable" />
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
                            <i class="icon-info hasTip m3l" title="<?php echo htmlspecialchars(JText::_( 'FLEXI_NOTES' ), ENT_COMPAT, 'UTF-8'); ?>::<?php echo htmlspecialchars(JText::_( 'FLEXI_ORIGINAL_CONTENT_IGNORED_IF_DEFAULT_LANG' ), ENT_COMPAT, 'UTF-8');?>"></i>
                            <?php
					} else {
						echo JText::_( 'FLEXI_ORIGINAL_CONTENT_ALREADY_SET' );
					}
					?>
                            <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
//include('development_tmp.php');
?>
                    <div class="form-inline">
                      <div class="control-group">
                        <label id="langassocs-lbl" for="langassocs" class="flexi_label" > <?php echo JText::_( 'FLEXI_ASSOC_TRANSLATIONS' );?> </label>
                        <div class="controls">
                          <div class="container_fcfield container_fcfield_name_langassocs">
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
						
						$_link  = 'index.php?option=com_flexicontent&'.$task_items.'edit&cid='. $assoc_item->id;
						$_title = htmlspecialchars(JText::_( 'FLEXI_EDIT_ASSOC_TRANSLATION' ), ENT_COMPAT, 'UTF-8').':: ['. $assoc_item->lang .'] '. htmlspecialchars($assoc_item->title, ENT_COMPAT, 'UTF-8');
						echo "<a class='fc_assoc_translation editlinktip hasTip' target='_blank' href='".$_link."' title='".$_title."' >";
						//echo $assoc_item->id;
						if ( !empty($assoc_item->lang) && !empty($this->langs->{$assoc_item->lang}->imgsrc) ) {
							echo ' <img src="'.$this->langs->{$assoc_item->lang}->imgsrc.'" alt="'.$assoc_item->lang.'" />';
						} else if( !empty($assoc_item->lang) ) {
							echo $assoc_item->lang=='*' ? JText::_("All") : $assoc_item->lang;
						}
						
						$assoc_modified = strtotime($assoc_item->modified);
						if (!$assoc_modified)  $assoc_modified = strtotime($assoc_item->created);
						if ( $assoc_modified < $row_modified ) echo "(!)";
						echo "</a>";
					}
				}
				?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php endif; /* IF enable_translation_groups */ ?>
                  </div>
                  <!--.hlang-->
                  <?php endif; /* IF language */ ?>
<!-- would like it to go to the content page
<hr>
<div class="row-fluid">
<div class="span12 text-right">
                  <div class="btn-wrapper" id="toolbar-apply">
                    <button onclick="Joomla.submitbutton('items.cancel')" class="btn btn-small"> <span class="icon-cancel"></span> Cancel</button>
                    <button onclick="Joomla.submitbutton('items.apply');window.location.href='/#fcform_tabset_0_nav_2'/;" class="btn btn-small btn-success" href="#fcform_tabset_0_nav_2"> <span class="icon-apply icon-white"></span> Save + Next</button>
                  </div>
                </div>
                </div>  -->
                  <!--/BASIC--> 
                </div><!--.form-horizontal--> 
<!--TAB1 CONTENT-->
</div> <!--/.m20x-->
	</div> <!-- end tab -->



<?php
$type_lbl = $this->row->type_id ? JText::_( 'FLEXI_ITEM_TYPE' ) . ' : ' . $this->typesselected->name : JText::_( 'FLEXI_TYPE_NOT_DEFINED' );
?>
<?php if ($this->fields && $this->row->type_id) : ?>
	
	<div class='tabbertab hcontent' id='fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>' >
    <div class="m20x">
		<h3 class="tabberheading">Content</h3>
		
        
  
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
		
		<div class="fc_edit_container_full form-horizontal">
			
			<?php
			$hidden = array('fcloadmodule', 'fcpagenav', 'toolbar');
			$noplugin = '<div class="fc-mssg fc-warning">'. JText::_( 'FLEXI_PLEASE_PUBLISH_PLUGIN' ) .'</div>';
			$row_k = 0;
			foreach ($this->fields as $field)
			{
				// SKIP backend hidden fields from this listing
				if (
					($field->iscore && $field->field_type!='maintext')  ||
					$field->parameters->get('backend_hidden')  ||
					(in_array($field->field_type, $hidden) && empty($field->html)) ||
					in_array($field->formhidden, array(2,3))
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
				}
				
				// Decide label classes, tooltip, etc
				$lbl_class = 'control-label';
				$lbl_title = '';
				// field has tooltip
				$edithelp = $field->edithelp ? $field->edithelp : 1;
				if ( $field->description && ($edithelp==1 || $edithelp==2) ) {
					 $lbl_class .= ' hasTip'.($edithelp==2 ? ' fc_tooltip_icon_bg' : '');
					 $lbl_title = '::'.htmlspecialchars($field->description, ENT_COMPAT, 'UTF-8');
				}
				// field is required
				$required = $field->parameters->get('required', 0 );
				if ($required)  $lbl_class .= ' required';
				
				// Some fields may force a container width ?
				$row_k = 1 - $row_k;
				$width = $field->parameters->get('container_width', '' );
				$width = !$width ? '' : 'width:' .$width. ($width != (int)$width ? 'px' : '');
				$container_class = "controls fcfield_row".$row_k." container_fcfield container_fcfield_id_".$field->id." container_fcfield_name_".$field->name;
				?>
				
				<div class='fcclear'></div>
				

            <div class="form-inline">
                    <div class="control-group"><label for="<?php echo (FLEXI_J16GE ? 'custom_' : '').$field->name;?>" for_bck="<?php echo (FLEXI_J16GE ? 'custom_' : '').$field->name;?>" class="<?php echo $lbl_class;?>" title="<?php echo $lbl_title;?>" >
					<?php echo $field->label; ?>
				</label>
				
				<div style="<?php echo $width; ?>;" class="<?php echo $container_class;?>" id="container_fcfield_<?php echo $field->id;?>">
					<?php echo ($field->description && $edithelp==3) ? '<div class="fc_mini_note_box">'.$field->description.'</div>' : ''; ?>
					
				<?php // CASE 1: CORE 'description' FIELD with multi-tabbed editing of joomfish (J1.5) or falang (J2.5+)
					if ($field->field_type=='maintext' && isset($this->row->item_translations) ) : ?>
					
					<!-- tabber start -->
					<div class="fctabber" style=''>
						<div class="tabbertab">
							<h3 class="tabberheading"> <?php echo '- '.$itemlangname.' -'; // $t->name; ?> </h3>
							<?php
								$field_tab_labels = & $field->tab_labels;
								$field_html       = & $field->html;
								echo !is_array($field_html) ? $field_html : flexicontent_html::createFieldTabber( $field_html, $field_tab_labels, "");
							?>
						</div>
						<?php foreach ($this->row->item_translations as $t): ?>
							<?php if ($itemlang!=$t->shortcode && $t->shortcode!='*') : ?>
								<div class="tabbertab" >
									<h3 class="tabberheading"> <?php echo $t->name; // $t->shortcode; ?> </h3>
									<?php
									$field_tab_labels = & $t->fields->text->tab_labels;
									$field_html       = & $t->fields->text->html;
									echo !is_array($field_html) ? $field_html : flexicontent_html::createFieldTabber( $field_html, $field_tab_labels, "");
									?>
								</div>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
					<!-- tabber end -->
					
                   
				<?php elseif ( !is_array($field->html) ) : /* CASE 2: NORMAL FIELD non-tabbed */ ?>
					
					<?php echo isset($field->html) ? $field->html : $noplugin; ?>
					
				<?php else : /* MULTI-TABBED FIELD e.g textarea, description */ ?>
					
					<!-- tabber start -->
					<div class="fctabber">
					<?php foreach ($field->html as $i => $fldhtml): ?>
						<?php
							// Hide field when it has no label, and skip creating tab
							$not_in_tabs .= !isset($field->tab_labels[$i]) ? "<div style='display:none!important'>".$field->html[$i]."</div>" : "";
							if (!isset($field->tab_labels[$i]))	continue;
						?>
								
						<div class="tabbertab">
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
					<?php echo $not_in_tabs;      // Output ENDING hidden fields, by placing them outside the tabbing area ?>
					
                
				<?php endif; /* END MULTI-TABBED FIELD */ ?>
			            </div>
                    </div>	             
				</div>
				
			<?php
			}
			?>
			
		</div>

	
<?php else : /* NO TYPE SELECTED */ ?>

	<div class='tabbertab' id='fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>' >
		<h3 class="tabberheading"> <?php echo $type_lbl; ?> </h3>
		
		<div class="fc_edit_container_full">
			<?php if ($this->row->id == 0) : ?>
				<input name="jform[type_id_not_set]" value="1" type="hidden" />
				<div class="fc-mssg fc-note"><?php echo JText::_( 'FLEXI_CHOOSE_ITEM_TYPE' ); ?></div>
			<?php else : ?>
				<div class="fc-mssg fc-warning"><?php echo JText::_( 'FLEXI_NO_FIELDS_TO_TYPE' ); ?></div>
			<?php	endif; ?>
		</div>
		
	</div> <!-- end tab -->
	
<?php	endif; ?>

</div><!--/.m20x -->
	</div> <!-- end tab -->
	
	
	
	
	<div class='tabbertab hseo' id='fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>' >
    <div class="m20x">
		<h3 class="tabberheading"> <?php echo JText::_('FLEXI_META_SEO'); ?> </h3>
		
		<?php
		//echo $this->form->getLabel('metadesc');
		//echo $this->form->getInput('metadesc');
		//echo $this->form->getLabel('metakey');
		//echo $this->form->getInput('metakey');]
		//$tabSetCnt++;
//$tabCnt[$tabSetCnt] = 0;
		?>
		<div class="form-horizontal">
		<fieldset class="panelform params_set">
			<legend>
				<?php echo JText::_( 'FLEXI_META' ); ?>
			</legend>
			
         <div class="form-inline hmetad">
                    <div class="control-group">
			<?php echo $this->form->getLabel('metadesc'); ?>

			<div class="controls">
				
				<?php	if ( isset($this->row->item_translations) ) : ?>
					
					<!-- tabber start -->
					<div class="fctabber" style='display:inline-block;'>
						<div class="tabbertab" style="padding: 0px;" >
							<h3 class="tabberheading"> <?php echo '-'.$itemlang.'-'; // $t->name; ?> </h3>
							<?php echo $this->form->getInput('metadesc');?>
						</div>
						<?php foreach ($this->row->item_translations as $t): ?>
							<?php if ($itemlang!=$t->shortcode && $t->shortcode!='*') : ?>
								<div class="tabbertab" style="padding: 0px;" >
									<h3 class="tabberheading"> <?php echo $t->shortcode; // $t->name; ?> </h3>
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
				
				<?php else : ?>
					<?php echo $this->form->getInput('metadesc'); ?>
				<?php endif; ?>
				
			</div></div></div>
			
		
            
                    <div class="form-inline hkey">
                    <div class="control-group">
			<?php echo $this->form->getLabel('metakey'); ?>
			
			<div class="controls">
				<?php	if ( isset($this->row->item_translations) ) :?>
					
					<!-- tabber start -->
					<div class="fctabber">
						<div class="tabbertab">
							<h3 class="tabberheading"> <?php echo '-'.$itemlang.'-'; // $t->name; ?> </h3>
							<?php echo $this->form->getInput('metakey');?>
						</div>
						<?php foreach ($this->row->item_translations as $t): ?>
							<?php if ($itemlang!=$t->shortcode && $t->shortcode!='*') : ?>
								<div class="tabbertab" style="padding: 0px;" >
									<h3 class="tabberheading"> <?php echo $t->shortcode; // $t->name; ?> </h3>
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
					
				<?php else : ?>
					<?php echo $this->form->getInput('metakey'); ?>
				<?php endif; ?>
				
				</div>
</div></div>
			<?php foreach($this->form->getGroup('metadata') as $field): ?>
				<?php if ($field->hidden): ?>
					<span style="visibility:hidden !important;">
						<?php echo $field->input; ?>
					</span>
				<?php else: ?>
              <div class="form-inline">
                    <div class="control-group">
					<?php echo $field->label; ?>
					<div class="controls">
						<?php echo $field->input;?>
					</div>
                    </div></div>
				<?php endif; ?>
			<?php endforeach; ?>
		</fieldset>
		
		<fieldset class="panelform params_set">
			<legend>
				<?php echo JText::_( 'FLEXI_SEO' ); ?>
			</legend>
			
			<?php foreach ($this->form->getFieldset('params-seoconf') as $field) : ?>
			
                             <div class="form-inline">
                    <div class="control-group">
				<?php echo $field->label; ?>
				<div class="controls">
					<?php echo $field->input;?>
				</div></div></div>
			<?php endforeach; ?>
			
		</fieldset>
	</div><!--.m20x-->
    </div> <!-- endform horizontal-->
	</div> <!-- end tab -->
	
	
	<div class='tabbertab' id='fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>' >
    <div class="m20x">
		<h3 class="tabberheading"> <?php echo JText::_('FLEXI_DISPLAYING'); ?> </h3>
		
		<?php //echo JHtml::_('sliders.start','plugin-sliders-'.$this->row->id, array('useCookie'=>1)); ?>

		<?php
			$fieldSets = $this->form->getFieldsets('attribs');
			foreach ($fieldSets as $name => $fieldSet) :
				if ( $name=='themes' || $name=='params-seoconf'  || $name=='images' ||  $name=='urls' ) continue;

				//$label = !empty($fieldSet->label) ? $fieldSet->label : 'FLEXI_'.$name.'_FIELDSET_LABEL';
				//echo JHtml::_('sliders.panel', JText::_($label), $name.'-options');
				?>
				<fieldset class="flexi_params panelform form-horizontal">
                
<?php

$SetCnt=0;

?>  
     <?php foreach ($this->form->getFieldset($name) as $field) : ?>
	                       <div class="form-inline h-<?php echo $SetCnt++; ?>  ">
                        <div class="control-group">
						<?php echo $field->label; ?>
                        <div class="controls">
						<?php if (strlen(trim($field->input))) :?>
							<div class="container_fcfield">
								<?php echo $field->input; ?>
							</div>
						<?php endif; ?>
                        </div>
                        </div>
                        </div>
					<?php endforeach; ?>
				</fieldset>
		<?php endforeach; ?>

		<?php	//echo JHtml::_('sliders.end'); ?>
		

		<h3> <?php echo JText::_('FLEXI_TEMPLATE'); ?> </h3>
		
		<fieldset class="fc_edit_container_full">
			<div class="fc-note fc-mssg-inline">
			<?php
				echo JText::_( 'FLEXI_PARAMETERS_LAYOUT_EXPLANATION' ) ;
				$type_default_layout = $this->tparams->get('ilayout');
			?>
				<br/><br/>
				<ol>
					<li> Select TEMPLATE layout </li>
					<li> Open slider with TEMPLATE (layout) PARAMETERS </li>
				</ol>
				<br/>
				<b>NOTE:</b> Common method for -displaying- fields is by <b>editing the template layout</b> in template manager and placing the fields into <b>template positions</b>
			</div>
			
			<?php foreach($this->form->getFieldset('themes') as $field): ?>
				<div class="fcclear"></div>
				<?php if ($field->hidden): ?>
					<span style="visibility:hidden !important;">
						<?php echo $field->input; ?>
					</span>
				<?php else: ?>
                <div class="form-inline h-<?php echo $SetCnt++; ?>  ">
                        <div class="control-group">
					<?php echo $field->label; ?>
					<div class="controls">
						<?php echo $field->input;?>
					</div></div></div>
				<?php endif; ?>
			<?php endforeach; ?>

			<div class="fcclear"></div>
			<div class="fc-success fc-mssg-inline" id="content_type_default_layout">
				<?php echo JText::sprintf( 'FLEXI_USING_CONTENT_TYPE_LAYOUT', $type_default_layout ); ?>
				<?php echo "<br/><br/>". JText::_( 'FLEXI_RECOMMEND_CONTENT_TYPE_LAYOUT' ); ?>
			</div>
			<div class="fcclear"></div>
			
			<?php
			echo JHtml::_('sliders.start','theme-sliders-'.$this->form->getValue("id"), array('useCookie'=>1));
			$groupname = 'attribs';  // Field Group name this is for name of <fields name="..." >
			
			foreach ($this->tmpls as $tmplname => $tmpl) :
				$fieldSets = $tmpl->params->getFieldsets($groupname);
				foreach ($fieldSets as $fsname => $fieldSet) :
					$label = !empty($fieldSet->label) ? $fieldSet->label : JText::_( 'FLEXI_PARAMETERS_THEMES_SPECIFIC' ) . ' : ' . $tmpl->name;
					echo JHtml::_('sliders.panel',JText::_($label), $tmpl->name.'-'.$fsname.'-options');
					if (isset($fieldSet->description) && trim($fieldSet->description)) :
						echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
					endif;
					?>
					<fieldset class="panelform form-horizontal">
						<?php foreach ($tmpl->params->getFieldset($fsname) as $field) :
							
							$fieldname =  $field->__get('fieldname');
							$value = $tmpl->params->getValue($fieldname, $groupname, $this->row->itemparams->get($fieldname));
							echo '<div class="control-group">';
							echo $tmpl->params->getLabel($fieldname, $groupname);
							echo '<div class="controls">';
							echo
								str_replace('jform_attribs_', 'jform_layouts_'.$tmpl->name.'_', 
									str_replace('[attribs]', '[layouts]['.$tmpl->name.']',
										$tmpl->params->getInput($fieldname, $groupname, $value)
									)
								);
							echo '</div></div>';
						endforeach; ?>
					</fieldset>
				<?php endforeach; //fieldSets ?>
			<?php endforeach; //tmpls ?>
			
			<?php echo JHtml::_('sliders.end'); ?>
		</fieldset>
		
</div>
	</div> <!-- end tab -->
	
	
	<?php 
	// *********************
	// JOOMLA IMAGE/URLS TAB
	// *********************
	if (JComponentHelper::getParams('com_content')->get('show_urls_images_backend', 0) ) : ?>
		<div class='tabbertab hcomp' id='fcform_tabset_<?php echo $tabSetCnt; ?>_tab_<?php echo $tabCnt[$tabSetCnt]++; ?>' >
			 <div class="m20x form-horizontal">
            <h3 class="tabberheading"> <?php echo JText::_('Compatibility'); ?> </h3>
			
			<?php
			$fields_grps_compatibility = array('images', 'urls');
			foreach ($fields_grps_compatibility as $name => $fields_grp_name) :
			?>
	
			<fieldset class="flexi_params fc_edit_container_full">
				<?php foreach ($this->form->getGroup($fields_grp_name) as $field) : ?>
								<?php if ($field->hidden): ?>
						<span style="visibility:hidden !important;">
							<?php echo $field->input; ?>
						</span>
					<?php else: ?>
                    <div class="form-inline">
                    <div class="control-group">
						<?php echo $field->label; ?>
						<div class="controls">
							<?php echo $field->input;?>
						</div></div></div>
					<?php endif; ?>
				<?php endforeach; ?>
			</fieldset>
			
			<?php endforeach; ?>
		</div>	
		</div>
	<?php endif;
	?>
	
	
	
	

<?php
// ***************
// MAIN TABSET END
// ***************
?>
</div> <!-- end of tab set -->
				
	</div><!-- /.span9 --><div class="span4 mline">			
			<div class="clear"></div>
            
            <div class="panel-group accordion accordion-semi" id="accordion2">
<!--PANEL-->
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
      <i class="fa icon-arrow-right"></i> <i class="icon-calendar">&nbsp;</i> <?php echo JText::_('FLEXI_PUBLISHING'); ?>   </a></h4>
    </div>
    <div id="collapseOne" class="accordion-body in collapse">
      <div class="panel-body form-horizontal">
        <!--CONTENT-->
        <?php
		$hide_style = $this->perms['canparams'] ? '' : 'visibility:hidden;';
		/*if (isset($fieldSet->description) && trim($fieldSet->description)) :
			echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
		endif;*/
		?>
		
		<fieldset class="flexi_params fc_edit_container_full" style="<?php echo $hide_style; ?>" >

			
			
			<?php /*if ($this->perms['isSuperAdmin']) :*/ ?>
			<div class="form-inline"><div class="control-group"><?php echo $this->form->getLabel('created_by'); ?>
			<div class="controls"><?php echo $this->form->getInput('created_by'); ?></div></div></div>
			<?php /*endif;*/ ?>
			
			<?php if ($this->perms['editcreationdate']) : ?>
			<div class="form-inline"><div class="control-group"><?php echo $this->form->getLabel('created'); ?>
			<div class="controls"><?php echo $this->form->getInput('created'); ?></div></div></div>
			<?php endif; ?>
			
			<div class="form-inline"><div class="control-group"><?php echo $this->form->getLabel('created_by_alias'); ?>
			<div class="controls"><?php echo $this->form->getInput('created_by_alias'); ?></div></div></div>
			
			<div class="form-inline"><div class="control-group"><?php echo $this->form->getLabel('publish_up'); ?>
			<div class="controls"><?php echo $this->form->getInput('publish_up'); ?></div></div></div>
			
			<div class="form-inline"><div class="control-group"><?php echo $this->form->getLabel('publish_down'); ?>
			<div class="controls"><?php echo $this->form->getInput('publish_down'); ?></div></div></div>
			
			<div class="form-inline"><div class="control-group"><?php echo $this->form->getLabel('access'); ?>
			<?php if ($this->perms['canacclvl']) :?>
				<div class="controls"><?php echo $this->form->getInput('access'); ?></div></div></div>
			<?php else :?>
				<div class="controls"><span class="label"><?php echo $this->row->access_level; ?></span></div>
			<?php endif; ?>

		</fieldset>
        <!--/CONTENT-->
        
							      </div>
    </div>
  </div>
<!--/PANEL--> 
<!--PANEL2--> 
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title"><a class="collapsed collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
       <i class="fa icon-arrow-right"></i> <i class="icon-info"></i> Info</a></h4>
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
		<table class="responsive no-strip plain-label">
		<?php
		if ( $this->row->id ) {
		?>
		<tr>
			<td>
				<span class="label"><?php echo JText::_( 'FLEXI_ITEM_ID' ); ?></span>
			</td>
			<td>
				<?php echo $this->row->id; ?>
			</td>
		</tr>
		<?php
		}
		?>
		<tr>
			<td>
				<?php
					$field = isset($this->fields['state']) ? $this->fields['state'] : false;
					if ($field) {
						$label_tooltip = 'class="'.$tip_class.' label" title="'.flexicontent_html::getToolTip(null, $field->description, 0, 1).'"';
					} else {
						$label_tooltip = 'class="label"';
					}
				?>
				<span <?php echo $label_tooltip; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_STATE' ); ?></span>
			</td>
			<td>
				<?php echo $this->published;?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
					$field = isset($this->fields['hits']) ? $this->fields['hits'] : false;
					if ($field) {
						$label_tooltip = 'class="'.$tip_class.' label" title="'.flexicontent_html::getToolTip(null, $field->description, 0, 1).'"';
					} else {
						$label_tooltip = 'class="label"';
					}
				?>
				<span <?php echo $label_tooltip; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_HITS' ); ?></span>
			</td>
			<td>
				<div id="hits" style="float:left;"></div> &nbsp;
				<span <?php echo $visibility; ?>>
					<input name="reset_hits" type="button" class="button" value="<?php echo JText::_( 'FLEXI_RESET' ); ?>" onclick="reseter('<?php echo $ctrl_items; ?>resethits', '<?php echo $this->row->id; ?>', 'hits')" />
				</span>
			</td>
		</tr>
		<tr>
			<td>
				<?php
					$field = isset($this->fields['voting']) ? $this->fields['voting'] : false;
					if ($field) {
						$label_tooltip = 'class="'.$tip_class.' label" title="'.flexicontent_html::getToolTip(null, $field->description, 0, 1).'"';
					} else {
						$label_tooltip = 'class="label"';
					}
				?>
				<span <?php echo $label_tooltip; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_SCORE' ); ?></span>
			</td>
			<td>
				<div id="votes" style="float:left;"></div> &nbsp;
				<span <?php echo $visibility2; ?>>
					<input name="reset_votes" type="button" class="button" value="<?php echo JText::_( 'FLEXI_RESET' ); ?>" onclick="reseter('<?php echo $ctrl_items; ?>resetvotes', '<?php echo $this->row->id; ?>', 'votes')" />
				</span>
			</td>
		</tr>
		<tr>
			<td>
				<?php
					$field = isset($this->fields['modified']) ? $this->fields['modified'] : false;
					if ($field) {
						$label_tooltip = 'class="'.$tip_class.' label" title="'.flexicontent_html::getToolTip(null, $field->description, 0, 1).'"';
					} else {
						$label_tooltip = 'class="label"';
					}
				?>
				<span <?php echo $label_tooltip; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_REVISED' ); ?></span>
			</td>
			<td>
				<?php echo $this->row->last_version;?> <?php echo JText::_( 'FLEXI_TIMES' ); ?>
			</td>
		</tr>
		<tr>
			<td>
				<span class="label"><?php echo JText::_( 'FLEXI_FRONTEND_ACTIVE_VERSION' ); ?></span>
			</td>
			<td>
				#<?php echo $this->row->current_version;?>
			</td>
		</tr>
		<tr>
			<td>
				<span class="label"><?php echo JText::_( 'FLEXI_FORM_LOADED_VERSION' ); ?></span>
			</td>
			<td>
				#<?php echo $this->row->version;?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
					$field = isset($this->fields['created']) ? $this->fields['created'] : false;
					if ($field) {
						$label_tooltip = 'class="'.$tip_class.' label" title="'.flexicontent_html::getToolTip(null, $field->description, 0, 1).'"';
					} else {
						$label_tooltip = 'class="label"';
					}
				?>
				<span <?php echo $label_tooltip; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_CREATED' ); ?></span>
			</td>
			<td>
				<?php
				if ( $this->row->created == $this->nullDate ) {
					echo JText::_( 'FLEXI_NEW_ITEM' );
				} else {
					echo JHTML::_('date',  $this->row->created,  JText::_( 'DATE_FORMAT_LC2' ) );
				}
				?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
					$field = isset($this->fields['modified']) ? $this->fields['modified'] : false;
					if ($field) {
						$label_tooltip = 'class="'.$tip_class.' label" title="'.flexicontent_html::getToolTip(null, $field->description, 0, 1).'"';
					} else {
						$label_tooltip = 'class="label"';
					}
				?>
				<span <?php echo $label_tooltip; ?>><?php echo $field ? $field->label : JText::_( 'FLEXI_MODIFIED' ); ?></span>
			</td>
			<td>
				<?php
					if ( $this->row->modified == $this->nullDate ) {
						echo JText::_( 'FLEXI_NOT_MODIFIED' );
					} else {
						echo JHTML::_('date',  $this->row->modified, JText::_( 'DATE_FORMAT_LC2' ));
					}
				?>
			</td>
		</tr>
		</table>

</div>
    </div>
  </div>
  <!--/PANEL2-->
  
  <!--PANEL3--> 
  <div class="panel panel-default">
    <div class="panel-heading">
      <h4 class="panel-title"><a class="collapsed collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
       <i class="fa icon-arrow-right"></i> <i class="icon-clock"></i> Revisions</a></h4>
    </div>
    <div id="collapseThree" class="accordion-body collapsed collapse">
      <div class="panel-body form-horizontal">
     			
                	<?php if ($this->params->get('use_versioning', 1)) : ?>
                    <h3 class="text-center p10"><?php echo JText::_( 'FLEXI_VERSION_COMMENT' ); ?></h3>
                    <div class="row-fluid">
                    <div class="span12">
                    <div class="m20x text-center">
<textarea name="jform[versioncomment]" id="versioncomment" rows="5" cols="32"></textarea>
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
				<td class="versions text-center"><a href="javascript:;" class="hasTip" title="Comment::<?php echo htmlspecialchars($version->comment, ENT_COMPAT, 'UTF-8');?>"><?php echo $commentimage;?></a><?php
				if((int)$version->nr==(int)$this->row->current_version) { ?>
					<a onclick="javascript:return clickRestore('index.php?option=com_flexicontent&view=item&<?php echo $task_items;?>edit&cid=<?php echo $this->row->id;?>&version=<?php echo $version->nr; ?>');" href="#"><?php echo JText::_( 'FLEXI_CURRENT' ); ?></a>
				<?php }else{
				?>
					<a class="modal-versions"
						href="index.php?option=com_flexicontent&view=itemcompare&cid=<?php echo $this->row->id; ?>&version=<?php echo $version->nr; ?>&tmpl=component"
						title="<?php echo JText::_( 'FLEXI_COMPARE_WITH_CURRENT_VERSION' ); ?>"
					>
						<?php echo $viewimage; ?>
					</a>
					<a onclick="javascript:return clickRestore('index.php?option=com_flexicontent&task=items.edit&cid=<?php echo $this->row->id; ?>&version=<?php echo $version->nr; ?>&<?php echo (FLEXI_J30GE ? JSession::getFormToken() : JUtility::getToken());?>=1');"
						href="javascript:;"
						title="<?php echo JText::sprintf( 'FLEXI_REVERT_TO_THIS_VERSION', $version->nr ); ?>"
					>
						<?php echo $revertimage; ?>
					</a>
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
	
		</td>
	</tr>
</table>
</div>
    </div>
  </div>
  <!--/PANEL3-->
 
</div>
<!--/ACCORDION-->
<!--PUBLISHING-->




<?php echo JHTML::_( 'form.token' ); ?>
<input type="hidden" name="option" value="com_flexicontent" />
<input type="hidden" name="jform[id]" value="<?php echo $this->row->id; ?>" />
<input type="hidden" name="controller" value="items" />
<input type="hidden" name="view" value="item" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="unique_tmp_itemid" value="<?php echo JRequest::getVar( 'unique_tmp_itemid' );?>" />
<?php echo $this->form->getInput('hits'); ?>
<!--CONTENT-->
</div></div></div></div>
</form>

</div>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>
