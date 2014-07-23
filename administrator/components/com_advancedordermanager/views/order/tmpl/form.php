<?php
defined('_JEXEC') or die('Restricted access'); 
$db =& JFactory::getDBO();
?>
<form name="search2" action="index.php" method="post" id="search2">
<div class="col100">
 <fieldset class="adminform">
  <legend><?php echo JText::_( 'search by coupon code' ); ?></legend>
        <table class="admintable">
        <tr>
            <td width="100" align="right" class="key">
                <label for="Search">
                    <?php echo JText::_( 'Coupon code' ); ?>:
                </label>
            </td>


<td colspan="3"><select name="coupon" id="coupon"><option value="">coupon code</option>
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


<input type="submit" value="Submit" name="searchcoupon"></td></tr></table>

<div class="clr"></div>
 
<input type="hidden" name="option" value="com_advancedordermanager" />
<input type="hidden" name="id" value="" />
<input type="hidden" name="view" value="" />
<input type="hidden" name="task" value="search" />
<input type="hidden" name="controller" value="order" />

</div></fieldset>
</form>