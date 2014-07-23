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

?>

<div id="chartdiv">
		<script type="text/javascript">
    		//<![CDATA[ 
	        	var chartObj = null;
        	
    			window.addEvent('load', function() {
    				chartObj = new FusionCharts("<?php
								echo $this->statistic->chart_url;
								?>", "ChartId", "<?php
								echo VMREPORTS_CHART_WIDTH?>", "<?php
								echo VMREPORTS_CHART_HEIGHT?>", "0", "0");
    				var xml = "<?php
								$this->statistic->renderXmlData ();
								?>";
	    			chartObj.setDataXML(xml);   
    				chartObj.render("chartdiv");
	    		});
			//]]>
		</script>


</div>

<div id="secondchartdiv"></div>
		<script type="text/javascript">
    		//<![CDATA[ 
	        	var chartObj = null;
        	
    			window.addEvent('load', function() {
    				revenueChartObj = new FusionCharts("<?php
								echo $this->statistic->chart_url;
								?>", "ChartId", "<?php
								echo VMREPORTS_CHART_WIDTH?>", "<?php
								echo VMREPORTS_CHART_HEIGHT?>", "0", "0");
    				var revenueXml = "<?php
								$this->statistic->renderXmlRevenueData ();
								?>";
	    			revenueChartObj.setDataXML(revenueXml);   
    				revenueChartObj.render("secondchartdiv");
	    		});
			//]]>
		</script>