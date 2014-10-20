<?php
/**
 * @version 1.5 stable $Id: default.php 1125 2012-01-26 12:38:53Z ggppdk $
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
$infoimage 	= JHTML::image ( 'administrator/templates/flexi/images/flexi/lightbulb.png', JText::_( 'FLEXI_NOTES' ), ' style="vertical-align:top; margin-top:6px;" ' );
?>

<div class="m20x">
<form action="index.php" method="post" class="form-validate flexi_field" name="adminForm" id="adminForm">
  <div class="row-fluid"> 
    <!--LEFT-->
    <div class="span6"> 
<!--Basic-->
      <div class="row-fluid">
        <div class="span12">
          <div class="block-flat">
            <div class="form-horizontal">
              <h2 class="purple"><?php echo JText::_( 'FLEXI_FIELD_PROPERTIES' ); ?></h2>
              <h3 class="purple"><?php echo JText::_( 'FLEXI_BASIC' ); ?></h3>
              <div class="clear"></div>
              
              <!--TITLE-->
              <div class="form-inline m10x">
                <div class="control-group"> <?php echo $this->form->getLabel('label'); ?>
                  <div class="controls"><?php echo $this->form->getInput('label'); ?></div>
                </div>
              </div>
              <!--/TITLE--> 
              
              <!--KEY-->
              <?php if ($this->form->getValue('iscore') == 0) : ?>
              <div class="form-inline m10x">
                <div class="control-group"> <?php echo $this->form->getLabel('name').'*'; ?>
                  <div class="controls"><?php echo $this->form->getInput('name'); ?> </div>
                </div>
              </div>
              <?php else : ?>
              <div class="form-inline m10x">
                <div class="control-group"> <?php echo $this->form->getLabel('name'); ?>
                  <div class="controls key"> <span class="key1"><?php echo $this->form->getValue("name"); ?></span> </div>
                </div>
              </div>
              <?php endif; ?>
              <!--/KEY--> 
              
              <!--Published-->
              <div class="form-inline m10x">
                <div class="control-group"> <?php echo $this->form->getLabel('published'); ?>
                  <div class="controls"> <?php echo $this->form->getInput('published'); ?>
                    <?php
							$disabled = ($this->form->getValue("id") > 0 && $this->form->getValue("id") < 7);
							if ($disabled) {
								$this->document->addScriptDeclaration("
									jQuery( document ).ready(function() {
										setTimeout(function(){ 
											jQuery('#jform_published input').attr('disabled', 'disabled').off('click');
											jQuery('#jform_published label').attr('disabled', true).off('click');
										}, 1);
									});
								");
							}
							?>
                  </div>
                </div>
              </div>
              <!--/Published-->
              
              <?php if ($this->form->getValue("iscore") == 0) : ?>
              <div class="form-inline m10x">
                <div class="control-group"> <?php echo $this->form->getLabel('field_type').'*'; ?>
                  <div class="controls"> <?php echo $this->lists['field_type']; ?> &nbsp;&nbsp;&nbsp;
                    [ <span id="field_typename"><?php echo $this->form->getValue('field_type'); ?></span> ] </div>
                </div>
              </div>
              <?php endif; ?>
              <div class="form-inline m10x">
                <div class="control-group"> <?php echo $this->form->getLabel('ordering'); ?>
                  <div class="controls"> <?php echo $this->form->getInput('ordering'); ?> </div>
                </div>
              </div>
            
            
              <div class="form-inline m10x">
                <div class="control-group">
                  <?php $div_class = $this->row->iscore ? 'alert-info' : ($this->typesselected ? 'alert-success' : 'alert-warning'); ?>
                  <?php $icon_class = $this->row->iscore ? 'icon-info' : ($this->typesselected ? 'icon-checkmark-2' : 'icon-warning'); ?>
                  <div class="m20"></div>
                  <div class="alert <?php echo $div_class;?> alert-white rounded">
                    <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                    <div class="icon"><i class="fa <?php echo $icon_class;?>"></i></div>
                    <?php echo JText::_( $this->row->iscore ? 'FLEXI_SELECT_TYPES_CORE_NOTES' : 'FLEXI_SELECT_TYPES_CUSTOM_NOTES' ); ?> </div>
                </div>
              </div>
              <div class="form-inline m10x">
                <div class="control-group"> <span class="flexi label hasTip" title="<?php echo JText::_('FLEXI_TYPES').'::'.JText::_('FLEXI_TYPES_NOTES');?>"> <?php echo JText::_( 'FLEXI_TYPES' ); ?> </span>
                  <div class="controls"> <?php echo /*FLEXI_J16GE ? $this->form->getInput('tid') :*/ $this->lists['tid']; ?> </div>
                </div>
              </div>
              <?php if ($this->supportsearch || $this->supportfilter) : ?>
              <div class="row-fluid">
                <div class="span12">
                  <div class="m20"></div>
                  <span class="fcsep_level2"><?php echo JText::_( 'FLEXI_BASIC_INDEX' ); ?></span> <span class="fcsep_level3"><?php echo JText::_( 'FLEXI_BASIC_INDEX_NOTES' ); ?></span> </div>
              </div>
              <?php endif; ?>
              <?php if ($this->supportsearch) : ?>
              <div class="form-inline m10x">
                <div class="control-group"> <?php echo $this->form->getLabel('issearch'); ?>
              <div class="controls"> <?php echo
								in_array($this->form->getValue('issearch'),array(-1,2)) ?
									JText::_($this->form->getValue('issearch')==-1 ? 'FLEXI_NO' : 'FLEXI_YES') .' -- '. JText::_('FLEXI_FIELD_BASIC_INDEX_PROPERTY_DIRTY') :
									$this->form->getInput('issearch'); ?>
                </div>
              </div>
            </div>
            <?php endif; ?>
            
            
                <?php if ($this->supportfilter) : ?>
            <div class="form-inline m10x">
              <div class="control-group"> <?php echo $this->form->getLabel('isfilter'); ?>
                <div class="controls"> <?php echo $this->form->getInput('isfilter'); ?> </div>
              </div>
            </div>
            <?php endif; ?>
            <?php if ($this->supportadvsearch || $this->supportadvfilter) : ?>
            <div class="row-fluid">
              <div class="span12"> <span class="fcsep_level2"><?php echo JText::_( 'FLEXI_ADV_INDEX' ); ?></span> <span class="fcsep_level3"><?php echo JText::_( 'FLEXI_ADV_INDEX_NOTES' ); ?></span> </div>
            </div>
            <?php endif; ?>
            <?php if ($this->supportadvsearch) : ?>
            <div class="form-inline m10x">
              <div class="control-group"> <?php echo $this->form->getLabel('isadvsearch'); ?>
                <div class="controls"> <?php echo
								in_array($this->form->getValue('isadvsearch'),array(-1,2)) ?
									JText::_($this->form->getValue('isadvsearch')==-1 ? 'FLEXI_NO' : 'FLEXI_YES') .' -- '. JText::_('FLEXI_FIELD_ADVANCED_INDEX_PROPERTY_DIRTY') :
									$this->form->getInput('isadvsearch'); ?>  
              </div>
            </div>
            <?php endif; ?>
             <?php if ($this->supportadvfilter) : ?>
            
            <div class="form-inline m10x">
              <div class="control-group"><?php echo $this->form->getLabel('isadvfilter'); ?>
                <div class="controls"><?php echo
								in_array($this->form->getValue('isadvfilter'),array(-1,2)) ?
									JText::_($this->form->getValue('isadvfilter')==-1 ? 'FLEXI_NO' : 'FLEXI_YES') .' -- '. JText::_('FLEXI_FIELD_ADVANCED_INDEX_PROPERTY_DIRTY') :
									$this->form->getInput('isadvfilter'); ?>
              </div>
            </div>
          </div>
          <?php endif; ?>
          <div class="row-fluid">
              <div class="span12"><span class="fcsep_level2"><?php echo JText::_( 'FLEXI_ITEM_FORM' ); ?></span>
          </div>
          </div>
            <div class="form-inline  m10x <?php echo !$this->supportuntranslatable?' none':'';?> ">
              <div class="control-group"><?php echo $this->form->getLabel('untranslatable'); ?>
            <div class="controls"><?php echo $this->form->getInput('untranslatable'); ?> </div>
            </div>
          </div>
          <div class="form-inline m10x <?php echo !$this->supportformhidden?' none':'';?>">
            <div class="control-group"><?php echo $this->form->getLabel('formhidden'); ?>
			<div class="controls"><?php echo $this->form->getInput('formhidden'); ?></div>
            </div>
          </div>
          <?php if (FLEXI_ACCESS || FLEXI_J16GE) : ?>
          
          <div class="form-inline m10x <?php echo !$this->supportvalueseditable?' none':'';?>">
          <div class="control-group">
