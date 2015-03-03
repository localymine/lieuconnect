<?php
/* @var $this TermsController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = ($update == 0) ? ("Add New Pages" . ' | ' . Yii::app()->name) : ("Update Pages" . ' | ' . Yii::app()->name);
?>

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-brush"></i>Pages<br /><small>...</small>
        </h1>
    </div>
</div>

<div class="row">

    <div class="col-md-8">
        <div class="block">
            <div class="block-title">
                <h2><?php echo $form_title; ?></h2>
                <?php if ($update == 1): ?>
                    <div class="block-options pull-right"><a href="<?php echo Yii::app()->createUrl('backend/page/create') ?>" class="btn btn-sm btn-primary">Add New</a></div>
                <?php endif; ?>
            </div>

            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'page-form',
                'enableAjaxValidation' => false,
                'htmlOptions' => array(
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                    'class' => 'form-horizontal form-bordered'
                ),
            ));
            ?>

            <div class="form-group">
                <div class="col-md-12">
                    <?php echo $form->errorSummary($model); ?>
                </div>
            </div>

            <div class="form-group">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs">
                    <?php
                    foreach (Yii::app()->params['translatedLanguages'] as $l => $lang):
                        if ($l === Yii::app()->params['defaultLanguage']) {
                            $suffix = '';
                            $active = ' active';
                        } else {
                            $suffix = '_' . $l;
                            $active = '';
                        }
                        ?>
                        <li class="<?php echo $active ?>"><a href="#<?php echo $lang ?>" data-toggle="tab"><img src="<?php echo Yii::app()->baseUrl ?>/images/flags/<?php echo $l ?>.png"/></a></li>
                    <?php endforeach; ?>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <?php
                    foreach (Yii::app()->params['translatedLanguages'] as $l => $lang):
                        if ($l === Yii::app()->params['defaultLanguage']) {
                            $suffix = '';
                            $active = ' active';
                        } else {
                            $active = '';
                            $suffix = '_' . $l;
                        }
                        ?>
                        <div class="tab-pane fade in <?php echo $active ?>" id="<?php echo $lang; ?>">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Title</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><img width="24" src="<?php echo Yii::app()->baseUrl ?>/images/flags/<?php echo $l ?>.png"/></span>
                                        <?php echo $form->textField($model, 'post_title' . $suffix, array('class' => 'form-control')) ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-offset-2">Content or Video Attachment</label>
                                <div class="col-md-12">
                                    <?php echo $form->textArea($model, 'post_content' . $suffix, array('class' => 'form-control custom_editor')) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                </div>

                <?php $this->endWidget(); ?>

            </div>



        </div>
    </div>

    <div class="col-md-4">

        <div class="block clearfix">
            <div class="block-title">
                <h2>Page Type</h2>
            </div>
            <div class="form-group">
                <?php echo $form->dropDownList($model, 'post_type', Page::item_alias('pages'), array('class' => 'form-control', 'form' => 'page-form')) ?>
            </div>
        </div>

        <div class="block">
            <div class="block-title">
                <?php if (!$model->isNewRecord): ?><div class="block-options pull-right">Last Modified: <?php echo $model->post_modified ?></div><?php endif; ?>
                <h2>Publish</h2>
            </div>
            <div class="form-group form-actions">
                <div class="col-md-offset-4 col-xs-offset-4">
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Add New' : 'Update', array('class' => 'btn btn-sm btn-primary', 'form' => 'page-form')); ?>
                    <button class="btn btn-sm btn-warning" type="reset" form="stories-form"><i class="fa fa-repeat"></i> Reset</button>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
$script = <<< EOD
window.onload = function() {
    
    $('textarea.custom_editor').each(function(){
        CKEDITOR.replace($(this).attr('id'));
    });
     
//    editor.setData( '<p>Hello world!</p>' );
};
        
var offset = 300;
$(window).scroll(function() {
    if ($(this).scrollTop() > offset) {
        $('.nav.nav-tabs').addClass('fix-lang');
    } else {
        $('.nav.nav-tabs').removeClass('fix-lang');
    }
});
EOD;

Common::register_script('input-form', $script);
?>