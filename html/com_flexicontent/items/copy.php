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

$copy_behaviour = JRequest::getVar('copy_behaviour','copy/move');
?>

<div id="flexicontent" class="flexicontent fcopy">

<form action="index.php" method="post"  name="adminForm" id="adminForm" class="form-inline">
<!--MAIN TABLE--> 
<div class="m20x">   
<div class="row-fluid">
<div class="span12">
<div class="block-flat">
<div class="table-responsive">

<div class="row-fluid">
<div class="span4">
<!--Content 1-->
<fieldset>
<h2 class="purple"><?php echo JText::_( 'FLEXI_CONTENTS_LIST' ); ?></h2>
<table class="table no-border no-hover">
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
							<td><strong><?php echo $row->title; ?></strong></td>
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
<div class="span4">
<!--Content 2-->
<?php if ($copy_behaviour == 'translate') : ?>
			<h2 class="purple"><?php echo JText::_( 'FLEXI_TRANSLATE_OPTIONS' ); ?></h2>
		<?php else : ?>
			<h2 class="purple"><?php echo JText::_( 'FLEXI_COPY_MOVE_OPTIONS' ); ?></h2>
		<?php endif; ?>
        
      <table class="table no-border no-hover no-strip">
					<tr>
					
					<?php if ($copy_behaviour == 'translate') : ?>
						<td class="key">
							<label class="fckey">
								<?php echo JText::_( 'FLEXI_METHOD' ); ?>
							</label>
							<input type="hidden" name="method" value="99" /> <!-- METHOD number for traslate -->
							<input type="hidden" name="initial_behaviour" value="copymove" /> <!-- a hidden field to give info to JS initialization code -->
						</td>
						<td>
							<input id="method-duplicateoriginal" type="radio" name="translate_method" value="1" onclick="copymove();" checked="checked" />
							<label for="method-duplicateoriginal">
								<?php echo JText::_( 'FLEXI_DUPLICATEORIGINAL' ); ?>
							</label><div class="clear"></div>
							
							<input id="method-usejoomfish" type="radio" name="translate_method" value="2" onclick="copymove();" />
							<label for="method-usejoomfish">
								<?php echo JText::_( 'FLEXI_USE_JF_FL_DATA' ); ?>
							</label>
							<div class="clear"></div>
							<?php echo JText::_( 'FLEXI_USE_JF_FL_DATA_INFO' ); ?>
							
						<?php if ( JFile::exists(JPATH_COMPONENT_SITE.DS.'helpers'.DS.'translator.php') ) : /* if automatic translator installed ... */ ?>
						
							<input id="method-autotranslation" type="radio" name="translate_method" value="3" onclick="copymove();" />
							<label for="method-autotranslation">
								<?php echo JText::_( 'FLEXI_AUTO_TRANSLATION' ); ?>
							</label><div class="clear"></div>
							
							<input id="method-firstjf-thenauto" type="radio" name="translate_method" value="4" onclick="copyonly();" />
							<label for="method-firstjf-thenauto">
								<?php echo JText::_( 'FLEXI_FIRST_JF_FL_THEN_AUTO' ); ?>
							</label>
							
						<?php endif; ?>
						
						</td>
					<?php else : ?>
					
						<td>
							<label class="fckey">
								<?php echo JText::_( 'FLEXI_METHOD' ); ?>
							</label>
							<input type="hidden" name="initial_behaviour" value="copyonly" /> <!-- a hidden field to give info to JS initialization code -->
						</td>
						<td>
							<input id="menus-copy" type="radio" name="method" value="1" onclick="copyonly();" checked="checked" />
							<label for="menus-copy" class="lang_box" >
								<?php echo JText::_( 'FLEXI_COPYONLY' ); ?>
							</label><div class="clear"></div>
								
							<input id="method-move" type="radio" name="method" value="2" onclick="moveonly();" />
							<label for="method-move" class="lang_box"  >
								<?php echo JText::_( 'FLEXI_MOVEONLY' ); ?>
							</label><div class="clear"></div>
							
							<input id="method-copymove" type="radio" name="method" value="3" onclick="copymove();" />
							<label for="method-copymove" class="lang_box" >
								<?php echo JText::_( 'FLEXI_COPYMOVE' ); ?>
							</label>
						</td>
						
					<?php endif; ?>
					
					</tr>

					<tr>
						<td><label class="fckey"><?php echo JText::_( 'FLEXI_KEEP_SEC_CATS' ); ?></label></td>
						<td>
							<input id="keepseccats0" type="radio" name="keepseccats" value="0" onclick="secmove();" />
							<label for="keepseccats0">
								<?php echo JText::_( 'No' ); ?>
							</label>
							
							<input id="keepseccats1" type="radio" name="keepseccats" value="1" onclick="secnomove();" checked="checked" />
							<label for="keepseccats1">
								<?php echo JText::_( 'Yes' ); ?>
							</label>
						</td>						
					</tr>
					<tr>
						<td class="key"><label class="fckey"><?php echo JText::_( 'FLEXI_KEEP_TAGS' ); ?></label></td>
						<td>
							<input id="keeptags0" type="radio" name="keeptags" value="0" onclick="secmove();" />
							<label for="keeptags0">
								<?php echo JText::_( 'No' ); ?>
							</label>
							
							<input id="keeptags1" type="radio" name="keeptags" value="1" onclick="secnomove();" checked="checked" />
							<label for="keeptags1">
								<?php echo JText::_( 'Yes' ); ?>
							</label>
						</td>						
					</tr>
					<tr>
						<td class="key"><label class="fckey"><?php echo JText::_( 'FLEXI_ADD_PREFIX' ); ?></label></td>
						<td>
							<?php
							if ($copy_behaviour == 'translate') $defprefix = JText::_( 'FLEXI_DEFAULT_TRANSLATE_PREFIX' );
							else $defprefix = JText::_( 'FLEXI_DEFAULT_PREFIX');
							?>
							<input type="text" id="prefix" name="prefix" value="<?php echo $defprefix; ?>" size="15" />
						</td>
					</tr>
					<tr>
						<td class="key"><label class="fckey"><?php echo JText::_( 'FLEXI_ADD_SUFFIX' ); ?></label></td>
						<td>
							<input type="text" id="suffix" name="suffix" value="" size="15" />
						</td>
					</tr>
					<tr>
						<td class="key"><label class="fckey"><?php echo JText::_( 'FLEXI_COPIES_NR' ); ?></label></td>
						<td>
							<input type="text" id="copynr" name="copynr" value="1" size="3" />
						</td>
					</tr>
					<?php if (FLEXI_FISH || FLEXI_J16GE) : ?>
					<tr>
						<td class="key"><label class="fckey"><?php echo JText::_( 'NEW' )." ".JText::_( 'FLEXI_LANGUAGE' ); ?></label></td>
						<td>
							<?php echo flexicontent_html::buildlanguageslist('language', '', $this->rows[0]->lang, $type = ($copy_behaviour != 'translate' ? 5 : 4) ); ?>
						</td>
					</tr>
					<?php endif; ?>
					<tr>
						<td class="key"><label class="fckey"><?php echo JText::_( 'FLEXI_STATE' ); ?></label></td>
						<td>
							<?php echo flexicontent_html::buildstateslist('state', '', ''); ?>
						</td>
					</tr>
				</table>
<!--Content 2--></div>
<div class="span4">
<!--Content 3-->
<fieldset>
			<h2 class="purple"><?php echo JText::_( 'FLEXI_COPY_MOVE_DESTINATION' ); ?></h2>
				<table class="table no-border no-hover no-strip">
					<tr>
						<td class="key"><label class="fckey"><?php echo JText::_( 'FLEXI_PRIMARY_CATEGORY' ); ?></label></td>
						<td>
							<?php echo $this->lists['maincat']; ?>
						</td>
					</tr>
					<tr>
						<td class="key"><label class="fckey"><?php echo JText::_( 'FLEXI_SECONDARY_CATEGORIES' ); ?></label></td>
						<td><?php echo $this->lists['seccats']; ?></td>
					</tr>
				</table>
			</fieldset>
<!--Content 3-->
            </div>
</div>

    </div></div></div></div></div>
	<input type="hidden" name="option" value="com_flexicontent" />
	<input type="hidden" name="controller" value="items" />
	<input type="hidden" name="view" value="items" />
	<input type="hidden" name="task" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
</div>
