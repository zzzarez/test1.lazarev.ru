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

class ProductsSaleabilityStatistic extends Statistic
{
	/**
	 * Value of time interval selectbox. 
	 * Change the GROUP BY clause in SQL query 
	 * 
	 * @var string
	 */
	public $selected_interval = 'day';
	
	/**
	 * If its not empty, the count chart is displayed
	 * 
	 * @var string
	 */
	public $check_count = "checked='checked'";
	
	/**
	 * If its not empty, the revenue chart is displayed
	 * 
	 * @var string
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
	 * Object of ProductsSelect class. 
	 * Renders HTML code of products select dialog. 
	 * 
	 * @var object
	 */
	public $products_select;
	
	/**
	 * Array of selected products ids and names
	 * 
	 * @var array
	 */
	public $selected_products = array();
	
	/**
	 * Sets parametters to right values
	 * 
	 * @param $model
	 */
	function __construct($model)
	{
		$this->selected_chart_type = "multi_series_charts";
		$this->selected_chart_name = "combi2D";
		$this->template = "productsSaleability";
		$this->title = "PRODUCTS_SALEABILITY";
		$this->show_total_values = true;
		
		parent::__construct($model);

		$this->products_select = new ProductsSelect("products_select");
	}
	
	function setParameters()
	{		
		parent::setParameters();
		
		$this->model->time_interval = $this->selected_interval;
		if (is_array($this->selected_products)) {
	 		$this->model->selected_products = implode (',', array_keys($this->selected_products));
		}
	 	
		$this->data =& $this->model->getProductsSaleability();
	}
	
	/**
	 * counts total count and total revenue from all selected records
	 */
	public function countTotalValues() 
	{
		$this->total_count = array();
		$this->total_revenue = array();
		
		if (!is_array($this->data)) return;
		
		foreach ($this->data as $prod) {
			if (!is_array($prod['data'])) continue;
			
			$this->total_count[$prod['name']] = 0;
			$this->total_revenue[$prod['name']] = 0;
			
			foreach ($prod['data'] as $row) {
			if (isset($row['count'])) {
				$this->total_count[$prod['name']] += $row['count'];
			}
			if (isset($row['revenue'])) {
				$this->total_revenue[$prod['name']] += $row['revenue'];
			}
			}
		}
		
	}
	
	/**
	 * counts average count and revenue
	 */
	public function countAverageValues()
	{				
		$this->average_count = array();
		$this->average_revenue = array(); 
		
		if (count($this->total_count) == 0 && count($this->total_revenue == 0)) {
			$this->countTotalValues();
		}
		
		$count = count($this->model->time_intervals);
		if ($count != 0) {
			foreach ($this->total_count as $key => $val) {
				$this->average_count[$key] = $val / $count;
				$this->average_revenue[$key] = $this->total_revenue[$key] / $count;
			}
		}
	}
	
