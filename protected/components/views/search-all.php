<?php
$form_high_school = $this->beginWidget('CActiveForm', array(
    'id' => 'search-all-form',
    'method' => 'get',
    'action' => Yii::app()->createUrl('search'),
    'htmlOptions' => array(
        'class' => 'navbar-form navbar-left nav-search',
        'role' => 'search',
    )
));
?>
<div class="form-group">
    <?php echo CHtml::textField('kw', (isset($_GET['kw']) ? $_GET['kw'] : ''), array('placeholder' => Common::t('', 'post'), 'class' => 'form-control search-width search')) ?>
</div>
<!--<div>-->
    <?php // echo CHtml::submitButton(Common::t('Search'), array('name' => 'high-school', 'class' => '')) ?>
<!--</div>-->
<?php $this->endWidget(); ?>