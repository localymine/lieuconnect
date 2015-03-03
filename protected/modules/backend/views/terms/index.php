<?php
/* @var $this TermsController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Categories" . ' | ' . Yii::app()->name;

Common::register('jquery.min.js', 'pro', CClientScript::POS_HEAD);
?>

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-brush"></i>Categories<br /><small><?php echo join(' ● ', Yii::app()->params['show_in']) ?></small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-5">
        <div class="block">
            <div class="block-title">
                <h2><?php echo $form_title ?></h2>
                <?php if($update == 1): ?>
                <div class="block-options pull-right"><a href="<?php echo Yii::app()->createUrl('backend/terms') ?>" class="btn btn-sm btn-primary">Add New Category</a></div>
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
                                <label class="col-md-3 control-label"><?php echo BackendModule::t('Category name', $l) ?></label>
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
                <label class="col-md-3 control-label">Show in</label>
                <div class="col-md-9">
                    <?php echo $form->dropDownList($model_term_taxonomy, 'show_in', Yii::app()->params['show_in'], array('class' => 'form-control', 'size' => '1', 'options' => array($select_show_in => array('selected' => true)), 'disabled' => $update)) ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-md-3 control-label">Parent</label>
                <div class="col-md-9">
                    <?php
                        $this->widget('SelectionCategory', array(
                            'id' => 'TermTaxonomy_term_id',
                            'submit_name' => 'TermTaxonomy[term_id]',
                            'select' => $select,
                            'select_update' => $select_update,
                            'update' => $model->isNewRecord ? FALSE : TRUE,
                            'taxonomy' => 'category',
                            'show_in'  => $select_show_in,
                        ))
                    ?>
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
                <h2>List Categories</h2>
               
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tab-list-categories">
                        <?php $i=0; foreach (Yii::app()->params['show_in'] as $key => $value):
                            $active = ($key == 'major') ? 'active' : '';
                            $i++;
                            ?>
                        <li class="<?php echo $active ?>"><a href="#tab_<?php echo $key ?>" data-toggle="tab" rel="tooltip" data-original-title="<?php echo $value ?>" title="<?php echo $value ?>"><?php echo $value ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content list-categories">
                        <?php $i=0; foreach (Yii::app()->params['show_in'] as $key => $value):
                            $active = ($key == 'major') ? 'active' : '';
                            $i++;
                            ?>
                        <div class="tab-pane fade in <?php echo $active ?>" id="tab_<?php echo $key ?>">
                            <div class="block clearfix">
                                <div class="well well-sm themed-background-modern"><strong><?php echo $value ?></strong></div> 
                                <div class="table-responsive">
                                <?php 
                                $this->widget('ListCategory', array(
                                    'num' => $i,
                                    'taxonomy' => 'category',
                                    'show_in' => $key,
                                    'url_edit' => '/backend/terms/update/',
                                    'url_delete' => '/backend/terms/delete/',
                                )) ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function(){
    
    var update = <?php echo $update ?>;
    var ajaxloader = '<div id="ajaxloader"><i class="ajaxloader fa fa-spinner fa-4x fa-spin text-info"></i></div>';
    var select = '{<?php echo json_encode($select) ?>}';
    
    $('#TermTaxonomy_show_in').change(function(){
        var cur_active_tab = '#tab_' + $(this).val();
        $('.tab-list-categories li').each(function(){
            $(this).removeClass('active');
            var active_tab = $(this).children().attr('href');
            if (cur_active_tab == active_tab){
                $(this).addClass('active');
            }
        });
        $('.list-categories > div').each(function(){
            $(this).removeClass('active');
            var active_tab = '#' + $(this).attr('id');
            if (cur_active_tab == active_tab){
                $(cur_active_tab).addClass('active in');
            }
        });
        //
        var data = { show_in : $(this).val(), update: update, select: select, ajax: true };
        $.ajax({
            type : "POST",
            url : '<?php echo Yii::app()->createUrl('backend/terms/loadTerms') ?>',
            data : data,
            beforeSend: function() {
                $('body').append(ajaxloader);
            },
            success: function(html){
                if (html == 0){
                    alert("Can't load data");
                } else{
                    $('#TermTaxonomy_term_id').html(html);
                    $('#ajaxloader').remove();
                    $('#TermTaxonomy_term_id').addClass('form-control-focus');
                }
            }
        });
    });
    
    $('#TermTaxonomy_term_id').focus(function(){
        $(this).removeClass('form-control-focus');
    });
    
    $("[rel='tooltip']").tooltip();
});
</script>