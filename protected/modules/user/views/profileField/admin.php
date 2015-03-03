<?php
$this->pageTitle = UserModule::t('Manage Profile Fields') . ' | ' . Yii::app()->name;
?>

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-brush"></i><?php echo UserModule::t('Manage Profile Fields') ?><br/>
            <small>
                <a class="btn btn-sm btn-primary" href="<?php echo Yii::app()->createUrl('user/profileField/create') ?>"><?php echo UserModule::t('Create Profile Field') ?></a>
                <a class="btn btn-sm btn-primary" href="<?php echo Yii::app()->createUrl('user/profileField/admin') ?>"><?php echo UserModule::t('Manage Profile Field') ?></a>
                <a class="btn btn-sm btn-primary" href="<?php echo Yii::app()->createUrl('user/admin') ?>"><?php echo UserModule::t('Manage Users') ?></a>
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
    $.fn.yiiGridView.update('profile-field-grid', {
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
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => array(
        'id',
        array(
            'name' => 'varname',
            'type' => 'raw',
            'value' => 'UHtml::markSearch($data,"varname")',
        ),
        array(
            'name' => 'title',
            'value' => 'UserModule::t($data->title)',
        ),
        array(
            'name' => 'field_type',
            'value' => '$data->field_type',
            'filter' => ProfileField::itemAlias("field_type"),
        ),
        'field_size',
        //'field_size_min',
        array(
            'name' => 'required',
            'value' => 'ProfileField::itemAlias("required",$data->required)',
            'filter' => ProfileField::itemAlias("required"),
        ),
        //'match',
        //'range',
        //'error_message',
        //'other_validator',
        //'default',
        'position',
        array(
            'name' => 'visible',
            'value' => 'ProfileField::itemAlias("visible",$data->visible)',
            'filter' => ProfileField::itemAlias("visible"),
        ),
        //*/
        array(
            'class' => 'CButtonColumn',
            'htmlOptions' => array('style' => 'white-space: nowrap', 'align' => 'center'),
            'template' => '{update}{delete}',
            'buttons' => array(
                'update' => array(
                    'options' => array('rel' => 'tooltip', 'data-toggle' => 'tooltip', 'title' => 'Update', 'style' => 'padding: 0 5px;'),
                    'label' => '<i class="fa fa-pencil"></i>',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("user/profileField/update", array("id" => $data->id))'
                ),
                'delete' => array(
                    'options' => array('rel' => 'tooltip', 'data-toggle' => 'tooltip', 'title' => 'Delete', 'style' => 'padding: 0 5px;color: red;'),
                    'label' => '<i class="fa fa-times"></i>',
                    'imageUrl' => false,
                    'url' => 'Yii::app()->createUrl("user/profileField/delete", array("id" => $data->id))',
                    'click' => 'function(e) {
                        e.preventDefault();
                        if (confirm("Are you sure Delete?")) {
                            var postid = true;
                            var request = $.ajax({
                                url: $(this).attr("href"),
                                type: "POST",
                                data: {id: postid},
                                dataType: "json",
                                beforeSend: function() {
                                    $("body").append(ajaxloader);
                                },
                                success: function(msg) {
                                    if (msg == 0) {
                                        alert("Can not delete.");
                                    } else if (msg == 1) {
                                        location.href = location.href;
                                    }
                                    $("#ajaxloader").remove();
                                },
                                fail: function() {
                                    $("#ajaxloader").remove();
                                }
                            });
                        }
                    }'
                ),
            ),
        ),
    ),
));
?>

<<script type="text/javascript">
var ajaxloader = '<div id="ajaxloader"><i class="ajaxloader fa fa-spinner fa-4x fa-spin text-info"></i></div>';
</script>