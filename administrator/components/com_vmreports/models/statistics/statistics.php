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

/**
 * abstract class of statistic. Contains parameters common for all statistics
 * 
 * @author pedu
 *
 */
abstract class Statistic
{	
	/**
	 * points to dhe folder in /charts
	 * 
	 * @var string
	 */
	public $selected_chart_type;
	
	/**
	 * points to the the folder in /charts/*_series_charts
	 * 
	 * @var unknown_type
	 */
	public $selected_chart_name;
	
	/**
	 * actual date
	 * 
	 * @var array
	 */
	public $date;
	
	/**
	 * default start date
	 * 
	 * @var string
	 */
	public $start_date_default;
	
	/**
	 * start date of statistic
	 * 
	 * @var string
	 */
	public $start_date;
	
	/**
	 * last day of statistic
	 * 
	 * @var string
	 */
	public $end_date;
	
	/**
	 * contains loaded data from database
	 * 
	 * @var array
	 */
	public $data;
	
	/**
	 * object of class VmreportsModelVmreports
	 * 
	 * @var object
	 */
	protected $model;
	
	/**
	 * value of selectbox with time presetts
	 * 
	 * @var string
	 */
	public $selected_time_preset;
	
	/**
	 * URL of chart *.swf file
	 * 
	 * @var string
	 */
	public $chart_url;
	
	/**
	 * name of column in SQL query, which is used for ordering 
	 * 
	 * @var string
	 */
	 public $order_by;
	
	/**
	 * value of selectbox. Amount of selected records 
	 * 
	 * @var string
	 */
	public $list_limit;
	
	/**
	 * name of php file in views/vmreports/tmpl/graphTmpl. this one is included in the main part of page
	 * 
	 * @var string
	 */
	public $template;
	
	/**
	 * title of the page
	 * 
	 * @var string
	 */
	public $title;
	
	/**
	 * Sum of all counts
	 * 
	 * @var int or array of ints
	 */
	public $total_count = 0;
	
	/**
	 * Sum of all revenues
	 * 
	 * @var float or array of floats
	 */
	public $total_revenue = 0;
	
	/**
	 * average count of orders
	 * 
	 * @var float or array of floats
	 */
	public $average_count = 0;
	
	/**
	 * average revenue of orders
	 * 
	 * @var float or array of floats
	 */
	public $average_revenue = 0;
	
	/**
	 * if true, total count values are displayed 
	 * 
	 * @var bool
	 */
	public $show_total_values = false;
	
	/**
	 * Value of time interval selectbox. 
	 * Change the GROUP BY clause in SQL query 
	 * 
	 * @var string
	 */
	public $selected_interval = VMREPORTS_DEFAULT_SELECTED_TIME_INTERVAL;
	
	/**
	 * Sets parametters to right values
	 * 
	 * @param $model
	 */
	function __construct($model)
	{
		$this->model = $model;
	}
	
	/**
	 * counts total count and total revenue from all selected records
	 */
	public function countTotalValues() 
	{
		$this->total_count = 0;
		$this->total_revenue = 0;
		
		if (is_array($this->data)) {
			foreach ($this->data as $val) {
				if (isset($val['count'])) {
					$this->total_count += $val['count'];
				}
				if (isset($val['revenue'])) {
					$this->total_revenue += $val['revenue'];
				}
			}
		}
	}
	
	/**
	 * renders HTML code, which shows total count and revenue
	 */
	public function renderTotalValues()
	{
		if (is_array($this->total_count)) {
			return $this->renderTotalValuesList();
		}
		
		$ret  = "<div>"
    			."<div class='settings_div'>";
    	$ret .=	"<span class='total_count_label'>".Jtext::_('TOTAL_COUNT').": </span>".$this->total_count;
    	$ret .= "</div>"
    			."<div style='line-height: 250%;'>";
    	$ret .= "<span class='total_count_label'>".Jtext::_('TOTAL_REVENUE').": </span>".VmreportsHelper::numberFormat($this->total_revenue, VMREPORTS_DECIMAL_PRECISION, '.', ' ')." ".VMREPORTS_CURRENCY;
    	$ret .= "</div>"
    			."</div>";	
    			
    	return $ret;
	}
	
