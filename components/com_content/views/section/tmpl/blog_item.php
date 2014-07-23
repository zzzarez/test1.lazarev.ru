<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>
<?php $canEdit   = ($this->user->authorize('com_content', 'edit', 'content', 'all') || $this->user->authorize('com_content', 'edit', 'content', 'own')); ?>
<?php if ($this->item->state == 0) : ?>
<div class="system-unpublished">
<?php endif; ?>

<?php if ($this->item->params->get('show_title') || $this->item->params->get('show_pdf_icon') || $this->item->params->get('show_print_icon') || $this->item->params->get('show_email_icon') || $canEdit) : ?>
    <div class="menu_news_wrap">
    <div class="menu_news_date">
        <?php echo JHTML::_('date', $this->item->created, JText::_('DATE_FORMAT_LC')); ?>
        <b style="font-size: 24px;margin-left: 7px;line-height: 11px;color:#145588;">•</b>
    </div>
    <div class="menu_news_link">
        <a href="/index.php?option=com_content&view=article&id=<?php echo $this->item->id; ?>&catid=17:news&Itemid=35" class="contentpagetitlenews">
            <?php echo $this->escape($this->item->title);?>
        </a>

    </div>
    </div>
<!--<table class="contentpaneopen--><?php //echo $this->escape($this->item->params->get( 'pageclass_sfx' )); ?><!--">-->
<!--<tr>-->
<!--	--><?php //if ($this->item->params->get('show_title')) : ?>
<!--	<td class="contentheading--><?php //echo $this->escape($this->item->params->get( 'pageclass_sfx' )); ?><!--" width="100%">-->
<!--		--><?php //if ($this->item->params->get('link_titles') && $this->item->readmore_link != '') : ?>
<!--		<a href="--><?php //echo $this->item->readmore_link; ?><!--" class="contentpagetitle--><?php //echo $this->escape($this->item->params->get( 'pageclass_sfx' )); ?><!--">-->
<!--			--><?php //echo $this->escape($this->item->title); ?><!--</a>-->
<!--		--><?php //else : ?>
<!--			--><?php //echo $this->escape($this->item->title); ?>
<!--		--><?php //endif; ?>
<!--	</td>-->
<!--	--><?php //endif; ?>
<!---->
<!--	--><?php //if ($this->item->params->get('show_pdf_icon')) : ?>
<!--	<td align="right" width="100%" class="buttonheading">-->
<!--	--><?php //echo JHTML::_('icon.pdf', $this->item, $this->item->params, $this->access); ?>
<!--	</td>-->
<!--	--><?php //endif; ?>
<!---->
<!--	--><?php //if ( $this->item->params->get( 'show_print_icon' )) : ?>
<!--	<td align="right" width="100%" class="buttonheading">-->
<!--	--><?php //echo JHTML::_('icon.print_popup', $this->item, $this->item->params, $this->access); ?>
<!--	</td>-->
<!--	--><?php //endif; ?>
<!---->
<!--	--><?php //if ($this->item->params->get('show_email_icon')) : ?>
<!--	<td align="right" width="100%" class="buttonheading">-->
<!--	--><?php //echo JHTML::_('icon.email', $this->item, $this->item->params, $this->access); ?>
<!--	</td>-->
<!--	--><?php //endif; ?>
<!---->
<!--	--><?php //if ($canEdit) : ?>
<!--	   <td align="right" width="100%" class="buttonheading">-->
<!--	   --><?php //echo JHTML::_('icon.edit', $this->item, $this->item->params, $this->access); ?>
<!--	   </td>-->
<!--   --><?php //endif; ?>
<!---->
<!--</tr>-->
<!--</table>-->
<?php endif; ?>
<?php  if (!$this->item->params->get('show_intro')) :
	echo $this->item->event->afterDisplayTitle;
endif; ?>
<?php //echo $this->item->event->beforeDisplayContent;?>


<!--    <div class="newsinline">-->
<!--        <div class="contentnewsdate">-->
<!--            --><?php //echo JHTML::_('date', $this->item->created, JText::_('DATE_FORMAT_LC')); ?>
<!--        </div>-->
<!--        <div class="contentnewslink">-->
<!--            <a href="/index.php?option=com_content&view=article&id=--><?php //echo $this->item->id; ?><!--&catid=17:news&Itemid=35" class="contentpagetitlenews">-->
<!--                --><?php //echo $this->escape($this->item->title);?>
<!--            </a>-->
<!--        </div>-->
<!--    </div>-->


<?php
//fix!!! del big part of code here
if ($this->item->state == 0) : ?>
</div>
<?php endif; ?>
<span class="article_separator">&nbsp;</span>
<?php echo $this->item->event->afterDisplayContent; ?>
