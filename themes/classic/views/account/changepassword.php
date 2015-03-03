<?php
/* @var $this AccountController */

$this->pageTitle = Common::t('Change Password', 'account') . ' | ' . Yii::app()->name;
?>

<div class="main-frame">
    <div class="feature">
        <span class="lilely-btn no-clickable"><?php echo Common::t('Change Password', 'account') ?></span>
    </div>
    <div class="main forgot-password">
            
        <!-- BEGIN Change Password Form -->
        <?php echo CHtml::beginForm(); ?>
        <fieldset>
            <div class="form-group">
                <span class="help-block text-danger"><?php echo CHtml::errorSummary($form); ?></span>
                <div class="col-xs-12">
                    <label for="password" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label"><i class="fa fa-asterisk text-danger"></i><?php echo Common::t('New Password', 'account') ?>:</label>
                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                        <?php echo CHtml::activePasswordField($form, 'password', array('class' => 'form-control text-input')); ?>
                    </div>
                    <p class="hint">
                        <?php echo Common::t("Minimal password length 4 symbols.", 'account'); ?>
                    </p>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-xs-12">
                    <label for="password" class="col-xs-12 col-sm-3 col-md-3 col-lg-2 control-label"><i class="fa fa-asterisk text-danger"></i><?php echo Common::t('Verify Password', 'account') ?>:</label>
                    <div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">
                        <?php echo CHtml::activePasswordField($form, 'verifyPassword', array('class' => 'form-control text-input')); ?>
                        <button type="submit" class="btn btn-default btn-go"><i class="fa fa-angle-right"></i> <?php echo Common::t("Save", 'account'); ?></button>
                    </div>
                </div>
            </div>
            
            <p>Need help? <a href="<?php echo Yii::app()->createUrl('site/contact') ?>"><?php echo Common::t('Contact Us', 'footer') ?></a></p>
        </fieldset>
        
        <?php echo CHtml::endForm(); ?>
        <!-- END Change Password Form -->
    </div>
</div>

