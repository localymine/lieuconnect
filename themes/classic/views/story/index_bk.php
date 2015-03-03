<?php

$this->pageTitle = Common::t('Lilely Connect');

?>
<h1>1 newest story</h1>
<h2><?php echo $model->post_title ?></h2>
<div>
    <?php echo $model->post_content ?>
</div>

<h1>Our picks</h1>
<ol>
    <?php foreach ($model_our_picks as $row): ?>
        <li>
            <a href="<?php echo Yii::app()->createUrl('story/show/', array('slug' => $row->post->slug)) ?>"><?php echo $row->post->post_title ?></a> -
            <?php echo $row->post->post_excerpt ?>
        </li>
    <?php endforeach; ?>
</ol>