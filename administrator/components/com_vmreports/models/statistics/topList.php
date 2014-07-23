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

class TopListStatistic extends Statistic
{
	/**
	 * 
	 * @var string name of color tema
	 */
	protected $color_schema;
	
	/**
	 * 
	 * @param $index is the start index of colors array to rotate
	 */
	function setStartColorIndex($index) {
		$this->start_color_index = $index;
	}
	
	/**
	 * Sets parametters to right values
	 * 
	 * @param $model
	 */
	function __construct($model)
	{
		$this->selected_chart_type = "multi_series_charts";
		$this->selected_chart_name = "combi2D";
		$this->template = "topList";
		
		parent::__construct($model);		
	}
	
	function setParameters()
	{		
		$this->model->order_by = $this->order_by;
		$this->model->limit_count = $this->list_limit;
		
		parent::setParameters();
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
	
	
	/**
	 * returns color of selected schema
	 * 
	 * @param string $schema name of color schema
	 * @param int $index index of color
	 */
	private function getTopListColor($schema, $index) {
		switch ($schema) {
			case 'topProducts':
				if ($index == 0) return "AFD8F8";
				if ($index == 1) return "F6BD0F";
				break;
			case 'topUsers':
				if ($index == 0) return "00cccc";
				if ($index == 1) return "00ae00";
				break;
			case 'topCategories':
				if ($index == 0) return "23b8dc";
				if ($index == 1) return "e6e64c";
				break;
			default:
				return "";
				break;
		}
	}

	public function renderXmlData() {
		$xml = "<graph SYAxisName='". Jtext::_('SALES') ."' PYAxisName='". Jtext::_('REVENUE') ." (".VMREPORTS_CURRENCY.")'  >";
		
		$xml .= "<categories>";
		foreach ($this->data as $category){	
				$xml .= "<category name='$category->name'  />";
				$xml .= "<vLine dashed='1' color='b0b0b0' />";
		}
		$xml .= "</categories>";
		
		
		$xml .= "<dataset seriesName='".Jtext::_('SALES') ."' color='".$this->getTopListColor($this->color_schema, 0)."' renderAs='Bar'  showValues='0' parentYAxis='S' >";
		foreach ($this->data as $set){
		$xml .= "<set value='" . $set->count . "' hoverText='".Jtext::_('SALES')."(".$set->name."): ".VmreportsHelper::numberFormat($set->count, 0, '.', ' ')."' />";
		}
		$xml .= "</dataset>";
			
		$xml .= "<dataset seriesName='". Jtext::_('REVENUE') ."' color='".$this->getTopListColor($this->color_schema, 1)."' showValues='0' renderAs='Bar' parentYAxis='P' >";
		foreach ($this->data as $set){
		$xml .= "<set value='" . $set->revenue . "' hoverText='".Jtext::_('REVENUE')."(".$set->name."): ".VmreportsHelper::numberFormat($set->revenue, VMREPORTS_DECIMAL_PRECISION, '.', ' ')." ".VMREPORTS_CURRENCY."' />";
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
    	echo "</div><br/>";
    	
    	echo "<div class='settings_div'>";
    	include (VMREPORTS_COMPONENT_ADR . "/views/vmreports/tmpl/params/listLimitParams.php");
  		echo "</div><div class='settings_div_two'>";
    	include (VMREPORTS_COMPONENT_ADR . "/views/vmreports/tmpl/params/orderParams.php");
    	echo "</div>";
    	
    	echo "</div>"; 	
	}
	
	/**
	 * saves statistic presets to session
	 */
	public function saveToSession ()
	{		
		if ($_POST) {
			$session['order_by'] = JRequest::getVar('order_by', '' , 'post', 'string');
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
		$session['order_by'] = 'count';
		$session['list_limit'] = '5';

		return $session;
	}

}