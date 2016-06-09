<?php
/**
 * @version 1.5 stable $Id: default.php 1832 2014-01-17 00:17:27Z ggppdk $
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

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.filesystem.path');

if ( !$this->layout->name ) die('Template folder does not exist');

// Load JS tabber lib
$this->document->addScriptVersion(JURI::root(true).'/components/com_flexicontent/assets/js/tabber-minimized.js', FLEXI_VHASH);
$this->document->addStyleSheetVersion(JURI::root(true).'/components/com_flexicontent/assets/css/tabber.css', FLEXI_VHASH);
$this->document->addScriptDeclaration(' document.write(\'<style type="text/css">.fctabber{display:none;}<\/style>\'); ');  // temporarily hide the tabbers until javascript runs

$tip_class = FLEXI_J30GE ? ' hasTooltip' : ' hasTip';
$btn_class = FLEXI_J30GE ? 'btn' : 'fc_button fcsimple';

$app = JFactory::getApplication();
$db = JFactory::getDbo();
JFactory::getApplication()->setUserState('editor.source.syntax', 'css');

$code_btn_lbls = array(
	'fieldPosXML'=>'FLEXI_ADD_FIELD_POSITION_XML',
	'paramTextXML'=>'FLEXI_ADD_PARAMETER_TEXT_XML',
	'paramRadioXML'=>'FLEXI_ADD_PARAMETER_RADIO_XML',
	'paramSelectXML'=>'FLEXI_ADD_PARAMETER_SELECT_XML',
	'itemPosHTML'=>'FLEXI_ADD_FIELD_POSITION_PHP',
	'catPosHTML'=>'FLEXI_ADD_FIELD_POSITION_PHP',
	'itemFieldDisplay'=>'FLEXI_ADD_FIELD_DISPLAY',
	'catFieldDisplay'=>'FLEXI_ADD_FIELD_DISPLAY'
);
$code_btn_tips = array(
	'fieldPosXML'=>'Please <br/>- place new position <b>inside &lt;fieldgroup&gt; &lt;/fieldgroup&gt;</b>, <br/>- <b>set a name</b> for the field position and remember to use same name inside your item.php or category_items.php',
	'paramTextXML'=>'Please <br/>- place new parameter <b>inside &lt;fields ...&gt; &lt;/fields&gt;</b>, <br/>- <b>set the name</b> for the parameter and customize it, <br/>- make sure you prefix it with e.g. my_ to have a unique name <br/>- to use it add inside PHP files: <br/>if ( $item->parameters->get(\'my_param01\')) { /* do ... */ }',
	'paramRadioXML'=>'Please <br/>- place new parameter <b>inside &lt;fields ...&gt; &lt;/fields&gt;</b>, <br/>- <b>set the name</b> for the parameter and customize it, <br/>- make sure you prefix it with e.g. my_ to have a unique name <br/>- to use it add inside PHP files: <br/>if ( $item->parameters->get(\'my_param01\')) { /* do ... */ }',
	'paramSelectXML'=>'Please <br/>- place new parameter <b>inside &lt;fields ...&gt; &lt;/fields&gt;</b>, <br/>- <b>set the name</b> for the parameter and customize it, <br/>- make sure you prefix it with e.g. my_ to have a unique name <br/>- to use it add inside PHP files: <br/>if ( $item->parameters->get(\'my_param01\')) { /* do ... */ }',
	'itemPosHTML'=>'This code will <b>loop through a set</b> of fields added to a field position place and display them<br/>- place it outside the code of other position <br/>- be careful not break PHP or HTML',
	'catPosHTML'=>'This code will <b>loop through a set</b> of fields added to a field position place and display them<br/>- place it outside the code of other position <br/>- be careful not break PHP or HTML',
	'itemFieldDisplay'=>'This code will display a single field manually, NOTE:<br/>- You need to add this field to the renderonly position, in the "Field\'s placement" TAB<br/>- be careful not break PHP or HTML',
	'catFieldDisplay'=>'This code will display a single field manually, NOTE:<br/>- You need to add this field to the renderonly position, in the "Field\'s placement" TAB<br/>- be careful not break PHP or HTML'
);

