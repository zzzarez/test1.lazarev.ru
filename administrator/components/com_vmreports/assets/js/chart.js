
/**
 * Componet for creating Fusion Charts XML source in graphic mode.
 * Can create, modified and deleting assorted Fusion Charts. Charts
 * can display on frontend page by module or content plugin.  
 *
 * @version		$Id$
 * @package     ArtioFusioncharts
 * @copyright	Copyright (C) 2010 ARTIO s.r.o.. All rights reserved. 
 * @author 		ARTIO s.r.o., http://www.artio.net
 * @license     GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link        http://www.artio.net Official website
 */

var Chart = {

	renderSingle : function() {
		var cols = this.getCharCols();
		this.render(cols);
	},

	renderMulti : function() {
		var cols = MultiSeries.getXML();
		this.render(cols);
	},

	render : function(cols) {
		var params = this.getParams();
		var width = this.quote(document.getElementById('width').value);
		var height = this.quote(document.getElementById('height').value);
		var lines = typeof Lines == 'undefined' ? '' : Lines.getXML();
		xml = '<' + CHART + ' ' + params + '>' + cols + ' ' + lines + '</'
				+ CHART + '>';
		chartObj.initialDataSet = false;
		if (width) {
			chartObj.setAttribute('width', width);
			chartObj.addVariable('chartWidth', width);
		}
		if (height) {
			chartObj.setAttribute('height', height);
			chartObj.addVariable('chartHeight', height);
		}
		chartObj.setDataXML(xml);
		chartObj.render("chartdiv");
	},

	getParams : function() {
		var data = "";
		var params = document.getElementsByTagName('input');
		for ( var i = 0; i < params.length; i++) {
			if (params[i].type != 'hidden') {
				var id = params[i].id;
				if (id.match('params') && params[i].value != '') {
					var name = id.replace(/(params)/ig, "");
					if (params[i].type == 'radio') {
						if (!params[i].checked)
							continue;
						name = name.replace(/(0)/ig, "");
						name = name.replace(/(1)/ig, "");
					}
					data += ' ' + name + "='" + this.quote(params[i].value)
							+ "'";
				}
			}
		}
		var params = document.getElementsByTagName('select');
		for ( var i = 0; i < params.length; i++) {
			var id = params[i].id;
			if (id.match('params')) {
				var name = id.replace(/(params)/ig, "");
				data += ' ' + name + "='" + this.quote(params[i].value) + "'";
			}
		}
		return data;
	},

	remove : function() {
		lastID = Chart.removeRow('item-checkbox-', this.getLastID(),
				'dataBody', 'items-values-row-', 'emptyItemsInfo');
		this.setLastID(lastID);
		this.renderSingle();
	},

	removeRow : function(checkboxID, lastID, bodyID, rowID, emptyInfoID) {
		var itemsCount = lastID;
		var countDeleted = 0;
		for ( var id = itemsCount; id > 0; id--) {
			var checkbox = document.getElementById(checkboxID + id);
			if (checkbox) {
				if (lastID == -1) {
					lastID = id;
				}
				if (checkbox.checked == true) {
					var dataBody = document.getElementById(bodyID);
					var row = document.getElementById(rowID + id);
					this.removeElement(dataBody, row);
					countDeleted++;
					if (id == lastID) {
						lastID = -1;
					}
				}
			}
		}
		if (lastID == -1) {
			lastID = 0;
		}
		if (lastID == 0) {
			info = document.getElementById(emptyInfoID);
			if (info)
				info.style.display = "table-row";
		}
		if (countDeleted == 0) {
			alert(lgNoCheckedToRemove);
		}
		return lastID;
	},

	removeElement : function(parent, child) {
		if (parent && child) {
			parent.removeChild(child);
		}
	},

	checkAll : function() {
		Chart.checkAllRows('items-check-all', 'item-checkbox-', this
				.getLastID());
	},

	checkAllRows : function(checkAllID, checkboxID, lastID) {
		var checkAll = document.getElementById(checkAllID);
		if (checkAll) {
			for ( var i = 1; i <= lastID; i++) {
				var checkbox = document.getElementById(checkboxID + i);
				if (checkbox) {
					checkbox.checked = checkAll.checked;
				}
			}
		}
	},
	
	getRowsCount : function() {
		var count = 0;
		var lastID = this.getLastID();
		for ( var i = 1; i <= lastID; i++) {
			var checkbox = document.getElementById('item-checkbox-' + i);
			if (checkbox) {
				count++;
			}
		}
		return count;
	},
	
	getColor : function(count) {
		var length = afcolors.length;
		var position = count % length;
		return afcolors[position];
	},

	addCol : function() {
		var lastID = this.getLastID();
		var dataBody = document.getElementById('dataBody');

		var lastRow = document.getElementById('items-values-row-' + lastID);
		lastID++;
		this.setLastID(lastID);

		var newRow = document.createElement('tr');
		newRow.id = 'items-values-row-' + lastID;
		if (lastRow)
			newRow.className = lastRow.className == "row1" ? "row0" : "row1";
		else
			newRow.className = "row0";

		var cols = new Array(addIsSliced ? 9: 8);

		cols[0] = document.createElement('td');
		cols[0].innerHTML = '<input type="checkbox" id="item-checkbox-'
				+ lastID + '" name="item-checkbox-' + lastID + '" value="1"/>';

		cols[1] = document.createElement('td');
		cols[1].innerHTML = '<input type="text" id="item-label-' + lastID + '" name="item-label[' + lastID + ']" value="" size="30" tabindex=""/>';

		cols[2] = document.createElement('td');
		cols[2].innerHTML = '<input type="text" id="item-value-' + lastID + '" name="item-value[' + lastID + ']" value="" size="10"/>';

		cols[3] = document.createElement('td');

		var collsCount = this.getRowsCount();
		
		var color = document.createElement('input')
		color.className = "color {pickerPosition:'top'}";
		color.id = 'item-color-' + lastID;
		color.name = 'item-color[' + lastID + ']';
		color.size = '7';
		color.value = this.getColor(collsCount);
		var pixer = new jscolor.color(color);
		pixer.pickerPosition = 'top';
		pixer.valueElement.value = color.value;
		cols[3].appendChild(color);

		cols[4] = document.createElement('td');
		cols[4].innerHTML = '<input type="text" id="item-htext-' + lastID + '" name="item-htext[' + lastID + ']" value="" size="30"/>';

		cols[5] = document.createElement('td');
		cols[5].innerHTML = '<input type="text" id="item-link-' + lastID + '" name="item-link[' + lastID + ']" value="" size="30"/>';

		cols[6] = document.createElement('td');
		cols[6].innerHTML = '<input type="text" id="item-alpha-' + lastID + '" name="item-alpha[' + lastID + ']" value="100" size="3" maxLength="3"/>';

		cols[7] = document.createElement('td');
		cols[7].innerHTML = '<input type="checkbox" id="item-show-' + lastID + '" name="item-show[' + lastID + ']" value="1" checked="checked"/>';
		
		if (addIsSliced){
			cols[8] = document.createElement('td');
			cols[8].innerHTML = '<input type="checkbox" id="item-isSliced-' + lastID + '" name="item-isSliced[' + lastID + ']" value="1"/>';
		}

		for ( var i = 0; i < cols.length; i++)
			newRow.appendChild(cols[i]);

		dataBody.appendChild(newRow);

		var ei = document.getElementById('emptyItemsInfo');
		if (ei) {
			ei.style.display = 'none';
		}
	},

	addNext : function(container, lastItem, nextItem) {
		if (container && lastItem && nextItem) {
			container.insertBefore(nextItem, lastItem.nextSibling);
			return true;
		}
		return false;
	},

	getLabelsRow : function() {
		return document.getElementById('items-labels-row');
	},

	getValuesRow : function() {
		return document.getElementById('items-values-row');
	},

	getCheckboxesRow : function() {
		return document.getElementById('items-checkboxes-row');
	},

	getItemLabel : function(id) {
		return document.getElementById('item-labels-cont-' + id);
	},

	getItemValue : function(id) {
		return document.getElementById('item-values-cont-' + id);
	},

	getItemCheckbox : function(id) {
		return document.getElementById('item-checkboxes-cont-' + id);
	},

	getItemLabelValue : function(id) {
		return document.getElementById('item-label-' + id);
	},

	getItemValueValue : function(id) {
		return document.getElementById('item-value-' + id);
	},

	getCharCols : function() {
		var lastID = this.getLastID();
		var result = '';
		for ( var i = 1; i <= lastID; i++) {
			var label = document.getElementById('item-label-' + i);
			var value = document.getElementById('item-value-' + i);
			if (label && value) {
				var color = document.getElementById('item-color-' + i);
				var htext = document.getElementById('item-htext-' + i);
				var link = document.getElementById('item-link-' + i);
				var alpha = document.getElementById('item-alpha-' + i);
				var show = document.getElementById('item-show-' + i).checked ? "1" : "0";
				if(addIsSliced){
					var isSliced = document.getElementById('item-isSliced-' + i).checked ? "1" : "0";
				}
				if (label != "" && value != "") {
					result += '<set name="' + this.quote(label.value) + '" value="' + value.value + '" ';
					if (color != ""){
						result += ' color="' + color.value + '" ';
					}
					if (htext != ""){
						result += ' hoverText="' + this.quote(htext.value) + '" ';
					}
					if (link != ""){
						result += ' link="' + this.quote(link.value) + '" ';
					}
					if (alpha != ""){
						result += ' alpha="' + alpha.value + '" ';
					}
					if (show != ""){
						result += ' showName="' + show + '" ';
					}
					if(addIsSliced && isSliced != ""){
						result += ' isSliced="' + isSliced + '" ';
					}
					result += '/>';
				}
			}
		}
		return result;
	},

	getLastID : function() {
		var lastID = this.loadLastID();
		return lastID ? lastID.value : 0;
	},

	setLastID : function(value) {
		var lastID = this.loadLastID();
		if (lastID) {
			lastID.value = value;
		}
	},

	loadLastID : function() {
		return document.getElementById('lastID');
	},

	changeType : function() {
		if (document.adminForm.type.value == "0") {
			alert(lgErrorMissType);
			return false;
		}
		if (confirm(lgChangeTypeConfirm)) {
			submitbutton('apply');
		}
	},

	insertChart : function(id, title, eName) {
		window.parent.jInsertEditorText(
				'{fusionchart id="' + id + '" ' + title + '}', eName);
		window.parent.document.getElementById('sbox-window').close();
		return false;
	},

	changeCharts : function() {
		var category = document.getElementById('category');
		var selectedCategory = document.getElementById('selectedCategory');
		var old = document.getElementById(selectedCategory.value);
		if (old) {
			old.style.display = 'none';
			old.name = '';
		}
		var newCharts = document.getElementById(category.value);
		if (newCharts) {
			newCharts.style.display = 'inline';
			newCharts.name = 'type';
			selectedCategory.value = category.value;
		}
	},

	quote : function(string) {
		string = string.split("'").join('&#039;');
		return string.split('"').join('&#034;');
	},

	unquote : function(string) {
		string = string.split('&#039;').join("'");
		return string.split('&#034;').join('"');
	},
	
	arraysMerge : function(array1, array2) {
		var output = new Array(array1.length + array2.length);
		for ( var i = 0; i < array1.length; i++) {
			output[i] = array1[i];
		}
		for ( var j = 0; j < array2.length; j++) {
			output[j + i] = array2[j];
		}
		return output;
	}
}