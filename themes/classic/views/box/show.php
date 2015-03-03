<?php
$this->pageTitle = $post->post_title . ' - ' . Common::t('Lilely Connect');
$avatar = ($post->post_by->image != '') ? $post->post_by->image : 'avatar.png';
$image_entry = ($post->image != '') ? $post->image : 'no-image.png';

$share_url = Yii::app()->createUrl(Yii::app()->params['siteUrl'] . '/scholarship/show', array('slug' => $post->slug));
$title = $post->post_title;
$image_url = Yii::app()->params['siteUrl'] . '/images/scholarship/' . $image_entry;
?>

<div class="main-frame">
    <div class="feature-social-bottom">
        <?php if (Yii::app()->user->isGuest): ?>
            <a href="<?php echo Yii::app()->createUrl('account/signup') ?>" class="btn-lilely"></a>
        <?php endif; ?>
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
        <div class="col-md-12 col-sm-12 col-xs-12 no-padding ">
            <div class="col-md-3 col-sm-3 col-xs-3 no-padding ">
                <a href="<?php echo Yii::app()->createUrl('story/curator', array('id' => $post->post_author)) ?>">
                <img width="125" src="<?php echo Yii::app()->baseUrl ?>/avatars/<?php echo $avatar ?>" class="img-responsive" />
                    <div class="text-left"><?php echo $post->post_user->username ?></div>
                </a>
            </div>
            <div class="col-md-9 col-sm-9 col-xs-9 no-padding result-detail">
                <h2><?php echo $post->post_title ?></h2>
                <p><img src="<?php echo Yii::app()->baseUrl ?>/images/scholarship/<?php echo $image_entry ?>" class="img-responsive" /></p>
                <div class="content">
                    <?php echo $post->post_content ?>
                </div>
                <p><span class="award"><?php echo Common::t('Award', 'post') ?>: </span><?php echo $post->award ?></p>
                <p><span class="deadline"><?php echo Common::t('Deadline', 'post') ?>: </span><?php echo Post::item_alias('deadline', $post->deadline_string) ?> <?php echo '(' . Common::date_format($post->post_date, 'M d, Y') . ')' ?></p>
            </div>
        </div>
    </div>
</div>

<?php
$url_read_later = Yii::app()->createUrl('box/readLater');
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