$code_btn_rawcode = array(
'fieldPosXML' => '
<group>myposition</group>
',

'paramTextXML' => '
<field name="my_param01" type="text" size="10" default="Default value of parameter" label="Label of parameter" description="Description of parameter" />
',

'paramRadioXML' => '
<field name="my_param01" type="radio" default="two" label="Label of parameter" description="Description of parameter" class="btn-group btn-group-yesno">
	<option value="one">Label of first value</option>
	<option value="two">Label of second value</option>
</field>
',
'paramSelectXML' => '
<field name="my_param01" type="list" default="2" label="Label of parameter" description="Description of parameter" class="btn-group btn-group-yesno">
	<option value="1">Case 1</option>
	<option value="2">Case 2</option>
	<option value="3">Case 3</option>
	<option value="4">Case 4</option>
</field>
',

'itemPosHTML' => '
<!-- BOF myposition block -->
<?php $_position_name = "myposition"; ?>

<?php if (isset($item->positions[$_position_name])) : /* IF position has fields */ ?>
<div class="flexi lineinfo <?php echo $_position_name; ?> group">
	
		<?php foreach ($item->positions[$_position_name] as $field) : /* LOOP through fields of the position */?>
		<div class="flexi element field_<?php echo $field->name; ?>">
		
			<?php if ($field->label) : /* Display label according to configuration */ ?>
				<span class="flexi label field_<?php echo $field->name; ?>">
					<?php echo $field->label; ?>
				</span>
			<?php endif; ?>
			
			<div class="flexi value field_<?php echo $field->name; ?>">
				<?php echo $field->display; ?>
			</div>
			
		</div>
	<?php endforeach; ?>
		
</div>
<?php endif; ?>

<!-- EOF myposition block -->
',

'itemFieldDisplay' => '
<?php echo $item->fields["fieldname"]->display; ?>
');

// Case similar to above
$code_btn_rawcode['catPosHTML'] = $code_btn_rawcode['itemPosHTML'];  // Currently same as item
$code_btn_rawcode['catFieldDisplay'] = $code_btn_rawcode['itemFieldDisplay'];    // Currently same as item



// Codemirror should be enabled
$query = $db->getQuery(true)
	->select('COUNT(*)')
	->from('#__extensions as a')
	->where(
		'(a.name =' . $db->quote('plg_editors_codemirror') . ' AND a.enabled = 1) '
		//' OR (a.name =' . $db->quote('plg_editors_none')   . ' AND a.enabled = 1) '
	);
$db->setQuery($query);
$use_editor = (boolean)$db->loadResult();
if (!$use_editor)  $app->enqueueMessage(JText::_('Codemirror is disabled, please enable, simple textarea will be used for editting files'), 'warning');

?>

<script type="text/javascript">
	function tmpls_fcfield_init_ordering() {
	<?php echo $this->jssort . ';' ; ?>
	}
	
	var code_box_cnt = 0;
	function toggle_code_inputbox(btn)
	{
		var _btn = jQuery(btn);
		var el=jQuery(btn).next().next();
		
		var becomes_visible = ! el.is(':visible');
		el.toggle();
		becomes_visible ? code_box_cnt++ : code_box_cnt--;
		if (code_box_cnt<0) code_box_cnt = 0;
		
		if (becomes_visible) {
			_btn.addClass('btn-info').find('span').removeClass('icon-eye').addClass('icon-eye-close');
			el.get(0).select();
			el.prev().show(400);
		} else {
			_btn.removeClass('btn-info').find('span').removeClass('icon-eye-close').addClass('icon-eye');
			el.prev().hide(400);
		}
	}
	
	function set_editor_contents(txtarea, theData)
	{
		var CM = txtarea.next();//.get(0).CodeMirror;
		if (CM.hasClass('CodeMirror')) {
			CM.get(0).CodeMirror.toTextArea();
			txtarea.val(theData.content);
			txtarea.attr('form', 'layout_file_editor_form');
			txtarea.show();
			CM = CodeMirror.fromTextArea(txtarea.get(0),
			{
				mode: "application/x-httpd-php",
				indentUnit: 2,
				lineNumbers: true,
				matchBrackets: true,
				lineWrapping: true,
				onCursorActivity: function() 
				{
					CM.setLineClass(hlLine, null);
					hlLine = CM.setLineClass(CM.getCursor().line, "activeline");
				}	
			});
		} else {
			txtarea.val(theData.content);
			txtarea.show();
		}
	}
	
	function save_layout_file(formid)
	{
		var form = jQuery('#'+formid);
		var layout_name  = jQuery('#editor__layout_name').val();
		var file_subpath = jQuery('#editor__file_subpath').val();
		if (file_subpath=='') {
			alert(<?php echo "'".JText::_('FLEXI_TMPLS_LOAD_FILE_BEFORE_SAVING', true)."'"; ?>);
			return;
		}
		
		<?php
		if (in_array($this->layout->name, array('blog','default','faq','items-tabbed','presentation'))) {
			echo 'if (!confirm("'.JText::_('FLEXI_TMPLS_SAVE_BUILT_IN_TEMPLATE_FILE_WARNING', true).'")) return false;';
		}
		?>
		
		txtarea = jQuery('#editor__file_contents');
		txtarea.before('<span id="fc_doajax_loading"><img src="components/com_flexicontent/assets/images/ajax-loader.gif" align="center" /> ... <?php echo JText::_("FLEXI_SAVING");?><br/></span>');
		
		// Set the current codemirror data into the textarea before serializing and submit the form via AJAX
		var CM = txtarea.next();//.get(0).CodeMirror;
		if (CM.hasClass('CodeMirror')) {
			var file_contents = CM.get(0).CodeMirror.getValue();
			txtarea.val(file_contents);
		}
		
		jQuery.ajax({
			type: "POST",
			url: "index.php?option=com_flexicontent&task=templates.savelayoutfile&format=raw",
			data: form.serialize(),
			success: function (data) {
				jQuery('#fc_doajax_loading').remove();
				var theData = jQuery.parseJSON(data);
				jQuery('#ajax-system-message-container').html(theData.sysmssg);
				if (!theData.content) return;  // Saving task may return modified data, if so set them into the editor
				set_editor_contents(txtarea, theData);
			}
		});
	}
	
	function load_layout_file(layout_name, file_subpath, load_mode, btn_classes)
	{
		var layout_name  = (typeof layout_name != "undefined"  && layout_name!='')  ? layout_name  : jQuery('#editor__layout_name').val();
		var file_subpath = (typeof file_subpath != "undefined" && file_subpath!='') ? file_subpath : jQuery('#editor__file_subpath').val();
		var btn_classes = (typeof btn_classes != "undefined") ? btn_classes : '';
		if (btn_classes=='-1') btn_classes = jQuery('#editor__btn_classes').val();
		
		var load_mode = (typeof load_mode != "undefined") ? load_mode : 0;
		var form = jQuery('#layout_file_editor_form');
		
		jQuery('#editor__layout_name').val(layout_name);
		jQuery('#editor__file_subpath').val(file_subpath);
		jQuery('#editor__load_mode').val(load_mode);
		jQuery('#editor__btn_classes').val(btn_classes);
		
		jQuery('.code_box').hide();
		btn_classes = btn_classes!='' ? btn_classes.split(" ") : Array();
		jQuery.each( btn_classes, function( cname, val ) {
			jQuery('.'+val).show();
		});
		if (btn_classes.length)
			jQuery('#code_box_header').css('display', '');
		else
			jQuery('#code_box_header').css('display', 'none');
		
		if (load_mode == '2') {
			form.submit();
			return;
		}
		
		txtarea = jQuery('#editor__file_contents');
		txtarea.before('<span id="fc_doajax_loading"><img src="components/com_flexicontent/assets/images/ajax-loader.gif" align="center" /> ... <?php echo JText::_("FLEXI_LOADING");?><br/></span>');
		txtarea.hide();
		jQuery('#layout_edit_name_container').html(file_subpath);
		
		jQuery.ajax({
			type: form.attr('method'),
			url: form.attr('action'),
			data: { layout_name: layout_name, file_subpath: file_subpath, load_mode: load_mode, '<?php echo (FLEXI_J30GE ? JSession::getFormToken() : JUtility::getToken());?>': 1 },
			success: function (data) {
				jQuery('#fc_doajax_loading').remove();
				var theData = jQuery.parseJSON(data);
				jQuery('#ajax-system-message-container').html(theData.sysmssg);
				
				file_subpath.split('.').pop().toLowerCase() == 'css' ?
					jQuery('#edit-css-files-warning').show() :
					jQuery('#edit-css-files-warning').hide() ;
									
				// Loading task always return data, even empty data, set them into the editor
				set_editor_contents(txtarea, theData);
				// Display the buttons
				jQuery('#editor__save_file_btn').css('display', '');
				jQuery('#editor__download_file_btn').css('display', '');
				jQuery('#editor__load_common_file_btn').css('display', parseInt(theData.default_exists) ? '' : 'none');
			}
		});
	}
</script>

<div id="flexicontent" class="flexicontent">

<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">
	
    


<?php 
$options = array('active'    => 'tab1_id');
echo JHtml::_('bootstrap.startTabSet', 'ID-Tabs-Group', $options, array('useCookie'=>1));?> 

<!--FLEXI TAB1 -->
<?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-Group', 'tab1_id', '<i class="icon-info"></i>'.JText::_('FLEXI_INFORMATION')); ?> 
<!--Content TAB1 -->
 <div class="row-fluid" id="lay-desc-table">
     <div class="span4">
				<img src="../<?php echo $this->layout->thumb; ?>" alt="<?php echo JText::_( 'FLEXI_TEMPLATE_THUMBNAIL' ); ?>" style="max-width:none;" />
</div>
<div class="span8">
           
 					<div class="row-fluid">
                <div class="span3">
							<label class="label">
							<?php echo JText::_( 'FLEXI_FOLDER' ); ?>
							</label>
						 </div>
                <div class="span9">
							<span class="badge badge-warning"><?php echo $this->layout->name; ?></span>
						</div></div>
					<div class="row-fluid">
                <div class="span3">
							<label class="label">
								<?php echo JText::_( 'FLEXI_VIEW' ); ?>
							</label>
						 </div>
                <div class="span9">
							<span class="badge badge-success"><?php echo $this->layout->view; ?></span>
						</div></div>
					
			
					<div class="row-fluid">
                <div class="span3">
							<label class="label">
								<?php echo JText::_( 'Default title' ); ?>
							</label>
						 </div>
                <div class="span9">
							<?php echo JText::_($this->layout->defaulttitle); ?>
						</div></div>
					<div class="row-fluid">
                <div class="span3">
							<label class="label">
								<?php echo JText::_( 'Description' ); ?>
							</label>
						 </div>
                <div class="span9">
							<?php echo JText::_($this->layout->description); ?>
						</div></div>
				
					
					<div class="row-fluid">
                <div class="span3">
							<label class="label">
								<?php echo JText::_( 'FLEXI_AUTHOR' ); ?>
							</label>
						 </div>
                <div class="span9">
							<?php echo $this->layout->author; ?>
						</div></div>
					<div class="row-fluid">
                <div class="span3">
							<label class="label">
								<?php echo JText::_( 'FLEXI_WEBSITE' ); ?>
							</label>
						 </div>
                <div class="span9">
							<a href="http://<?php echo $this->layout->website; ?>" target="_blank"><?php echo $this->layout->website; ?></a>
						</div></div>
					<div class="row-fluid">
                <div class="span3">
							<label class="label">
								<?php echo JText::_( 'Email' ); ?>
							</label>
						 </div>
                <div class="span9">
							<a href="mailto:<?php echo $this->layout->email; ?>"><?php echo $this->layout->email; ?></a>
						</div></div>
					<div class="row-fluid">
                <div class="span3">
							<label class="label">
								<?php echo JText::_( 'License' ); ?>
							</label>
						 </div>
                <div class="span9">
							<?php echo $this->layout->license; ?>
						</div></div>
					<div class="row-fluid">
                <div class="span3">
							<label class="label">
								<?php echo JText::_( 'Version' ); ?>
							</label>
						 </div>
                <div class="span9">
							<?php echo $this->layout->version; ?>
						</div></div>
					<div class="row-fluid">
                <div class="span3">
							<label class="label">
								<?php echo JText::_( 'FLEXI_RELEASE' ); ?>
							</label>
						 </div>
                <div class="span9">
							<?php echo $this->layout->release; ?>
						</div>
                        </div>
                        
                        </div>
                        </div>
<!--/Content TAB1 -->

<?php echo JHtml::_('bootstrap.endTab');?> 
<!--/FLEXI_ACCOUNT_DETAILS TAB1 -->

<!--TAB 2 START-->
<?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-Group', 'tab2_id', '<i class="icon-signup"></i>'.JText::_('FLEXI_FIELDS_PLACEMENT')); ?> 

<div class="alert alert-info">
				<strong><?php echo JText::_('FLEXI_NOTES');?>:</strong>
				<?php echo JText::_('FLEXI_INSTRUCTIONS_ADD_FIELD_TO_LAYOUT_POSITION');?>
			</div>
		
        <div class="row-fluid">
				<!--Content Tab2 Col1-->
                <div class="span6 full_width_980">
                
                
                <fieldset id="available_fields_container">
						<h3 class="fcsep_level1"><?php echo JText::_('FLEXI_AVAILABLE_FIELDS') ?></h3>
                        
                        <div style="float:left; clear:both; width:100%; margin:0px 0px 12px 0px;">
							<div style="float:left; margin-right:32px;">
								<div style="float:left;" class="postitle label" ><?php echo JText::_('FLEXI_FILTER').' '.JText::_('FLEXI_TYPE'); ?></div>
								<div style="float:left; clear:both;">
									<?php echo sprintf(str_replace('__au__', '_available', $this->content_type_select), 'available_fields_container', 'hide', 'available'); ?>
								</div>
							</div>
							<div style="float:left;">
								<div style="float:left;" class="postitle label" ><?php echo JText::_('FLEXI_FILTER').' '.JText::_('FLEXI_FIELD_TYPE'); ?></div>
								<div style="float:left; clear:both;">
									<?php echo sprintf(str_replace('__au__', '_available', $this->field_type_select), 'available_fields_container', 'hide', 'available'); ?>
								</div>
							</div>
						</div>
						
						
						<div class="postitle label label-info" style="margin-top:10px;"><?php echo JText::_('FLEXI_CORE_FIELDS'); ?></div>
					
						<div class="positions_container">
							<ul id="sortablecorefields" class="positions">
							<?php
							foreach ($this->fields as $field) :
								if ($field->iscore && (!in_array($field->name, $this->used))) :
									$class_list  = "fields core";
									$class_list .= !empty($field->type_ids) ? " content_type_".implode(" content_type_", $field->type_ids) : "";
									$class_list .= " field_type_".$field->field_type;
							?>
							<li class="<?php echo $class_list; ?>" id="field_<?php echo $field->name; ?>"><?php echo $field->label; ?></li>
							<?php
								endif;
							endforeach;
							?>
							</ul>
						</div>
						
						
						<div class="postitle label label-info" style="margin-top:10px;"><?php echo JText::_('FLEXI_NON_CORE_FIELDS'); ?></div>
						
						<div class="positions_container">
							<ul id="sortableuserfields" class="positions">
							<?php
							foreach ($this->fields as $field) :
								if (!$field->iscore && (!in_array($field->name, $this->used))) :
									$class_list  = "fields user";
									$class_list .= !empty($field->type_ids) ? " content_type_".implode(" content_type_", $field->type_ids) : "";
									$class_list .= " field_type_".$field->field_type;
							?>
							<li class="<?php echo $class_list; ?>" id="field_<?php echo $field->name; ?>"><?php echo $field->label.' #'.$field->id; ?></li>
							<?php
								endif;
							endforeach;
							?>
							</ul>
						</div>
					
					</fieldset>
                       <!--end Content Tab2 Col1--> 
                </div>
                <!--row2-->
                <div class="span6 full_width_980">
                
                <!--Content Tab2 Col2-->
                <fieldset id="layout_positions_container">
						<legend style="margin:0 0 12px 0; font-size:14px; padding-top:6px; padding-bottom:6px; background:gray;" class="fcsep_level1"><?php echo JText::_('FLEXI_AVAILABLE_POS') ?></legend>
						<div class="fcclear"></div>
						
						<div style="float:left; clear:both; width:100%; margin:0px 0px 12px 0px;">
							<div style="float:left; margin-right:32px;">
								<div style="float:left;" class="postitle label" ><?php echo JText::_('FLEXI_FILTER').' '.JText::_('FLEXI_TYPE'); ?></div>
								<div style="float:left; clear:both;">
									<?php echo sprintf(str_replace('__au__', '_used',$this->content_type_select), 'layout_positions_container', 'highlight', 'used'); ?>
								</div>
							</div>
							<div style="float:left;">
								<div style="float:left;" class="postitle label" ><?php echo JText::_('FLEXI_FILTER').' '.JText::_('FLEXI_FIELD_TYPE'); ?></div>
								<div style="float:left; clear:both;">
									<?php echo sprintf(str_replace('__au__', '_used',$this->field_type_select), 'layout_positions_container', 'highlight', 'used'); ?>
								</div>
							</div>
						</div>
						
						<?php
						if (isset($this->layout->positions)) :
							$count=-1;
							foreach ($this->layout->positions as $pos) :
								$count++;
								
								$pos_css = "";
								$posrow_prev = @$posrow;
								$posrow = isset($this->layout->attributes[$count]['posrow'] )  ?  $this->layout->attributes[$count]['posrow'] : '';
								
								// Detect field group row change and close previous row if open
								echo ($posrow_prev && $posrow_prev != $posrow)  ?  "</td></tr></table>\n"  :  "";
								
								if ($posrow) {
									// we are inside field group row, start it or continue with next field group
									echo ($posrow_prev != $posrow)  ?  "<table style='width:100%;'><tr class='fieldgrprow' ><td class='fieldgrprow_cell' >\n"  :  "</td><td class='fieldgrprow_cell'>\n";
								}
								
							?>
							
							<div class="postitle label label-success" style="margin:10px 0 2px"><?php echo $pos; ?></div>
							
							<?php
							if ( isset($this->layout->attributes[$count]['readonly']) ) {
								switch ($this->layout->view) {
									case FLEXI_ITEMVIEW: $msg='in the <strong>Item Type</strong> configuration and/or in each individual <strong>Item</strong>'; break;
									case 'category': $msg='in each individual <strong>Category</strong>'; break;
									default: $msg='in each <strong>'.$this->layout->view.'</strong>'; break;
								}
								echo "<div class='positions_readonly_info fc-mssg fc-info fc-nobgimage'>NON-editable position.<br/> To customize edit TEMPLATE parameters ".$msg."</div>";
								continue;
							}
							?>
						<div class="positions_container">
							<ul id="sortable-<?php echo $pos; ?>" class="positions" >
							<?php
							if (isset($this->fbypos[$pos])) :
								foreach ($this->fbypos[$pos]->fields as $f) :
									if (isset($this->fields[$f])) : // this check is in case a field was deleted
										$field = $this->fields[$f];
										$class_list  = "fields ". ($this->fields[$f]->iscore ? 'core' : 'user');
										$class_list .= !empty($field->type_ids) ? " content_type_".implode(" content_type_", $field->type_ids) : "";
										$class_list .= " field_type_".$field->field_type;
							?>
								<li class="<?php echo $class_list; ?>" id="field_<?php echo $this->fields[$f]->name; ?>">
								<?php echo $this->fields[$f]->label . ($this->fields[$f]->iscore ? '' : ' #'.$this->fields[$f]->id); ?>
								</li>
							<?php
									endif;
								endforeach;
							endif;	
							?>
							</ul>
						</div>
							<input type="hidden" name="<?php echo $pos; ?>" id="<?php echo $pos; ?>" value="" />
						<?php 
							endforeach;
							// Close any field group line that it is still open
							echo @$posrow ? "</td></tr></table>\n" : "";
						else :
							echo JText::_('FLEXI_NO_GROUPS_AVAILABLE');
						endif;
						?>
					</fieldset>
                    <!--/ Content Tab2 Col2-->
                </div>
                </div>	
            
<?php echo JHtml::_('bootstrap.endTab');?> 
<!--/ TAB 2-->


<!--TAB 3 START-->
<?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-Group', 'tab3_id', '<i class="icon-options"></i>'.JText::_('FLEXI_DISPLAY_PARAMETERS')); ?> 
<!-- Content Tab3 Col1-->
<p class="fc-mssg-inline fc-warning">
				Parameters specific to the layout, <br/> -
				<?php echo JText::_( $this->layout->view == 'item' ?
					'your <strong>content types / items</strong> ' :
					'your <strong>content lists</strong> (categories, etc)'
				);?>
				will inherit defaults from here, but may <strong>override</strong> them
				<br/> -
				<?php echo JText::_( 'setting any parameter below to <strong>"Use global"</strong>, will use default value inside the <strong>template\'s PHP code</strong>');?>
			</p>


<?php
				$groupname = 'attribs';  // Field Group name this is for name of <fields name="..." >
				$fieldSets = $this->layout->params->getFieldsets($groupname);
				foreach ($fieldSets as $fsname => $fieldSet) :
					if (isset($fieldSet->description) && trim($fieldSet->description)) :
						echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
					endif;
					?>
					<fieldset class="panelform m20">
						<?php foreach ($this->layout->params->getFieldset($fsname) as $field) :
							echo '<div class="control-group">';
							$fieldname = $field->__get('fieldname');
							$value = $this->layout->params->getValue($fieldname, $groupname, @$this->conf->attribs[$fieldname]);
							echo '<div class="control-label">'.$this->layout->params->getLabel($fieldname, $groupname).'</div>';
							echo '<div class="controls">';
							echo
								str_replace('jform_attribs_', 'jform_layouts_'.$this->layout->name.'_', 
									str_replace('[attribs]', '[layouts]['.$this->layout->name.']',
										$this->layout->params->getInput($fieldname, $groupname, $value)
									)
								);
							echo '</div></div>';
						endforeach; ?>
					</fieldset>
				<?php endforeach; ?>
                
<!-- / Content Tab3 Col1-->
<?php echo JHtml::_('bootstrap.endTab');?> 
<!--/ TAB 3-->

<!--TAB 4 START-->
<?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-Group', 'tab4_id', '<i class="icon-signup"></i>'.JText::_('FLEXI_EDIT_LAYOUT_FILES')); ?> 

<div class="row-fluid">
<div id="layout-filelist-container" class="span4">
				<span class="fcsep_level0">
					<span class="label label-info"><?php echo JText::_( 'FLEXI_LAYOUT_FILES' ); ?></span>
				</span>
				<?php
				$tmpldir = JPATH_ROOT.DS.'components'.DS.'com_flexicontent'.DS.'templates'.DS.$this->layout->name;
				$it = new RegexIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($tmpldir)), '#('.$this->layout->view.'(_.*\.|\.)(php|xml|less|css|js)|include.*less)#i');
				//$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($tmpldir));
				$it->rewind();
				$ext_badge = array('php'=>'success', 'xml'=>'info', 'css'=>'warning', 'js'=>'important', 'ini'=>'info', 'less'=>'inverse');
				$file_tip = array(
					'config.less'=>'Contains your LESS imports, variables, mixins, etc',
					'config_auto_item.less'=>'Auto-created LESS variables (every save of this form), <br/> saves layout parameters as LESS variables',
					'config_auto_category.less'=>'Auto-created LESS variables (every save of this form), <br/> saves layout parameters as LESS variables',
					'item.less'=>'Contains LESS directives, used to create CSS of this item layout',
					'item.css'=>'Contains CSS specific to this item layout',
					'category.less'=>'Contains LESS directives, used to create CSS of this category layout',
					'category.css'=>'Contains CSS specific to this category layout',
					'item.xml'=>'Layout\'s structure: including display parameters, field positions, file list, etc',
					'category.xml'=>'Layout\'s structure: including display parameters, field positions, file list, etc',
					'item.php'=>'PHP/HTML to display item\'s fields',
					'category_items.php'=>'Item list filtering form, and Item loop (displaying every item)',
					'category.php'=>'Top-level container of the layout',
					'category_category.php'=>'Displays category information: title, description, image, etc',
					'category_category_html5.php'=>'Displays category information: title, description, image, etc',
					'category_alpha.php'=>'Displays the alphanumerical index of the item list',
					'category_peercategories.php'=>'Displays the peer categories list',
					'category_subcategories.php'=>'Display the sub-categories list',
				);
				$file_tip['item_html5.php'] = $file_tip['item.php'].' (HTML5 version)';
				$file_tip['category_items_html5.php'] = $file_tip['category_items.php'].' (HTML5 version)';
				
				$file_tip['category_html5.php'] = $file_tip['category.php'].' (HTML5 version)';
				$file_tip['category_category_html5.php'] = $file_tip['category_category.php'].' (HTML5 version)';
				$file_tip['category_alpha_html5.php'] = $file_tip['category_alpha.php'].' (HTML5 version)';
				$file_tip['category_peercategories_html5.php'] = $file_tip['category_peercategories.php'].' (HTML5 version)';
				$file_tip['category_subcategories_html5.php'] = $file_tip['category_subcategories.php'].' (HTML5 version)';
				
				
						$file_tip_extra = array(
					'config.less'=>'NOTE:<br/>- this is automatically imported by item.less and category.less <br/> - if you need to import extra less files, then files must be in same folder (less/include/) for automatic compiling to be triggered',
					'item.xml'=>'This file contains layout\' s structure: including <br/> - <b>display parameters, field positions, file list, etc</b>, <br/> - you can add extra parameters/positions, <br/>-- if you add a new position, you will need to also add the dispay -LOOP- of the new position inside files: <br/><b>item.php</b> <br/><b>item_html5.php</b> <br/><br/>(click to edit file and then use the code button)',
					'category.xml'=>'This file contains layout\' s structure: including <br/> - <b>display parameters, field positions, file list, etc</b>, <br/> - you can add extra parameters/positions, <br/>-- if you add a new position, you will need to also add the dispay -LOOP- of the new position inside files: <br/><b>category_items.php</b> <br/><b>category_items_html5.php</b> <br/><br/>(click to edit file and then use the code button)',
					'item.php'=>'This file display the item, thus has display LOOPs of for every position to show fields of every position, if you add new position in the XML file, then make sure that you ADD the display loop here <br/><br/>(click to edit file and then use the code button)',
					'category_items.php'=>'This file includes: <br/><br/>- Item list filtering form, <br/>- Item loop (that displays every item\'s fields), <br/><br/>if you add new field position in the XML file, then make sure that you ADD here, the display loop that displays the fields of the position <br/><br/>(click to edit file and then use the code button)'
				);
				$file_tip_extra['item_html5.php'] = $file_tip_extra['item.php'];
				$file_tip_extra['category_items_html5.php'] = $file_tip_extra['category_items.php'];
				
				$file_code_btns = array(
					'item.xml'=>array('fieldPosXML'=>1, 'paramTextXML'=>1, 'paramRadioXML'=>1, 'paramSelectXML'=>1),
					'category.xml'=>array('fieldPosXML'=>1, 'paramTextXML'=>1, 'paramRadioXML'=>1, 'paramSelectXML'=>1),
					'item.php'=>array('itemPosHTML'=>1, 'itemFieldDisplay'=>1),
					'category_items.php'=>array('catPosHTML'=>1, 'catFieldDisplay'=>1)
				);
				$file_code_btns['item_html5.php'] = $file_code_btns['item.php'];
				$file_code_btns['category_items_html5.php'] = $file_code_btns['category_items.php'];
				
				
				while($it->valid())
				{
					if (!$it->isDot()) {
						//echo '<span class="label">SubPathName</span> '. $it->getSubPathName();
						//echo ' -- <span class="label">SubPath</span> '. $it->getSubPath();
						//echo ' -- <span class="label">Key</span> '. $it->key();
						$subpath = $it->getSubPath();
						$subpath_highlighted = $subpath ? '<span class="label">'.str_replace('\\', '/', $subpath).'/</span>' : '';
						
						$subpath_file = $it->getSubPathName();
						$filename = basename( $subpath_file ); //preg_replace('#^'.$subpath.'\\'.DS.'#', '', $subpath_file);
						
						// Skip some files, e.g. auto generated item / category specific files
						if (
							($this->layout->view == 'item' && $filename == 'config_auto_category.less') ||
							($this->layout->view == 'category' && $filename == 'config_auto_item.less')
						) {
							$it->next();
							continue;
						}
						
						$pi = pathinfo($it->key());
						$ext = $pi['extension'];
						$file_type = isset($ext_badge[$ext]) ?
							'<span class="badge badge-'.$ext_badge[$ext].'">'.$ext.'</span> ' :
							'<span class="badge">---</span> ' ;
						$btns_handle_class = isset($ext_badge[$ext]) ?
							'<span class="badge badge-'.$ext_badge[$ext].'">'.$ext.'</span> ' :
							'<span class="badge">---</span> ' ;
						$btn_allowed = isset($file_code_btns[$filename]) ? array_keys($file_code_btns[$filename]) : array();
						echo '
						'.$file_type.
						(!isset($file_tip_extra[$filename]) ? '<img src="components/com_flexicontent/assets/images/tick_f2.png" alt="Edit file">' :
							'<img src="components/com_flexicontent/assets/images/comments.png" data-placement="bottom" class="'.$tip_class.'" title="'.$file_tip_extra[$filename].'" alt="Edit file"/>'
						).'
						<a href="javascript:;" class="'.$tip_class.'" data-placement="right" onclick="load_layout_file(\''.addslashes($this->layout->name).'\', \''.addslashes($it->getSubPathName()).'\', \'0\', \''.implode(' ', $btn_allowed).'\'); return false;"
						title="'.(isset($file_tip[$filename]) ? $file_tip[$filename] : $ext.' file').'">'
							.$subpath_highlighted.'&nbsp;'.$filename.
						'</a>'
						;
						echo "<br/>";
					}
					
					$it->next();
				}
				?>
			</div>

			<div id="layout-fileeditor-container" class="span8">
				<span class="fcsep_level0" style="margin-top: 0;">
					<span id="layout_edit_name_container" class="label label-info"><?php echo JText::_( 'FLEXI_NO_FILE_LOADED' ); ?></span>
				</span>
				<div class="fcclear"></div>
				<div id="ajax-system-message-container"></div>
				<div class="fcclear"></div>
				
                
                <div class="fc-note fc-mssg" id="edit-css-files-warning" style="display: none;">
					<?php echo JText::_( 'FLEXI_MODIFY_LESS_FILES_INSTEAD_OF_CSS' ); ?>
				</div>
                
				<?php
				if ($use_editor) {
					$editor = JFactory::getEditor('codemirror');
					$editor_plg_params = array('mode'=>'php');  // Override parameters of the editor plugin, ignored by most editors !!
				}
				
				$elementid_n = "editor__file_contents";  $fieldname_n = "file_contents";
				$cols="80"; $rows="16";   $width = '100%'; $height='400px';
				$class="fcfield_textval";
				$show_buttons = false; // true/false, or this can be skip button array
				$txtarea = !$use_editor ? '
					<textarea id="'.$elementid_n.'" name="'.$fieldname_n.'" style="width: 100%;" cols="'.$cols.'" rows="'.$rows.'" class="'.$class.'" form="layout_file_editor_form"></textarea>' :
					$editor->display( $fieldname_n, '', $width, $height, $cols, $rows, $show_buttons, $elementid_n, $_asset_ = null, $_author_ = null, $editor_plg_params );
				echo $txtarea;
				?>
				
				<br/>
				
				<?php echo str_replace('<input', '<input form="layout_file_editor_form"', JHTML::_( 'form.token' )); ?>
				<input type="hidden" name="load_mode" id="editor__load_mode" form="layout_file_editor_form"/>
				<input type="hidden" name="layout_name" id="editor__layout_name" form="layout_file_editor_form"/>
				<input type="hidden" name="file_subpath" id="editor__file_subpath" form="layout_file_editor_form"/>
				<input type="hidden" name="btn_classes" id="editor__btn_classes" form="layout_file_editor_form"/>
				
				<input type="button" name="save_file_btn" id="editor__save_file_btn" class="<?php echo $btn_class; ?> btn-success <?php echo $tip_class; ?>" onclick="save_layout_file('layout_file_editor_form'); return false;" style="display:none;" value="Save" form="layout_file_editor_form"
				title="<?php echo flexicontent_html::getToolTip('Save file', 'You may want to download a copy in your local disk before saving changes', 0, 1); ?>"
				/>
				<input type="button" name="download_file_btn" id="editor__download_file_btn" class="<?php echo $btn_class; ?> btn-info <?php echo $tip_class; ?>" onclick="load_layout_file('', '', 2, -1); return false;" style="display:none;" value="Download" form="layout_file_editor_form"
				title="<?php echo flexicontent_html::getToolTip('Download file', 'This will download the current file from server and not the text currently in the editor, if you want the text in the editor then just copy paste it in a local text file', 0, 1); ?>"
				/>
				<input type="button" name="load_file_btn" id="editor__load_common_file_btn" class="<?php echo $btn_class; ?> btn-info <?php echo $tip_class; ?>" onclick="load_layout_file('', '', 1, -1); return false;" style="display:none;" value="Load/customize system's default" form="layout_file_editor_form"
				title="<?php echo flexicontent_html::getToolTip('System\'s default code', 'Please note that this loads the <strong>system\'s default</strong> for the current file, which maybe different than <strong>template\'s default</strong> code', 0, 1); ?>"
				/>
				
				
				<span class="fcsep_level0" id="code_box_header">
					<span id="layout_edit_" class="label label-info"><?php echo JText::_( 'FLEXI_INSERT_TEMPLATE_CODE' ); ?></span>
				</span>
				<div class="fcclear"></div>
				
				<?php foreach ($code_btn_lbls as $_posname => $btn_lbl) : ?>
				<span class="code_box <?php echo $_posname; ?> nowrap_box" style="display:none;" >
					<span class="btn <?php echo $tip_class; ?>"
						title="<?php echo flexicontent_html::getToolTip('Insert code', $code_btn_tips[$_posname], 0, 1); ?>"
						onclick="toggle_code_inputbox(this);"><span class="icon-eye"></span><?php echo JText::_( $code_btn_lbls[$_posname] ); ?></span>
					<span class="nowrap_box" style="display:none; float:left; clear:both; margin:2px 0px 0px 0px;">
						<div class="alert alert-warning" style="clear:both; margin:2px 0px 2px 0px;"><?php echo JText::_( 'FLEXI_COPY_CODE' ); ?></div>
						<div class="alert alert-info" style="clear:both; margin:2px 0px 2px 0px;">
							<?php echo $code_btn_tips[$_posname]; ?>
						</div>
					</span>
					<textarea style="float:left; clear:both; display:none; width:100%;" rows="24" form="code_insertion_form"><?php echo htmlspecialchars($code_btn_rawcode[$_posname]); ?></textarea>
				</span>
				<?php endforeach; ?>
     </div>           
<?php echo JHtml::_('bootstrap.endTab');?> 
<!--/ TAB 4-->

<!-- / ENDTAB -->
<?php echo JHtml::_('bootstrap.endTabSet');?>
	<input type="hidden" name="option" value="com_flexicontent" />
	<input type="hidden" name="controller" value="templates" />
	<input type="hidden" name="rows" id="rows" value="" />
	<input type="hidden" name="positions" id="positions" value="<?php echo $this->positions; ?>" />
	<input type="hidden" name="view" value="template" />
	<input type="hidden" name="type" value="<?php echo $this->type; ?>" />
	<input type="hidden" name="folder" value="<?php echo $this->folder; ?>" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

<form id="layout_file_editor_form" name="layout_file_editor_form" action="index.php?option=com_flexicontent&task=templates.loadlayoutfile&format=raw" method="POST"></form>
<form id="code_insertion_form" name="code_insertion_form" action="#" method="POST"></form>

</div>