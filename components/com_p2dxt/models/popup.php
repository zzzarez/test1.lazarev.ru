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
jimport('joomla.application.component.model');

class p2dxtModelPopup extends JModel
{
	function getFieldCheck($field, $view, $comp, $args, $pfield=false, $pfunc = "") {
	 	$query = "";
	 	foreach ($args as $k=>$v) {
			$query .= "&".$k."=".$v;
		}
			
		if ($pfield && $pfunc == "") $pfunc = "parent.document.forms.p2dform.".$pfield.".value=document.getElementById('".$field."').value;";
$ajax = "
		window.addEvent( 'domready', function() {
		
		        $('".$field."').addEvent( 'blur', function() {
		 				var url = 'index.php?option=com_p2dxt&controller=popup&tmpl=component&task=fieldcheck&format=json&comp=".$comp."&ajaxview=".$view."&".$query."';
		                 var a = new Ajax( url, {
		                        method: 'get',
		                        onComplete: function( response ) {
		                                var resp = Json.evaluate( response );
		 								if (resp=='true') {
											".$pfunc."

		 									$('ajax').innerHTML='';
										}
		                                else {
											$('ajax').innerHTML=resp;
										}
		 
		                        }
		                }).request();
		        });
		});
		
";		
$doc = & JFactory::getDocument();
$doc->addScriptDeclaration( $ajax );
	}
	
	function getComps($conf, $item) {
	 	$comps=P2DPro::getComps($conf, $item);
		return $comps;
	}
}

?>