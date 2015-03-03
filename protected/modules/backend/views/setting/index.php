<?php
/* @var $this TermsController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Settings" . ' | ' . Yii::app()->name;

Common::register('jquery.min.js', 'pro', CClientScript::POS_HEAD);
Common::register_js(Yii::app()->theme->baseUrl . '/js/pages/readyInbox.js', CClientScript::POS_HEAD);

?>

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-brush"></i>Settings
        </h1>
    </div>
</div>

<div class="block">
    <div class="row">
        <div class="col-md-12">
            <div class="block">
                <div class="table-responsive">
                    <?php
                    $form = $this->beginWidget('CActiveForm', array(
                        'id' => 'setting-form',
                        'method' => 'post',
                        'action' => Yii::app()->createUrl('backend/setting'),
                        'htmlOptions' => array(
//                            'enctype' => 'multipart/form-data',
//                            'class' => 'form-horizontal'
                        ),
                    ));
                    ?>
                    <table class="table table-vcenter table-striped">
                        <?php foreach($entry as $row): ?>
                        <tr>
                            <td width="20%"><strong><?php echo $row->name ?></strong><div class="hint text-info"><?php echo $row->description ?></div></td>
                            <td>
                                <?php if($row->disp_type == 'textarea'): ?>
                                <?php echo CHtml::textArea('Setting[' . $row->name . ']', $row->value, array('class' => 'form-control', 'style' => 'resize:vertical;')) ?>
                                <?php else: ?>
                                <?php echo CHtml::textField('Setting[' . $row->name . ']', $row->value, array('class' => 'form-control')) ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                    <div class="form-group">
                        <button class="search btn btn-danger" type="submit">Submit <span class="fa fa-arrow-circle-right"></span></button>
                    </div>
                    <?php $this->endWidget(); ?>
                </div>
            </div>
        </div>
    </div>
</div>