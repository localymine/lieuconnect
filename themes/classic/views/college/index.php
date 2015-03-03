<?php
/* @var $this ScholarshipController */

$this->pageTitle = Common::t('College', 'post');
?>

<!-- search form -->
<?php $this->renderPartial('../search/college/_form_search', array('model' => $model, 'select_major' => $select_major, 'select_location' => $select_location)) ?>
<!-- / search form -->

<div class="main-frame">
    <div class="feature">
        <span class="lilely-btn no-clickable"><?php echo Common::t('Popular Colleges', 'post') ?></span>
    </div>
    <div class="main college-main">

        <?php foreach ($model_college as $row): ?>
            <div class="col-md-12 no-padding college-item">
                <div class="img-frame no-padding">
                    <?php $school_logo = ($row->school_logo != '') ? $row->school_logo : '0.jpg'; ?>
                    <img src="<?php echo Yii::app()->baseUrl ?>/images/school-logo/<?php echo $school_logo ?>" class="img-responsive" />
                </div>
                <div class="content-frame">
                    <h2 class="hidden-xs hidden-sm"><a href="<?php echo Yii::app()->createUrl('college/show', array('slug' => $row->slug)) ?>"><?php echo $row->post_title ?></a></h2>
                    <div class="college-row visible-sm visible-xs">
                        <h2><a href="<?php echo Yii::app()->createUrl('college/show', array('slug' => $row->slug)) ?>"><?php echo $row->post_title ?></a></h2>
                        <?php $this->widget('LoginForm', array('type' => 'apply-button', 'title' => Common::t('Contact School Now', 'account'), 'post_id' => $row->id)) ?>
                    </div>
                    <p class="reward">
                        <?php echo Common::strip_truncate($row->post_content, 1000) ?>
                    </p>
                </div>
                <div class="button-frame no-padding">
                    <?php $this->widget('LoginForm', array('type' => 'apply-button', 'title' => Common::t('Contact School Now', 'account'), 'post_id' => $row->id)) ?>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</div>

<?php $this->widget('LoginForm', array('type' => 'modal-login')) ?>
<?php $this->widget('LoginForm', array('type' => 'modal-recruit', 'title' => Common::t('Apply', 'account'))) ?>