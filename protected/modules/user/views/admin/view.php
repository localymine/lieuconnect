<?php
$this->pageTitle = UserModule::t('View User') . ' | ' . Yii::app()->name;
?>

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-brush"></i><?php echo UserModule::t('View User') . ' "' . $model->username . '"'; ?><br/>
            <small>
                <a class="btn btn-sm btn-primary" href="<?php echo Yii::app()->createUrl('user/create') ?>"><?php echo UserModule::t('Create User') ?></a>
                <a class="btn btn-sm btn-primary" href="<?php echo Yii::app()->createUrl('user/update', array('id' => $model->id)) ?>"><?php echo UserModule::t('Update User') ?></a>
                <a class="btn btn-sm btn-primary" href="<?php echo Yii::app()->createUrl('user/admin') ?>"><?php echo UserModule::t('Manage Users') ?></a>
                <a class="btn btn-sm btn-primary" href="<?php echo Yii::app()->createUrl('user/profileField/admin') ?>"><?php echo UserModule::t('Manage Profile Field') ?></a>
                <a class="btn btn-sm btn-primary" href="<?php echo Yii::app()->createUrl('user') ?>"><?php echo UserModule::t('List User') ?></a>
            </small>
        </h1>
    </div>
</div>

<?php
$attributes = array(
    'id',
    'username',
);

$profileFields = ProfileField::model()->forOwner()->sort()->findAll();
if ($profileFields) {
    foreach ($profileFields as $field) {
        array_push($attributes, array(
            'label' => UserModule::t($field->title),
            'name' => $field->varname,
            'type' => 'raw',
            'value' => (($field->widgetView($model->profile)) ? $field->widgetView($model->profile) : (($field->range) ? Profile::range($field->range, $model->profile->getAttribute($field->varname)) : $model->profile->getAttribute($field->varname))),
        ));
    }
}

array_push($attributes, 'password', 'email', 'activkey', 'create_at', 'lastvisit_at', array(
    'name' => 'superuser',
    'value' => User::itemAlias("AdminStatus", $model->superuser),
        ), array(
    'name' => 'status',
    'value' => User::itemAlias("UserStatus", $model->status),
        )
);

$this->widget('zii.widgets.CDetailView', array(
    'data' => $model,
    'attributes' => $attributes,
));
?>
