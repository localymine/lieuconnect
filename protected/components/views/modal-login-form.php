<?php
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/jquery.validate.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/additional-methods.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/jquery.validate.bootstrap.popover.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/localization/messages_' . $this->lang . '.js', CClientScript::POS_END);
?>

<!-- Large modal -->
<div id="modal-signup-popup" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="sign-in in-the-main sign-in-free">
                <div class="signin-form">
                    <h2>Enter your information below</h2>
                    <form id="modal-login-form" role="form" class="form-horizontal">
                        <div class="form-group">
                            <label for="UserLogin_username" class="col-sm-4 col-md-4  control-label">Username</label>
                            <div class="col-sm-7">
                                <input type="email" class="form-control" id="UserLogin_username" name="UserLogin[username]" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="login-password" class="col-sm-4 col-md-4 control-label">Password</label>
                            <div class="col-sm-7">
                                <input type="password" class="form-control" id="UserLogin_password" name="UserLogin[password]" placeholder="Password">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="inputPassword3" class="col-sm-3 col-md-3 control-label"></label>
                            <div class="col-sm-8 no-padding col-sm-offset-1">
                                <p><a href="<?php echo Yii::app()->createUrl('account/recover') ?>">Forgot your password</a> ?
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-6">
                                <input type="submit" class="btn btn-default trigger btn-go" value="Sign In">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="signup-notify">
                    <h2>Create an account today.<br> It's fast, free and easy</h2>
                    <p>Your benefits include:</p>
                    <ul>
                        <li>Free Student Membership</li>
                        <li>Add schools to your customized college list</li>
                        <li>Browse and filter awards and intership ease.</li>
                        <li>Tuned to the most meaningful education videos on the web.</li>
                        <li>Get email reminders</li>
                    </ul>
                    <a href="<?php echo Yii::app()->createUrl('account/signup') ?>" class="btn btn-default trigger btn-go">Sign Up</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$url_login = Yii::app()->createUrl('account/do_login');

$script = <<< EOD
$(function() {
    var form_valid = $('#modal-login-form');

    form_valid.validate_popover({
        lang: '$this->lang',
        popoverPosition: 'top',
        rules: {
            'UserLogin[username]' : {
                required: true,
                email: true,
            },
            'UserLogin[password]' : {
                required: true,
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
        submitHandler: function (form) {
            var username = $('#modal-login-form #UserLogin_username').val();
            var password = $('#modal-login-form #UserLogin_password').val();
            $.ajax({
                type: 'POST',
                url: '$url_login',
                data: {username: username, password: password},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    var res = $.parseJSON(res);
                    if (res.CODE == 'ERR') {
                        loader.stop();
                        bootbox.alert(res.MESS, function() {
                        }).find("div.modal-dialog").addClass("largeWidth");
                    } else if (res.CODE == 'OK') {
                        location.href = location.href;
                    }
                },
                error: function() {
                    loader.stop();
                    bootbox.alert('Error', function() {
                    }).find("div.modal-dialog").addClass("largeWidth");
                }
            });
            return false;
        }
    });
        
    $('body').on('click', function (e) {
    $('[data-toggle="popover"]').each(function () {
        //the 'is' for buttons that trigger popups
        //the 'has' for icons within a button that triggers a popup
        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0) {
            $(this).popover('hide');
        }
    });
    });
});
EOD;
?>

<?php
Yii::app()->clientScript->registerScript('modal-login-form-' . rand(), $script, CClientScript::POS_END);
?>