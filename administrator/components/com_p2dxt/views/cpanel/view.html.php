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
jimport('joomla.application.component.view');
/**
 * @package		Joomla.Administrator
 * @subpackage	com_content
 */
class p2dxtViewCpanel extends JView
{

	public function display($tpl = null)
	{
//	JHTML::_('behavior.mootools');
	P2DPro::cPanelTitle();

	JToolBarHelper::customX("editSettings", "config", "config", JText::_("Edit Settings"), false);
	JToolBarHelper::customX("listItems", "edit", "edit", JText::_("Edit Items"), false);
	JToolBarHelper::customX("txlist", "preview", "preview", JText::_("Transactions"), false);



	$database = JFactory::getDBO();
	$conf = new p2dxt($database);
	$conf->load("1");

	jimport('joomla.html.pane');
 	$pane = &JPane::getInstance('sliders', array('allowAllClose' => false, 'opacityTransition'=>true));

 	echo $pane->startPane("cPanelPane");

	
	$this->assignRef('conf',$conf);

//	echo p2dPro::dashboard($pane);

//	parent::display($tpl);		
//	parent::display("version");		
	$default = $this->loadTemplate("default");
	$version = $this->loadTemplate("version");
	$addinfo = $this->loadTemplate("addinfo");
	$this->assignRef('default',$default);
	$this->assignRef('version',$version);
	$this->assignRef('addinfo',$addinfo);


	// Get data from the model
 	$items =& $this->get('List');	
 	$pagination =& $this->get('Pagination');
	
	// push data into the template
	$this->assignRef('items',		$items);
	$this->assignRef('pagination', $pagination);

	$itemlist = $this->loadTemplate("items");
	$this->assignRef('itemlist',$itemlist);
	

	$banner = P2Dpro::banner();
	if ($banner) {
		$this->assignRef('banner',$banner);
		$banner = $this->loadTemplate("banner");
	}
	
	$lists = p2dPro::dashboard($pane);
	if ($lists) {
		$this->assignRef('lists',$lists);
		$dashboard = $this->loadTemplate("dashboard");
		$this->assignRef('dashboard',$dashboard);
	}
	
	parent::display("master");		


	echo $pane->endPane();
	}

}