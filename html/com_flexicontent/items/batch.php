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

defined( '_JEXEC' )or die( 'Restricted access' );

if ( $this->behaviour === 'translate' && !flexicontent_db::useAssociations() ) {
   JFactory::getApplication()->enqueueMessage( JText::_( 'FLEXI_LANGUAGE_ASSOCS_IS_OFF_ENABLE_HERE' ) );
}
?>

<div id="flexicontent" class="flexicontent">
   <form action="index.php" method="post" name="adminForm" id="adminForm" class="form-validate form-horizontal">
      <div class="block-flat">
         <div class="container-fluid row">

            <div class="span4 col-4 full_width_980">
               <fieldset class="form-horizontal">
                  <h2 class="ruler"><small>1.&nbsp;</small><?php echo JText::_( 'FLEXI_CONTENTS_LIST' ); ?></h2>
                  <table class="table no-border no-hover">
                     <thead>
                        <tr>
                           <th>
                              <?php echo JText::_( 'FLEXI_TITLE' ); ?>
                           </th>
                           <th>
                              <?php echo JText::_( 'FLEXI_MAIN_CATEGORY' ); ?>
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php
                        foreach ( $this->rows as $row ):
                           if ( in_array( $row->id, $this->cid ) ):
                              foreach ( $row->catids as $catid ):
                                 if ( $catid == $row->catid ):
                                    $maincat = $this->itemCats[ $catid ]->title;
                                 ?>
                        <tr>
                           <td>
                              <?php echo $row->title; ?>
                           </td>
                           <td>
                              <?php echo $maincat; ?><input type="hidden" name="cid[]" value="<?php echo $row->id; ?>"/>
                           </td>
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
            </div>
            <div class="span4 col-4 full_width_980">
               <fieldset class="form-horizontal">

                  <?php if ($this->behaviour == 'translate') : ?>
                  <h2 class="ruler"><small>2.&nbsp;</small><?php echo JText::_( 'FLEXI_TRANSLATE_OPTIONS' ); ?></h2>
                  <?php else : ?>
                  <h2 class="ruler"><small>2.&nbsp;</small><?php echo JText::_( 'FLEXI_BATCH_OPTIONS' ); ?></h2>
                  <?php endif; ?>



                  <?php if ($this->behaviour == 'translate') : ?>


                  <div class="control-group">
                     <div class="control-label">
                        <label>
                           <?php echo JText::_( 'FLEXI_METHOD' ); ?>
                        </label>
                     </div>
                     <div class="controls">

                        <input type="hidden" name="method" value="99"/>
                        <!-- METHOD number for traslate -->
                        <input type="hidden" name="initial_behaviour" value="copymove"/>
                        <!-- a hidden field to give info to JS initialization code -->
                        <fieldset class="radio btn-group btn-group-yesno">
                           <input id="method-duplicateoriginal" type="radio" name="translate_method" value="1" onclick="copymove();" checked="checked"/>
                           <label for="method-duplicateoriginal" class="btn">
                              <?php echo JText::_( 'FLEXI_DUPLICATEORIGINAL' ); ?>
                           </label>

                           <input id="method-usejoomfish" type="radio" name="translate_method" value="2" onclick="copymove();"/>
                           <label for="method-usejoomfish" class="btn">
                              <?php echo JText::_( 'FLEXI_USE_JF_FL_DATA' ); ?> *
                           </label>

                           <?php if ( JFile::exists(JPATH_SITE.DS.'components'.DS.'com_flexicontent'.DS.'helpers'.DS.'translator.php') ) : /* if automatic translator installed ... */ ?>

                           <input id="method-autotranslation" type="radio" name="translate_method" value="3" onclick="copymove();"/>
                           <label for="method-autotranslation" class="btn">
                              <?php echo JText::_( 'FLEXI_AUTO_TRANSLATION' ); ?>
                           </label>

                           <input id="method-firstjf-thenauto" type="radio" name="translate_method" value="4" onclick="copyonly();"/>
                           <label for="method-firstjf-thenauto" class="btn">
                              <?php echo JText::_( 'FLEXI_FIRST_JF_FL_THEN_AUTO' ); ?> *
                           </label>

                           <?php endif; ?>
                           <div></div>
                        </fieldset>

                        <div class="fcclear"></div>
                        <div id="falang-import-info" class="fc-mssg fc-note" style="display:none; margin-top: 4px;">
                           <?php echo JText::_( 'FLEXI_USE_JF_FL_DATA_INFO' ); ?>
                        </div>

                        </td>
                        <?php else : ?>

                        <td class="key" style="vertical-align:top;">
                           <label>
                              <?php echo JText::_( 'FLEXI_METHOD' ); ?>
                           </label>
                           <input type="hidden" name="initial_behaviour" value="copyonly"/>
                           <!-- a hidden field to give info to JS initialization code -->
                        </td>
                        <td style="vertical-align:top;">
                           <fieldset class="radio btn-group btn-group-yesno">
                              <input id="menus-copy" type="radio" name="method" value="1" onclick="copyonly();" checked="checked"/>
                              <label for="menus-copy" class="btn">
                                 <?php echo JText::_( 'FLEXI_COPY' ); ?>
                              </label>

                              <input id="method-move" type="radio" name="method" value="2" onclick="moveonly();"/>
                              <label for="method-move" class="btn">
                                 <?php echo JText::_( 'FLEXI_UPDATE' ); ?>
                              </label>

                              <input id="method-copymove" type="radio" name="method" value="3" onclick="copymove();"/>
                              <label for="method-copymove" class="btn">
                                 <?php echo JText::_( 'FLEXI_COPYUPDATE' ); ?>
                              </label>
                           </fieldset>
                        </td>

                        <?php endif; ?>



               </fieldset>
               </div>
               <div class="span4 col-4 full_width_980">

                  <h2 class="ruler"><small>3.&nbsp;</small>Choose</h2>
                  <fieldset class="form-horizontal" id="row_copy_options">
                     <h4 class="ruler bold"><small>a.</small>&nbsp;<?php echo JText::_( 'FLEXI_COPY_OPTIONS'); ?></h4>
                  </fieldset>


                  <fieldset class="form-horizontal" id="row_prefix">
                     <div class="control-group">
                        <div class="control-label">
                           <label for="prefix">
                              <?php echo JText::_( 'FLEXI_ADD_PREFIX' ); ?>
                           </label>
                        </div>
                        <div class="controls">
                           <?php
                           if ( $this->behaviour == 'translate' )$defprefix = JText::_( 'FLEXI_DEFAULT_TRANSLATE_PREFIX' );
                           else $defprefix = JText::_( 'FLEXI_DEFAULT_PREFIX' );
                           ?>
                           <input type="text" id="prefix" name="prefix" value="<?php echo $defprefix; ?>" size="15"/>
                        </div>
                     </div>
                  </fieldset>

                  <fieldset class="form-horizontal" id="row_suffix">
                     <div class="control-group">
                        <div class="control-label">
                           <label for="suffix">
                              <?php echo JText::_( 'FLEXI_ADD_SUFFIX' ); ?>
                           </label>
                        </div>
                        <div class="controls">
                           <input type="text" id="suffix" name="suffix" value="" size="15"/>
                        </div>
                     </div>
                  </fieldset>

                  <fieldset class="form-horizontal" id="row_copynr">
                     <div class="control-group">
                        <div class="control-label">
                           <label>
                              <?php echo JText::_( 'FLEXI_COPIES_NR' ); ?>
                           </label>
                        </div>
                        <div class="controls">
                           <input type="text" id="copynr" name="copynr" value="1" size="3"/>
                        </div>
                     </div>
                  </fieldset>

                  <fieldset class="form-horizontal">
                     <h4 class="ruler bold"><small>b.</small>&nbsp;<?php echo JText::_( 'FLEXI_COPY_UPDATE_OPTIONS'); ?></h4>
                  </fieldset>

                  <fieldset class="form-horizontal" id="row_language">
                     <div class="control-group">
                        <div class="control-label">
                           <label for="language">
                              <?php echo ($this->behaviour == 'translate' ? JText::_( 'NEW' )." " : '').JText::_( 'FLEXI_LANGUAGE' ); ?>
                           </label>
                        </div>
                        <div class="controls">
                           <?php echo $this->lists['language']; ?>
                        </div>
                     </div>
                  </fieldset>

                  <fieldset class="form-horizontal" id="row_state">
                     <div class="control-group">
                        <div class="control-label">
                           <label>
                              <?php echo JText::_( 'FLEXI_STATE' ); ?>
                           </label>
                        </div>
                        <div class="controls">
                           <?php echo $this->lists['state']; ?>
                        </div>
                     </div>
                  </fieldset>

                  <fieldset class="form-horizontal" id="row_type_id">
                     <div class="control-group">
                        <div class="control-label">
                           <label>
                              <?php echo JText::_( 'FLEXI_TYPE' ); ?>
                           </label>
                        </div>
                        <div class="controls">
                           <?php echo $this->lists['type_id']; ?>
                           <div id="fc-change-warning" class="fc-mssg fc-warning" style="display:none; float:left;">
                              <?php echo JText::_( 'FLEXI_TAKE_CARE_CHANGING_FIELD_TYPE' ); ?>
                           </div>
                        </div>
                     </div>
                  </fieldset>

                  <fieldset class="form-horizontal" id="row_access">
                     <div class="control-group">
                        <div class="control-label">
                           <label>
                              <?php echo JText::_( 'FLEXI_ACCESS' ); ?>
                           </label>
                        </div>
                        <div class="controls">
                           <?php echo $this->lists['access']; ?>
                        </div>
                     </div>
                  </fieldset>


                  <fieldset class="form-horizontal">
                     <h4 class="ruler bold"><small>c.</small>&nbsp;<?php echo JText::_( 'FLEXI_ASSIGNMENTS'); ?></h4>
                  </fieldset>



                  <fieldset class="form-horizontal" id="row_keeptags">

                     <div class="control-group">
                        <div class="control-label">
                           <label>
                              <?php echo JText::_( 'FLEXI_KEEP_TAGS' ); ?>
                           </label>
                        </div>
                        <div class="controls">
                           <div class="group-fcset fc_input_set">
                              <input id="keeptags0" type="radio" name="keeptags" value="0"/>
                              <label for="keeptags0">
                                 <?php echo JText::_( 'FLEXI_NO' ); ?>
                              </label>

                              <input id="keeptags1" type="radio" name="keeptags" value="1" checked="checked"/>
                              <label for="keeptags1">
                                 <?php echo JText::_( 'FLEXI_YES' ); ?>
                              </label>
                           </div>
                        </div>
                     </div>
                  </fieldset>


                  <fieldset class="form-horizontal" id="row_maincat">
                     <div class="control-group">
                        <div class="control-label">
                           <label>
                              <?php echo JText::_( 'FLEXI_MAIN_CATEGORY' ); ?>
                           </label>
                        </div>
                        <div class="controls">
                           <?php echo $this->lists['maincat']; ?>
                        </div>
                     </div>
                  </fieldset>

                  <fieldset class="form-horizontal" id="row_keepseccats">
                     <div class="control-group">
                        <div class="control-label">
                           <label>
                              <?php echo JText::_( 'FLEXI_KEEP_SEC_CATS' ); ?>
                           </label>
                        </div>
                        <div class="controls">
                           <div class="group-fcset fc_input_set">
                              <input id="keepseccats0" type="radio" name="keepseccats" value="0" onclick="seccats_on();"/>
                              <label for="keepseccats0">
                                 <?php echo JText::_( 'FLEXI_NO' ); ?>
                              </label>

                              <input id="keepseccats1" type="radio" name="keepseccats" value="1" onclick="seccats_off();"/>
                              <label for="keepseccats1">
                                 <?php echo JText::_( 'FLEXI_YES' ); ?>
                              </label>
                           </div>
                        </div>
                     </div>
                  </fieldset>

                  <fieldset class="form-horizontal" id="row_seccats">
                     <div class="control-group">
                        <div class="control-label">
                           <label>
                              <?php echo JText::_( 'FLEXI_SECONDARY_CATEGORIES' ); ?>
                           </label>
                        </div>
                        <div class="controls">
                           <?php echo $this->lists['seccats']; ?>
                        </div>

                     </div>
                  </fieldset>
               </div>
               </div>
               <input type="hidden" name="option" value="com_flexicontent"/>
               <input type="hidden" name="controller" value="items"/>
               <input type="hidden" name="view" value="items"/>
               <input type="hidden" name="task" value=""/>
               <?php echo JHtml::_( 'form.token' ); ?>
   </form>
   </div>