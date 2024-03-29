<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
/**
* VirtueMart Search Module
* NOTE: THIS MODULE REQUIRES THE PHPSHOP COMPONENT FOR MOS!
*
* @version $Id: mod_virtuemart_search.php 1159 2008-01-14 20:30:30Z soeren_nb $
* @package VirtueMart
* @subpackage modules
*
* @copyright (C) 2004-2007 soeren
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* VirtueMart is Free Software.
* VirtueMart comes with absolute no warranty.
*
* www.virtuemart.net
*/

// Load the virtuemart main parse code
if( file_exists(dirname(__FILE__).'/../../components/com_virtuemart/virtuemart_parser.php' )) {
	require_once( dirname(__FILE__).'/../../components/com_virtuemart/virtuemart_parser.php' );
} else {
	require_once( dirname(__FILE__).'/../components/com_virtuemart/virtuemart_parser.php' );
}

global $VM_LANG, $mm_action_url, $sess;

?>
<div class="search_block radgrad">
<!--BEGIN Search Box --> 
<form action="<?php $sess->purl( $mm_action_url."index.php?page=shop.browse" ) ?>" method="post">

	<!--<p><label for="keyword"><?php echo $VM_LANG->_('PHPSHOP_SEARCH_LBL') ?></label></p>-->
		<input name="keyword" type="text" size="12" title="<?php echo $VM_LANG->_('PHPSHOP_SEARCH_TITLE') ?>" class="inputbox" id="keyword" size="20" value="поиск..." onblur="if(this.value=='') this.value='поиск...';" onfocus="if(this.value=='поиск...') this.value='';" />
		<input class="enter_button" type="submit" name="Search" value="<?php echo $VM_LANG->_('PHPSHOP_SEARCH_TITLE') ?>" />
	
</form>
    <div class="search_help">
        Воспользуйтесь поиском по словам <i>(например: семья, судьба, здоровье)</i>
    </div>
</div>
<!-- End Search Box --> 