<?php
 // No direct access
	defined( '_JEXEC' ) or die( 'Restricted access' );
 // Require the base controller
	jimport('joomla.application.component.controller');
	
	//class for controller
class AdvancedordermanagerController extends JController
{
    
		function display($tpl=null)
		{
			// sets the view to someview.html.php
				$view = & $this->getView( 'advancesearch', 'html' );
		
				$viewLayout = JRequest::getVar('tmpl', 'form' );
		
					$view->setLayout($viewLayout);	
					
					$view->display($tpl);
		}
	
	function Asearch()
	{
		$view = & $this->getView( 'advancesearch', 'html' );
		//$viewLayout = JRequest::getVar( 'tmpl', 'default' );
		//$view->setLayout($viewLayout);	
		if ($model = & $this->getModel( 'advancesearch' )) 
			$view->setModel( $model, true );
   
			$view->Avsearch();
	
	}
					
		function savesearchform($tpl=null)
		{
		
			$view = & $this->getView( 'advancesearch', 'html' );
		
			$viewLayout = JRequest::getVar( 'tmpl', 'saveform' );
		
			$view->setLayout($viewLayout);	
			
				$view->display2($tpl);
		}
		
		function savesearch()
		{
			$model =& $this->getModel('advancesearch');
 
			if ($model->store()) 
			{
				$msg = JText::_( 'Search Results Saved Successfully!');
			} 
			else
			{
				$msg = JText::_('Error Saving Search Results');
			}
 
   
			$link = 'index.php?option=com_advancedordermanager';
			$this->setRedirect($link, $msg);
		}
		
		//Function For delete Saved Results
			function del()
		{
			JRequest::setVar( 'view', 'advancesearch');
			JRequest::setVar('layout', 'delform');
			
		$model =& $this->getModel('advancesearch');
		$view = & $this->getView( 'advancesearch', 'html' );
		$view->setModel( $model, true );
		$view->delview();
		parent::display();
		
		}
		
		function remove()
		{
				$model = $this->getModel('advancesearch');
				if(!$model->delete()) {
				$msg = JText::_( 'Error: One or More  Record Could not be Deleted' );
				} 	
			else 
			{
				$msg = JText::_( 'Record Deleted' );
				}
 
			$this->setRedirect( 'index.php?option=com_advancedordermanager', $msg );
		}
   
		function exportsearch()
		{
			$model = & $this->getModel('advancesearch');
			if($model->exportMysqlToCsv()) 
			{
				$msg = JText::_( 'Record Exported successfully' );
				}
			
			$this->setRedirect( 'index.php?option=com_advancedordermanager', $msg );
   
   
		}
		
		
		
 
 }
