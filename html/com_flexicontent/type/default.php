<?php
/**
 * @version 1.5 stable $Id: default.php 1079 2012-01-02 00:18:34Z ggppdk $
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

<div class="m20x">
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">
  <div class="row-fluid">
    <div class="span6">
      <div class="block-flat"> 
        <!--CONTENT-->
        <div class="form-inline m10x">
          <div class="control-group"> <?php echo $this->form->getLabel('name'); ?>
            <div class="controls"> <?php echo $this->form->getInput('name'); ?> </div>
          </div>
        </div>
        <div class="form-inline m10x">
          <div class="control-group"> <?php echo $this->form->getLabel('published'); ?>
            <div class="controls"> <?php echo $this->form->getInput('published'); ?> </div>
          </div>
        </div>
        <div class="form-inline m10x">
          <div class="control-group"> <?php echo $this->form->getLabel('access'); ?>
            <div class="controls"> <?php echo $this->form->getInput('access'); ?> </div>
          </div>
        </div>
        <div class="form-inline m10x">
          <div class="control-group"> <?php echo $this->form->getLabel('alias'); ?>
            <div class="controls"> <?php echo $this->form->getInput('alias'); ?> </div>
          </div>
        </div>
        <?php if (FLEXI_ACCESS || FLEXI_J16GE) : ?>
        <div class="form-inline m10x">
          <div class="control-group"> <?php echo $this->form->getLabel('itemscreatable'); ?>
            <div class="controls"> <?php echo $this->form->getInput('itemscreatable'); ?> </div>
          </div>
        </div>
        <?php endif; ?>
        <!--CONTENT--> 
      </div>
    </div>
    <div class="span6"> 
    <div class="block-flat"> 
      <!--CONTENT2--> 
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
    <table id="tabacces" class="admintable" width="100%">
      <tr>
        <td><div id="access"><?php echo $this->form->getInput('rules'); ?></div></td>
      </tr>
    </table>
    <div id="notabacces"> <?php echo JText::_( 'FLEXI_RIGHTS_MANAGEMENT_DESC' ); ?> </div>
  </fieldset>
  <?php endif; ?>
<?php
				echo JText::_('FLEXI_ITEM_PARAM_OVERRIDE_ORDER_DETAILS');
				echo JHtml::_('sliders.start','basic-sliders-'.$this->form->getValue("id"), array('useCookie'=>1));
				$fieldSets = $this->form->getFieldsets('attribs');
				foreach ($fieldSets as $fsname => $fieldSet) :
					if ( $fsname=='themes' ) continue;
					
					$label = !empty($fieldSet->label) ? $fieldSet->label : 'FLEXI_'.strtoupper($fsname).'_FIELDSET_LABEL';
					echo JHtml::_('sliders.panel',JText::_($label), $fsname.'-options');
					if (isset($fieldSet->description) && trim($fieldSet->description)) :
						echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
					endif;
					?>
    <fieldset class="panelform">
      <?php
						foreach ($this->form->getFieldset($fsname) as $field) :
							echo '<div class="form-inline m10x">
          <div class="control-group">';
							echo $field->label;
							echo '<div class="controls">';
							echo $field->input;
							echo '</div></div></div>';
						endforeach;
						?>
    </fieldset>
    <?php endforeach;
				echo JHtml::_('sliders.end');
				?>
    <fieldset class="panelform">
      <?php
					echo '<span class="fc-note fc-mssg-inline" style="margin: 8px 0px!important;">' . JText::_( 'FLEXI_PARAMETERS_LAYOUT_EXPLANATION' ) .'</span>';
					echo '<div class="clear"></div>';
					
					foreach ($this->form->getFieldset('themes') as $field) :
						if ($field->hidden) echo $field->input;
						else echo $field->label . $field->input;
						echo '<div class="clear"></div>';
					endforeach;
					?>
    </fieldset>
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
    <fieldset class="panelform">
      <?php foreach ($tmpl->params->getFieldset($fsname) as $field) :
							$fieldname =  $field->__get('fieldname');
							$value = $tmpl->params->getValue($fieldname, $groupname, @$this->row->attribs[$fieldname]);
							echo $tmpl->params->getLabel($fieldname, $groupname);
							echo
								str_replace('jform_attribs_', 'jform_layouts_'.$tmpl->name.'_', 
									str_replace('[attribs]', '[layouts]['.$tmpl->name.']',
										$tmpl->params->getInput($fieldname, $groupname, $value)
									)
								);
						endforeach; ?>
    </fieldset>
    <?php endforeach; //fieldSets ?>
    <?php endforeach; //tmpls ?>
    <?php echo JHtml::_('sliders.end'); ?>
      <!--CONTENT2--> </div>
      </div>
  </div>
 
  <?php echo JHTML::_( 'form.token' ); ?>
  <input type="hidden" name="option" value="com_flexicontent" />
  <?php echo $this->form->getInput('id'); ?>
  <input type="hidden" name="controller" value="types" />
  <input type="hidden" name="view" value="type" />
  <input type="hidden" name="task" value="" />
</form>
<div class="m20x">
<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>
