<?php
/**
 * @version 1.5 stable $Id: import.php 1614 2013-01-04 03:57:15Z ggppdk $
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

$ctrl_task = FLEXI_J16GE ? 'task=tags.' : 'controller=tags&task=';
$close_popup_js = FLEXI_J16GE ? "window.parent.SqueezeBox.close();" : "window.parent.document.getElementById('sbox-window').close();";
?>
<script type="text/javascript">
window.addEvent('domready', function(){
	$('adminForm').addEvent('submit', function(e) {
		e = new Event(e).stop();
		if (MooTools.version>="1.2.4") {
			$('log-bind').set('html','<p class="centerimg"><img src="components/com_flexicontent/assets/images/ajax-loader.gif" align="center"></p>');
			new Request.HTML({
				 url: this.get('action'),
			   evalScripts: true,
			   update: $('log-bind'),
			   data: $('adminForm')
			}).send();
		} else {
			$('log-bind').setHTML('<p class="centerimg"><img src="components/com_flexicontent/assets/images/ajax-loader.gif" align="center"></p>');
			this.send({
				update: 	$('log-bind')
			});
		}
		
	});
}); 
</script>
<div class="row-fluid">
<div class="span-12">
<form action="index.php?option=com_flexicontent&".$ctrl_task."import&layout=import&<?php echo FLEXI_J16GE ? 'format=raw' : 'tmpl=component';?>" method="post" name="adminForm" id="adminForm">

<div class="control-group">
<div class="controls">

	<fieldset>
		
			<h3 class="purple"><?php echo JText::_( 'FLEXI_IMPORT_TAGS' ); ?> <i class="icon-info hasTip purple hasTip tags" title="<?php echo JText::_( 'FLEXI_IMPORT_TAGS_DESC' ); ?>"></i></h3>
		
		<textarea id="taglist" name="taglist" rows="20" cols="51"></textarea>
	</fieldset>
</div>
</div>
<br>

<div class="form-inline">
<div class="control-group">
<div class="controls">
<div class="center">
			<button id="import" type="submit" class="fc_button btn btn-primary" value="<?php echo JText::_( 'FLEXI_IMPORT_TAGS_BUTTON' ); ?>" /><?php echo JText::_( 'FLEXI_IMPORT_TAGS_BUTTON' ); ?></button>
			<button type="button" class="fc_button btn" onclick="window.parent.document.adminForm.submit();<?php echo $close_popup_js;?>" value="<?php echo JText::_( 'FLEXI_CLOSE_IMPORT_TAGS' ); ?>" /><?php echo JText::_( 'FLEXI_CLOSE_IMPORT_TAGS' ); ?></button>		

</div>
</div>
</div>
</div>
	<div id="log-bind"></div>

	<?php echo JHTML::_( 'form.token' ); ?>
	<input type="hidden" name="option" value="com_flexicontent" />

<?php if (FLEXI_J16GE) : ?>
	<input type="hidden" name="task" value="tags.import" />
	<input type="hidden" name="layout" value="import" />
	<input type="hidden" name="format" value="raw" />
<?php else : ?>
	<input type="hidden" name="task" value="import" />
	<input type="hidden" name="controller" value="tags" />
	<input type="hidden" name="view" value="tags" />
	<input type="hidden" name="tmpl" value="component" />
<?php endif; ?>

</form></div></div>