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
$model->getFieldCheck("p2dDonationAmount", "ajaxDonation", "donation", $args, "amount");
// layout comes here
?>


<h2><?php echo JText::_("Donation");?></h2>

<div class="article-content">
<?php echo JText::_("Please enter the amount you want to donate");?><br>
<?php echo JText::_("The minimum amount is ").$this->item->minamount." ".$this->item->currency;?><br/><br/>
<input type="input" id="p2dDonationAmount" value="<?php echo $this->item->amount;?>"/><?php echo $this->item->currency;?><br/><br/>
<div id="donationMsg"></div><br/>

</div>	
