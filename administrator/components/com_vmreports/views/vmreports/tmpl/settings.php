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

VmreportsHelper::importChartJS ();

?>
			<div class='left_panel' id='vmrports_menu_div'>
				<?php
				echo $this->renderMenu ();
				?>
			</div>

		<div class='center_panel' id='vmreports_body_div'>
			<div class='title settings_title' style=''><?php echo JText::_('CONFIG'); ?></div>
			
			
			 <form action="" method="post" name="adminForm" class="form-validate">
				<input type="hidden" name="task" value="" id="task" />
				
				<fieldset class='settings_fieldset'>
				<legend><div class='settings_fieldset_legend'><?php echo Jtext::_('CALCULATE_SETTINGS'); ?></div></legend>
				<table>
					
					<tr>
						<td class='settings_label'>
							<?php echo JText::_('CURRENCY')?>
						</td>
						<td>
							<input type="text" name="currency" value="<?php echo $this->settings->currency;?>" />
						</td>
					</tr>
					<tr>
						<td class='settings_label'>
							<?php echo JText::_('DECIMAL_PRECISION')?>
						</td>
						<td>
							<input type="text" name="decimal_precision" value="<?php echo $this->settings->decimal_precision;?>" />
						</td>
					</tr>
					<tr>
						<td class='settings_label'>
							<?php echo JText::_('COUNTED_STATUS')?>
						</td>
						<td>
							<?php echo $this->settings->renderCountedStatusSelect(); ?>
						</td>
					</tr>
				</table>
				</fieldset>
				
				<fieldset class='settings_fieldset_right'>
				<legend><div class='settings_fieldset_legend'><?php echo Jtext::_('CHART_SETTINGS'); ?></div></legend>
				<table>
					<tr>
						<td class='settings_label'>
							<?php echo JText::_('VISIBLE_PLOT')?>
						</td>
						<td>
							<input type="text" name="visible_plot" value="<?php echo $this->settings->visible_plot; ?>" class="required validate-numeric" />
						</td>
					</tr>
					<tr>
						<td class='settings_label'>
							<?php echo JText::_('CHART_WIDTH')?>
						</td>
						<td>
							<input type="text" name="chart_width" value="<?php echo $this->settings->chart_width;?>" />
						</td>
					</tr>
					<tr>
						<td class='settings_label'>
							<?php echo JText::_('CHART_HEIGHT')?>
						</td>
						<td>
							<input type="text" name="chart_height" value="<?php echo $this->settings->chart_height;?>" />
						</td>
					</tr>
					<tr>
						<td class='settings_label'>
							<?php echo JText::_('MAX_LABEL_LENGTH')?>
						</td>
						<td>
							<input type="text" name="max_label_length" value="<?php echo $this->settings->max_label_length;?>" />
						</td>
					</tr>
				</table>
				</fieldset>
			</form>
		</div>
		
		<script type="text/javascript">
			var con_pag = document.getElementById('vmreports_body_div');
			var lef_col = document.getElementById('vmrports_menu_div');
			var menu_height = lef_col.offsetHeight;
			var body_height = con_pag.offsetHeight;
			if (menu_height > body_height) {
				con_pag.style.height = menu_height + 'px';
			} 
		</script>	
		
