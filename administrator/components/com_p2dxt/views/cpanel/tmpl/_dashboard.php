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
<fieldset><legend><?php echo JText::_("Dashboard");?></legend>
<table class="adminform" width="100%">
	<tr><td>
	
 	<table align="left" class="adminlist">
	<thead>
 		<tr>
 			<th width="10%"></th>
 			<th><a href="index2.php?option=com_p2dxt&task=txlist&filter_time=cDay"><?php echo JText::_("Today");?></a></th> 
 			<th><a href="index2.php?option=com_p2dxt&task=txlist&filter_time=lDay"><?php echo JText::_("Yesterday");?></a></th> 
 			<th><a href="index2.php?option=com_p2dxt&task=txlist&filter_time=prevWeek"><?php echo JText::_("Last 7 days");?></a></th> 
 			<th><a href="index2.php?option=com_p2dxt&task=txlist&filter_time=cMonth"><?php echo JText::_("Current Month");?></a></th> 
 			<th><a href="index2.php?option=com_p2dxt&task=txlist&filter_time=lMonth"><?php echo JText::_("Last Month");?></a></th> 
 			<th><a href="index2.php?option=com_p2dxt&task=txlist&filter_time=cYear"><?php echo JText::_("Current Year");?></a></th> 
 			<th><a href="index2.php?option=com_p2dxt&task=txlist&filter_time=lYear"><?php echo JText::_("Last Year");?></a></th> 
 			<th><a href="index2.php?option=com_p2dxt&task=txlist&filter_time="><?php echo JText::_("Total");?></a></th> 
		</tr>
	</thead>
 		<tr class="row0">
 			<td class="key"><?php echo JText::_("Sales Volume");?></td> 
 			<td><?php echo $this->lists["cDayVal"];?></td> 
 			<td><?php echo $this->lists["lDayVal"];?></td> 
 			<td><?php echo $this->lists["prevWeekVal"];?></td> 
 			<td><?php echo $this->lists["cMonthVal"];?></td> 
 			<td><?php echo $this->lists["lMonthVal"];?></td> 
 			<td><?php echo $this->lists["cYearVal"];?></td> 
 			<td><?php echo $this->lists["lYearVal"];?></td> 
 			<td><?php echo $this->lists["totalVal"];?></td> 
 		</tr>
 		<tr class="row1">
 			<td><?php echo JText::_("No. of transactions");?></td> 
 			<td><?php echo $this->lists["cDayCount"];?></td> 
 			<td><?php echo $this->lists["lDayCount"];?></td> 
 			<td><?php echo $this->lists["prevWeekCount"];?></td> 
 			<td><?php echo $this->lists["cMonthCount"];?></td> 
 			<td><?php echo $this->lists["lMonthCount"];?></td> 
 			<td><?php echo $this->lists["cYearCount"];?></td> 
 			<td><?php echo $this->lists["lYearCount"];?></td> 
 			<td><?php echo $this->lists["totalCount"];?></td> 
 		</tr>
	</table>
</td></tr></table>
</fieldset>