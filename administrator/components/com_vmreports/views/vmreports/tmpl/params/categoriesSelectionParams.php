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

<div  class='settings_legend'>
<span style='vertical-align: top;'>
		<?php echo Jtext::_('CATEGORIES_SELECT'); ?>
</span>
</div>
		<?php 
		if (!$this->selected_categories){
			echo "<div id='".$this->name."NoDataDiv' class='warning' style='visibility: $no_data_visibility'>";
			echo Jtext::_('NO_CATEGORIES_SELECTED');
			echo "</div>";
		}
		?>
		
		
		<?php $this->renderCategoriesMultipleSelect(); ?>

