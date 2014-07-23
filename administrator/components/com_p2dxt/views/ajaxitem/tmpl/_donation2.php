<?php 
/**
* P2DXT for Joomla!
* @Copyright ((c) 2008 - 2010 JoomlaXT
* @ All rights reserved
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @ http://www.joomlaxt.com
* @version 1.00.14
**/
defined('_JEXEC') or die('Direct Access to this location is not allowed.');
?>
	<table>
		<tr>
			<td width="300" class="key">
				<label for="damount">
					<?php echo JText::_( 'Default Amount' ); ?>:
				</label>
			</td>
			<td >
				<input class="inputbox" type="text" name="row[amount]" value="<?php echo $this->row->amount;?>" size="10">
			</td>
			<td width="300" class="key">
				<label for="minamount">
					<?php echo JText::_( 'Minimum Amount' ); ?>:
				</label>
			</td>
			<td >
				<input class="inputbox" type="text" name="row[minamount]" value="<?php echo $this->row->minamount;?>" size="10">
			</td>
		</tr>
		<tr>
			<td width="300" class="key">
				<label for="tax">
					<?php echo JText::_( 'Tax' ); ?>:
				</label>
			</td>
			<td>
				<?php $cc= array(JText::_("Amount")=>0,JText::_("Percentage")=>1);
				foreach ($cc as $v=>$k) {
					$items[] = JHTML::_('select.option', $k, $v);
				}
				echo JHTML::_('select.genericlist',   $items, 'row[taxcalc]', 'class="inputbox" size="1";"', 'value', 'text', $this->row->taxcalc );				
				?>
			</td>
			<td>
				<input class="inputbox" type="text" name="row[tax]" value="<?php echo $this->row->tax;?>" size="10">
			</td>
			<td></td>
</tr>
</table>