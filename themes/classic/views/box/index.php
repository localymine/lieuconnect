<?php
$this->pageTitle = Common::t('Read Later', 'post') . ' | ' . 'Lilely Connect';

Common::register_js(Yii::app()->theme->baseUrl . '/js/scroll.jquery.js', CClientScript::POS_END);
        
?>

<div class="table-main">
    <header>
        <button class="readlater-btn-small check-all"><input id="check-all" type="checkbox"></button>
        <button class="readlater-btn delete-btn showtooltip" data-toggle="tooltip" title="<?php echo Common::t('Remove All', 'post') ?>"><?php echo Common::t('Remove All', 'post') ?></button>
        <button class="readlater-btn mark-read-btn showtooltip" data-toggle="tooltip" title="<?php echo Common::t('Mark As Read', 'post') ?>"><?php echo Common::t('Mark As Read', 'post') ?></button>
        <?php echo Common::t('Unread', 'post') ?> <span>(<?php echo ReadLater::total_unread() ?>)</span>
    </header>
    <ul class="readlater-list">
        <?php if ($result != NULL) $this->renderPartial('_entry_all', array('result' => $result), false); ?>
    </ul>
</div>

<?php
$url_load_read_later = Yii::app()->createUrl('box');
$url_remove_read_later = Yii::app()->createUrl('box/deleteAll');
$url_mark_read = Yii::app()->createUrl('box/mark_as_read');
$mess_1 = Common::t('Deleted');
$mess_2 = Common::t('An error occurred!');
?>
<?php
$script = <<< EOD
$(function() {
    $('.mystudent').scrollLoad({
        url: '$url_load_read_later',
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
        
    $('.showtooltip').tooltip();
    $('.check-all').click(function() {
        if ($(this).children().is(':checked')) {
            $(this).children().prop('checked', false);
            $('li input[type=checkbox]').each(function () {
                $("input[name='item[]']").prop('checked', false);
            });
        } else {
            $(this).children().prop('checked', true);
            $('li input[type=checkbox]').each(function () {
                $("input[name='item[]']").prop('checked', true);
            });
        }
    });
    $('.delete-btn').click(function() {
        var postid = [];
        $(':checkbox:checked').each(function(i) {
            var obj = $(this).attr('data-number');
            deleteItem(obj);
            postid.push(obj);
        });
        if (postid != null) {
            $.ajax({
                url: '$url_remove_read_later',
                type: 'POST',
                data: {data: JSON.stringify(postid)},
                dataType: 'json',
                beforeSend: function() {
                    loader.start();
                },
                success: function (msg) {
                    if (msg == 1) {
                        bootbox.alert('$mess_1', function(){
                        }).find("div.modal-dialog").addClass("largeWidth");
                        loader.stop();
                    } else {
                        bootbox.alert('$mess_2', function(){
                        }).find("div.modal-dialog").addClass("largeWidth");
                        loader.stop();
                    }
                },
                error: function() {
                    bootbox.alert('$mess_2', function(){
                        }).find("div.modal-dialog").addClass("largeWidth");
                    loader.stop();
                }
            });
        }
    });
    $('.mark-read-btn').click(function() {
        var postid = [];
        $(':checkbox:checked').each(function(i) {
            var obj = $(this).attr('data-number');
            deleteItem(obj);
            postid.push(obj);
        });
        if (postid != null) {
            $.ajax({
                url: '$url_mark_read',
                type: 'POST',
                data: {data: JSON.stringify(postid)},
                dataType: 'json',
                beforeSend: function() {
                    loader.start();
                },
                success: function (msg) {
                    location.href = location.href;
                },
                error: function() {
                    bootbox.alert('$mess_2', function(){
                        }).find("div.modal-dialog").addClass("largeWidth");
                    loader.stop();
                }
            });
        }
    });

    function deleteItem(item) {
        var item = $("input[data-number='" + item + "']").parent('li').fadeOut('slow', function() {
            $(this).remove();
        });
    }
});
EOD;
?>

<?php
Yii::app()->clientScript->registerScript('box-read-later-' . rand(), $script, CClientScript::POS_END);
?>