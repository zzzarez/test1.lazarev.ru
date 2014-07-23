
function timePresetChanged(flag) 
{
	//alert(document.getElementById('time_preset').value);
	if (document.getElementById('time_preset') == null) return;
	if (document.getElementById('time_preset').value == 'custom'){
		document.getElementById('start_date').disabled = false;
		document.getElementById('start_date_img').style.visibility = 'visible';
		document.getElementById('end_date').disabled = false;
		document.getElementById('end_date_img').style.visibility = 'visible';
	} else {
		document.getElementById('start_date').disabled = true;
		document.getElementById('start_date_img').style.visibility = 'hidden';
		document.getElementById('end_date').disabled = true;
		document.getElementById('end_date_img').style.visibility = 'hidden';

		var currentTime = new Date();
		
		switch (document.getElementById('time_preset').value){
		case 'last_24': 
			if (flag == true && document.getElementById('time_intervalhour')) document.getElementById('time_intervalhour').checked = true;
			var start = new Date (currentTime.getTime()-86400000);
			document.getElementById('start_date').value = dateFormat(start, false);
			document.getElementById('end_date').value = dateFormat(currentTime, false);
			document.getElementById('start_date_hid').value = dateFormat(start, false);
			document.getElementById('end_date_hid').value = dateFormat(currentTime, false);
			break;
		case 'last_week':
			if (flag == true && document.getElementById('time_intervalday')) document.getElementById('time_intervalday').checked = true;
			var start = new Date (currentTime.getTime()-604800000);
			document.getElementById('start_date').value = dateFormat(start, false);
			document.getElementById('end_date').value = dateFormat(currentTime, false);
			document.getElementById('start_date_hid').value = dateFormat(start, false);
			document.getElementById('end_date_hid').value = dateFormat(currentTime, false);
			break;
		case 'last_30':
			if (flag == true && document.getElementById('time_intervalweek')) document.getElementById('time_intervalweek').checked = true;
			var start = new Date (currentTime.getTime()-2592000000);
			document.getElementById('start_date').value = dateFormat(start, false);
			document.getElementById('end_date').value = dateFormat(currentTime, false);
			document.getElementById('start_date_hid').value = dateFormat(start, false);
			document.getElementById('end_date_hid').value = dateFormat(currentTime, false);
			break;
		case 'last_90':
			if (flag == true && document.getElementById('time_intervalweek')) document.getElementById('time_intervalweek').checked = true;
			var start = new Date (currentTime.getTime()-7776000000);
			document.getElementById('start_date').value = dateFormat(start, false);
			document.getElementById('end_date').value = dateFormat(currentTime, false);
			document.getElementById('start_date_hid').value = dateFormat(start, false);
			document.getElementById('end_date_hid').value = dateFormat(currentTime, false);
			break;
		case 'last_year':
			if (flag == true && document.getElementById('time_intervalmonth')) document.getElementById('time_intervalmonth').checked = true;
			document.getElementById('start_date').value = dateFormat(currentTime, true);
			document.getElementById('end_date').value = dateFormat(currentTime, false);
			document.getElementById('start_date_hid').value = dateFormat(currentTime, true);
			document.getElementById('end_date_hid').value = dateFormat(currentTime, false);
			break;
		}
	}
}

function dateFormat (date, flag)
{
	var year = date.getFullYear();
	var month = date.getMonth() + 1;
	var day = date.getDate();
	
	if (flag == true) year -= 1;
	if ( month.toString().length == 1 ) month = "0" + month;
	if ( day.toString().length == 1 ) day = "0" + day;
	
	return year + "-" + month + "-" + day;
}

function checkChanged (name) {
	avg_check = document.getElementById('check_avg_'+name);
	inp_check = document.getElementById('check_'+name);
	
	if (avg_check != null) {
		if (inp_check.checked) {
			avg_check.disabled = false;
		} else {
			avg_check.checked = false;
			avg_check.disabled = true;
		}
	}
	
}
