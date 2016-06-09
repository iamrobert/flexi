<?php
/**
 * @version 1.5 stable $Id: default.php 1245 2012-04-12 05:16:57Z ggppdk $
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

// For tabsets/tabs ids (focusing, etc)
$tabSetCnt = -1;
$tabSetMax = -1;
$tabCnt = array();
$tabSetStack = array();


$tabtabSetCnt = -1;
$tabtabSetMax = -1;
$tabtabCnt = array();
$tabtabSetStack = array();
?>

<?php
$useAssocs = flexicontent_db::useAssociations();


?>

<style>
.current:after{
	clear: both;
	content: "";
	display: block;
}
</style>

<div id="flexicontent" class="flexicontent">
<form action="index.php" method="post" name="adminForm" id="adminForm" class="form-horizontal">


<!-- ##################
+ TITLE
####################### -->
    <div class="row-fluid">
      <div class="span12">
        <h1 class="contentx">
          <?php if ($this->form->getValue('title') == '') {
echo 'New Category';}
else {
	echo $this->form->getValue('title');
};?>
        </h1>
      </div>
    </div>
    
     <div class="row-fluid">
        <div class="span12">
          <div class="block-flat">
          <!--CONTENT-->
          
           <div class="control-group">
                  <label class="control-label required" for="jform_title"><?php echo jtext::_("FLEXI_TITLE"); ?></label>
                  <div class="controls"> <?php echo $this->form->getInput('title'); ?> </div>
                </div>
                <!--CONTENT-->
                
                 <!--ALIAS-->
         
                <div class="control-group">
                  <label class="control-label" for="jform_alias"><?php echo jtext::_("FLEXI_ALIAS"); ?></label>
                  <div class="controls"> <?php echo $this->form->getInput('alias'); ?> </div>
                </div>
                         <!--/ALIAS-->
                         
                         
                         <!--PUBLISHED-->
          
                <div class="control-group"> <?php echo $this->form->getLabel('published'); ?>
                  <div class="controls"> <?php echo $this->form->getInput('published'); ?><i class="icon-calendar m3l"></i> </div>
                </div>
            
              <!--/PUBLISHED--> 
              
              
                            <!--CAT-->
            
                <div class="control-group"> <?php echo $this->form->getLabel('parent_id'); ?>
                  <div class="controls"> <?php echo $this->Lists['parent_id']; ?><i class="icon-folder m3l"></i> </div>
                </div>
             
              <!--/CAT-->
              <!--LANGUAGE-->
              <div class="hlang">
                <div class="control-group"> <?php echo $this->form->getLabel('language'); ?>
                  <div class="controls"> <?php echo $this->form->getInput('language'); ?><i class="icon-comments-2 m3l movel"></i> </div>
                </div>
              </div>
              <!--END--> 
          </div>
          </div>
          </div>
	
	<?php /*echo JHtml::_('tabs.start','core-tabs-'.$this->form->getValue("id"), array('useCookie'=>1));*/ ?>
		<?php /*echo JHtml::_('tabs.panel', JText::_( 'FLEXI_DESCRIPTION' ), 'cat-description');*/ ?>
	<?php
// *****************
// MAIN TABSET START
// *****************
global $tabSetCnt;
array_push($tabSetStack, $tabSetCnt);
$tabSetCnt = ++$tabSetMax;
$tabCnt[$tabSetCnt] = 0;
?>	
<script>
/* tab memory */
jQuery(function($) {
  var json, tabsState;
  $('a[data-toggle="pill"], a[data-toggle="tab"]').on('shown', function(e) {
    var href, json, parentId, tabsState;

    tabsState = localStorage.getItem("tabs-state");
    json = JSON.parse(tabsState || "{}");
    parentId = $(e.target).parents("ul.nav.nav-pills, ul.nav.nav-tabs").attr("id");
    href = $(e.target).attr('href');
    json[parentId] = href;

    return localStorage.setItem("tabs-state", JSON.stringify(json));
  });

  tabsState = localStorage.getItem("tabs-state");
  json = JSON.parse(tabsState || "{}");

  $.each(json, function(containerId, href) {
    return $("#" + containerId + " a[href=" + href + "]").tab('show');
  });

  $("ul.nav.nav-pills, ul.nav.nav-tabs").each(function() {
    var $this = $(this);
    if (!json[$this.attr("id")]) {
      return $this.find("a[data-toggle=tab]:first, a[data-toggle=pill]:first").tab("show");
    }
  });
});
</script>

      <?php 
