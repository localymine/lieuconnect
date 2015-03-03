<?php
/* @var $this TermsController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Feeling" . ' | ' . Yii::app()->name;

Common::register('jquery.min.js', 'pro', CClientScript::POS_HEAD);
?>

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-brush"></i>How I'm Feeling
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="block">
            <div class="block-title">
                <h2><?php echo $form_title  ?></h2>
                <?php if($update == 1): ?>
                <div class="block-options pull-right"><a href="<?php echo Yii::app()->createUrl('backend/feeling') ?>" class="btn btn-sm btn-primary">Add New</a></div>
                <?php endif; ?>
            </div>
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'feeling-form',
                'enableAjaxValidation' => false,
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data',
                    'class' => 'form-horizontal form-bordered'
                ),
            ));
            ?>
            <?php if ($form->errorSummary($model) != ''): ?>
                <div class="form-group">
                    <div class="col-md-12 text-danger">
                        <?php echo $form->errorSummary($model, ''); ?>
                    </div>
                </div>
            <?php endif; ?>

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
                                <label class="col-md-3 control-label">How I'm Feeling</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><img width="24" src="<?php echo Yii::app()->baseUrl ?>/images/flags/<?php echo $l ?>.png"/></span>
                                        <?php echo $form->textField($model, 'title' . $suffix, array('class' => 'form-control')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="form-group form-actions">
                <div class="col-md-9 col-md-offset-4">
                    <?php if ($model->isNewRecord): ?>
                        <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Submit</button>
                    <?php else: ?>
                        <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Update</button>
                    <?php endif; ?>
                    <button class="btn btn-sm btn-warning" type="reset"><i class="fa fa-repeat"></i> Reset</button>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
    <div class="col-md-7">
        <div class="block">
            <div class="block-title">
                <h2>List</h2>

                <div class="block clearfix">
                    <div class="table-responsive">
                        <table class="table table-vcenter table-striped">
                            <?php $result = $model->findAll(); ?>
                            <?php foreach ($result as $row):?>
                            <tr>
                                <td><?php echo $row->title ?></td>
                                <td align="right" class="block-options">
                                    <a class="edit btn btn-alt btn-sm btn-default btn-option" data-original-title="Edit" data-toggle="tooltip" href="<?php echo Yii::app()->createUrl('backend/feeling/update', array('id' => $row->feeling_id)) ?>"><i class="fa fa-pencil"></i></a> <a class="delete btn btn-alt btn-sm btn-danger btn-option" data-original-title="Delete" data-toggle="tooltip" href="<?php echo Yii::app()->createUrl('backend/feeling/delete', array('id' => $row->feeling_id)) ?>"><i class="fa fa-times"></i></a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>