	/**
	 * renders HTML code, which shows total count and revenue
	 */
	public function renderTotalValuesList()
	{		
		if (!is_array($this->total_count) || !is_array($this->total_revenue)) return;
		
		if (count ($this->total_count) == 0) {
			$r  = "<div><h3>";
			$r .= Jtext::_('NO_DATA_TO_DISPLAY');
    		$r .= "</h3></div>";	
			
			return $r;
		}
		
		foreach ($this->total_count as $name => $val) {
			$ret .= "<div>"
    				."<div class='settings_div'>";
    		$ret .= "<span class='total_count_label'>".$name." ".Jtext::_('SALE').": </span>".$val;
    		$ret .= "</div>"
    				."<div style='line-height: 250%;'>";
    		$ret .= "<span class='total_count_label'>".$name." ".Jtext::_('REVENUE').": </span>".VmreportsHelper::numberFormat($this->total_revenue[$name], VMREPORTS_DECIMAL_PRECISION, '.', ' ')." ".VMREPORTS_CURRENCY;
    		$ret .= "</div>"
    				."</div>";	
		}
    			
    	return $ret;
	}
	
	/**
	 * counts average count and revenue
	 */
	public function countAverageValues()
	{
		
	}
	
	/**
	 * return string name of selected time interval from language pack
	 */
	protected function getTimeIntervalName() {
		
	switch ($this->selected_interval) {
			case 'hour':
				$time_interval = Jtext::_('HOURS');
				break;
			case 'day':
				$time_interval = Jtext::_('DAYS');
				break;
			case 'week':
				$time_interval = Jtext::_('WEEKS');
				break;
			case 'month':
				$time_interval = Jtext::_('MONTHS');
				break;
		}
		
	return $time_interval;
	}
	
	/**
	 * sets time attributes with values from request
	 */
	function setTimeSettings()
	{		
		if ($this->selected_time_preset != 'custom'){
			switch ($this->selected_time_preset){
				case 'last_24':
					$start = time() - VMREPORTS_SEC_IN_DAY;
					$this->model->last_day = true;
					break;
				case 'last_week':
					$start = mktime(0, 0, 0, $this->date['mon'], $this->date['mday'], $this->date['year']) - (VMREPORTS_SEC_IN_DAY * 7);
					break;
				case 'last_30':
					$start = mktime(0, 0, 0, $this->date['mon'], $this->date['mday'], $this->date['year']) - (VMREPORTS_SEC_IN_DAY * 30);
				break;
					break;
				case 'last_90':
					$start = mktime(0, 0, 0, $this->date['mon'], $this->date['mday'], $this->date['year']) - (VMREPORTS_SEC_IN_DAY * 90);
					break;
				case 'last_year':
					$start = mktime(0, 0, 0, $this->date['mon'], $this->date['mday'], $this->date['year'] - 1);
					break;
			}
			$this->start_date = VmreportsHelper::dateFormat($start);
		}

		$this->model->start_date = $this->start_date;
		$this->model->end_date = $this->end_date;
	}
	
	/**
	 * method returns colors of datasets
	 * 
	 * @param bool $next if true the next color of array is used
	 * @param int $start is the start index of colors array to rotate
	 */
	public function getColor ($next = false, $start = 0, $reset = false) {
		$colors = array('AFD8F8' , 'F6BD0F' , '8BBA00' , 'FF8E46' , '008E8E' , 'D64646' , '8E468E' , '588526' , 'B3AA00' , '008ED6' , '9D080D' , 'A186BE' , 'CC6600' , 'FDC689' , 'ABA000' , 'F26D7D' , 'FFF200' , '0054A6' , 'F7941C' , 'CC3300' , '006600' , '663300' , '6DCFF6');
		$arr_length = count($colors);

		static $index;
		
		if ($reset){
			$index = 0;
		}
				
		if (!$index) {
			$index = $start;
		}
		
		if ($next) {
			if ($index < $arr_length) {
				$index++;
			} else {
				$index = 0;
			}
		}
		
		return $colors[$index];
	}
	
	/**
	 * Abstract method. Used for renderin XML document from loaded data 
	 */
	abstract public function renderXmlData();
	
	/**
	 * Abstract method. Used for rendering of form with statistic options
	 */
	abstract public function renderStatisticSettings();
	
