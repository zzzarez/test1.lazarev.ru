var AJAXupdater = {

		showProductsSelectDialog : function (name) 
		{
			document.getElementById(name+'Dialog').style.visibility = 'visible';
			//document.getElementById(name+'FilterButton').focus();
		},

		hideProductsSelectDialog : function (name) 
		{
			document.getElementById(name+'Dialog').style.visibility = 'hidden';
		},

		AJAXfilter : function (name, url)
		{
			var params;
			url += "&filter=" + document.getElementById(name+'Filter').value + "&name=" + name;
			AUtility.ajaxSend (url, AJAXupdater.AJAXupdate, name);
		},

		AJAXupdate : function  (response, name)
		{
			document.getElementById(name+'AJAXdiv').innerHTML = response
		},
		
		addProduct : function (id, product, name, remove_title)
		{
			document.getElementById(name+'NoDataDiv').style.visibility = 'hidden';

			var input_name = name + "_" + product + "_" + id;
			
			var tr = document.createElement('tr');
			tr.id = input_name+'_tr';
			var td1 = document.createElement('td');
			var td2 = document.createElement('td');
			td2.setAttribute("class", "product_delete_link");
			td2.align = 'right';
			td1.innerHTML = product;
			tr.appendChild(td1);
			tr.appendChild(td2);
			
			var delete_link = document.createElement ('a');
			delete_link.onclick = function () {AJAXupdater.deleteProduct(id, product, name)};
			delete_link.innerHTML = remove_title;
			td2.appendChild(delete_link);
			
			var input = document.createElement('input');
			input.type = 'hidden';
			input.name = name+"["+id+"]";
			input.value = product;
			td1.appendChild(input);

			document.getElementById(name+'SelectedProducts').appendChild(tr);
		},
		
		deleteProduct : function (id, product, name)
		{
			var input_name = name + "_" + product + "_" + id;
			var tab = document.getElementById (name + "SelectedProducts");
						
			for(var i=0; i<tab.rows.length; i++) {
				if (tab.rows[i].id == input_name+"_tr"){
					tab.deleteRow(i);
					break;
				}
			}
			
			if (tab.rows.length == 0) {
				document.getElementById(name+'NoDataDiv').style.visibility = 'visible';
			}
		}
}
