window.addEvent('domready', function(){
	var sliders = new Array();
	var contents = $$(".p2dupgrade");
	var handle = "p2dupgrade";
	var field = "row[upgrade]1";
	sliders = init(handle, contents, field, sliders, "horizontal");
	var contents = $$(".p2ddonation");
	var handle = "p2ddonation";
	var field = "row[donation]1";
	sliders = init(handle, contents, field, sliders, "vertical");
	var contents = $$(".p2dpayment");
	var handle = "p2ddonation";
	var field = "row[donation]0";
	sliders = init(handle, contents, field, sliders, "vertical");
	var contents = $$(".paid");
	var handle = "paid";
	var field = "row[article]1";
	sliders = init(handle, contents, field, sliders, "horizontal");
	
});

function init(handle, contents, field, sliders, direction) {
	var i = sliders.length+1;
	var handle = $(handle);
	contents.each(function(el) {
		sliders[i] = new Fx.Slide(el);	
		if ($(field).checked)
			sliders[i].show();
		else
			sliders[i].slideOut(direction);
		i++;
	})
 
  handle.addEvent("change", function(e){
   	sliders.each(function(slider){
		slider.toggle(direction);	
	})
  });
return sliders;
}