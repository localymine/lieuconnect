<div class="feature">
    <span class="lilely-btn no-clickable student-btn"><?php echo Common::t('Student of the Month Scholarship Winner', 'post') ?></span>
</div>
<div class="main scholarship">
    <?php if(isset($posts) && $posts != NULL): ?>
    <div class="col-md-8 col-xs-12 no-padding">
        <div class="col-md-5 col-xs-5 no-padding">
            <img src="<?php echo Yii::app()->baseUrl ?>/images/winner/<?php echo $posts[0]->image ?>" class="img-responsive">
        </div>
        <div class="col-md-7 col-xs-7 winner-detail">
            <h2><a href="<?php echo Yii::app()->createUrl('winner/show', array('slug' => $posts[0]->slug)) ?>"><?php echo $posts[0]->post_title; ?></a></h2>
            <p class="reward" ><?php echo Post::item_alias('month', $posts[0]->winner_in_month) ?> (<?php echo Common::t('Winner', 'post') ?>)</p>
            <p>
                <?php if (trim($posts[0]->post_excerpt) != ''): ?>
                <?php echo $posts[0]->post_excerpt ?>
                <?php else: ?>
                <?php echo mb_substr(strip_tags($posts[0]->post_content), 1, 240)?>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <div class="col-md-4 col-xs-12 no-padding">
        <ul class="winner-list">
            <?php for ($i = 1; $i < count($posts); $i++): ?>
            <?php $row = $posts[$i]; ?>
            <li>
                <div class="col-md-3 col-xs-4 no-padding">
                    <img src="<?php echo Yii::app()->baseUrl ?>/images/winner/<?php echo $row->image ?>" class="img-responsive">
                </div>
                <div class="col-md-9 col-xs-8 no-padding">
                    <h2><a href="<?php echo Yii::app()->createUrl('winner/show', array('slug' => $row->slug)) ?>"><?php echo $row->post_title ?></a></h2>
                    <p class="reward" ><?php echo Post::item_alias('month', $row->winner_in_month) ?> (<?php echo Common::t('Winner', 'post') ?>)</p>
                </div>
            </li>
            <?php endfor; ?>
        </ul>
    </div>
    <?php endif; ?>
</div>