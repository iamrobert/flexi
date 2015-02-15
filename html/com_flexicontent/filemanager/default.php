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

defined('_JEXEC') or die('Restricted access');

$tip_class = FLEXI_J30GE ? ' hasTooltip' : ' hasTip';
$btn_class = FLEXI_J30GE ? 'btn' : 'fc_button fcsimple';
$hintmage = JHTML::image ( 'administrator/components/com_flexicontent/assets/images/comment.png', JText::_( 'FLEXI_NOTES' ), 'style="vertical-align:top;"' );

$start_text = '<span class="control-label <?php echo $tip_class; ?>">'.JText::_('FLEXI_COLUMNS', true).'</span>';
$end_text = '<div class="icon-cancel" title="'.JText::_('FLEXI_HIDE').'" style="cursor: pointer;" onclick="fc_toggle_box_via_btn(\\\'mainChooseColBox\\\', document.getElementById(\\\'fc_mainChooseColBox_btn\\\'), \\\'btn-primary\\\');"></div>';
flexicontent_html::jscode_to_showhide_table('mainChooseColBox', 'adminListTableFCfiles', $start_text, $end_text);
$ctrl_task  = FLEXI_J16GE ? 'task=filemanager.'  :  'controller=filemanager&amp;task=';
$ctrl_task_authors = FLEXI_J16GE ? 'task=users.'  :  'controller=users&amp;task=';
$permissions = FlexicontentHelperPerm::getPerm();
$session = JFactory::getSession();
$document = JFactory::getDocument();

// Common language strings
$edit_item_title = JText::_('FLEXI_EDIT_ITEM');
$usage_in_str = JText::_('FLEXI_USAGE_IN');
$fields_str = JText::_('FLEXI_FIELDS');

$close_btn = FLEXI_J30GE ? '<a class="close" data-dismiss="alert">&#215;</a>' : '<a class="fc-close" onclick="this.parentNode.parentNode.removeChild(this.parentNode);">&#215;</a>';
$alert_box = FLEXI_J30GE ? '<div %s class="alert alert-%s %s">'.$close_btn.'%s</div>' : '<div %s class="fc-mssg fc-%s %s">'.$close_btn.'%s</div>';

// Load JS tabber lib
$document->addScript(JURI::root(true).'/components/com_flexicontent/assets/js/tabber-minimized.js');
$document->addStyleSheet(JURI::root(true).'/components/com_flexicontent/assets/css/tabber.css');
$document->addScriptDeclaration(' document.write(\'<style type="text/css">.fctabber{display:none;}<\/style>\'); ');  // temporarily hide the tabbers until javascript runs

$list_total_cols = 14;
?>
<script type="text/javascript">

// delete active filter
function delFilter(name)
{
	//window.console.log('Clearing filter:'+name);
	var myForm = jQuery('#adminForm');
	var filter = jQuery('#'+name);
	if (filter.attr('type')=='checkbox')
		filter.checked = '';
	else
		filter.val('');
}

function delAllFilters() {
	delFilter('search'); delFilter('filter_assigned');  delFilter('filter_state');
}

</script>

<div id="flexicontent" class="flexicontent">

<?php if (!empty( $this->sidebar)) : ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
<?php else : ?>
	<div id="j-main-container">
