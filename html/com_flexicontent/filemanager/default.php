<?php
/**
 * @version 1.5 stable $Id: default.php 1929 2014-07-08 17:04:16Z ggppdk $
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

defined( '_JEXEC' ) or die( 'Restricted access' );

$ctrl_task  = FLEXI_J16GE ? 'task=filemanager.'  :  'controller=filemanager&amp;task=';
$ctrl_task_authors = FLEXI_J16GE ? 'task=users.'  :  'controller=users&amp;task=';
$permissions = FlexicontentHelperPerm::getPerm();
$session = JFactory::getSession();
$document = JFactory::getDocument();

$close_btn = FLEXI_J30GE ? '<a class="close" data-dismiss="alert">&#215;</a>' : '<a class="fc-close" onclick="this.parentNode.parentNode.removeChild(this.parentNode);">&#215;</a>';
$alert_box = FLEXI_J30GE ? '<div %s class="alert alert-%s %s">'.$close_btn.'%s</div>' : '<div %s class="fc-mssg fc-%s %s">'.$close_btn.'%s</div>';

// Load JS tabber lib
$document->addScript( JURI::root().'components/com_flexicontent/assets/js/tabber-minimized.js' );
$document->addStyleSheet( JURI::root().'components/com_flexicontent/assets/css/tabber.css' );
$document->addScriptDeclaration(' document.write(\'<style type="text/css">.fctabber{display:none;}<\/style>\'); ');  // temporarily hide the tabbers until javascript runs
?>

<div id="flexicontent" class="flexicontent">
<div class="m20x"></div>
<?php if (!$this->CanUpload) :?>
	<?php echo sprintf( $alert_box, '', 'note', '', JText::_('FLEXI_YOUR_ACCOUNT_CANNOT_UPLOAD') ); ?>
<?php endif; ?>


<div class="fctabber" style=''>
	<?php
	//echo FLEXI_J16GE ? JHtml::_('tabs.start') : $this->pane->startPane( 'stat-pane' );
	?>
	
	<!-- File(s) by uploading -->
	
	<?php if ($this->CanUpload):
		//echo FLEXI_J16GE ?
		//	JHtml::_('tabs.panel', JText::_( 'FLEXI_UPLOAD_LOCAL_FILE' ), 'local' ) :
		//	$this->pane->startPanel( JText::_( 'FLEXI_UPLOAD_LOCAL_FILE' ), 'local' ) ;
	?>
	<div class="tabbertab" style="padding: 0px;" id="local_tab" >
		<h3 class="tabberheading"> <?php echo JText::_( 'FLEXI_UPLOAD_LOCAL_FILE' ); ?> </h3>
		<?php if ($this->require_ftp): ?>
        <form action="index.php?option=com_flexicontent&amp;<?php echo $ctrl_task; ?>ftpValidate" name="ftpForm" id="ftpForm" method="post">
            
                <h3><?php echo JText::_( 'FLEXI_DESCFTPTITLE' ); ?></h3>
                <hr>
                <p><?php echo JText::_( 'FLEXI_DESCFTP' ); ?></p>
<div class="form-horizontal"> 

  <div class="form-inline m10x">
                <div class="control-group">
                  <label class="control-label required" for="jform_title"><?php echo JText::_( 'FLEXI_USERNAME' ); ?></label>
                  <div class="controls"><input type="text" id="username" name="username" class="input_box"  value="" /></div>
                </div>
              </div>
              

  <div class="form-inline m10x">
                <div class="control-group">
                                <label for="password"  class="control-label required"><?php echo JText::_( 'FLEXI_PASSWORD' ); ?>:</label>
                    <div class="controls">
                                <input type="password" id="password" name="password" class="input_box"  value="" />
                      </div></div></div>
                      
                      </div>
            
        </form>
		<?php endif; ?>
	
	<!-- File Upload Form -->
	<fieldset class="filemanager-tab" >
		<legend><?php echo JText::_( 'FLEXI_CHOOSE_FILE' ); ?> [ <?php echo JText::_( 'FLEXI_MAX' ); ?>&nbsp;<?php echo ($this->params->get('upload_maxsize') / 1000000); ?>M ]</legend>
				
	
		<fieldset class="actions" id="filemanager-1">
			<form action="<?php echo JURI::base(); ?>index.php?option=com_flexicontent&amp;<?php echo $ctrl_task; ?>upload&amp;<?php echo $session->getName().'='.$session->getId(); ?>" name="uploadFileForm" id="uploadFileForm" method="post" enctype="multipart/form-data">
				
                <div class="row-fluid">
                <div class="span6 w100">
                 <div class="form-horizontal">
               <!--CONTENT--> 
                <!--1-->
               <div class="form-inline m10x">
                <div class="control-group">   
                <label for="file-upload" class="required">
							<?php echo JText::_( 'FLEXI_CHOOSE_FILE' ); ?>
							</label>
                            <div class="controls">
                <input type="file" id="file-upload" name="Filedata" />
                </div>
                </div>
                </div>
                <!--1-->
                
             
                
                     <!--2-->
               <div class="form-inline m10x">
                <div class="control-group">   
            <label class="hasTip"  title="<?php echo JText::_( 'FLEXI_CHOOSE_DIR' ); ?>">
							<?php echo JText::_( 'FLEXI_FILE_DIRECTORY' ); ?>
							</label>
                     
               <?php echo JHTML::_('select.booleanlist', 'secure', 'class="inputbox"', 1, JText::_( 'FLEXI_SECURE' ), JText::_( 'FLEXI_MEDIA' ), 'secure_uploadFileForm' ); ?>
               
               
               
                </div>
                </div>
                <!--2-->
                
                   <!--3-->
               <div class="form-inline m10x">
                <div class="control-group">   
          <label for="file-title">
							<?php echo JText::_( 'FLEXI_FILE_TITLE' ); ?>
							</label>
                            <div class="controls">
             <input type="text" id="file-title" size="44" class="required" name="file-title" />
                </div>
                </div>
                </div>
                <!--3-->
                <!--/CONTENT-->
                </div>
                </div>
                <div class="span6 w100">
                <div class="form-horizontal">
                <!--CONTENT-->
                        <!--2-->
               <div class="form-inline m10x">
                <div class="control-group">   
               <label>
							<?php echo JText::_( 'FLEXI_LANGUAGE' ); ?>
							</label>
                            <div class="controls">
               <?php echo $this->lists['file-lang']; ?>
                </div>
                </div>
                </div>
                <!--2-->
                
                 <!--3-->
               <div class="form-inline m10x">
                <div class="control-group">   
          <label for="file-desc_uploadFileForm">
							<?php echo JText::_( 'FLEXI_DESCRIPTION' ); ?>
							</label>
                            <div class="controls">
              <textarea name="file-desc" cols="24" rows="3" id="file-desc_uploadFileForm"></textarea>
                </div>
                </div>
                </div>
                <!--3-->
                <!--/CONTENT-->
                </div>
                </div>
                </div>
		
				
				<input type="submit" id="file-upload-submit" class="btn btn-primary validate" value="<?php echo JText::_( 'FLEXI_START_UPLOAD' ); ?>"/>
				<span id="upload-clear"></span>
				
				<?php echo JHTML::_( 'form.token' ); ?>
				<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_flexicontent&view=filemanager'); ?>" />
			</form>
			
		</fieldset>
		</fieldset>

	
	</div>
	<?php
	//echo FLEXI_J16GE ? '' : $this->pane->endPanel();
	?>
	<?php endif; ?>
	
	
	<!-- File URL by Form -->
	<?php
		//echo FLEXI_J16GE ?
		//	JHtml::_('tabs.panel', JText::_( 'FLEXI_ADD_FILE_BY_URL' ), 'fileurl' ) :
		//	$this->pane->startPanel( JText::_( 'FLEXI_ADD_FILE_BY_URL' ), 'fileurl' ) ;
	?>
	<div class="tabbertab" id="fileurl_tab" >
		<h3 class="tabberheading"> <?php echo JText::_( 'FLEXI_ADD_FILE_BY_URL' ); ?> </h3>
	
	<form action="<?php echo JURI::base(); ?>index.php?option=com_flexicontent&amp;<?php echo $ctrl_task; ?>addurl&amp;<?php echo $session->getName().'='.$session->getId(); ?>&amp;<?php echo (FLEXI_J30GE ? JSession::getFormToken() : JUtility::getToken());?>=1" class="form-validate" name="addUrlForm" id="addUrlForm" method="post">
    
    <div class="form-horizontal">
    <fieldset class="filemanager-tab" >
			<legend><?php echo JText::_( 'FLEXI_ADD_FILE_BY_URL' ); ?></legend>
			<fieldset class="actions" id="filemanager-2">
            
            <div class="row-fluid">
            <div class="span6 w100">
            <!--Col1-->
            <div class="form-inline m10x">
  <div class="control-group">
    <label for="file-url-data"> <?php echo JText::_( 'FLEXI_FILE_URL' ); ?> </label>
    <div class="controls">
      <input type="text" id="file-url-data" size="44" class="required" name="file-url-data" />
    </div>
  </div>
</div>

<div class="form-inline m10x">
  <div class="control-group">
    <label for="file-url-ext"> <?php echo JText::_( 'FLEXI_FILEEXT' ); ?> </label>
    <div class="controls">
      <input type="text" id="file-url-ext" size="5" class="required" name="file-url-ext" />
    </div>
  </div>
</div>
         
       <div class="form-inline m10x">
  <div class="control-group">
    <label for="file-url-title"> <?php echo JText::_( 'FLEXI_FILE_TITLE' ); ?> </label>
    <div class="controls">
      <input type="text" id="file-url-title" size="44" class="required" name="file-url-title" />
    </div>
  </div>
</div>  
          <!--/COL1-->
            </div>
            <div class="span6 w100">
            <!--COL2-->
            <div class="form-inline m10x">
  <div class="control-group">
    <label> <?php echo JText::_( 'FLEXI_LANGUAGE' ); ?> </label>
    <div class="controls"> <?php echo str_replace('file-lang', 'file-url-lang', $this->lists['file-lang']); ?> </div>
  </div>
</div>

<div class="form-inline m10x">
  <div class="control-group">
    <label for="file-url-desc"> <?php echo JText::_( 'FLEXI_DESCRIPTION' ); ?> </label>
    <div class="controls">
      <textarea name="file-url-desc" cols="24" rows="3" id="file-url-desc"></textarea>
    </div>
  </div>
</div>
            <!--/COL2-->
            </div>
            </div>
    </fieldset>
    </fieldset>
    
    </div>
		
				
				
				
				<input type="submit" id="file-url-submit" class="btn btn-primary validate" value="<?php echo JText::_( 'FLEXI_ADD_FILE' ); ?>"/>
			</fieldset>
		</fieldset>
		<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_flexicontent&view=filemanager'); ?>" />
	</form>
	<?php /*echo FLEXI_J16GE ? '' : $this->pane->endPanel();*/ ?>
	</div>
	
	
	<!-- File(s) from server Form -->
	<?php
	if ($this->CanUpload) :
		/*echo FLEXI_J16GE ?
			JHtml::_('tabs.panel', JText::_( 'FLEXI_ADD_FILE_FROM_SERVER' ), 'server' ) :
			$this->pane->startPanel( JText::_( 'FLEXI_ADD_FILE_FROM_SERVER' ), 'server' ) ;*/
	?>
	<div class="tabbertab" style="padding: 0px;" id="server_tab" >
		<h3 class="tabberheading"> <?php echo JText::_( 'FLEXI_ADD_FILE_FROM_SERVER' ); ?> </h3>
	
	<form action="index.php?option=com_flexicontent&amp;<?php echo $ctrl_task; ?>addlocal&amp;<?php echo $session->getName().'='.$session->getId(); ?>&amp;<?php echo (FLEXI_J30GE ? JSession::getFormToken() : JUtility::getToken());?>=1" class="form-validate" name="addFileForm" id="addFileForm" method="post">
    <div class="form-horizontal">
		<fieldset class="filemanager-tab" >
			<legend>
				<?php echo JText::_( 'FLEXI_ADD_FILE_FROM_SERVER' ); ?>
			</legend>
			<fieldset class="actions" id="filemanager-3">

			<div class="row-fluid">	
				<div class="span6 w100">
                <!--col1-->
                <div class="form-inline m10x">
  <div class="control-group">
    <label for="file-dir-path" class="required hasTip"  title="<?php echo JText::_( 'FLEXI_CHOOSE_DIR_PATH_DESC' ); ?>"> <?php echo JText::_( 'FLEXI_CHOOSE_DIR_PATH' ); ?> </label>
    <div class="controls">
      <input type="text" id="file-dir-path" size="50" value="/tmp" class="required" name="file-dir-path" />
    </div>
  </div>
