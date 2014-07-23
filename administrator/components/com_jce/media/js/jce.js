/*  
 * JCE Editor                 2.1.3
 * @package                 JCE
 * @url                     http://www.joomlacontenteditor.net
 * @copyright               Copyright (C) 2006 - 2012 Ryan Demmer. All rights reserved
 * @license                 GNU/GPL Version 2 or later - http://www.gnu.org/licenses/gpl-2.0.html
 * @date                    19 May 2012
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * NOTE : Javascript files have been compressed for speed and can be uncompressed using http://jsbeautifier.org/
 */
(function($){$.jce={options:{},init:function(options){var self=this;$.extend(true,this.options,options);$('body').addClass('ui-jce');$('.tooltip, .hasTip').tips({parent:'#jce'});$('div.icon a').addClass('ui-widget-content ui-corner-all');$('a.dialog').click(function(e){self.createDialog({src:$(this).attr('href'),options:$(this).data('options'),modal:$(this).hasClass('modal'),type:/(users|help|preferences|updates|browser|legend)/.exec($(this).attr('class'))[0],title:$(this).attr('title')});e.preventDefault();});if(!$.support.cssFloat){$('#jce').addClass('ie');if(!window.XMLHttpRequest){$('#jce').addClass('ie6');$('input:text').addClass('ie_input_text');$('ul.adminformlist > li, dl.adminformlist > dd').addClass('ie_adminformlist');$('ul.adminformlist > li > label:first-child, ul.adminformlist > li > span:first-child, dl.adminformlist > dd > label:first-child, dl.adminformlist > dd > span:first-child').addClass('ie_adminformlist_child');}else{if(!document.querySelector){$('#jce').addClass('ie7');}}
if(!document.querySelector){$('button').addClass('ie_button');}else{if(!$.support.leadingWhitespace){$('#jce').addClass('ie8');}else{$('#jce').addClass('ie9');}}}
$('button#filter_go').button({icons:{primary:'ui-icon-search'}});$('button#filter_reset').button({icons:{primary:'ui-icon-arrowrefresh-1-e'}});$('div#jce tbody tr:odd').addClass('odd');this._formWidgets();},createDialog:function(o){var self=this;function _fixDialog(el,settings){if(parseFloat(el.style.height)==0){var h=settings.height;$(el).siblings('div').each(function(){h=h-parseFloat($(this).outerHeight());});h=h-$(el).outerHeight();$(el).css('height',h).dialog('option','position','center');}}
var buttons={};var div=document.createElement('div');var loader=document.createElement('div');var iframe=document.createElement('iframe');var title=o.title||'';if(o.type=='users'){$.extend(buttons,{'$select':function(){iframe.contentWindow.selectUsers();$(this).dialog("close");}});}
if(o.type=='preferences'){$.extend(buttons,{'$save':function(){iframe.contentWindow.submitform('apply');},'$saveclose':function(){iframe.contentWindow.submitform('save');}});}
var src=o.src,data={};if($.type(o.options)=='string'){data=$.parseJSON(o.options.replace(/'/g,'"'));}else{data=o.options;}
data=data||{};var settings={bgiframe:true,width:640,height:480,modal:o.modal||false,buttons:buttons,resizable:true,open:function(){_fixDialog(div,settings);$(loader).addClass('loader').appendTo(div);$(iframe).css({width:'100%',height:'100%'}).attr({src:src,scrolling:'auto',frameBorder:'no'}).appendTo(div).load(function(){$(loader).hide();});$('button').each(function(){var h=this.innerHTML;h=h.replace(/\$([a-z]+)/,function(a,b){return self.options.labels[b];});this.innerHTML=h;}).button();}};if(o.type=='confirm'||o.type=='alert'){var text=o.text||'';var title=o.title||(o.type=='alert'?self.options.labels.alert:'');$.extend(settings,{width:300,height:'auto',resizable:false,dialogClass:'ui-jce',buttons:{'$ok':function(){if(src){if(/function\([^\)]*\)\{/.test(src)){$.globalEval(src);}else{document.location.href=src;}}
$(this).dialog("close");}},open:function(){_fixDialog(div,settings);$(div).attr('id','dialog-confirm').append(text);$('button').each(function(){var h=this.innerHTML;h=h.replace(/\$([a-z]+)/,function(a,b){return self.options.labels[b];});this.innerHTML=h;}).addClass('ui-state-default ui-corner-all');},close:function(){$(this).dialog('destroy');}});if(o.type=='confirm'){$.extend(settings.buttons,{'$cancel':function(){$(this).dialog("close");}});}}
if(data.id){$(div).attr('id',data.id);}
$(div).css('overflow','hidden').attr('title',title).dialog($.extend(settings,data));},closeDialog:function(el){$(el).dialog("close").remove();},_passwordWidget:function(el){var span=document.createElement('span');$(span).addClass('widget-password locked').insertAfter(el).click(function(){el=$(this).siblings('input[type="password"]');if($(this).hasClass('locked')){var input=document.createElement('input');$(el).hide();$(input).attr({type:'text',size:$(el).attr('size'),value:$(el).val(),'class':$(el).attr('class')}).insertAfter(el).change(function(){$(el).val(this.value);});}else{var n=$(this).siblings('input[type="text"]');var v=$(n).val();$(n).remove();$(el).val(v).show();}
$(this).toggleClass('locked');});},_formWidgets:function(){var self=this;$('input[type="password"]').each(function(){self._passwordWidget(this);});$('input[placeholder]:not(:file), textarea[placeholder]').placeholder();$(':input[pattern]').pattern();$(':input[max]').max();$(':input[min]').min();}};})(jQuery);var $jce=jQuery.jce;