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

class OrdersByCountryStatistic extends Statistic
{
	/**
	 * Sets parametters to right values
	 * 
	 * @param $model
	 */
	function __construct($model)
	{
		$this->selected_chart_type = "multi_series_charts";
		$this->selected_chart_name = "bar3D";
		$this->template = "ordersByCountry";
		$this->title = "ORDERS_BY_COUNTRY";
		
		parent::__construct($model);
	}
	
	function setParameters()
	{
		$this->model->limit_count = $this->list_limit;
		
		parent::setParameters();
		
		$this->data = $this->model->getOrdersByCountryData();
	}

	public function renderXmlData() {
		$xml = "<graph yAxisName='Revenue' formatNumber='1' thousandSeparator=' ' decimalPrecision='2' inDecimalSeparator='.' formatNumberScale='0' >";

		
		$xml .= "<categories>";
		
		foreach ($this->data as $set) {
			$xml .= "<category name='".$set->name."' />";	
		}
		
		$xml .= "</categories>";
		
		
		$xml .= "<dataset>";
		
		foreach ($this->data as $set){
		$xml .= "<set color='".$this->getColor(true)."' name='".$set->name."' value='" . $set->revenue . "' hoverText='".$set->name.": ".VmreportsHelper::numberFormat($set->revenue, VMREPORTS_DECIMAL_PRECISION, '.', ' ')." ".VMREPORTS_CURRENCY."' />";
		}
		
		$xml .= "</dataset>";
		
		$xml .= "</graph>";
	
    	echo $xml;
	}

	public function renderStatisticSettings() {
		echo "<div class='settings_parrent_div'>";
		
    	echo "<div class='settings_div'>";
    	include (VMREPORTS_COMPONENT_ADR . "/views/vmreports/tmpl/params/timePresetsParams.php");
    	echo "</div><div>";
    	include (VMREPORTS_COMPONENT_ADR . "/views/vmreports/tmpl/params/basicParams.php");
    	echo "</div><br>";
    	echo "<div class='settings_div_two'>";
    	include (VMREPORTS_COMPONENT_ADR . "/views/vmreports/tmpl/params/listLimitParams.php");
    	echo "</div>";
    	//echo "</div>";
    
    	echo "</div>"; 
	}
	
	/**
	 * saves statistic presets to session
	 */
	public function saveToSession ()
	{		
		if ($_POST) {
			$session['list_limit'] = JRequest::getVar('list_limit', '' , 'post', 'string');
		}
		
		$parent = parent::saveToSession();
		if (is_array($parent)) {
			$session = array_merge($parent, $session);
		}
		
		return $session;
	}
	
	/**
	 * returns array of inputs default values
	 */
	public function getDefaultSession() 
	{
		$session['list_limit'] = '5';

		return $session;
	}

}