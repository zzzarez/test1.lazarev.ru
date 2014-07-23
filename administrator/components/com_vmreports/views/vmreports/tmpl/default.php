<?php

/**
 * Componet for dispaying statistics about VirtueMart trades.
 * Can display many types of statistics and customized them by
 * number of parameters.  
 *
 * @version		$Id$
 * @package     ArtioVMReports
 * @copyright	Copyright (C) 2010 ARTIO s.r.o.. All rights reserved. 
 * @author 		ARTIO s.r.o., http://www.artio.net
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link        http://www.artio.net Official website
 */

defined ( '_JEXEC' ) or die ( 'Restricted access' );

VmreportsHelper::importChartJS ();

?>

<div class='left_panel'>
				<?php
				echo $this->renderMenu ();
				?>
			</div>

<div class='center_panel'>
<form action="" method="post" name="adminForm">

<fieldset><legend class='title'>
<?php
echo Jtext::_ ( 'STATISTIC_SETTINGS' );
?>
</legend>
		
			<?php
			$this->statistic->renderStatisticSettings ();
			?>
		<br/>
			<input type="submit"
			value="<?php
				echo Jtext::_ ( 'SHOW' );
			?>" />
		

</fieldset>

</form>	
		
		<?php
		if ($this->statistic->show_total_values) {
			$ret = "<fieldset>";
			$ret .= "<legend class='title'>";
			$ret .= Jtext::_ ( 'TOTAL_COUNTS' );
			$ret .= "</legend>";
			echo $ret;
			echo $this->statistic->renderTotalValues ();
			echo "</fieldset>";
		}
		?>

		<fieldset><legend class='title'>
<?php
echo Jtext::_ ( 'CHART_VIEW' );
?>
</legend>

<div>
		 <?php
			require (VMREPORTS_COMPONENT_ADR . '/views/vmreports/tmpl/graphTmpl/' . $this->statistic->template . '.php');
			?>
		 </div>
</fieldset>
</div>

<script type="text/javascript">
<!--
	timePresetChanged(false);
	checkChanged ('count');
	checkChanged ('revenue');
//-->
</script>