<?php endif;?>

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
	<div class="tabbertab" id="local_tab" >
		<h3 class="tabberheading"> <?php echo JText::_( 'FLEXI_UPLOAD_LOCAL_FILE' ); ?> </h3>
		<?php if ($this->require_ftp): ?>
        <form action="index.php?option=com_flexicontent&amp;<?php echo $ctrl_task; ?>ftpValidate" name="ftpForm" id="ftpForm" method="post" class="form-horizontal">
            <fieldset title="<?php echo JText::_( 'FLEXI_DESCFTPTITLE' ); ?>">
                <legend><?php echo JText::_( 'FLEXI_DESCFTPTITLE' ); ?></legend>
                <p><?php echo JText::_( 'FLEXI_DESCFTP' ); ?></p>
         
         
           <div class="form-inline">
                <div class="control-group">
                                <label class="control-label required" for="username"><?php echo JText::_( 'FLEXI_USERNAME' ); ?>:</label>
               <div class="controls">
                                <input type="text" id="username" name="username" class="input_box" size="70" value="" />
                      </div>
                      </div>
                      </div>
                    <div class="form-inline">
                <div class="control-group">
                                <label class="control-label required" for="password"><?php echo JText::_( 'FLEXI_PASSWORD' ); ?>:</label>
                    <div class="controls">
                                <input type="password" id="password" name="password" class="input_box" size="70" value="" />
                           </div>
                      </div>
                      </div>
                                 </fieldset>
        </form>
		<?php endif; ?>
	
	<!-- File Upload Form -->
	<fieldset class="filemanager-tab" >
		<?php
		// Configuration
		$upload_maxsize = $this->params->get('upload_maxsize');
		$phpUploadLimit = flexicontent_upload::getPHPuploadLimit();
		$sys_limit_class = ($phpUploadLimit['value'] < $upload_maxsize) ? 'badge-warning' : '';
		
		echo '<span class="control-label label-info">'.JText::_( 'FLEXI_UPLOAD_LIMITS' ).'</span>'
			.'<i class="icon-info m20l '.$tip_class.'" title="'.flexicontent_html::getToolTip('FLEXI_CONF_UPLOAD_MAX_LIMIT', 'FLEXI_CONF_UPLOAD_MAX_LIMIT_DESC', 1, 1).'"></i>'
			.'<span class="badge badge">'.round($upload_maxsize / (1024*1024), 2).' M </span>'
			.'<i class="icon-info m20l '.$tip_class.'"   title="'.flexicontent_html::getToolTip(JText::_('FLEXI_SERVER_UPLOAD_MAX_LIMIT'), JText::sprintf('FLEXI_SERVER_UPLOAD_MAX_LIMIT_DESC', $phpUploadLimit['name']), 0, 1).'"></i>'
			.'<span class="badge '.$sys_limit_class.'">'.round($phpUploadLimit['value'] / (1024*1024), 2).' M </span>'
			;
		?>
		
		<fieldset class="actions" id="filemanager-1">
			<form action="<?php echo JURI::base(); ?>index.php?option=com_flexicontent&amp;<?php echo $ctrl_task; ?>upload&amp;<?php echo $session->getName().'='.$session->getId(); ?>" name="uploadFileForm" id="uploadFileForm" method="post" enctype="multipart/form-data" class="form-horizontal">
				
				<div class="fc-form-tbl" id="file-upload-form-container">
					
					<div class="form-inline">
<div class="control-group">
						
							<label  class="control-label <?php echo $tip_class; ?>" for="file-upload" id="file-upload-lbl" title="<?php echo flexicontent_html::getToolTip('FLEXI_CHOOSE_FILE', 'FLEXI_CHOOSE_FILE_DESC', 1, 1); ?>">
							<?php echo JText::_( 'FLEXI_CHOOSE_FILE' ); ?>
							</label>
						<div class="controls" id="file-upload-container">
							<input type="file" id="file-upload" name="Filedata" />
						</div>
                        </div>
                        </div>
						
                        <div class="form-inline">
<div class="control-group">
						
							<label class="control-label <?php echo $tip_class; ?>" id="file-title-lbl" for="file-title" title="<?php echo flexicontent_html::getToolTip('FLEXI_FILE_DISPLAY_TITLE', 'FLEXI_FILE_DISPLAY_TITLE_DESC', 1, 1); ?>">
							<?php echo JText::_( 'FLEXI_FILE_DISPLAY_TITLE' ); ?>
							</label>
						<div class="controls" id="file-title-container">
							<input type="text" id="file-title" size="44" class="required" name="file-title" />
					</div>
                        </div>
                        </div>
					
					<div class="form-inline">
<div class="control-group">
							<label class="control-label <?php echo $tip_class; ?>"  id="file-lang-lbl" for="file-lang" title="<?php echo flexicontent_html::getToolTip('FLEXI_LANGUAGE', 'FLEXI_FILE_LANGUAGE_DESC', 1, 1); ?>">
							<?php echo JText::_( 'FLEXI_LANGUAGE' ); ?>
							</label>
						<div class="controls" id="file-lang-container">
							<?php echo $this->lists['file-lang']; ?>
						</div>
                        </div>
                        </div>
						
                        
                        <div class="form-inline">
<div class="control-group">
						
							<label class="control-label required <?php echo $tip_class; ?>" id="file-desc-lbl" for="file-desc_uploadFileForm" title="<?php echo flexicontent_html::getToolTip('FLEXI_DESCRIPTION', 'FLEXI_FILE_DESCRIPTION_DESC', 1, 1); ?>">
							<?php echo JText::_( 'FLEXI_DESCRIPTION' ); ?>
							</label>
						
						<div class="controls"  id="file-desc-container">
							<textarea name="file-desc" cols="24" rows="3" id="file-desc_uploadFileForm"></textarea>
						</div>
                        </div>
                        </div>
					
					
					<div class="form-inline">
