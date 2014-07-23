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
<?php
$id = $this->item->id;
$args["id"]=$id;
$args["p2dDonationAmount"] = "'+$('p2dDonationAmount').value+'";
$model = &$this->getModel('popup');

if ($this->item->taxcalc==1) {
	$tax = ($this->item->tax * $this->item->amount) / 100;
$pfunc = "
var donAmount = document.getElementById('p2dDonationAmount').value;
var tax = 100+".$this->item->tax.";
var amount = (Math.round(donAmount*10000/tax)/100).toString();
var taxAmount = (Math.round((donAmount - amount)*100)/100).toString();
parent.document.forms.p2dform.amount.value=amount;
parent.document.forms.p2dform.tax.value=taxAmount;
";
}
else { 
 	$tax = $this->item->tax;
$pfunc = "
var donAmount = document.getElementById('p2dDonationAmount').value;
var amount = (Math.round((donAmount-".$tax.")*100)/100).toString();
var taxAmount = (Math.round(".$tax."*100)/100).toString();
parent.document.forms.p2dform.amount.value=amount;
parent.document.forms.p2dform.tax.value=taxAmount;";
} 	
$model->getFieldCheck("p2dDonationAmount", "ajaxMinvalue", "donation", $args, "amount", $pfunc);
// layout comes here
$amount= $this->item->minamount;
?>


<h2><?php echo JText::_("Price");?></h2>

<div class="article-content">
<?php echo JText::_("Please enter the amount you want to offer");?><br>
<?php echo JText::_("The minimum amount is ").$amount." ".$this->item->currency;?><br/><br/>
<input type="input" id="p2dDonationAmount" value="<?php echo $this->item->amount;?>"/><?php echo $this->item->currency;?><br/><br/>
<div id="donationMsg"></div><br/>

</div>	
