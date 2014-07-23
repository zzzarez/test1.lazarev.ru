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
include_once('main.p2dxt.php');
?>

<?php 
$task		= JRequest::getVar('task', "cPanel");
$cid 		= JRequest::getVar( 'cid', array(0), '', 'array' );




// Require the base controller
if( $controller = JRequest::getWord('controller'))
{
	
	jimport('joomla.application.component.controller');
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if( file_exists($path))
	{
		require_once $path;
	} else
	{
		$controller = '';
	}

	$classname    = 'p2dxtController'.$controller;
	$controller   = new $classname( );
	// Perform the Request task
	$controller->execute( JRequest::getVar( 'task' ) );
	// Redirect if set by the controller
	$controller->redirect();
	$task = "done";
}

switch ($task) {
	case "editSettings":
		editSettings();
		break;		
	case "saveSettings":
		saveSettings();		
		break;
	case "listItems":
		showList();		
		break;
	case "saveItem":
		saveItem();		
		break;
	case "edit":
		editItem($cid[0]);		
		break;
	case "add":
		newItem();		
		break;
	case "new":
		newItem();		
		break;
	case "cancel":
		cPanel();
		break;
	case "cancelItem":
		showList();		
		break;
	case "remove":
		deleteItem($cid[0]);
		break;
	case "txlist":
		txlist();
		break;
	case "deletetx":
		deletetx($cid);
		break;
	case "done":
		break;
	default:
		cPanel();
		break;

}
function cPanel() {
	$controller = "cpanel";
	
	jimport('joomla.application.component.controller');
	$path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
	if( file_exists($path))
	{
		require_once $path;
	} else
	{
		$controller = '';
	}

	$classname    = 'p2dxtController'.$controller;
	$controller   = new $classname( );
	// Perform the Request task
	$controller->execute( JRequest::getVar( 'task' ) );
	// Redirect if set by the controller
	$controller->redirect();
	return;
}