<div class="control-group">
						
							<label class="control-label <?php echo $tip_class; ?>" id="secure-lbl" title="<?php echo flexicontent_html::getToolTip('FLEXI_CHOOSE_DIRECTORY', 'FLEXI_CHOOSE_DIRECTORY_DESC', 1, 1); ?>">
							<?php echo JText::_( 'FLEXI_FILE_DIRECTORY' ); ?>
							</label>
						<div class="controls" id="secure-container">
							<?php
							//echo JHTML::_('select.booleanlist', 'secure', 'class="inputbox"', 1, JText::_( 'FLEXI_SECURE' ), JText::_( 'FLEXI_MEDIA' ), 'secure_uploadFileForm' );
							$_options = array();
							$_options['0'] = JText::_( 'FLEXI_MEDIA' );
							$_options['1'] = JText::_( 'FLEXI_SECURE' );
							echo flexicontent_html::buildradiochecklist($_options, 'secure', /*selected*/1, /*type*/1, /*attribs*/'', /*tagid*/'secure_uploadFileForm');
							?>

    </div>
                </div>
                </div>
                <div class="form-inline">
<div class="control-group"><div class="control-label">&nbsp;</div>
<div class="controls">
				<button type="submit" id="file-upload-submit" class="btn btn-primary " value="<?php echo JText::_( 'FLEXI_START_UPLOAD' ); ?>"/><?php echo JText::_( 'FLEXI_START_UPLOAD' ); ?></button>
				<span id="upload-clear"></span>
				
				<?php echo JHTML::_( 'form.token' ); ?>
				<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_flexicontent&view=filemanager'); ?>" />
                </div>
                </div>
                </div>
                
                </div>
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
	
	<form action="<?php echo JURI::base(); ?>index.php?option=com_flexicontent&amp;<?php echo $ctrl_task; ?>addurl&amp;<?php echo $session->getName().'='.$session->getId(); ?>&amp;<?php echo (FLEXI_J30GE ? JSession::getFormToken() : JUtility::getToken());?>=1" class="form-horizontal form-validate" name="addUrlForm" id="addUrlForm" method="post">
		<fieldset class="filemanager-tab" >
			<fieldset class="actions" id="filemanager-2">
				
				<div class="fc-form-tbl" id="file-url-form-container">
					
                    
                    
					<div class="form-inline">
                    <div class="control-group">
						<label class="control-label required <?php echo $tip_class; ?>" for="file-url-data" title="<?php echo flexicontent_html::getToolTip('FLEXI_FILE_URL', 'FLEXI_FILE_URL_DESC', 1, 1); ?>">
							<?php echo JText::_( 'FLEXI_FILE_URL' ); ?>
							</label>
						<div class="controls">
							<input type="text" id="file-url-data" size="44" class="required" name="file-url-data" />
					</div>
                    </div>
                    </div>
						<div class="form-inline">
                    <div class="control-group">
							<label class="control-label required <?php echo $tip_class; ?>" for="file-url-title" title="<?php echo flexicontent_html::getToolTip('FLEXI_FILE_DISPLAY_TITLE', 'FLEXI_FILE_DISPLAY_TITLE_DESC', 1, 1); ?>">
							<?php echo JText::_( 'FLEXI_FILE_DISPLAY_TITLE' ); ?>
							</label>
						<div class="controls">
							<input type="text" id="file-url-title" size="44" class="required" name="file-url-title" />
					</div>
                    </div>
                    </div>
						<div class="form-inline">
						  <div class="control-group">
							<label class="control-label <?php echo $tip_class; ?>" id="file-url-lang-lbl" for="file-url-lang" title="<?php echo flexicontent_html::getToolTip('FLEXI_LANGUAGE', 'FLEXI_FILE_LANGUAGE_DESC', 1, 1); ?>">
							<?php echo JText::_( 'FLEXI_LANGUAGE' ); ?>
							</label>
					<div class="controls">
							<?php echo str_replace('file-lang', 'file-url-lang', $this->lists['file-lang']); ?>
							</div>
                    </div>
                    </div>
					
                    
              
						<div class="form-inline">
						  <div class="control-group">
            
							<label class="control-label <?php echo $tip_class; ?>" for="file-url-desc" title="<?php echo flexicontent_html::getToolTip('FLEXI_DESCRIPTION', 'FLEXI_FILE_DESCRIPTION_DESC', 1, 1); ?>">
							<?php echo JText::_( 'FLEXI_DESCRIPTION' ); ?>
							</label>
					<div class="controls">
							<textarea name="file-url-desc" cols="24" rows="3" id="file-url-desc"></textarea>
						</div>
                    </div>
                    </div>
						<div class="form-inline">
						  <div class="control-group">
												<label class="control-label required <?php echo $tip_class; ?>" for="file-url-ext" title="<?php echo flexicontent_html::getToolTip('FLEXI_FILEEXT_MIME', 'FLEXI_FILEEXT_MIME_DESC', 1, 1); ?>"> 
							<?php echo JText::_( 'FLEXI_FILEEXT_MIME' ); ?>
							</label>
					<div class="controls">
							<input type="text" id="file-url-ext" size="5" class="required" name="file-url-ext" />
							</div>
                    </div>
                    </div>
					
				
				<div class="form-inline">
						  <div class="control-group"><div class="control-label">&nbsp;</div>
				<div class="controls"><input type="submit" id="file-url-submit" class="btn btn-primary validate" value="<?php echo JText::_( 'FLEXI_ADD_FILE' ); ?>"/></div>
                    		
                    </div>
                    </div>
					
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
		<fieldset class="filemanager-tab" >
			<fieldset class="actions" id="filemanager-3">

				<div class="fc-form-tbl" id="add-files-form-container">
					
					<div class="form-inline">
                    <div class="control-group">
							<label class="control-label <?php echo $tip_class; ?>" for="file-dir-path" title="<?php echo flexicontent_html::getToolTip('FLEXI_CHOOSE_SRC_DIR', 'FLEXI_CHOOSE_SRC_DIR_DESC', 1, 1); ?>">
							<?php echo JText::_( 'FLEXI_CHOOSE_SRC_DIR' ); ?>
							</label>
					
						<div class="controls">
							<input type="text" id="file-dir-path" size="50" value="/tmp" class="required" name="file-dir-path" />
							</div>
                        </div>
                        </div>
						
                        <div class="form-inline">
                    <div class="control-group">
 							<label class="control-label <?php echo $tip_class; ?>" id="_file-lang-lbl" for="_file-lang" title="<?php echo flexicontent_html::getToolTip('FLEXI_LANGUAGE', 'FLEXI_FILE_LANGUAGE_DESC', 1, 1); ?>">
							<?php echo JText::_( 'FLEXI_LANGUAGE' ); ?>
							</label>
				<div class="controls">
							<?php echo
								str_replace('id="file-lang', 'id="_file-lang',
								str_replace('id="file-lang', 'id="_file-lang', $this->lists['file-lang'])
								); ?>
					</div>
                        </div>
                        </div>
						
                        <div class="form-inline">
                    <div class="control-group">
							<label class="control-label <?php echo $tip_class; ?>" for="file-filter-ext" title="<?php echo flexicontent_html::getToolTip('FLEXI_FILE_FILTER_EXT', 'FLEXI_FILE_FILTER_EXT_DESC', 1, 1); ?>">
							<?php echo JText::_( 'FLEXI_FILE_FILTER_EXT' ); ?>
							</label>
					<div class="controls">
							<input type="text" id="file-filter-ext" size="50" value="" name="file-filter-ext" />
					</div>
                        </div>
                        </div>
						
                        <div class="form-inline">
                    <div class="control-group">
				
							<label class="control-label <?php echo $tip_class; ?>" for="file-desc_addFileForm" title="<?php echo flexicontent_html::getToolTip('FLEXI_DESCRIPTION', 'FLEXI_FILE_DESCRIPTION_DESC', 1, 1); ?>">
							<?php echo JText::_( 'FLEXI_DESCRIPTION' ); ?>
							</label>
						<div class="controls">
							<textarea name="file-desc" cols="24" rows="6" id="file-desc_addFileForm"></textarea>
					</div>
                        </div>
                        </div>
					
                    
                     <div class="form-inline">
                    <div class="control-group">
					
							<label class="control-label <?php echo $tip_class; ?>" for="file-filter-re" title="<?php echo flexicontent_html::getToolTip('FLEXI_FILE_FILTER_REGEX', 'FLEXI_FILE_FILTER_REGEX_DESC', 1, 1); ?>">
							<?php echo JText::_( 'FLEXI_FILE_FILTER_REGEX' ); ?>
							</label>
				           <div class="controls">
							<input type="text" id="file-filter-re" size="50" value="" name="file-filter-re" />
					</div>
                        </div>
                        </div>
					
				         <div class="form-inline">
                    <div class="control-group">
						
							<label class="control-label <?php echo $tip_class; ?>" title="<?php echo flexicontent_html::getToolTip('FLEXI_KEEP_ORIGINAL_FILE', 'FLEXI_KEEP_ORIGINAL_FILE_DESC', 1, 1); ?>">
							<?php echo JText::_( 'FLEXI_KEEP_ORIGINAL_FILE' ); ?>
							</label>
				<div class="controls">
							<?php
							//echo JHTML::_('select.booleanlist', 'keep', 'class="inputbox"', 1, JText::_( 'FLEXI_YES' ), JText::_( 'FLEXI_NO' ) );
							$_options = array();
							$_options['0'] = JText::_( 'FLEXI_NO' );
							$_options['1'] = JText::_( 'FLEXI_YES' );
							echo flexicontent_html::buildradiochecklist($_options, 'keep', /*selected*/1, /*type*/1, /*attribs*/'', /*tagid*/'keep_addFileForm');
							?>
					</div>
                        </div>
                        </div>
					
				         <div class="form-inline">
                    <div class="control-group">
												<label class="control-label <?php echo $tip_class; ?>" data-placement="bottom" title="<?php echo flexicontent_html::getToolTip('FLEXI_CHOOSE_DIRECTORY', 'FLEXI_CHOOSE_DIRECTORY_DESC', 1, 1); ?>">
							<?php echo JText::_( 'FLEXI_FILE_DIRECTORY' ); ?>
							</label>
				<div class="controls">
							<?php
							//echo JHTML::_('select.booleanlist', 'secure', 'class="inputbox"', 1, JText::_( 'FLEXI_SECURE' ), JText::_( 'FLEXI_MEDIA' ), 'secure_addFileForm' );
							$_options = array();
							$_options['0'] = JText::_( 'FLEXI_MEDIA' );
							$_options['1'] = JText::_( 'FLEXI_SECURE' );
							echo flexicontent_html::buildradiochecklist($_options, 'secure', /*selected*/1, /*type*/1, /*attribs*/'', /*tagid*/'secure_addFileForm');
							?>
									</div>
                        </div>
                        </div>
		
				 <div class="form-inline">
                    <div class="control-group">
												<div class="control-label"></div>
                                                <div class="controls"> 
				<input type="submit" id="file-dir-submit" class="fc_button fcsimple validate" value="<?php echo JText::_( 'FLEXI_ADD_DIR' ); ?>"/>
                </div>
        </div>
        </div>
			</fieldset>
            
		</fieldset>
		<input type="hidden" name="return-url" value="<?php echo base64_encode('index.php?option=com_flexicontent&view=filemanager'); ?>" />
        
			
        
	</form>
	<?php /*echo FLEXI_J16GE ? '' : $this->pane->endPanel();*/ ?>
	</div>
	<?php endif; ?>
	
	
	<div class="tabbertab" style="padding: 0px;" id="server_tab" >
		<h3 class="tabberheading infobg"> <?php echo JText::_( 'Why a DB-based filemanager ?' ); ?> </h3>
	
	<span class="fc-mssg fc-info" style="font-size:14px;">Why not just Joomla media manager ? <br/><br/>
	- To keep track of <b>file usage</b> inside content (<b>assigned</b> items column in this page) <br/>
	- To <b>prevent direct access</b> to files, allowing only indirect access, thus also hiding file's real path (* <b>file / image-gallery fields</b>)<br/>
	- To add <b>more control</b> over the download <sup>1</sup>(<b>file field</b>) <br/>
	  &nbsp; &nbsp; a. gathering <b>hits</b> and other statistics <br/>
	  &nbsp; &nbsp; b. adding <b>access</b> control to the files, and more (e.g. download coupons <sup>1</sup>) <br/>
	- To better handle a <b>SET of re-usable</b> images <sup>2,3</sup>(<b>image-gallery field</b> in DB-mode)<br/><br/>
	 
	<sup>1</sup> Each new version may add more statistics and/or more download control<br/>
	<sup>2</sup> If images are <b>not reusable</b>, please do NOT use the DB-mode in image-gallery field, instead use <b>'folder mode'</b><br/>
	<sup>3</sup> If user can not add extra images and/or you need filtering in item listings, then instead use <b>checkbox-image or radio-image fields</b><br/>
	</span>
	
	<?php /*echo FLEXI_J16GE ? '' : $this->pane->endPanel();*/ ?>
	</div>
	
	
	<?php /*echo FLEXI_J16GE ? JHtml::_('tabs.end') : $this->pane->endPane();*/ ?>