$options = array('active' => 'tab'.$tabCnt[$tabSetCnt].'');
echo JHtml::_('bootstrap.startTabSet', 'ID-Tabs-'.$tabSetCnt.'', $options, array('useCookie'=>1));?>
      
      <!--FLEXI_BASIC TAB1 --> 
      <?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', 
	  JText::_( '<i class="icon-file-2"></i> '.JText::_('FLEXI_DESCRIPTION'))); ?> 

			
			<div class="flexi_params">
				<?php
					// parameters : areaname, content, hidden field, width, height, rows, cols
					echo $this->editor->display( FLEXI_J16GE ? 'jform[description]' : 'description',  $this->row->description, '100%', '350px', '75', '20', array('pagebreak', 'readmore') ) ;
					//echo $this->form->getInput('description');  // does not use default user editor, but instead the one specified in XML file or the Globally configured one
				?>
			</div>
			
		  <?php echo JHtml::_('bootstrap.endTab');?> 
		
		
	 <!--TAB2-->
         <?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', 
	  JText::_( '<i class="icon-image"></i>'.JText::_('FLEXI_IMAGE'))); ?> 
      
			
			<?php
			$fieldSets = $this->form->getFieldsets('params');
			foreach ($fieldSets as $name => $fieldSet) :
				if ($name != 'cat_basic' ) continue;
				$label = !empty($fieldSet->label) ? $fieldSet->label : 'FLEXI_PARAMS_'.$name;
				if (isset($fieldSet->description) && trim($fieldSet->description)) :
					echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
				endif;
				?>
				<fieldset>
					<?php foreach ($this->form->getFieldset($name) as $field) : ?>
						<div class="control-group">
						<div class="control-label"><?php echo $field->label; ?></div>
						<div class="controls"><?php echo $field->input; ?></div>
                        </div>
					<?php endforeach; ?>
				</fieldset>
			<?php endforeach; ?>
			
     <?php echo JHtml::_('bootstrap.endTab');?> 
        <!--/TAB2 --> 
        <!--TAB3-->
<?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', 
	  JText::_( '<i class="icon-calendar"></i>'.JText::_('FLEXI_PUBLISHING'))); ?> 
			
			<fieldset>
				
				
	
    
     <div class="control-group">
    <div class="control-label"><?php echo $this->form->getLabel('created_user_id'); ?></div>
    <div class="controls"><?php echo $this->form->getInput('created_user_id'); ?></div>
  </div>
				<?php if (intval($this->form->getValue('created_time'))) : ?>
					<div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('created_time'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('created_time'); ?></div>
                    </div>
				<?php endif; ?>
	
				<?php if ($this->form->getValue('modified_user_id')) : ?>
                <div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('modified_user_id'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('modified_user_id'); ?>
	</div>
    </div>
    <div class="control-group">
					<div class="control-label"><?php echo $this->form->getLabel('modified_time'); ?></div>
					<div class="controls"><?php echo $this->form->getInput('modified_time'); ?></div>
                    </div>
				<?php endif; ?>
				
                <div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('access'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('access'); ?></div>
                    </div>
			</fieldset>
			
            
      <?php echo JHtml::_('bootstrap.endTab');?> 
      <!--/TAB3-->
      
      
              <!--TAB4-->
<?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', 
	  JText::_( '<i class="icon-bookmark"></i>'.JText::_('FLEXI_META_SEO'))); ?> 

			
			<fieldset>
			 <div class="control-group">
    <div class="control-label"><?php echo $this->form->getLabel('metadesc'); ?></div>
    <div class="controls">
				<?php echo $this->form->getInput('metadesc'); ?></div>
  </div>
	
				  <div class="control-group">
    <div class="control-label"><?php echo $this->form->getLabel('metakey'); ?></div>
    <div class="controls">
				<?php echo $this->form->getInput('metakey'); ?></div>
  </div>
	
				<?php foreach($this->form->getGroup('metadata') as $field): ?>
					<?php if ($field->hidden): ?>
						<?php echo $field->input; ?>
					<?php else: ?>
						  <div class="control-group">
    <div class="control-label"><?php echo $field->label; ?></div>
    <div class="controls">
						<?php echo $field->input; ?></div>
  </div>
					<?php endif; ?>
				<?php endforeach; ?>
			</fieldset>
			
			<?php
			$fieldSets = $this->form->getFieldsets('params');
			foreach ($fieldSets as $name => $fieldSet) :
				if ($name != 'cat_seo' ) continue;
				$label = !empty($fieldSet->label) ? $fieldSet->label : 'FLEXI_PARAMS_'.$name;
				if (isset($fieldSet->description) && trim($fieldSet->description)) :
					echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
				endif;
				?>
				<fieldset>
					<?php foreach ($this->form->getFieldset($name) as $field) : ?>
                    <div class="control-group">
						<div class="control-label"><?php echo $field->label; ?></div>
						<div class="controls"><?php echo $field->input; ?></div>
                        </div>
					<?php endforeach; ?>
				</fieldset>
			<?php endforeach; ?>
			
	      <?php echo JHtml::_('bootstrap.endTab');?> 
      <!--/TAB4-->
      
                    <!--TAB5-->
<?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', 
	  JText::_( '<i class="icon-wrench"></i>'.JText::_('FLEXI_PARAMETERS_HANDLING'))); ?> 
			
			<div style="margin: 24px 0px;">
				
				<?php foreach($this->form->getGroup('special') as $field): ?>
					<fieldset>
                    <div class="control-group">
						<div class="control-label"><?php echo $field->label; ?></div>
						<div class="controls">
							<?php echo $this->Lists[$field->fieldname]; ?></div>
						</div>
					</fieldset>
				<?php endforeach; ?>
				
				
				<div class="alert alert-success alert-white-alt rounded">
                <div class="icon"><i class="icon-checkmark-2"></i></div>
					<?php echo JText::_('FLEXI_CAT_PARAM_OVERRIDE_ORDER_DETAILS_INHERIT'); ?>
				</div>
				
				
				
				<fieldset>
                <div class="control-group">
    <div class="control-label">
					<?php echo $this->form->getLabel('copycid'); ?></div>
					<div class="controls">
						<?php echo $this->Lists['copycid']; ?></div>
						</div>
					
				</fieldset>
			<div class="alert alert-warning alert-white-alt rounded">
                <div class="icon"><i class="icon-notification"></i></div>
					<?php echo JText::_('FLEXI_COPY_PARAMETERS_DESC'); ?>
				</div>
				
			</div>
			
 <?php echo JHtml::_('bootstrap.endTab');?> 
      <!--/TAB5-->
      
      
                          <!--TAB6-->
<?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', 
	  JText::_( '<i class="icon-eye-open"></i>'.JText::_('FLEXI_PARAMETERS'))); ?> 
	
			

