<?php defined('_JEXEC') or die('Restricted access');
// Getting the Database information
$db =& JFactory::getDBO();

 ?>

<form action="index.php?option=com_advancedordermanager" method="post" name="adminForm">
<fieldset class="adminform">
  <legend><?php echo JText::_( 'Search By Coupon Code' ); ?></legend>
        <table class="admintable">
        <tr>
            <td width="100" align="right" class="key">
                <label for="Search">
                    <?php echo JText::_( 'Coupon Code' ); ?>:
                </label>
            </td>


	<td colspan="2">
	<input type="text" name="coupon" id="coupon" value="<?php echo $this->coupon; ?>" /></td></tr>
	</table>
	</fieldset>

 
<div>
    <table class="adminlist">
	<!-- column header starts-->
    <thead>
        <tr>
            <th width="1%">
                <?php echo JText::_( '#' ); ?>
            </th>
            <th width="2%">
              <input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $this->items ); ?>);" />
            </th>
					
            <th width="5%" >
               <?php echo JHTML::_( 'grid.sort', 'Orderid', 'jos_vm_orders.order_id', $this->lists['order_Dir'], $this->lists['order']); ?>
            </th>
		<th width="5">
	<?php echo JHTML::_( 'grid.sort', 'Customer Name', 'jos_vm_order_user_info.first_name', $this->lists['order_Dir'], $this->lists['order']); ?>
					</th>
					
					<th width="5">
		<?php echo JHTML::_( 'grid.sort', 'Date', 'jos_vm_orders.cdate', $this->lists['order_Dir'], $this->lists['order']); ?>
					</th>
					<th width="5" >
						<?php echo JText::_('Coupon Code'); ?>
					</th>
					
					<th width="5">
						<?php echo JText::_('Coupon Discount'); ?>
						</th>
					<th width="7">
						<?php echo JText::_('Order Subtotal'); ?>
					</th>
					
					<th width="8">
	<?php echo JHTML::_( 'grid.sort', 'Order Total', 'jos_vm_orders.order_total', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
        </tr>
		
		</thead>  
<!-- column header end-->        
   <tfoot>
				<tr>
					<td colspan="9">
					<!-- paging link-->
	<?php  echo $this->pagenav->getListFooter(); ?>
					</td>
				</tr>
			</tfoot>   

    <?php
    $k = 0;
	$i=0;
    //loop for display records
	for ($i=0, $n=count($this->items); $i < $n; $i++)
			{
			
				$row = & $this->items[$i];
	
        $checked    = Jhtml::_( 'grid.id', $i, $row->order_id );
        $link = JRoute::_( 'index.php?option=com_advancedordermanager&controller=order&cid[]='. $row->order_id );
 
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $this->pagenav->getRowOffset($i); ?> 
            </td>
				
            <td>
              <?php echo $checked; ?>
            </td>
            <td width="1%">
                <a href="<?php echo $link; ?>"><?php echo $row->order_id; ?></a>
            </td>
			<td align="center">
              <?php echo $row->first_name.' '.$row->last_name;?>
            </td>
			<td align="center">
            <?php $cdate=date('d-M-Y h:m:s',$row->cdate); echo $cdate;?>
            </td>
			<td align="center">
                <?php echo $row->coupon_code; ?>
            </td >
			<td align="center">
                <?php echo $row->coupon_discount; ?>
            </td>
			<td align="center">
                <?php echo $row->order_subtotal; ?>
            </td>
			<td align="center">
                <?php echo $row->order_total; ?>
            </td>
        </tr>
        <?php
     
	  $k = 1 - $k;
    }
    ?>
	
	 

	   

 <input type="hidden" name="view" value="" />
<input type="hidden" name="option" value="com_advancedordermanager" />
<input type="hidden" name="task" value="search" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="order" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />

	<?php echo JHTML::_( 'form.token' ); ?>
 	</div>
</form>
 </table>