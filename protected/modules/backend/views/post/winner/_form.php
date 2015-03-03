<?php
/* @var $this TermsController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = ($update == 0) ? ("Add Monthly Winner" . ' | ' . Yii::app()->name) : ("Update Monthly Winner" . ' | ' . Yii::app()->name);

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
            <i class="gi gi-brush"></i>Monthly Winner<br /><small>...</small>
        </h1>
    </div>
</div>

<div class="row">

    <div class="col-md-8">
        <div class="block">
            <div class="block-title">
                <h2><?php echo $form_title; ?></h2>
                <?php if ($update == 1): ?>
                    <div class="block-options pull-right"><a href="<?php echo Yii::app()->createUrl('backend/post/addWinner') ?>" class="btn btn-sm btn-primary">Add New</a></div>
                <?php endif; ?>
            </div>

            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'winner-form',
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
                                <label class="col-md-3 control-label">The Winner's Name</label>
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
                                        <?php echo $form->textField($model, 'slug' . $suffix, array('class' => 'form-control', 'form' => 'winner-form', 'id' => 'slug-' . $lang)) ?>
                                        <span class="input-group-addon"><a class="clear-slug hand" data-slug-id="<?php echo 'slug-' . $lang ?>"><i class="fa fa-times text-danger"></i></a></span>
                                    </div>
                                    <div class="help-block">Auto generate slug when empty</div>
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

                            <div class="form-group">
                                <label class="col-md-3 control-label">Image</label>
                                <div class="col-xs-9">
                                    <?php
                                    echo $form->fileField($model, 'image' . $suffix, array('class' => ''))
                                    ?>
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
                <h2>Winner in Month</h2>
            </div>

            <div class="form-group">
                <div class="">
                    <?php
                    echo $form->dropDownList($model, 'winner_in_month', Post::item_alias('month'), array('class' => 'form-control', 'form' => 'winner-form'))
                    ?>
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
                    <?php echo CHtml::submitButton($model->isNewRecord ? 'Add New' : 'Update', array('class' => 'btn btn-sm btn-primary', 'form' => 'winner-form')); ?>
                    <button class="btn btn-sm btn-warning" type="reset" form="winner-form"><i class="fa fa-repeat"></i> Reset</button>
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
        html: {hidden: '<input id="Post_tags" name="Post[tags]" type="hidden" form="winner-form" />'},
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