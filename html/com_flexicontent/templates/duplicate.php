<?php
/**
 * @version 1.5 stable $Id: duplicate.php 1750 2013-09-03 20:50:59Z ggppdk $
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

$ctrl_task = FLEXI_J16GE ? 'task=templates.' : 'controller=templates&task=';
$close_popup_js = FLEXI_J16GE ? "window.parent.SqueezeBox.close();" : "window.parent.document.getElementById('sbox-window').close();";
?>
<script type="text/javascript">

jQuery(document).ready(function() {
	var adminForm = jQuery('#adminForm');
	adminForm.submit(function( event ) {
		var log_bind = jQuery('#log-bind');
		log_bind.html('<p class="centerimg"><img src="templates/<?php echo $template;?>/images/flexi/ajax-loader.gif" class="center"></p>');
		jQuery.ajax({
			type: "POST",
			data: adminForm.serialize(),
			url:  adminForm.prop('action'),
			success: function(str) {
				log_bind.html(str);
			}
		});
		event.preventDefault();
	});	
});
</script>
<style type="text/css">
fieldset {
	padding: 0;
	margin: 0;
	border: 0;
}
</style>
<div class="row-fluid">
  <div class="span-12">
    <form action="index.php?option=com_flexicontent&<?php echo $ctrl_task; ?>duplicate&layout=duplicate&<?php echo FLEXI_J16GE ? 'format=raw' : 'tmpl=component';?>" method="post" name="adminForm" id="adminForm" class="form-horizontal">
      <fieldset>
        <h3 color="purple"> <?php echo JText::_( 'FLEXI_DUPLICATE_TEMPLATE' ); ?> <i class="icon-info hasTooltip purple tags" data-placement="bottom" title="<?php echo JText::_( 'FLEXI_DUPLICATE_TEMPLATE_DESC' ); ?>"></i> </h3>
        <hr>
        
          <div class="control-group">
            <div class="controls">
              <div class="center">
                <p>
                  <input type="text" id="dest" name="dest" value="<?php echo $this->dest; ?>" class="input_box" />
                  <input type="hidden" id="source" name="source" value="<?php echo $this->source; ?>" />
                </p>
                <button id="import" type="submit" class="fc_button btn btn-primary" value="<?php echo JText::_( 'FLEXI_DUPLICATE_TEMPLATE_BUTTON' ); ?>" />
                <?php echo JText::_( 'FLEXI_DUPLICATE_TEMPLATE_BUTTON' ); ?>
                </button>
                <button type="button" class="fc_button btn" onclick="window.parent.document.adminForm.submit();<?php echo $close_popup_js;?>" value="<?php echo JText::_( 'FLEXI_CLOSE_IMPORT_TAGS' ); ?>" />
                <?php echo JText::_( 'FLEXI_CLOSE_IMPORT_TAGS' ); ?>
                </button>
              </div>
            </div>
          </div>
      </fieldset>
      <div id="log-bind"></div>
      <?php echo JHTML::_( 'form.token' ); ?>
      <input type="hidden" name="option" value="com_flexicontent" />
      <?php if (FLEXI_J16GE) : ?>
      <input type="hidden" name="task" value="templates.duplicate" />
      <input type="hidden" name="layout" value="templates.duplicate" />
      <input type="hidden" name="format" value="raw" />
      <?php else : ?>
      <input type="hidden" name="task" value="duplicate" />
      <input type="hidden" name="controller" value="templates" />
      <input type="hidden" name="view" value="templates" />
      <input type="hidden" name="tmpl" value="component" />
      <?php endif; ?>
    </form>
  </div>
</div>
