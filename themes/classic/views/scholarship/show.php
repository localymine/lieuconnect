<?php
$this->pageTitle = $post->post_title . ' - ' . Common::t('Lilely Connect');
//$avatar = ($post->post_by->image != '') ? $post->post_by->image : 'avatar.png';
$school_logo = ($post->school_logo != '') ? $post->school_logo : '0.jpg';
$image_entry = ($post->image != '') ? $post->image : 'no-image.png';

$share_url = Yii::app()->createUrl(Yii::app()->params['siteUrl'] . '/scholarship/show', array('slug' => $post->slug));
$title = $post->post_title;
$image_url = Yii::app()->params['siteUrl'] . '/images/scholarship/' . $image_entry;

Common::register_js(Yii::app()->theme->baseUrl . '/js/fix-scroll.js', CClientScript::POS_END);
?>

<div class="main-frame">
    <div class="feature-readlater">
        <a class="btn-readlater read" data-id="<?php echo $post->id ?>"><span></span><?php echo Common::t('Read Later', 'post') ?></a>
    </div>
    <div class="feature-social-bottom">
        <?php if (Yii::app()->user->isGuest): ?>
            <a href="<?php echo Yii::app()->createUrl('account/signup') ?>" class="btn-lilely"></a>
        <?php endif; ?>
        <?php
        $this->widget('SocialNetwork', array(
            'type' => 'fixed-share-bottom',
            'data_href' => $share_url,
            'title' => $title,
            'image_url' => $image_url,
        ));
        ?>
    </div>
    <?php
    $this->widget('SocialNetwork', array(
        'type' => 'fixed-share-left',
        'data_href' => $share_url,
        'title' => $title,
        'image_url' => $image_url,
    ));
    ?>
    <div class="main scholarship-result">
        <div class="content-col col-md-8 col-sm-12 col-xs-12 no-padding ">
            <div class="col-md-3 col-sm-3 col-xs-3 no-padding ">
                <img width="125" src="<?php echo Yii::app()->baseUrl ?>/images/school-logo/<?php echo $school_logo ?>" class="img-responsive" />
            </div>
            <div class="col-md-9 col-sm-9 col-xs-9 no-padding result-detail">
                <h2><?php echo $post->post_title ?></h2>
                <div class="content">
                    <!--<p><img src="<?php // echo Yii::app()->baseUrl ?>/images/scholarship/<?php // echo $image_entry ?>" class="img-responsive" /></p>-->
                    <?php echo $post->post_content ?>
                </div>
                <p><span class="award"><?php echo Common::t('Award', 'post') ?>: </span><?php echo $post->award ?></p>
                <p><span class="deadline"><?php echo Common::t('Deadline', 'post') ?>: </span><?php echo Post::item_alias('deadline', $post->deadline_string) ?> <?php echo '(' . Common::date_format($post->post_date, 'M d, Y') . ')' ?></p>
                
                <?php $this->widget('LoginForm', array('type' => 'apply-button', 'title' => Common::t('Apply', 'account'), 'post_id' => $post->id)) ?>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12 no-padding our-picks-frame">
            <div class="ourPicks">
                <!-- story -->
                <h2><?php echo Common::t('Our Picks') ?></h2>
                <?php foreach ($model_our_picks as $row): ?>
                    <div class="outpick-item">
                        <p>
                            <a href="<?php echo Yii::app()->createUrl('scholarship/show', array('slug' => $row->post->slug)) ?>"                                <img src="<?php echo Yii::app()->baseUrl ?>/images/scholarship/<?php echo $row->post->image ?>" class="img-responsive">
                                <?php echo $row->post->post_title ?>
                            </a>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</div>

<?php $this->widget('MoreStuff') ?>

<?php $this->widget('LoginForm', array('type' => 'modal-login')) ?>
<?php $this->widget('LoginForm', array('type' => 'modal-recruit', 'title' => Common::t('Apply', 'account'))) ?>

<?php
$url_read_later = Yii::app()->createUrl('read');
$mess_1 = Common::t('Added into Read Later list');
$mess_2 = Common::t('You have to Login In');
?>
<?php
$script = <<< EOD
$(function() {
    $('.read').each(function(event) {
        $(this).css('cursor', 'pointer');
        var postid = $(this).data('id');
       
        $(this).click(function() {
            $.ajax({
                url: '$url_read_later',
                type: 'POST',
                data: {id: postid},
                dataType: 'json',
                beforeSend: function() {
                    loader.start();
                },
                success: function(msg) {
                    if (msg == 1) {
                        alert('$mess_1');
                    } else if (msg == -1) {
                        $('input:checkbox').removeAttr('checked');
                        alert('$mess_2');
                    }
                    loader.stop();
                },
                fail: function() {
                    loader.stop();
                }
            });
        });
    });
});
EOD;
?>

<?php
Yii::app()->clientScript->registerScript('read-later-' . rand(), $script, CClientScript::POS_END);
?>

<?php
$script = <<< EOD
$(function() {
    $('#apply').click(function(e) {
        e.preventDefault();
        $('.signup-popup').modal();
    });
});
EOD;
?>

<?php
Yii::app()->clientScript->registerScript('scroll-show-' . rand(), $script, CClientScript::POS_END);
?>