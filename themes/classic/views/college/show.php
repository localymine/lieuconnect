<?php
$this->pageTitle = $post->post_title . ' - ' . Common::t('Lilely Connect');
//$avatar = ($post->post_by->image != '') ? $post->post_by->image : 'avatar.png';
$school_logo = ($post->school_logo != '') ? $post->school_logo : '0.jpg';
$image_entry = ($post->image != '') ? $post->image : 'no-image.png';

$share_url = Yii::app()->createUrl(Yii::app()->params['siteUrl'] . '/college/show', array('slug' => $post->slug));
$title = $post->post_title;
$image_url = Yii::app()->params['siteUrl'] . '/images/college/' . $image_entry;

Common::register_js(Yii::app()->theme->baseUrl . '/js/fix-scroll.js', CClientScript::POS_END);
?>

<div class="main-frame">
    <div class="feature-readlater">
        <a class="btn-readlater read" data-id="<?php echo $post->id ?>"><span></span><?php echo Common::t('Read Later', 'post') ?></a>
    </div>
    <div class="feature-social-bottom">
        <?php if (Yii::app()->user->isGuest): ?>
            <a href="<?php echo Yii::app()->createUrl('account/signup') ?>" class="btn-lilely"></a>
        <?php endif; ?>
        <?php
        $this->widget('SocialNetwork', array(
            'type' => 'fixed-share-bottom',
            'data_href' => $share_url,
            'title' => $title,
            'image_url' => $image_url,
        ));
        ?>
    </div>
    <?php
    $this->widget('SocialNetwork', array(
        'type' => 'fixed-share-left',
        'data_href' => $share_url,
        'title' => $title,
        'image_url' => $image_url,
    ));
    ?>
    <div class="main scholarship-result">
        
        <?php
        $rs_location = TermRelationships::model()->get_relate_terms($post->id, 'category', 'location')->findAll();
        $rs_programs = TermRelationships::model()->get_relate_terms($post->id, 'category', 'major')->findAll();
        $rs_school_type = TermRelationships::model()->get_relate_terms($post->id, 'category', 'school-type')->findAll();
        $rs_school_funding = TermRelationships::model()->get_relate_terms($post->id, 'category', 'school-funding')->findAll();
        $rs_admission_difficult = TermRelationships::model()->get_relate_terms($post->id, 'category', 'admission-difficult')->findAll();
