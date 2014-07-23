<?php
  ////////////////////////////////////////////////////////
  // Алгоритм импорта в Virtuemart 1.1.x				//
  // 2005 (C) Выскорко М.С. (aspid02@ngs.ru)			//
  // Компонент для Joomla 1.5.x Алгоритм экспорта		//
  // 2010 (C) Ребров О.В.   (admin@webplaneta.com.ua)	//
  ////////////////////////////////////////////////////////
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once( JApplicationHelper::getPath( 'toolbar_html' ) );

switch($task)
{
	case 'import':
		TOOLBAR_myimport::_IMPORT();
		break;
	case 'export':
		TOOLBAR_myimport::_EXPORT();
		break;
	case 'about':
		TOOLBAR_myimport::_ABOUT();
		break;
 
	default:
		TOOLBAR_myimport::_DEFAULT();
		break;
}

?>