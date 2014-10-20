<?php
/**
 * @version 1.5 stable $Id: import.php 1883 2014-04-09 17:49:21Z ggppdk $
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

$params = $this->cparams;
$document	= JFactory::getDocument();

// Add some CSS to the doc header
$document->addStyleDeclaration("
#adminForm table fieldset.fleximport ul {
	padding: 0px 0px 0px 24px;
	margin: 12px 0px 12px 0px;
}
#adminForm table fieldset.fleximport li {
	list-style: disc inside none !important;
	margin: 0 !important;
	padding: 1px 0 0 8px !important;
}
");


// Add form validation JS to the doc header
$document->addScriptDeclaration("
".(FLEXI_J16GE ? 'Joomla.submitform = ' : '')."

function submitbutton(task) {
	if(task=='cancel') {
		submitform(task);
		return;
	}
	if(!$('type_id').value) {
		alert('Please select item type.');
		$('type_id').focus();
		return;
	}

	radio_ischecked = 0;
	for (i = 0; i < document.getElementsByName('language').length; i++) {
		if (document.getElementsByName('language')[i].checked) {
			radio_ischecked = 1;
		}
	}
	if( !radio_ischecked ) {
		alert('Please select item language.');
		return;
	}

	radio_ischecked = 0;
	for (i = 0; i < document.getElementsByName('state').length; i++) {
		if (document.getElementsByName('state')[i].checked) {
			radio_ischecked = 1;
		}
	}
	if( !radio_ischecked ) {
		alert('Please select item state.');
		return;
	}

	if( $('maincat').value.length<=0 && !$('maincat_col').checked ) {
		alert('Please select a primary category or select to use \'catid\' column');
		$('maincat').focus();
		return;
	}
	if($('field_separator').value=='') {
		alert('Please select your FLEXIcontent field separator string e.g ~~');
		$('csvfile').focus();
		return;
	}
	if($('record_separator').value=='') {
		alert('Please select your FLEXIcontent item separator string e.g \\n~~');
		$('csvfile').focus();
		return;
	}
	if($('csvfile').value=='') {
		alert('Please select your csv file that you want to import.');
		$('csvfile').focus();
		return;
	}
	submitform(task);
}
");
?>

<div class="flexicontent fimport" id="flexicontent">

<form action="index.php" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">

<div class="row-fluid">
<div class="span12">
<div class="m20"></div>
<p class="text-right"><a href="#tools_3rd_party" ><?php echo JText::_( 'FLEXI_3RD_PARTY_DEV_IMPORT_EXPORT_TOOLS' ); ?></a></p>
<div class="block-flat">
<!--CONTENT1-->
<div class="form-horizontal import">
<h2><?php echo JText::_("FLEXI_IMPORT_CUSTOM_COLS");?></h2>
<hr>
 <div class="form-inline m10x">
                    <div class="control-group">
<label class="required" for="type_id"><?php echo JText::_("FLEXI_ITEM_TYPE");?></label>
<div class="controls">
<?php echo $this->lists['type_id'];?>
</div>
</div>
</div>
<!--unused columns-->
 <div class="form-inline m10x">
                    <div class="control-group">
<label><?php echo JText::_("FLEXI_IMPORT_IGNORE_UNUSED_COLUMNS");?></label>
<div class="controls">
<?php
								$_ignore_unused_cols_checked = $params->get('import_ignore_unused_cols', 0) ? 'checked="checked"' : '';
							?>
							<label for="ignore_unused_cols" class="w100"><input type="checkbox" id="ignore_unused_cols" name="ignore_unused_cols" value="1" <?php echo $_ignore_unused_cols_checked; ?> /><?php echo JText::_( 'FLEXI_IMPORT_IGNORE_REDUDANT_COLS' ); ?></label><span class="fc-mssg-inline fc-info"><?php echo JText::_("FLEXI_IMPORT_IGNORE_REDUDANT_COLS_DESC");?></span>
</div>
</div>
</div>
<!--Custom-->
 <div class="form-inline m10x">
                    <div class="control-group">
                    <label><?php echo JText::_("FLEXI_IMPORT_CUSTOM_ITEM_ID");?></label>
                    <div class="controls">
                    <?php
								$_id_col_checked = $params->get('import_id_col', 0) ? 'checked="checked"' : '';
							?>
							<label for="id_col" class="w100"><input type="checkbox" id="id_col" name="id_col" value="1" <?php echo $_id_col_checked; ?> />
							<?php echo JText::_("FLEXI_IMPORT_USE_ID_COL");?></label><span class="fc-mssg-inline fc-info"><?php echo JText::_("FLEXI_IMPORT_ALL_IDS_CHECKED_BEFORE_IMPORT");?></span>
                      </div>
</div>
</div>
      <!--LANGUAGE-->
       <div class="form-inline m10x">
                    <div class="control-group">
                    <label class="required"><?php echo JText::_("FLEXI_LANGUAGE");?></label>
                    <div class="controls autox">
                    <?php echo str_replace('<br />', '', $this->lists['languages']); ?>
                    </div></div></div>
                    
           <!--STATE-->    
           
                  <div class="form-inline m10x">
                    <div class="control-group">
                    <label><?php echo JText::_("FLEXI_STATE");?></label>
                    <div class="controls autox">
                    <?php echo str_replace('<br />', '', $this->lists['states']); ?>
                    </div></div></div>     
                    
                    
            
                               <!--CREATION DATE-->    
                  <div class="form-inline m10x">
                    <div class="control-group">
                    <label class="control-label"><?php echo JText::_("FLEXI_CREATION_DATE");?></label>
                    <div class="controls">
                    <?php
								$dv = $params->get('import_created_col', 0);
								$checked0 = $dv==0 ? 'checked="checked"' : '';
								$checked1 = $dv==1 ? 'checked="checked"' : '';
							?>
                            <div class="boxe">
							<label for="created_col0" class="radio control-label w100"><input type="radio" id="created_col0" name="created_col" value="0" <?php echo $checked0; ?> />
							a. <?php echo JText::_("FLEXI_IMPORT_CREATION_CURR_DATE");?></label>
							
<div class="clear"></div>
							<label for="created_col1" class="radio control-label w100"><input type="radio" id="created_col1" name="created_col" value="1" <?php echo $checked1; ?> />
							b. <?php echo JText::_("FLEXI_IMPORT_CREATION_USE_COL");?></label>
                            </div>
                    </div></div></div>    
                    
                             <div class="form-inline m10x">
                    <div class="control-group"><label><?php echo JText::_("FLEXI_CREATOR_AUTHOR");?></label>  
                    <div class="controls">
                    <div class="boxe">
							<?php
								$dv = $params->get('import_created_by_col', 0);
								$checked0 = $dv==0 ? 'checked="checked"' : '';
								$checked1 = $dv==1 ? 'checked="checked"' : '';
							?>
							<input type="radio" id="created_by_col0" name="created_by_col" value="0" <?php echo $checked0; ?> />
							<label for="created_by_col0"><span>a. <?php echo JText::_("FLEXI_IMPORT_CREATOR_CURR_USER");?></span></label>
							<div class="fcclear"></div>
							<input type="radio" id="created_by_col1" name="created_by_col" value="1" <?php echo $checked1; ?> />
							<label for="created_by_col1"><span>b. <?php echo JText::_("FLEXI_IMPORT_CREATOR_USE_COL");?></span></label>
						  </div>
                    </div></div></div>   
                    
                    
                    <!--FLEXI_MODIFICATION_DATE-->
                    
                                  <div class="form-inline m10x">
                    <div class="control-group"><label><?php echo JText::_("FLEXI_MODIFICATION_DATE");?></label> 
                    <div class="controls">
                    <div class="boxe">
					<?php
								$dv = $params->get('import_modified_col', 0);
								$checked0 = $dv==0 ? 'checked="checked"' : '';
								$checked1 = $dv==1 ? 'checked="checked"' : '';
							?>
							<input type="radio" id="modified_col0" name="modified_col" value="0" <?php echo $checked0; ?> />
							<label for="modified_col0"><span>a. <?php echo JText::_("FLEXI_IMPORT_MODIFICATION_NEVER_DATE");?></span></label>
							<div class="fcclear"></div>
							<input type="radio" id="modified_col1" name="modified_col" value="1" <?php echo $checked1; ?> />
							<label for="modified_col1"><span>b. <?php echo JText::_("FLEXI_IMPORT_MODIFICATION_USE_COL");?></span></label>
						  </div>
                    </div></div></div> 
                    
                    <!--FLEXI_MODIFIER-->
                    
                                  <div class="form-inline m10x">
                    <div class="control-group"><label><?php echo JText::_("FLEXI_MODIFIER");?></label>
                    <div class="controls">
                    <div class="boxe">
				<?php
								$dv = $params->get('import_modified_by_col', 0);
								$checked0 = $dv==0 ? 'checked="checked"' : '';
								$checked1 = $dv==1 ? 'checked="checked"' : '';
							?>
							<input type="radio" id="modified_by_col0" name="modified_by_col" value="0" <?php echo $checked0; ?> />
							<label for="modified_by_col0"><span>a. <?php echo JText::_("FLEXI_IMPORT_MODIFIER_NONE_USER");?></span></label>
							<div class="fcclear"></div>
							<input type="radio" id="modified_by_col1" name="modified_by_col" value="1" <?php echo $checked1; ?> />
							<label for="modified_by_col1"><span>b. <?php echo JText::_("FLEXI_IMPORT_MODIFIER_USE_COL");?></span></label>
						  </div>
                    </div></div></div> 
                    
                      <!--FLEXI_META_DATA-->
                    
                                  <div class="form-inline m10x">
                    <div class="control-group"><label><?php echo JText::_("FLEXI_META_DATA");?></label>
                    <div class="controls">
                    <div class="boxe">
				<?php
								$_desc_checked = $params->get('import_metadesc_col', 0) == 1 ? 'checked="checked"' : '';
								$_key_checked = $params->get('import_metakey_col', 0) == 1 ? 'checked="checked"' : '';
							?>
							<input type="checkbox" id="metadesc_col" name="metadesc_col" value="1" <?php echo $_desc_checked; ?> />
							<label for="metadesc_col"><span><?php echo JText::_("FLEXI_IMPORT_USE_METADESC_COL");?></span></label>
							<div class="fcclear"></div>
							<input type="checkbox" id="metakey_col" name="metakey_col" value="1" <?php echo $_key_checked; ?> />
							<label for="metakey_col"><span><?php echo JText::_("FLEXI_IMPORT_USE_METAKEY_COL");?></span></label>
						  </div>
                    </div></div></div> 
                    
                       <!--FLEXI_PUBLICATION_DATES-->
                    
                                  <div class="form-inline m10x">
                    <div class="control-group"><label><?php echo JText::_("FLEXI_PUBLICATION_DATES");?></label>
                    <div class="controls">
                    <div class="boxe">
		
							<?php
								$_up_checked = $params->get('import_publish_up_col', 0) == 1 ? 'checked="checked"' : '';
								$_down_checked = $params->get('import_publish_down_col', 0) == 1 ? 'checked="checked"' : '';
							?>
							<input type="checkbox" id="publish_up_col" name="publish_up_col" value="1" <?php echo $_up_checked; ?> />
							<label for="publish_up_col"><span><?php echo JText::_("FLEXI_IMPORT_USE_PUBLISH_UP_COL");?></span></label>
							<div class="fcclear"></div>
							<input type="checkbox" id="publish_down_col" name="publish_down_col" value="1" <?php echo $_down_checked; ?> />
							<label for="publish_down_col"><span><?php echo JText::_("FLEXI_IMPORT_USE_PUBLISH_DOWN_COL");?></span></label>
                            
                            	<span class="fc-mssg-inline fc-info"><?php echo JText::_("FLEXI_IMPORT_ENTER_VALID_DATES");?></span>
						  </div>
                    </div></div></div> 
                    
                    
                      <!--FLEXI_TAGS-->
                    
                                  <div class="form-inline m10x">
                    <div class="control-group"><label><?php echo JText::_("FLEXI_TAGS");?></label>
                    <div class="controls">
                    <div class="boxe">
		<?php
								$dv = $params->get('import_tags_col', 0);
								$checked0 = $dv==0 ? 'checked="checked"' : '';
								$checked1 = $dv==1 ? 'checked="checked"' : '';
								$checked2 = $dv==2 ? 'checked="checked"' : '';
							?>
							<input type="radio" id="tags_col0" name="tags_col" value="0" <?php echo $checked0; ?> />
							<label for="tags_col0">a. <?php echo JText::_("FLEXI_IMPORT_DO_NOT_IMPORT_TAGS");?></label>
							<div class="fcclear"></div>
							<input type="radio" id="tags_col1" name="tags_col" value="1" <?php echo $checked1; ?> />
							<label for="tags_col1">b. <?php echo JText::_("FLEXI_IMPORT_USE_TAG_NAMES_COL");?></label>
							<div class="fcclear"></div>
							<input type="radio" id="tags_col2" name="tags_col" value="2" <?php echo $checked2; ?> />
							<label for="tags_col2">c. <?php echo JText::_("FLEXI_IMPORT_USE_TAG_IDS_COL");?></label>
                            <span class="fc-mssg-inline fc-info"><?php echo JText::_("FLEXI_IMPORT_TAGS_WILL_BE_CREATED_BEFORE_IMPORT");?></span>
						  </div>
                    </div></div></div> 
                    
                    <div class="m20"></div>
                    <h2><?php echo JText::_( 'FLEXI_IMPORT_CATS_LEGEND' ); ?></h2>
<hr>
<div class="form-inline m10x">
<div class="control-group">
<label for="maincat"><?php echo JText::_( 'FLEXI_PRIMARY_CATEGORY' ); ?></label>
<div class="controls">
<div class="span4"><?php echo $this->lists['maincat']; ?></div>
<div class="span4"><input type="checkbox" id="maincat_col" name="maincat_col" value="1"/> <label for="maincat_col" class="autox">(Use 'catid' column, e.g. 54)</label>
</div>
</div>
</div>

<div class="form-inline m10x">
<div class="control-group">
<label for="seccats"><?php echo JText::_( 'FLEXI_SECONDARY_CATEGORIES' ); ?></label>
<div class="controls">
<div class="span3"><?php echo $this->lists['seccats']; ?></div>
<div class="span3"><label for="seccats_col"  class="autox"><?php echo JText::_( 'FLEXI_IMPORT_FILE_OVERRIDE' ); ?> <?php echo JText::_( 'FLEXI_SECONDARY_CATEGORIES' ); ?></label></div>
<div class="span3"><input type="checkbox" id="seccats_col" name="seccats_col" value="1" /> <label for="seccats_col" class="autox">(Use 'cid' column, e.g. 54,14,51)</label></div>
</div>
</div>
</div>
		
          <div class="m20"></div>
                    <h2><?php echo JText::_( 'FLEXI_IMPORT_CSV_FILE_FORMAT_LEGEND' ); ?></h2>
<hr>

<div class="form-inline m10x">
<div class="control-group">
<label for="field_separator" class="required span3"><?php echo JText::_( 'FLEXI_CSV_FIELD_SEPARATOR' ); ?></label>
<div class="span3">
<input type="text" name="field_separator" id="field_separator" value="<?php echo htmlspecialchars($params->get('csv_field_sep','~~')); ?>"  /></div> 
<div class="span3 m10s"><span class="fc-mssg-inline fc-info"><?php echo JText::_( 'FLEXI_CSV_FIELD_SEPARATOR_DESC' ); ?></span></div>
</div></div>

<div class="form-inline m10x">
<div class="control-group">
<label for="enclosure_char"><?php echo JText::_( 'FLEXI_CSV_FIELD_ENCLOSE_CHAR' ); ?>
							</label>
<div class="span3">
<input type="text" name="enclosure_char" id="enclosure_char" value="<?php echo htmlspecialchars($params->get('csv_field_enclose_char','')); ?>" class="fcfield_textval" /></div> 
<div class="span3 m10s"><span class="fc-mssg-inline fc-info"><?php echo JText::_( 'FLEXI_CSV_FIELD_ENCLOSE_CHAR_DESC' ); ?></span></div>
</div></div>


<div class="form-inline m10x">
<div class="control-group">
<label for="record_separator required"><?php echo JText::_( 'FLEXI_CSV_ITEM_SEPARATOR' ); ?></label>
<div class="span3">
<input type="text" name="record_separator" id="record_separator" value="<?php echo htmlspecialchars($params->get('csv_item_record_sep','\n~~')); ?>" class="fcfield_textval" /></div> 
<div class="span3 m10s"><span class="fc-mssg-inline fc-info"><?php echo JText::_( 'FLEXI_CSV_ITEM_SEPARATOR_DESC' ); ?></span></div>
</div></div>

<div class="form-inline m10x">
<div class="control-group">
<label for="csvfile"><?php echo JText::_( 'FLEXI_CSV_DISPLAY_FIELDS_OF_FIRST_RECORDS' ); ?></label>
<div class="span3">
<input type="text" name="debug" id="debug" value="<?php echo (int)$params->get('csv_debug_records','2'); ?>" class="fcfield_textval" /></div> 
<div class="span3 m10s"><span class="fc-mssg-inline fc-warning"><?php echo JText::_( 'FLEXI_CSV_DISPLAY_FIELDS_OF_FIRST_RECORDS_DESC' ); ?></span></div>
</div></div>


          <div class="m20"></div>
                    <h2><?php echo JText::_( 'FLEXI_CSV_FILE_FILE_FOLDERS_LEGEND' ); ?></h2>
<hr>

<div class="form-inline m10x">
<div class="control-group">
<label for="csvfile" class="required"><?php echo JText::_( 'FLEXI_CSVFILE' ); ?></label>
<div class="span3">
<input type="file" name="csvfile" id="csvfile" value="" class="" /></div> 
<div class="span3 m10s"><span class="fc-mssg-inline fc-success"><?php echo JText::_( 'FLEXI_IMPORT_ABOUT_NEW_FILES' ); ?> </span></div>
</div></div>

<div class="form-inline m10x">
<div class="control-group">
<label for="import_items_per_step" class="required"><?php echo JText::_( 'FLEXI_IMPORT_ITEMS_PER_STEP' ); ?></label>
<div class="span3"><input type="text" name="import_items_per_step" id="import_items_per_step" value="<?php echo $params->get('import_items_per_step',5); ?>" class="fcfield_textval" size="40"/></div> 
<div class="span3 m10s"><span class="fc-mssg-inline fc-info"><?php echo JText::_( 'FLEXI_IMPORT_ITEMS_PER_STEP_DESC' ); ?></span></div>
</div></div>

<div class="form-inline m10x">
<div class="control-group">
<label for="import_media_folder"><?php echo JText::_( 'FLEXI_IMPORT_MEDIA_FOLDER' ); ?>
							</label>
<div class="span3"><input type="text" name="import_media_folder" id="import_media_folder" value="<?php echo $params->get('import_media_folder','tmp/fcimport_media'); ?>" class="fcfield_textval" size="40"/></div> 
<div class="span3 m10s"><span class="fc-mssg-inline fc-info"><?php echo JText::_( 'FLEXI_IMPORT_FOLDER_DESC' ); ?></span></div>
</div></div>

<div class="form-inline m10x">
<div class="control-group">
<label for="import_docs_folder"><?php echo JText::_( 'FLEXI_IMPORT_DOCUMENTS_FOLDER' ); ?></label>
<div class="span3"><input type="text" name="import_docs_folder" id="import_docs_folder" value="<?php echo $params->get('import_docs_folder','tmp/fcimport_docs'); ?>" class="fcfield_textval" size="40"/></div> 
<div class="span3 m10s"><span class="fc-mssg-inline fc-info"><?php echo JText::_( 'FLEXI_IMPORT_FOLDER_DESC' ); ?></span></div>
</div></div>

<div class="form-inline m10x">
<div class="control-group">
<label for="import_docs_folder"><?php echo JText::_( 'FLEXI_IMPORT_SKIP_FILE_CHECK' ); ?></label>
<div class="span3"><?php foreach ($this->file_fields as $i=> $file_fieid) : ?>
								<input type="checkbox" id="skip_file_field_<?php echo $i; ?>" name="skip_file_field[]" value="<?php echo $file_fieid->name; ?>" />
								<label for="skip_file_field_<?php echo $i; ?>" class=""><?php echo $file_fieid->label." <small>[".$file_fieid->name."]</small>"; ?></label>
							<?php endforeach; ?></div> 
<div class="span3 m10s"><span class="fc-mssg-inline fc-note"><?php echo JText::_( 'FLEXI_IMPORT_SKIP_FILE_CHECK_DESC' ); ?></span></div>
</div></div>

		

</div>
<!--/CONTENT1-->

<h2><?php echo JText::_( 'FLEXI_IMPORT_CSV_FILE_FORMAT_EXPLANATION' ); ?></h2>
<hr>
<b>1. First line</b> of the CSV file: &nbsp; &nbsp; must contain the <b>field names</b> <u>(and not the field labels!)</u><br/><br/>
<b>2. Field separator</b> of the CSV file: &nbsp; &nbsp; separate fields with a string<b>, that does not appear inside the data</b>, e.g. &nbsp; ~~<br/><br/>
<b>3. Item separator</b> of the CSV file: &nbsp; &nbsp; separate items with a string<b>, that does not appear inside the data</b>, e.g. &nbsp; \n~~<br/><br/>
<b>4. Supported fields:</b><br/><br/>
-- a. <b>basic fields</b>: title, description, text fields, <br/><br/>

-- b. <b>single-value fields</b>: select, radio, radioimage, <br/><br/>

-- c. <b>multi-value fields</b>: selectmultiple, selectmultple, checkbox, checkboximage, email, weblink, <b>separate multiple values with %%</b><br/><br/>

-- d. <b>multi-property per value fields</b>: e.g. email fields & weblink fields,
<br/> <u>either enter like</u>: <b>usera@somedomain.com</b>,
<br/> <u>or enter like</u>: <b>[-propertyname-]=propertyvalue</b>, and <b>separate</b> mutliple properties with !!
<br/> - <b>email field</b> properties: addr, text
<br/> - <b>weblink field</b> properties: link, title, hits
<br/> - <b>extendedweblink field</b> properties: link, title, linktext, class, id
<br/> - ... etc
<br/><br/>

-- e. <b>image/gallery</b> must contain the file name (<b>new file</b>),
<br/> <b>NOTE:</b> NEW files must be placed inside media folder,
<br/> <b>NOTE:</b> It can be the name of an existing image name (if image field is in DB-mode)
<br/> <b>NOTE:</b> since image field is multi-property / multi-value it can use format of these fields too,
<br/> properties are: originalname, alt, title, desc, urllink <br/><br/>

-- f. <b>file</b> field, must contain the file name (<b>new file</b>), OR it can be the id of an existing document (filemanager 's file ID)
<br/> <b>NOTE:</b> NEW files must be placed inside the document folder<br/><br/>

<u>CSV example Format:</u> <br/>

<span class="fcimport_sampleline">title ~~ text ~~ textfield3 ~~ emailfield6 ~~ weblinkfld8 ~~ single_value_field22 ~~ multi_value_field24 </span><br/>
<span class="fcimport_sampleline">~~ title 1 ~~ description 1 ~~ textfield3 value ~~ [-addr-]=usera@somedomain.com!![-text-]=usera ~~ www.somedomaina.com ~~ f22_valuea ~~ f24_value01%%f24_value02%%f24_value03 </span><br/>
<span class="fcimport_sampleline">~~ title 2 ~~ description 2 ~~ textfield3 value ~~ [-addr-]=userb@somedomain.com!![-text-]=userb ~~ www.somedomainb.com ~~ f22_valuea ~~ f24_value04%%f24_value05%%f24_value06 </span><br/>
<span class="fcimport_sampleline">~~ title 3 ~~ description 3 ~~ textfield3 value ~~ [-addr-]=userc@somedomain.com!![-text-]=userc ~~ www.somedomainc.com ~~ f22_valuea ~~ f24_value07%%f24_value08%%f24_value09 </span><br/>
<span class="fcimport_sampleline">~~ title 4 ~~ description 4 ~~ textfield3 value ~~ userd@somedomain.com ~~ [-link-]=www.somedomaind.com!![-title-]=somedomainD ~~ f22_valuea ~~ f24_value10%%f24_value11%%f24_value12 </span><br/>
<span class="fcimport_sampleline">~~ title 5 ~~ description 5 ~~ textfield3 value ~~ usere@somedomain.com ~~ [-link-]=www.somedomaine.com!![-title-]=somedomainE ~~ f22_valuea ~~ f24_value13%%f24_value14%%f24_value15 </span><br/>

<br/>
<p><?php echo JText::_( 'FLEXI_3RD_PARTY_DEV_IMPORT_EXPORT_TOOLS' ); ?>
<a id="tools_3rd_party"></a>
					<?php echo $this->fleximport; ?></p>

</div>
</div>
</div>

	
	<input type="hidden" name="option" value="com_flexicontent" />
	<input type="hidden" name="controller" value="import" />
	<input type="hidden" name="view" value="import" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>