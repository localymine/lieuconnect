<?php
$this->pageTitle = Common::t('Curator', 'post') . Common::t('Lilely Connect');

Common::register_js(Yii::app()->theme->baseUrl . '/js/scroll.jquery.js', CClientScript::POS_END);

$avatar = ($model_user->profile->image != '') ? $model_user->profile->image : 'avatar.png';

$share_url = Yii::app()->createUrl(Yii::app()->params['siteUrl'] . '/story/curator', array('id' => $model_user->id));
$title = $model_user->username;
$image_url = Yii::app()->params['siteUrl'] . '/avatars/' . $avatar;
?>

<div class="main-frame">
    <div class="feature">
        <span class="lilely-btn no-clickable"><?php echo Common::t('Curators', 'post') ?></span>
    </div>
    <?php 
    $this->widget('SocialNetwork', array(
        'type' => 'fixed-share-left',
        'data_href' => $share_url,
        'title' => $title,
        'image_url' => $image_url,
    )); 
    ?>
    <div class="main curator">
        <div class="row">
            <div class="col-md-12 title-item">
                <div class="col-md-2 col-xs-2 no-padding">
                    <img src="<?php echo Yii::app()->baseUrl ?>/avatars/<?php echo $avatar ?>" class="img-responsive"/>
                </div>
                <div class="col-md-10 col-xs-10 winner-detail">
                    <h2><?php echo $model_user->username ?></h2>
                    <div id="social-wrapper">
                        <div class="accounts">
                            <?php
                            $this->widget('SocialNetwork', array(
                                'type' => 'twitter-share',
                                'data_href' => $share_url
                            ));
                            $this->widget('SocialNetwork', array(
                                'type' => 'facebook-share',
                                'fb_margin_top' => -1,
                                'data_href' => $share_url
                            ));
                            ?>
                        </div>
                    </div>
                    <div class="block clearfix" style="margin-top: 30px;">
                        <p><?php echo $model_user->profile->blast ?></p>
                    </div>
                </div>
            </div>

            <?php if ($model_story != '') $this->renderPartial('_curator_post', array('posts' => $model_story), false); ?>

        </div>
    </div>
</div>

<?php
$post_url = Yii::app()->createUrl('story/curator', array('id' => $id));

$script = <<< EOD
$(function() {
   
   $('.curator').scrollLoad({
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
        
//    $("[data-toggle='tooltip']").tooltip();
});
EOD;
?>

<?php
Yii::app()->clientScript->registerScript('view-more-' . rand(), $script, CClientScript::POS_END);
?>