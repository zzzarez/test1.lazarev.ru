<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<h1 class="activation_comp_title">
	<?php echo $this->escape($this->message->title) ; ?>
</h1>

<div class="activation_comp">
	<?php echo $this->escape($this->message->text) ; ?>
</div>


<?php if($this->message->title==JText::_( 'REG_ACTIVATE_COMPLETE_TITLE' )): ?>
<div class="activation_comp_img">
<img src="/images/ac2.png">
</div>
<?php endif; ?>
