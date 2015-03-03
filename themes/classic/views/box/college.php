<?php
$this->pageTitle = Common::t('College', 'post') . ' | ' . Common::t('Read Later', 'post') . ' | ' . Common::t('Lilely Connect');

Common::register_js(Yii::app()->theme->baseUrl . '/js/scroll.jquery.js', CClientScript::POS_END);
        
?>

<div class="table-main">
    <table class="table entry-readlater">
        <tr>
            <td style="width:7%;text-align:left;"><input type="checkbox" class="check-all"></td>
            <td style="width:70%;"><button class="delete-btn" data-toggle="tooltip" data-placement="right" title="<?php echo Common::t('Delete', 'post') ?>"><i class="fa fa-times"></i></button></td>
            <td style="text-align:right"></td>
        </tr>
        <?php if ($result != NULL) $this->renderPartial('_entry_one', array('result' => $result), false); ?>
    </table>
</div>

<?php
$url_load_read_later = Yii::app()->createUrl('box/college');
$url_read_later = Yii::app()->createUrl('box/deleteAll');
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
        
    $('.delete-btn').tooltip();
    $('.check-all').change(function() {
        if (this.checked) {
            $("input[name='item[]']").prop('checked', true);
        }
        else {
            $("input[name='item[]']").prop('checked', false);
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
                url: '$url_read_later',
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
                fail: function() {
                    loader.stop();
                }
            });
        }        
    });

    function deleteItem(item) {
        var item = $("input[data-number='" + item + "']").closest('tr').fadeOut('slow', function() {
            $(this).remove();
        });
    }
});
EOD;
?>

<?php
Yii::app()->clientScript->registerScript('box-read-later-' . rand(), $script, CClientScript::POS_END);
?>