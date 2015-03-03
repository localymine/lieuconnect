<?php
$this->pageTitle = Common::t('Lilely Connect');

Common::register_js(Yii::app()->theme->baseUrl . '/js/scroll.jquery.js', CClientScript::POS_END);
?>

<div class="main-frame">
    <div class="feature">
        <div class="dropdown">
            <a class="lilely-btn" data-toggle="dropdown" href="#"><?php echo Common::t('Lilely Connect') ?><span class="collapse-expand"></span></a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                <li>
                    <a class="center-block <?php echo ($group == '') ? 'active' : '' ?>" href="<?php echo Yii::app()->createAbsoluteUrl('story') ?>"><?php echo Common::t('All', 'account') ?></a>
                </li>
                <?php foreach (Post::item_alias('story-group') as $key => $value): ?>
                <li>
                    <a class="center-block <?php echo ($group == $key) ? 'active' : '' ?>" href="<?php echo Yii::app()->createAbsoluteUrl('story', array('group' => $key)) ?>"><?php echo Common::t($value, 'post') ?></a>
                </li>
                <?php endforeach; ?>
                <li>
                    <a class="center-block" href="<?php echo Yii::app()->createUrl('story/our_curators') ?>"><?php echo Common::t('Our Curators', 'post') ?></a>
                </li>
            </ul>
        </div>
    </div>
    <div id="infinite-scroll-story" class="main">
        <?php if ($model_story != '') $this->renderPartial('_story', array('posts' => $model_story), false); ?>
    </div>
</div>

<?php
if ($group != '') {
    $post_url = Yii::app()->createUrl('story', array('group' => $group));
} else {
    $post_url = Yii::app()->createUrl('story');
}
$script = <<< EOD
$(function() {
    $('#infinite-scroll-story').scrollLoad({
        url: '$post_url',
        getData: function(){
            //you can post some data along with ajax request
        },
        start: function(){
            loader.start();
        },
        ScrollAfterHeight : 95,			//this is the height in percentage after which ajax stars
        onload: function(html){
            $(this).append(html);
            // reload social button
            FB.XFBML.parse(); // For Facebook button.
            twttr.widgets.load(); // For Twitter button.
            // gapi.plusone.go(); // For Google plus button.
            //
            loader.stop();
        },
        continueWhile : function( resp ) {
            if( $(this).children('div').length >= 200 ) { // stops when number of 'div' reaches 100
                return false;
            }
            return true; 
        }
    });
});
EOD;
?>

<?php
Yii::app()->clientScript->registerScript('view-more-' . rand(), $script, CClientScript::POS_END);
?>