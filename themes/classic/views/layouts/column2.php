<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

<?php
$user = AUsers::model()->findByPk(Yii::app()->user->id);
$profile = $user->profile;
//
$action = Yii::app()->controller->action->id;
$controller = Yii::app()->controller->id;

// avatar
$avatar = ($profile->image != '') ? ($profile->image) : 'avatar.png';

// create link for upload
//$languages = Yii::app()->request->languages;
//$params = explode('/', Yii::app()->request->requestUri);
//if (array_key_exists($params[2], $languages)) {
//    $url_upload = Yii::app()->createUrl($params[2] . '/account/upload');
//} else {
//    $url_upload = Yii::app()->createUrl('/account/upload');
//}
?>

<div id="toTop"><i class="fa fa-chevron-up"></i></div>

<div class="main-frame">
    <div class="feature">
        <span class="lilely-btn no-clickable"><?php echo Common::t('My Student Center', 'center') ?></span>
    </div>
    <div class="main mystudent">
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-2 col-xs-2 no-padding">
                    <div class="avatar">
                        <img id="myavatar" src="<?php echo Yii::app()->baseUrl ?>/avatars/<?php echo 'thumb_' . $avatar ?>" class="img-responsive">
                        <div id="upload-avatar" class="upload-avatar">
                            <?php
                            $form = $this->beginWidget('CActiveForm', array(
                                'id' => 'upload-form',
                                'action' => Yii::app()->createUrl('account/upload'),
                                'htmlOptions' => array(
                                    'enctype' => 'multipart/form-data', 
                                    'method' => 'post', 
                                    'target' => 'i-holder'),
                                ));
                            ?>
                                <div class="fileUpload btn btn-alt pull-right">
                                    <span><i class="fa fa-upload"></i></span>
                                    <?php echo $form->fileField($profile, 'image', array('class' => 'upload')) ?>
                                </div>
                            <?php $this->endWidget(); ?>
                        </div>
                    </div>
                    <div class="dropdown">
                        <a class="mystudent-btn" href="javascript:void(0);"><?php echo $user->username ?><span class="collapse-expand"></span></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li>
                                <a  class="<?php echo ($controller == 'profile') ? 'active' : '' ?>" href="<?php echo Yii::app()->createUrl('profile') ?>"><?php echo Common::t('My Profile', 'home') ?></a>
                            </li>
                            <li>
                                <a class="<?php echo ($controller == 'resume') ? 'active' : '' ?>" href="<?php echo Yii::app()->createUrl('resume') ?>"><?php echo Common::t('Resume', 'home') ?></a>
                            </li>
                            <li>
                                <a class="<?php echo ($controller == 'practice') ? 'active' : '' ?>" href="<?php echo Yii::app()->createUrl('practice') ?>"><?php echo Common::t('Practice Test', 'home') ?></a>
                            </li>
                            <li>
                                <a class="<?php echo ($controller == 'box' && $action == 'scholarship') ? 'active' : '' ?>" href="<?php echo Yii::app()->createUrl('box/scholarship') ?>"><?php echo Common::t('Scholarship', 'home') ?></a>
                            </li>
                            <li>
                                <a class="<?php echo ($controller == 'box' && $action == 'internship') ? 'active' : '' ?>" href="<?php echo Yii::app()->createUrl('box/internship') ?>"><?php echo Common::t('Internship', 'home') ?></a>
                            </li>
                            <li>
                                <a class="<?php echo ($controller == 'box' && $action == 'college') ? 'active' : '' ?>" href="<?php echo Yii::app()->createUrl('box/college') ?>"><?php echo Common::t('College', 'home') ?></a>
                            </li>
                            <li>
                                <a class="<?php echo ($controller == 'box' && $action == 'index') ? 'active' : '' ?>" href="<?php echo Yii::app()->createUrl('box') ?>"><?php echo Common::t('Read Later', 'home') ?></a>
                            </li>
                        </ul>
                    </div>
                </div>
                <hr>
                <div class="col-md-10 col-xs-10 ">
                    <?php echo $content; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- content -->
<?php $this->endContent(); ?>

<!-- Modal upload -->
<iframe id="i-holder" name="i-holder" width="0" height="0" border="0" style="display: none;"></iframe>

<?php
$script = <<< EOD
$(function() { 
    $('#AProfiles_image').on('change', function(){
        loader.start();
//        var iframe = $('<iframe />', {
//            name: 'i-holder',
//            id: 'i-holder',
//            src: 'about:holder?nocache=' + Math.random()
//        }).css('display', 'none !important').appendTo('body');
        //
        $('#upload-form').submit();
        //
//        $('#i-holder').remove();
    });

    // 
    $('.mystudent .dropdown').click(function() {
        $(this).toggleClass('open');
    });
});
EOD;
?>
<?php
Yii::app()->clientScript->registerScript('my-student-center-' . rand(), $script, CClientScript::POS_END);
?>