</div>
<div class="form-inline m10x">
  <div class="control-group">
    <label for="file-filter-ext" class="hasTip"  title="<?php echo JText::_( 'FLEXI_FILE_FILTER_EXT' ); ?>::<?php echo JText::_( 'FLEXI_FILE_FILTER_EXT_DESC' ); ?>"> <?php echo JText::_( 'FLEXI_FILE_FILTER_EXT' ); ?> </label>
    <div class="controls">
      <input type="text" id="file-filter-ext" size="50" value="" name="file-filter-ext" />
    </div>
  </div>
</div>

<div class="form-inline m10x">
  <div class="control-group">
    <label for="file-filter-re" class="hasTip"  title="<?php echo JText::_( 'FLEXI_FILE_FILTER_REGEX' ); ?>::<?php echo JText::_( 'FLEXI_FILE_FILTER_REGEX_DESC' ); ?>"> <?php echo JText::_( 'FLEXI_FILE_FILTER_REGEX' ); ?> </label>
    <div class="controls">
      <input type="text" id="file-filter-re" size="50" value="" name="file-filter-re" />
    </div>
  </div>
</div>

<div class="form-inline m10x">
  <div class="control-group">
    <label class="hasTip"  title="<?php echo JText::_( 'FLEXI_CHOOSE_DIR' ); ?>"> <?php echo JText::_( 'FLEXI_FILE_DIRECTORY' ); ?> </label>
    <div class="controls">
      <?php
							echo JHTML::_('select.booleanlist', 'secure', 'class="inputbox"', 1, JText::_( 'FLEXI_SECURE' ), JText::_( 'FLEXI_MEDIA' ), 'secure_addFileForm' );
							?>
    </div>
  </div>
