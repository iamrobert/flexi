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

defined('_JEXEC') or die('Restricted access'); ?>
<script language="javascript" type="text/javascript">
	function <?php echo $this->use_jquery_sortable ? 'initordering' : 'storeordering'; ?>() {
	<?php echo $this->jssort . ';' ; ?>
	}
</script>

<div id="flexicontent" class="flexicontent">
  <form action="index.php" method="post" name="adminForm" id="adminForm">
    <div class="m20"></div>
    <div class="row-fluid">
      <div class="span6">
        <div class="block-flat">
        
        
             <div class="row-fluid">
     <div class="span12">
          <h3><?php echo JText::_('FLEXI_TEMPLATE_PROPERTIES') ?></h3>
          <hr></div></div>
          
          <!--PROP-->
          <fieldset class="tmplprop">
            <div id="propvisible">
            <div class="admintable" id="lay-desc-table">
     
     <div class="row-fluid">
     <div class="span8">
     
              <div class="row-fluid">
                <div class="span3">
                  <label class="label"> <?php echo JText::_( 'FLEXI_FOLDER' ); ?> </label>
                </div>
                <div class="span9"><?php echo $this->layout->name; ?></div>
              </div>
              <div class="row-fluid">
                <div class="span3">
                  <label class="label"> <?php echo JText::_( 'View' ); ?> </label>
                </div>
                <div class="span9"><?php echo $this->layout->view; ?></div>
              </div>
              <div class="row-fluid">
                <div class="span3">
                  <label class="label"> <?php echo JText::_( 'Author' ); ?> </label>
                </div>
                <div class="span9"> <?php echo $this->layout->author; ?></div>
              </div>
              <div class="row-fluid">
                <div class="span3">
                  <label class="label"> <?php echo JText::_( 'FLEXI_WEBSITE' ); ?> </label>
                </div>
                <div class="span9"><a href="http://<?php echo $this->layout->website; ?>" target="_blank"><?php echo $this->layout->website; ?></a></div>
              </div>
              <div class="row-fluid">
                <div class="span3">
                  <label class="label"> <?php echo JText::_( 'Email' ); ?> </label>
                </div>
                <div class="span9"><a href="mailto:<?php echo $this->layout->email; ?>"><?php echo $this->layout->email; ?></a></div>
              </div>
              <div class="row-fluid">
                <div class="span3">
                  <label class="label"> <?php echo JText::_( 'License' ); ?> </label>
                </div>
                <div class="span9"><?php echo $this->layout->license; ?></div>
              </div>
              <div class="row-fluid">
                <div class="span3">
                  <label class="label"> <?php echo JText::_( 'Version' ); ?> </label>
                </div>
                <div class="span9"><?php echo $this->layout->version; ?></div>
              </div>
              <div class="row-fluid">
                <div class="span3">
                  <label class="label"> <?php echo JText::_( 'FLEXI_RELEASE' ); ?> </label>
                </div>
                <div class="span9"><?php echo $this->layout->release; ?></div>
              </div>
              <div class="row-fluid">
                <div class="span3">
                  <label class="label"> <?php echo JText::_( 'Description' ); ?> </label>
                </div>
                <div class="span9"><?php echo $this->layout->description; ?></div>
              </div>
              
              </div>
              <div class="span4">
           
           <p class="text-right"><img src="../<?php echo $this->layout->thumb; ?>" alt="<?php echo JText::_( 'FLEXI_TEMPLATE_THUMBNAIL' ); ?>" /></p>