</div><!-- fctabber end -->


			
<div class="clear"></div>
<script type="text/javascript">

// delete active filter
function delFilter(name)
{
	//window.console.log('Clearing filter:'+name);
	var myForm = jQuery('#adminForm');
	var filter = jQuery('#'+name);
	if (filter.attr('type')=='checkbox')
		filter.checked = '';
	else
		filter.val('');
}

function delAllFilters() {
	delFilter('search'); delFilter('filter_state');  delFilter('filter_access');
}

</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">

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
              <?php $_class = FLEXI_J30GE ? ' btn btn-primary btn-rad' : ' btn btn-primary btn-rad'; ?>
              <div class="btn-group ml40">
                <button type="button" id="fc_filters_box_btn" class="<?php echo $_class.($this->count_filters ? ' btn-primary' : ''); ?>" onclick="fc_toggle_box_via_btn('fc-filters-box', this, 'active');" value="<?php echo JText::_( 'FLEXI_FILTERS' ); ?>" />
                <i class="fa icon-filter"></i> <?php echo JText::_( 'FLEXI_FILTERS' ); ?>
                </button>
                <button type="button" id="fc_mainChooseColBox_btn" class="<?php echo $_class; ?> h1000" onclick="fc_toggle_box_via_btn('mainChooseColBox', this, 'active');" value="<?php echo JText::_( 'FLEXI_COLUMNS' ); ?>" />
                <i class="fa icon-eye"></i> <?php echo JText::_( 'FLEXI_COLUMNS' ); ?>
                </button>
              </div>
              <div class="clear"></div>
            </div>
            <!--/ FILTER--> 
            </div>
            </div>
            </div>
	
	<div id="fc-filters-box" <?php if (!$this->count_filters) echo 'style="display:none;"'; ?> class="flexheader">
		  <div class="row-fluid">
          <div class="span10"> 
            <!--FILTERS-->
            <div class="cat-filters">
              <div class="smallx">
              
              <!--<span class="label"><?php echo JText::_( 'FLEXI_FILTERS' ); ?></span>-->
		
		<span class="boxy">
			<i class="icon-comments-2"></i> <?php echo $this->lists['language']; ?>
		</span>
		
		<span class="boxy">
			<i class="icon-briefcase"></i> <?php echo $this->lists['url']; ?>
		</span>
		
		<span class="boxy">
			<i class="icon-lock"></i> <?php echo $this->lists['secure']; ?>
		</span>
		
		<span class="boxy">
			<i class="icon-file-2"></i> <?php echo $this->lists['ext']; ?>
		</span>
		
		<span class="boxy">
			<?php if ($this->CanViewAllFiles) echo '<i class="icon-user"></i> '.$this->lists['uploader']; ?>
			<?php /*?><label class="label">Item ID</label> <?php echo $this->lists['item_id']; ?><?php */?>
		</span>
		

		</div>
	 <div class="break-sm">
                <button class="btn btn-success btn-flat btn-rad" type="submit" onclick="this.form.submit();" value="<?php echo JText::_( 'FLEXI_APPLY_FILTERS' ); ?>" ><i class="fa icon-filter"></i> <?php echo JText::_( 'FLEXI_APPLY_FILTERS' ); ?></button>
                <button class="btn btn-danger btn-flat btn-rad" type="submit" onclick="delAllFilters();this.form.submit();" value="<?php echo JText::_( 'FLEXI_CLEAR' ); ?>" ><i class="fa icon-cancel"></i> <?php echo JText::_( 'FLEXI_CLEAR' ); ?></button>
              </div>
            </div>
			
  <!--/FILTERS--> 
          </div>
        </div>
        <div class="icon-cancel" title="<?php echo JText::_('FLEXI_HIDE'); ?>" style="cursor: pointer;" onclick="fc_toggle_box_via_btn('fc-filters-box', document.getElementById('fc_filters_box_btn'), 'btn-primary');"></div>
      </div>
	<div id="mainChooseColBox" class="fc_mini_note_box well well-small" style="display:none;"></div>

	<div class="fcclear"></div>
	
    
            <!--MAIN TABLE-->
      <div class="row-fluid">
        <div class="span12">
          <div class="block-flat">
            <div class="table-responsive">     
	<table id="adminListTableFCfiles" class="adminlist fcmanlist table no-border no-border-x hover cats">
	<thead>
		<tr>
			<th class="h1100"><?php echo JText::_( 'FLEXI_NUM' ); ?></th>
			<th class="h500"><input type="checkbox" name="toggle" value="" onclick="<?php echo FLEXI_J30GE ? 'Joomla.checkAll(this);' : 'checkAll('.count( $this->rows).');'; ?>" /></th>
			<th class="center hideOnDemandClass"><?php echo JText::_( 'FLEXI_THUMB' ); ?></th>
			<th class="hideOnDemandClass">
				<?php echo JHTML::_('grid.sort', 'FLEXI_FILENAME', 'f.filename_displayed', $this->lists['order_Dir'], $this->lists['order'] ); ?>
				/
				<?php echo JHTML::_('grid.sort', 'FLEXI_FILE_DISPLAY_TITLE', 'f.altname', $this->lists['order_Dir'], $this->lists['order'] ); ?>
			</th>
			<th class="center hideOnDemandClass nowrap"><?php echo JText::_( 'FLEXI_PUBLISHED' ); ?></th>
			<th class="center hideOnDemandClass h500"><?php echo JText::_( 'FLEXI_ACCESS' ); ?></th>
			<th class="center hideOnDemandClass h700"><?php echo JText::_( 'FLEXI_LANGUAGE' ); ?></th>
			<th class="center hideOnDemandClass h900"><?php echo JText::_( 'FLEXI_SIZE' ); ?></th>
			<th class="center hideOnDemandClass h900"><?php echo JHTML::_('grid.sort', 'FLEXI_HITS', 'f.hits', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="center hideOnDemandClass h700"><?php echo JText::_( 'FLEXI_FILE_ITEM_ASSIGNMENTS' ); ?> </th>
			<th class="center hideOnDemandClass h700"><?php echo JHTML::_('grid.sort', 'FLEXI_UPLOADER', 'uploader', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="center hideOnDemandClass h500"><?php echo JHTML::_('grid.sort', 'FLEXI_UPLOAD_TIME', 'f.uploaded', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
			<th class="center hideOnDemandClass nowrap h1100"><?php echo JHTML::_('grid.sort', 'FLEXI_ID', 'f.id', $this->lists['order_Dir'], $this->lists['order'] ); ?></th>
		</tr>
	</thead>

	<tfoot>
		<tr>
			<td colspan="<?php echo $list_total_cols; ?>">
				<?php echo $pagination_footer; ?>
			</td>
		</tr>
		
		<?php
		$field_legend = array();
		$this->assigned_fields_labels;
		foreach($this->assigned_fields_labels as $field_type => $field_label) {
			$icon_name = $this->assigned_fields_icons[$field_type];
			$tip = $field_label;
			$image = JHTML::image('administrator/components/com_flexicontent/assets/images/'.$icon_name.'.png', $tip);
			$field_legend[$field_type] = $image. " ".$field_label;
		}
		?>
		
		<tr>
			<td colspan="14" class="center">
				<span class="fc_legend_box <?php echo $tip_class; ?>" title="<?php echo flexicontent_html::getToolTip('FLEXI_FILE_ITEM_ASSIGNMENTS_LEGEND', 'FLEXI_FILE_ITEM_ASSIGNMENTS_LEGEND_TIP', 1, 1); ?> " ><?php echo JText::_('FLEXI_FILE_ITEM_ASSIGNMENTS_LEGEND'); ?></span> : &nbsp; 
				<?php echo implode(' &nbsp; &nbsp; | &nbsp; &nbsp; ', $field_legend); ?>
			</td>
		</tr>
				
	</tfoot>

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
			
			if (!$row->url && substr($row->filename, 0, 7)!='http://') {
				$file_path = $path . DS . $row->filename;
			} else {
				$thumb_or_icon = 'URL';
			}
			
			$file_path = str_replace('\\', '/', $file_path);
			if ( empty($thumb_or_icon) ) {
				if (file_exists($file_path)){
					$thumb_or_icon = '<img src="'.JURI::root().'components/com_flexicontent/librairies/phpthumb/phpThumb.php?src='.$file_path.'&w=60&h=60" alt="'.$display_filename.'" />';
				} else {
					$thumb_or_icon = '<span class="badge badge-important">'.JText::_('FLEXI_FILE_NOT_FOUND');'</span>';
				}
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
						$image = JHTML::image('administrator/components/com_flexicontent/assets/images/'.$icon_name.'.png', $tip, 'title="'.$usage_in_str.' '.$field_type.' '.$fields_str.'"' );
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
			<td class="h1100"><?php echo $this->pagination->getRowOffset( $i ); ?></td>
			<td class="h500">
				<?php echo $checked; ?>
			</td>
			<td class="center">
				<span class="editlinktip" title="<?php echo $edit_item_title; ?>">
				<?php echo ' <a href="index.php?option=com_flexicontent&amp;'.$ctrl_task.'edit&amp;cid[]='.$row->id.'">'.$thumb_or_icon.'</a>'; ?>
				</span>
			</td>
			<td>
				<?php
					if (JString::strlen($row->filename_displayed) > 100) {
						$filename = JString::substr( htmlspecialchars($row->filename_displayed, ENT_QUOTES, 'UTF-8'), 0 , 25).'...';
					} else {
						$filename = htmlspecialchars($row->filename_displayed, ENT_QUOTES, 'UTF-8');
					}
				?>
				<span class="editlinktip" title="<?php echo $edit_item_title; ?>">
				<?php echo ' <a href="index.php?option=com_flexicontent&amp;'.$ctrl_task.'edit&amp;cid[]='.$row->id.'">'.$filename.'</a>'; ?>
				</span>
				
				<?php
				if ($row->altname != $row->filename_displayed) {
					if (JString::strlen($row->altname) > 100) {
						echo "<br/><small>".JString::substr( htmlspecialchars($row->altname, ENT_QUOTES, 'UTF-8'), 0 , 25).'... </small>';
					} else {
						echo "<br/><small>".htmlspecialchars($row->altname, ENT_QUOTES, 'UTF-8').'</small>';
					}
				}
				?>
			</td>
			<td class="center">
				<?php echo FLEXI_J16GE  ?  JHTML::_('jgrid.published', $row->published, $i, 'filemanager.' )  :  JHTML::_('grid.published', $row, $i ); ?>
			</td>
			
			<td class="center h500">
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
			<td class="col_lang center h700">
				<?php if ( 0 && !empty($row->language) && !empty($this->langs->{$row->language}->imgsrc) ) : ?>
					<img title="<?php echo $row->language=='*' ? JText::_("All") : $this->langs->{$row->language}->name; ?>" src="<?php echo $this->langs->{$row->language}->imgsrc; ?>" alt="<?php echo $row->language; ?>" />
				<?php elseif( !empty($row->language) ) : ?>
					<?php echo $row->language=='*' ? JText::_("FLEXI_ALL") : $this->langs->{$row->language}->name;?>
				<?php endif; ?>
			</td>
			
			<td class="center h900"><?php echo $row->size; ?></td>
			<td class="center h900"><span class="badge"><?php echo $row->hits; ?></span></td>
			<td class="center h700">
				<span class="nowrap_box"><?php echo $row->assigned; ?></span>
				<?php if ($row->count_assigned) : ?>
					<br/><br/>
					<span class="badge badge-info"><?php echo count($row->itemids); ?></span>
					<a href="<?php echo $items_list; ?>">
					[<?php echo JText::_( 'FLEXI_VIEW_ITEMS' );?>]
					</a>
				<?php endif; ?>
			</td>
			<td class="center h700">
			<?php if ($permissions->CanAuthors) :?>
				<a target="_blank" href="index.php?option=com_flexicontent&amp;<?php echo $ctrl_task_authors; ?>edit&amp;hidemainmenu=1&amp;cid[]=<?php echo $row->uploaded_by; ?>">
					<?php echo $row->uploader; ?>
				</a>
			<?php else :?>
				<?php echo $row->uploader; ?>
			<?php endif; ?>
			</td>
			<td class="center h500"><?php echo JHTML::Date( $row->uploaded, JText::_( 'DATE_FORMAT_LC2' ) ); ?></td>
			<td class="center h1100"><?php echo $row->id; ?></td>
		</tr>
		<?php 
			$k = 1 - $k;
			$i++;
		} 
		?>
	</tbody>

	</table>
    </div>
    </div>
    </div>
    </div>
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="option" value="com_flexicontent" />
	<input type="hidden" name="view" value="filemanager" />
	<input type="hidden" name="controller" value="filemanager" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	
</form>

	</div>  <!-- sidebar -->

</div>