</div>
                <!--/col1-->
                </div>
                <div class="span6 w100">
                <!--col2-->
                <div class="form-inline m10x">
  <div class="control-group">
    <label> <?php echo JText::_( 'FLEXI_LANGUAGE' ); ?> </label>
    <div class="controls"> <?php echo
								str_replace('id="file-lang', 'id="_file-lang',
								str_replace('id="file-lang', 'id="_file-lang', $this->lists['file-lang'])
								); ?> </div>
  </div>
</div>
<div class="form-inline m10x">
  <div class="control-group">
    <label for="file-desc_addFileForm"> <?php echo JText::_( 'FLEXI_DESCRIPTION' ); ?> </label>
    <div class="controls">
      <textarea name="file-desc" cols="24" rows="6" id="file-desc_addFileForm"></textarea>
    </div>
  </div>
</div>
          
          
          <div class="form-inline m10x">
  <div class="control-group">
    <label class="key hasTip"  title="<?php echo JText::_( 'FLEXI_KEEP_ORIGINAL_FILE_DESC' ); ?>"> <?php echo JText::_( 'FLEXI_KEEP_ORIGINAL_FILE' ); ?> </label>
    <div class="controls">
      <?php
							echo JHTML::_('select.booleanlist', 'keep', 'class="inputbox"', 1, JText::_( 'FLEXI_YES' ), JText::_( 'FLEXI_NO' ) );
							?>
    </div>
  </div>
