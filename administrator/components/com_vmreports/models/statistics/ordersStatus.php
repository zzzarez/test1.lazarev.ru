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

require_once (JPATH_COMPONENT . DS . 'models' . DS . 'statistics' . DS . 'statistics.php');

class OrdersStatusStatistic extends Statistic
{	
	/**
	 * Sets parametters to right values
	 * 
	 * @param $model
	 */
	function __construct($model)
	{		
		$this->selected_chart_type = "single_series_charts";
		$this->selected_chart_name = "pie2D";
		$this->template = "ordersStatus";
		$this->title = "ORDERS_STATUS";
		
		parent::__construct($model);
	}
	
	/**
	 * counts total count and total revenue from all selected records
	 */
	public function countTotalValues() 
	{
		$this->total_count = 0;
		$this->total_revenue = 0;
		
		foreach ($this->data as $val) {
			if (isset($val->count)) {
				$this->total_count += $val->count;
			}
			if (isset($val->revenue)) {
				$this->total_revenue += $val->revenue;
			}
		}

	}
	
	function setParameters()
	{		
		parent::setParameters();
		
		$this->data =& $this->model->getOrdersStatusData();
	}
	
	/**
	 * Renders XML data document for count chart
	 */
	function renderXmlData (){	
		$xml = "<graph showPercentageValues='1' caption='" . JText::_('SALES') . "' >";
		
		foreach ($this->data as $set) {
			$label = $this->shortenLabel($set->status);
			$xml .= "<set label='$label' value='$set->count' hoverText='$set->status: $set->count' />";
		}
		
		$xml .= "</graph>";

    	echo $xml;
    }
    
    /**
     * renders XML data document for revenue chart
     */
	function renderXmlRevenueData (){	
		$xml = "<graph showPercentageValues='1' caption='" . JText::_('REVENUE') ."' >";
		
		foreach ($this->data as $set) {
			$label = $this->shortenLabel($set->status);
			$xml .= "<set label='$label' value='$set->revenue' hoverText='$set->status: ".VmreportsHelper::numberFormat($set->revenue, VMREPORTS_DECIMAL_PRECISION, '.', ' ')." ".VMREPORTS_CURRENCY."' />";
		}
		
		$xml .= "</graph>";

    	echo $xml;
    }
    
	function renderStatisticSettings()
    {
		
		echo "<div>";
    	echo "<div class='settings_div'>";
    	include (VMREPORTS_COMPONENT_ADR . "/views/vmreports/tmpl/params/timePresetsParams.php");
    	echo "</div><div>";
    	include (VMREPORTS_COMPONENT_ADR . "/views/vmreports/tmpl/params/basicParams.php");
    	echo "</div>";
    	echo "</div>";
    }
    
	/**
	 * saves statistic presets to session
	 */
	public function saveToSession ()
	{	
		$session = array();
			
		$parent = parent::saveToSession();
		if (is_array($parent)) {
			$session = array_merge($parent, $session);
		}
		
		return $session;
	}
}