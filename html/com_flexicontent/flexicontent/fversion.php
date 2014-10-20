<?php
/**
* @version 1.5 stable $Id$
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

defined( '_JEXEC' ) or die( 'Restricted access' );
$app = JFactory::getApplication();
$template	= $app->getTemplate();
if($this->check['connect'] == 0) {
?>
	<table class="adminlist">
		<thead>
				<tr>
					<th colspan="2">
					<?php echo JText::_( 'FLEXI_VERSION' ); ?>
					</th>
				</tr>
		</thead>
		<tbody>
			<tr>
			<td colspan="2">
				<?php
					echo '<b><font class="red">'.JText::_( 'FLEXI_CONNECTION_FAILED' ).'</font></b>';
				?>
			</td>
			</tr>
		</tbody>
	</table>
<?php
} elseif ($this->check['enabled'] == 1) {
?>

	<table class="adminlist responsive borderfree">
	<thead>
	<tr>
		<th colspan="2">
		<h3><?php echo JText::_( 'FLEXI_UPDATE_CHECK' ); ?></h3>
		</th>
	</tr>
	</thead>
	<tbody>
	<tr>
<?php /*?>	<td width="33%">
	<?php
		if ($this->check['current'] == 0 ) {		  				
			echo FLEXI_J16GE ?
				JHTML::image('administrator/templates/'. $template .'/images/header/icon-48-checkin.png', NULL, 'width=32') :
				JHTML::_('image.site', 'icon-48-checkin.png', '../administrator/templates/'. $template .'/images/header/', NULL, NULL, NULL, 'width=32') ;
		} elseif( $this->check['current'] == -1 ) {
			echo FLEXI_J16GE ?
				JHTML::image('administrator/templates/'. $template .'/images/header/icon-48-info.png', NULL, 'width=32') :
				JHTML::_('image.site', 'icon-48-info.png', '../administrator/templates/'. $template .'/images/header/', NULL, NULL, NULL, 'width=32') ;
		} else {
			echo FLEXI_J16GE ?
				JHTML::image('administrator/templates/'. $template .'/images/header/icon-48-info.png', NULL, 'width=32') :
				JHTML::_('image.site', 'icon-48-info.png', '../administrator/templates/'. $template .'/images/header/', NULL, NULL, NULL, 'width=32') ;
		}
	?>
	</td><?php */?>
	<th colspan="2" class="text-center">
	<?php
		if ($this->check['current'] == 0) {
			echo '<strong class="green">'.JText::_( 'FLEXI_LATEST_VERSION_INSTALLED' ).'</strong>';
		} elseif( $this->check['current'] == -1 ) {
			echo '<strong class="red">'.JText::_( 'FLEXI_OLD_VERSION_INSTALLED' ).'</strong>';
		} else {
			echo '<strong class="red">'.JText::_( 'You have installed a newer version than the latest officially stable version' /*'FLEXI_NEWS_VERSION_COMPONENT'*/ ).'</strong>';
		}
	?>
	</td>
	</tr>
	<tr>
	<td width="33%">
		<?php echo JText::_( 'FLEXI_LATEST_VERSION' ).':'; ?>
	</td>
	<td>
		<?php echo $this->check['version']; ?>
	</td>
	</tr>
	<tr>
	<td width="33%">
		<?php echo JText::_( 'FLEXI_INSTALLED_VERSION' ).':'; ?>
	</td>
	<td>
		<?php echo $this->check['current_version']; ?>
	</td>
	</tr>
	<tr>
	<td width="33%">
		<?php echo JText::_( 'FLEXI_RELEASED_DATE' ).':'; ?>
	</td>
	<td>
		<?php echo $this->check['released']; ?>
	</td>
	</tr>


	</tbody>
	</table>
<?php
}
?>
