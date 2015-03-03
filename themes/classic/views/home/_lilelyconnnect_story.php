<?php foreach ($posts as $row): ?>
    <li><a href="<?php echo Yii::app()->createUrl('story/show', array('slug' => $row->slug)) ?>"><?php echo $row->post_title ?></a></li>
<?php endforeach; ?>