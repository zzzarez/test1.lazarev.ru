<?php defined('_JEXEC') or die('Restricted access');

JHTML::_('behavior.modal', 'a.modal');
$doc = &JFactory::getDocument();
?>

<style type="text/css">
tr.sortable th img {
        margin-left: 10px;
}
</style>
<?php
			if($this->coupon)
			{
			echo '<font size="2" face="verdana">Coupon Code:'.$this->coupon.'</font><br>';
			}
			if($this->dstart)
			{
			echo '<font size="2" face="verdana">Date From:'.$this->dstart.'</font><br>';
			}
			if($this->edate)
			{
				echo '<font size="2" face="verdana">Date To:'.$this->edate.'</font><br>';
			}
			
			if($this->orderStatus)
			{
			echo '<font size="2" face="verdana">Order Status:'. $this->orderStatus.'</font><br>';
			}
			if($this->paymentMethod)
			{
			echo '<font size="2" face="verdana">Payment Method:'. $this->paymentMethod.'</font><br>';
			}
			if($this->product)
			{
			echo '<font size="2" face="verdana">Product:'. $this->product.'</font><br>';
			}
			
			if($this->orderid)
			{
			echo '<font size="2" face="verdana">Order Number:'.$this->orderid.'</font><br>';
			}
			if($this->prdsku)
			{
			echo '<font size="2" face="verdana">Product SKU:'.$this->prdsku.'</font><br>';
			}
			if($this->name)
			{
			echo '<font size="2" face="verdana">First Name:'.$this->name.'</font><br>';
			}
			if($this->lastname)
			{
			echo '<font size="2" face="verdana">Last Name:'.$this->lastname.'</font><br>';
			}
			if($this->username)
			{
			echo '<font size="2" face="verdana">User Name:'.$this->username.'</font><br>';
			}
			if($this->email)
			{
			echo '<font size="2" face="verdana">Email:'.$this->email.'</font><br>';
			}
			
			
		echo '<font face="verdana" color="#0B55C4">Click on an Order Number to view Order Details</font>';
			
		?>
		<br><br>
   		

		<form action="index.php" method="post" name="adminForm">

		<input type="hidden" name="coupon" id="coupon" value="<?php echo $this->coupon; ?>"/>

			
			<input type="hidden" name="dstart" id="dstart" value="<?php echo $this->dstart; ?>" />

                    
					<input type="hidden" name="edate" id="edate" value="<?php echo $this->edate; ?>" />
          
	

	<input type="hidden" name="orderStatus" id="orderStatus" value="<?php echo $this->orderStatus; ?>" />
		
		<input type="hidden" name="paymentMethod" id="paymentMethod" value="<?php echo $this->paymentMethod; ?>" />
			
			<input type="hidden" name="prd_name" value="<?php echo $this->product; ?>" >
							
			<input type="hidden" name="orderid" value="<?php echo $this->orderid; ?>" id="orderid">
		
		<input type="hidden" name="prdsku" value="<?php echo $this->prdsku; ?>" id="prdsku">
		<input type="hidden" id="name" name="name" value="<?php echo $this->name; ?>" />
		<input type="hidden" id="email" name="email" class="Field250"  value="<?php echo $this->email; ?>"/>
			
			<input type="hidden" id="lastname" name="lastname" value="<?php echo $this->lastname; ?>" />
			<input type="hidden" id="username" name="username" value="<?php echo $this->username; ?>" />
		<input type="hidden" id="savequery" name="savequery" value="<?php echo $this->savequery; ?>" />
		
	<input type="hidden" id="searchq" name="searchq" value="<?php echo $this->savesearch; ?>" />
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
          <?php echo JHTML::_( 'grid.sort', 'Order Number', '#__vm_orders.order_id', $this->lists['order_Dir'], $this->lists['order']); ?></th>
            </th>
								<th width="5">
		<?php echo JHTML::_( 'grid.sort', 'Name', '#__vm_order_user_info.first_name', $this->lists['order_Dir'], $this->lists['order']); ?></th>
					</th>
					
					<th width="5">
	<?php echo JHTML::_( 'grid.sort', 'Order Date', '#__vm_orders.cdate', $this->lists['order_Dir'], $this->lists['order']); ?>
					</th>
					<th width="5" >
				  <?php echo JHTML::_( 'grid.sort', 'Order Coupon', '#__vm_orders.coupon_code', $this->lists['order_Dir'], $this->lists['order']); ?>
					</th>
					
					<th width="5">
						  <?php echo JHTML::_( 'grid.sort', 'Coupon Discount', '#__vm_orders.coupon_discount', $this->lists['order_Dir'], $this->lists['order']); ?>
						</th>
					<th width="7">
					  <?php echo JHTML::_( 'grid.sort', 'Order Subtotal', '#__vm_orders.order_subtotal', $this->lists['order_Dir'], $this->lists['order']); ?>
					</th>
					
					<th width="8">
		<?php echo JHTML::_( 'grid.sort', 'Order Total', '#__vm_orders.order_total', $this->lists['order_Dir'], $this->lists['order']); ?>
			</th>
        </tr>
		
		</thead>  
	<!-- column header end-->  
 
	 <tfoot>
				<tr>
					<td colspan="9">
					<!--  paging link-->
						<?php echo $this->pagenav->getListFooter(); ?>
					</td>
					
				</tr>
			</tfoot>    

    <?php
    $k = 0;
	$i=0;
	$coupon_discount = 0;
	$order_subtotal = 0;
	$order_total = 0;
	
    //loop for display records
	for ($i=0, $n=count($this->items); $i < $n; $i++)
			{
			
				$row = & $this->items[$i];
	
        $checked    = Jhtml::_( 'grid.id', $i, $row->order_id );
        $link = JRoute::_( 'index.php?page=order.order_print&limitstart=0&keyword=&order_id='.$row->order_id.'&option=com_virtuemart&tmpl=component');
 
        ?>
        <tr class="<?php echo "row$k"; ?>">
            <td>
                <?php echo $this->pagenav->getRowOffset($i); ?>
            </td>
				
            <td>
              <?php echo $checked; ?>
            </td>
            <td width="1%">
                <a href="<?php echo $link; ?>" rel="{handler: 'iframe', size: {x:window.getSize().size.x-100, y: window.getSize().size.y-100}}" class="modal"><?php echo $row->order_id; ?></a>
            </td>
			<td align="center">
              <?php echo $row->first_name.' '.$row->last_name;?>
            </td>
			<td align="center">
            <?php $cdate=date('M-d-Y h:i:s',$row->cdate); echo $cdate;?>
            </td>
			<td align="center">
                <?php echo $row->coupon_code; ?>
            </td >
			<td align="center">
                <?php echo $row->coupon_discount;  ?>
            </td>
			<td align="center">
                <?php echo $row->order_subtotal;   ?>
            </td>
			<td align="center">
                <?php echo $row->order_total;     ?>
            </td>
        </tr>
        <?php
     
	  $k = 1 - $k;
    }
    ?>   
<tr ><td width="8"></td><td width="8"></td><td width="8"></td><td width="8"></td><td width="8"></td><td width="8"></td><td style="width:8%">Total:&nbsp;&nbsp;&nbsp;<?php echo '$'.$this->cdiscount;  ?></td><td>Total:&nbsp;&nbsp;&nbsp;<?php  echo '$'.$this->subtot ; ?></td><td width="8" >Grand Total:&nbsp;&nbsp;&nbsp;<?php echo '$'.$this->tot ;?></td></tr>
 <input type="hidden" name="view" value="" />
<input type="hidden" name="option" value="com_advancedordermanager" />
<input type="hidden" name="task" value="Asearch" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="controller" value="order" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />

<?php echo JHTML::_( 'form.token' ); ?>
 	
	 </table>
	 </div>
</form>
