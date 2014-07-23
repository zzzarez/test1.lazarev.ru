<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<div class="login_form radgrad">
<?php if($type == 'logout') : ?>
<form action="index.php" method="post" name="login" id="form-login">
<?php if ($params->get('greeting')) : ?>
	<div class="hello_logout">
	<?php if ($params->get('name')) : {
		echo JText::sprintf( 'HINAME', $user->get('name') );
	} else : {
		echo JText::sprintf( 'HINAME', $user->get('username') );
	} endif; ?>
	</div>
<?php endif; ?>
	<div align="center" class="login_button">
		<input type="submit" name="Submit" class="button" value="<?php echo JText::_( 'BUTTON_LOGOUT'); ?>" />
	</div>

	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="logout" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
</form>
<?php else : ?>
<?php if(JPluginHelper::isEnabled('authentication', 'openid')) :
		$lang->load( 'plg_authentication_openid', JPATH_ADMINISTRATOR );
		$langScript = 	'var JLanguage = {};'.
						' JLanguage.WHAT_IS_OPENID = \''.JText::_( 'WHAT_IS_OPENID' ).'\';'.
						' JLanguage.LOGIN_WITH_OPENID = \''.JText::_( 'LOGIN_WITH_OPENID' ).'\';'.
						' JLanguage.NORMAL_LOGIN = \''.JText::_( 'NORMAL_LOGIN' ).'\';'.
						' var modlogin = 1;';
		$document = &JFactory::getDocument();
		$document->addScriptDeclaration( $langScript );
		JHTML::_('script', 'openid.js');
endif; ?>
<form action="<?php echo JRoute::_( 'index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login" >
	<?php echo $params->get('pretext'); ?>
<!--	<fieldset class="input">-->
	<div class="form-login-username">
<!--		<label for="modlgn_username">--><?php //echo JText::_('Username') ?><!--</label><br />-->
		<input id="modlgn_username" type="text" name="username" class="inputbox_txt radgrad" alt="username" size="18" tabindex="1" value="<?php echo JText::_('Username') ?>" onclick="if(this.value=='<?php echo JText::_('Username') ?>'){this.value='';}" onblur="if(this.value==''){this.value='<?php echo JText::_('Username') ?>';}"/>
	</div>
	<div class="form-login-password">
		<input id="modlgn_passwd" type="password" name="passwd" class="inputbox_txt radgrad" size="18" alt="password" tabindex="1" value="<?php echo JText::_('Password') ?>" onclick="if(this.value=='<?php echo JText::_('Password') ?>'){this.value='';}" onblur="if(this.value==''){this.value='<?php echo JText::_('Password') ?>';}" />
	</div>


    <div class="login_line2">
	<?php if(JPluginHelper::isEnabled('system', 'remember')) : ?>
    <div class="form-login-remember option">
		    <div class="checkbox_label"><?php echo JText::_('Remember me') ?></div>
            <div class="check_box"><input id="modlgn_remember" type="checkbox" name="remember" class="inputbox" value="yes" alt="Remember Me" /></div>
    </div>
	<?php endif; ?>
    <div class="form-login-submit option">
	    <input type="submit" name="Submit" class="enter_button" value="<?php echo JText::_('LOGIN') ?>" />
    </div>
    </div>

<!--	</fieldset>-->
    <br>
    <?php
    $usersConfig = &JComponentHelper::getParams( 'com_users' );
    if ($usersConfig->get('allowUserRegistration')) : ?>
        <a href="<?php echo JRoute::_( 'index.php?option=com_user&view=register' ); ?>">
            <?php echo JText::_('REGISTER'); ?></a>
    <?php endif; ?>
    <a href="<?php echo JRoute::_( 'index.php?option=com_user&view=reset' ); ?>"><?php echo JText::_('FORGOT_YOUR_PASSWORD'); ?></a>
    <br><br>
<!--			<a href="--><?php //echo JRoute::_( 'index.php?option=com_user&view=remind' ); ?><!--">-->
<!--			--><?php //echo JText::_('FORGOT_YOUR_USERNAME'); ?><!--</a>-->
<!--            <br>-->

	<?php echo $params->get('posttext'); ?>

	<input type="hidden" name="option" value="com_user" />
	<input type="hidden" name="task" value="login" />
	<input type="hidden" name="return" value="<?php echo $return; ?>" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>
<?php endif; ?>
</div>