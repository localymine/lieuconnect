<?php
$this->pageTitle = UserModule::t("Profile") . ' | ' . Yii::app()->name;
$this->breadcrumbs = array(
    UserModule::t("Profile"),
);

//$this->menu = array(
//    ((UserModule::isAdmin()) ? array('label' => UserModule::t('Manage Users'), 'url' => array('/user/admin')) : array()),
//    array('label' => UserModule::t('List User'), 'url' => array('/user')),
//    array('label' => UserModule::t('Edit'), 'url' => array('edit')),
//    array('label' => UserModule::t('Change password'), 'url' => array('changepassword')),
//    array('label' => UserModule::t('Logout'), 'url' => array('/user/logout')),
//);

Common::register('jquery.min.js', 'pro', CClientScript::POS_HEAD);

$avatar = (isset(Yii::app()->user->image)) ? (Yii::app()->user->image) : 'avatar.png';
$page = 1;
?>


<div class="content-header content-header-media">
    <div class="header-section">
        <img style="border-radius: 128px;height: 128px; width: 128px;" src="<?php echo Yii::app()->request->baseUrl; ?>/avatars/<?php echo $avatar ?>" alt="Avatar" class="pull-right img-circle" />
        <h1>
            <i class="gi gi-brush"></i><?php echo Yii::app()->user->username; ?><br /><small>A visual display of personal data</small>
        </h1>
    </div>
    <img src="<?php echo Yii::app()->theme->baseUrl ?>/img/placeholders/headers/profile_header.jpg" alt="header image" class="animation-pulseSlow" />
</div>

<div class="row">
    <div class="col-md-7">
        <div class="block">
            <div class="block-title">
                <div class="block-options pull-right">
                    <a href="javascript:void(0)" class="label label-danger animation-pulse">Live Feed</a>
                </div>
                <h2>News Feed</h2>
            </div>
            <div class="block-content-full">
                <ul class="media-list media-feed media-feed-hover">
                    <?php if ($posts != '') $this->renderPartial('_new_feed', array('posts' => $posts), false); ?>
                    <li id="holder-view-more" class="media text-center">
                        <a id="view-more" class="btn btn-xs btn-success push">View more..</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="block">
            <div class="block-title">
                <div class="block-options pull-right">
                    <a href="#modal-user-settings" class="btn btn-alt btn-sm btn-default" data-placement="bottom" data-toggle="modal" data-original-title="Settings" title="Settings"><i class="fa fa-cog"></i></a>
                </div>
                <h2>About <?php echo Yii::app()->user->username; ?></h2>
            </div>
            <?php if (Yii::app()->user->hasFlash('profileMessage')): ?>
                <div class="success">
                    <?php echo Yii::app()->user->getFlash('profileMessage'); ?>
                </div>
            <?php endif; ?>

            <table class="table table-borderless table-striped">
                <tr>
                    <td style="width: 20%;"><strong><?php echo CHtml::encode($model->getAttributeLabel('username')); ?></strong></td>
                    <td><?php echo CHtml::encode($model->username); ?></td>
                </tr>
                <?php
                $profileFields = ProfileField::model()->forOwner()->sort()->findAll();
                if ($profileFields) {
                    foreach ($profileFields as $field) {
                        //echo "<pre>"; print_r($profile); die();
                        ?>

                        <?php if ($field->title == 'Image'): ?>

                            <tr>
                                <td colspan="2">
                                    <div class="user-avatar center-block" style="border: 1px solid #ccc; border-radius: 128px;width: 128px;">
                                        <img class="img-circle" style="border-radius: 128px;height: 128px; width: 128px;" src="<?php echo Yii::app()->request->baseUrl; ?>/avatars/<?php echo $avatar ?>" />
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <tr>
                                <td><?php echo CHtml::encode(UserModule::t($field->title)); ?></td>
                                <td><?php echo (($field->widgetView($profile)) ? $field->widgetView($profile) : CHtml::encode((($field->range) ? Profile::range($field->range, $profile->getAttribute($field->varname)) : $profile->getAttribute($field->varname)))); ?></td>
                            </tr>
                        <?php endif; ?>
                        <?php
                    }//$profile->getAttribute($field->varname)
                }
                ?>
                <tr>
                    <td><?php echo CHtml::encode($model->getAttributeLabel('email')); ?></td>
                    <td><?php echo CHtml::encode($model->email); ?></td>
                </tr>
                <tr>
                    <td><?php echo CHtml::encode($model->getAttributeLabel('create_at')); ?></td>
                    <td><?php echo $model->create_at; ?></td>
                </tr>
                <tr>
                    <td><?php echo CHtml::encode($model->getAttributeLabel('lastvisit_at')); ?></td>
                    <td><?php echo $model->lastvisit_at; ?></td>
                </tr>
                <tr>
                    <td><?php echo CHtml::encode($model->getAttributeLabel('status')); ?></td>
                    <td><div class="label <?php echo ($model->status == 1) ? 'label-success' : 'label-danger'; ?> animation-pulse"><?php echo CHtml::encode(User::itemAlias("UserStatus", $model->status)); ?></div></td>
                </tr>
            </table>

        </div>
    </div>

</div>

<script type="text/javascript">
    $(function() {
        var ajaxloader = '<div id="ajaxloader"><i class="ajaxloader fa fa-spinner fa-4x fa-spin text-info"></i></div>';
        $('#view-more').click(function(e) {
            $('body').append(ajaxloader);
            e.preventDefault();
            $.get('<?php echo Yii::app()->createUrl('user/profile') ?>', {ajax: true},
            function(html) {
                $(html).insertBefore('#holder-view-more');
                $('#ajaxloader').remove();
            });
        });
    });
</script>