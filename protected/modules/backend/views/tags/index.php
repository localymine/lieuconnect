<?php
/* @var $this TermsController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Tags" . ' | ' . Yii::app()->name;

Common::register('jquery.min.js', 'pro', CClientScript::POS_HEAD);
?>

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-brush"></i>Tags
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="block">
            <div class="block-title">
                <h2><?php echo $form_title ?></h2>
                <?php if($update == 1): ?>
                <div class="block-options pull-right"><a href="<?php echo Yii::app()->createUrl('backend/tags') ?>" class="btn btn-sm btn-primary">Add New Category</a></div>
                <?php endif; ?>
            </div>
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'terms-form',
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
                        if ($l === Yii::app()->params['defaultLanguage']){
                            $suffix = '';
                            $active = ' active';
                        }
                        else{
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
                                <label class="col-md-3 control-label"><?php echo BackendModule::t('Tag name', $l) ?></label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><img width="24" src="<?php echo Yii::app()->baseUrl ?>/images/flags/<?php echo $l ?>.png"/></span>
                                        <?php echo $form->textField($model, 'name' . $suffix, array('class' => 'form-control', 'placeholder' => '...')); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-md-3 control-label">Slug</label>
                                <div class="col-md-9">
                                    <?php echo $form->textField($model, 'slug' . $suffix, array('class' => 'form-control', 'placeholder' => '...', 'disabled' => false)); ?>
                                    <span class="help-block">The “slug” is the URL-friendly version of the name. It is usually all lowercase and contains only letters, numbers, and hyphens.</span>
                                </div>
                            </div>
                            
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-3 control-label">Description</label>
                <div class="col-md-9">
                    <?php echo $form->textArea($model_term_taxonomy, 'description', array('class' => 'form-control', 'rows' => 3)) ?>
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
                <h2>List Tags</h2>
                    
                    <div class="block clearfix">
                        <div class="table-responsive">
                        <?php 
                        $this->widget('ListCategory', array(
                            'taxonomy' => 'tag',
                            'show_in' => '',
                            'url_edit' => '/backend/tags/update/',
                            'url_delete' => '/backend/tags/delete/',
                        )) ?>
                        </div>
                    </div>
                       
            </div>
        </div>
    </div>
</div>