<?php 
$options = array('active' => 'tabtab'.$tabCnt[$tabSetCnt].'');
echo JHtml::_('bootstrap.startTabSet', 'ID-Tabs-J31-Group', $options, array('useCookie'=>1));?> 

<!--inner-->
<?php
				$fieldSets = $this->form->getFieldsets('params');
				foreach ($fieldSets as $name => $fieldSet) :
					if ($name == 'cat_basic' ) continue;
					if ($name == 'cat_notifications_conf' && ( !$this->cparams->get('enable_notifications', 0) || !$this->cparams->get('nf_allow_cat_specific', 0) ) ) continue;
					if ($name == 'cat_seo' ) continue;
					$label = !empty($fieldSet->label) ? $fieldSet->label : 'FLEXI_PARAMS_'.$name;
					echo JHtml::_('bootstrap.addTab', 'ID-Tabs-J31-Group', 'tabtab'.$tabCnt[$tabSetCnt]++.'', JText::_($label));
					if (isset($fieldSet->description) && trim($fieldSet->description)) :
						echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
					endif;
					?>

		<fieldset>
						<?php foreach ($this->form->getFieldset($name) as $field) : ?>
						<div class="control-group">
    <div class="control-label"><?php echo $field->label; ?></div>
    <div class="controls"><?php echo $field->input; ?></div>
                            </div>
						<?php endforeach; ?>
					</fieldset>
                    
<?php echo JHtml::_('bootstrap.endTab');?> 
<?php endforeach; ?>


<?php echo JHtml::_('bootstrap.endTabSet');?>

<!--/inner-->


      
    

			
<?php echo JHtml::_('bootstrap.endTab');?> 
      <!--/TAB6-->
      
      
      <!--TAB7-->
