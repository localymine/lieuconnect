<?php
Common::register_js(Yii::app()->theme->baseUrl . '/js/plugins.js', CClientScript::POS_END);
Common::register_js(Yii::app()->theme->baseUrl . '/js/app.js', CClientScript::POS_END);
?>

<div class="filter-bar">
    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="high">
            <?php
            $form_high_school = $this->beginWidget('CActiveForm', array(
                'id' => 'search-form',
                'method' => 'get',
                'action' => Yii::app()->createUrl('search/internship'),
                'htmlOptions' => array(
                )
            ));
            ?>
            <div class="row">
                <div class="col-md-10 no-padding">
                    <?php echo CHtml::textField('k', (isset($keyword) ? $keyword : ''), array('placeholder' => Common::t('Enter job title, position...', 'post'), 'class' => 'form-control')) ?>
                </div>
                <div class="col-md-2 no-padding popup-go">
                    <div class="popover-markup">
                        <input type="button" class="btn btn-default trigger btn-go hidden-sm hidden-xs" value="Go">
                        <div class="content hide">
                            <?php echo CHtml::submitButton(Common::t("I'm A High School Student"), array('name' => 'high-school', 'class' => 'btn btn-default btn-go')) ?>
                            <?php echo CHtml::submitButton(Common::t("I'm A College Student"), array('name' => 'college', 'class' => 'btn btn-default btn-go')) ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row option-choice">
                <div class="col-md-5 no-padding">
                    <?php
                    $this->widget('SelectionSearchCategory', array(
                        'empty' => Common::t('All categories'),
                        'id' => 'cat_major',
                        'submit_name' => 'm',
                        'select' => $select_major,
                        'taxonomy' => 'category',
                        'show_in' => $this->cate_major,
                        'class' => 'select-chosen',
                    ))
                    ?>
                </div>
                <div class="col-md-5 no-padding">
                    <?php
                    $this->widget('SelectionSearchCategory', array(
                        'empty' => Common::t('All categories'),
                        'id' => 'cat_location',
                        'submit_name' => 'l',
                        'select' => $select_location,
                        'taxonomy' => 'category',
                        'show_in' => $this->cate_location,
                        'class' => 'select-chosen',
                    ))
                    ?>
                </div>
            </div>
            <div class="row visible-sm visible-xs popup-go">
                <input type="submit" class="btn btn-default btn-go" value="Go" />
            </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>

<?php
$script = <<< EOD
$(function() {
    $('.popover-markup>.trigger').popover({
        html: true,
        placement: 'top',
        title: function() {
            return $(this).parent().find('.head').html();
        },
        content: function() {
            return $(this).parent().find('.content').html();
        }
    });
        
    $('#k').bind('keypress keydown keyup', function(e){
        var keycode = (e.keyCode ? e.keyCode : e.which);
        if(e.keyCode == 13) { 
            $('.popover-markup>.trigger').popover('show');
            e.preventDefault(); 
            return false; 
        }
    });
});
EOD;
?>

<?php
Yii::app()->clientScript->registerScript('go-submit-form-' . rand(), $script, CClientScript::POS_END);
?>