</div>
            </div>
            <div id="propnovisible"> 
             <div class="row-fluid">
                <div class="span12">
               <?php /*?> <p><?php echo JText::_( 'FLEXI_CLICK_PROPERTIES' ); ?></p><?php */?> </div></div></div>
          </fieldset>
          <!--/PROP--> 
        </div>
        <!--/BLOCK FLAT-->
        <div class="block-flat">
          <fieldset id="available_fields_container">
            <legend><?php echo JText::_('FLEXI_AVAILABLE_FIELDS') ?></legend>
            <hr>
            <div style="float:left; clear:both; width:100%; margin:0px 0px 12px 0px;">
              <div style="float:left; margin-right:32px;">
                <div style="float:left;" class="postitle" ><?php echo JText::_('FLEXI_FILTER').' '.JText::_('FLEXI_TYPE'); ?></div>
                <div style="float:left; clear:both;"> <?php echo sprintf(str_replace('__au__', '_available', $this->content_type_select), 'available_fields_container', 'hide', 'available'); ?> </div>
              </div>
              <div style="float:left;">
                <div style="float:left;" class="postitle" ><?php echo JText::_('FLEXI_FILTER').' '.JText::_('FLEXI_FIELD_TYPE'); ?></div>
                <div style="float:left; clear:both;"> <?php echo sprintf(str_replace('__au__', '_available', $this->field_type_select), 'available_fields_container', 'hide', 'available'); ?> </div>
              </div>
            </div>
            <div class="postitle"><?php echo JText::_('FLEXI_CORE_FIELDS'); ?></div>
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
            <div class="postitle"><?php echo JText::_('FLEXI_NON_CORE_FIELDS'); ?></div>
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
        </div>
        <!--/BLOCK FLAT--> 
        
      </div>
      <div class="span6">
        <div class="block-flat"> 
          <!--Content-->
          <fieldset id="layout_positions_container">
            <legend><?php echo JText::_('FLEXI_AVAILABLE_POS') ?></legend>
            <hr>
            <div style="float:left; clear:both; width:100%; margin:0px 0px 12px 0px;">
              <div style="float:left; margin-right:32px;">
                <div style="float:left;" class="postitle" ><?php echo JText::_('FLEXI_FILTER').' '.JText::_('FLEXI_TYPE'); ?></div>
                <div style="float:left; clear:both;"> <?php echo sprintf(str_replace('__au__', '_used',$this->content_type_select), 'layout_positions_container', 'highlight', 'used'); ?> </div>
              </div>
              <div style="float:left;">
                <div style="float:left;" class="postitle" ><?php echo JText::_('FLEXI_FILTER').' '.JText::_('FLEXI_FIELD_TYPE'); ?></div>
                <div style="float:left; clear:both;"> <?php echo sprintf(str_replace('__au__', '_used',$this->field_type_select), 'layout_positions_container', 'highlight', 'used'); ?> </div>
              </div>
            </div>
            <span style="font-weight:bold;"><?php echo JText::_('FLEXI_NOTES');?>:</span> <span style="color:darkred;"><?php echo JText::_('FLEXI_INSTRUCTIONS_ADD_FIELD_TO_LAYOUT_POSITION');?></span>
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
								echo ($posrow_prev != $posrow)  ?  "<table width='100%' cellpadding='0' cellspacing='0'><tr class='fieldgrprow' ><td class='fieldgrprow_cell' >\n"  :  "</td><td class='fieldgrprow_cell'>\n";
							}
							
						?>
            <div class="postitle"><?php echo $pos; ?></div>
            <?php
						if ( isset($this->layout->attributes[$count]['readonly']) ) {
							switch ($this->layout->view) {
								case FLEXI_ITEMVIEW: $msg='in the <b>Item Type</b> configuration and/or in each individual <b>Item</b>'; break;
								case 'category': $msg='in each individual <b>Category</b>'; break;
								default: $msg='in each <b>'.$this->layout->view.'</b>'; break;
							}
							echo "<div class='positions_readonly'>NON-editable position.<br/> To customize edit TEMPLATE parameters ".$msg."</div>";
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
                <li class="<?php echo $class_list; ?>" id="field_<?php echo $this->fields[$f]->name; ?>"> <?php echo $this->fields[$f]->label . ($this->fields[$f]->iscore ? '' : ' #'.$this->fields[$f]->id); ?> </li>
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
          <!--/Content--> 
        </div>
      </div>
    </div>
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
</div>
