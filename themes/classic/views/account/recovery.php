<?php
/* @var $this AccountController */

$this->pageTitle = Common::t('Recovery Password', 'account') . ' | ' . Yii::app()->name;

?>

<div class="main-frame">
    <div class="feature">
        <span class="lilely-btn no-clickable"><?php echo Common::t('Recovery Password', 'account') ?></span>
    </div>
    <div class="main forgot-password">
        <?php if (Yii::app()->user->hasFlash('recoveryMessage')): ?>
            <div class="success">
                <?php echo Yii::app()->user->getFlash('recoveryMessage'); ?>
            </div>
        <?php else: ?>

            <!-- BEGIN Forgot Password Form -->
            <?php echo CHtml::beginForm('', 'post', array('class' => 'form-horizontal')); ?>
            <fieldset>
                <p>Please enter your registered email address.<br>We will send you instructions on how to reset your password.</p>
                <div class="form-group">
                        <span class="help-block text-danger"><?php echo CHtml::errorSummary($form); ?></span>
                    <div class="col-xs-12">
                        <label for="login_or_email" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label"><i class="fa fa-asterisk text-danger"></i><?php echo Common::t('Your login email') ?>:</label>
                        <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                            <?php echo CHtml::activeTextField($form, 'login_or_email', array('placeholder' => 'Enter your email', 'class' => 'form-control text-input')) ?>
                            <button type="submit" class="btn btn-default btn-go"><i class="fa fa-angle-right"></i> <?php echo Common::t("Recover", 'account'); ?></button>
                        </div>
                    </div>
                </div>
                
                <p>Need help? <a href="<?php echo Yii::app()->createUrl('site/contact') ?>"><?php echo Common::t('Contact Us', 'footer') ?></a></p>
                
            </fieldset>
            <?php echo CHtml::endForm(); ?>
            <!-- END Forgot Password Form -->

        <?php endif; ?>   

    </div>
</div>

<?php $this->widget('MoreStuff') ?>