function txlist() {
	global $mainframe;
	$db = JFactory::getDBO();
	$option		= JRequest::getVar('option', 0);


	$context = 'com_p2dxt.tx.list.';
	$filter_order = $mainframe->getUserStateFromRequest( $context.'filter_order','filter_order','t.id',	'cmd' );
	$filter_order_Dir = $mainframe->getUserStateFromRequest( $context.'filter_order_Dir','filter_order_Dir','','word' );
	$filter_time = $mainframe->getUserStateFromRequest( $context.'filter_time','filter_time','',	'string' );
	$filter_productid = $mainframe->getUserStateFromRequest( $context.'filter_productid','filter_productid','',	'string' );
	$filter_itemid = $mainframe->getUserStateFromRequest( $context.'filter_itemid','filter_itemid','','int' );
	$filter_test = $mainframe->getUserStateFromRequest( $context.'filter_test','filter_test','','int' );

	$orderby	= ' ORDER BY '. $filter_order .' '. $filter_order_Dir .', t.id';

	if ($filter_time) {
	 		$ts = getTimeStamps();
	 		$dat = $ts->$filter_time;
			$where[] = 't.payment_date > ' . "'".$dat."'";
			if (strpos($filter_time, "l")===0) {		
			 	$c = str_replace("l","c",$filter_time);
			 	$cdat = $ts->$c;	
				$where[] = 't.payment_date < ' . "'".$cdat."'";
			}
			
	}
	
	if ($filter_productid) {
			$where[] = 'i.productid = ' . "'".$filter_productid."'";
	}
	if ($filter_test) {
			$where[] = 't.testmode <> 1';
	}
	if ($filter_itemid) {
			$where[] = 't.item_number = ' . $filter_itemid;
	}
	
	if (isset($where))
		$where	= " WHERE t.txn_id <> '' AND ".implode( ' AND ', $where );
	else
		$where	= " WHERE t.txn_id <> '' ";

	$query = "SELECT COUNT(t.id) FROM #__p2dxt_tx as t LEFT JOIN #__p2dxt_files as i ON t.item_number = i.id ".$where;
	$db->setQuery( $query );
	$total = $db->loadResult();

	jimport('joomla.html.pagination');
	$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
	$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

	$pagination = new JPagination( $total, $limitstart, $limit );


	$query = "SELECT i.*, t.* FROM #__p2dxt_tx as t LEFT JOIN #__p2dxt_files as i ON t.item_number = i.id ".$where.$orderby;

	$db->setQuery($query, $limitstart, $limit );
	$txlist = $db->loadObjectList();
	JToolBarHelper::title(  JText::_( 'Transaction List' ) );
	JToolBarHelper::deleteList(JText::_("Are you sure you want to delete the selected transaction(s)?"), "deletetx");
	JToolBarHelper::cancel();
	


	// get list of Items for dropdown filter
	$query = 'SELECT id,itemname'
	. ' FROM #__p2dxt_files '
	. ' GROUP BY id'
	. ' ORDER BY itemname'
	;
	$db->setQuery( $query );
	$itemids = $db->loadObjectList();
	$items[] 			= JHTML::_('select.option',  '0', '- '. JText::_( 'Select Item' ) .' -', 'value', 'text' );

	foreach ($itemids as $i) {
		$items[] = JHTML::_('select.option', $i->id, $i->itemname);
	}
	$lists['itemid']	= JHTML::_('select.genericlist',   $items, 'filter_itemid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $filter_itemid );
	
	// get list of products for dropdown filter
	$query = 'SELECT productid'
	. ' FROM #__p2dxt_files '
	. ' GROUP BY productid'
	. ' ORDER BY productid'
	;
	$db->setQuery( $query );
	$pids = $db->loadObjectList();
	$ps[] 			= JHTML::_('select.option',  '0', '- '. JText::_( 'Select ProductID' ) .' -', 'value', 'text' );
	
	foreach ($pids as $i) {
		$ps[] = JHTML::_('select.option', $i->productid, $i->productid);
	}
	$lists['productid']	= JHTML::_('select.genericlist',   $ps, 'filter_productid', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $filter_productid );

	// create list for test dropdown filter
	$tes[] = JHTML::_('select.option', "0", JText::_("Show all"));
	$tes[] = JHTML::_('select.option', "1", JText::_("Filter test items"));
	$lists['test']	= JHTML::_('select.genericlist',   $tes, 'filter_test', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $filter_test );
	
	// create list for timeframe dropdown filter
	$tval[] = JHTML::_('select.option',  '0', '- '. JText::_( 'Select Timeframe' ) .' -', 'value', 'text' );
	$tval[] = JHTML::_('select.option', "cDay", JText::_("Today"));
	$tval[] = JHTML::_('select.option', "lDay", JText::_("Yesterday"));
	$tval[] = JHTML::_('select.option', "prevWeek", JText::_("Last 7 Days"));
	$tval[] = JHTML::_('select.option', "cMonth", JText::_("Current Month"));
	$tval[] = JHTML::_('select.option', "lMonth", JText::_("Last Month"));
	$tval[] = JHTML::_('select.option', "cYear", JText::_("Current Year"));
	$tval[] = JHTML::_('select.option', "lYear", JText::_("Last Year"));

	$lists['time']	= JHTML::_('select.genericlist',   $tval, 'filter_time', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $filter_time );


	// table ordering
	$lists['order_Dir']	= $filter_order_Dir;
	$lists['order']		= $filter_order;
	
	p2dxt_html::txList($txlist,$pagination, $lists);	
}

function deleteItem($id) {
 	global $mainframe;
	$db = JFactory::getDBO();
	$query = "DELETE FROM #__p2dxt_files WHERE id = '$id'";
	$db->setQuery($query);
	$db->query();
	$mainframe->redirect("index2.php?option=com_p2dxt&task=listItems");
}
function deletetx($id) {
 	global $mainframe;
	$db = JFactory::getDBO();
	foreach ($id as $did) {
		$query = "DELETE FROM #__p2dxt_tx WHERE id = '$did'";
		$db->setQuery($query);
		$db->query();
	}
	$mainframe->redirect("index2.php?option=com_p2dxt&task=txlist");
}

function saveItem() {
 	global $mainframe;
	$uploadErrors = array(
    UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
    UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
    UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
    UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
    UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
    UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
    UPLOAD_ERR_EXTENSION => 'File upload stopped by extension.',
	);
	$db = JFactory::getDBO();

	$row		= JRequest::getVar('row', "", "", "", JREQUEST_ALLOWRAW);
	$row["introtext"] = $row["introtext"];
	$row["itemname"] = $row["itemname"];
	if ($_FILES['file']['name']['userfile']) {
	 	$errorCode = $_FILES['file']['userfile']['error'];
		if($errorCode !== UPLOAD_ERR_OK)
		{
	    	if(isset($uploadErrors[$errorCode])) {
        		$msg = $uploadErrors[$errorCode];
				$mainframe->redirect("index2.php?option=com_p2dxt&task=listItems", $msg);	
			}
		}
		
		$uploadfile = P2Dpro::getDataPath().DS.$_FILES['file']['name']['userfile'];
		
		if (!move_uploaded_file($_FILES['file']['tmp_name']['userfile'], $uploadfile)) 
			$msg = "upload Error";
		chmod($uploadfile, 0600);
	}
	
	$p2d = new p2dxt_files($db);
	
	$p2d->bind($row);
	if ($row["selectfile"]) $p2d->filename = $row["selectfile"];
	if ($_FILES['file']['name']['userfile'] != "")
		$p2d->filename=$_FILES['file']['name']['userfile'];
	
	if (!$p2d->store())
	{
		JError::raiseWarning(E_ERROR, $p2d->getError() );
		$mainframe->redirect("index2.php?option=com_p2dxt&task=edit&cid[]=".$p2d->id);
		
	}
	P2Dpro::plgSaveItem($p2d->id);
	
	$mainframe->redirect("index2.php?option=com_p2dxt&task=listItems", $msg);
	
}

function editItem($id) {
	$uri = JURI::getInstance();
	$root = $uri->root();
	$doc =& JFactory::getDocument();
	$doc->addScript($root."administrator/components/com_p2dxt/script/p2dxt.admin.item.js");


	$db = JFactory::getDBO();
	$row = new p2dxt_files($db);
	$row->load($id);
	
	if ($row->maxdown == "") $row->maxdown = "1";
	
	$query = "SELECT COUNT(id) FROM #__p2dxt_tx WHERE item_number = '$id'";
	$db->setQuery( $query );
	$cnt = $db->loadResult();
	
	$files = JFolder::files(P2Dpro::getDataPath());

	$opt[] = JHTML::_( 'select.option',  "-- Select File --", "");
	
	foreach ($files as $f) 
		$opt[] = JHTML::_( 'select.option',  $f);

	$sel = JHTML::_( 'select.genericlist', $opt, 'row[selectfile]', null, 'value', 'text', $row->filename); 

	JToolBarHelper::title(  JText::_( 'Edit Item' ) );
	JToolBarHelper::save('saveItem');
	JToolBarHelper::cancel('cancelItem');
	p2dxt_html::editItem($row, $sel, $cnt);	
}

function newItem() {
	$uri = JURI::getInstance();
	$root = $uri->root();
	$doc =& JFactory::getDocument();
	$doc->addScript($root."administrator/components/com_p2dxt/script/p2dxt.admin.item.js");

	$db = JFactory::getDBO();
	$row = new p2dxt_files($db);
	
	$files = JFolder::files(P2Dpro::getDataPath());
	$cnt = 0;
	$opt[] = JHTML::_( 'select.option',  "-- Select File --", "");
	
	foreach ($files as $f) 
		$opt[] = JHTML::_( 'select.option',  $f);

	$sel = JHTML::_( 'select.genericlist', $opt, 'row[selectfile]', null, 'value', 'text', $row->filename); 
	
	
	JToolBarHelper::title(  JText::_( 'New Item' ) );
	JToolBarHelper::save('saveItem');
	JToolBarHelper::cancel();
	p2dxt_html::editItem($row, $sel, $cnt);	
}

function showList() {
global $mainframe;
	$db = JFactory::getDBO();
	$option		= JRequest::getVar('option', 0);

	$query = 'SELECT COUNT(id) FROM #__p2dxt_files';
	$db->setQuery( $query );
	$total = $db->loadResult();


	jimport('joomla.html.pagination');
	$limit		= $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
	$limitstart	= $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );

	$pagination = new JPagination( $total, $limitstart, $limit );

	$query = 'SELECT * FROM #__p2dxt_files';
	$db->setQuery( $query, $pagination->limitstart, $pagination->limit );
	$rows = $db->loadObjectList();

	foreach ($rows as &$row) {
		$query = "SELECT COUNT(id) FROM #__p2dxt_tx WHERE item_number = '$row->id'";
		$db->setQuery( $query );
		$row->cnt = $db->loadResult();
	}

	JToolBarHelper::title(  JText::_( 'Pay 2 download items' ) );
	JToolBarHelper::deleteList();
	JToolBarHelper::editListX();
	JToolBarHelper::addNewX();
	JToolBarHelper::cancel();

	p2dxt_html::showList($rows, $pagination);	
}


