<?php
/* @var $this TermsController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = ($update == 0) ? ("Add Internship" . ' | ' . Yii::app()->name) : ("Update Internship" . ' | ' . Yii::app()->name);

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
            <i class="gi gi-brush"></i>Internship<br /><small>...</small>
        </h1>
    </div>
</div>

<div class="row">

    <div class="col-md-8">
        <div class="block">
            <div class="block-title">
                <h2><?php echo $form_title; ?></h2>
                <?php if ($update == 1): ?>
                    <div class="block-options pull-right"><a href="<?php echo Yii::app()->createUrl('backend/post/addInternship') ?>" class="btn btn-sm btn-primary">Add New</a></div>
                <?php endif; ?>
            </div>

            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'internship-form',
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
                                        <?php echo $form->textField($model, 'slug' . $suffix, array('class' => 'form-control', 'form' => 'internship-form', 'id' => 'slug-' . $lang)) ?>
                                        <span class="input-group-addon"><a class="clear-slug hand" data-slug-id="<?php echo 'slug-' . $lang ?>"><i class="fa fa-times text-danger"></i></a></span>
                                    </div>
                                    <div class="help-block">Auto generate slug when empty</div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Provide by</label>
                                <div class="col-md-9">
                                    <?php echo $form->textField($model, 'provided_by' . $suffix, array('class' => 'form-control')) ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Job Title</label>
                                <div class="col-md-9">
                                    <?php echo $form->textField($model, 'job_title' . $suffix, array('class' => 'form-control')) ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Position</label>
                                <div class="col-md-9">
                                    <?php echo $form->textField($model, 'position' . $suffix, array('class' => 'form-control')) ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-3 control-label">Amount</label>
                                <div class="col-md-9">
                                    <?php echo $form->textField($model, 'award' . $suffix, array('class' => 'form-control')) ?>
                                </div>
                            </div>

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
                                <div class="col-xs-9">
                                    <?php
                                    echo $form->fileField($model, 'image' . $suffix, array('class' => ''))
                                    ?>
                                </div>
                            </div>
                            -->

                        </div>
                    <?php endforeach; ?>

                </div>

                <?php $this->endWidget(); ?>

            </div>
        </div>
    </div>

    <div class="col-md-4">
        
        <div class="block form-horizontal clearfix">
            <div class="form-group">
                <div class="col-md-12">
                    <label class="col-md-2 control-label">School Logo</label>
                    <div class="col-md-8">
                        <input type="file" id="Post_school_logo" name="Post[school_logo]" class="" form="internship-form">
                        <div class="help-block">The best image size (recommend): 150 x 150</div>
                    </div>
                    <div class="col-md-2">
                        <?php echo CHtml::image(Yii::app()->baseUrl . '/images/school-logo/' . $model->school_logo, '', array('width' => '52')) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="block clearfix">
            <div class="block-title clearfix">&nbsp;</div>
            <div class="form-group">
                <label class="checkbox-inline" for="Post_flag_credit">College Credit Required
                    <?php echo $form->checkBox($model, 'flag_credit', array('form' => 'internship-form')) ?>
                </label><br/>
                <label class="checkbox-inline" for="Post_flag_partime">Part Time
                    <?php echo $form->checkBox($model, 'flag_partime', array('form' => 'internship-form')) ?>
                </label><br/>
                <label class="checkbox-inline" for="Post_flag_fulltime">Full Time
                    <?php echo $form->checkBox($model, 'flag_fulltime', array('form' => 'internship-form')) ?>
                </label><br/>
                <h5>COMPENSATION</h5>
                <label class="checkbox-inline" for="Post_flag_paid">Unpaid/Paid
                    <?php echo $form->checkBox($model, 'flag_paid', array('form' => 'internship-form')) ?>
                </label>
            </div>
        </div>

        <div class="block clearfix">
            <div class="block-title">
                <h2>Deadline for Internship</h2>
            </div>
            <div class="form-group">
                <?php echo $form->textField($model, 'deadline', array('class' => 'form-control input-datepicker', 'data-date-format' => 'yyyy-mm-dd', 'placeholder' => 'mm/dd/yy', 'form' => 'internship-form')) ?>
                <span class="help-block">Input with format (yyyy-mm-dd)</span>
            </div>
            <div class="form-group">
                <?php echo $form->dropDownList($model, 'deadline_string', Post::item_alias('deadline'), array('class' => 'form-control', 'form' => 'internship-form', 'empty' => 'None')) ?>
            </div>
        </div>

        <div class="block clearfix">
            <div class="block-title clearfix">
                <!--<h2>&nbsp;</h2>-->
                <div class="block-options pull-left"><a href="#tab_major" data-toggle="tab" class="btn btn-sm btn-success">Major</a></div>
                <div class="block-options pull-left"><a href="#tab_location" data-toggle="tab" class="btn btn-sm btn-warning active">Location</a></div>
                <div class="block-options pull-left"><a href="#tab_school_type" data-toggle="tab" class="btn btn-sm btn-danger active">School Type</a></div>
                <div class="block-options pull-left"><a href="#tab_provider" data-toggle="tab" class="btn btn-sm btn-c1 active">Provider</a></div>
            </div>

            <div class="form-group">
                <div class="lst_category">
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane fade in active" id="tab_major">
                            <div class="well well-sm alert-success themed-color-night"><strong>Major</strong>
                                <?php
                                $this->widget('TreeCategory', array(
                                    'submit_form' => 'internship-form',
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
                                    'submit_form' => 'internship-form',
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
                                    'submit_form' => 'internship-form',
                                    'submit_name' => 'Post[category][]',
                                    'select' => $select_categories,
                                    'taxonomy' => 'category',
                                    'show_in' => 'school-type',
                                ))
                                ?>
                            </div>
                        </div>
                        <div class="tab-pane fade in" id="tab_provider">
                            <div class="well well-sm alert-c1 themed-color-night"><strong>Provider</strong>
                                <?php
                                $this->widget('TreeFilter', array(
                                    'submit_form' => 'internship-form',
                                    'submit_name' => 'Post[provider][]',
                                    'select' => $select_provider,
                                    'filter' => 'internship',
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
<?php echo CHtml::submitButton($model->isNewRecord ? 'Add New' : 'Update', array('class' => 'btn btn-sm btn-primary', 'form' => 'internship-form')); ?>
                    <button class="btn btn-sm btn-warning" type="reset" form="internship-form"><i class="fa fa-repeat"></i> Reset</button>
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
        html: {hidden: '<input id="Post_tags" name="Post[tags]" type="hidden" form="internship-form" />'},
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