	function renderXmlData (){	
		$xml = "<graph bgColor='E5E5E5' SYAxisName='". Jtext::_('SALES') ."' PYAxisName='". Jtext::_('REVENUE') ." (".VMREPORTS_CURRENCY.")' XAxisName='".$this->getTimeIntervalName()."' numVisiblePlot='" . VMREPORTS_NUM_VISIBLE_PLOT . "'>";
    	
		$xml .= "<categories>";
		
		if (is_array($this->data)) {
			$prod = array_values($this->data);
			
			foreach ($prod[0]['data'] as $cat => $set){
				$cat = $this->categoryNameByTimeInterval($cat);
				$xml .= "<category name='$cat'  />";
				$xml .= "<vLine dashed='1' color='b0b0b0' />";
			}
		}

		$xml .= "</categories>";
		/*echo "<pre>";
		var_dump($this->data);
		echo "</pre>";*/
		if (is_array($this->data)) {
			foreach ($this->data as $prod){
				if ($this->check_count){
					$xml .= "<dataset color='".$this->getColor()."' ";
					//the name can be only in one dataset
					if (!$this->check_revenue || ($this->check_revenue && $this->check_count)){
						$xml .= " seriesName='".$prod['name'] . "' ";
					}
					$xml .= " renderAs='Line' lineThickness='5' parentYAxis='S' showValues='0' >";
					
					foreach ($prod['data'] as $cat => $set) {
						if ($set == null){
							$set['count'] = 0;
						}
						$cat = $this->getFullCategoryName($cat);
						$xml .= "<set value='" . $set['count'] . "' hoverText='".$prod['name'] . " " . Jtext::_('SALE') ."(".$cat."): ".VmreportsHelper::numberFormat($set['count'], 0, '.', ' ')."' />";
					}
					$xml .= "</dataset>";
				}
			
				if ($this->check_revenue){
					$xml .= "<dataset color='".$this->getColor()."' ";
					//the name can be only in one dataset
					if (!$this->check_count){
						$xml .= " seriesName='".$prod['name'] . "' ";
					}
					$xml .= " renderAs='Column' parentYAxis='P' showValues='0' >";
					
					foreach ($prod['data'] as $cat => $set) {
						$cat = $this->getFullCategoryName($cat);
						$xml .= "<set value='" . $set['revenue'] . "' hoverText='".$prod['name'] . " " . Jtext::_('REVENUE') ."(".$cat."): ".VmreportsHelper::numberFormat($set['revenue'], VMREPORTS_DECIMAL_PRECISION, '.', ' ')." ".VMREPORTS_CURRENCY."' />";
					}
					$xml .= "</dataset>";
				}
				
				$this->getColor(true);
			}
		}
		
		$xml .=  "<trendLines>";
		
			$this->getColor(false, 0, true);
			foreach ($this->average_count as $key => $val) {
				
				if ($this->check_avg_count){
  					$xml .=  "<line startValue='$val' color='".$this->getColor()."' " 
  							."toolText='".Jtext::_('AVERAGE_COUNT')." (".$key."): ".VmreportsHelper::numberFormat($val, VMREPORTS_DECIMAL_PRECISION, '.', ' ')."/".strtolower(JText::_(strtoupper($this->selected_interval)))."' "
  				 			."dashed='1' dashGap='5' thickness='1' parentYAxis='S' displayValue=' ' />";
				}
				
				if ($this->check_avg_revenue){
  					$xml .=  "<line startValue='".$this->average_revenue[$key]."' color='".$this->getColor()."' " 
  							."toolText='".Jtext::_('AVERAGE_REVENUE')." (".$key."): ".VmreportsHelper::numberFormat($this->average_revenue[$key], VMREPORTS_DECIMAL_PRECISION, '.', ' ')." ".VMREPORTS_CURRENCY."/".strtolower(JText::_(strtoupper($this->selected_interval)))."' "
  							."displayValue=' ' thickness='1' parentYAxis='P' />";
				}
				
				$this->getColor(true);
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
    	echo "</div><br/>";
    	echo "<div>";
    	include (VMREPORTS_COMPONENT_ADR . "/views/vmreports/tmpl/params/productsSelectionParams.php");
  		echo "</div>";
    	echo "</div>"; 
    }
    
    
	/**
	 * saves statistic presets to session
	 */
	public function saveToSession ()
	{		
		if ($_POST) {
			$session['time_interval'] = JRequest::getVar('time_interval', '' , 'post', 'string');
			$session['check_count'] = JRequest::getVar('check_count', '' , 'post', 'string');
			$session['check_revenue'] = JRequest::getVar('check_revenue', '' , 'post', 'string');
			$session['check_avg_count'] = JRequest::getVar('check_avg_count', '' , 'post', 'string');
			$session['check_avg_revenue'] = JRequest::getVar('check_avg_revenue', '' , 'post', 'string');
			
			$session['products_select'] = JRequest::getVar('products_select', array(), 'post', 'array');
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
			$session['products_select'] = array();

			return $session;
	}
}