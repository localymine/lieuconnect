<?php
$this->pageTitle = Common::t('Read Later', 'post') . ' | ' . 'Lilely Connect';

Common::register_js(Yii::app()->theme->baseUrl . '/js/scroll.jquery.js', CClientScript::POS_END);
        
?>

<div class="table-main">
    <ul class="pratice-list">
        <?php if ($result != NULL) $this->renderPartial('_entry', array('result' => $result), false); ?>
    </ul>
</div>

<?php
$url_load = Yii::app()->createUrl('practice');
?>
<?php
$script = <<< EOD
$(function() {
    $('.mystudent').scrollLoad({
        url: '$url_load',
        getData: function(){
            //you can post some data along with ajax request
        },
        start: function(){
            loader.start();
        },
        ScrollAfterHeight : 95,			//this is the height in percentage after which ajax stars
        onload: function(html){
            $('table.entry-readlater tr:last').after(html);
            loader.stop();
        },
        continueWhile : function( resp ) {
            var append_entry = $('table.entry-readlater tr');
            if( $(append_entry).length >= 500 ) { // stops when number of 'div' reaches 100
                return false;
            }
            return true; 
        }
    });
});
EOD;
?>

<?php
Yii::app()->clientScript->registerScript('practice-' . rand(), $script, CClientScript::POS_END);
?>