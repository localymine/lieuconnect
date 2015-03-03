<?php
$this->pageTitle = UserModule::t("Registration") . '|' . Yii::app()->name;
$this->breadcrumbs = array(
    UserModule::t("Registration"),
);
?>

<div class="login-title text-center">
    <h1><i class="gi gi-wifi_alt"></i> <strong>Lilely</strong>Connect<br /><small><?php echo UserModule::t("Registration") ?></small></h1>
</div>


<div class="block remove-margin">
    <?php if (Yii::app()->user->hasFlash('registration')): ?>
        <div class="success">
            <?php echo Yii::app()->user->getFlash('registration'); ?>
        </div>
    <?php else: ?>

        <!-- BEGIN Register Form -->
        <?php
        $form = $this->beginWidget('UActiveForm', array(
            'id' => 'registration-form',
            'enableAjaxValidation' => true,
            'disableAjaxValidationAttributes' => array('RegistrationForm_verifyCode'),
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
            'htmlOptions' => array('enctype' => 'multipart/form-data', 'class' => 'form-horizontal form-bordered form-control-borderless'),
        ));
        ?>

        <?php // var_dump(trim($form->errorSummary(array($model, $profile)))) ?>

        <?php if ($form->errorSummary(array($model, $profile)) != ""): ?>
            <div class="form-group">
                <div class="help-block"><?php echo $form->errorSummary(array($model, $profile)); ?></div>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <div class="col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                    <?php echo $form->textField($model, 'username', array('placeholder' => UserModule::t("User Name"), 'class' => 'form-control input-lg')); ?>
                    <div class="help-block"><?php echo $form->error($model, 'username'); ?></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-asterisk"></i></span>
                    <?php echo $form->passwordField($model, 'password', array('placeholder' => UserModule::t("Password"), 'class' => 'form-control input-lg')); ?>
                    <div class="help-block"><?php echo $form->error($model, 'password'); ?></div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-exclamation"></i></span>
                    <?php echo $form->passwordField($model, 'verifyPassword', array('placeholder' => UserModule::t("Repeat Password"), 'class' => 'form-control input-lg')); ?>
                    <div class="help-block"><?php echo $form->error($model, 'verifyPassword'); ?></div>
                </div>
            </div>
        </div>

        <hr/>

        <?php
        $profileFields = $profile->getFields();
        if ($profileFields) {
            foreach ($profileFields as $field) {
                ?>
                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                            <?php
                            if ($widgetEdit = $field->widgetEdit($profile)) {
                                echo $widgetEdit;
                            } elseif ($field->range) {
                                echo $form->dropDownList($profile, $field->varname, Profile::range($field->range), array('placeholder' => UserModule::t($field->varname), 'class' => 'form-control input-lg'));
                            } elseif ($field->field_type == "TEXT") {
                                echo $form->textArea($profile, $field->varname, array('placeholder' => UserModule::t($field->varname), 'class' => 'form-control input-lg'));
                            } else {
                                echo $form->textField($profile, $field->varname, array('placeholder' => UserModule::t($field->varname), 'class' => 'form-control input-lg', 'maxlength' => (($field->field_size) ? $field->field_size : 255)));
                            }
                            ?>
                            <div class="text-danger"><?php echo $form->error($profile, $field->varname); ?></div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
        
        <div class="form-group">
            <div class="col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope-o"></i></span>
                    <?php echo $form->textField($model, 'email', array('placeholder' => UserModule::t("Email"), 'class' => 'form-control input-lg')); ?>
                    <div class="help-block"><?php echo $form->error($model, 'email'); ?></div>
                </div>
            </div>
        </div>

        <hr/>

        <?php if (UserModule::doCaptcha('registration')): ?>
            <div class="form-group">
                <div class="col-xs-12">
                    <div class="input-group">
                        <span class="text-center"><?php $this->widget('CCaptcha'); ?></span>
                        <?php echo $form->textField($model, 'verifyCode', array('placeholder' => UserModule::t("Enter verify code"), 'class' => 'form-control')); ?>
                        <div class="text-danger"><?php echo $form->error($model, 'verifyCode'); ?></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <div class="col-xs-12">
                <div class="input-group">
                    <label class="checkbox">
                        <input type="checkbox" value="remember" /> I accept the <a href="#">user aggrement</a>
                    </label>
                </div>
            </div>
        </div>
        
        <div class="form-group form-actions">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i> Sign up</button>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">
                <p class="pull-left remove-margin">
                    <?php echo CHtml::link(UserModule::t("← Back to login form"), Yii::app()->getModule('user')->loginUrl, array('class' => 'goto-login pull-left')); ?>
                </p>
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-xs-12">
                <p class="pull-left remove-margin">
                    <a href="#modal-terms" data-toggle="modal" class="enable-tooltip" data-placement="bottom" title="Terms"><i class="fa fa-info-circle"></i>Terms & Privacy</a>
                </p>
            </div>
        </div>

        <?php $this->endWidget(); ?>
        <!-- END Register Form -->

    <?php endif; ?>
</div>