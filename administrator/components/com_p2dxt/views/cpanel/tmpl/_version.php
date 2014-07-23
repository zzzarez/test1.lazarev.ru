<?php 
/**
* P2DXT for Joomla!
* @Copyright ((c) 2008 - 2010 JoomlaXT
* @ All rights reserved
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @ http://www.joomlaxt.com
* @version 1.00.14
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
?>
<fieldset><legend><?php echo JText::_("Version Check");?></legend>
	<table class="adminform" width="100%">
		<tr>
			<td width="300" class="key">
					<?php echo JText::_( 'Installed Version' ); ?>
				</label>
			</td>
			<td>
				<?php echo $this->conf->version ?>
			</td>
		</tr>
		<tr>
			<td width="300" class="key">
					<?php echo JText::_( 'Latest Version' ); ?>
				</label>
			</td>
			<td>
				<?php echo versionCheck(P2Dpro::version())->version ?>
			</td>
		</tr>
	</table>
</fieldset>