function editSettings() {
	$database = JFactory::getDBO();
	$conf = new p2dxt($database);
	$conf->load("1");

	if ($conf->button == "") $conf->button = "http://www.paypal.com/en_US/i/btn/btn_paynowCC_LG.gif";
	if ($conf->dbutton == "") $conf->dbutton = "https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif";
	
	JToolBarHelper::title(  JText::_( 'Global Settings' ) );
	JToolBarHelper::save('saveSettings');
	JToolBarHelper::cancel();
	
	p2dxt_html::editSettings($conf);
		
}

function saveSettings() {
 	global $mainframe;

	$db = JFactory::getDBO();

	$row		= JRequest::getVar('conf', 0, "", "", JREQUEST_ALLOWRAW);
	
	$p2d = new p2dxt($db);
	
	$p2d->bind($row);

	if (!$p2d->store())
	{
		JError::raiseWarning(E_ERROR, $p2d->getError() );
		$mainframe->redirect("index2.php?option=com_p2dxt&task=editSettings");
	}
	$mainframe->redirect("index2.php?option=com_p2dxt&task=editSettings", JText::_("Settings saved"));
}



class p2dxt_html {
	function editSettings($conf) {
		jimport('joomla.html.pane');
		JHTML::_('behavior.modal');
		$pane = &JPane::getInstance('sliders', array('allowAllClose' => false));
		$editor = JFactory::getEditor();
	 
?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		else {
			submitform(pressbutton);
		}
	}
	function jInsertEditorText(tag, editor) {
	 	ret = tag.match(/src="(\S*)"/);
	 	val = ret[1];
	 	document.getElementById(editor).value=val;
	}
		
</script>
<form action="index2.php" method="post" name="adminForm">
<?php echo $pane->startPane("settings"); ?>
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'Settings' ); ?></legend>

