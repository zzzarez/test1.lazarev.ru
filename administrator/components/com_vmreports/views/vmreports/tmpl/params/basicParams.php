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
<div class='settings_legend'>	
		<?php
		echo Jtext::_ ( 'DATES' );
		?>
		</div>

 	<span style='line-height: 250%;' >
 		<?php
		echo Jtext::_ ( 'START_ON' );
		?>
		&nbsp;&nbsp;
	</span>

		<?php
		echo JHTML::_ ( 'calendar', $this->start_date, 'start_date', 'start_date', '%Y-%m-%d', array ('class' => 'inputbox', 'size' => '10', 'maxlength' => '19' ) );
		?>
		<input type="hidden" name='start_date_hid' id='start_date_hid' />
		&nbsp;&nbsp;
	<span style='line-height: 250%;' >
		<?php
		echo Jtext::_ ( 'END_AT' );
		?>
		&nbsp;&nbsp;
	</span>
		<?php
		echo JHTML::_ ( 'calendar', $this->end_date, 'end_date', 'end_date', '%Y-%m-%d', array ('class' => 'inputbox', 'size' => '10', 'maxlength' => '19' ) );
		?>
		<input type="hidden" name='end_date_hid' id='end_date_hid' />

		

		



