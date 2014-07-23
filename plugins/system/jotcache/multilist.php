<?php
/*
 * @version $Id: multilist.php,v 1.2 2010/11/27 12:02:50 Vlado Exp $
 * @package JotCache
 * @copyright (C) 2010 Vladimir Kanich
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
class JElementMultiList extends JElement {
var $_name = 'MultiList';
function fetchElement($name, $value, &$node, $control_name) {
$ctrl = $control_name . '[' . $name . ']';
$options = array();
foreach ($node->children() as $option) {
$val = $option->attributes('value');
$text = $option->data();
$options[] = JHTML::_('select.option', $val, JText::_($text));
}$attribs = ' ';
if ($v = $node->attributes('size')) {
$attribs .= 'size="' . $v . '"';
}if ($v = $node->attributes('class')) {
$attribs .= 'class="' . $v . '"';
} else {
$attribs .= 'class="inputbox"';
}if ($m = $node->attributes('multiple')) {
$attribs .= ' multiple="multiple"';
$ctrl .= '[]';
}return JHTML::_('select.genericlist', $options, $ctrl, $attribs, 'value', 'text', $value, $control_name . $name);
}}?>
