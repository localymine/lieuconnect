<?php
/* @var $this ScholarshipController */
$this->pageTitle = Common::t('Result', 'post') . ' - ' . Common::t('Search All', 'post');

// $keyword
?>

<div class="main-frame ">
    <div class="feature">
        <span class="lilely-btn no-clickable"><?php echo Common::t('Result', 'post') ?></span>
    </div>
    <div class="main result-search">
        <?php foreach ($result as $row): ?>
        <div class="col-md-12 no-padding college-item">
            <div class="img-frame no-padding">
                <?php $image = (isset($row->image)) ? $row->image : '0.jpg' ?>
                <a href="<?php echo Yii::app()->createUrl("$row->post_type/show", array('slug' => $row->slug)) ?>"><img src="<?php echo Yii::app()->baseUrl ?>/images/<?php echo $row->post_type ?>/<?php echo $image ?>" class="img-responsive img-thumbnail"></a>
            </div>
            <div class="content-frame">
                <h2 class="hidden-xs hidden-sm"><a href="<?php echo Yii::app()->createUrl("$row->post_type/show", array('slug' => $row->slug)) ?>"><?php echo $row->post_title ?></a></h2>
                <div class="college-row visible-sm visible-xs">
                    <h2><a href="<?php echo Yii::app()->createUrl("$row->post_type/show", array('slug' => $row->slug)) ?>"><?php echo $row->post_title ?></a></h2>
                </div>
                <p class="reward">
                    <a href="<?php echo Yii::app()->createUrl("$row->post_type/show", array('slug' => $row->slug)) ?>"><?php echo Common::strip_truncate($row->post_content, 400) ?></a>
                </p>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php
        // the pagination widget with some options to mess
        $this->widget('CLinkPager', array(
            'pages' => $pages,
            'currentPage' => $pages->getCurrentPage(),
            'itemCount' => $item_count,
            'pageSize' => $page_size,
            'maxButtonCount' => 5,
            'firstPageCssClass' => 'hidden',
            'firstPageLabel' => '<i class="fa fa-angle-double-left"></i>',
            'previousPageCssClass' => 'control',
            'prevPageLabel' => '<i class="fa fa-angle-left"></i>',
            'nextPageCssClass' => 'control',
            'nextPageLabel' => '<i class="fa fa-angle-right"></i>',
            'lastPageCssClass' => 'hidden',
            'lastPageLabel' => '<i class="fa fa-angle-double-right"></i>',
            'selectedPageCssClass' => 'active',
            'header' => '',
            'htmlOptions' => array('class' => 'pagination pull-right'),
        ));
        ?>
    </div>
</div>

<?php
$url_read_later = Yii::app()->createUrl('read');
$url_delete = Yii::app()->createUrl('box/delete');
$mess_1 = Common::t('Added into Read Later list');
$mess_2 = Common::t('You have to Login In');
?>
<?php
$script = <<< EOD
$(function() {
    $('.read').each(function(event) {
        $(this).css('cursor', 'pointer');
        var postid = $(this).data('id');
        
        $(this).click(function() {
            if ($(this).is(':checked')){
                $.ajax({
                    url: '$url_read_later',
                    type: 'POST',
                    data: {id: postid},
                    dataType: 'json',
                    beforeSend: function() {
                        loader.start();
                    },
                    success: function(msg) {
                        if (msg == 1) {
                            alert('$mess_1');
                        } else if (msg == -1) {
                            $('input:checkbox').removeAttr('checked');
                            alert('$mess_2');
                        }
                        loader.stop();
                    },
                    fail: function() {
                        loader.stop();
                    }
                });
            } else {
                $.ajax({
                    url: '$url_delete',
                    type: 'POST',
                    data: {id: postid},
                    dataType: 'json',
                    beforeSend: function() {
                        loader.start();
                    },
                    success: function(msg) {
                        loader.stop();
                    },
                    fail: function() {
                        loader.stop();
                    }
                });
            }
        });
    });
});
EOD;
?>

<?php
Yii::app()->clientScript->registerScript('read-later-' . rand(), $script, CClientScript::POS_END);
?>