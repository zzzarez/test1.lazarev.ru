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
<fieldset><legend><?php echo JText::_("Quick Item access");?></legend>
	<table class="adminlist" width="100%" >
	<thead>
		<tr>
			<th>
				<?php echo JText::_( 'Item' ); ?>
			</th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<td>
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = $i = 0;
	foreach ($this->items as $row)
	{
	$link 		= JRoute::_( 'index2.php?option=com_p2dxt&task=edit&cid[]='. $row->id );
	 
	?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<a href="<?php echo $link  ?>"><?php echo $row->itemname; ?></a>
			</td>
		</tr>
		<?php
			$k = 1 - $k;
			$i++;
		}
		?>
	</tbody>
	</table>
</fieldset>

