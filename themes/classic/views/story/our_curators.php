<?php
$this->pageTitle = Common::t('Curator', 'post') . Common::t('Lilely Connect');

Common::register_js(Yii::app()->theme->baseUrl . '/js/scroll.jquery.js', CClientScript::POS_END);
?>

<div class="main-frame">
    <div class="feature">
        <span class="lilely-btn no-clickable"><?php echo Common::t('Our Curators', 'post') ?></span>
    </div>
    <div class="main our-curator">
        <div class="row">
            <?php $this->renderPartial('_our_curators_post', array('model_user' => $model_user), false); ?>
        </div>
    </div>
</div>


<?php
$post_url = Yii::app()->createUrl('story/our_curators');

$script = <<< EOD
$(function() {
   
   $('.our-curator').scrollLoad({
        url: '$post_url',
        getData: function(){
            //you can post some data along with ajax request
        },
        start: function(){
            loader.start();
        },
        ScrollAfterHeight : 95,			//this is the height in percentage after which ajax stars
        onload: function(html){
            $(this).children('.row').append(html);
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
Yii::app()->clientScript->registerScript('view-more-curators' . rand(), $script, CClientScript::POS_END);
?>