</div>      <!--/col2-->
                </div>
				</div><input type="submit" id="file-dir-submit" class="btn btn-primary validate" value="<?php echo JText::_( 'FLEXI_ADD_DIR' ); ?>"/>
			</fieldset>
		</fieldset>
		<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_flexicontent&view=filemanager'); ?>" />
        </div>
	</form>
	<?php /*echo FLEXI_J16GE ? '' : $this->pane->endPanel();*/ ?>
	</div>
	<?php endif; ?>
	
	<?php /*echo FLEXI_J16GE ? JHtml::_('tabs.end') : $this->pane->endPane();*/ ?>

</div><!-- fctabber end -->


			
<div class="fcclear m20"></div>

<form action="<?php echo JURI::base(); ?>index.php" method="post" name="adminForm" id="adminForm">
<div class="row-fluid">
<div class="span12">
<div class="block-flat">
<div class="row-fluid filterbuttons_head">
<!--SEARCH-->

<div class="span5 w100">
<label class="label"><?php echo JText::_( 'FLEXI_SEARCH' ); ?></label>
				<?php echo $this->lists['filter']; ?>
				<input type="text" name="search" id="search" value="<?php echo $this->lists['search']; ?>" class="text_area" onchange="document.adminForm.submit();" />
				<div id="fc-filter-buttons">
					<button class="fc_button fcsimple" onclick="this.form.submit();"><?php echo JText::_( 'FLEXI_GO' ); ?></button>
					<button class="fc_button fcsimple" onclick="this.form.getElementById('search').value='';this.form.submit();"><?php echo JText::_( 'FLEXI_RESET' ); ?></button>
