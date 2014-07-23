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
		echo Jtext::_ ( 'SHOW' );
		?>
		</div>


		<?php echo Jtext::_('SALES');?> <input type="checkbox" name="check_count" id="check_count" class='radio' onChange="checkChanged('count')" <?php echo $this->check_count; ?>  />
		<?php echo Jtext::_('REVENUE');?> <input type="checkbox" name="check_revenue" id="check_revenue" class='radio' onChange="checkChanged('revenue')" <?php echo $this->check_revenue ?> />
		
		<?php echo Jtext::_('AVERAGE_COUNT');?> <input type="checkbox" name="check_avg_count" id="check_avg_count" class='radio' <?php echo $this->check_avg_count ?> />
		<?php echo Jtext::_('AVERAGE_REVENUE');?> <input type="checkbox" name="check_avg_revenue" id="check_avg_revenue" class='radio' <?php echo $this->check_avg_revenue ?> />