//        Common::print_r($rs_programs);
        ?>
        
        <div class="col-md-8 col-sm-12 col-xs-12 no-padding ">
            <div class="col-md-3 col-sm-3 col-xs-12 no-padding ">
                <img width="125" src="<?php echo Yii::app()->baseUrl ?>/images/school-logo/<?php echo $school_logo ?>" class="img-responsive" />
            </div>
            <div class="col-md-9 col-sm-9 col-xs-12 no-padding result-detail">
                <h2 class="college_detail">
                    <?php echo $post->post_title ?>
                </h2>
                <div class="content">
                    <?php echo $post->post_content ?>
                </div>
                <div class="college-detail-row">
                    <div class="col-33">
                        <div class="content odd">
                            <h4>
                                <?php 
                                $school_type = array();
                                foreach ($rs_school_type as $rs){
                                    $school_type[] = $rs->termtaxonomy->terms->name;
                                }
                                echo implode('; ', $school_type);
                                ?>
                            </h4>
                            <p>School Type</p>
                        </div>
                    </div>
                    <div class="col-33">
                        <div class="content odd">
                            <h4>
                                <?php 
                                $school_funding = array();
                                foreach ($rs_school_funding as $rs){
                                    $school_funding[] = $rs->termtaxonomy->terms->name;
                                }
                                echo implode('; ', $school_funding);
                                ?>
                            </h4>
                            <p>School Funding</p>
                        </div>
                    </div>
                    <div class="col-33">
                        <div class="content odd">
                            <h4>
                                <?php 
                                $location = array();
                                foreach ($rs_location as $rs){
                                    $location[] = $rs->termtaxonomy->terms->name;
                                }
                                echo implode('; ', $location);
                                ?>
                                <!--<i class="city"></i>-->
                            </h4>
                            <p>Location</p>
                        </div>
                    </div>
                </div>
                <div class="college-detail-row">
                    <div class="col-50">
                        <div class="content even study-right">
                            <h4>
                                <?php echo number_format($post->total_student) ?>
                            </h4>
                            <p>Total Undergraduate Students</p>
                        </div>
                    </div>
                    <div class="col-25">
                        <div class="content even study-left">
                            <h4><?php echo $post->total_international ?>%</h4>
                            <p>International</p>
                        </div>
                    </div>
                    <div class="col-25">
                        <div class="content even">
                            <h4><?php echo $post->total_asia ?>%</h4>
                            <p>Asian</p>
                        </div>
                    </div>
                </div>
                <div class="college-detail-row">
                    <div class="col-100">
                        <div class="content odd">
                            <h4>
                                <?php 
                                $programs = array();
                                foreach ($rs_programs as $rs){
                                    $programs[] = $rs->termtaxonomy->terms->name;
                                }
                                echo implode('; ', $programs);
                                ?>
                            </h4>
                            <p>Pupolar Programs</p>
                        </div>
                    </div>
                </div>
                <div class="college-detail-row">
                    <div class="col-100">
                        <div class="content even">
                            <h4>
                                <?php 
                                $admission_difficult = array();
                                foreach ($rs_admission_difficult as $rs){
                                    $admission_difficult[] = $rs->termtaxonomy->terms->name;
                                }
                                echo implode('; ', $admission_difficult);
                                ?>
                            </h4>
                            <p>Admission Difficult</p>
                        </div>
                    </div>
                </div>
                <div class="college-detail-row">
                    <div class="col-50">
                        <div class="content odd toefl-right">
                            <h4><?php echo $post->toefl_min ?></h4>
                            <p>minimum score</p>
                        </div>
                    </div>
                    <div class="col-50">
                        <div class="content odd toefl-left">
                            <h4><?php echo $post->toefl_avg ?></h4>
                            <p>average score</p>
                        </div>
                    </div>
                </div>
                <div class="college-detail-row">
                    <div class="col-50">
                        <div class="content even calendar-right">
                            <h4>
                                <?php
                                $date = new DateTime($post->regular_admission);
                                echo $date->format('M d, Y');
                                ?>
                            </h4>
                            <p>Regular Admission</p>
                        </div>
                    </div>
                    <div class="col-50">
                        <div class="content even calendar-left">
                            <h4>
                                <?php
                                $date = new DateTime($post->deadline);
                                echo $date->format('M d, Y');
                                ?>
                            </h4>
                            <p>Notification Of Admission</p>
                        </div>
                    </div>
                </div>
                <div class="college-detail-row row-2">
                    <div class="col-25">
                        <div class="content odd">
                            <img src="<?php echo Yii::app()->theme->baseUrl ?>/img/icon-dollar.png">
                        </div>
                    </div>
                    <div class="col-25">
                        <div class="content odd">
                            <h4>
                                $<?php echo number_format($post->application_fee) ?>
                            </h4>
                            <p>Application Fee Of U.S</p>
                        </div>
                    </div>
                    <div class="col-25">
                        <div class="content odd">
                            <h4>
                                $<?php echo number_format($post->out_state_tutition) ?>
                            </h4>
                            <p>Out-of-State Tutition</p>
                        </div>
                    </div>
                    <div class="col-25">
                        <div class="content odd">
                            <h4>
                                $<?php echo number_format($post->give_out_tutition) ?>
                            </h4>
                            <p>
                                <?php echo Post::item_alias('contract-type', $post->contract_type) . ' ' . $post->contract_number ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <?php $this->widget('LoginForm', array('type' => 'apply-button', 'title' => Common::t('Contact School Now', 'account'), 'post_id' => $post->id)) ?>
                
            </div>
        </div>
        
        <div class="col-md-4 col-sm-12 col-xs-12 no-padding our-picks-frame">
            <div class="ourPicks">
                <!-- story -->
                <h2><?php echo Common::t('Our Picks') ?></h2>
                <?php foreach ($model_our_picks as $row): ?>
                    <div class="outpick-item">
                    <p>
                        <a href="<?php echo Yii::app()->createUrl('college/show', array('slug' => $row->post->slug)) ?>">
                        <img src="<?php echo Yii::app()->baseUrl ?>/images/college/<?php echo $row->post->image ?>" class="img-responsive">
                            <?php echo $row->post->post_title ?>
                        </a>
                    </p>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>
    </div>
</div>

<?php $this->widget('MoreStuff') ?>

<?php $this->widget('LoginForm', array('type' => 'modal-login')) ?>
<?php $this->widget('LoginForm', array('type' => 'modal-recruit', 'title' => Common::t('Apply', 'account'))) ?>

<?php
$url_read_later = Yii::app()->createUrl('read');
$mess_1 = Common::t('Added into Read Later list');
$mess_2 = Common::t('You have to Login In');
?>
<?php
$script = <<< EOD
$(function() {
    $('.read').each(function(event) {
        $(this).css('cursor', 'pointer');
        var postid = $(this).data('id');
       
        $(this).click(function() {
            $.ajax({
                url: '$url_read_later',
                type: 'POST',
                data: {id: postid},
                dataType: 'json',
                beforeSend: function() {
                    loader.start();
                },
                success: function(msg) {
                    if (msg == 1) {
                        alert('$mess_1');
                    } else if (msg == -1) {
                        $('input:checkbox').removeAttr('checked');
                        alert('$mess_2');
                    }
                    loader.stop();
                },
                fail: function() {
                    loader.stop();
                }
            });
        });
    });   
});
EOD;
?>

<?php
Yii::app()->clientScript->registerScript('read-later-' . rand(), $script, CClientScript::POS_END);
?>

<?php
$script = <<< EOD
$(function() {
    $('#apply').click(function(e) {
        e.preventDefault();
        $('.signup-popup').modal();
    });
});
EOD;
?>

<?php
Yii::app()->clientScript->registerScript('scroll-show-' . rand(), $script, CClientScript::POS_END);
?>