	/**
	 * Sets model paramaters and count other necessery values
	 */
	public function setParameters ()
	{
		$this->setTimeSettings();
		$this->chart_url = VMREPORTS_CHARTS . $this->selected_chart_type . DS . $this->selected_chart_name . DS . VMREPORTS_CHART_NAME;
	}
	
	/**
	 * saves statistic presets to session
	 */
	public function saveToSession ()
	{		
		if ($_POST) {
			$session['time_preset'] = JRequest::getVar('time_preset', '' , 'post', 'string');
			
			if ($_POST['start_date']) {
				$session['start_date'] = $_POST['start_date'];
			}
			if ($_POST['end_date']) {
				$session['end_date'] = $_POST['end_date'];
			}
			if ($_POST['start_date_hid']) {
				$session['start_date_hid'] = $_POST['start_date_hid'];
			}
			if ($_POST['end_date_hid']) {
				$session['end_date_hid'] = $_POST['end_date_hid'];
			}
		}
		
		return $session;
	}
	
	/**
	 * returns array of inputs default values
	 */
	public function getDefaultSession () {
		
	}
	
	/**
	 * Renders radiobuttons with time intervals 
	 */
	function timeIntervalSelect (){
		$put[] = JHTML::_('select.option',  'hour', JText::_( 'HOUR' ));
    	$put[] = JHTML::_('select.option',  'day', JText::_( 'DAY' ));
		$put[] = JHTML::_('select.option',  'week', JText::_( 'WEEK' ));
		$put[] = JHTML::_('select.option',  'month', JText::_( 'MONTH' ));

		$time_interval = JHTML::_('select.radiolist',  $put, 'time_interval', array('class' => 'radio', 'style' => 'margin-top: 5px;'), 'value', 'text', $this->selected_interval );
		return $time_interval;
    }
    
    /**
     * Renders radiobutton with "order by" values
     */
    function orderBySelect(){
    	$put[] = JHTML::_('select.option',  'count', JText::_( 'SALES' ));
		$put[] = JHTML::_('select.option',  'revenue', JText::_( 'REVENUE' ));

		$order_by = JHTML::_('select.radiolist',  $put, 'order_by', array('class' => 'radio'), 'value', 'text', $this->order_by );
		
		return $order_by;
    }
    
    /**
     * Renders selectbox with top list lenght
     */
	function topListLimitSelect(){
    	$put[] = JHTML::_('select.option',  '5', '5');
		$put[] = JHTML::_('select.option',  '10', '10');
		$put[] = JHTML::_('select.option',  '20', '20');

		$order_by = JHTML::_('select.genericlist',  $put, 'list_limit', array('style' => 'margin-top: 5px;'), 'value', 'text', $this->list_limit );
		
		return $order_by;
    }
    
    /**
     * Renders selectbox with time presets
     */
	function timePresetsSelect (){
		$put[] = JHTML::_('select.option',  'custom', JText::_( 'CUSTOM' ));
    	$put[] = JHTML::_('select.option',  'last_24', JText::_( 'LAST_24_HOURS' ));
		$put[] = JHTML::_('select.option',  'last_week', JText::_( 'LAST_WEEK' ));
		$put[] = JHTML::_('select.option',  'last_30', JText::_( 'LAST_30_DAYS' ));
		$put[] = JHTML::_('select.option',  'last_90', JText::_( 'LAST_90_DAYS' ));
		$put[] = JHTML::_('select.option',  'last_year', JText::_( 'LAST_YEAR' ));

		$time_interval = JHTML::_('select.genericlist',  $put, 'time_preset', array('onchange'=>'timePresetChanged(true)', 'style' => 'margin-top: 5px;'), 'value', 'text', $this->selected_time_preset );
		return $time_interval;
    }
    
