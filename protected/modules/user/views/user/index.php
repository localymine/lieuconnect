<?php
$this->pageTitle = UserModule::t("List Users") . ' | ' . Yii::app()->name;
?>

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-brush"></i><?php echo UserModule::t("List User"); ?><br/>
            <?php if (UserModule::isAdmin()): ?>
                <?php $this->layout = '//layouts/column2'; ?>
                <small>
                    <a class="btn btn-sm btn-primary" href="<?php echo Yii::app()->createUrl('user/admin') ?>"><?php echo UserModule::t('Manage Users') ?></a>
                    <a class="btn btn-sm btn-primary" href="<?php echo Yii::app()->createUrl('user/profileField/admin') ?>"><?php echo UserModule::t('Manage Profile Field') ?></a>
                </small>
            <?php endif; ?>
        </h1>
    </div>
</div>


<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $dataProvider,
    'columns' => array(
        array(
            'name' => 'username',
            'type' => 'raw',
            'value' => 'CHtml::link(CHtml::encode($data->username),array("user/view","id"=>$data->id))',
        ),
        'create_at',
        'lastvisit_at',
    ),
));
?>
