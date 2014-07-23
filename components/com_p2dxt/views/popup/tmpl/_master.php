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
// do the AJAX stuff
	JHTML::_( 'behavior.mootools' );
	if ($this->item->testmode == "1") {
	 	$paypal = "https://www.sandbox.paypal.com";
	}
	else {
	 	$paypal = "https://www.paypal.com";
	}
$paypal .= "/cgi-bin/webscr";
	
$id = $this->item->id;	
$redir = JText::_("You\'re being redirected to Paypal");


$ajax = "
window.addEvent( 'domready', function() {

        $('p2dPopup').addEvent( 'submit', function(e) { 
						new Event(e).stop();
		 				var url = 'index.php?option=com_p2dxt&controller=popup&tmpl=component&task=allchecks&format=json';
						var postString = '';
						for (var i = 0; i < this.elements.length; i++) {
							el = this.elements[i];
							if (el.type == 'text' || el.type == 'hidden' || (el.type == 'checkbox' && el.checked == true)) {
							postString = postString + '&' + el.id + '=' + el.value;

						}
						
						}
						 var a = new Ajax( url, {
		                        method: 'post',
		                        data: postString,
								onComplete: function(response) {
		                                var resp = Json.evaluate( response );
		 								if (resp=='true') {
		 									$('ajax').innerHTML='".$redir."';
											parent.document.forms.p2dform.action='".$paypal."';
											$('sbutton').display='hidden';
											parent.p2dsubmit();
		 									
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

// layout comes here
?>
<div style="margin:20px;">
<form id="p2dPopup" action="index.php?option=com_p2dxt&controller=popup&tmpl=component&task=allchecks&format=json" method="get" >
<input type="hidden" id="id" value="<?php echo $id;?>"/> 
<?php
$comps = P2DPro::getComps($this->conf, $this->item);
foreach ($comps as $c) {
	$tmpl = $this->loadTemplate($c);
	echo $tmpl;
}
?>
<br/>
<div id="ajax"></div><br/><br/>
<button class="button" type="submit" id="sbutton">Continue</button>
</form>
</div>