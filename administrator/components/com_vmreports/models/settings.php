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
 * Class with configuration attributed. This attributes are stored in config.php 
 * 
 * @author pedu
 *
 */
class Settings 
{
	/**
	 * number of visible plots in chart
	 * 
	 * @var int
	 */
	public $visible_plot;
	
	/**
	 * char codes of order status, which are counted to revenue. 
	 * Values are divided by ','
	 * 
	 * @var string
	 */
	public $counted_status;
	
	/**
	 * wide of chart in pixels
	 * 
	 * @var int
	 */
	public $chart_width;
	
	/**
	 * height of chart in pixels
	 * 
	 * @var int
	 */
	public $chart_height;
	
	/**
	 * currency code, displayed next to value
	 * 
	 * @var string
	 */
	public $currency;
	
	/**
	 * number of decimal positions of real numbers
	 * 
	 * @var int
	 */
	public $decimal_precision;
	
	/**
	 * Maximul length of label string
	 * 
	 * @var int
	 */
	public $max_label_length;
				
	/**
	 * load data from config file
	 */
	function LoadFromConfig(){
		$this->visible_plot = VMREPORTS_NUM_VISIBLE_PLOT;
		$this->counted_status = explode(',', VMREPORTS_COUNTED_STATUS);
		$this->chart_width = VMREPORTS_CHART_WIDTH;
		$this->chart_height = VMREPORTS_CHART_HEIGHT;
		$this->currency = VMREPORTS_CURRENCY;
		$this->decimal_precision = VMREPORTS_DECIMAL_PRECISION;
		$this->max_label_length = VMREPORTS_MAX_LABEL_LENGTH;
	}
	
	/**
	 * returns true, if all incoming data are in valid format 
	 */
	function isValid()
	{
		$return = true;
		if (!$this->visible_plot){
			JError::raiseWarning( 100, Jtext::_('VISIBLE_PLOT') . " " . Jtext::_('REQUIRED') );
			$return = false; 
		}
		if (!$this->chart_width){
			JError::raiseWarning( 100, Jtext::_('CHART_WIDTH') . " " . Jtext::_('REQUIRED') );
			$return = false; 
		}
		if (!$this->chart_height){
			JError::raiseWarning( 100, Jtext::_('CHART_HEIGHT') . " " . Jtext::_('REQUIRED') );
			$return = false; 
		}
		if (!$this->decimal_precision) {
			JError::raiseWarning( 100, Jtext::_('DECIMAL_PRECISION') . " " . Jtext::_('REQUIRED') );
			$return = false;
		}
		if (!is_array($this->counted_status)) {
			JError::raiseWarning( 100, Jtext::_('COUNTED_STATUS') . " " . Jtext::_('REQUIRED') );
			return false;
		}	
		if (!ctype_digit($this->chart_height)){
			JError::raiseWarning( 100, Jtext::_('CHART_HEIGHT') . ": " . Jtext::_('REQUIRED_TYPE_IS') . " " . JText::_('INTEGER') );
			$return = false; 
		}
		if (!ctype_digit($this->chart_width)){
			JError::raiseWarning( 100, Jtext::_('CHART_WIDTH') . ": " . Jtext::_('REQUIRED_TYPE_IS') . " " . JText::_('INTEGER') );
			$return = false; 
		}
		if (!ctype_digit($this->visible_plot)){
			JError::raiseWarning( 100, Jtext::_('VISIBLE_PLOT') . ": " . Jtext::_('REQUIRED_TYPE_IS') . " " . JText::_('INTEGER') );
			$return = false; 
		}
		if (!ctype_digit($this->decimal_precision)) {
			JError::raiseWarning( 100, Jtext::_('DECIMAL_PRECISION') . ": " . Jtext::_('REQUIRED_TYPE_IS') . " " . JText::_('INTEGER') );
			$return = false; 
		}
		if (!ctype_digit($this->max_label_length) && $this->max_label_length != '') {
			JError::raiseWarning( 100, Jtext::_('MAX_LABEL_LENGTH') . ": " . Jtext::_('REQUIRED_TYPE_IS') . " " . JText::_('INTEGER') );
			$return = false; 
		}
		
		return $return;
	}
	
	/**
	 * Create or rewrite the config.php
	 */
	function saveConfig()
	{
		if (is_array($this->counted_status)) {
			$counted_stat = implode (',', str_replace("\'","'", $this->counted_status));
		} else {
			$counted_stat = '';
		}
		
		$conf = "<?php \n"
				."define('VMREPORTS_COMPONENT_URL', JURI::root() . 'administrator/components/com_vmreports/');\n"
				."define('VMREPORTS_COMPONENT_ADR', JPATH_ROOT . DS. 'administrator/components/com_vmreports');\n"
				."define('VMREPORTS_COMPONENT_VIEWS_ADR', VMREPORTS_COMPONENT_ADR . DS . 'views/vmreports/');\n"
				."define('VMREPORTS_ASSETS_JS', VMREPORTS_COMPONENT_URL . 'assets/js/');\n"
				."define('VMREPORTS_CHARTS', VMREPORTS_COMPONENT_URL . 'charts/');\n"
				."define('VMREPORTS_CHARTS_ADR', VMREPORTS_COMPONENT_ADR . DS . 'charts/');\n"
				."define('VMREPORTS_CHART_NAME', 'chart.swf');\n"

				."define('VMREPORTS_VMREPORTS_DEFAULT_SELECTED_TIME_INTERVAL', 'day');\n"

				."define ('VMREPORTS_NUM_VISIBLE_PLOT', '$this->visible_plot');\n"

				."define ('VMREPORTS_SEC_IN_HOUR', '3600');\n"
				."define ('VMREPORTS_SEC_IN_DAY', '86400');\n"

				."define ('VMREPORTS_COUNTED_STATUS', \"".$counted_stat."\");\n"

				."define('VMREPORTS_CHART_WIDTH', '$this->chart_width');\n"
				."define('VMREPORTS_CHART_HEIGHT', '$this->chart_height');\n"
				."define('VMREPORTS_CURRENCY', '$this->currency');\n"
				."define('VMREPORTS_DECIMAL_PRECISION', '$this->decimal_precision');\n"
				."define('VMREPORTS_MAX_LABEL_LENGTH', '$this->max_label_length');\n";
				
		$file = fopen(JPATH_ROOT . DS. 'administrator/components/com_vmreports' . DS . "config.php", 'w') or die("can't open file");;
		fwrite($file, $conf);
		fclose($file);
	}
	
	/**
	 * rendets miltiselectox with all order states
	 */
	function renderCountedStatusSelect ()
	{
		$db =& JFactory::getDBO();
		
		$query = "SELECT order_status_code as code, order_status_name as name "
				."FROM #__vm_order_status ";
				
		$db->setQuery( $query );
		$status = $db->loadObjectList();
		
		foreach ($status as $stat){
			$put[] = JHTML::_('select.option', "'$stat->code'", $stat->name);
		}

		$order_by = JHTML::_('select.genericlist',  $put, 'counted_status[]', array('size' => count($put), 'multiple' => ''), 'value', 'text', $this->counted_status );
		
		return $order_by;
	}
}