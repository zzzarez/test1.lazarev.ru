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
<form action="index2.php?option=com_p2dxt" method="post" name="adminForm">
	<input type="hidden" name="hidemainmenu" value="" />
	<input type="hidden" name="option" value="com_p2dxt" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="controller" value="" />
<table width="100%">
<tr>
 	<td colspan="2">
 		<?php 
		 	if (isset($this->dashboard)) echo $this->dashboard; ?>
 	</td>
</tr>
<tr>
<td width="60%" valign="top">
<?php 
	echo $this->default;
	echo $this->itemlist;

	
 ?>

</td>
<td valign="top">
<?php 
	echo $this->version;
	echo $this->addinfo;	
 ?>
</td>
</tr>
<tr>
 	<td colspan="2">
 		<?php if (isset($this->banner)) echo $this->banner; ?>
 	</td>
</tr>
</table>
</form>