</div>   </div>                       
<!--SEARCH-->
<div class="span7 text-right line w100">
<!--FILTER-->
<div class="smallx">
<div class="s-cats"><?php echo $this->lists['language']; ?></div>
				<div class="s-cats"><?php echo $this->lists['url']; ?></div>
			 	<div class="s-cats"><?php echo $this->lists['secure']; ?></div>
			 	<div class="s-cats"><?php echo $this->lists['ext']; ?></div>
			 	<div class="s-cats"><?php if ($this->CanViewAllFiles) echo $this->lists['uploader']; ?></div>
			 	<div class="s-cats"><label class="label">Item ID</label> <?php echo $this->lists['item_id']; ?></div>
                
                </div>
                <!--/FILTER-->
</div>
</div>
</div>
</div>
</div>

<!--PAGINATION-->
<div class="row-fluid">
<div class="span12  text-right">
<div class="limit m10l hidden-tablet hidden-phone">
				<?php
					echo JText::_(FLEXI_J16GE ? 'JGLOBAL_DISPLAY_NUM' : 'DISPLAY NUM');
					$pagination_footer = $this->pagination->getListFooter();
					if (strpos($pagination_footer, '"limit"') === false) echo $this->pagination->getLimitBox();
					?>
                    	<span class="fc_item_total_data fc_nice_box m10l">
<?php echo @$this->resultsCounter ? $this->resultsCounter : $this->pagination->getResultsCounter(); // custom Results Counter ?></span>
				
				<span class="fc_pages_counter">
	<?php echo $this->pagination->getPagesCounter(); ?>	</span>
				</div>
