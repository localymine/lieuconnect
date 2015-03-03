<?php
/* @var $this TermsController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Resume" . ' | ' . Yii::app()->name;

Common::register('jquery.min.js', 'pro', CClientScript::POS_HEAD);
Common::register_js(Yii::app()->theme->baseUrl . '/js/pages/readyInbox.js', CClientScript::POS_HEAD);

?>

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-brush"></i>Resume
        </h1>
    </div>
</div>

<div class="block">
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="block-title">
                    <h2><strong>List Resume</strong></h2>
                </div>
                <div class="gallary" data-toggle="lightbox-gallary">
                    <div class="row">
                        <?php foreach($posts as $row): ?>
                            <div class="col-sm-2 gallery-image">
                                <?php 
                                $profile = $row->profile;
                                $avatar = isset($profile->image) ? ('thumb_' . $profile->image) : 'avatar.png'; 
                                ?>
                                <img alt="image" class="img-circle" src="<?php echo Yii::app()->baseUrl . '/avatars/' . $avatar ?>" alt="<?php echo $row->username ?>">
                                <h4 class="text-center"><?php echo $row->username ?></h4>
                                <div class="gallery-image-options text-center">
                                    <div class="btn-group btn-group-sm">
                                        <?php if($row->profile->public_profile == 1): ?>
                                        <a data-toggle="tooltip" class="about btn btn-sm btn-alt btn-primary" data-original-title="About" data-id="<?php echo $row->id ?>"><i class="fa fa-user"></i></a>
                                        <?php endif; ?>
                                        <?php if($row->profile->public_resume == 1): ?>
                                        <a data-toggle="tooltip" class="info btn btn-sm btn-alt btn-success" data-original-title="Resume" data-id="<?php echo $row->id ?>">&nbsp;<i class="fa fa-info"></i>&nbsp;</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 text-center">
    <?php
    // the pagination widget with some options to mess
    $this->widget('CLinkPager', array(
        'pages' => $pages,
        'currentPage' => $pages->getCurrentPage(),
        'itemCount' => $item_count,
        'pageSize' => $page_size,
        'maxButtonCount' => 5,
        'firstPageCssClass' => '',
        'firstPageLabel' => '<i class="fa fa-angle-double-left"></i>',
        'prevPageLabel' => '<i class="fa fa-angle-left"></i>',
        'nextPageLabel' => '<i class="fa fa-angle-right"></i>',
        'lastPageCssClass' => '',
        'lastPageLabel' => '<i class="fa fa-angle-double-right"></i>',
        'selectedPageCssClass' => 'active',
        'header' => '',
        'htmlOptions' => array('class' => 'pagination pagination-sm'),
    ));
    ?>
</div>

<div class="modal fade" tabindex="-1" id="aboutModal" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 id="ads_id" class="modal-title">About: <span id="username"></span></h4>
            </div>
            <div class="modal-body">
                <p>
                    <label class="form-label">Full name</label>
                    <span id="firstname"></span> <span id="lastname"></span>
                </p>
                <p>
                    <label class="form-label">Gender</label>
                    <span id="gender"></span>
                </p>
                <p>
                    <label class="form-label">Birth-date</label>
                    <span id="birth_date"></span>
                </p>
                <p>
                    <label class="form-label">Email</label>
                    <span id="email"></span>
                </p>
                    <label class="form-label">Phone</label>
                    <span id="phone"></span>
                </p>
                <p>
                    <label class="form-label">Address</label>
                    <span id="address"></span><br/>
                    <span id="state"></span>, 
                    <span id="city"></span>, 
                    <span id="country"></span>
                </p>
                <p>
                    <label class="form-label">Expectation</label>
                    <span id="expectation"></span>
                </p>
                <p>
                    <label class="form-label">Grade Year</label>
                    <span id="grade_year"></span>
                </p>
                <p>
                    <label class="form-label">GPA</label>
                    <span id="gpa"></span>
                </p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="resumeModal" role="dialog" aria-hidden="true">
    <div class="modal-scrollbar-measure">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 id="ads_id" class="modal-title">Resume</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="block full">
                            <div class="block-title">
                                <ul data-toggle="tabs" class="nav nav-tabs" style="margin-bottom: -1px; margin-left: 0px;">
                                    <li class="active"><a href="#lst-extra" class="text-success">Extra-Curricular & Activity</a></li>
                                    <li class=""><a href="#lst-exper" class="text-success">Experience</a></li>
                                    <li class=""><a href="#lst-educa" class="text-success">Education</a></li>
                                    <li class=""><a href="#lst-honor" class="text-success">Honors & Awards</a></li>
                                    <li class=""><a href="#lst-skill" class="text-success">Specialities & Skills</a></li>
                                    <li class=""><a href="#lst-interest" class="text-success">Interests</a></li>
                                    <li class=""><a href="#lst-favorite" class="text-success">Favorites</a></li>
                                </ul>
                            </div>
                            <div class="tab-content">
                                <div id="lst-extra" class="tab-pane active"></div>
                                <div id="lst-exper" class="tab-pane"></div>
                                <div id="lst-educa" class="tab-pane"></div>
                                <div id="lst-honor" class="tab-pane"></div>
                                <div id="lst-skill" class="tab-pane"></div>
                                <div id="lst-interest" class="tab-pane"></div>
                                <div id="lst-favorite" class="tab-pane"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>$(function(){ ReadyInbox.init(); });</script>
<script>
    var ajaxloader = '<div id="ajaxloader"><i class="ajaxloader fa fa-spinner fa-4x fa-spin text-info"></i></div>';
    
    $('.mark-all-read').click(function(){
        var count = 0;
        var postid = [];
        $('.recruit-check').each(function(){
            count = $('.recruit-check:checked').length;
            if ($(this).is(':checked')){
                postid.push($(this).data('postid'));
            }
        });
        //
        if (count > 0) {
            var request = $.ajax({
                url: '<?php echo Yii::app()->createUrl('backend/recruit/mark_read') ?>',
                type: 'POST',
                data: {id: postid},
                dataType: 'json',
                beforeSend: function() {
                    $('body').append(ajaxloader);
                },
                success: function(msg) {
                    //
                    $.each(postid, function(index, value){
                        var status_holder = '#recruit-status-' + value + ' span';
                        $(status_holder).removeClass('label-warning animation-pulse').addClass('label-default').html('Read');
                    });
                    $('.recruit-check').prop('checked', false);
                    //
                    $('#ajaxloader').remove();
                },
                error: function() {
                    $('#ajaxloader').remove();
                }
            });
        }
    });

    $("a.about").click(function(e) {
        var postid = $(this).data('id');
        var request = $.ajax({
            url: '<?php echo Yii::app()->createUrl('backend/resume/about') ?>',
            type: 'POST',
            data: {id: postid},
            dataType: 'json',
            beforeSend: function() {
                $('body').append(ajaxloader);
            },
            success: function(msg) {
                $('#username').html(msg.username);
                $('#firstname').html(msg.firstname);
                $('#lastname').html(msg.lastname);
                $('#gender').html(msg.gender);
                $('#birth_date').html(msg.birth_date);
                $('#email').html(msg.email);
                $('#phone').html(msg.phone);
                $('#address').html(msg.address);
                $('#state').html(msg.state);
                $('#city').html(msg.city);
                $('#country').html(msg.country);
                $('#expectation').html(msg.expectation);
                $('#grade_year').html(msg.grade_year);
                $('#gpa').html(msg.gpa);
                //
                $('#aboutModal').modal('show');
                $('#ajaxloader').remove();
            },
            error: function() {
                $('#ajaxloader').remove();
            }
        });
    });

    
    $("a.info").click(function(e) {
        var postid = $(this).data('id');
        var request = $.ajax({
            url: '<?php echo Yii::app()->createUrl('backend/resume/info') ?>',
            type: 'POST',
            data: {id: postid},
            dataType: 'json',
            beforeSend: function() {
                $('body').append(ajaxloader);
            },
            success: function(msg) {
                $('#lst-extra').html(msg.tm_extra);
                $('#lst-exper').html(msg.tm_exper);
                $('#lst-educa').html(msg.tm_educa);
                $('#lst-honor').html(msg.tm_honor);
                $('#lst-skill').html(msg.tm_skill);
                $('#lst-interest').html(msg.tm_interest);
                $('#lst-favorite').html(msg.tm_favorite);
                //
                $('#resumeModal').modal('show');
                $('#ajaxloader').remove();
            },
            error: function() {
                $('#infoloader').remove();
            }
        });
    });
</script>