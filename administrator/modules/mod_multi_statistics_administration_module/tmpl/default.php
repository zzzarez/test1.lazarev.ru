<?php
/**
* iMaQma Joomla Statistics Administration Module
* www.imaqma.com
*
* @package iMaQma Joomla Statistics Administration Module
* @copyright (C) 2006-2010 Components Lab, Lda.
* @license GNU/GPL
*
**/

defined( '_JEXEC' ) or die( 'Restricted access' ); ?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<table class="adminlist">
<tbody>

<?php if( $dimension=='all' || $dimension=='users' ) : ?>
<tr style="background-color:#efefef;">
	<th colspan="3" class="title" style="font-size:14px;height:30px;">
		<strong><?php echo JText::_( 'USERS' ); ?> <a href="index.php?option=com_users"><img src="images/expandall.png" alt="" border="0" /></a></strong>
	</th>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'REGISTERED' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $registered_users;?>
	</td>
	<td rowspan="6">
		<script type="text/javascript">
		  google.load("visualization", "1", {packages:["corechart"]});
		  google.setOnLoadCallback(drawChart);
		  function drawChart() {
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Month');
			data.addColumn('number', 'Users');
			data.addRows(2);
			data.setValue(0, 0, '<?php echo JText::_( 'CHART_PREVIOUS' ); ?>');
			data.setValue(0, 1, <?php echo $reg_last_month;?>);
			data.setValue(1, 0, '<?php echo JText::_( 'CHART_CURRENT' ); ?>');
			data.setValue(1, 1, <?php echo $reg_this_month;?>);

			var chart = new google.visualization.PieChart(document.getElementById('userspie'));
			chart.draw(data, {
								width: 300, 
								height: 180, 
								title: '<?php echo JText::_( 'CHART_USERS' ); ?>',
								legend: 'none',
								pieSliceText: 'value'
							 });
		  }
		</script>
		<div id="userspie" style="height:180px;"></div>
	</td>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'VISITORS' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $current_visitors;?>
	</td>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'REG_THIS_MONTH' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $reg_this_month;?>
	</td>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'REG_LAST_MONTH' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $reg_last_month;?>
	</td>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'REG_THIS_YEAR' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $reg_this_year;?>
	</td>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'REG_LAST_YEAR' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $reg_last_year;?>
	</td>
</tr>
<?php endif; ?>

<?php if( ( $digistore || $virtuemart || $redshop ) && ( $dimension=='all' || $dimension=='store' ) ) : ?>
<tr style="background-color:#efefef;">
	<th colspan="3" class="title" style="font-size:14px;height:30px;">
		<strong><?php echo JText::_( 'ORDERS' ); ?> <i>(<?php echo $store; ?>)</i> <a href="index.php?option=com_<?php echo $store; ?>"><img src="images/expandall.png" alt="" border="0" /></a></strong>
	</th>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'ORDERS_TODAY' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $orders_today;?>
	</td>
	<td rowspan="5">
		<script type="text/javascript">
		  google.load("visualization", "1", {packages:["corechart"]});
		  google.setOnLoadCallback(drawChart);
		  function drawChart() {
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Month');
			data.addColumn('number', 'Users');
			data.addRows(2);
			data.setValue(0, 0, '<?php echo JText::_( 'CHART_PREVIOUS' ); ?>');
			data.setValue(0, 1, <?php echo $orders_last_month;?>);
			data.setValue(1, 0, '<?php echo JText::_( 'CHART_CURRENT' ); ?>');
			data.setValue(1, 1, <?php echo $orders_this_month;?>);

			var chart = new google.visualization.PieChart(document.getElementById('orderspie'));
			chart.draw(data, {
								width: 300, 
								height: 150, 
								title: '<?php echo JText::_( 'CHART_ORDERS' ); ?>',
								legend: 'none',
								pieSliceText: 'value'
							 });
		  }
		</script>
		<div id="orderspie" style="height:150px;"></div>
	</td>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'ORDERS_THIS_MONTH' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $orders_this_month;?>
	</td>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'ORDERS_LAST_MONTH' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $orders_last_month;?>
	</td>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'ORDERS_THIS_YEAR' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $orders_this_year;?>
	</td>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'ORDERS_LAST_YEAR' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $orders_last_year;?>
	</td>
</tr>
<?php endif; ?>

<?php if( $helpdesk && ( $dimension=='all' || $dimension=='helpdesk' ) ) : ?>
<tr style="background-color:#efefef;">
	<th colspan="3" class="title" style="font-size:14px;height:30px;">
		<strong><?php echo JText::_( 'HELPDESK' ); ?> <a href="index.php?option=com_maqmahelpdesk"><img src="images/expandall.png" alt="" border="0" /></a></strong>
	</th>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'HELPDESK_OPEN' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $tickets_pending;?>
	</td>
	<td rowspan="6">
		<script type="text/javascript">
		  google.load("visualization", "1", {packages:["corechart"]});
		  google.setOnLoadCallback(drawChart);
		  function drawChart() {
			var data = new google.visualization.DataTable();
			data.addColumn('string', 'Month');
			data.addColumn('number', 'Users');
			data.addRows(2);
			data.setValue(0, 0, '<?php echo JText::_( 'CHART_PREVIOUS' ); ?>');
			data.setValue(0, 1, <?php echo $tickets_last_month;?>);
			data.setValue(1, 0, '<?php echo JText::_( 'CHART_CURRENT' ); ?>');
			data.setValue(1, 1, <?php echo $tickets_this_month;?>);

			var chart = new google.visualization.PieChart(document.getElementById('ticketspie'));
			chart.draw(data, {
								width: 300, 
								height: 180, 
								title: '<?php echo JText::_( 'CHART_HELPDESK' ); ?>',
								legend: 'none',
								pieSliceText: 'value'
							 });
		  }
		</script>
		<div id="ticketspie" style="height:180px;"></div>
	</td>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'HELPDESK_TODAY' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $tickets_today;?>
	</td>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'HELPDESK_THIS_MONTH' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $tickets_this_month;?>
	</td>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'HELPDESK_LAST_MONTH' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $tickets_last_month;?>
	</td>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'HELPDESK_THIS_YEAR' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $tickets_this_year;?>
	</td>
</tr>
<tr>
	<td style="padding-left:15px;">
		<?php echo JText::_( 'HELPDESK_LAST_YEAR' ); ?>
	</td>
	<td style="text-align:right;font-size:16px;font-weight:bold;padding-right:10px;">
		<?php echo $tickets_last_year;?>
	</td>
</tr>
<?php endif; ?>

</tbody>
</table>
