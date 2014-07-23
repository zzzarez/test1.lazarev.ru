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

class OrdersStatistic extends Statistic
{	
	/**
	 * If its not empty, the count chart is displayed
	 * 
	 * @var string
	 */
	public $check_count = "checked='checked'";
	
	/**
	 * If its not empty, the revenue chart is displayed
	 * 
	 * @var strin
	 */
	public $check_revenue = "checked='checked'";
	
	/**
	 * If its not empty, the count average trend lines is isplayed
	 * 
	 * @var string
	 */
	public $check_avg_count = "checked='checked'";
	
	/**
	 * If its not empty, the revenue average trend lines is isplayed
	 * 
	 * @var string
	 */
	public $check_avg_revenue = "checked='checked'";
	
	/**
	 * Sets parametters to right values
	 * 
	 * @param $model
	 */
	function __construct($model)
	{
		$this->selected_chart_type = "multi_series_charts";
		$this->selected_chart_name = "combi3D";
		$this->template = "orders";
		$this->title = "ORDERS";
		$this->show_total_values = true;
		
		parent::__construct($model);
	}
	
	function setParameters()
	{
		$this->model->time_interval = $this->selected_interval;
		
		parent::setParameters();
		
		$this->data =& $this->model->getOrdersData();

	}
	
	/**
	 * saves statistic presets to session
	 */
	public function saveToSession ()
	{		
		if ($_POST) {
			//$session['time_preset'] = JRequest::getVar('time_preset', '' , 'post', 'string');
			$session['time_interval'] = JRequest::getVar('time_interval', '' , 'post', 'string');
			$session['check_count'] = JRequest::getVar('check_count', '' , 'post', 'string');
			$session['check_revenue'] = JRequest::getVar('check_revenue', '' , 'post', 'string');
			$session['check_avg_count'] = JRequest::getVar('check_avg_count', '' , 'post', 'string');
			$session['check_avg_revenue'] = JRequest::getVar('check_avg_revenue', '' , 'post', 'string');
			/*if ($_POST['start_date']) {
				$session['start_date'] = $_POST['start_date'];
			}
			if ($_POST['end_date']) {
				$session['end_date'] = $_POST['end_date'];
			}*/
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
			$session['time_interval'] = 'day';
			
			$session['check_count'] = 'on';
			$session['check_revenue'] = 'on';
			$session['check_avg_count'] = 'on';
			$session['check_avg_revenue'] = 'on';

			return $session;
	}
	
	/**
	 * counts average count and revenue
	 */
	public function countAverageValues()
	{		
		$this->average_count = 0;
		$this->average_revenue = 0; 
		
		if ($this->total_count == 0 && $this->total_revenue == 0) {
			$this->countTotalValues();
		}
		
		$count = count($this->data);
		if ($count != 0) {
			$this->average_count = $this->total_count / $count;
			$this->average_revenue = $this->total_revenue / $count;
		}
	}
	
	function renderXmlData (){
		$xml = "<graph bgColor='E5E5E5' SYAxisName='". Jtext::_('SALES') ."' PYAxisName='". Jtext::_('REVENUE')." (".VMREPORTS_CURRENCY.")' XAxisName='".$this->getTimeIntervalName()."' numVisiblePlot='" . VMREPORTS_NUM_VISIBLE_PLOT . "'>";
		
		$xml .= "<categories>";
		
		$data_length = count($this->data);
		if (count($this->data) > VMREPORTS_NUM_VISIBLE_PLOT) {
			$x_label_gap = $data_length / VMREPORTS_NUM_VISIBLE_PLOT;
		} else {
			$x_label_gap = 1;
		}
		$cat_index = 0;
		$array_index = 0;
		foreach ($this->data as $cat => $set) {
			if (($cat_index /$x_label_gap) >= 1 || $cat_index == 0 /*|| $array_index == $data_length-1*/) {
				$cat = $this->categoryNameByTimeInterval($cat, true);
				$cat_index = 0;
			} else {
				$cat = '';
			}
			$xml .= "<category name='$cat'  />";
			$xml .= "<vLine dashed='1' color='b0b0b0' />";
			
			$cat_index++;
			$array_index++;
		}
		$xml .= "</categories>";
		
		if ($this->check_count){
			$xml .= "<dataset seriesName='". Jtext::_('SALES') ."' renderAs='Line' lineThickness='5' color='9FbA2F' parentYAxis='S' showValues='0' >";
			foreach ($this->data as $cat => $set) {
				if ($set == null){
					$set['count'] = 0;
					$set['revenue'] = 0;
				}
				$cat = $this->getFullCategoryName($cat);
				$xml .= "<set value='$set[count]' hoverText='".Jtext::_('SALE')."(".$cat."): ".VmreportsHelper::numberFormat($set['count'], 0, '.', ' ')."' />";
			}
			$xml .= "</dataset>";
		}
		
		if ($this->check_revenue){
			$xml .= "<dataset seriesName='". Jtext::_('REVENUE') ."' color='AFD8F8' renderAs='Column' parentYAxis='P' showValues='0' >";
			foreach ($this->data as $cat => $set) {
				$cat = $this->getFullCategoryName($cat);
				$xml .= "<set value='$set[revenue]' hoverText='".Jtext::_('REVENUE')."(".$cat."): ".VmreportsHelper::numberFormat($set['revenue'], VMREPORTS_DECIMAL_PRECISION, '.', ' ')." ".VMREPORTS_CURRENCY."' />";
			}
			$xml .= "</dataset>";
		}
		
		$xml .=  "<trendLines>";
			if ($this->check_avg_count){
  				$xml .=  "<line startValue='$this->average_count' color='9FbA2F' thickness='2' parentYAxis='S' " 
  						."toolText='".Jtext::_('AVERAGE_COUNT').": ".VmreportsHelper::numberFormat($this->average_count, VMREPORTS_DECIMAL_PRECISION, '.', ' ')."/".strtolower(JText::_(strtoupper($this->selected_interval)))."' "
  				 		."dashed='1' dashGap='5' displayValue=' ' />";
  			}

  			if ($this->check_avg_revenue){
  				$xml .=  "<line startValue='$this->average_revenue' color='AFD8F8' thickness='2' parentYAxis='P' " 
  						."toolText='".Jtext::_('AVERAGE_REVENUE').": ".VmreportsHelper::numberFormat($this->average_revenue, VMREPORTS_DECIMAL_PRECISION, '.', ' ')." ".VMREPORTS_CURRENCY."/".strtolower(JText::_(strtoupper($this->selected_interval)))."' "
  						." displayValue=' ' />";
  			}
		$xml .= "</trendLines>";
		
		$xml .= "</graph>";
	
    	echo $xml;
    }
    
    function renderStatisticSettings()
    {
    	echo "<div>";
    	echo "<div class='settings_div'>";
    	include (VMREPORTS_COMPONENT_ADR . "/views/vmreports/tmpl/params/timeIntervalParams.php");
    	echo "</div><div style='line-height: 250%;'>";
    	include (VMREPORTS_COMPONENT_ADR . "/views/vmreports/tmpl/params/checkValueTypesParams.php");
    	echo "</div><br/>";
    	echo "<div class='settings_div'>";
    	include (VMREPORTS_COMPONENT_ADR . "/views/vmreports/tmpl/params/timePresetsParams.php");
    	echo "</div><div>";
    	include (VMREPORTS_COMPONENT_ADR . "/views/vmreports/tmpl/params/basicParams.php");
    	echo "</div>";
    	echo "</div>"; 
    }
}
