<div class="feature social-left">
    <a id="ref_fb" href="http://www.facebook.com/sharer.php?s=100&amp;p[title]=<?php echo $title; ?>&amp;p[summary]=<?php echo $description; ?>&amp;p[url]=<?php echo urlencode($share_url); ?>&amp;
       p[images][0]=<?php echo $image_url; ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600');
               return false;"><img src="<?php echo Yii::app()->theme->baseUrl ?>/img/fb-share.png" alt=""/></a>
    <a id="ref_tw" href="http://twitter.com/home?status=<?php echo $title; ?>+<?php echo urlencode($share_url); ?>" onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=no,scrollbars=no,height=400,width=600');
            return false;"><img src="<?php echo Yii::app()->theme->baseUrl ?>/img/tw-share.png" alt=""/></a>
</div>

<?php
$script = <<< EOD
$(function() {
    $('.social-left a').hover(function() {
        $(this).toggleClass('boxshadow');
    });
    
    $(window).on('resize', function() {
        var left = ($(this).width() - 1130) / 2;
        $('.social-left').css('left', left + 'px');
    })
    
    $(window).resize();
});
EOD;
?>

<?php
Yii::app()->clientScript->registerScript('social-fixed-left-' . rand(), $script, CClientScript::POS_END);
?>