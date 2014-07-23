<?php defined('_JEXEC') or die('Restricted access');
// Getting the Database information
$db =& JFactory::getDBO();
$doc = & JFactory::getDocument();
 JHTMLBehavior::formvalidation(); 
  JHTML::_('behavior.calendar');
  $path='administrator/components/com_advancedordermanager/';
 
?>

<script language="javascript">
function myValidate(f) {
   if (document.formvalidator.isValid(f)) {
      f.check.value='<?php echo JUtility::getToken(); ?>'; //send token
      return true; 
   }
   else {
      var msg = '';
 
      
      if($('email').hasClass('invalid')){msg += 'Invalid E-Mail Address';}
 
      alert(msg);
	  
   }
   return false;
}
</script>

<script type="text/javascript">
 
$.noConflict();

jQuery().ready(function() {
	jQuery("#course").autocomplete("<?php echo JURI::root().'respons.php' ;?>", {
		width: 200,
		matchContains: true,
		//mustMatch: true,
		minChars: 2,
		//multiple: true,
		//highlight: true,
		//multipleSeparator: ",",
		selectFirst: false
	});
});
</script>
<style>

body    {
       
	   font-family: arial;
    font-size: 13px;
	   }

</style>
<?php
   // $item1 = 'index.php?option=com_advancedordermanager&controller=report';
   // $items2 = 'index.php?option=com_advancedordermanager&controller=report&task=combochart';
 // JSubMenuHelper::addEntry(JText::_('Search & Report'), $item1 );
 
 
?>

<fieldset class="adminform">
<legend>Order Search</legend>
 <form  action="index.php" id="adminform" method="post" name="adminform" class="form-validate" autocomplete="off" onSubmit="return myValidate(this);" >
	<div align="right" style="width:90%;"><font size="2" face="verdana"  color="#0B55C4">Search by Saved Results:&nbsp;</font><?php echo $this->lists2 ?>&nbsp;&nbsp;<a href="index.php?option=com_advancedordermanager&controller=advancedordermanager&task=del"> <?php echo JText::_( 'Manage Saved result(s)' ); ?></a></div>
	<div class="col100">
	
	
	<div class="BodyContainer" >
	<table class="admintable" >
	  <tr>
		<td class="Gap" colspan="2">
		<font size="2" face="verdana"  color="#0B55C4">Select Item or Enter Data Into One or More Fields to Start Search.</font>
			</td></tr>
		
	
		<tr>
			<td>

			  <table cellspacing="1">
				<tr>
				 <th class="Heading2" colspan=2><font size="2" face="verdana"  color="#0B55C4">Search by Order Detail:</font></th>
				</tr>
				<!-- order id -->
				<tr><td class="editlinktip hasTip" title="<?php echo JText::_( 'Enter Order Number' );?>">Order Number:</td>
				<td><input type="text" name="orderid" value="" id="orderid"></td></tr>
				
				<!-- Product SKU -->
		<?php
		if($this->productskutype ==0)
		{
		?><tr><td class="editlinktip hasTip" title="<?php echo JText::_('Enter Product SKU');?>">Product SKU:</td>
				<td><input type="text" name="prdsku" value="" id="prdsku"></td>
				<?php } else
				{?>
				
			<tr><td class="editlinktip hasTip" title="<?php echo JText::_('Select Product SKU');?>">Product SKU:</td>
				
				
			<td><select name="prdsku" value="" id="prdsku" style="width:100px;">
			<option value="">Product SKU</option>
			<option value="">All</option>
			
		<?php
			$query='select DISTINCT(product_sku) from #__vm_product';
						$db->setQuery($query);
						$rows=& $db->loadAssocList();
						foreach($rows as $value)
						{ ?>
		<option value="<?php echo $value['product_sku']; ?>"><?php echo $value['product_sku']; ?></option>
						<?php }?>
				
			</select>
			</td>
			<?php } ?>
			</tr>	
				<!-- Product name -->
				
				<tr>
				
				<?php
				if($this->producttype==0)
				{
				
				?>
				<td class="editlinktip hasTip" title="<?php echo JText::_( 'Enter product Name' );?>" >
						Product Name: </td>
				<td><input type="text" name="prd_name" id="prd_name" value="" /></td>
				<?php } else
					{	?>
						<td class="editlinktip hasTip" title="<?php echo JText::_( 'Select Product from list' );?>" >
						Product Name: </td>
					
					<td><select name="prd_name" value="" id="prd_name" style="width:100px;"><option value="">Product</option><option value="">All</option>
						<?php
						$query='select DISTINCT(product_name) from #__vm_product';
						$db->setQuery($query);
						$rows=& $db->loadAssocList();
						foreach($rows as $value)
						{?>
						<option value="<?php echo $value['product_name']; ?>"><?php echo $value['product_name']; ?></option>
						<?php }?>
						</select>
					</td><?php }?>
				</tr>
				<tr>
			<td  class="editlinktip hasTip" title="<?php echo JText::_( 'Select Order Status from list' );?>">
						Order Status:
					</td>

					<td>
						<select id="orderStatus" name="orderStatus" class="Field250" style="width:100px;">
						<option value="">Order Status</option>	<option value="">All</option>
						<?php
						$query='select * from #__vm_order_status';
						$db->setQuery($query);
						$rows=&$db->loadAssocList();
						foreach($rows as $value)
						{
						?>
						<option value="<?php echo $value['order_status_code'];?>" ><?php echo $value['order_status_name']; ?></option>
						<?php
						}
						?>
						</select>
					</td>
				</tr>
				<tr>
					<td  class="editlinktip hasTip" title="<?php echo JText::_( 'Select payment Method From List');?>">
						Payment Method:
					</td>
					<td>
						<select id="paymentMethod" name="paymentMethod" style="width:110px;" >

							<option value="">Payment Method</option><option value="">All</option>
							<?php
							if($this->paymentmethodtype==0)
							{
								$query="select * from #__vm_payment_method where payment_enabled='y'";
								}
							else 
							{
							$query='select * from #__vm_payment_method';
							}
						$db->setQuery($query);
						$rows=&$db->loadAssocList();
						foreach($rows as $value)
						{
						?>
				<option value="<?php echo $value['payment_method_id'];?> "><?php echo $value['payment_method_name'];?></option>
							<?php
							}
							?>
						</select>
					</td>
				</tr>
			
				<tr>
					<td  class="editlinktip hasTip" title="<?php echo JText::_( 'Select Discount Coupon Code from list' );?>">
						Coupon Code:
					</td>
					<td>
					<select name="coupon" id="coupon" style="width:100px;"><option value="">Coupon Code</option>
					<option value="">All</option>
	<?php
			$query="select DISTINCT(coupon_code) from #__vm_orders where coupon_code!=''";
			$db->setQuery($query);
			$rL=&$db->loadAssocList();
			foreach($rL as $rows) 
	{
		?>
			<option value="<?php echo $rows['coupon_code']; ?>"><?php echo $rows['coupon_code']; ?></option>
			<?php
				}
				?>


