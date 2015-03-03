<?php
$this->pageTitle = Common::t('My Profile', 'post') . ' | ' . 'Lilely Connect';

Common::register_css(Yii::app()->theme->baseUrl . '/css/datepicker.css');
Common::register_js(Yii::app()->theme->baseUrl . '/js/bootstrap-datepicker.js', CClientScript::POS_END);
//
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/jquery.validate.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/additional-methods.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/jquery.validate.bootstrap.popover.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/localization/messages_' . $this->lang . '.js', CClientScript::POS_END);

Common::register_js(Yii::app()->theme->baseUrl . '/js/plugins.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/myprofile.js', CClientScript::POS_END);
?>

<div class="table-main">
    <ul class="edit-profile">
        <!-- Extracurricular List -->
        <li>
            <h4><?php echo Common::t('About', 'account') ?></h4>
            <div id="about-list">
                <?php $this->renderPartial('_about', array('user' => $user, 'profile' => $profile)) ?>
            </div>
        </li>

        <li>
            <h4 data-toggle="modal" data-target="#contactModal"><?php echo Common::t('Contact Information', 'account') ?></h4>
            <div id="contact-list">
                <?php $this->renderPartial('_contact_info', array('user' => $user, 'profile' => $profile)) ?>
            </div>
        </li>
        <li>
            <h4><?php echo Common::t('Settings', 'account') ?></h4>
            <div id="profile-settings">
                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="checkbox">
                                <label>
                                    <?php echo CHtml::checkBox('public_profile', $profile->public_profile, array('class'=>'setting', 'data-type' => 'profile')) ?><?php echo Common::t('Public Profile', 'account') ?>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="checkbox">
                                <label>
                                    <?php echo CHtml::checkBox('public_resume', $profile->public_resume, array('class'=>'setting', 'data-type' => 'resume')) ?><?php echo Common::t('Public Resume', 'account') ?>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </li>
    </ul>
</div>

<!-- Modal About -->
<div class="modal fade recruited-popup" tabindex="-1" id="aboutModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content resume-frame">
            <div>
                <h2>About</h2>
                <form id="form-profile-about" role="form" class="form-horizontal">
                    <div class="form-group">
                        <label for="fristname" class=" col-sm-3 col-md-3 control-label">First Name</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('firstname', $profile->firstname, array('class' => 'form-control firstname-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="lastname" class=" col-sm-3 col-md-3 control-label">Last Name</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('lastname', $profile->lastname, array('class' => 'form-control lastname-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email" class=" col-sm-3 col-md-3 control-label">Email</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('email', $user->email, array('class' => 'form-control email-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="phone" class=" col-sm-3 col-md-3 control-label">Phone</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('phone', $profile->phone, array('class' => 'form-control phone-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="birthday" class=" col-sm-3 col-md-3 control-label">Birth Date</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('birth_date', $profile->birth_date, array('data-date-format' => 'yyyy-mm-dd', 'readonly' => 'readonly', 'class' => 'form-control datepicker birthday-text')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="gender" class=" col-sm-3 col-md-3 gradyear-text control-label">Gender</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::dropDownList('gender', $profile->gender, AUsers::itemAlias('Gender'), array('class' => 'form-control gender-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-6">
                            <button type="submit" class="btn btn-primary btn btn-go button-update">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Change Password -->
<div class="modal fade recruited-popup" tabindex="-1" id="passwordModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content resume-frame">
            <div>
                <h2>Change Your Password</h2>
                <form id="form-profile-password" role="form" class="form-horizontal">
                    <div class="form-group">
                        <label for="oldPassword" class=" col-sm-5 col-md-5 control-label">Current Passsword</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::passwordField('oldPassword', $model_password->oldPassword, array('class' => 'form-control')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class=" col-sm-5 col-md-5 control-label">New Password</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::passwordField('password', $model_password->password, array('class' => 'form-control')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="verifyPassword" class=" col-sm-5 col-md-5 control-label">Confirm New Password</label>
                        <div class="col-sm-7">
                            <?php echo CHtml::passwordField('verifyPassword', $model_password->verifyPassword, array('class' => 'form-control')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-6">
                            <button type="submit" class="btn btn-primary btn btn-go button-update">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Contact Information -->
<div class="modal fade recruited-popup" tabindex="-1" id="contactModal" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content resume-frame">
            <div>
                <h2>Contact Information</h2>
                <form id="form-profile-contact-info" role="form" class="form-horizontal">
                    <div class="form-group">
                        <label for="address" class=" col-sm-3 col-md-3 control-label">Adress</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('address', $profile->address, array('class' => 'form-control address-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="country" class=" col-sm-3 col-md-3 control-label">Country</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::dropDownList('country', $profile->country, CHtml::listData($countries, 'countryID', 'countryName'), array('class' => 'form-control country-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="state" class=" col-sm-3 col-md-3 control-label">State</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::dropDownList('state', $profile->state, CHtml::listData($states, 'stateID', 'stateName'), array('class' => 'form-control state-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="city" class=" col-sm-3 col-md-3 control-label">City</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::dropDownList('city', $profile->city, CHtml::listData($cities, 'cityID', 'cityName'), array('class' => 'form-control city-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="zipcode" class=" col-sm-3 col-md-3 control-label">Zip</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('zipcode', $profile->zipcode, array('class' => 'form-control zip-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="expectation" class=" col-sm-12 col-md-12 text-left">What would you like to study?</label>
                        <div class="col-sm-offset-3 col-sm-9">
                            <?php echo CHtml::textArea('expectation', $profile->expectation, array('class' => 'form-control description-text', 'style' => 'resize: none;')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="grade_year" class=" col-sm-3 col-md-3 control-label">Grade Year</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::dropDownList('grade_year', $profile->grade_year , Common::get_years_from_current(), array('class' => 'form-control gradeyear-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="inputEmail3" class=" col-sm-3 col-md-3 control-label">GPA</label>
                        <div class="col-sm-9">
                            <?php echo CHtml::textField('gpa', $profile->gpa, array('class' => 'form-control gpa-text')) ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-6 col-sm-6">
                            <button type="submit" class="btn btn-primary btn btn-go button-update">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>