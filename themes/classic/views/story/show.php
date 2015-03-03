<?php
$this->pageTitle = Common::t('Lilely Connect');

$avatar = ($post->post_by->image != '') ? $post->post_by->image : 'avatar.png';
$image_entry = ($post->image != '') ? $post->image : 'no-image.png';

$share_url = Yii::app()->createUrl(Yii::app()->params['siteUrl'] . '/story/show', array('slug' => $post->slug));
$title = $post->post_title;
$image_url = Yii::app()->params['siteUrl'] . '/images/story/' . $image_entry;

Common::register_js(Yii::app()->theme->baseUrl . '/js/fix-scroll.js', CClientScript::POS_END);
?>

<div class="main-frame">
    <div class="feature">
        <span class="lilely-btn no-clickable"><?php echo Common::t('Lilely Connect') ?></span>
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
        <div class="content-col col-md-8 col-sm-12 col-xs-12 no-padding">
            <div class="col-md-3 col-sm-3 col-xs-3 no-padding ">
                <img width="125" src="<?php echo Yii::app()->baseUrl ?>/avatars/<?php echo $avatar ?>" class="img-responsive" />
            </div>
            <div class="col-md-9 col-sm-9 col-xs-9 no-padding result-detail">
                <h2><?php echo $post->post_title ?></h2>
                <div class="content">
                    <!--<p><img src="<?php // echo Yii::app()->baseUrl ?>/images/story/<?php // echo $image_entry ?>" class="img-responsive" /></p>-->
                    <?php // echo $post->post_excerpt ?>
                    <!--<div class="quote_author"><?php // echo $post->quote_author ?></div>-->
                    <?php echo $post->post_content ?>
                </div>
                <?php // if($post->provided_by != ''): ?>
                <!--<p><span class="credit"><?php // echo Common::t('Credit', 'post') ?>: </span><?php // echo $post->provided_by ?></p>-->
                <?php // endif; ?>
                <p>
                    <span class="quote-author-tag">
                        <?php
                            $tags = TermRelationships::model()->get_relate_terms($post->id, 'tag')->findAll();
                            $arr_tags = NULL;
                            if ($tags != NULL){
                                foreach ($tags as $tag) {
                                    $t_tag = $tag->termtaxonomy->terms->localized($this->lang)->name;
                                    $arr_tags[] = '#' . $t_tag;
                                }
                            }
                        ?>
                        <?php echo isset($arr_tags) ? join($arr_tags, ', ') : '' ?>
                    </span>
                </p>
            </div>
        </div>
        <div class="col-md-4 col-sm-12 col-xs-12 no-padding our-picks-frame">
            <div class="ourPicks">
                <!-- story -->
                <h2><?php echo Common::t('Our Picks') ?></h2>
                <?php foreach($model_our_picks as $row): ?>
                <div class="outpick-item">
                    <p>
                        <a href="<?php echo Yii::app()->createUrl('story/show', array('slug' => $row->post->slug)) ?>">
                            <img src="<?php echo Yii::app()->baseUrl ?>/images/story/<?php echo $row->post->image ?>" class="img-responsive">
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