</div>
</div>
<!--/PAGINATION-->
<div class="row-fluid">
<div class="span12">
<div class="block-flat">
<div class="table-responsive">
	<table class="no-border hover">
	<thead class="no-border">
		<tr>
			<th class="hidden-tablet hidden-phone"><?php echo JText::_( 'FLEXI_NUM' ); ?></th>
			<th><input type="checkbox" name="toggle" value="" onclick="<?php echo FLEXI_J30GE ? 'Joomla.checkAll(this);' : 'checkAll('.count( $this->rows).');'; ?>" /></th>
			<th><?php echo JText::_( 'FLEXI_THUMB' ); ?></th>
			<th class="title"><?php echo JHTML::_('grid.sort', 'FLEXI_FILENAME', 'f.filename', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="hidden-tablet hidden-phone"><?php echo JHTML::_('grid.sort', 'FLEXI_ORIGINAL_FILENAME', 'f.filename_original', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th  class="hidden-tablet hidden-phone"><?php echo JHTML::_('grid.sort', 'FLEXI_FILE_TITLE', 'f.altname', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th nowrap="nowrap"><?php echo JText::_( 'FLEXI_PUBLISHED' ); ?></th>
			<th><?php echo JText::_( 'FLEXI_ACCESS' ); ?></th>
			<th class="hidden-phone"><?php echo JText::_( 'FLEXI_LANGUAGE' ); ?></th>
			<th class="hidden-phone"><?php echo JText::_( 'FLEXI_SIZE' ); ?></th>
			<th class="hidden-tablet hidden-phone"><?php echo JHTML::_('grid.sort', 'FLEXI_HITS', 'f.hits', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th><?php echo JText::_( 'FLEXI_FILE_ITEM_ASSIGNMENTS' ); ?> </th>
			<th class="hidden-tablet hidden-phone"><?php echo JHTML::_('grid.sort', 'FLEXI_UPLOADER', 'uploader', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="hidden-tablet hidden-phone"><?php echo JHTML::_('grid.sort', 'FLEXI_UPLOAD_TIME', 'f.uploaded', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th nowrap="nowrap" class="hidden-phone"><?php echo JHTML::_('grid.sort', 'FLEXI_ID', 'f.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		</tr>
		
	</thead>

	
	<tbody>
		<?php
		$imageexts = array('jpg','gif','png','bmp');
		$index = JRequest::getInt('index', 0);
		$k = 0;
		$i = 0;
		$n = count($this->rows);
		foreach ($this->rows as $row) {
			unset($thumb_or_icon);
			$filename    = str_replace( array("'", "\""), array("\\'", ""), $row->filename );
			$filename_original = str_replace( array("'", "\""), array("\\'", ""), $row->filename_original );
			$display_filename  = $filename_original ? $filename_original : $filename;
			
			if ( !in_array($row->ext, $imageexts)) $thumb_or_icon = JHTML::image($row->icon, $row->filename);
			
			$checked 	= @ JHTML::_('grid.checkedout', $row, $i );
			
			$path		= $row->secure ? COM_FLEXICONTENT_FILEPATH : COM_FLEXICONTENT_MEDIAPATH;  // JPATH_ROOT . DS . <media_path | file_path>
			$file_path = $row->filename;
			
			if (substr($row->filename, 0, 7)!='http://') {
				$file_path = $path . DS . $row->filename;
			} else {
				$thumb_or_icon = 'URL';
			}
			
			$file_path    = str_replace('\\', '/', $file_path);
			if ( empty($thumb_or_icon) ) {
				$thumb_or_icon = JURI::root() . 'components/com_flexicontent/librairies/phpthumb/phpThumb.php?src=' . $file_path . '&w=60&h=60';
				$thumb_or_icon = "<img src=\"$thumb_or_icon\" alt=\"$display_filename\" />";
			}
			
			$row->count_assigned = 0;
			foreach($this->assigned_fields_labels as $field_type => $ignore) {
				$row->count_assigned += $row->{'assigned_'.$field_type};
			}
			if ($row->count_assigned)
			{
				$row->assigned = array();
				foreach($this->assigned_fields_labels as $field_type => $field_label) {
					if ( $row->{'assigned_'.$field_type} )
					{
						$icon_name = $this->assigned_fields_icons[$field_type];
						$tip = $row->{'assigned_'.$field_type} . ' ' . $field_label;
						$image = JHTML::image('administrator/templates/flexi/images/flexi/'.$icon_name.'.png', $tip, 'title="'.$field_type.' '.JText::_('FLEXI_FIELDS').'"' );
						$row->assigned[] = $row->{'assigned_'.$field_type} . ' ' . $image;
					}
				}
				$row->assigned = implode('&nbsp;&nbsp;| ', $row->assigned);
			} else {
				$row->assigned = JText::_( 'FLEXI_NOT_ASSIGNED' );
			}
			// link to items using the field
			$items_list = 'index.php?option=com_flexicontent&amp;view=items&amp;filter_fileid='. $row->id;
   		?>
		<tr class="<?php echo "row$k"; ?>">
			<td class="hidden-tablet hidden-phone"><?php echo $this->pagination->getRowOffset( $i ); ?></td>
			<td>
				<?php echo $checked; ?>
			</td>
			<td align="center">
				<span class="editlinktip hasTip" title="<?php echo JText::_( 'FLEXI_SELECT' ); ?>::<?php echo $row->filename; ?>">
				<a style="cursor:pointer">
				<?php echo $thumb_or_icon; ?>
				</a>
				</span>
			</td>
			<td align="left" class="hidden-tablet hidden-phone">
				<?php
					if (JString::strlen($row->filename) > 100) {
						$filename = JString::substr( htmlspecialchars($row->filename, ENT_QUOTES, 'UTF-8'), 0 , 25).'...';
					} else {
						$filename = htmlspecialchars($row->filename, ENT_QUOTES, 'UTF-8');
					}
				?>
				<span class="editlinktip hasTip" title="<?php echo JText::_('FLEXI_FILENAME'); ?>::<?php echo htmlspecialchars($row->filename, ENT_QUOTES, 'UTF-8'); ?>">
				<?php echo ' <a href="index.php?option=com_flexicontent&amp;'.$ctrl_task.'edit&amp;cid[]='.$row->id.'">'.$filename.'</a>'; ?>
				</span>
			</td>
			<td  class="hidden-tablet hidden-phone">
				<?php
					if (JString::strlen($row->filename_original) > 100) {
						$filename = JString::substr( htmlspecialchars($row->filename_original, ENT_QUOTES, 'UTF-8'), 0 , 25).'...';
					} else {
						$filename = htmlspecialchars($row->filename_original, ENT_QUOTES, 'UTF-8');
					}
				?>
				<span class="editlinktip hasTip" title="<?php echo JText::_('FLEXI_FILENAME'); ?>::<?php echo htmlspecialchars($row->filename_original, ENT_QUOTES, 'UTF-8'); ?>">
				<?php echo $row->filename_original; ?>
				</span>
			</td>
			<td>
				<?php
				if (JString::strlen($row->altname) > 100) {
					echo JString::substr( htmlspecialchars($row->altname, ENT_QUOTES, 'UTF-8'), 0 , 25).'...';
				} else {
					echo htmlspecialchars($row->altname, ENT_QUOTES, 'UTF-8');
				}
				?>
			</td>
			<td align="center">
				<?php echo FLEXI_J16GE  ?  JHTML::_('jgrid.published', $row->published, $i, 'filemanager.' )  :  JHTML::_('grid.published', $row, $i ); ?>
			</td>
			
			<td align="center">
			<?php
			$is_authorised = $this->CanFiles && ($this->CanViewAllFiles || $user->id == $row->uploaded_by);
			if (FLEXI_J16GE) {
				if ($is_authorised) {
					$access = flexicontent_html::userlevel('access['.$row->id.']', $row->access, 'onchange="return listItemTask(\'cb'.$i.'\',\'filemanager.access\')"');
				} else {
					$access = strlen($row->access_level) ? $this->escape($row->access_level) : '-';
				}
			} else if (FLEXI_ACCESS) {
				if ($is_authorised) {
					$access 	= FAccess::accessswitch('file', $row, $i);
				} else {
					$access 	= FAccess::accessswitch('file', $row, $i, 'content', 1);
				}
			} else {
				$access = JHTML::_('grid.access', $row, $i );
			}
			echo $access;
			?>
			</td>
			
			<?php
			// Set a row language, even if empty to avoid errors
			$row->language = @$row->language ? $row->language : '*';
   		?>
			<td align="center" class="hasTip col_lang hidden-phone" title="<?php echo JText::_( 'FLEXI_LANGUAGE', true ).'::'.($row->language=='*' ? JText::_("All") : $this->langs->{$row->language}->name); ?>">
				<?php if ( !empty($row->language) && !empty($this->langs->{$row->language}->imgsrc) ) : ?>
					<img src="<?php echo $this->langs->{$row->language}->imgsrc; ?>" alt="<?php echo $row->language; ?>" />
				<?php elseif( !empty($row->language) ) : ?>
					<?php echo $row->language=='*' ? JText::_("FLEXI_ALL") : $row->language;?>
				<?php endif; ?>
			</td>
			
			<td align="center" class="hidden-phone"><?php echo $row->size; ?></td>
			<td align="center" class="hidden-tablet hidden-phone"><?php echo $row->hits; ?></td>
			<td align="center">
				<?php echo $row->assigned; ?>
				<?php if ($row->count_assigned) : ?>
					<br/><br/>
					<?php echo count($row->itemids); ?>
					<a href="<?php echo $items_list; ?>">
					[<?php echo JText::_( 'FLEXI_VIEW_ITEMS' );?>]
					</a>
				<?php endif; ?>
			</td>
			<td align="center"  class="hidden-tablet hidden-phone">
			<?php if ($permissions->CanAuthors) :?>
				<a target="_blank" href="index.php?option=com_flexicontent&amp;<?php echo $ctrl_task_authors; ?>edit&amp;hidemainmenu=1&amp;cid[]=<?php echo $row->uploaded_by; ?>">
					<?php echo $row->uploader; ?>
				</a>
			<?php else :?>
				<?php echo $row->uploader; ?>
			<?php endif; ?>
			</td>
			<td align="center"  class="hidden-tablet hidden-phone"><?php echo JHTML::Date( $row->uploaded, JText::_( 'DATE_FORMAT_LC2' ) ); ?></td>
			<td align="center"  class="hidden-phone"><?php echo $row->id; ?></td>
		</tr>
		<?php 
			$k = 1 - $k;
			$i++;
		} 
		?>
	</tbody>

	</table>
    
    <table class="borderfree">
		<tr>
			<td>
				<?php echo $pagination_footer; ?>
			</td>
		</tr>
		
		<?php
		$field_legend = array();
		$this->assigned_fields_labels;
		foreach($this->assigned_fields_labels as $field_type => $field_label) {
			$icon_name = $this->assigned_fields_icons[$field_type];
			$tip = $field_label;
			$image = JHTML::image('administrator/templates/flexi/images/flexi/'.$icon_name.'.png', $tip);
			$field_legend[$field_type] = $image. " ".$field_label;
		}
		?>
		
		<tr>
			<td colspan="14" align="center">
				<span class="fc_legend_box hasTip" title="<?php echo JText::_('FLEXI_FILE_ITEM_ASSIGNMENTS_LEGEND').'::'.JText::_('FLEXI_FILE_ITEM_ASSIGNMENTS_LEGEND_TIP'); ?> " ><?php echo JText::_('FLEXI_FILE_ITEM_ASSIGNMENTS_LEGEND'); ?></span> : &nbsp; 
				<?php echo implode(' &nbsp; &nbsp; | &nbsp; &nbsp; ', $field_legend); ?>
			</td>
		</tr>
				
	</table>


    </div>
 </div> </div> </div>
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_flexicontent" />
	<input type="hidden" name="view" value="filemanager" />
	<input type="hidden" name="controller" value="filemanager" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>
</div>