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

require_once (JPATH_COMPONENT . DS . 'models' . DS . 'statistics' . DS . 'topList.php');

class TopUsersStatistic extends TopListStatistic
{	
	/**
	 * Sets parametters to right values
	 * 
	 * @param $model
	 */
	function __construct($model)
	{
		parent::__construct($model);
		$this->title = "TOP_USERS";
	}
	
	function setParameters()
	{			
		parent::setParameters();
		
		$this->color_schema = "topUsers";
	
		$this->data =& $this->model->getTopUsersData();
	}	
	
}