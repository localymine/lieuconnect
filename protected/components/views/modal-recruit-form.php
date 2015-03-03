<?php
Common::register_css(Yii::app()->theme->baseUrl . '/css/datepicker.css');
Common::register_js(Yii::app()->theme->baseUrl . '/js/bootstrap-datepicker.js', CClientScript::POS_END);
//
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/jquery.validate.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/additional-methods.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/jquery.validate.bootstrap.popover.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/localization/messages_' . $this->lang . '.js', CClientScript::POS_END);
//

$script = <<< EOD
$.fn.datepicker.defaults.format = "yyyy-mm-dd";
$('.datepicker').datepicker({
  'viewMode' : 2,
  'language': "$this->lang",
  'orientation': 'auto top',
});
EOD;

Common::register_script('recruit-form', $script);
?>

<!-- Large modal -->
<div class="modal fade recruited-popup" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content recruited-frame">
            <div class="">
                <h2><?php echo Common::t('Get Recruited', 'account') ?></h2>
                <p><?php echo Common::t('Fill out the form below to receive more information LilelyConnect', 'account') ?></p>
                <form id="modal-recruit-form" action="<?php echo Yii::app()->createUrl('recruit/apply') ?>" method="post" enctype="multipart/form-data" role="form" class="form-horizontal">
                    <div class="form-group">
                        <label for="firstname" class=" col-sm-5 col-md-5 no-padding control-label"><?php echo Common::t('First Name', 'account') ?> <i class="fa fa-asterisk text-required"></i>:</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::textField('firstname', $model_user->profile->firstname, array('class' => 'form-control long-input', 'placeholder' => Common::t('First Name', 'account'))) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lastname" class=" col-sm-5 col-md-5 control-label"><?php echo Common::t('Last Name', 'account') ?> <i class="fa fa-asterisk text-required"></i>:</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::textField('lastname', $model_user->profile->lastname, array('class' => 'form-control long-input', 'placeholder' => Common::t('Last Name', 'account'))) ?>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="address" class=" col-sm-5 col-md-5 control-label"><?php echo Common::t('Address', 'account') ?>:</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::textField('address', $model_user->profile->lastname, array('class' => 'form-control long-input', 'placeholder' => Common::t('Address', 'account'))) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="country" class=" col-sm-5 col-md-5 control-label"><?php echo Common::t('Country', 'account') ?> <i class="fa fa-asterisk text-required"></i>:</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::dropDownList('country', $select_country, CHtml::listData($countries, 'countryID', 'countryName'), array('class' => 'form-control long-input', 'empty' => Common::t('Choose Country', 'account'))) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="state" class=" col-sm-5 col-md-5 control-label"><?php echo Common::t('State/Region', 'account') ?>:</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::dropDownList('state', $select_state, CHtml::listData($states, 'stateID', 'stateName'), array('class' => 'form-control long-input', 'empty' => Common::t('Choose State/Region', 'account'))) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="city" class=" col-sm-5 col-md-5 control-label"><?php echo Common::t('City', 'account') ?>:</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::dropDownList('city', $select_city, CHtml::listData($cities, 'cityID', 'cityName'), array('class' => 'form-control long-input', 'empty' => Common::t('Choose City', 'account'))) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="zipcode" class=" col-sm-5 col-md-5 control-label"><?php echo Common::t('Zip/Postal', 'account') ?>:</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::textField('zipcode', $model_user->profile->zipcode, array('class' => 'form-control long-input', 'placeholder' => Common::t('Zip/Postal', 'account'))) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class=" col-sm-5 col-md-5 control-label"><?php echo Common::t('Email', 'account') ?> <i class="fa fa-asterisk text-required"></i>:</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::textField('email', $model_user->email, array('class' => 'form-control long-input', 'placeholder' => Common::t('Email', 'account'))) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone" class=" col-sm-5 col-md-5 control-label"><?php echo Common::t('Phone', 'account') ?>:</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::textField('phone', $model_user->profile->phone, array('class' => 'form-control long-input', 'placeholder' => Common::t('Phone number', 'account'))) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gender" class=" col-sm-5 col-md-5 control-label"><?php echo Common::t('Gender', 'account') ?>:</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::dropDownList('gender', $model_user->profile->gender, AUsers::itemAlias('Gender'), array('class' => 'form-control short-input')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="birthday" class=" col-sm-5 col-md-5 control-label"><?php echo Common::t('Date of Birth', 'account') ?>:</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::textField('birth_date', $model_user->profile->birth_date, array('readonly' => 'readonly', 'class' => 'form-control text-center input-append datepicker long-input')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="grade_year" class=" col-sm-5 col-md-5 control-label"><?php echo Common::t('Grade Year', 'account') ?>:</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::dropDownList('grade_year', $model_user->profile->grade_year, Common::get_years_from_current(), array('class' => 'form-control text-center long-input')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="school_name" class=" col-sm-5 col-md-5 control-label"><?php echo Common::t('School Name', 'account') ?>:</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::textField('school_name', '', array('class' => 'form-control long-input')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gpa" class=" col-sm-5 col-md-5 control-label"><?php echo Common::t('GPA on a 4.0 scale', 'account') ?>:</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::textField('gpa', $model_user->profile->gpa, array('class' => 'form-control long-input text-right')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="feeling" class=" col-sm-5 col-md-5 control-label"><?php echo Common::t("How I'm Feeling", 'account') ?>:</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::dropDownList('feeling', '', CHtml::listData($feeling, 'feeling_id', 'title') , array('class' => 'form-control long-input')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-5 col-sm-6">
                            <input type="submit" class="btn btn-default trigger btn-go" value="<?php echo $title ?>">
                            <?php echo CHtml::hiddenField('post_id') ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
$url_apply = Yii::app()->createUrl('recruit/apply');
$url_load_state = Yii::app()->createUrl('account/load_state');
$url_load_city = Yii::app()->createUrl('account/load_city');
$choose_country = Common::t('Choose Country', 'account');
$choose_state = Common::t('Choose State/Region', 'account');
$choose_city = Common::t('Choose City', 'account');

$script = <<< EOD
$(function() {
    $('#country').on('change', function() {
        var value = $(this).val();
        if (value != '') {
            loader.start();
            $.ajax({
                type: 'POST',
                url: '$url_load_state',
                data: {id: value},
                success: function(res) {
                    $('#state').html(res);
                    loader.stop();
                }
            });
        } else {
            $('#state').html('<option value="">$choose_state</option>');
        }
        $('#city').html('<option value="">$choose_city</option>');
    });
    $('#state').on('change', function() {
        var value = $(this).val();
        if (value != '') {
            loader.start();
            $.ajax({
                type: 'POST',
                url: '$url_load_city',
                data: {id: value},
                success: function(res) {
                    $('#city').html(res);
                    loader.stop();
                }
            });
        } else {
            $('#city').html('<option value="">$choose_city</option>');
        }
    });
    var form_valid = $('#modal-recruit-form');
    form_valid.validate_popover({
        lang: '$this->lang',
        popoverPosition: 'top',
        rules: {
            'firstname': {
                required: true,
            },
            'lastname': {
                required: true,
            },
            'country': {
                required: true,
            },
            'email': {
                required: true,
                email: true,
            }
        },
        showErrors: function(errorMap, errorList) {
            // Clean up any tooltips for valid elements
            $.each(this.validElements(), function(index, element) {
                var \$element = $(element);
                \$element.data("title", "") // Clear the title - there is no error associated anymore
                        .removeClass("error")
                        .tooltip("destroy");
            });
            // Create new tooltips for invalid elements
            $.each(errorList, function(index, error) {
                var \$element = $(error.element);
                \$element.tooltip("destroy") // Destroy any pre-existing tooltip so we can repopulate with new tooltip content
                        .data("title", error.message)
                        .addClass("error")
                        .tooltip(); // Create a new tooltip based on the error messsage we just set in the title
            });
        },
        submitHandler: function(form) {
        
            var data = JSON.stringify(form_valid.serializeObject());
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: '$url_apply',
                data: {data: data},
                beforeSend: function(){
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    if (res == 1){
                        bootbox.alert('Thank you for applying', function(){
                            $('.recruited-popup').modal('hide');
                        }).find("div.modal-dialog").addClass("largeWidth");
                    } else if (res == -1){
                        bootbox.alert('You can only apply 5 times a day', function(){
                            $('.recruited-popup').modal('hide');
                        }).find("div.modal-dialog").addClass("largeWidth");
                    } else {
                        bootbox.alert('Error!', function(){
                            $('.recruited-popup').modal('hide');
                        }).find("div.modal-dialog").addClass("largeWidth");
                    }
                }
            });
            return false;
        }
    });
});
EOD;
?>

<?php
Yii::app()->clientScript->registerScript('modal-login-form-' . rand(), $script, CClientScript::POS_END);
?>