<?php echo $this->form->getLabel('valueseditable'); ?>
<div class="controls"><?php echo $this->form->getInput('valueseditable'); ?></div>
            </div>
          </div>
          <?php endif; ?>
          
          <div class="form-inline m10x <?php echo !$this->supportedithelp?' none':'';?>">
          <div class="control-group">
<?php echo $this->form->getLabel('edithelp'); ?>
            <div class="controls"><?php echo $this->form->getInput('edithelp'); ?>
          </div>
            </div>
          </div>
          <?php if (!FLEXI_ACCESS || FLEXI_J16GE) : ?>
          <div class="form-inline m10x">
		  <div class="control-group"><?php echo $this->form->getLabel('access'); ?>
          <div class="controls"><?php echo $this->form->getInput('access'); ?>
          </div>
            </div>
          </div>
          <?php endif; ?>
   <div class="form-inline m10x">
		  <div class="control-group"><?php echo $this->form->getLabel('description'); ?>
           <div class="controls"><?php echo $this->form->getInput('description'); ?>
           
            </div>
            </div>
          </div>
          <?php
			if ($this->permission->CanConfig) :
				$this->document->addScriptDeclaration("
					window.addEvent('domready', function() {
						var slideaccess = new Fx.Slide('tabacces');
						var slidenoaccess = new Fx.Slide('notabacces');
						slideaccess.hide();
						$$('fieldset.flexiaccess legend').addEvent('click', function(ev) {
							slideaccess.toggle();
							slidenoaccess.toggle();
						});
					});
				");
			?>
          <fieldset class="flexiaccess">
            <legend><?php echo JText::_( 'FLEXI_RIGHTS_MANAGEMENT' ); ?></legend>
            <div id="tabacces" class="row-fluid admintable">
              <div class="span12">
              <div id="access">
			  <?php echo $this->form->getInput('rules'); ?>
              </div>
              </div>
              </div>
                      <div id="notabacces"> <?php echo JText::_( 'FLEXI_RIGHTS_MANAGEMENT_DESC' ); ?> </div>
          </fieldset>
          <?php endif; ?>
         </div>
        <!--/.form-horizontal--> 
  
      
   
         
          
   </div>

      </div>
    </div>
  </div>
  <!--/Basic-->
  </div>  
  <!--/.span6--> 

  <!--/LEFT--> 
  <!--RIGHT-->
  <div class="span6">
  <!--ACCORDION-->
<div class="panel-group accordion accordion-semi" id="accordion2">
<!--PANEL-->
  <div class="accordion-group">
    <div class="panel-heading">
      <h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
      <i class="fa icon-arrow-right"></i> <?php echo JText::_( 'FLEXI_STANDARD_FIELDS_PROPERTIES' ); ?>
      </a></h4>
    </div>
    <div id="collapseOne" class="accordion-body collapse in">
      <div class="panel-body form-horizontal">
        <?php
						foreach($this->form->getFieldset('basic') as $field) :
							//$input = str_replace("name=\"".$field->inputName."\"", "name=\"params[".$field->inputName."]\"", $field->input);
							?>
							<fieldset class="panelform">
                               <div class="form-inline m10x">
		  <div class="control-group">
							<?php echo $field->label; ?>
                            <div class="controls">
							<?php echo $field->input; ?>
                            </div>
                            </div>
                            </div>
							</fieldset>
							<?php
						endforeach;
						?>
      </div>
    </div>
  </div>
<!--/PANEL--> 
<!--PANEL2--> 
  <div class="accordion-group">
    <div class="panel-heading">
      <h4 class="panel-title"><a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
       <i class="fa icon-arrow-right"></i> <?php echo JText::_( 'FLEXI_THIS_FIELDTYPE_PROPERTIES' ); ?>
      </a></h4>
    </div>
    <div id="collapseTwo" class="accordion-body collapsed collapse">
      <div class="panel-body form-horizontal">
       <?php
					$field_type = $this->form->getValue("field_type", NULL, "text");
					if ($field_type) {
						foreach($this->form->getFieldset('group-' . $field_type) as $field) :
							//$input = str_replace("name=\"".$field->inputName."\"", "name=\"params[".$field->inputName."]\"", $field->input);
							?>
							<fieldset class="panelform">
                            <div class="form-inline m10x">
		  <div class="control-group">
							<?php echo $field->label; ?>
							                            <div class="controls">
							<?php echo $field->input; ?>
                            </div>
                            </div>
                            </div>

							</fieldset>
							<?php
						endforeach;
					} else {
						echo "<br /><span style=\"padding-left:25px;\"'>" . JText::_( 'FLEXI_APPLY_TO_SEE_THE_PARAMETERS' ) . "</span><br /><br />";
					}
					?>
      </div>
    </div>
  </div>
  <!--/PANEL2-->
 
</div>
  <!--ACCORDION-->
  </div>
  <!--/.span6--> 
  <!--/RIGHT-->
  </div>
  <?php echo JHTML::_( 'form.token' ); ?>
  <input type="hidden" name="option" value="com_flexicontent" />
  <?php if ($this->form->getValue('iscore') == 1) : ?>
  <input type="hidden" name="jform[iscore]" value="<?php echo $this->form->getValue("iscore"); ?>" />
  <input type="hidden" name="jform[name]" value="<?php echo $this->form->getValue("name"); ?>" />
  <?php endif; ?>
  <input type="hidden" name="jform[id]" value="<?php echo $this->form->getValue("id"); ?>" />
  <input type="hidden" name="controller" value="fields" />
  <input type="hidden" name="view" value="field" />
  <input type="hidden" name="task" value="" />
</form>
</div>
<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>
