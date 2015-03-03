var currentElement;
var currentSubElement;

///Call To Server
function addNewResumeToServer(id) {

    ///Variables 
    var activityValue = $('#curricularModal .activity-text').val();
    var positionValue = $('#curricularModal .position-text').val();
    var descriptionValue = $('#curricularModal .description-text').val();
    var startValue = $('#curricularModal .start-text').val();
    var endValue = $('#curricularModal .end-text').val();

    ///Create New Element
    var liTag = $('<li data-id="' + id + '" class="resume-item">');
    var container = $('<div class="curricular">');
    var pTage = $('<p>');
    var activity = $('<span class="activity">');
    var date = $('<span class="date">');
    var start = $('<span class="start">');
    var end = $('<span class="end">');
    var position = $('<p class="position">');
    var description = $('<p class="description">');
    var spanTag = $('<span class="glyphicon glyphicon-minus">');

    activity.text(activityValue);
    position.text(positionValue);
    description.text(descriptionValue);
    start.text(startValue);
    end.text(endValue);
    date.append(start);
    date.append(" ~ ");
    date.append(end);
    pTage.append(activity);
    pTage.append(date);
    container.append(pTage);
    container.append(position);
    container.append(description);
    liTag.append(spanTag);
    liTag.append(container);

    currentElement.find('ul').append(liTag);
    $('#curricularModal').modal('hide');
}
function updateResumeToServer(id, callBack) {
    //ajax
    callBack();
}
function deleteResumeToServer(id, callBack) {
    callBack();
}

