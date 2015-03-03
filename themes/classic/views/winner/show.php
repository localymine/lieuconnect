<?php
$this->pageTitle = Common::t('Lilely Connect');
$image_entry = ($post->image != '') ? $post->image : 'no-image.png';

$share_url = Yii::app()->createUrl(Yii::app()->params['siteUrl'] . '/winner/show', array('slug' => $post->slug));
$title = $post->post_title;
$image_url = Yii::app()->params['siteUrl'] . '/images/winner/' . $image_entry;
?>

<div class="main-frame">
    <div class="feature">
        <span class="lilely-btn no-clickable student-btn"><?php echo Common::t('Student of the Month Scholarship Winner', 'post') ?></span>
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
        <div class="col-md-12 col-sm-12 col-xs-12 no-padding ">
	        <div class="col-md-5 col-xs-5 no-padding">
	            <img src="<?php echo Yii::app()->baseUrl ?>/images/winner/<?php echo $image_entry ?>" class="img-responsive">
	        </div>	
            <div class="col-md-7 col-xs-7 no-padding">
	    	<h2><?php echo $post->post_title ?></h2>
                <div class="content">
                    <p><?php echo $post->post_content ?></p>
                </div>		
	    </div>
        </div>
    </div>
</div>

<?php $this->widget('MoreStuff') ?>