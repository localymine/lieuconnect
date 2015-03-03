<?php
/* @var $this AccountController */

$this->pageTitle = Common::t('Sign up', 'post');

Common::register_css(Yii::app()->theme->baseUrl . '/css/datepicker.css');
Common::register_js(Yii::app()->theme->baseUrl . '/js/bootstrap-datepicker.js', CClientScript::POS_END);
//
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/jquery.validate.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/additional-methods.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/jquery.validate.bootstrap.popover.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/localization/messages_' . $this->lang . '.js', CClientScript::POS_END);
//
Common::register_js(Yii::app()->theme->baseUrl . '/js/account.js', CClientScript::POS_END);


$script = <<< EOD
$.fn.datepicker.defaults.format = "yyyy-mm-dd";
$('.datepicker').datepicker({
    'viewMode' : 2,
    'setValue': new Date(84,5,30),
    'language': "$this->lang",
});
EOD;

Common::register_script('signup-form', $script);
?>


<div class="main-frame">
    <div class="feature">
        <span class="lilely-btn no-clickable"><?php echo Common::t('Sign up to Lilely Connect', 'account') ?></span>
    </div>
    <div class="main signup">
        <?php
        $form = $this->beginWidget('UActiveForm', array(
            'id' => 'signup-form',
            'enableAjaxValidation' => true,
            'disableAjaxValidationAttributes' => array('SignupForm_verifyCode'),
            'action' => Yii::app()->createUrl('account/signup'),
            'htmlOptions' => array(
//                'enctype' => 'multipart/form-data', 
//                'class' => 'form-bordered', 
            )
        ));
        ?>

        <div class="help-block">
            <?php echo $form->errorSummary(array($model, $model_profile), ''); ?>
        </div>
        <fieldset>
            <legend><?php echo Common::t('About', 'account') ?></legend>

            <div class="form-group col-md-4">
                <label class="control-label"><?php echo Common::t('First Name', 'account') ?> <i class="fa fa-asterisk text-required"></i></label>
                <?php echo $form->textField($model_profile, 'firstname', array('placeholder' => '', 'class' => 'form-control')); ?>
            </div>

            <div class="form-group col-md-4">
                <label class="control-label"><?php echo Common::t('Last Name', 'account') ?> <i class="fa fa-asterisk text-required"></i></label>
                <?php echo $form->textField($model_profile, 'lastname', array('placeholder' => '', 'class' => 'form-control')); ?>
            </div>

            <div class="form-group col-md-4">
                <label class="control-label"><?php echo Common::t('Email', 'account') ?> <i class="fa fa-asterisk text-required"></i></label>
                <?php echo $form->textField($model, 'email', array('type' => 'email', 'placeholder' => '', 'class' => 'form-control')); ?>
            </div>

            <div class="form-group col-md-4">
                <label class="control-label"><?php echo Common::t('Phone', 'account') ?></label>
                <?php echo $form->textField($model_profile, 'phone', array('placeholder' => '', 'class' => 'form-control')); ?>
            </div>

            <div class="form-group col-md-4">
                <label class="control-label"><?php echo Common::t('Birth Date', 'account') ?></label>
                <?php echo $form->textField($model_profile, 'birth_date', array('readonly' => 'readonly', 'placeholder' => '', 'class' => 'form-control text-center input-append datepicker', 'value' => '1984-05-30')); ?>
            </div>

            <div class="form-group col-md-4">
                <label class="control-label"><?php echo Common::t('Gender', 'account') ?></label>
                <?php echo $form->dropDownList($model_profile, 'gender', AUsers::itemAlias('Gender'), array('class' => 'form-control')) ?>
            </div>

        </fieldset>

        <fieldset>
            <legend><?php echo Common::t('Username and Password', 'account') ?></legend>
            <div class="form-group col-md-4">
                <label class="control-label"><?php echo Common::t('Username', 'account') ?> <i class="fa fa-asterisk text-required"></i></label>
                <?php echo $form->textField($model, 'username', array('class' => 'form-control', 'placeholder' => '')) ?>
            </div>
            <div class="form-group col-md-4">
                <label class="control-label"><?php echo Common::t('Password', 'account') ?> <i class="fa fa-asterisk text-required"></i></label>
                <?php echo $form->passwordField($model, 'password', array('class' => 'form-control', 'placeholder' => '')) ?>
            </div>
            <div class="form-group col-md-4">
                <label class="control-label"><?php echo Common::t('Confirm Password', 'account') ?> <i class="fa fa-asterisk text-required"></i></label>
                <?php echo $form->passwordField($model, 'verifyPassword', array('class' => 'form-control', 'placeholder' => '')) ?>
            </div>
        </fieldset>

        <fieldset>
            <legend><?php echo Common::t('Contact Information', 'account') ?></legend>

            <div class="form-group col-md-12">
                <label class="control-label"><?php echo Common::t('Address', 'account') ?></label>
                <?php echo $form->textField($model_profile, 'address', array('placeholder' => '', 'class' => 'form-control')); ?>
            </div>
            <div class="form-group col-md-3">
                <label class="control-label"><?php echo Common::t('Country', 'account') ?> <i class="fa fa-asterisk text-required"></i></label>
                <?php echo $form->dropDownList($model_profile, 'country', CHtml::listData($countries, 'countryID', 'countryName'), array('class' => 'form-control', 'empty' => Common::t('Choose Country', 'account'), 'options' => array($select_country => array('selected' => true)))) ?>
            </div>
            <div class="form-group col-md-3">
                <label class="control-label"><?php echo Common::t('State/Region', 'account') ?></label>
                <?php echo $form->dropDownList($model_profile, 'state', CHtml::listData($states, 'stateID', 'stateName'), array('class' => 'form-control', 'empty' => Common::t('Choose State/Region', 'account'), 'options' => array($select_state => array('selected' => true)))) ?>
            </div>
            <div class="col-md-3">
                <label class="control-label"><?php echo Common::t('City', 'account') ?></label>
                <?php echo $form->dropDownList($model_profile, 'city', CHtml::listData($cities, 'cityID', 'cityName'), array('class' => 'form-control', 'empty' => Common::t('Choose City', 'account'), 'options' => array($select_city => array('selected' => true)))) ?>
            </div>
            <div class="form-group col-md-3">
                <label class="control-label"><?php echo Common::t('Zipcode', 'account') ?></label>
                <?php echo $form->textField($model_profile, 'zipcode', array('class' => 'form-control', 'placeholder' => '')) ?>
            </div>

            <div class="form-group col-md-6">
                <label class="control-label"><?php echo Common::t('What would you like to study?', 'account') ?></label>
                <?php echo $form->textArea($model_profile, 'expectation', array('rows' => '4', 'class' => 'form-control', 'placeholder' => '', 'style' => 'resize: none;')) ?>
            </div>
            <div class="form-group col-md-3">
                <label class="control-label"><?php echo Common::t('Grade Year', 'account') ?></label>
                <?php echo $form->dropDownList($model_profile, 'grade_year', Common::get_years_from_current(), array('class' => 'form-control text-center')) ?>
            </div>
            <div class="form-group col-md-3">
                <label class="control-label"><?php echo Common::t('GPA', 'account') ?></label>
                <?php echo $form->textField($model_profile, 'gpa', array('class' => 'form-control', 'placeholder' => '')) ?>
            </div>
            <div class="form-group col-md-3 text-center">
                <div class="">
                    <?php
                    $this->widget('CCaptcha', array(
                        'buttonLabel' => '<i class="fa fa-refresh"></i>',
                        'buttonType' => '',
                        'buttonOptions' => array(
                            'class' => 'inline-block'
                        ),
                        'imageOptions' => array(
                            'class' => 'inline-block'
                        )
                    ));
                    ?>
                </div>
            </div>
            <div class="form-group col-md-3">
                <?php echo $form->textField($model, 'verifyCode', array('value' => '', 'placeholder' => Common::t('Enter verify code','account'), 'class' => 'form-control')); ?>
            </div>
        </fieldset>

        <fieldset>
            <div class="form-group">
                <div class="col-sm-12">
                    <div class="checkbox">
                        <label>
                            <?php echo CHtml::checkBox('agree') ?>
                            <?php echo Common::t('I agree', 'account') ?>
                        </label>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-warning" value="Sign Up">
            </div>
        </fieldset>
        <?php $this->endWidget(); ?>
    </div>
</div>