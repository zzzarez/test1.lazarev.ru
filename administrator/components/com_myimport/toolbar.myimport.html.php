<?php
  ////////////////////////////////////////////////////////
  // Алгоритм импорта в Virtuemart 1.1.x				//
  // 2005 (C) Выскорко М.С. (aspid02@ngs.ru)			//
  // Компонент для Joomla 1.5.x Алгоритм экспорта		//
  // 2010 (C) Ребров О.В.   (admin@webplaneta.com.ua)	//
  ////////////////////////////////////////////////////////
defined( '_JEXEC' ) or die( 'Restricted access' );

class TOOLBAR_myimport {
	function _EXPORT() {
		JToolBarHelper::title( JText::_( 'Экспорт' ), 'generic.png' );
		JToolBarHelper::back();
	}
	function _IMPORT() {
		JToolBarHelper::title( JText::_( 'Импорт' ), 'generic.png' );
		JToolBarHelper::back();
	}
	function _ABOUT() {
		JToolBarHelper::title( JText::_( 'О компоненте' ), 'generic.png' );
		JToolBarHelper::back();
	}

	function _DEFAULT() {
		JToolBarHelper::title( JText::_( 'Импорт/Експорт товаров' ), 'generic.png' );
		
}
	}

?> 