</select>
						</td>
				</tr>
				<tr><td colspan="3">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="SubmitButton1" value="Search" class="FormButton"></td></tr>
			 </table>
			</td>
		</tr>
		
			
		<tr>
			<td>
			  <table >
				<tr>
				<th colspan="2"><font size="2" face="verdana"  color="#0B55C4">Search by Date Range:</font></th>
				</tr>

				
				<tr>
				<td  class="editlinktip hasTip" title="<?php echo JText::_( 'Select starting date' );?>"><?php echo JText::_( 'Date From' ); ?>:</td>
				<td >
				<?php echo JHTML::calendar('', 'dstart', 'dstart','%Y-%m-%d');?></td><td>
				
</td>
	</tr><tr>
	<td  class="editlinktip hasTip" title="<?php echo JText::_( 'Select ending date' );?>"><?php echo JText::_( 'Date Upto' ); ?>:</td>
<td>

<?php echo JHTML::calendar('', 'edate', 'edate', '%Y-%m-%d');
  ?>
</td></tr>
<tr><td colspan="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Delete" onClick="document.adminform.dstart.value='';document.adminform.edate.value='';">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="SubmitButton3" value="Search" class="FormButton"></td></tr>

				
				
			 </table>
			</td>
		</tr>

		<tr>
			<td>
			 <table class="admintable">
				
				<tr>
					<tr><th colspan="2"><font size="2" face="verdana"  color="#0B55C4">Search by Customer Detail:</font></th></tr>
					<tr><td class="editlinktip hasTip" title="<?php echo JText::_( 'Enter email address ' );?>">Email Address:</td>
			<td><input type="text" id="email" name="email" class="validate-email" /></td></tr>
			
			
			<tr><td class="editlinktip hasTip" title="<?php echo JText::_( 'Enter User Name ' );?>">Username:</td>
			<td><input type="text" id="course" name="username" class="Field250" /></td></tr>
						
			
			
			<tr><td class="editlinktip hasTip" title="<?php echo JText::_( 'Enter First Name ' );?>">First Name:</td><td>
			
			<input type="text" id="name" name="name" class="Field250" /></td></tr>
			<tr><td class="editlinktip hasTip" title="<?php echo JText::_( 'Enter Last Name ' );?>">Last Name:</td><td>
			<input type="text" id="lastname" name="lastname" class="Field250" /></td></tr>
			
	</table>
			</td>
		</tr>
</tr>
	</table>
			  <table class="admintable">
				
				<tr>
					
					<td colspan="2"> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; <input type="reset" name="CancelButton" value="Reset" class="FormButton">&nbsp; &nbsp; <input type="submit" name="SubmitButton1" value="Search" class="FormButton"> 
			
					
					</td>
				</tr>

				
			 </table>
			</td>
		

	
	 
 
<input type="hidden" name="option" value="com_advancedordermanager" />
<input type="hidden" name="id" value="" />
<input type="hidden" name="view" value="" />
<input type="hidden" name="task" value="Asearch" />
<input type="hidden" name="controller" value="order" />
<input type="hidden" name="filter_order" value="" />
<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</div>
</form>
</fieldset>