<?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', 
	  JText::_( '<i class="icon-palette"></i>'.JText::_('FLEXI_TEMPLATE'))); ?> 
      
      <div class="alert alert-info alert-white-alt rounded">
      <div class="icon"><i class="icon-info"></i></div>
      <?php echo JText::_( 'FLEXI_PARAMETERS_LAYOUT_EXPLANATION' ); ?>
      
   
			<br/><br/>
			<ol>
				<li> Select TEMPLATE layout </li>
				<li> Open slider with TEMPLATE (layout) PARAMETERS </li>
			</ol>
			<br/>
			<strong>NOTE:</strong> Common method for -displaying- fields is by <strong>editing the template layout</strong> in template manager and placing the fields into <strong>template positions</strong>
			</div>
			
			<fieldset>
				<?php
				$_p = & $this->row->params;
				foreach($this->form->getGroup('templates') as $field):
					$_name  = $field->fieldname;
					$_value = isset($_p[$_name])  ?  $_p[$_name]  :  null;
					
					if ($field->hidden):
						echo $field->input;
					else:
						// setValue(), is ok if input property, has not been already created
						// otherwise we need to re-initialize (which clears input)
						//$field->setup($field->element, $_value, $field->group);
						
						$field->setValue($_value);
						echo '<div class="control-group"><div class="control-label">';
						echo $field->label;
						echo '</div><div class="controls">';
						echo $field->input;
						echo '</div></div>';
						
						
	
					endif;
				endforeach;
				?>
			</fieldset>
			
 
<?php echo JHtml::_('sliders.start','theme-sliders-'.$this->form->getValue("id"), array('useCookie'=>1,'show'=>1)); ?>
				
				<?php
				$groupname = 'attribs';  // Field Group name this is for name of <fields name="..." >
				foreach ($this->tmpls as $tmpl) :
					$fieldSets = $tmpl->params->getFieldsets($groupname);
					foreach ($fieldSets as $fsname => $fieldSet) :
						$label = !empty($fieldSet->label) ? $fieldSet->label : JText::_( 'FLEXI_PARAMETERS_THEMES_SPECIFIC' ) . ' : ' . $tmpl->name;
						echo JHtml::_('sliders.panel',JText::_($label), $tmpl->name.'-'.$fsname.'-options');
						if (isset($fieldSet->description) && trim($fieldSet->description)) :
							echo '<p class="tip">'.$this->escape(JText::_($fieldSet->description)).'</p>';
						endif;
						?>
						<fieldset class="m20">
							<?php foreach ($tmpl->params->getFieldset($fsname) as $field) :
								echo '<div class="control-group">';
								$fieldname =  $field->fieldname;
								$value = $tmpl->params->getValue($fieldname, $groupname, @$this->row->params[$fieldname]);
								echo str_replace('jform_attribs_', 'jform_layouts_'.$tmpl->name.'_',
									'<div class="control-label">'.$tmpl->params->getLabel($fieldname, $groupname)).'</div>';
								echo
									str_replace('jform_attribs_', 'jform_layouts_'.$tmpl->name.'_', 
										str_replace('[attribs]', '[layouts]['.$tmpl->name.']',
											'<div class="controls">'.$tmpl->params->getInput($fieldname, $groupname, $value).'</div>'
										)
									);
					echo '</div>';
							
							endforeach; ?>
						</fieldset>
					<?php endforeach; ?>
				<?php endforeach; ?>
				
				<?php echo JHtml::_('sliders.end'); ?>
      
      <br class="clear">
             <?php echo JHtml::_('bootstrap.endTab');?> 
      <!--/TAB7-->
      
      <?php if ($useAssocs) : ?>
		<!-- Associations tab -->
       <?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', JText::_( '<i class="icon-flag"></i>'.JText::_('FLEXI_ASSOCIATIONS'))); ?> 
      	<?php echo $this->loadTemplate('associations'); ?>
		<?php echo JHtml::_('bootstrap.endTab');?> 
		<?php endif; ?>


		<?php if ( $this->perms->CanRights ) : ?>
		<!-- Permissions tab -->
        
        <?php echo JHtml::_('bootstrap.addTab', 'ID-Tabs-'.$tabSetCnt.'', 'tab'.$tabCnt[$tabSetCnt]++.'', JText::_( '<i class="icon-power-cord"></i>'.JText::_('FLEXI_PERMISSIONS'))); ?>
	
			<div class="fc_tabset_inner">

				<div id="access"><?php echo $this->form->getInput('rules'); ?></div>
			</div>
			
<?php echo JHtml::_('bootstrap.endTab');?> 
		<?php endif; ?>
        
        <!-- / TABS --> 
        <?php echo JHtml::_('bootstrap.endTabSet');?> </div>
        





		
		
	
	
	
	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_flexicontent" />
	<input type="hidden" name="id" value="<?php echo $this->form->getValue('id'); ?>" />
	<input type="hidden" name="controller" value="category" />
	<input type="hidden" name="view" value="category" />
	<input type="hidden" name="task" value="" />
	<?php echo $this->form->getInput('extension'); ?>
</form>
</div>

<?php
//keep session alive while editing
JHTML::_('behavior.keepalive');
?>