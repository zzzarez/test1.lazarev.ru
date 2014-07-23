<?php
defined('_JEXEC') or die('Restricted access'); 
$db =& JFactory::getDBO();
?>
<form name="search2" action="index.php?option=com_advancedordermanager" method="post" id="search2">
<div class="col100">
 <fieldset class="adminform">
  <legend><?php echo JText::_( 'Search By Coupon Code' ); ?></legend>
        <table class="admintable">
        <tr>
            <td width="100" align="right" class="key">
                <label for="Search">
                    <?php echo JText::_( 'Coupon Code' ); ?>:
                </label>
            </td>


<td colspan="3"><select name="coupon" id="coupon"><option value="">Coupon Code</option>
<?php
$query="select DISTINCT(coupon_code) from jos_vm_orders where coupon_code!=''";
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

<input type="hidden" name="filter_order" value="ASC" />
<input type="submit" value="Submit" name="searchcoupon"></td></tr></table>

<div class="clr"></div>
 
<input type="hidden" name="option" value="com_advancedordermanager" />
<input type="hidden" name="id" value="" />
<input type="hidden" name="view" value="" />
<input type="hidden" name="task" value="search" />
<input type="hidden" name="controller" value="order" />
<input type="hidden" name="filter_order" value="" />
<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</div></fieldset>
</form>
	
	<!--This is the Form  to search by date-->


	<form name="formdate" action="index.php" method="post" id="formdate">
	<div class="col100">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'Search By Date' ); ?></legend>
        <table class="admintable">
		<tr class="key"> 
		<td>  
		<label for="dfrom"><?php echo JText::_( 'Date From' ); ?>:</label>
	<input type="text" name="dstart"  id="birthday" value=""  class="DatePicker"></input>
	
	<label for="dupto"><?php echo JText::_( 'Date Upto' ); ?>:</label>
	<input type="text" name="edate" value="" id="birthday"  class="DatePicker">

	
	<input type="submit" value="submit" name="Submit"></td></tr></table>
        
	<div class="clr"></div>
 <input type="hidden" name="filter_order" value="" />
<input type="hidden" name="filter_order_Dir" value="" />
<input type="hidden" name="option" value="com_advancedordermanager" />
<input type="hidden" name="option" value="com_advancedordermanager" />
<input type="hidden" name="id" value="" />
<input type="hidden" name="view" value="" />
<input type="hidden" name="task" value="searchbydate" />
<input type="hidden" name="controller" value="order" />
	<?php echo JHTML::_( 'form.token' ); ?>
</div></fieldset>
</form>

<!--This is the Form  to search by coupon and date -->


	<form name="adminform" action="index.php" method="post" id="adminform">
	<div class="col100">
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'Search by Coupon and Date' ); ?></legend>
        <table class="admintable">
		<tr>
            <td width="100" align="right" class="key">
                <label for="Search">
                    <?php echo JText::_( 'Coupon Code' ); ?>:
                </label>
            </td>


<td colspan="3"><select name="coupon" id="coupon"><option value="">Coupon Code</option>
<?php
$query="select DISTINCT(coupon_code) from jos_vm_orders where coupon_code!=''";
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
		<tr class="key"> 
		<td>  
		<label for="dfrom"><?php echo JText::_( 'Date From' ); ?>:</label>
	<input type="text" name="dstart"  id="datepicker" value=""  class="DatePicker"></input>
	
	<label for="dupto"><?php echo JText::_( 'Date Upto' ); ?>:</label>
	<input type="text" name="edate" value="" id="datepicker"  class="DatePicker">

	
	<input type="submit" value="submit" name="Submit"></td></tr></table>
        
	<div class="clr"></div>
 
<input type="hidden" name="option" value="com_advancedordermanager" />
<input type="hidden" name="id" value="" />
<input type="hidden" name="view" value="" />
<input type="hidden" name="task" value="searchbycoupondate" />
<input type="hidden" name="controller" value="order" />
<input type="hidden" name="filter_order" value="" />
<input type="hidden" name="filter_order_Dir" value="" />
	<?php echo JHTML::_( 'form.token' ); ?>
</div></fieldset>
</form>







