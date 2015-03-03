<?php
$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Restore");
$this->breadcrumbs = array(
    UserModule::t("Login") => array('/user/login'),
    UserModule::t("Restore"),
);
?>


<div class="login-title text-center">
    <h1><i class="gi gi-wifi_alt"></i> <strong>Lilely</strong>Connect<br /><small>Revover Password</small></h1>
</div>


<div class="block remove-margin">
    <?php if (Yii::app()->user->hasFlash('recoveryMessage')): ?>
        <div class="success">
            <?php echo Yii::app()->user->getFlash('recoveryMessage'); ?>
        </div>
    <?php else: ?>

        <!-- BEGIN Forgot Password Form -->
        <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal form-bordered form-control-borderless')); ?>

        <div class="form-group">
            <div class="col-xs-12">
                <div class="input-group">
                    <span class="input-group-addon"><i class="gi gi-envelope"></i></span>
                    <?php echo CHtml::activeTextField($form, 'login_or_email', array('placeholder' => 'Enter your email', 'class' => 'form-control input-lg')) ?>
                    <span class="help-block"><?php echo CHtml::errorSummary($form); ?></span>
                </div>
            </div>
        </div>

        <div class="form-group form-actions">
            <div class="col-xs-12 text-center">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-angle-right"></i> <?php echo UserModule::t("Recover"); ?></button>
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">

                <p class="pull-left remove-margin">
                    <?php echo CHtml::link(UserModule::t("← Back to login form"), Yii::app()->getModule('user')->loginUrl, array('class' => 'goto-login pull-left')); ?>
                </p>

                <!--<p class="pull-right remove-margin"><small>Don't have an account?</small> <a href="<?php // echo Yii::app()->createUrl('user/registration') ?>" id="link-login"><small>Create one for free!</small></a></p>-->
            </div>
        </div>

        <div class="form-group">
            <div class="col-xs-12">
                <p class="pull-left remove-margin">
                    <a href="#modal-terms" data-toggle="modal" class="enable-tooltip" data-placement="bottom" title="Terms"><i class="fa fa-info-circle"></i>Terms & Privacy</a>
                </p>
            </div>
        </div>

        <?php echo CHtml::endForm(); ?>
        <!-- END Forgot Password Form -->

    <?php endif; ?>
</div>