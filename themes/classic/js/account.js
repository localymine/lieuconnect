$(function() {
    $('#AProfiles_country').on('change', function() {
        var value = $(this).val();
        if (value != '') {
            loader.start();
            $.ajax({
                type: 'POST',
                url: 'load_state',
                data: {id: value},
                success: function(res) {
                    $('#AProfiles_state').html(res);
                    loader.stop();
                }
            });
        }
    });

    $('#AProfiles_state').on('change', function() {
        var value = $(this).val();
        if (value != '') {
            loader.start();
            $.ajax({
                type: 'POST',
                url: 'load_city',
                data: {id: value},
                success: function(res) {
                    $('#AProfiles_city').html(res);
                    loader.stop();
                }
            });
        }
    });

    var form_valid = $('#signup-form');

    form_valid.validate_popover({
        lang: 'vi',
        popoverPosition: 'top',
        rules: {
            'AProfiles[firstname]': {
                required: true,
                minlength: 3
            },
            'AProfiles[lastname]': {
                required: true,
                minlength: 3
            },
            'SignupForm[email]': {
                required: true,
                email: true,
                remote: {
                    url: 'existsEmail',
                    type: 'POST',
                    async: false
                }
            },
            'SignupForm[username]': {
                required: true,
                minlength: 3,
                remote: {
                    url: 'existsUsername',
                    type: 'POST',
                    async: false
                }
            },
            'SignupForm[password]': {
                required: true, 
                minlength: 6
            },
            'SignupForm[verifyPassword]': {
                required: true, 
                equalTo: "#SignupForm_password", 
                minlength: 6
            },
            'AProfiles[country]': {
                required: true
            },
            'SignupForm[verifyCode]': {
                required: true
            },
            'agree': {
                required: true
            }
        },
        messages: {
            'SignupForm[email]': {
                remote: '$err_mes_email'
            },
            'SignupForm[username]': {
                remote: '$err_mes_username'
            },
            'SignupForm[verifyPassword]': {}
        },
        submitHandler: function(form) {
            form.submit();
            return false;
        }
    });
});

$(window).resize(function() {
    $.validator.reposition();
});