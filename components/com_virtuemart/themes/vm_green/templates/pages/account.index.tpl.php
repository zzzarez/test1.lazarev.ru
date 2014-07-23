<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: account.index.tpl.php 1339 2008-04-01 17:43:24Z soeren_nb $
* @package VirtueMart
* @subpackage html
* @copyright Copyright (C) 2004-2008 soeren - All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/

if ($perm->is_registered_customer($auth['user_id'])) {

    if($_GET['typel']!= 'orders') {
?>
<div class="maycab_hdr" style="text-align: left;">Личный кабинет</div>
  <div class="maycab_info">
    Рады видеть Вас, <b><?php echo (isset($_POST["first_name"]) || isset($_POST["last_name"])) ? $_POST["first_name"] . " " . $_POST["last_name"] : $auth["first_name"] . " " . $auth["last_name"];?></b><br/>
  Ваш зарегистрированный e-mail: <b><?php echo $auth["username"]; ?></b>
  </div>
  <br />
<div class="maycab_user" onClick="window.location='<?php echo $sess->purl(SECUREURL . "index.php?page=account.billing&typel=mycab");?>'">
    <div  class="maycab_user_link"><a href='<?php echo $sess->purl(SECUREURL . "/index.php?page=account.billing&typel=mycab")."'>"; echo $VM_LANG->_('PHPSHOP_ACC_ACCOUNT_INFO'); ?></a></div>
    <div  class="maycab_user_info">Здесь вы можете изменить свои личные данные и пароль</div>
</div>
<div class="maycab_orders" onClick="window.location='/index.php?page=account.index&option=com_virtuemart&Itemid=1&typel=orders'">
    <div class="maycab_user_link"><a href="/index.php?page=account.index&option=com_virtuemart&Itemid=1&typel=orders">Мои Заказы</a></div>
    <div class="maycab_user_info">Здесь вы можете просмотреть свои оплаченные заказы</div>
</div>

    <?php
    }
    else{
?>

  <table border="0" cellspacing="0" cellpadding="10" width="545" style="margin-bottom: -14px;">
  
          
<!--    <tr>-->
<!--      <td>-->
<!--      <strong><a href="--><?php //$sess->purl(SECUREURL . "index.php?page=account.billing") ?><!--">-->
<!--          --><?php //
//          echo "<img src=\"".VM_THEMEURL."images/identity.png\" align=\"middle\" height=\"48\" width=\"48\" border=\"0\" alt=\"".$VM_LANG->_('PHPSHOP_ACCOUNT_TITLE')."\" />&nbsp;";
//          echo $VM_LANG->_('PHPSHOP_ACC_ACCOUNT_INFO') ?><!--</a></strong>-->
<!--          <br />--><?php //echo $VM_LANG->_('PHPSHOP_ACC_UPD_BILL') ?>
<!--      </td>-->
<!--    </tr>-->
    <?php
    if(NO_SHIPTO != '1') {

	?>
		<tr><td>&nbsp;</td></tr>

		<tr>
		  <td><hr />
		  <strong><a href="<?php $sess->purl(SECUREURL . "index.php?page=account.shipping") ?>"><?php
                 // echo "<img src=\"".VM_THEMEURL."images/web.png\" align=\"middle\" border=\"0\" height=\"32\" width=\"32\" alt=\"".$VM_LANG->_('PHPSHOP_ACC_SHIP_INFO')."\" />&nbsp;&nbsp;&nbsp;";
                  //echo $VM_LANG->_('PHPSHOP_ACC_SHIP_INFO') ?></a></strong>
                        <br />
                        <?php //echo $VM_LANG->_('PHPSHOP_ACC_UPD_SHIP') ?>
                  </td>
                </tr>
                <?php
	}

	?>

    
    <tr>
      <td>

      	<strong style="font-size:24px;color: #808080;" >
              Список Ваших заказов<br><br>
              <?php
	      //echo "<img src=\"".VM_THEMEURL."images/package.png\" align=\"middle\" height=\"32\" width=\"32\" border=\"0\" alt=\"".$VM_LANG->_('PHPSHOP_ACC_ORDER_INFO')."\" />&nbsp;&nbsp;&nbsp;";
	      //echo $VM_LANG->_('PHPSHOP_ACC_ORDER_INFO') ?>
	    </strong>
        <?php $ps_order->list_order(isset($_GET['show_all']) ? "A" : "C", "1" ); ?>
      </td>
    </tr>
</table>
<!-- Body ends here -->
<?php
    }
}
else { 
	// You're not allowed... you need to login.
    echo $VM_LANG->_('DO_LOGIN') .'<br/><br/><br/>';
    include(PAGEPATH.'checkout.login_form.php');
} ?>