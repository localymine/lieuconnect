<?php
//Yii::app()->clientScript->scriptMap['jquery.js'] = false;
//Yii::app()->clientScript->scriptMap['jquery.min.js'] = false;  
//Yii::app()->clientScript->scriptMap['jquery.ba-bbq.js'] = false;
//Yii::app()->clientScript->scriptMap['jquery.yiigridview.js'] = false;
?>

<div id="modal-user-settings" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-pencil"></i> Settings</h2>
            </div>


<!--            <div class="modal-body">
                <fieldset>
                    <legend>About <?php // echo Yii::app()->user->username; ?></legend>
                </fieldset>
                <fieldset>
                    <div class="form-group" style="width: 93%;margin: 0 auto;">
                        <form action="Yii::app()->createUrl('user/profile/upload')" enctype="multipart/form-data" class="dropzone form-bordered" /></form>
                    </div>
                </fieldset>
            </div>-->

            <div class="modal-body">
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'profile-form',
//                    'enableAjaxValidation' => true, 'stateful' => true,
                    'action' => Yii::app()->createUrl('user/profile/edit'),
                    'htmlOptions' => array(
                        'enctype' => 'multipart/form-data', 
                        'class' => 'form-horizontal form-bordered', 
                        'target' => 'i-profile-edit'),
                    ));
                ?>
                <fieldset>
                    <legend>About <?php echo Yii::app()->user->username; ?></legend>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div id="error_profile_edit" class="alert-danger"></div>
                        </div>
                    </div>
                    <?php
                    if ($profileFields) {
                        $show_fields = array('firstname', 'lastname', 'phone', 'birth_date');
                        foreach ($profileFields as $field) {
                            ?>
                            <?php if ($field->varname == 'image'): ?>
                                <div class="form-group">
                                    <label class="col-md-2 control-label" ><?php echo $field->title; ?></label>
                                    <div class="col-md-10">
                                        <?php echo $form->fileField($profile, $field->varname) ?>
                                    </div>
                                </div>
                            <?php elseif ($field->varname == 'blast'): ?>
                                <div class="form-group">
                                    <label class="col-md-2 control-label" ><?php echo $field->title; ?></label>
                                    <div class="col-md-10">
                                        <?php echo $form->textArea($profile, $field->varname, array('class' => 'form-control', 'row' => 5)) ?>
                                    </div>
                                </div>
                            <?php elseif (in_array($field->varname, $show_fields)): ?>
                                <div class="form-group">
                                    <label class="col-md-2 control-label" ><?php echo $field->title; ?></label>
                                    <div class="col-md-10">
                                        <?php echo $form->textField($profile, $field->varname, array('class' => 'form-control')) ?>
                                    </div>
                                </div>
                            <?php else: ?>
                                <!--<div class="form-group">-->
                                    <!--<label class="col-md-4 control-label" ><?php // echo $field->title; ?></label>-->
                                    <!--<div class="col-md-8">-->
                                        <?php
//                                        if ($widgetEdit = $field->widgetEdit($profile)) {
//                                            echo $widgetEdit;
//                                        } elseif ($field->range) {
//                                            echo $form->dropDownList($profile, $field->varname, Profile::range($field->range, array('class' => 'form-control')));
//                                        } elseif ($field->field_type == "TEXT") {
//                                            echo $form->textArea($profile, $field->varname, array('class' => 'form-control', 'rows' => 6, 'cols' => 50));
//                                        } else {
//                                            echo $form->textField($profile, $field->varname, array('class' => 'form-control', 'maxlength' => (($field->field_size) ? $field->field_size : 255)));
//                                        }
//                                        echo $form->error($profile, $field->varname);
                                        ?>
                                    <!--</div>-->
                                <!--</div>-->
                            <?php endif; ?>
                            <?php
                        }
                    }
                    ?>

                    <div class="form-group">
                        <label class="col-md-2 control-label" ><?php echo $form->labelEx($model, 'email'); ?></label>
                        <div class="col-md-10">
                            <?php echo $form->textField($model, 'email', array('class' => 'form-control', 'maxlength' => 128)); ?>
                        </div>
                    </div>

                    <div class="form-group form-actions">
                        <div class="col-xs-12 text-right">
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                        </div>
                    </div>

                </fieldset>
                <?php $this->endWidget(); ?>

                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'changepassword-form',
//                    'enableAjaxValidation' => true,
//                    'enableClientValidation' => true,
//                    'clientOptions' => array(
//                        'validateOnSubmit' => true,
//                    ),
                    'action' => Yii::app()->createUrl('user/profile/changepassword'),
                    'htmlOptions' => array(
                        'class' => 'form-horizontal form-bordered',
                        'target' => 'i-profile-edit',
//                        'onsubmit' => "return false;",
//                        'onkeypress'=>" if(event.keyCode == 13){ js_function(); } "
                    ),
                ));
                ?>
                <fieldset>
                    <legend>Password Update</legend>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div id="error-change-password" class="alert-danger"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="user-settings-password">Old Password</label>
                        <div class="col-md-8">
                            <?php echo $form->passwordField($model_password, 'oldPassword', array('placeholder' => 'Please input old password', 'class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="user-settings-password">New Password</label>
                        <div class="col-md-8">
                            <?php echo $form->passwordField($model_password, 'password', array('placeholder' => 'Please choose a complex one..', 'class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label" for="user-settings-repassword">Confirm New Password</label>
                        <div class="col-md-8">
                            <?php echo $form->passwordField($model_password, 'verifyPassword', array('placeholder' => '..and confirm it!', 'class' => 'form-control')); ?>
                        </div>
                    </div>
                    <div class="form-group form-actions">
                        <div class="col-xs-12 text-right">
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                        </div>
                    </div>

                </fieldset>
                <?php $this->endWidget(); ?>

            </div>
        </div>
    </div>
</div>

<iframe name="i-profile-edit" frameborder="0" width="0" height="0" style="display: none;"></iframe>
<script type="text/javascript">
    
    function error_change_password(res){
        var err_mes = $.parseJSON(res);
        var v_mes = '';
        if (err_mes[0].mess == 'NG'){
            $.each(err_mes[1].resp, function(i, item){
                if (item instanceof Object != false){
                    $.each(item, function(j, obj){
                        v_mes += '<li>' + obj + '</li>';
                    })
                }else{
                    v_mes += '<li>' + item + '</li>';
                }
            })
            $('#error-change-password').html('');
            $('#error-change-password').html(v_mes);
            $('#error-change-password li').delay(5000).fadeOut(300);
        } else{
            window.location.href=window.location.href; // not resend
        }
    }
    
    function error_profile_edit(res){
        var err_mes = $.parseJSON(res);
        var v_mes = '';
        if (err_mes[0].mess == 'NG'){
            $.each(err_mes[1].resp, function(i, item){
                if (item instanceof Object != false){
                    $.each(item, function(j, obj){
                        v_mes += '<li>' + obj + '</li>';
                    })
                }else{
                    v_mes += '<li>' + item + '</li>';
                }
            })
            $('#error_profile_edit').html('');
            $('#error_profile_edit').html(v_mes);
            $('#error_profile_edit li').delay(5000).fadeOut(300);
        } else{
            window.location.href=window.location.href; // not resend
        }
    }
    
</script>