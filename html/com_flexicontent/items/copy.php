<?php
/**
 * @version 1.5 stable $Id: copy.php 1902 2014-05-10 16:06:11Z ggppdk $
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
?>

<div id="flexicontent" class="flexicontent">
<form action="index.php" method="post"  name="adminForm" id="adminForm" class="form-horizontal">


<div class="block-flat">
<div class="table-responsive">

<div class="row-fluid">
<!--CONTENT TITLE-->
<div class="span4">
<!--Content 1-->
<fieldset>
<h2 class="ruler"><small>1. </small><?php echo JText::_( 'FLEXI_CONTENTS_LIST' ); ?></h2>
<table class="fc-form-tbl table no-border no-hover">
					<thead>
						<tr>
							<th><strong><?php echo JText::_( 'FLEXI_TITLE' ); ?></strong></th>
							<th><strong><?php echo JText::_( 'FLEXI_PRIMARY_CATEGORY' ); ?></strong></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ($this->rows as $row) :
							if (in_array($row->id, $this->cid)) :
								foreach ($row->cats as $catid) :
									if ($catid == $row->catid) :
										$maincat = $this->itemCats[$catid]->title;
						?>
						<tr>
							<td><strong class="color-warning"><?php echo $row->title; ?></strong></td>
							<td><?php echo $maincat; ?><input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" /></td>
						</tr>
						<?php
									endif;
								endforeach;
							endif;
						endforeach;
						?>
					</tbody>
				</table>
</fieldset>
<!--Content 1-->
</div>
<!--/CONTENT TITLE-->

<!--COPY BLOCK-->
<div class="span4">

	<fieldset>
		
			<?php if ($this->behaviour == 'translate') : ?>
				<h2 class="ruler"><small>2. </small><?php echo JText::_( 'FLEXI_TRANSLATE_OPTIONS' ); ?></h2>
			<?php else : ?>
				<h2 class="ruler"><small>2. </small><?php echo JText::_( 'FLEXI_BATCH_OPTIONS' ); ?></h2>
			<?php endif; ?>
		
				<table class="fc-form-tbl table no-border topx no-hover">
                <tbody>
					<tr class="no-border">
					
					<?php if ($this->behaviour == 'translate') : ?>
						<td class="no-border">
							<label class="label">
								<?php echo JText::_( 'FLEXI_METHOD' ); ?>
							</label>
							<input type="hidden" name="method" value="99" /> <!-- METHOD number for traslate -->
							<input type="hidden" name="initial_behaviour" value="copymove" /> <!-- a hidden field to give info to JS initialization code -->
						</td>
						<td class="no-border"><div class="m20x">
							<fieldset class="radio btn-group btn-group-yesno">
								<input id="method-duplicateoriginal" type="radio" name="translate_method" value="1" onclick="copymove();" checked="checked" />
								<label for="method-duplicateoriginal" class="btn">
									<?php echo JText::_( 'FLEXI_DUPLICATEORIGINAL' ); ?>
								</label>
								
								<input id="method-usejoomfish" type="radio" name="translate_method" value="2" onclick="copymove();" />
								<label for="method-usejoomfish" class="btn">
									<?php echo JText::_( 'FLEXI_USE_JF_FL_DATA' ); ?> *
								</label>
								
							<?php if ( JFile::exists(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'translator.php') ) : /* if automatic translator installed ... */ ?>
							
								<input id="method-autotranslation" type="radio" name="translate_method" value="3" onclick="copymove();" />
								<label for="method-autotranslation" class="btn">
									<?php echo JText::_( 'FLEXI_AUTO_TRANSLATION' ); ?>
								</label>
								
								<input id="method-firstjf-thenauto" type="radio" name="translate_method" value="4" onclick="copyonly();" />
								<label for="method-firstjf-thenauto" class="btn">
									<?php echo JText::_( 'FLEXI_FIRST_JF_FL_THEN_AUTO' ); ?> *
								</label>
								
							<?php endif; ?>
							</fieldset>
							
							<div class="clear"></div>
							<div id="falang-import-info" class="fc-mssg fc-note" style="display:none; margin-top: 4px;">
								<?php echo JText::_( 'FLEXI_USE_JF_FL_DATA_INFO' ); ?>
							</div>
							
                            </div>
						</td>
					<?php else : ?>
					
						<td>
							<label class="label">
								<?php echo JText::_( 'FLEXI_METHOD' ); ?>
							</label>
							<input type="hidden" name="initial_behaviour" value="copyonly" /> <!-- a hidden field to give info to JS initialization code -->
						</td>
						<td><div class="m20x">
							<fieldset class="radio btn-group btn-group-yesno">
								<input id="menus-copy" type="radio" name="method" value="1" onclick="copyonly();" checked="checked" />
								<label for="menus-copy" class="btn" >
									<?php echo JText::_( 'FLEXI_COPY' ); ?>
								</label>
									
								<input id="method-move" type="radio" name="method" value="2" onclick="moveonly();" />
								<label for="method-move" class="btn"  >
									<?php echo JText::_( 'FLEXI_UPDATE' ); ?>
								</label>
								
								<input id="method-copymove" type="radio" name="method" value="3" onclick="copymove();" />
								<label for="method-copymove" class="btn" >
									<?php echo JText::_( 'FLEXI_COPYUPDATE' ); ?>
								</label>
							</fieldset></div>
						</td>
						
					<?php endif; ?>
                    </tr>
                    </tbody>
                    </table>