    /**
     * Recursice Method. Renders selectbox from from tree array of categories  
     * 
     * @param $cats categories tree data
     * @param $level current level in the categories tree
     * @param $skip skip of value in selectbox from left border 
     */
	function renderCategoriesMultipleSelect($cats = null, $level = 0, $skip = ""){
    	$sel = "";
    	
    	if ($level == 0){
    		echo "<select multiple size='10' name='selected_categories[]' >";
    	}
 
    	if ($level == 0){
    		$cats = $this->categories_tree;
    	}
		
    	foreach ($cats as $cat) {
    		echo "<option value='$cat->id' ";
    		if (is_array($this->selected_categories)) {
    			if (in_array($cat->id, $this->selected_categories)){
    				echo " selected='true' ";
	    		}
    		}
    		echo ">";
    		if ($level == 0) {
    			echo $cat->name."</option>";
    		} else {
    			echo $skip."_".$cat->name."</option>";
    		}
    		
    		
    		if ($cat->subcats) {
    				$gap = $skip."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|";
    			
    			$this->renderCategoriesMultipleSelect($cat->subcats, $level+1, $gap);
    		}
    	}

    	if ($level == 0){
    		echo "</select>";
    	}
    }
    
    protected function getFullCategoryName ($cat) 
    {
    		$date = explode ("-", $cat);
    		$date[1] = ltrim($date[1], '0');
    		$date[2] = ltrim($date[2], '0');
    		switch ($this->selected_interval){
				case 'hour':
					$hour = explode(" ", $date[2]);
					$hr = (int)$hour[1];
					if ($hr >= 12) {
						if ($hr != 12) {
							$hr -= 12;
						}
						$hr .= " ".Jtext::_('PM');
					} else {
						if ($hr == 0) {
							$hr = 12;
						}
						$hr .= " ".Jtext::_('AM');
					}
					$category = $hr." ".$hour[0].".".$date[1].".";
					break;
				case 'day':
					$category = $date[2].".".(int)$date[1].".".substr($date[0], 2);
					break;
				case 'week':
					$category = $date[1]."/".substr($date[0], 2);
					break;
				case 'month':
					$category = $date[1]."/".substr($date[0], 2);
					break;
			}
		return $category;
    }
    
    protected function categoryNameByTimeInterval($cat, $no_scrollbar_date_format = false)
    {
    	if ($no_scrollbar_date_format) {
    		$date = explode ("-", $cat);
    		$date[1] = ltrim($date[1], '0');
    		$date[2] = ltrim($date[2], '0');
    		switch ($this->selected_interval){
				case 'hour':
					$hour = explode(" ", $date[2]);
					$hour[0] = ltrim($hour[0], '0');
					$hour[1] = ltrim($hour[1], '0');   
    				$hour = explode(" ", $date[2]);
					$hr = (int)$hour[1];
					if ($hr >= 12) {
						if ($hr != 12) {
							$hr -= 12;
						}
						$hr .= " ".Jtext::_('PM');
					} else {
						if ($hr == 0) {
							$hr = 12;
						}
						$hr .= " ".Jtext::_('AM');
					} 		
					$category = $hr."\\n".$hour[0].".".$date[1].".";
					break;
				case 'day':
					$category = $date[2].".".(int)$date[1].".";
					break;
				case 'week':
					$category = $date[1]."/".substr($date[0], 2);
					break;
				case 'month':
					$category = $date[1]."/".substr($date[0], 2);
					break;
			}
    	} else {
    		switch ($this->selected_interval){
				case 'hour':
					$hour = substr($cat,11,2);
					$hour = ltrim($hour, '0');
					$hr = (int)$hour;
					if ($hr >= 12) {
						if ($hr != 12) {
							$hr -= 12;
						}
						$hr .= " ".Jtext::_('PM');
					} else {
						if ($hr == 0) {
							$hr = 12;
						}
						$hr .= " ".Jtext::_('AM');
					} 		
					$category = $hr;
					//$category = substr($cat,11,2);
					break;
				case 'day':
					$category = ltrim(substr($cat,8,10), '0');
					break;
				case 'week':
					$category = ltrim(substr($cat,5,2), '0');
					break;
				case 'month':
					$category = ltrim(substr($cat,5,2), '0');
					break;
			}
    	}
		return $category;		
    } 
    
    /**
     * return substring of specified length (in config) ending with "..."
     * 
     * @param string $label
     */
    protected function shortenLabel ($label) {
    	if (VMREPORTS_MAX_LABEL_LENGTH == '') return $label;
    	
    	$label_length = JString::strlen($label);
    	
    	if ($label_length > VMREPORTS_MAX_LABEL_LENGTH) {
    		$label = JString::substr ($label, 0, VMREPORTS_MAX_LABEL_LENGTH) . "...";
    	}
    	
    	return $label;
    }
}
