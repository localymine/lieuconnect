<?php
$form = $this->beginWidget('UActiveForm', array(
    'id' => 'advertise-form',
    'action' => Yii::app()->createUrl('advertise'),
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    )
        ));
?>
<fieldset>
    <p class="margin-bottom-small">Please enter your registered email address.We will send you instructions on how to reset your password.We will send you instructions </p>
    <p class="margin-bottom-small">Please enter your registered email address.We will send you instructions We will send you instructions We will send you instructions >We will send you instructions on how to reset your password.</p>
    <!--<div class="alert alert-danger">-->
        <?php // echo $form->errorSummary($model, ''); ?>
    <!--</div>-->
    <div class="form-group no-margin-horizontal margin-bottom-small">
        <label for="company"><?php echo Common::t('Company', 'post') ?></label>
        <?php echo $form->textField($model, 'company', array('class' => 'form-control')) ?>
        <?php echo $form->error($model,'company', array('class' => 'hint text-danger')); ?>
    </div>
    <div class="form-group no-margin-horizontal margin-bottom-small">
        <label for="first_name"><?php echo Common::t('First Name', 'post') ?></label>
        <?php echo $form->textField($model, 'first_name', array('class' => 'form-control')) ?>
        <?php echo $form->error($model,'first_name', array('class' => 'hint text-danger')); ?>
    </div>
    <div class="form-group no-margin-horizontal margin-bottom-small">
        <label for="last_name"><?php echo Common::t('Last Name', 'post') ?></label>
        <?php echo $form->textField($model, 'last_name', array('class' => 'form-control')) ?>
        <?php echo $form->error($model,'last_name', array('class' => 'hint text-danger')); ?>
    </div>
    <div class="form-group no-margin-horizontal margin-bottom-small">
        <label for="email"><?php echo Common::t('Email', 'post') ?></label>
        <?php echo $form->textField($model, 'email', array('class' => 'form-control')) ?>
        <?php echo $form->error($model,'email', array('class' => 'hint text-danger')); ?>
    </div>
    <div class="form-group no-margin-horizontal margin-bottom-small">
        <span><?php echo Common::t('Organization Type', 'post') ?></span>
        <?php echo $form->dropDownList($model, 'type', Advertise::itemAlias('type'), array('class' => 'form-control', 'empty' => Common::t('None', 'post'))) ?>
        <?php echo $form->error($model,'type', array('class' => 'hint text-danger')); ?>
    </div>
    Are you interest in (check all that apply)
    <div class="form-group no-margin-horizontal margin-bottom-small">
        <?php echo $form->checkBoxList($model_ads_relate, 'f_interest', Advertise::itemAlias('interest'), array('class' => 'hand')) ?>
    </div>
    Please provide any further details/ask any further questions here
    <div class="form-group no-margin-horizontal margin-bottom-small">
        <?php echo $form->textArea($model, 'description', array('class' => 'form-control', 'row' => '6', 'style' => 'resize:vertical;')) ?>
    </div>
    <div class="form-group no-margin-horizontal margin-bottom-small">
        <input type="submit" class="btn btn-default btn-go" value="<?php echo Common::t('Submit', 'post') ?>">
    </div>
</fieldset>
<?php $this->endWidget(); ?>