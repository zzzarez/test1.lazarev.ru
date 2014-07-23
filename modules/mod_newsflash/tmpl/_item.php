<?php // no direct access
defined('_JEXEC') or die('Restricted access'); ?>

<div class="contentnewsdate">
    <?php echo JHTML::_('date', $item->created, '%d.%m.%Y'); ?><b style="font-size: 24px;margin-left: 7px;line-height: 11px;color:#145588;">&bull;</b>
</div>
<div class="contentnewslink">
    <a href="/index.php?option=com_content&view=article&id=<?php echo $item->id; ?>&catid=17:news&Itemid=35" class="contentpagetitlenews">
        <?php echo $item->title;?>
    </a>
</div>
