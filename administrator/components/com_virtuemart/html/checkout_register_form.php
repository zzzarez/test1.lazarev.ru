<?php 
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
*
* @version $Id: checkout_register_form.php 1612 2009-01-22 20:11:25Z thepisu $
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
mm_showMyFileName( __FILE__ );
global $mosConfig_allowUserRegistration, $mosConfig_useractivation;
require_once( CLASSPATH . "ps_userfield.php" );
require_once( CLASSPATH . "htmlTools.class.php" );

//$missing = vmGet( $_REQUEST, "missing", "" );
//
//if (!empty( $missing )) {
//	echo "<script type=\"text/javascript\">alert('".$VM_LANG->_('CONTACT_FORM_NC',false)."'); </script>\n";
//}
//------------------------fix
$missing = vmGet( $_REQUEST, "missing", "" );

?>

<script language="javascript" type="text/javascript">


    function trim()
    {
        return this.replace(/^\s+|\s+$/g, '');
    }
    function getUsername(){
        var form = document.adminForm;
        var unam = form.username.value;





        if( !$('username_ticker') )
            $('username_input').innerHTML	= $('username_input').innerHTML + "<div id=\"username_ticker\" style=\"margin-top:-20px; margin-left:225px;\"></div>";

        $('username_field').value	= unam;

        if( form.username.value.length < 3 ) {
            $('username_ticker').innerHTML = '<span style="background:#FFFFCC;border:1px solid #CC0000;color:red;font-weight:bold;padding:3px 3px 3px 3px;">Неверный логин</span>';
        } else {
            $('username_ticker').innerHTML	= "<img src=\"<?php echo JURI::base()."images/wait.gif";?>\">&nbsp;Checking";
            var url = 'index.php?option=com_virtuemart&tasked=chkuserinfo&format=raw&what=uname';
            url = url + '&uname=' + form.username.value;

            new Ajax(url, {
                method: 'get',
                onComplete: function(x){
                    document.adminForm.username.value=document.adminForm.username.value.trim();
                    if(x == 1) {
                        $('username_ticker').innerHTML = '<span style="background:#FFFFCC;color:red;">Логин занят, попробуйте ввести другой. <br>Например:'+unam+'123 <br>или '+unam+'1978</span>';
                    } else {
                        $('username_ticker').innerHTML = '<span style="background:#ffffff;border:1px solid #b7b7b7;color:green;font-weight:bold;padding:3px 3px 3px 3px;">OK</span>';
                    }
                }
            }).request();

        }
    }


    function getEmail(){

        var form = document.adminForm;
        var eadd = form.email.value;

        if( !$('email_ticker') )
            $('email_input').innerHTML	= $('email_input').innerHTML + "<div id=\"email_ticker\" style=\"margin-top:24px; margin-left:205px;\"></div>";

        $('email_field').value	= eadd;

        if( !(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,4})+$/.test(form.email.value))) {
            $('email_ticker').setHTML('<span style="background:#FFFFCC;border:1px solid #CC0000;color:red;font-weight:bold;padding:3px 3px 3px 3px;">Неправильный e-mail</span>');
        } else {
           // $('email_ticker').setHTML("<img src=\"<?php echo JURI::base()."images/wait.gif";?>\">&nbsp;Checking");
            var url = 'index.php?option=com_virtuemart&tasked=chkuserinfo&format=raw&what=email';
            url = url + '&email=' + form.email.value;

            new Ajax(url, {
                method: 'get',
                onComplete: function(x){
                    if(x == 1) {
                        $('email_ticker').setHTML('<span style="background:#FFFFCC;border:1px solid #CC0000;color:red;font-weight:bold;padding:3px 3px 3px 3px;">E-mail уже зарегистрирован</span>');
                    } else {
                       // $('email_ticker').setHTML('<span style="background:#ffffff;border:1px solid #b7b7b7;;color:green;font-weight:bold;padding:3px 3px 3px 3px;">OK</span>');
                    }
                }
            }).request();

        }

    }



</script>
<?php

if (!empty( $missing )) {
    echo "<script type=\"text/javascript\">alert('".$VM_LANG->_('CONTACT_FORM_NC',false)."'); </script>\n";
}
//----------------------fix

// If not using NO_REGISTRATION, redirect with a warning when Joomla doesn't allow user registration
if ($mosConfig_allowUserRegistration == "0" && VM_REGISTRATION_TYPE != 'NO_REGISTRATION' ) {
	$msg = $VM_LANG->_('USER_REGISTRATION_DISABLED');
	vmRedirect( $sess->url( 'index.php?page='.HOMEPAGE, true, false ), $msg );
	return;
}

if( vmIsJoomla( '1.5' ) ) {
	// Set the validation value
	$validate = JUtility::getToken();
} else {
	$validate =  function_exists( 'josspoofvalue' ) ? josSpoofValue(1) : vmSpoofValue(1);
}

$fields = ps_userfield::getUserFields('registration', false, '', false );
// Read-only fields on registration don't make sense.
foreach( $fields as $field ) $field->readonly = 0;
$skip_fields = array();

if ( $my->id > 0 || (VM_REGISTRATION_TYPE != 'NORMAL_REGISTRATION' && VM_REGISTRATION_TYPE != 'OPTIONAL_REGISTRATION' 
								&& ( $page == 'checkout.index' || $page == 'shop.registration' ) ) ) {
	// A listing of fields that are NOT shown
	$skip_fields = array( 'username', 'password', 'password2' );
	if( $my->id ) {
		$skip_fields[] = 'email';
	}
}

// This is the part that prints out ALL registration fields!
ps_userfield::listUserFields( $fields, $skip_fields );

echo '
<div align="center">';
    
	if( !$mosConfig_useractivation && @VM_SHOW_REMEMBER_ME_BOX && VM_REGISTRATION_TYPE == 'NORMAL_REGISTRATION' ) {
		echo '<input type="checkbox" name="remember" value="yes" id="remember_login2" checked="checked" />
		<label for="remember_login2">'. $VM_LANG->_('REMEMBER_ME') .'</label><br /><br />';
	}
	else {
		if( VM_REGISTRATION_TYPE == 'NO_REGISTRATION' ) {
			$rmbr = '';
		} else {
			$rmbr = 'yes';
		}
		echo '<input type="hidden" name="remember" value="'.$rmbr.'" />';
	}
	echo '
		<input type="submit" value="'. $VM_LANG->_('BUTTON_SEND_REG') . '" class="enter_button" style="width:150px;font-size:20px;" onclick="return( submitregistration());" />
	</div>
	<input type="hidden" name="Itemid" value="'. $sess->getShopItemid() .'" />
	<input type="hidden" name="gid" value="'. $my->gid .'" />
	<input type="hidden" name="id" value="'. $my->id .'" />
	<input type="hidden" name="user_id" value="'. $my->id .'" />
	<input type="hidden" name="option" value="com_virtuemart" />
	<input type="hidden" name="' . $validate . '" value="1" />
	<input type="hidden" name="useractivation" value="'. $mosConfig_useractivation .'" />
	<input type="hidden" name="func" value="shopperadd" />
	<input type="hidden" name="page" value="checkout.index" />
	</form>';
?>
