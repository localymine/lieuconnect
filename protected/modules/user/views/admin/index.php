<?php
$this->pageTitle = UserModule::t("Manage Users") . ' | ' . Yii::app()->name;
?>

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-brush"></i><?php echo UserModule::t("Manage Users"); ?><br/>
            <small>
                <a class="btn btn-sm btn-primary" href="<?php echo Yii::app()->createUrl('user/admin/create') ?>"><?php echo UserModule::t('Create User') ?></a>
                <a class="btn btn-sm btn-primary" href="<?php echo Yii::app()->createUrl('user/admin') ?>"><?php echo UserModule::t('Manage Users') ?></a>
                <a class="btn btn-sm btn-primary" href="<?php echo Yii::app()->createUrl('user/profileField/admin') ?>"><?php echo UserModule::t('Manage Profile Field') ?></a>
                <a class="btn btn-sm btn-primary" href="<?php echo Yii::app()->createUrl('user') ?>"><?php echo UserModule::t('List User') ?></a>
            </small>
        </h1>
    </div>
</div>

<div class="block full">
    <?php
    Yii::app()->clientScript->registerScript('search', "
        $('.search-button').click(function(){
            $('.search-form').toggle();
            return false;
        });	
        $('.search-form form').submit(function(){
            $.fn.yiiGridView.update('user-grid', {
                data: $(this).serialize()
            });
            return false;
        });
        ");
    ?>

    <?php echo CHtml::link(UserModule::t('Advanced Search'), '#', array('class' => 'search-button')); ?>

    <div class="search-form" style="display:none">
        <?php
        $this->renderPartial('_search', array(
            'model' => $model,
        ));
        ?>
    </div><!-- search-form -->

    <p><?php echo UserModule::t("You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b> or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done."); ?></p>
</div>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'user-grid',
    'dataProvider' => $model->search(),
//                    'filter' => $model,
    'columns' => array(
        array(
            'name' => 'id',
            'type' => 'raw',
            'value' => 'CHtml::link(CHtml::encode($data->id),array("admin/update","id"=>$data->id))',
        ),
        array(
            'name' => 'username',
            'type' => 'raw',
            'value' => 'CHtml::link(UHtml::markSearch($data,"username"),array("admin/view","id"=>$data->id))',
        ),
        array(
            'name' => 'email',
            'type' => 'raw',
            'value' => 'CHtml::link(UHtml::markSearch($data,"email"), "mailto:".$data->email)',
        ),
        'create_at',
        'lastvisit_at',
        array(
            'name' => 'superuser',
            'value' => 'User::itemAlias("AdminStatus",$data->superuser)',
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center'),
            'filter' => User::itemAlias("AdminStatus"),
        ),
        array(
            'name' => 'status',
            'value' => 'User::itemAlias("UserStatus",$data->status)',
            'type' => 'raw',
            'htmlOptions' => array('style' => 'text-align: center'),
            'filter' => User::itemAlias("UserStatus"),
        ),
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>