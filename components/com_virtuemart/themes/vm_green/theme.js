/**
 * This file holds javscript functions that are used by the templates in the Theme
 * 
 */
 
 // AJAX FUNCTIONS 
function loadNewPage( el, url ) {
	
	var theEl = $(el);
	var callback = {
		success : function(responseText) {
			theEl.innerHTML = responseText;
			if( Lightbox ) Lightbox.init();
		}
	}
	var opt = {
	    // Use POST
	    method: 'post',
	    // Handle successful response
	    onComplete: callback.success
    }
	new Ajax( url + '&only_page=1', opt ).request();
}

function handleGoToCart() { document.location = 'index.php?option=com_virtuemart&page=shop.cart&product_id=' + formCartAdd.product_id.value ; }

function handleAddToCart( formId, parameters,price ) {


	formCartAdd = document.getElementById( formId );

   // var qty = document.getElementById('quantity_hlp').value;
    var notice_lbl=parameters ;
    var ok_lbl='Продолжить покупки';
    var cart_title='Перейти в корзину';
	var callback = function(responseText) {
		updateMiniCarts();

        var colvo=(1+parseInt(document.getElementById('quantity_hlp').value))+"";
        var tp=(parseInt(document.getElementById('totalprice_hlp').value)+parseInt(price))+"";

        if(document.getElementById('quantity_hlp').value=="")
        {
            colvo= "1";
            tp=parseInt(price)+"";
        }
        var tov="товар";
        if((parseInt(colvo)>=2)&&(parseInt(colvo)<=4))
        {tov="товара";}

        if(parseInt(colvo)>=5)
        {tov="товаров";}


        var responseText='В вашей корзине <b>'+ colvo+'</b> '+tov+' <br>Предварительная сумма <b>'+tp+' р.</b>';
        document.boxB = new MooPrompt(notice_lbl, responseText, {
				buttons: 2,
				width:370,
				height:150,
				overlay: false,
				button1: ok_lbl,
				button2: cart_title,
                showCloseBtn: false,
				onButton2: 	handleGoToCart
			});

			
		//setTimeout( 'document.boxB.close()', 999999 );
	}
	
	var opt = {
	    // Use POST
	    method: 'post',
	    // Send this lovely data
	    data: $(formId),
	    // Handle successful response
	    onComplete: callback,
	    
	    evalScripts: true
	}

	new Ajax('index2.php?ajax_request=1', opt).request();
}
/**
* This function searches for all elements with the class name "vmCartModule" and
* updates them with the contents of the page "shop.basket_short" after a cart modification event
*/
function updateMiniCarts() {
	var callbackCart = function(responseText) {
		carts = $$( '.vmCartModule' );
		if( carts ) {
			try { 
				for (var i=0; i<carts.length; i++){
					carts[i].innerHTML = responseText;
					color = carts[i].getStyle( 'color' );
					bgcolor = carts[i].getStyle( 'background-color' );
					if( bgcolor == 'transparent' ) {
						// If the current element has no background color, it is transparent.
						// We can't make a highlight without knowing about the real background color,
						// so let's loop up to the next parent that has a BG Color
						parent = carts[i].getParent();
						while( parent && bgcolor == 'transparent' ) {
							bgcolor = parent.getStyle( 'background-color' );
							parent = parent.getParent();
						}
					}
					var fxc = new Fx.Style(carts[i], 'color', {duration: 1000});
					var fxbgc = new Fx.Style(carts[i], 'background-color', {duration: 1000});

					fxc.start( '#222', color );							
					fxbgc.start( '#fff68f', bgcolor );
					if( parent ) {
						setTimeout( "carts[" + i + "].setStyle( 'background-color', 'transparent' )", 1000 );
					}
				}
			} catch(e) {}
		}
	}
	option = { method: 'POST', onComplete: callbackCart }
	new Ajax('index2.php?only_page=1&page=shop.basket_short&option=com_virtuemart', option).request();
}
/**
* This function allows you to present contents of a URL in a really nice stylish dhtml Window
* It uses the WindowJS, so make sure you have called
* vmCommonHTML::loadWindowsJS();
* before
*/
function fancyPop( url, parameters ) {
	
	parameters = parameters || {};
	popTitle = parameters.title || '';
	popWidth = parameters.width || 700;
	popHeight = parameters.height || 600;
	popModal = parameters.modal || false;
	
	window_id = new Window('window_id', {className: "mac_os_x", 
										title: popTitle,
										showEffect: Element.show,
										hideEffect: Element.hide,
										width: popWidth, height: popHeight}); 
	window_id.setAjaxContent( url, {evalScripts:true}, true, popModal );
	window_id.setCookie('window_size');
	window_id.setDestroyOnClose();
}