<?php	  echo $pane->startPanel(JText :: _('Paypal Access Settings'), "access");
?>
	<table class="admintable">
		<tr>
			<td width="110" class="key">
				<label for="locale">
					<?php echo JText::_( 'Locale' ); ?>:
				</label>
			</td>
			<td>
			<?php
				$items[] = JHTML::_('select.option',  '0', '- '. JText::_( 'Select Country' ) .' -', 'value', 'text' );
				$cc= getCountryCodeArray();
				foreach ($cc as $k=>$v) {
					$items[] = JHTML::_('select.option', strtoupper($k), $v);
				}
				echo JHTML::_('select.genericlist',   $items, 'conf[locale]', 'class="inputbox" size="1";"', 'value', 'text', $conf->locale );
							
			?>
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="business">
					<?php echo JText::_( 'Paypal Account' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="conf[business]" value="<?php echo $conf->business;?>" size="80">
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="token">
					<?php echo JText::_( 'Token' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="conf[token]" value="<?php echo $conf->token;?>" size="80">
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="testbusiness">
					<?php echo JText::_( 'Paypal Test Account' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="conf[testbusiness]" value="<?php echo $conf->testbusiness;?>" size="80">
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="testtoken">
					<?php echo JText::_( 'Test Token' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="conf[testtoken]" value="<?php echo $conf->testtoken;?>" size="80">
			</td>
		</tr>
	</table>
<?php  
echo $pane->endPanel();
echo $pane->startPanel(JText :: _('Layout'), "layout"); ?>
	<table class="admintable">
		<tr>
			<td width="110" class="key">
				<label for="button">
					<?php echo JText::_( 'Pay now button' ); ?>:
				</label>
			</td>
			<td>
				<?php echo mediaManager("conf[button]", $conf->button) ?>            
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="button">
					<?php echo JText::_( 'Donation button' ); ?>:
				</label>
			</td>
			<td>
				<?php echo mediaManager("conf[dbutton]", $conf->dbutton) ?>            
			</td>
		</tr>
		<tr>
			<td width="110" class="key">
				<label for="popupsize">
					<?php echo JText::_( 'Popup Size' ); ?>:
				</label>
			</td>
			<td>
				<?php echo JText::_( 'Width (px))' ); ?>:
				<input class="inputbox" type="text" name="conf[popupx]" value="<?php echo $conf->popupx;?>" size="4">
				<?php echo JText::_( 'Height (px))' ); ?>:
				<input class="inputbox" type="text" name="conf[popupy]" value="<?php echo $conf->popupy;?>" size="4">
			</td>
		</tr>
		<?php P2Dpro::powered($conf);?>
	</table>
<?php 
echo $pane->endPanel();

P2DPro::settings($conf, $pane);
?>
	</fieldset>	
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'Texts' ); ?></legend>
<?php	echo $pane->startPanel(JText :: _('Error Text'), "errortext"); ?>

	<table class="admintable">
		<tr>
			<td>
				<?php echo $editor->display( 'conf[errortext]',  $conf->errortext, '800', '300', '100', '20', array()) ; ?>
			</td>
		</tr>
	</table>
<?php 
echo $pane->endPanel();
echo $pane->startPanel(JText :: _('Confirmation Text'), "conftext"); ?>
	<table class="admintable">
		<tr>
			<td>
				<?php echo $editor->display( 'conf[thanktext]',  $conf->thanktext, '800', '300', '100', '20', array()) ; ?>
			</td>
		</tr>
	</table>
<?php 
echo $pane->endPanel();
P2DPro::texts($conf, $editor, $pane); 
?>
	</fieldset>
<?php
P2DPro::plgConfigSettings($pane);
echo $pane->endPane(); ?>

	<input type="hidden" name="conf[id]" value="<?php echo $conf->id; ?>" />
	<input type="hidden" name="conf[version]" value="<?php echo $conf->version; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_p2dxt" />
</form>

<?php	
	}
	function showList($rows, $pagination) {
?>
<form action="index2.php?option=com_p2dxt" method="post" name="adminForm">
<div id="tablecell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5">
				<?php echo JText::_( 'ID' ); ?>
			</th>
			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $rows ); ?>);" />
			</th>
			<th  align="left" >
				<?php echo JText::_( 'Item Title' ) ?>
			</th>
			<th  align="left" >
				<?php echo JText::_( 'Downloads' ) ?>
			</th>

		</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="4">
				<?php echo $pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	<?php
	$k = $i = 0;
	foreach ($rows as $row)
	{
		
		$link 		= JRoute::_( 'index2.php?option=com_p2dxt&task=edit&cid[]='. $row->id );

	?>
		<tr class="<?php echo "row$k"; ?>">
			<td>
				<?php echo $row->id; ?>
			</td>
			<td>
				<?php echo '<input type="checkbox" id="cb'.$i.'" name="cid[]" value="'.$row->id.'" onclick="isChecked(this.checked);" />';?>			</td>
			<td align="left">
				<a href="<?php echo $link  ?>">
					<?php echo $row->itemname; ?></a>
			</td>
			<td align="left">
				<?php echo $row->cnt; ?>
			</td>
		</tr>
		<?php
			$k = 1 - $k;
			$i++;
		}
		?>
	</tbody>
	</table>
</div>

	<input type="hidden" name="hidemainmenu" value="" />
	<input type="hidden" name="option" value="com_p2dxt" />
	<input type="hidden" name="task" value="listItems" />
	<input type="hidden" name="boxchecked" value="0" />

</form>
<?php  		
	}	
	function editItem($row, $sel, $cnt=0) {
		jimport('joomla.html.pane');
		$pane = &JPane::getInstance('sliders', array('allowAllClose' => false));
		$editor = JFactory::getEditor();

?>
<script language="javascript" type="text/javascript">
	function submitbutton(pressbutton) {
		var form = document.adminForm;
		if (pressbutton == 'cancel') {
			submitform( pressbutton );
			return;
		}
		else {
		 	<?php echo $editor->save( 'row[introtext]' ) ; ?>

			submitform(pressbutton);
		}
	}
</script>
<form enctype="multipart/form-data" action="index2.php" method="post" name="adminForm">
<?php echo $pane->startPane("settings"); ?>
	<fieldset class="adminform">
	<legend><?php echo JText::_( 'Item Settings' ); ?></legend>
	<?php echo $pane->startPanel(JText :: _('Product Description'), "product"); ?>
	
	<table class="admintable" width="100%">
		<tr>
			<td width="300" class="key">
				<label for="itemname">
					<?php echo JText::_( 'Item Name' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="row[itemname]" value="<?php echo $row->itemname;?>" size="80">
			</td>
		</tr>
		<tr>
			<td width="300" class="key">
				<label for="productid">
					<?php echo JText::_( 'Unique Product ID' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="row[productid]" value="<?php echo $row->productid;?>" size="80">
			</td>
		</tr>
		<tr>
			<td width="300" class="key">
				<label for="metatags">
					<?php echo JText::_( 'Meta Tags (Keywords)' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="row[meta]" value="<?php echo $row->meta;?>" size="80">
			</td>
		</tr>
		<tr>
			<td width="300" class="key">
				<label for="introtext">
					<?php echo JText::_( 'Description' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $editor->display( 'row[introtext]',  $row->introtext, '350', '300', '60', '20', array()) ; ?>
			</td>
		</tr>
	</table>
<?php  
echo $pane->endPanel();
?>
<?php	echo $pane->startPanel(JText :: _('File & Download Settings'), "file"); ?>
	<table class="admintable">
		<tr>
			<td width="300" class="key">
				<label for="filename">
					<?php echo JText::_( 'Filename' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="row[filename]" value="<?php echo $row->filename;?>" readonly="readonly"  size="80"> 
			</td>
		</tr>
		<tr>
			<td width="300" class="key">
				<label for="uploadfile">
					<?php echo JText::_( 'Upload File' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" name="file[userfile]" type="file"> 
			</td>
		</tr>
		<tr>
			<td width="300" class="key">
				<label for="selectfile">
					<?php echo JText::_( 'Select File' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $sel; ?>
			</td>
		</tr>
		<tr>
			<td width="300" class="key">
				<label for="url">
					<?php echo JText::_( 'URL' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" name="row[url]" size="80"  value="<?php echo $row->url;?>"> 
			</td>
		</tr>
		<tr>
			<td width="300" class="key">
				<label for="dwnl_count">
					<?php echo JText::_( 'Downloads:' ); ?>:
				</label>
			</td>
			<td>
				<?php echo $cnt;?>
			</td>
		</tr>
		<tr>
			<td width="300" class="key">
				<label for="maxdown">
					<?php echo JText::_( 'Maximum no. of downloads per User' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="row[maxdown]" value="<?php echo $row->maxdown;?>" size="3">
			</td>
		</tr>
		<tr>
			<td width="300" class="key">
				<label for="limitdown">
					<?php echo JText::_( 'Maximum no. of total Downloads' ); ?>:
				</label>
			</td>
			<td>
				<input class="inputbox" type="text" name="row[limitdown]" value="<?php echo $row->limitdown;?>" size="9">
			</td>
		</tr>


	</table>
<?php  
echo $pane->endPanel();
P2DPro::itemSettingsArticle($row,$pane);
?>
<?php	echo $pane->startPanel(JText :: _('Pricing & Shipping'), "donation"); ?>
	<table class="admintable">
		<tr>
			<td width="300" class="key">
				<label for="payment">
					<?php echo JText::_( 'Payment method' ); ?>:
				</label>
			</td>
			<td width="600">
				<?php $cc= array(JText::_("Fixed Amount")=>0, JText::_("Minimum Amount")=>2, JText::_("Donation")=>1);
				foreach ($cc as $v=>$k) {
					$items[] = JHTML::_('select.option', $k, $v);
				}
				echo JHTML::_('select.genericlist',   $items, 'row[donation]', 'id="row[donation]" class="inputbox" size="1"', 'value', 'text', $row->donation );				
				?>
			</td>
		</tr>
		<tr >
			<td id="donationContent" colspan="2"></td>
		</tr>
		<tr>
			<td width="300" class="key">
				<label for="currency">
					<?php echo JText::_( 'Currency' ); ?>:
				</label>
			</td>
			<td>
			<?php
				$items = array();
				$items[] = JHTML::_('select.option',  '0', '- '. JText::_( 'Select Currency' ) .' -', 'value', 'text' );
				$cc= array("Australian Dollar"=>"AUD","Brazilian Real"=>"BRL", "Canadian Dollar"=>"CAD",
							"Czech Koruna"=>"CZK","Danish Krone"=>"DKK", "Euro"=>"EUR", "Hong Kong Dollar"=>"HKD",
							"Hungarian Forint"=>"HUF","Israeli New Sheqel"=>"ILS", "Japanese Yen"=>"JPY", 
							"Malaysian Ringgit"=>"MYR", "Mexican Peso"=>"MXN", 
							"Norwegian Krone"=>"NOK", "New Zealand Dollar"=>"NZD", "Philippine Peso"=>"PHP",
							"Polish Zloty"=>"PLN",
							"Pound Sterling"=>"GBP", "Singapore Dollar"=>"SGD", "Swedish Krona"=>"SEK", 
							"Swiss Franc"=>"CHF", "Taiwan New Dollar"=>"TWD", "Thai Baht"=>"THB",
							"U.S. Dollar"=>"USD");
				foreach ($cc as $v=>$k) {
					$items[] = JHTML::_('select.option', $k, $v);
				}
				echo JHTML::_('select.genericlist',   $items, 'row[currency]', 'class="inputbox" size="1"', 'value', 'text', $row->currency );
							
			?>

			</td>
		</tr>
		<tr>
			<td width="300" class="key">
				<label for="shipping">
					<?php echo JText::_( 'Shipping address required' ); ?>:
				</label>
			</td>
			<td>
				<?php 
				$arr = array(
					JHTML::_('select.option',  '1', JText::_( 'No' ) ),
					JHTML::_('select.option',  '2', JText::_( 'Yes' ) )
				);
				echo JHTML::_('select.radiolist',  $arr, 'row[shipping]', '', 'value', 'text', $row->shipping);
				?>
			</td>
		</tr>
	</table>
<?php  
echo $pane->endPanel();
P2DPro::itemSettingsUpgrade($row,$pane);
?>
<?php	echo $pane->startPanel(JText :: _('Testmode'), "test"); ?>
	<table class="admintable">
		
		<tr>
			<td width="300" class="key">
				<label for="testmode">
					<?php echo JText::_( 'Testmode' ); ?>:
				</label>
			</td>
			<td>
				<?php echo JHTML::_( 'select.booleanlist',  'row[testmode]', 'class="inputbox"', $row->testmode ); ?>
			</td>
		</tr>

	</table>
<?php  
echo $pane->endPanel();
P2DPro::itemSettingsOverride($row,$pane);
?>

	</fieldset>
<?php
P2DPro::plgConfigItem($pane, $row->id);
echo $pane->endPane();
?>
	

	<input type="hidden" id= "row[id]" name="row[id]" value="<?php echo $row->id; ?>" />
	<input type="hidden" name="cid[]" value="<?php echo $row->id; ?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="option" value="com_p2dxt" />
	<?php echo JHTML::_( 'form.token' ); ?>
</form>

<?php	
	}

function txList($txs, $pagination, $lists) {
	?>
<form action="index2.php" method="post" name="adminForm">
<div id="tablecell">
			<table>
				<tr>
					<td width="100%" class="filter">
					</td>
					<td nowrap="nowrap">
				<?php
				echo $lists['time'];
				echo $lists['productid'];
				echo $lists['itemid'];
				echo $lists['test'];
				?>
					</td>
				</tr>
			</table>


	<table class="adminlist">
	<thead>
 	<tr>
	<th width="20">
		<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count( $txs ); ?>);" />
	</th>
 	
 	<th align="left"><?php echo JHTML::_('grid.sort',  JText::_('TRANSACTION ID'), 't.txn_id', @$lists['order_Dir'], @$lists['order'] );?> </th>
 	<th align="left"><?php echo JHTML::_('grid.sort',  JText::_('Product ID'), 'i.productid', @$lists['order_Dir'], @$lists['order'] );?> </th>
 	<th align="left"><?php echo JHTML::_('grid.sort',  JText::_('Payment Date'), 't.payment_date', @$lists['order_Dir'], @$lists['order'] );?> </th>
 	<th align="left"><?php echo JHTML::_('grid.sort',  JText::_('Buyer'), 't.payer_email', @$lists['order_Dir'], @$lists['order'] );?> </th>
 	<th align="left"><?php echo JHTML::_('grid.sort',  JText::_('Status'), 't.payment_status', @$lists['order_Dir'], @$lists['order'] );?> </th>
 	<th align="left"><?php echo JHTML::_('grid.sort',  JText::_('Buyer IP'), 't.ip', @$lists['order_Dir'], @$lists['order'] );?> </th>
 	<th align="left"><?php echo JHTML::_('grid.sort',  JText::_('Item No.'), 't.item_number', @$lists['order_Dir'], @$lists['order'] );?> </th>
 	<th align="left"><?php echo JHTML::_('grid.sort',  JText::_('Item'), 'i.itemname', @$lists['order_Dir'], @$lists['order'] );?> </th>
 	<th align="left"><?php echo JHTML::_('grid.sort',  JText::_('Quantity'), 't.quantity', @$lists['order_Dir'], @$lists['order'] );?> </th>
 	<th align="left"><?php echo JHTML::_('grid.sort',  JText::_('Price'), 't.mc_gross', @$lists['order_Dir'], @$lists['order'] );?> </th>
 	<th align="left"><?php echo JHTML::_('grid.sort',  JText::_('Taxes'), 't.tax', @$lists['order_Dir'], @$lists['order'] );?> </th>
 	<th align="left"><?php echo JHTML::_('grid.sort',  JText::_('Fee'), 't.fee', @$lists['order_Dir'], @$lists['order'] );?> </th>
 	<th align="left"><?php echo JHTML::_('grid.sort',  JText::_('Testmode'), 't.testmode', @$lists['order_Dir'], @$lists['order'] );?> </th>
	</tr>
	</thead>
	<tfoot>
		<tr>
			<td colspan="11">
				<?php echo $pagination->getListFooter(); ?>
			</td>
		</tr>
	</tfoot>
	<tbody>
	
	<?php
	$i = 0;
	foreach ($txs as $tx) {?>
	<tr>
	<td>
		<?php echo '<input type="checkbox" id="cb'.$i.'" name="cid[]" value="'.$tx->id.'" onclick="isChecked(this.checked);" />';?>			</td>
	<td><?php echo $tx->txn_id; ?></td>
	<td><?php echo $tx->productid; ?></td>
	<td><?php echo date("m-d-y H:i", $tx->payment_date); ?></td>
	<td><a href="mailto:<?php echo $tx->payer_email; ?>"><?php echo $tx->first_name." ".$tx->last_name; ?></a></td>
	<td><?php echo $tx->payment_status; ?></td>
	<td><?php echo $tx->ip; ?></td>
	<td><?php echo $tx->item_number; ?></td>
	<td><?php echo $tx->itemname; ?></td>
	<td><?php echo $tx->quantity; ?></td>
	<td><?php echo $tx->mc_gross; ?></td>
	<td><?php echo $tx->tax; ?></td>
	<td><?php echo $tx->mc_fee; ?></td>
	<td><?php echo $tx->testmode; ?></td>
	</tr>
	<?php
	$i++; 	
	}
	?></table>
	<input type="hidden" name="hidemainmenu" value="" />
	<input type="hidden" name="option" value="com_p2dxt" />
	<input type="hidden" name="task" value="txlist" />
	<input type="hidden" name="boxchecked" value="0" />	
	<input type="hidden" name="filter_order" value="<?php echo $lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $lists['order_Dir']; ?>" />
	
</form>
	
	<?php
$i++;
}
}
function mediaManager($fieldname, $value, $root = "") { 
		$html = '<input class="inputbox" type="text" name="'.$fieldname.'" id="'.$fieldname.'" size="100" value="'.$value.'"/>';
        $rootfolder = "";
        $html .= '<a id="modal" class="modal" title="Image" href="index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;e_name='.$fieldname.$rootfolder.'" rel="{handler: \'iframe\', size: {x: 570, y: 400}}"><img style="vertical-align:bottom" src="'.JUri::root().'administrator/templates/khepri/images/j_button2_image_rtl.png"><img style="vertical-align:bottom" src="'.JUri::root().'administrator/templates/khepri/images/j_button2_right_cap.png"></a>';
return $html;
	}

function getCountryCodeArray() {
   
      $countriesArray = array();
      
      // A
      $countriesArray['Al'] = 'Albania';
      $countriesArray['Dz'] = 'Algeria';
      $countriesArray['As'] = 'American Samoa';
      $countriesArray['Ad'] = 'Andorra';
      $countriesArray['Ai'] = 'Anguilla';
      $countriesArray['Ag'] = 'Antigua And Barbuda';
      $countriesArray['Ar'] = 'Argentina';
      $countriesArray['Am'] = 'Armenia';
      $countriesArray['Aw'] = 'Aruba';
      $countriesArray['Au'] = 'Australia';
      $countriesArray['At'] = 'Austria';
      $countriesArray['Az'] = 'Azerbaijan';
      
      // B
      $countriesArray['Bs'] = 'Bahamas';
      $countriesArray['Bh'] = 'Bahrain';
      $countriesArray['Bd'] = 'Bangladesh';
      $countriesArray['Bb'] = 'Barbados';
      $countriesArray['By'] = 'Belarus';
      $countriesArray['Be'] = 'Belgium';
      $countriesArray['Bz'] = 'Belize';
      $countriesArray['Bj'] = 'Benin';
      $countriesArray['Bm'] = 'Bermuda';
      $countriesArray['Bo'] = 'Bolivia';
      $countriesArray['Ba'] = 'Bosnia And Herzegovina';
      $countriesArray['Bw'] = 'Botswana';
      $countriesArray['Br'] = 'Brazil';
      $countriesArray['Vg'] = 'British Virgin Islands';
      $countriesArray['Bn'] = 'Brunei';
      $countriesArray['Bg'] = 'Bulgaria';
      $countriesArray['Bf'] = 'Burkina Faso';
      
      // C
      $countriesArray['Kh'] = 'Cambodia';
      $countriesArray['Cm'] = 'Cameroon';
      $countriesArray['Ca'] = 'Canada';
      $countriesArray['Cv'] = 'Cape Verde';
      $countriesArray['Ky'] = 'Cayman Islands';
      $countriesArray['Cl'] = 'Chile';
      $countriesArray['Cn'] = 'China';
      $countriesArray['Co'] = 'Colombia';
      $countriesArray['Ck'] = 'Cook Islands';
      $countriesArray['Hr'] = 'Croatia';
      $countriesArray['Cy'] = 'Cyprus';
      $countriesArray['Cz'] = 'Czech Republic';
      
      // D
      $countriesArray['Dk'] = 'Denmark';
      $countriesArray['Dj'] = 'Djibouti';
      $countriesArray['Dm'] = 'Dominica';
      $countriesArray['Do'] = 'Dominican Republic';
      
      // E
      $countriesArray['Tp'] = 'East Timor';
      $countriesArray['Eg'] = 'Egypt';
      $countriesArray['Sv'] = 'El Salvador';
      $countriesArray['Ee'] = 'Estonia';
      
      // F
      $countriesArray['Fj'] = 'Fiji';
      $countriesArray['Fi'] = 'Finland';
      $countriesArray['Fr'] = 'France';
      $countriesArray['Gf'] = 'French Guiana';
      $countriesArray['Pf'] = 'French Polynesia';
      
      // G
      $countriesArray['Ga'] = 'Gabon';
      $countriesArray['Ge'] = 'Georgia';
      $countriesArray['De'] = 'Germany';
      $countriesArray['Gh'] = 'Ghana';
      $countriesArray['Gi'] = 'Gibraltar';
      $countriesArray['Gr'] = 'Greece';
      $countriesArray['Gd'] = 'Grenada';
      $countriesArray['Gp'] = 'Guadeloupe';
      $countriesArray['Gu'] = 'Guam';
      $countriesArray['Gt'] = 'Guatemala';
      $countriesArray['Gn'] = 'Guinea';
      $countriesArray['Gy'] = 'Guyana';
      
      // H
      $countriesArray['Ht'] = 'Haiti';
      $countriesArray['Hn'] = 'Honduras';
      $countriesArray['Hk'] = 'Hong Kong';
      $countriesArray['Hu'] = 'Hungary';
      
      // I
      $countriesArray['Is'] = 'Iceland';
      $countriesArray['In'] = 'India';
      $countriesArray['Id'] = 'Indonesia';
      $countriesArray['Ie'] = 'Ireland';
      $countriesArray['Il'] = 'Israel';
      $countriesArray['It'] = 'Italy';
      $countriesArray['Ci'] = 'Ivory Coast';
      
      // J
      $countriesArray['Jm'] = 'Jamaica';
      $countriesArray['Jp'] = 'Japan';
      $countriesArray['Jo'] = 'Jordan';
      
      // K
      $countriesArray['Kz'] = 'Kazakhstan';
      $countriesArray['Ke'] = 'Kenya';
      $countriesArray['Kw'] = 'Kuwait';
      
      // L
      $countriesArray['La'] = 'Lao People\'S Democratic Republic';
      $countriesArray['Lv'] = 'Latvia';
      $countriesArray['Lb'] = 'Lebanon';
      $countriesArray['Ls'] = 'Lesotho';
      $countriesArray['Lt'] = 'Lithuania';
      $countriesArray['Lu'] = 'Luxembourg';
      
      // M
      $countriesArray['Mo'] = 'Macao';
      $countriesArray['Mk'] = 'Macedonia';
      $countriesArray['Mg'] = 'Madagascar';
      $countriesArray['My'] = 'Malaysia';
      $countriesArray['Mv'] = 'Maldives';
      $countriesArray['Ml'] = 'Mali';
      $countriesArray['Mt'] = 'Malta';
      $countriesArray['Mh'] = 'Marshall Islands';
      $countriesArray['Mq'] = 'Martinique';
      $countriesArray['Mu'] = 'Mauritius';
      $countriesArray['Mx'] = 'Mexico';
      $countriesArray['Fm'] = 'Micronesia, Federated States Of';
      $countriesArray['Md'] = 'Moldova';
      $countriesArray['Mn'] = 'Mongolia';
      $countriesArray['Ms'] = 'Montserrat';
      $countriesArray['Ma'] = 'Morocco';
      $countriesArray['Mz'] = 'Mozambique';
      
      // N
      $countriesArray['Na'] = 'Namibia';
      $countriesArray['Np'] = 'Nepal';
      $countriesArray['Nl'] = 'Netherlands';
      $countriesArray['An'] = 'Netherlands Antilles';
      $countriesArray['Nz'] = 'New Zealand';
      $countriesArray['Ni'] = 'Nicaragua';
      $countriesArray['Mp'] = 'Northern Mariana Islands';
      $countriesArray['No'] = 'Norway';
      
      // O
      $countriesArray['Om'] = 'Oman';
      
      // P
      $countriesArray['Pk'] = 'Pakistan';
      $countriesArray['Pw'] = 'Palau';
      $countriesArray['Ps'] = 'Palestine';
      $countriesArray['Pa'] = 'Panama';
      $countriesArray['Pg'] = 'Papua New Guinea';
      $countriesArray['Py'] = 'Paraguay';
      $countriesArray['Pe'] = 'Peru';
      $countriesArray['Ph'] = 'Philippines, Republic Of';
      $countriesArray['Pl'] = 'Poland';
      $countriesArray['Pt'] = 'Portugal';
      $countriesArray['Pr'] = 'Puerto Rico';
      
      // Q
      $countriesArray['Qa'] = 'Qatar';
      
      // R
      $countriesArray['Ro'] = 'Romania';
      $countriesArray['Ru'] = 'Russian Federation';
      $countriesArray['Rw'] = 'Rwanda';
      
      // S
      $countriesArray['Kn'] = 'Saint Kitts And Nevis';
      $countriesArray['Lc'] = 'Saint Lucia';
      $countriesArray['Vc'] = 'Saint Vincent And The Grendines';
      $countriesArray['Ws'] = 'Samoa';
      $countriesArray['Sa'] = 'Saudi Arabia';
      $countriesArray['Cs'] = 'Serbia And Montenegro';
      $countriesArray['Sc'] = 'Seychelles';
      $countriesArray['Sg'] = 'Singapore';
      $countriesArray['Sk'] = 'Slovakia';
      $countriesArray['Si'] = 'Slovenia';
      $countriesArray['Sb'] = 'Solomon Islands';
      $countriesArray['Za'] = 'South Africa';
      $countriesArray['Kr'] = 'South Korea';
      $countriesArray['Es'] = 'Spain';
      $countriesArray['Lk'] = 'Sri Lanka';
      $countriesArray['Sz'] = 'Swaziland';
      $countriesArray['Se'] = 'Sweden';

      $countriesArray['Ch'] = 'Switzerland';
      
      // T
      $countriesArray['Tw'] = 'Taiwan';
      $countriesArray['Tz'] = 'Tanzania, United Republic Of';
      $countriesArray['Th'] = 'Thailand';
      $countriesArray['Tg'] = 'Togo';
      $countriesArray['To'] = 'Tonga';
      $countriesArray['Tt'] = 'Trinidad And Tobago';
      $countriesArray['Tn'] = 'Tunisia';
      $countriesArray['Tr'] = 'Turkey';
      $countriesArray['Tm'] = 'Turkmenistan';
      $countriesArray['Tc'] = 'Turks And Caicos Islands';
      
      // U
      $countriesArray['Ug'] = 'Uganda';
      $countriesArray['Ua'] = 'Ukraine';
      $countriesArray['Ae'] = 'United Arab Emirates';
      $countriesArray['Gb'] = 'United Kingdom';
      $countriesArray['Us'] = 'United States Of America';
      $countriesArray['Uy'] = 'Uruguay';
      $countriesArray['Uz'] = 'Uzbekistan';
      
      // V
      $countriesArray['Vu'] = 'Vanuatu';
      $countriesArray['Ve'] = 'Venezuela';
      $countriesArray['Vn'] = 'Vietnam';
      $countriesArray['Vi'] = 'Virgin Islands, U.S.';
      
      // W, X, Y, Z
      $countriesArray['Ye'] = 'Yemen Arab Republic';
      $countriesArray['Zm'] = 'Zambia';
      
   return $countriesArray;
 }
function getTimeStamps() {
	$ts->cDay = strtotime(date('m/d/y'));
	$ts->lDay = strtotime(date('m/d/y'))-(24 * 60 * 60);
	$ts->prevWeek = strtotime(date('m/d/y'))-(7 * 24 * 60 * 60);
	$ts->cMonth = strtotime(date('m').'/01/'.date('y'));
	$ts->lMonth = strtotime((date('m')-1).'/01/'.date('y'));
	$ts->cYear = strtotime('01/01/'.date('y'));
	$ts->lYear = strtotime('01/01/'.(date('y')-1));

	return $ts;	
}
	function versionCheck($item) {
		$url = "www.joomlaxt.com";
		$req = "/index2.php?option=com_updatext&item=".$item;
		
		$fp = fsockopen ($url, 80, $errno, $errstr, 30);
		if (!$fp || $errno) return $errstr;

        @fputs($fp, "GET ".$req." HTTP/1.1\r\n");
        @fputs($fp, "HOST: ".$url."\r\n");
        @fputs($fp, "Connection: close\r\n\r\n");
		// read the body data
		$res = '';
		$headerdone = false;
		while (!feof($fp)) {
			$line = fgets ($fp, 1024);
			if (strcmp($line, "\r\n") == 0) {
			// read the header
			$headerdone = true;
			}
			else if ($headerdone)
			{
				// header has been read. now read the contents
				$res .= $line;
			}
		}	
		fclose ($fp);

		preg_match('/<#(.*)#>/i',$res, $ret);
		if (isset($ret[1])) {
			$parts = split(";", $ret[1]);
			$version = split("=",$parts[0]);
			$r->version = $version[1];
			$url = split("=",$parts[1]);
			$r->url = $url[1];
			return $r;
		}
	}

?>