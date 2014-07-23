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
<fieldset><legend><?php echo JText::_('Setup your paid downloads in few steps');?></legend>
<table class="adminform" style="font-size:12px">
	<tr>
 		<td>
			
			</div>
		</td>
	</tr>
 	<tr>
		<td>
			<?php echo JText::_('Get your Paypal Account at');?> <a target="_blank" href="https://www.paypal.com/de/mrb/pal=D6MXR7SEX68LU">Paypal.com</a>
		</td>
	</tr>
	<tr>
		<td>
			<?php echo JText::_('Maintain your account data:');?><a href="index2.php?option=com_p2dxt&task=editSettings"><?php echo JText::_('Edit Settings');?></a>
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('Create the items to sell:');?><a href="index2.php?option=com_p2dxt&task=listItems"><?php echo JText::_('Edit Items');?></a>
		</td>
	</tr>
	<tr>
		<td><?php echo JText::_('Create a menu entry:');?><a href="index2.php?option=com_menus&menutype=mainmenu"><?php echo JText::_('Edit Menu');?></a>
		</td>
	</tr>	
	<tr>
		<td><?php echo JText::_('View transaction details:');?>  
						<a href="index2.php?option=com_p2dxt&task=txlist"><?php echo JText::_('Show Transactions');?></a>
		</td>
	</tr>
</table>
</fieldset>