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
<div class="responsive-table">
	<table class="adminlist table no-border hover">
                <thead class="no-border">
				<tr>
					<th colspan="2">
					<?php echo JText::_( 'FLEXI_VERSION' ); ?>
					</th>
				</tr>
		</thead>
		<tbody class="no-border-y">
			<tr>
			<td colspan="2">
				<?php
					echo '<strong><font color="red">'.JText::_( 'FLEXI_CONNECTION_FAILED' ).'</font></strong>';
				?>
			</td>
			</tr>
		</tbody>
	</table>
    </div>
<?php
} elseif ($this->check['enabled'] == 1) {
?>

<div class="responsive-table">
	<table class="adminlist table no-border hover">
                <thead class="no-border">
                
	<tr>
		<th colspan="2">
		<?php echo JText::_( 'FLEXI_UPDATE_CHECK' ); ?>
		</th>
	</tr>
	</thead>
	<tbody class="no-border-y">
	<tr>
	<td width="33%">
	<?php
				if ($this->check['current'] == 0 ) {		  				
					echo JHTML::image( 'components/com_flexicontent/assets/images/'.'accept.png', JText::_('FLEXI_LATEST_VERSION_INSTALLED'),  '');
				} elseif( $this->check['current'] == -1 ) {
					echo JHTML::image( 'components/com_flexicontent/assets/images/'.'note.gif', JText::_('FLEXI_OLD_VERSION_INSTALLED'),  '');
				} else {
					echo JHTML::image( 'components/com_flexicontent/assets/images/'.'note.gif', JText::_('You have installed a newer version than the latest officially stable version'),  '');
				}
			?>
	</td>
	<td>
	<?php
				if ($this->check['current'] == 0) {
					echo '<strong><span style="color:darkgreen">'.JText::_( 'FLEXI_LATEST_VERSION_INSTALLED' ).'</span></strong>';
				} elseif( $this->check['current'] == -1 ) {
					echo '
					<strong><span style="color:darkorange">'.JText::_( 'FLEXI_NEWS_VERSION_COMPONENT' /*'FLEXI_OLD_VERSION_INSTALLED'*/ ).'</span></strong>
					<a class="btn btn-primary" href="http://www.flexicontent.org/downloads/latest-version.html" target="_blank" style="margin:4px;">'.JText::_( 'Download' ) .'</a>
					';
				} else {
					echo '<strong><span style="color:#777">'.JText::_( 'You have installed a newer version than the latest official version' /*'FLEXI_NEWS_VERSION_COMPONENT'*/ ).'</span></strong>';
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
				<span class="badge badge-success"><?php echo $this->check['version']; ?></span>
				&nbsp; <b><?php echo JText::_( 'FLEXI_RELEASED_DATE' ); ?></b>:
				<?php echo $this->check['released']; ?>
			</td>
		</tr>
		<tr>
			<td><?php echo JText::_( 'FLEXI_INSTALLED_VERSION' ); ?></td>
			<td>
				<span class="badge <?php echo $this->check['current']==-1 ? 'badge-warning' : ($this->check['current']==0 ? 'badge-success' : ''); ?>"><?php echo $this->check['current_version']; ?></span>
				&nbsp; <b><?php echo JText::_( 'FLEXI_RELEASED_DATE' ); ?></b>:
				
				<?php
					try {
						$timezone = 'UTC';
						$dateformat = 'Y-m-d';
						$date = JHTML::_('date', $this->check['current_creationDate'], $dateformat, $timezone );
					} catch ( Exception $e ) {
						$date = $this->check['current_creationDate'];
					}
					echo $date;
				?>
			</td>
		</tr>


	</tbody>
	</table>
<?php
}
?>
