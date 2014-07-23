<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); 
/**
*
* @version $Id: account.billing.tpl.php 1267 2008-02-18 15:26:52Z gregdev $
* @package VirtueMart
* @subpackage html
* @copyright Copyright (C) 2004-2007 Soeren Eberhardt. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See /administrator/components/com_virtuemart/COPYRIGHT.php for copyright notices and details.
*
* http://virtuemart.net
*/

?>
<!--<div class="pathway">--><?php //echo $vmPathway; ?><!--</div>-->
<!--<div style="float:left;width:90%;text-align:right;"> -->
<!--    <span>-->
<!--    	<a href="#" onclick="if( submitregistration() ) { document.adminForm.submit(); return false;}">-->
<!--    		<img border="0" src="administrator/images/save_f2.png" name="submit" alt="--><?php //echo $VM_LANG->_('CMN_SAVE') ?><!--" />-->
<!--    	</a>-->
<!--    </span>-->
<!--    <span style="margin-left:10px;">-->
<!--    	<a href="--><?php //$sess->purl( SECUREURL."index.php?page=$next_page") ?><!--">-->
<!--    		<img src="administrator/images/back_f2.png" alt="--><?php //echo $VM_LANG->_('BACK') ?><!--" border="0" />-->
<!--    	</a>-->
<!--    </span>-->
<!--</div>-->
<div style="color: #565656;">
    <h2>Личные данные</h2>
    <b>Здесь вы можете изменить свои личные данные и пароль</b>
    <br><br>
    <strong style="font-size: 20px;color: red;"> * </strong> обязательно для заполнения
</div>
<script type="text/javascript">
  var button_changepass = 0;
</script>
<?php
foreach ($fields as $field)
{
    if ($field->name == 'password' || $field->name == 'password2')
        $field->required = 0;
}
ps_userfield::listUserFields( $fields, array(), $db );
?>
    <button class="enter_button" style="font-size: 14px;" onclick="javascript:document.getElementById('passwords').style.display = 'block'; this.style.display = 'none'; button_changepass = 1; return false;">Изменить свой пароль</button>

<div style="margin-top: 20px;text-align: center;">

<div align="center" style="display: inline-block;">
	<input type="submit" value="Сохранить изменения" class="enter_button" onclick="return( submitregistration());" />
</div>
<div align="center" style="display: inline-block;">
  <input type="submit" class="enter_button" value="Отмена" onclick="location.replace('/index.php?page=account.index&option=com_virtuemart&Itemid=1'); return false;">
</div>

</div>
<input type="hidden" name="option" value="<?php echo $option ?>" />
  <input type="hidden" name="page" value="<?php echo $next_page; ?>" />
  <input type="hidden" name="Itemid" value="<?php echo $Itemid; ?>" />
  <input type="hidden" name="func" value="shopperupdate" />
  <input type="hidden" name="user_info_id" value="<?php $db->p("user_info_id"); ?>" />
  <input type="hidden" name="id" value="<?php echo $auth["user_id"] ?>" />
  <input type="hidden" name="user_id" value="<?php echo $auth["user_id"] ?>" />
  <input type="hidden" name="address_type" value="BT" />
</form>
