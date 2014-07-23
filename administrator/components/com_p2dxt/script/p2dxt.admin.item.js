window.addEvent( 'domready', function() {
var pro = new Array("upgrade", "article");
var inc = new Array("donation");

	pro.each(function(e) {
		submitAjax(e, true);	
		if ($("row["+e+"]1")) {
		$("row["+e+"]1").addEvent("click", function(f){
			submitAjax(e, true, true);
		});
		
		$("row["+e+"]0").addEvent("click", function(f){
			submitAjax(e, true, true);
		});
		}
	})
	
	inc.each(function(e) {
		submitAjax(e);	
		$("row"+e).addEvent("change", function(f){
			submitAjax(e, false, true);
		});
	})
	

});		 				

function submitAjax(task) {
 	if (arguments.length >= 2 && arguments[1] ) var pro = "pro"
 	else var pro = "";
 	if (arguments.length >= 3 && arguments[2] ) var adjustHeight = true;
 	else var adjustHeight = false;
 	
	var id = $("row[id]").value;
	var url = 'index.php?option=com_p2dxt&controller=ajax&tmpl=component&task=ajaxitem&comp='+pro+task+'&format=json&id='+id;
/*	if ($("row["+task+"]1").checked) var postString = "&"+pro+task+"=1"
	else var postString = "&"+pro+task+"=0";*/
	var item = $("row"+task);
	if (item) {
		var x = item.children[item.selectedIndex].value;
		var postString = "&"+pro+task+"="+x;
	}
	else {
	 	
		if (($("row["+task+"]1")) && $("row["+task+"]1").checked) var postString = "&"+pro+task+"=1"
		else var postString = "&"+pro+task+"=0";
	}
		
	var a = new Ajax( url, {
	    method: 'post',
 		data: postString,
 		onComplete: function(response) {
		 	var resp = Json.evaluate( response );
			replace_html($(task+'Content'), resp);
			if (adjustHeight) {
			 var e = $(task).nextElementSibling;
			 e.setStyle("height", e.scrollHeight);
			}
	 		}
 		}
	).request();
	
}

function replace_html(el, html) {
	if( el ) {
                var oldEl = (typeof el === "string" ? document.getElementById(el) : el);
                var newEl = document.createElement(oldEl.nodeName);

                // Preserve any properties we care about (id and class in this example)
                newEl.id = oldEl.id;
                newEl.className = oldEl.className;
                newEl.colSpan = oldEl.colSpan;
                newEl.width = oldEl.width;
                //set the new HTML and insert back into the DOM
                newEl.innerHTML = html;
                if(oldEl.parentNode)
        	        oldEl.parentNode.replaceChild(newEl, oldEl);
                else
		        oldEl.innerHTML = html;

                //return a reference to the new element in case we need it
                return newEl;
	}
};
