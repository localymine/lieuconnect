<?php
/* @var $this TermsController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = ($update == 0) ? ("Add College" . ' | ' . Yii::app()->name) : ("Update College" . ' | ' . Yii::app()->name);

Common::register('textext.core.css', 'pro/js/jqtextext');
Common::register('textext.plugin.tags.css', 'pro/js/jqtextext');
Common::register('textext.plugin.autocomplete.css', 'pro/js/jqtextext');
Common::register('textext.plugin.focus.css', 'pro/js/jqtextext');
Common::register('textext.plugin.prompt.css', 'pro/js/jqtextext');
Common::register('textext.plugin.arrow.css', 'pro/js/jqtextext');

Common::register('textext.core.js', 'pro/js/jqtextext', CClientScript::POS_HEAD);
Common::register('textext.plugin.tags.js', 'pro/js/jqtextext', CClientScript::POS_HEAD);
Common::register('textext.plugin.autocomplete.js', 'pro/js/jqtextext', CClientScript::POS_HEAD);
Common::register('textext.plugin.suggestions.js', 'pro/js/jqtextext', CClientScript::POS_HEAD);
Common::register('textext.plugin.filter.js', 'pro/js/jqtextext', CClientScript::POS_HEAD);
Common::register('textext.plugin.focus.js', 'pro/js/jqtextext', CClientScript::POS_HEAD);
Common::register('textext.plugin.prompt.js', 'pro/js/jqtextext', CClientScript::POS_HEAD);
Common::register('textext.plugin.ajax.js', 'pro/js/jqtextext', CClientScript::POS_HEAD);
Common::register('textext.plugin.arrow.js', 'pro/js/jqtextext', CClientScript::POS_HEAD);
?>

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-brush"></i>College<br /><small>...</small>
        </h1>
    </div>
</div>

<div class="row">

    <div class="col-md-8">
        <div class="block">
            <div class="block-title">
                <h2><?php echo $form_title; ?></h2>
                <?php if ($update == 1): ?>
                    <div class="block-options pull-right"><a href="<?php echo Yii::app()->createUrl('backend/post/addCollege') ?>" class="btn btn-sm btn-primary">Add New</a></div>
                <?php endif; ?>
            </div>

            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'college-form',
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
                                <label class="col-md-3 control-label">Slug</label>
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <span class="input-group-addon"><img width="24" src="<?php echo Yii::app()->baseUrl ?>/images/flags/<?php echo $l ?>.png"/></span>
                                        <?php echo $form->textField($model, 'slug' . $suffix, array('class' => 'form-control', 'form' => 'college-form', 'id' => 'slug-' . $lang)) ?>
                                        <span class="input-group-addon"><a class="clear-slug hand" data-slug-id="<?php echo 'slug-' . $lang ?>"><i class="fa fa-times text-danger"></i></a></span>
                                    </div>
                                    <div class="help-block">Auto generate slug when empty</div>
                                </div>
                            </div>

                            <!--
                            <div class="form-group">
                                <label class="col-md-3 control-label">Annual Tuition</label>
                                <div class="col-md-9">
                                    <?php echo $form->textField($model, 'award' . $suffix, array('class' => 'form-control')) ?>
                                </div>
                            </div>
                            -->
                            
                            <div class="form-group">
                                <label class="control-label col-md-offset-2">Content</label>
                                <div class="col-md-12">
                                    <?php echo $form->textArea($model, 'post_content' . $suffix, array('class' => 'form-control custom_editor')) ?>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="control-label col-md-offset-2">Excerpt</label>
                                <div class="col-md-12">
                                    <?php echo $form->textArea($model, 'post_excerpt' . $suffix, array('class' => 'form-control basic_editor')) ?>
                                </div>
                            </div>
                            
                            <!--
                            <div class="form-group">
                                <label class="col-md-3 control-label">Image</label>
                                <div class="col-xs-5">
                                    <?php
                                    echo $form->fileField($model, 'image' . $suffix, array('class' => ''))
                                    ?>
                                </div>
                                <div class="col-md-4">
                                    <?php echo CHtml::image(Yii::app()->baseUrl . '/images/college/' . $model->image, '', array('width' => '52')) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Feature Image</label>
                                <div class="col-xs-5">
                                    <?php
                                    echo $form->fileField($model, 'feature_image' . $suffix, array('class' => ''))
                                    ?>
                                </div>
                                <div class="col-md-4">
                                    <?php echo CHtml::image(Yii::app()->baseUrl . '/images/college/' . $model->feature_image, '', array('width' => '52')) ?>
                                </div>
                            </div>
                            -->

                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
            
            <div class="form-group">
                <label class="col-md-3 control-label">Application Fee</label>
                <div class="col-md-9">
                    <?php echo $form->textField($model, 'application_fee', array('class' => 'form-control', 'form' => 'college-form')) ?>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-md-3 control-label">Out-of-State Tutition</label>
                <div class="col-md-9">
                    <?php echo $form->textField($model, 'out_state_tutition', array('class' => 'form-control', 'form' => 'college-form')) ?>
                </div>
            </div>
            
            <div class="form-group">
                <label class="col-md-3 control-label">Total Aid</label>
                <div class="col-md-9">
                    <?php echo $form->textField($model, 'give_out_tutition', array('class' => 'form-control', 'form' => 'college-form')) ?>
                </div>
                <div class="col-md-offset-3 col-md-9">
                    <div class="form-group">
                        <div class="col-md-6">
                            Aid Rank <?php echo $form->dropDownList($model, 'contract_type', Post::item_alias('contract-type'), array('class' => 'form-control', 'form' => 'college-form')) ?>
                        </div>
                        <div class="col-md-6">
                            Aid Awards <?php echo $form->textField($model, 'contract_number', array('class' => 'form-control', 'form' => 'college-form')) ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php $this->endWidget(); ?>
        </div>
    </div>

    <div class="col-md-4">
        
        <div class="block form-horizontal clearfix">
            <div class="form-group">
                <div class="col-md-12">
                    <label class="col-md-2 control-label">School Logo</label>
                    <div class="col-md-8">
                        <input type="file" id="Post_school_logo" name="Post[school_logo]" class="" form="college-form">
                        <div class="help-block">The best image size (recommend): 150 x 150</div>
                    </div>
                    <div class="col-md-2">
                        <?php echo CHtml::image(Yii::app()->baseUrl . '/images/school-logo/' . $model->school_logo, '', array('width' => '52')) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="block form-horizontal clearfix">
            <div class="block-title">
                <h2>Total</h2>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon"><label>Student</label></span>
                        <input type="text" class="form-control" name="Post[total_student]" id="Post_total_student" form="college-form" value="<?php echo $model->total_student ?>" />
                        <span class="input-group-addon">&sum;&num;</span>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon"><label>International</label></span>
                        <input type="text" class="form-control" name="Post[total_international]" id="Post_total_international" form="college-form" value="<?php echo $model->total_international ?>" />
                        <span class="input-group-addon">
                            <?php echo $form->dropDownList($model, 'total_international_unit', Post::item_alias('cal-unit'), array('form' => 'college-form')) ?>
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon"><label>Asia</label></span>
                        <input type="text" class="form-control" name="Post[total_asia]" id="Post_total_asia" form="college-form" value="<?php echo $model->total_asia ?>" />
                        <span class="input-group-addon">&percnt;</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="block form-horizontal clearfix">
            <div class="block-title">
                <h2>Toefl Score</h2>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon"><label>Minimum</label></span>
                        <input type="text" class="form-control" name="Post[toefl_min]" id="Post_toefl_min" form="college-form" value="<?php echo $model->toefl_min ?>" />
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon"><label>Average</label></span>
                        <input type="text" class="form-control" name="Post[toefl_avg]" id="Post_toefl_avg" form="college-form" value="<?php echo $model->toefl_avg ?>" />
                    </div>
                </div>
            </div>
        </div>
        
        <div class="block form-horizontal clearfix">
            <div class="block-title">
                <h2>Admission Deadline for College</h2>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon"><label>Regular</label></span>
                        <?php echo $form->textField($model, 'regular_admission', array('class' => 'form-control input-datepicker', 'data-date-format' => 'yyyy-mm-dd', 'placeholder' => 'mm/dd/yy', 'form' => 'college-form')) ?>
                    </div>
                    <span class="help-block">Input with format (yyyy-mm-dd)</span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <span class="input-group-addon"><label>Notification</label></span>
                        <?php echo $form->textField($model, 'deadline', array('class' => 'form-control input-datepicker', 'data-date-format' => 'yyyy-mm-dd', 'placeholder' => 'mm/dd/yy', 'form' => 'college-form')) ?>
                    </div>
                    <span class="help-block">Input with format (yyyy-mm-dd)</span>
                </div>
                <div class="col-md-12">
                    <?php echo $form->dropDownList($model, 'deadline_string', Post::item_alias('deadline'), array('class' => 'form-control', 'form' => 'college-form', 'empty' => 'None')) ?>
                </div>
            </div>
        </div>

        <div class="block clearfix">
            <div class="block-title clearfix">
                <!--<h2>&nbsp;</h2>-->
                <div class="block-options pull-left"><a href="#tab_major" data-toggle="tab" class="btn btn-sm btn-success">Major</a></div>
                <div class="block-options pull-left"><a href="#tab_location" data-toggle="tab" class="btn btn-sm btn-warning active">Location</a></div>
                <div class="block-options pull-left"><a href="#tab_school_type" data-toggle="tab" class="btn btn-sm btn-danger active">School Type</a></div>
                <div class="block-options pull-left"><a href="#tab_school_funding" data-toggle="tab" class="btn btn-sm btn-c1 active">School Funding</a></div>
                <div class="block-options pull-left"><a href="#tab_admission_difficult" data-toggle="tab" class="btn btn-sm btn-c2 active">Admission Difficult</a></div>
                <div class="block-options pull-left"><a href="#tab_campus_setting" data-toggle="tab" class="btn btn-sm btn-primary active">Campus</a></div>
            </div>

            <div class="form-group">
                <div class="lst_category">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab_major">
                            <div class="well well-sm alert-success themed-color-night"><strong>Major</strong>
                                <?php
                                $this->widget('TreeCategory', array(
                                    'submit_form' => 'college-form',
                                    'submit_name' => 'Post[category][]',
                                    'select' => $select_categories,
                                    'taxonomy' => 'category',
                                    'show_in' => 'major',
                                    'multi_select' => true,
                                ))
                                ?>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="tab_location">
                            <div class="well well-sm alert-warning themed-color-night"><strong>Location</strong>
                                <?php
                                $this->widget('TreeCategory', array(
                                    'submit_form' => 'college-form',
                                    'submit_name' => 'Post[category][]',
                                    'select' => $select_categories,
                                    'taxonomy' => 'category',
                                    'show_in' => 'location',
                                ))
                                ?>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="tab_school_type">
                            <div class="well well-sm alert-danger themed-color-night"><strong>School Type</strong>
                                <?php
                                $this->widget('TreeCategory', array(
                                    'submit_form' => 'college-form',
                                    'submit_name' => 'Post[category][]',
                                    'select' => $select_categories,
                                    'taxonomy' => 'category',
                                    'show_in' => 'school-type',
                                ))
                                ?>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="tab_school_funding">
                            <div class="well well-sm alert-c1 themed-color-night"><strong>School Funding</strong>
                                <?php
                                $this->widget('TreeCategory', array(
                                    'submit_form' => 'college-form',
                                    'submit_name' => 'Post[category][]',
                                    'select' => $select_categories,
                                    'taxonomy' => 'category',
                                    'show_in' => 'school-funding',
                                ))
                                ?>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="tab_admission_difficult">
                            <div class="well well-sm alert-c2 themed-color-night"><strong>Admission Difficult</strong>
                                <?php
                                $this->widget('TreeCategory', array(
                                    'submit_form' => 'college-form',
                                    'submit_name' => 'Post[category][]',
                                    'select' => $select_categories,
                                    'taxonomy' => 'category',
                                    'show_in' => 'admission-difficult',
                                    'multi_select' => false,
                                ))
                                ?>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="tab_campus_setting">
                            <div class="well well-sm alert-primary themed-color-night"><strong>Campus</strong>
                                <?php
                                $this->widget('TreeFilter', array(
                                    'submit_form' => 'college-form',
                                    'submit_name' => 'Post[campus][]',
                                    'select' => $select_campus_setting,
                                    'filter' => 'campus-setting',
                                    'multi_select' => false,
                                ))
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="block clearfix">
            <div class="block-title">
                <h2>Enter tags</h2>
            </div>
            <div class="form-group">
                <div class="">
                    <input id="tags_form"/>
                </div>
            </div>
        </div>

        <div class="block">
            <div class="block-title">
<?php if (!$model->isNewRecord): ?><div class="block-options pull-right">Last Modified: <?php echo $model->post_modified ?></div><?php endif; ?>
                <h2>Publish</h2>
            </div>
            <div class="form-group form-actions">
                <div class="col-md-offset-4 col-xs-offset-4">
<?php echo CHtml::submitButton($model->isNewRecord ? 'Add New' : 'Update', array('class' => 'btn btn-sm btn-primary', 'form' => 'college-form')); ?>
                    <button class="btn btn-sm btn-warning" type="reset" form="college-form"><i class="fa fa-repeat"></i> Reset</button>
                </div>
            </div>
        </div>

    </div>
</div>

<?php
$script = <<< EOD
window.onload = function() {
        
    $('textarea.basic_editor').each(function(){
        CKEDITOR.replace($(this).attr('id') , {
            customConfig : 'config_basic.js'
        });
    });
    
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

<script>

    $('#tags_form').textext({
        plugins: 'tags prompt focus autocomplete ajax arrow',
        tagsItems: <?php echo $tags_json == NULL ? '' : $tags_json ?>,
        prompt: 'add a tag ...',
        html: {hidden: '<input id="Post_tags" name="Post[tags]" type="hidden" form="college-form" />'},
//        suggestions: ['',''], /* suggestions */
        ajax: {
            url: '<?php echo Yii::app()->createUrl('backend/post/loadTags') ?>',
            dataType: 'json',
            cacheResults: true
        }
    }).bind('isTagAllowed', function(e, data) {

        var formData = $(e.target).siblings('input#Post_tags').val();
        list = eval(formData);

        if (formData.length && list.indexOf(data.tag) >= 0) {
//            var message = [ data.tag, 'is already listed.' ].join(' ');
//            alert(message);
            data.result = false;
        } else {
            var lnhRegex = /^([a-zA-Z0-9 _-]+)$/;
            if ((data.tag).match(lnhRegex)) {
                // valid
                data.result = true;
            } else {
                // invalid
                data.result = false;
            }
        }
    });

    $('.clear-slug').click(function() {
        var id = $(this).data('slug-id');
        $('#' + id).val("");
    });

</script>