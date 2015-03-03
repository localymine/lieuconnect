<?php
/* @var $this ScholarshipController */

$this->pageTitle = Common::t('Scholarship', 'post');
?>

<!-- search form -->
<?php $this->renderPartial('../search/scholarship/_form_search', array('model' => $model, 'select_major' => $select_major, 'select_location' => $select_location)) ?>
<!-- / search form -->

<div class="main-frame ">
    <?php if ($model_winner != '') $this->renderPartial('_monthly_winner', array('posts' => $model_winner), false); ?>
</div>

<div class="main-frame">
    <div class="feature">
        <span class="lilely-btn no-clickable"><?php echo Common::t('New Scholarship', 'post') ?></span>
    </div>
    <div class="main new-scholarship">
        <?php $counter = 0; $line_break = 3; ?>
        
        <?php foreach ($model_scholarship as $row): ?>
        <?php if ($counter == 0): ?><div class="row"><?php endif; ?>
        <div class="col-md-4">
            <div>
                <h2><a href="<?php echo Yii::app()->createUrl('scholarship/show', array('slug' => $row->slug)) ?>"><?php echo $row->post_title ?></a></h2>
            </div>
        </div>
        <?php $counter++; ?>
        <?php if ($counter % $line_break == 0): ?><?php $counter = 0; ?></div><?php endif; ?>
        <?php endforeach; ?>
        
    </div>
</div>

<?php $this->widget('MoreStuff') ?>