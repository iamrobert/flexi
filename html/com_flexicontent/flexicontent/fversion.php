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


<div class="block-flat">
          <div class="header">							
            <h3 class="ruler"><?php echo JText::_( 'FLEXI_VERSION' ); ?></h3>
          </div>
</div>


<div class="responsive-table">
	<table class="adminlist table no-border no-hover">
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
					echo '<div class="alert warning-info">'.JText::_( 'FLEXI_CONNECTION_FAILED' ).'</div>';
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
	<table class="adminlist table no-border no-hover">
                <thead class="no-border">
                

	</thead>
	<tbody class="no-border-y">
	<tr>
	<td colspan="2" class="no-border-top">
	<?php
				if ($this->check['current'] == 0 ) {		  				
					echo '<div class="alert alert-success">'.JText::_('FLEXI_LATEST_VERSION_INSTALLED').'</div>';
				} elseif( $this->check['current'] == -1 ) {
					echo '<div class="alert alert-info">'.JText::_('FLEXI_OLD_VERSION_INSTALLED').'</div>';
				} else {
					echo '<div class="alert alert-info">'.JText::_('You have installed a newer version than the latest officially stable version').'</div>';
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