</div>
<!--/COPY BLOCK-->


<!--COPY LAST BLOCK-->
<div class="span4">
<h2 class="ruler"><small>3. </small>Choose</h2>

<table class="fc-form-tbl table no-border topx no-hover">
			<tr id="row_copy_options" class="no-border">
						<td colspan="2">
														<h3 class="color-success"><?php echo JText::_( 'FLEXI_COPY_OPTIONS'); ?></h3>
						</td>
					</tr>
					<tr id="row_prefix">
						<td><label class="label"><?php echo JText::_( 'FLEXI_ADD_PREFIX' ); ?></label></td>
						<td>
							<?php
							if ($this->behaviour == 'translate') $defprefix = JText::_( 'FLEXI_DEFAULT_TRANSLATE_PREFIX' );
							else $defprefix = JText::_( 'FLEXI_DEFAULT_PREFIX');
							?>
							<input type="text" id="prefix" name="prefix" value="<?php echo $defprefix; ?>" size="15" />
						</td>
					</tr>
					<tr id="row_suffix">
						<td><label class="label"><?php echo JText::_( 'FLEXI_ADD_SUFFIX' ); ?></label></td>
						<td>
							<input type="text" id="suffix" name="suffix" value="" size="15" />
						</td>
					</tr>
					<tr id="row_copynr">
						<td><label class="label"><?php echo JText::_( 'FLEXI_COPIES_NR' ); ?></label></td>
						<td>
							<input type="text" id="copynr" name="copynr" value="1" size="3" />
						</td>
					</tr>
					
					<tr>
						<td colspan="2">
							
							<h3 class="color-success"><?php echo JText::_( 'FLEXI_COPY_UPDATE_OPTIONS'); ?></h3>
						</td>
					</tr>
					<tr id="row_language">
						<td><label class="label"><?php echo ($this->behaviour == 'translate' ? JText::_( 'NEW' )." " : '').JText::_( 'FLEXI_LANGUAGE' ); ?></label></td>
						<td>
							<?php echo $this->lists['language']; ?>
						</td>
					</tr>
					<tr id="row_language">
						<td><label class="label"><?php echo JText::_( 'FLEXI_STATE' ); ?></label></td>
						<td>
							<?php echo $this->lists['state']; ?>
						</td>
					</tr>
					<tr id="row_type_id">
						<td><label class="label"><?php echo JText::_( 'FLEXI_TYPE' ); ?></label></td>
						<td>
							<?php echo $this->lists['type_id']; ?>
							<div id="fc-change-warning" class="fc-mssg fc-warning" style="display:none; width:50%;"><?php echo JText::_( 'FLEXI_TAKE_CARE_CHANGING_FIELD_TYPE' ); ?></div>
						</td>
					</tr>
					<tr id="row_access">
						<td><label class="label"><?php echo JText::_( 'FLEXI_ACCESS' ); ?></label></td>
						<td>
							<?php echo $this->lists['access']; ?>
						</td>
					</tr>
					
					
					<tr>
						<td colspan="2">
														<h3 class="color-success"><?php echo JText::_( 'FLEXI_ASSIGNMENTS'); ?></h3>
						</td>
					</tr>
					<tr id="row_keeptags" class="htags">
						<td><label class="label"><?php echo JText::_( 'FLEXI_KEEP_TAGS' ); ?></label></td>
						<td>
							<input id="keeptags0" type="radio" name="keeptags" value="0" checked="checked"/>
							<label for="keeptags0">
								<?php echo JText::_( 'FLEXI_NO' ); ?>
							</label>
							
							<input id="keeptags1" type="radio" name="keeptags" value="1" />
							<label for="keeptags1">
								<?php echo JText::_( 'FLEXI_YES' ); ?>
							</label>
						</td>
					</tr>
					<tr id="row_maincat">
						<td><label class="label"><?php echo JText::_( 'FLEXI_PRIMARY_CATEGORY' ); ?></label></td>
						<td>
							<?php echo $this->lists['maincat']; ?>
						</td>
					</tr>
					<tr id="row_keepseccats">
						<td><label class="label"><?php echo JText::_( 'FLEXI_KEEP_SEC_CATS' ); ?></label></td>
						<td>
							<input id="keepseccats0" type="radio" name="keepseccats" value="0" onclick="seccats_on();" />
							<label for="keepseccats0">
								<?php echo JText::_( 'FLEXI_NO' ); ?>
							</label>
							
							<input id="keepseccats1" type="radio" name="keepseccats" value="1" onclick="seccats_off();" checked="checked" />
							<label for="keepseccats1">
								<?php echo JText::_( 'FLEXI_YES' ); ?>
							</label>
						</td>
					</tr>
					<tr id="row_seccats">
						<td><label class="label"><?php echo JText::_( 'FLEXI_SECONDARY_CATEGORIES' ); ?></label></td>
						<td><?php echo $this->lists['seccats']; ?></td>
					</tr>
					
				</table>
			</fieldset>
</div>
<!--/COPY LAST BLOCK-->

</div>

</div>
</div>
	
	
	<input type="hidden" name="option" value="com_flexicontent" />
	<input type="hidden" name="controller" value="items" />
	<input type="hidden" name="view" value="items" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>