<?php
$this->pageTitle = UserModule::t("Login") . ' | ' . Yii::app()->name;
$this->breadcrumbs = array(
    UserModule::t("Login"),
);
?>

<div class="login-title text-center">
    <h1><i class="gi gi-wifi_alt"></i> <strong>Lilely</strong>Connect<br /><small>Please <strong>Login</strong> or <strong>Register</strong></small></h1>
</div>

<?php if (Yii::app()->user->hasFlash('loginMessage')): ?>
    <div class="success">
        <?php echo Yii::app()->user->getFlash('loginMessage'); ?>
    </div>
<?php endif; ?>


<div class="block remove-margin">
    <!-- BEGIN Login Form -->
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'login-form',
//    'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
        'htmlOptions' => array('class' => 'form-horizontal form-bordered form-control-borderless'),
    ));
    ?>
    <div class="form-group">
        <div class="col-xs-12">
            <div class="input-group">
                <span class="input-group-addon"><i class="gi gi-envelope"></i></span>
                <?php echo $form->textField($model, 'username', array('placeholder' => 'Username or Email', 'class' => 'form-control input-lg')) ?>
                <span class="help-block"><?php echo $form->error($model, 'email'); ?></span>
            </div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-xs-12">
            <div class="input-group">
                <span class="input-group-addon"><i class="gi gi-asterisk"></i></span>
                <?php echo $form->passwordField($model, 'password', array('placeholder' => 'Password', 'class' => 'form-control input-lg')); ?>
                <span class="help-block"><?php echo $form->error($model, 'password'); ?></span>
            </div>
        </div>
    </div>

    <div class="form-group form-actions">
        <div class="col-xs-4">
            <label class="switch switch-primary" data-toggle="tooltip" title="Remember Me?">
                <input id="UserLogin_rememberMe" type="checkbox" value="" name="UserLogin[rememberMe]" />
                <span></span>
            </label>
        </div>
        <div class="col-xs-8 text-right">
            <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i> Login to Dashboard</button>
        </div>
    </div>
    <div class="form-group">
        <div class="col-xs-12">
            <p class="pull-left remove-margin"><?php echo CHtml::link(UserModule::t("Forgot Password?"), Yii::app()->getModule('user')->recoveryUrl, array('class' => 'goto-forgot pull-left')); ?></p>
            <p class="pull-right remove-margin"><small>Don't have an account?</small> <a href="<?php echo Yii::app()->createUrl('user/registration') ?>" id="link-login"><small>Create one for free!</small></a></p>
        </div>
        
        <?php // echo CHtml::link(UserModule::t("Sign up now"), Yii::app()->getModule('user')->registrationUrl, array('class' => 'goto-register pull-right')); ?>
    </div>

    <div class="form-group">
        <div class="col-xs-12">
            <p class="pull-left remove-margin">
                <a href="#modal-terms" data-toggle="modal" class="enable-tooltip" data-placement="bottom" title="Terms"><i class="fa fa-info-circle"></i>Terms & Privacy</a>
            </p>
        </div>
    </div>

    <?php $this->endWidget(); ?>
    <!-- END Login Form -->

</div>