<?php
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/jquery.validate.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/additional-methods.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/validate/jquery.validate.bootstrap.popover.min.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/localization/messages_' . $this->lang . '.js', CClientScript::POS_END);
?>

<div class="main stuff text-center">
    <h2><strong>Want more stuff like this ?</strong></h2>
    <p>Lilelyconnect brings to you your dreams and is a bridge to a better futture!</p>
    <p>Join out email list today to be inspired and learn how to reach your goals!</p>
    <div class="row">
        <?php
        $form_high_school = $this->beginWidget('CActiveForm', array(
            'id' => 'subcribe-form',
            'method' => 'post',
            'action' => Yii::app()->createUrl('subcribe'),
            'htmlOptions' => array(
                'class' => ''
            )
        ));
        ?>
        <div class="col-md-6 col-sm-10 col-xs-10 col-xs-offset-1 col-sm-offset-1 no-padding col-md-offset-3">
            <div class="col-md-9 col-xs-9 col-sm-9 no-padding">
                <input id="email" name="email" class="form-control" placeholder="<?php echo Common::t('Your Email') ?>" type="email">
            </div>
            <div class="col-md-3 col-sm-3 col-xs-3 no-padding">
                <input id="send-subcribe" type="submit" class="btn btn-default btn-go" value="<?php echo Common::t('Submit') ?>" />
                
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
    <p class="privacy">By submitting above you agree to the LilelyConnect <a href="#modal-privacy-policy" data-toggle="modal" data-original-title="Privacy Policy">privacy policy</a></p>
</div>

<div id="modal-privacy-policy" class="modal fade" aria-hidden="hidden" role="dialog">
    <div class="modal-dialog"><i class="fa fa-apple"></i><i class="fa fa-apple"></i><i class="fa fa-apple"></i></div>
</div>

<div id="modal-subcribe-thanks" class="modal fade" aria-hidden="hidden" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header"></div>
            <div class="modal-body">
                <i class="fa fa-tasks"></i>
            </div>
        </div>
    </div>
</div>

<?php
$err_mes_email = Common::t('Email already subscribed', 'post');
$url_check = Yii::app()->createUrl('subcribe/exists');
$url_regist = Yii::app()->createUrl('subcribe/regist');
$script = <<< EOD
$(function() {
    
    var form_valid = $('#subcribe-form');

    var validator = form_valid.validate_popover({
        lang: '$this->lang',
        popoverPosition: 'top',
        errorClass: "has-error",
        highlight: function(label) {
            $(label).closest('.control-group').removeClass('has-success').addClass('has-error');
        },
        successClass: "has-success",
        unhighlight:function(label) {
            $(label).closest('.control-group').text('OK!').addClass('has-success');
        },
        rules: {
            email: {
                email: true,
                remote: {
                    url: '$url_check',
                    type: 'POST',
                    async: false
                }
            }
        },
        messages: {
            email: {
                remote: '$err_mes_email'
            }
        },
        submitHandler: function (form) {
            var email = $('#email').val();
            if (email != ''){
                $.ajax({
                    type: 'POST',
                    url: '$url_regist',
                    data: {email: email},
                    success: function(res) {
                        if (res == 1) {
                            $('#modal-subcribe-thanks').modal('show').on('hidden.bs.modal', function(){
                                $('#email').val('');
                            });
                        }
                    }
                });
            }
            return false;
        }
    });

});

$(window).resize(function() {
    $.validator.reposition();
});
EOD;
?>

<?php
Yii::app()->clientScript->registerScript('send-subcribe-form-' . rand(), $script, CClientScript::POS_END);
?>