$('body').on('click', '#curricularList .glyphicon-plus', function() {
    currentElement = $(this).closest('li');
    $('.button-update').hide();
    $('.button-delete').hide();
    $('#curricularModal .form-group').show();
    $('#curricularModal .resume-text').show();
    $('.button-add').show();
    //
    $('#form-resume-extra')[0].reset();
});
$('body').on('click', '#curricularModal .button-add', function() {
    ///Get Name Text
    var form_valid = $('#form-resume-extra');
    form_valid.validate_popover({
        popoverPosition: 'top',
        rules: {
            'activity': {
                required: true,
            },
            'position': {
                required: true,
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
            var reid = $('#curricularList').data('id');
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'resume/add_extra',
                data: {data: data, reid: reid},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    addNewResumeToServer(res);
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
$('body').on('click', '#curricularModal .button-update', function() {
    // validate
    var form_valid = $('#form-resume-extra');
    form_valid.validate_popover({
        popoverPosition: 'top',
        rules: {
            'activity': {
                required: true,
            },
            'position': {
                required: true,
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
            var reid = $('#curricularList').data('id');
            var id = currentSubElement.attr('data-id');
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'resume/update_extra',
                data: {data: data, reid: reid, id: id},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    //
                    ///Get Name Text
                    var activity = $('#curricularModal .activity-text').val();
                    var position = $('#curricularModal .position-text').val();
                    var description = $('#curricularModal .description-text').val();
                    var start = $('#curricularModal .start-text').val();
                    var end = $('#curricularModal .end-text').val();

                    if ($('#curricularModal #uptonow').is(':checked') == true) {
                        end = "Now";
                    }

                    var id = currentSubElement.attr('data-id');
                    updateResumeToServer(id, function() {
                        $('#curricularModal').modal('hide');
                        currentSubElement.find('.activity').text(activity);
                        currentSubElement.find('.position').text(position);
                        currentSubElement.find('.description').text(description);
                        currentSubElement.find('.start').text(start);
                        currentSubElement.find('.end').text(end);
                    });
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
$('body').on('click', '#curricularModal .button-delete', function() {
    var reid = $('#curricularList').data('id');
    var id = currentSubElement.attr('data-id');
    //
    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: 'resume/delete_extra',
        data: {reid: reid, id: id},
        beforeSend: function() {
            loader.start();
        },
        success: function(res) {
            loader.stop();
            //
            var id = currentSubElement.attr('data-id');
            deleteResumeToServer(id, function() {
                currentSubElement.remove();
                $('#curricularModal').modal('hide');
            });
        },
        error: function() {
            loader.stop();
            bootbox.alert('Error', function() {
                $('#curricularModal').modal('hide');
            }).find("div.modal-dialog").addClass("largeWidth");
        }
    });
});
$('body').on('click', '#curricularList .glyphicon-minus', function() {
    ///Get Name Text

    currentSubElement = $(this).closest('li');
    $('#curricularModal').modal('show');
    $('.button-update').hide();
    $('#curricularModal .form-group').hide();
    $('#curricularModal .form-group:last-child').show();
    $('.button-delete').show();
    $('.resume-text').hide();
    $('.button-add').hide();
});
$('body').on('dblclick', '#curricularList .resume-item', function() {
    var activityValue = $(this).find('.activity').text();
    var positionValue = $(this).find('.position').text();
    var descriptionValue = $(this).find('.description').text();
    var startDate = $(this).find('.start').text();
    var endDate = $(this).find('.end').text();

    currentSubElement = $(this);
    $('#curricularModal .activity-text').val(activityValue);
    $('#curricularModal .position-text').val(positionValue);
    $('#curricularModal .description-text').val(descriptionValue);
    $('#curricularModal .start-text').val(startDate);
    $('#curricularModal .start-text').datepicker('setValue', startDate);
    $('#curricularModal .end-text').val(endDate);
    $('#curricularModal .end-text').datepicker('setValue', endDate);

    $('#curricularModal').modal('show');
    $('#curricularModal .form-group').show();
    $('.button-add').hide();
    $('.button-delete').hide();
    $('.resume-text').show();
    $('.button-update').show();

    if (endDate == 'Now') {
        $('#curricularModal #uptonow').prop('checked', true);
        $('#end_extra_block').hide();
        $('#curricularModal .end-text').val('');
    } else {
        $('#curricularModal #uptonow').prop('checked', false);
        $('#end_extra_block').show();
    }
});
$('#curricularModal').on('hidden.bs.modal', function(e) {

    // do something...
    $('#curricularModal .activity-text').val("");
    $('#curricularModal .position-text').val("");
    $('#curricularModal .description-text').val("");
    $('#curricularModal .start-text').val("");
    $('#curricularModal .end-text').val("");
});


///// Experience Modal
///Call To Server
function addNewResumeExpToServer(id, callBack) {
    ///Create New Element
    ///Variables 
    var employerValue = $('#experienceModal .employer-text').val();
    var positionValue = $('#experienceModal .position-text').val();
    var descriptionValue = $('#experienceModal .description-text').val();
    var industryValue = $("#experienceModal option:selected").text();
    var startValue = $('#experienceModal .start-text').val();
    var endValue = $('#experienceModal .end-text').val();

    ///Create New Element
    var liTag = $('<li data-id="' + id + '" class="resume-item">');
    var container = $('<div class="curricular">');
    var pTage = $('<p>');
    var employer = $('<span class="employer">');
    var industry = $('<p class="industry">');
    var date = $('<span class="date">');
    var start = $('<span class="start">');
    var end = $('<span class="end">');
    var position = $('<p class="position">');
    var description = $('<p class="description">');
    var spanTag = $('<span class="glyphicon glyphicon-minus">');


    employer.text(employerValue);
    position.text(positionValue);
    description.text(descriptionValue);
    industry.text(industryValue);
    start.text(startValue);
    end.text(endValue);
    date.append(start);
    date.append(" ~ ");
    date.append(end);
    pTage.append(employer);
    pTage.append(date);
    container.append(pTage);
    container.append(industry);
    container.append(position);
    container.append(description);
    liTag.append(spanTag);
    liTag.append(container);

    currentElement.find('ul').append(liTag);
    $('#experienceModal').modal('hide');
}
function updateResumeExpToServer(id, callBack) {
    callBack();
}
function deleteResumeExpToServer(id, callBack) {
    callBack();
}

$('body').on('click', '#experienceList .glyphicon-plus', function() {
    currentElement = $(this).closest('li');
    $('.button-update').hide();
    $('.button-delete').hide();
    $('#experienceModal .form-group').show();
    $('.resume-text').show();
    $('.button-add').show();
    //
    $('#form-resume-experience')[0].reset();
});
$('body').on('click', '#experienceModal .button-add', function() {
    ///Get Name Text
    var form_valid = $('#form-resume-experience');
    form_valid.validate_popover({
        popoverPosition: 'top',
        rules: {
            'employer': {
                required: true,
            },
            'activity': {
                required: true,
            },
            'position': {
                required: true,
            },
            'industry': {
                required: true,
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
            var reid = $('#experienceList').data('id');
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'resume/add_exper',
                data: {data: data, reid: reid},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    addNewResumeExpToServer(res);
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
$('body').on('click', '#experienceModal .button-update', function() {
    ///Get Name Text
    var form_valid = $('#form-resume-experience');
    form_valid.validate_popover({
        popoverPosition: 'top',
        rules: {
            'employer': {
                required: true,
            },
            'activity': {
                required: true,
            },
            'position': {
                required: true,
            },
            'industry': {
                required: true,
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
            var reid = $('#experienceList').data('id');
            var id = currentSubElement.attr('data-id');
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'resume/update_exper',
                data: {data: data, reid: reid, id: id},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    //
                    var employerValue = $('#experienceModal .employer-text').val();
                    var positionValue = $('#experienceModal .position-text').val();
                    var descriptionValue = $('#experienceModal .description-text').val();
                    var industryValue = $("#experienceModal option:selected").text();
                    var startValue = $('#experienceModal .start-text').val();
                    var endValue = $('#experienceModal .end-text').val();

                    if ($('#experienceModal #uptonow').is(':checked') == true) {
                        endValue = "Now";
                    }

                    var id = currentSubElement.attr('data-id');
                    updateResumeExpToServer(id, function() {

                        currentSubElement.find('.employer').text(employerValue);
                        currentSubElement.find('.position').text(positionValue);
                        currentSubElement.find('.description').text(descriptionValue);
                        currentSubElement.find(".industry").text(industryValue);
                        currentSubElement.find('.start').text(startValue);
                        currentSubElement.find('.end').text(endValue);

                        $('#experienceModal').modal('hide');
                    });
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
$('body').on('click', '#experienceModal .button-delete', function() {
    var reid = $('#experienceList').data('id');
    var id = currentSubElement.attr('data-id');
    //
    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: 'resume/delete_exper',
        data: {reid: reid, id: id},
        beforeSend: function() {
            loader.start();
        },
        success: function(res) {
            loader.stop();
            //
            var id = currentSubElement.attr('data-id');
            deleteResumeToServer(id, function() {
                currentSubElement.remove();
                $('#experienceModal').modal('hide');
            });
        },
        error: function() {
            loader.stop();
            bootbox.alert('Error', function() {
                $('#curricularModal').modal('hide');
            }).find("div.modal-dialog").addClass("largeWidth");
        }
    });
});
$('body').on('click', '#experienceList .glyphicon-minus', function() {
    ///Get Name Text
    currentSubElement = $(this).closest('li');
    $('#experienceModal').modal('show');
    $('.button-update').hide();
    $('#experienceModal .form-group').hide();
    $('#experienceModal .form-group:last-child').show();
    $('.button-delete').show();
    $('.resume-text').hide();
    $('.button-add').hide();
});
$('body').on('dblclick', '#experienceList .resume-item', function() {

    var employerValue = $(this).find('.employer').text();
    var positionValue = $(this).find('.position').text();
    var descriptionValue = $(this).find('.description').text();
    var industryValue = $(this).find(".industry").text();
    var startDate = $(this).find('.start').text();
    var endDate = $(this).find('.end').text();

    currentSubElement = $(this);
    $('#experienceModal .employer-text').val(employerValue);
    $('#experienceModal .position-text').val(positionValue);
    $('#experienceModal .description-text').val(descriptionValue);
    $("#experienceModal option").each(function() {
        if ($(this).text() == industryValue) {
            $(this).attr('selected', 'selected');
        }
    });
    $('#experienceModal .start-text').val(startDate);
    $('#experienceModal .start-text').datepicker('setValue', startDate);
    $('#experienceModal .end-text').val(endDate);
    $('#experienceModal .end-text').datepicker('setValue', endDate)

    $('#experienceModal').modal('show');
    $('#experienceModal .form-group').show();
    $('.button-add').hide();
    $('.button-delete').hide();
    $('.resume-text').show();
    $('.button-update').show();

    if (endDate == 'Now') {
        $('#experienceModal #uptonow').prop('checked', true);
        $('#end_exper_block').hide();
        $('#experienceModal .end-text').val('');
    } else {
        $('#experienceModal #uptonow').prop('checked', false);
        $('#end_exper_block').show();
    }
});
$('#experienceModal').on('hidden.bs.modal', function(e) {
    // do something...
    $('.resume-text').val("");
});

///// Education Modal
///Call To Server
function addNewResumeEdcToServer(id, callBack) {
    ///Create New Element
    ///Variables 
    var highschoolValue = $('#educationModal .highschool-text').val();
    var gradyearValue = $('#educationModal option:selected').text();
    var gpaValue = $('#educationModal .gpa-text').val();
    var classrankValue = $("#educationModal .classrank-text").val();

    ///Create New Element
    var liTag = $('<li data-id="' + id + '" class="resume-item">');
    var container = $('<div class="curricular">');
    var gradyear_p = $('<p>Grade Year: </p>');
    var highschool = $('<p class="highschool">');
    var gradyear = $('<span class="gradyear">');
    var gpa_p = $('<p>GAP: </p>');
    var gpa = $('<span class="gpa">');
    var classrank_p = $('<p>Class rank: </p>');
    var classrank = $('<span class="classrank">');
    var spanTag = $('<span class="glyphicon glyphicon-minus">');


    highschool.text(highschoolValue);
    gradyear.text(gradyearValue);
    gpa.text(gpaValue);
    classrank.text(classrankValue);
    gradyear_p.append(gradyear);
    classrank_p.append(classrank);
    gpa_p.append(gpa);
    container.append(highschool);
    container.append(gradyear_p);
    container.append(gpa_p);
    container.append(classrank_p);
    liTag.append(spanTag);
    liTag.append(container);

    currentElement.find('ul').append(liTag);
    $('#educationModal').modal('hide');
}
function updateResumeEdcToServer(id, callBack) {
    callBack();
}
function deleteResumeEdcToServer(id, callBack) {
    callBack();
}

$('body').on('click', '#educationList .glyphicon-plus', function() {
    currentElement = $(this).closest('li');
    $('.button-update').hide();
    $('.button-delete').hide();
    $('#educationModal .form-group').show();
    $('.resume-text').show();
    $('.button-add').show();
    //
    $('#form-resume-education')[0].reset();
});
$('body').on('click', '#educationModal .button-add', function() {
    ///Get Name Text
    var form_valid = $('#form-resume-education');
    form_valid.validate_popover({
        popoverPosition: 'top',
        rules: {
            'school_name': {
                required: true,
            },
            'grade_year': {
                required: true,
            },
            'gpa': {
                required: true,
            },
            'class_rank': {
                required: true,
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
            var reid = $('#educationList').data('id');
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'resume/add_educa',
                data: {data: data, reid: reid},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    addNewResumeEdcToServer(res);
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
$('body').on('click', '#educationModal .button-update', function() {
    ///Get Name Text
    var form_valid = $('#form-resume-education');
    form_valid.validate_popover({
        popoverPosition: 'top',
        rules: {
            'school_name': {
                required: true,
            },
            'grade_year': {
                required: true,
            },
            'gpa': {
                required: true,
            },
            'class_rank': {
                required: true,
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
            var reid = $('#educationList').data('id');
            var id = currentSubElement.attr('data-id');
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'resume/update_educa',
                data: {data: data, reid: reid, id: id},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    //
                    var highschoolValue = $('#educationModal .highschool-text').val();
                    var gradyearValue = $('#educationModal option:selected').text();
                    var gpaValue = $('#educationModal .gpa-text').val();
                    var classrankValue = $("#educationModal .classrank-text").val();
                    var id = currentSubElement.attr('data-id');
                    updateResumeEdcToServer(id, function() {
                        currentSubElement.find('.highschool').text(highschoolValue);
                        currentSubElement.find('.gpa').text(gpaValue);
                        currentSubElement.find('.classrank').text(classrankValue);
                        currentSubElement.find(".gradyear").text(gradyearValue);

                        $('#educationModal').modal('hide');

                    });
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
$('body').on('click', '#educationModal .button-delete', function() {
    var reid = $('#educationList').data('id');
    var id = currentSubElement.attr('data-id');
    //
    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: 'resume/delete_educa',
        data: {reid: reid, id: id},
        beforeSend: function() {
            loader.start();
        },
        success: function(res) {
            loader.stop();
            //
            var id = currentSubElement.attr('data-id');
            deleteResumeEdcToServer(id, function() {
                currentSubElement.remove();
                $('#educationModal').modal('hide');
            });
        },
        error: function() {
            loader.stop();
            bootbox.alert('Error', function() {
                $('#curricularModal').modal('hide');
            }).find("div.modal-dialog").addClass("largeWidth");
        }
    });
});
$('body').on('click', '#educationList .glyphicon-minus', function() {
    ///Get Name Text
    currentSubElement = $(this).closest('li');
    $('#educationModal').modal('show');
    $('.button-update').hide();
    $('#educationModal .form-group').hide();
    $('#educationModal .form-group:last-child').show();
    $('.button-delete').show();
    $('.resume-text').hide();
    $('.button-add').hide();
});
$('body').on('dblclick', '#educationList .resume-item', function() {
    ///Variables 
    var highschoolValue = $(this).find('.highschool').text();
    var gradyearValue = $(this).find('.gradyear').text();
    var gpaValue = $(this).find('.gpa').text();
    var classrankValue = $(this).find('.classrank').text();
    currentSubElement = $(this);

    $('#educationModal .highschool-text').val(highschoolValue);
    $('#educationModal .gpa-text').val(gpaValue);
    $('#educationModal .classrank-text').val(classrankValue);
    $("#educationModal option").each(function() {
        if ($(this).text() == gradyearValue) {
            $(this).attr('selected', 'selected');
        }
    });

    $('#educationModal').modal('show');
    $('#educationModal .form-group').show();
    $('.button-add').hide();
    $('.button-delete').hide();
    $('.resume-text').show();
    $('.button-update').show();
});
$('#educationModal').on('hidden.bs.modal', function(e) {
    // do something...
    $('.resume-text').val("");
});

///// Aaward Modal
///Call To Server
function addNewResumeAwdToServer(id, callBack) {
    ///Create New Element
    var awardValue = $('#awardModal .award-text').val();

    ///Create New Element
    var liTag = $('<li data-id="' + id + '" class="resume-item">');
    var container = $('<div class="curricular">');
    var award = $('<p class="award">');
    var spanTag = $('<span class="glyphicon glyphicon-minus">');

    award.text(awardValue);
    container.append(award);
    liTag.append(spanTag);
    liTag.append(container);

    currentElement.find('ul').append(liTag);
    $('#awardModal').modal('hide');
}
function updateResumeAwdToServer(id, callBack) {
    callBack();
}
function deleteResumeAwdToServer(id, callBack) {
    callBack();
}

$('body').on('click', '#awardList .glyphicon-plus', function() {
    currentElement = $(this).closest('li');
    $('.button-update').hide();
    $('.button-delete').hide();
    $('#awardModal .form-group').show();
    $('.resume-text').show();
    $('.button-add').show();
});
$('body').on('click', '#awardModal .button-add', function() {
    ///Get Name Text
    var form_valid = $('#form-resume-honor-award');
    form_valid.validate_popover({
        popoverPosition: 'top',
        rules: {
            'description': {
                required: true,
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
            var reid = $('#awardList').data('id');
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'resume/add_honor',
                data: {data: data, reid: reid},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    addNewResumeAwdToServer(res);
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
$('body').on('click', '#awardModal .button-update', function() {
    ///Get Name Text
    var form_valid = $('#form-resume-honor-award');
    form_valid.validate_popover({
        popoverPosition: 'top',
        rules: {
            'description': {
                required: true,
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
            var reid = $('#awardList').data('id');
            var id = currentSubElement.attr('data-id');
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'resume/update_honor',
                data: {data: data, reid: reid, id: id},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    //
                    var awardValue = $('#awardModal .award-text').val();
                    var id = currentSubElement.attr('data-id');
                    updateResumeAwdToServer(id, function() {
                        currentSubElement.find('.award').text(awardValue);
                        $('#awardModal').modal('hide');
                    });
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
$('body').on('click', '#awardModal .button-delete', function() {
    var reid = $('#awardList').data('id');
    var id = currentSubElement.attr('data-id');
    //
    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: 'resume/delete_honor',
        data: {reid: reid, id: id},
        beforeSend: function() {
            loader.start();
        },
        success: function(res) {
            loader.stop();
            //
            var id = currentSubElement.attr('data-id');
            deleteResumeAwdToServer(id, function() {
                currentSubElement.remove();
                $('#awardModal').modal('hide');
            });
        },
        error: function() {
            loader.stop();
            bootbox.alert('Error', function() {
                $('#awardModal').modal('hide');
            }).find("div.modal-dialog").addClass("largeWidth");
        }
    });
});
$('body').on('click', '#awardList .glyphicon-minus', function() {
    ///Get Name Text
    currentSubElement = $(this).closest('li');
    $('#awardModal').modal('show');
    $('.button-update').hide();
    $('#awardModal .form-group').hide();
    $('#awardModal .form-group:last-child').show();
    $('.button-delete').show();
    $('.resume-text').hide();
    $('.button-add').hide();
});
$('body').on('dblclick', '#awardList .resume-item', function() {
    var awardValue = $(this).find('.award').text();
    currentSubElement = $(this);
    $('.award-text').val(awardValue);

    $('#awardModal').modal('show');
    $('#awardModal .form-group').show();
    $('.button-add').hide();
    $('.button-delete').hide();
    $('.resume-text').show();
    $('.button-update').show();
});
$('#awardModal').on('hidden.bs.modal', function(e) {
    // do something...
    $('.award-text').val("");
});

///// Skill Modal
///Call To Server
function addNewResumeSkiToServer(value, callBack) {
    ///Create New Element
    ///Create New Element
    var skillValue = $('#skillModal .skill-text').val();

    ///Create New Element
    var liTag = $('<li data-id="" class="resume-item">');
    var container = $('<div class="curricular">');
    var skill = $('<p class="skill">');
    var spanTag = $('<span class="glyphicon glyphicon-minus">');

    skill.text(skillValue);
    container.append(skill);
    liTag.append(spanTag);
    liTag.append(container);

    currentElement.find('ul').append(liTag);
    $('#skillModal').modal('hide');
}
function updateResumeSkiToServer(id, callBack) {
    callBack();
}
function deleteResumeSkiToServer(id, callBack) {
    callBack();
}

$('body').on('click', '#skillList .glyphicon-plus', function() {
    currentElement = $(this).closest('li');
    $('.button-update').hide();
    $('.button-delete').hide();
    $('#skillModal .form-group').show();
    $('.resume-text').show();
    $('.button-add').show();
});
$('body').on('click', '#skillModal .button-add', function() {
    ///Get Name Text
    var form_valid = $('#form-resume-skill');
    form_valid.validate_popover({
        popoverPosition: 'top',
        rules: {
            'description': {
                required: true,
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
            var reid = $('#skillList').data('id');
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'resume/add_skill',
                data: {data: data, reid: reid},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    addNewResumeSkiToServer(res);
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
$('body').on('click', '#skillModal .button-update', function() {
    ///Get Name Text
    var form_valid = $('#form-resume-skill');
    form_valid.validate_popover({
        popoverPosition: 'top',
        rules: {
            'description': {
                required: true,
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
            var reid = $('#skillList').data('id');
            var id = currentSubElement.attr('data-id');
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'resume/update_skill',
                data: {data: data, reid: reid, id: id},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    //
                    var skillValue = $('#skillModal .skill-text').val();
                    var id = currentSubElement.attr('data-id');
                    updateResumeSkiToServer(id, function() {
                        $('#skillModal').modal('hide');
                        currentSubElement.find('.skill').text(skillValue);
                    });
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
$('body').on('click', '#skillModal .button-delete', function() {
    var reid = $('#skillList').data('id');
    var id = currentSubElement.attr('data-id');
    //
    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: 'resume/delete_skill',
        data: {reid: reid, id: id},
        beforeSend: function() {
            loader.start();
        },
        success: function(res) {
            loader.stop();
            //
            var id = currentSubElement.attr('data-id');
            deleteResumeSkiToServer(id, function() {
                currentSubElement.remove();
                $('#skillModal').modal('hide');
            });
        },
        error: function() {
            loader.stop();
            bootbox.alert('Error', function() {
                $('#awardModal').modal('hide');
            }).find("div.modal-dialog").addClass("largeWidth");
        }
    });
});
$('body').on('click', '#skillList .glyphicon-minus', function() {
    ///Get Name Text
    currentSubElement = $(this).closest('li');
    $('#skillModal').modal('show');
    $('.button-update').hide();
    $('#skillModal .form-group').hide();
    $('#skillModal .form-group:last-child').show();
    $('.button-delete').show();
    $('.resume-text').hide();
    $('.button-add').hide();
});
$('body').on('dblclick', '#skillList .resume-item', function() {
    var skillValue = $(this).find('.skill').text();
    currentSubElement = $(this);
    $('.skill-text').val(skillValue);

    $('#skillModal').modal('show');
    $('#skillModal .form-group').show();
    $('.button-add').hide();
    $('.button-delete').hide();
    $('.resume-text').show();
    $('.button-update').show();
});
$('#skillModal').on('hidden.bs.modal', function(e) {
    // do something...
    $('.skill-text').val("");
});

///// Favorite Modal
///Call To Server
function addNewResumeFavToServer(id, callBack) {
    ///Create New Element
    ///Create New Element
    var musicValue = $('#favoriteModal .music-text').val();
    var quoteValue = $('#favoriteModal .quotes-text').val();
    var tvValue = $('#favoriteModal .tv-text').val();
    var bookValue = $('#favoriteModal .book-text').val();
    var movieValue = $('#favoriteModal .movie-text').val();
    var webValue = $('#favoriteModal .web-text').val();

    ///Create New Element
    var liTag = $('<li data-id="' + id + '" class="resume-item">');
    var container = $('<div class="curricular">');
    var music = $('<p class="music">');
    var quote = $('<p class="quote">');
    var tv = $('<p class="tv">');
    var book = $('<p class="book">');
    var movie = $('<p class="movie">');
    var web = $('<p class="web">');
    var spanTag = $('<span class="glyphicon glyphicon-minus">');

    music.text(musicValue);
    quote.text(quoteValue);
    tv.text(tvValue);
    book.text(bookValue);
    movie.text(movieValue);
    web.text(webValue);

    container.append(music);
    container.append(quote);
    container.append(tv);
    container.append(book);
    container.append(movie);
    container.append(web);
    liTag.append(spanTag);
    liTag.append(container);

    currentElement.find('ul').append(liTag);
    $('#favoriteModal').modal('hide');
}
function updateResumeFavToServer(id, callBack) {
    callBack();
}
function deleteResumeFavToServer(id, callBack) {
    callBack();
}

$('body').on('click', '#favoriteList .glyphicon-plus', function() {
    currentElement = $(this).closest('li');
    $('.button-update').hide();
    $('.button-delete').hide();
    $('#favoriteModal .form-group').show();
    $('.resume-text').show();
    $('.button-add').show();
});
$('body').on('click', '#favoriteModal .button-add', function() {
    ///Get Name Text
    var form_valid = $('#form-resume-favorite');
    form_valid.validate_popover({
        popoverPosition: 'top',
        rules: {
            'description': {
                required: true,
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
            var reid = $('#favoriteList').data('id');
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'resume/add_favorite',
                data: {data: data, reid: reid},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    addNewResumeFavToServer(res);
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
$('body').on('click', '#favoriteModal .button-update', function() {
    ///Get Name Text
    var form_valid = $('#form-resume-favorite');
    form_valid.validate_popover({
        popoverPosition: 'top',
        rules: {
            'description': {
                required: true,
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
            var reid = $('#favoriteList').data('id');
            var id = currentSubElement.attr('data-id');
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'resume/update_favorite',
                data: {data: data, reid: reid, id: id},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    //
                    var musicValue = $('#favoriteModal .music-text').val();
                    var quoteValue = $('#favoriteModal .quotes-text').val();
                    var tvValue = $('#favoriteModal .tv-text').val();
                    var bookValue = $('#favoriteModal .book-text').val();
                    var movieValue = $('#favoriteModal .movie-text').val();
                    var webValue = $('#favoriteModal .web-text').val();

                    var id = currentSubElement.attr('data-id');
                    updateResumeFavToServer(id, function() {
                        $('#favoriteModal').modal('hide');
                        currentSubElement.find('.music').text(musicValue);
                        currentSubElement.find('.quote').text(quoteValue);
                        currentSubElement.find('.tv').text(tvValue);
                        currentSubElement.find('.book').text(bookValue);
                        currentSubElement.find('.movie').text(movieValue);
                        currentSubElement.find('.web').text(webValue);
                    });
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
$('body').on('click', '#favoriteModal .button-delete', function() {
    var reid = $('#favoriteList').data('id');
    var id = currentSubElement.attr('data-id');
    //
    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: 'resume/delete_favorite',
        data: {reid: reid, id: id},
        beforeSend: function() {
            loader.start();
        },
        success: function(res) {
            loader.stop();
            //
            var id = currentSubElement.attr('data-id');
            deleteResumeFavToServer(id, function() {
                currentSubElement.remove();
                $('#favoriteModal').modal('hide');
            });
        },
        error: function() {
            loader.stop();
            bootbox.alert('Error', function() {
                $('#awardModal').modal('hide');
            }).find("div.modal-dialog").addClass("largeWidth");
        }
    });
});
$('body').on('click', '#favoriteList .glyphicon-minus', function() {
    ///Get Name Text
    currentSubElement = $(this).closest('li');
    $('#favoriteModal').modal('show');
    $('.button-update').hide();
    $('#favoriteModal .form-group').hide();
    $('#favoriteModal .form-group:last-child').show();
    $('.button-delete').show();
    $('.resume-text').hide();
    $('.button-add').hide();
});
$('body').on('dblclick', '#favoriteList .resume-item', function() {
    var musicValue = $(this).find('.music').text();
    var quoteValue = $(this).find('.quote').text();
    var tvValue = $(this).find('.tv').text();
    var bookValue = $(this).find('.book').text();
    var movieValue = $(this).find('.movie').text();
    var webValue = $(this).find('.web').text();

    currentSubElement = $(this);
    $('#favoriteModal .music-text').val(musicValue);
    $('#favoriteModal .quotes-text').val(quoteValue);
    $('#favoriteModal .tv-text').val(tvValue);
    $('#favoriteModal .book-text').val(bookValue);
    $('#favoriteModal .movie-text').val(movieValue);
    $('#favoriteModal .web-text').val(webValue);

    $('#favoriteModal').modal('show');
    $('#favoriteModal .form-group').show();
    $('.button-add').hide();
    $('.button-delete').hide();
    $('.resume-text').show();
    $('.button-update').show();
});
$('#favoriteModal').on('hidden.bs.modal', function(e) {
    // do something...
    $('#favoriteModal .music-text').val("");
    $('#favoriteModal .quotes-text').val("");
    $('#favoriteModal .tv-text').val("");
    $('#favoriteModal .book-text').val("");
    $('#favoriteModal .movie-text').val("");
    $('#favoriteModal .web-text').val("");
});

///// Interest Modal
///Call To Server
function addNewResumeIntToServer(value, callBack) {
    ///Create New Element
    var liTag = $('<li data-id="' + value + '" class="resume-item">');
    var container = $('<div class="curricular">');

    var n = $("#interestModal input:checked").length;

    if (n == 0) {
        $('#interestList .glyphicon-plus').show();
    }
    else {
        $('#interestList .glyphicon-plus').hide();
        $("input:checked").each(function(index, ele) {
            var pTag = $('<p>');
            pTag.text($(ele).attr('data-text'));
            container.append(pTag);
        });

        liTag.append(container);

        currentElement.find('ul').append(liTag);
    }


    $('#interestModal').modal('hide');
}
function updateResumeIntToServer(id, callBack) {
    callBack();
}
function deleteResumeIntToServer(id, callBack) {
    callBack();
}

$('body').on('click', '#interestList .glyphicon-plus', function() {
    currentElement = $(this).closest('li');
    $('.button-update').hide();
    $('.button-delete').hide();
    $('#interestModal .form-group').show();
    $('.resume-text').show();
    $('.button-add').show();
});
$('body').on('click', '#interestModal .button-add', function() {
    ///Get Name Text
    var reid = $('#interestList').data('id');
    var data = [];
    $("input:checked").each(function(index, ele) {
        data.push($(ele).data('text'));
    });
    //
    $.ajax({
        dataType: 'json',
        type: 'POST',
        url: 'resume/add_interest',
        data: {data: data, reid: reid},
        beforeSend: function() {
            loader.start();
        },
        success: function(res) {
            loader.stop();
            addNewResumeIntToServer(res);
        },
        error: function() {
            loader.stop();
            bootbox.alert('Error', function() {
            }).find("div.modal-dialog").addClass("largeWidth");
        }
    });
});
$('body').on('click', '#interestModal .button-update', function() {
    ///Get Name Text
    var id = currentSubElement.attr('data-id');
    currentElement.find('ul').empty();

    updateResumeIntToServer(id, function() {
        ///Create New Element
        var liTag = $('<li data-id="" class="resume-item">');
        var container = $('<div class="curricular">');

        var n = $("#interestModal input:checked").length;

        if (n == 0) {
            $('#interestList .glyphicon-plus').show();
            //
            var data = [];
            var reid = $('#interestList').data('id');
            var id = currentSubElement.attr('data-id');
            $("input:checked").each(function(index, ele) {
                data.push($(ele).data('text'));
            });
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'resume/delete_interest',
                data: {data: data, reid: reid, id: id},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    //
                },
                error: function() {
                    loader.stop();
                    bootbox.alert('Error', function() {
                    }).find("div.modal-dialog").addClass("largeWidth");
                }
            });
        }
        else {
            var data = [];
            var reid = $('#interestList').data('id');
            var id = currentSubElement.attr('data-id');
            $("input:checked").each(function(index, ele) {
                data.push($(ele).data('text'));
            });
            //
            $.ajax({
                dataType: 'json',
                type: 'POST',
                url: 'resume/update_interest',
                data: {data: data, reid: reid, id: id},
                beforeSend: function() {
                    loader.start();
                },
                success: function(res) {
                    loader.stop();
                    //
                    $('#interestList .glyphicon-plus').hide();
                    $("input:checked").each(function(index, ele) {
                        var pTag = $('<p>');
                        pTag.text($(ele).attr('data-text'));
                        container.append(pTag);
                    });

                    liTag.append(container);

                    currentElement.find('ul').append(liTag);
                },
                error: function() {
                    loader.stop();
                    bootbox.alert('Error', function() {
                    }).find("div.modal-dialog").addClass("largeWidth");
                }
            });
        }
        $('#interestModal').modal('hide');
    });
});
$('body').on('click', '#interestModal .button-delete', function() {
    var id = currentSubElement.attr('data-id');
    deleteResumeIntToServer(id, function() {
        currentSubElement.remove();
        $('#interestModal').modal('hide');
    });
});
$('body').on('click', '#interestList .glyphicon-minus', function() {
    ///Get Name Text
    currentSubElement = $(this).closest('li');
    $('#interestModal').modal('show');
    $('.button-update').hide();
    $('#interestModal .form-group').hide();
    $('#interestModal .form-group:last-child').show();
    $('.button-delete').show();
    $('.resume-text').hide();
    $('.button-add').hide();
});
$('body').on('dblclick', '#interestList .resume-item', function() {
    var array = [];
    var i = 0;
    $(this).find('p').each(function(index, ele) {
        array[i] = $(ele).text();
        i++;
    });
    $("#interestModal input[type=checkbox]").each(function(index, ele) {

        $(ele).prop('checked', false);
        if ($.inArray($(this).attr('data-text'), array) > -1) {
            $(ele).prop('checked', true);
        }
    });
    currentElement = $('#interestList').closest('li');
    currentSubElement = $(this);

    $('#interestModal').modal('show');
    $('#interestModal .form-group').show();
    $('.button-add').hide();
    $('.button-delete').hide();
    $('.resume-text').show();
    $('.button-update').show();
});
$('#interestModal').on('hidden.bs.modal', function(e) {
    // do something...
    $('.resume-text').val("");
});

/*---------- begin datatime picker ------------*/
$(function() {
    $.fn.datepicker.defaults.format = "yyyy-mm-dd";

    var startDate = new Date(2014, 3, 16);
    var endDate = new Date(2014, 5, 30);
    $('#curricularModal #uptonow').on('click', function(e) {
        if ($(this).is(':checked')) {
            // true
            $('#end_extra_block').hide();
        } else {
            $('#end_extra_block').show();
        }
    });
    $('#curricularModal .start-text').datepicker('setValue', startDate)
            .on('changeDate', function(ev) {
                if (ev.date.valueOf() > endDate.valueOf()) {
                    alert('The start date can not be greater then the end date');
                } else {
                    startDate = new Date(ev.date);
                }
                $('#curricularModal .start-text').datepicker('hide');
            });
    $('#curricularModal .end-text').datepicker('setValue', endDate)
            .on('changeDate', function(ev) {
                var end_date = $('#curricularModal .start-text').val().split('-');
                startDate = new Date(end_date[0], end_date[1], end_date[2]);
                if (ev.date.valueOf() < startDate.valueOf()) {
                    alert('The end date can not be less then the start date');
                } else {
                    endDate = new Date(ev.date);
                }
                $('#curricularModal .end-text').datepicker('hide');
            });
//
    $('#experienceModal #uptonow').on('click', function(e) {
        if ($(this).is(':checked')) {
            // true
            $('#end_exper_block').hide();
        } else {
            $('#end_exper_block').show();
        }
    });
    $('#experienceModal .start-text').datepicker('setValue', startDate)
            .on('changeDate', function(ev) {
                if (ev.date.valueOf() > endDate.valueOf()) {
                    alert('The start date can not be greater then the end date');
                } else {
                    startDate = new Date(ev.date);
                }
                $('#experienceModal .start-text').datepicker('hide');
            });
    $('#experienceModal .end-text').datepicker('setValue', endDate)
            .on('changeDate', function(ev) {
                var end_date = $('#experienceModal .start-text').val().split('-');
                startDate = new Date(end_date[0], end_date[1], end_date[2]);
                if (ev.date.valueOf() < startDate.valueOf()) {
                    alert('The end date can not be less then the start date');
                } else {
                    endDate = new Date(ev.date);
                }
                $('#experienceModal .end-text').datepicker('hide');
            });

});
/*---------- end datatime picker ------------*/