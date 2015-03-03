function updateAboutToServer(callBack) {
    //ajax   
    callBack();
}

$('body').on('click', '#aboutModal .button-update', function() {
    ///Get Name Text
    var form_valid = $('#form-profile-about');
    form_valid.validate_popover({
        popoverPosition: 'top',
        rules: {
            'firstname': {
                required: true
            },
            'lastname': {
                required: true
            },
            'email': {
                required: true,
                email: true
            },
            'birthday': {
                required: true
            }
        },
        showErrors: function(errorMap, errorList) {
            // Clean up any tooltips for valid elements
            $.each(this.validElements(), function(index, element) {
                var $element = $(element);
                $element.data("title", "") // Clear the title - there is no error associated anymore
                        .removeClass("error")
                        .tooltip("destroy");
            });
            // Create new tooltips for invalid elements
            $.each(errorList, function(index, error) {
                var $element = $(error.element);
                $element.tooltip("destroy") // Destroy any pre-existing tooltip so we can repopulate with new tooltip content
                        .data("title", error.message)
                        .addClass("error")
                        .tooltip(); // Create a new tooltip based on the error messsage we just set in the title
            });
        },
        submitHandler: function(form) {
            var data = JSON.stringify(form_valid.serializeObject());
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'profile/update_about',
                data: {data: data},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    if (res['CODE'] == 'ERR') {
                        bootbox.alert(res['MESS'], function() {
                        }).find("div.modal-dialog").addClass("largeWidth");
                    } else {
                        var firstname = $('#aboutModal .firstname-text').val();
                        var lastname = $('#aboutModal .lastname-text').val();
                        var email = $('#aboutModal .email-text').val();
                        var phone = $('#aboutModal .phone-text').val();
                        var birthday = $('#aboutModal .birthday-text').val();
                        var gender = $('#aboutModal .gender-text option:selected').text();

                        updateAboutToServer(function() {
                            $('#aboutModal').modal('hide');
                            $('#about-list').find('.firstname').text(firstname);
                            $('#about-list').find('.lastname').text(lastname);
                            $('#about-list').find('.email').text(email);
                            $('#about-list').find('.phone').text(phone);
                            $('#about-list').find('.birthday').text(birthday);
                            $('#about-list').find('.gender').text(gender);
                        });
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
});

function updateContactToServer(callBack) {
    //ajax   
    callBack();
}

$('body').on('click', '#contactModal .button-update', function() {
    ///Get Name Text
    var form_valid = $('#form-profile-contact-info');
    form_valid.validate_popover({
        popoverPosition: 'top',
        rules: {
            'address': {
                required: true
            },
            'country': {
                required: true
            },
            'grade_year': {
                required: true
            },
            'gpa': {
                required: true
            }
        },
        showErrors: function(errorMap, errorList) {
            // Clean up any tooltips for valid elements
            $.each(this.validElements(), function(index, element) {
                var $element = $(element);
                $element.data("title", "") // Clear the title - there is no error associated anymore
                        .removeClass("error")
                        .tooltip("destroy");
            });
            // Create new tooltips for invalid elements
            $.each(errorList, function(index, error) {
                var $element = $(error.element);
                $element.tooltip("destroy") // Destroy any pre-existing tooltip so we can repopulate with new tooltip content
                        .data("title", error.message)
                        .addClass("error")
                        .tooltip(); // Create a new tooltip based on the error messsage we just set in the title
            });
        },
        submitHandler: function(form) {
            var data = JSON.stringify(form_valid.serializeObject());
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'profile/update_contact_info',
                data: {data: data},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    if (res['CODE'] == 'ERR') {
                        bootbox.alert(res['MESS'], function() {
                        }).find("div.modal-dialog").addClass("largeWidth");
                    } else {
                        var address = $('#contactModal .address-text').val();
                        var city = $('#contactModal .city-text option:selected').text();
                        var zip = $('#contactModal .zip-text').val();
                        var state = $('#contactModal .state-text option:selected').text();
                        var country = $('#contactModal .country-text option:selected').text();
                        var description = $('#contactModal .description-text').val();
                        var gradeyear = $('#contactModal .gradeyear-text option:selected').text();
                        var gpa = $('#contactModal .gpa-text').val();

                        updateContactToServer(function() {
                            $('#contactModal').modal('hide');
                            $('#contact-list').find('.address').text(address);
                            $('#contact-list').find('.city').text(city);
                            $('#contact-list').find('.zip').text(zip);
                            $('#contact-list').find('.state').text(state);
                            $('#contact-list').find('.country').text(country);
                            $('#contact-list').find('.description').text(description);
                            $('#contact-list').find('.gradeyear').text(gradeyear);
                            $('#contact-list').find('.gpa').text(gpa);
                        });
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
});

$('body').on('click', '#passwordModal .button-update', function() {
    var form_valid = $('#form-profile-password');
    form_valid.validate_popover({
        popoverPosition: 'top',
        rules: {
            'oldPassword': {
                required: true
            },
            'password': {
                required: true
            },
            'verifyPassword': {
                required: true,
                equalTo: '#password'
            }
        },
        showErrors: function(errorMap, errorList) {
            // Clean up any tooltips for valid elements
            $.each(this.validElements(), function(index, element) {
                var $element = $(element);
                $element.data("title", "") // Clear the title - there is no error associated anymore
                        .removeClass("error")
                        .tooltip("destroy");
            });
            // Create new tooltips for invalid elements
            $.each(errorList, function(index, error) {
                var $element = $(error.element);
                $element.tooltip("destroy") // Destroy any pre-existing tooltip so we can repopulate with new tooltip content
                        .data("title", error.message)
                        .addClass("error")
                        .tooltip(); // Create a new tooltip based on the error messsage we just set in the title
            });
        },
        submitHandler: function(form) {
            var data = JSON.stringify(form_valid.serializeObject());
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'profile/change_password',
                data: {data: data},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    if (res['CODE'] == 'ERR') {
                        bootbox.alert(res['MESS'], function() {
                        }).find("div.modal-dialog").addClass("largeWidth");
                    } else {
                        bootbox.alert(res['MESS'], function() {
                            $('#passwordModal').modal('hide');
                            form_valid[0].reset();
                        }).find("div.modal-dialog").addClass("largeWidth");
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
});

$('.setting').click(function() {
    //
    var type = $(this).data('type');
    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: 'profile/setting',
        data: {type: type},
        beforeSend: function() {
            loader.start();
        },
        success: function(res) {
            loader.stop();
        },
        error: function() {
            loader.stop();
        }
    });
});

/*---------- begin datatime picker ------------*/
$(function() {
    $.fn.datepicker.defaults.format = "yyyy-mm-dd";

    $('#aboutModal .birthday-text').datepicker();
});
/*---------- end datatime picker ------------*/