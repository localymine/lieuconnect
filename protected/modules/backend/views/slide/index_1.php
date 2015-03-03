<?php
/* @var $this SlideController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Slide" . ' | ' . Yii::app()->name;

Common::register('jquery.min.js', 'pro', CClientScript::POS_HEAD);
Common::register('jquery-ui-1.10.4.custom.min.js', 'pro', CClientScript::POS_END);
?>

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-picture"></i>Slide Show Management
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="block">
            <div class="block-title">
                <h2><?php echo $form_title ?></h2>
                <?php if ($update == 1): ?>
                    <div class="block-options pull-right"><a href="<?php echo Yii::app()->createUrl('backend/slide') ?>" class="btn btn-sm btn-primary">Add New Item</a></div>
                <?php endif; ?>
            </div>
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'slide-form',
                'enableAjaxValidation' => false,
                'htmlOptions' => array(
                    'method' => 'post',
                    'enctype' => 'multipart/form-data',
                    'class' => 'form-horizontal form-bordered'
                ),
            ));
            ?>

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
                                <label class="col-md-2 control-label">Image</label>
                                <div class="col-xs-10">
                                    <?php echo $form->fileField($model, 'image' . $suffix) ?>
                                    <?php echo $form->error($model,'image' . $suffix, array('class' => 'text-danger')); ?>
                                    <input type="file" name="test" />
                                    <?php echo $form->fileField($model, 'image'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Title</label>
                                <div class="col-md-10">
                                    <div class="input-group">
                                        <span class="input-group-addon"><img width="24" src="<?php echo Yii::app()->baseUrl ?>/images/flags/<?php echo $l ?>.png"/></span>
                                        <?php echo $form->textField($model, 'title' . $suffix, array('class' => 'form-control', 'placeholder' => '...')); ?>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-2 control-label">Description</label>
                                <div class="col-md-10">
                                    <?php
                                    $this->widget('application.extensions.ckeditor.CKEditor', array(
                                        'model' => $model,
                                        'attribute' => 'description' . $suffix,
                                        'language' => 'en',
                                        'editorTemplate' => 'basic',
                                    ));
                                    ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <div class="form-group form-actions">
                <div class="col-md-9 col-md-offset-5">
                    <?php if ($model->isNewRecord): ?>
                        <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-angle-right"></i> Add</button>
                    <?php else: ?>
                        <button class="btn btn-sm btn-info" type="submit"><i class="fa fa-angle-right"></i> Update</button>
                    <?php endif; ?>
                </div>
            </div>

            <?php $this->endWidget(); ?>
        </div>
    </div>
    
    <div class="col-md-5">
        <div class="block clearfix">
            <div class="block-title">
                <h2>List Items</h2>
                <div class="block-options pull-right"><button id="update-sort" class="btn btn-sm btn-danger">Update Sorting</button></div>
            </div>
            <div class="row">
                <?php $slides = $model->get_slide()->findAll(); ?>
                <ul id="slide-sortable">
                    <?php foreach ($slides as $row): ?>
                    <li data-slideid="<?php echo $row->id ?>">
                        <div class="table-responsive" role="grid">
                            <table class="table table-vcenter table-condensed table-striped dataTable">
                                <tr role="row">
                                    <th><?php echo $row->id ?></th>
                                    <td align="center">
                                        <img class="img-responsive" width="64" alt="avatar" src="<?php echo Yii::app()->baseUrl ?>/images/slide/<?php echo $row->image ?>" />
                                    </td>
                                    <td>
                                        <?php echo $row->title ?>
                                    </td>
                                    <td width="60">
                                        <a href="<?php echo Yii::app()->createUrl('backend/slide/update', array('id' => $row->id)) ?>" style="padding: 0 5px;" title="" data-toggle="tooltip" rel="tooltip" data-original-title="Update"><i class="fa fa-pencil"></i></a>
                                        <a href="<?php echo Yii::app()->createUrl('backend/slide/delete', array('id' => $row->id)) ?>" class="delete" style="padding: 0 5px;color: red;" title="" data-toggle="tooltip" rel="tooltip" data-original-title="Delete"><i class="fa fa-times"></i></a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        
        <div class="block">
            <form enctype="multipart/form-data" action="<?php echo Yii::app()->createUrl('backend/slide') ?>" method="post">
                <!-- MAX_FILE_SIZE must precede the file input field -->
                <!--<input type="hidden" name="MAX_FILE_SIZE" value="30000" />-->
                <!-- Name of input element determines name in $_FILES array -->
                Send this file: <input name="userfile" type="file" />
                <input type="submit" value="Send File" />
            </form>
        </div>
        
    </div>
</div>

<script>
    $(function() {
        var ajaxloader = '<div id="ajaxloader"><i class="ajaxloader fa fa-spinner fa-4x fa-spin text-info"></i></div>';
        
        $("#slide-sortable li").mousedown(function(){ $(this).css('cursor', 'pointer'); }).mouseup(function(){ $(this).css('cursor', 'default'); });
        $("#slide-sortable").sortable();
        $("#slide-sortable").disableSelection();
        
        function get_current_ids() {
            var data = new Array();
            $('#slide-sortable').each(function() {
                $(this).find('li').each(function() {
                    var current = $(this);
                    data.push(current.data('slideid'));
                })
            });
            return data;
        }
        
        $('#update-sort').click(function(e) {
            var data = new Array();
            data = get_current_ids();
            //
            if (data.length === 0) {
                //
            } else {
                // send data
                var jsonString = JSON.stringify(data);
                $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->createUrl('backend/slide/sort') ?>',
                    data: {data: jsonString},
                    dataType: 'json',
                    cache: false,
                    beforeSend: function() {
                        $('body').append(ajaxloader);
                    },
                    success: function(res) {
                        if (res == 1) {
                            alert('Updated.');
                        } else {
                            alert('Error occurred during updating')
                        }
                        $('#ajaxloader').remove();
                    }
                });
            }
        });
        
        $("a.delete").click(function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this?\nCan not recover after delete.')) {
                location.href = this.href;
            }
        });
        
    });
</script>