<?php
/* @var $this ScholarshipController */
$this->pageTitle = Common::t('Result', 'post') . ' - ' . Common::t('Scholarship', 'post');
?>

<?php $this->renderPartial('college/_form_search', array('keyword' => $keyword, 'select_major' => $select_major, 'select_location' => $select_location)) ?>

<div class="main-frame ">
    <div class="main-table">
        <div class="table-header-frame">
            <div class="table-header-frame-inside">
                <table class="table table-header">
                    <thead>
                        <tr >
                            <th class="header-filter" style="width:16%;padding-left:10px;" height="41px">
                                <span><?php echo Common::t('Read later', 'post') ?></span>
                            </th>
                            <th class="header-filter" style="width:28%;" height="41px">
                    <div class="dropdown">
                        <a class="center-block" data-toggle="dropdown" href="#"><?php echo Common::t('Type of School', 'post') ?> <span class="collapse-expand"></span></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li>
                                <?php $active = ($type_of_school[0] == -1) ? 'active' : '' ?>
                                <?php
                                $tos_params = $params;
                                $tos_params['tos'] = -1;
                                ?>
                                <?php echo CHtml::link(Common::t('All', 'post'), $tos_params, array('class' => "center-block $active")) ?>
                            </li>
                            <?php foreach (Post::item_alias('type-of-school') as $key => $value): ?>
                                <li>
                                    <?php $active = ($type_of_school[0] == $key) ? 'active' : '' ?>
                                    <?php
                                    $tos_params = $params;
                                    $tos_params['tos'] = $key;
                                    ?>
                                    <?php echo CHtml::link(Common::t($value, 'post'), $tos_params, array('class' => "center-block $active")) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    </th>
                    <th class="header-filter" style="width:20%;" height="41px">
                    <div class="dropdown">
                        <a class="center-block" data-toggle="dropdown" href="#"><?php echo Common::t('Campus', 'post') ?><span class="collapse-expand"></span></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li>
                                <?php $active = ($campus_setting[0] == -1) ? 'active' : '' ?>
                                <?php
                                $camp_params = $params;
                                $camp_params['camp'] = -1;
                                ?>
                                <?php echo CHtml::link(Common::t('All', 'post'), $camp_params, array('class' => "center-block $active")) ?>
                            </li>
                            <?php foreach (Post::item_alias('campus-setting') as $key => $value): ?>
                                <li>
                                    <?php $active = ($campus_setting[0] == $key) ? 'active' : '' ?>
                                    <?php
                                    $camp_params = $params;
                                    $camp_params['camp'] = $key;
                                    ?>
                                    <?php echo CHtml::link(Common::t($value, 'post'), $camp_params, array('class' => "center-block $active")) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    </th>
                    <th class="header-filter" style="width:19%;" height="41px">
                    <div class="dropdown">
                        <a class="center-block" data-toggle="dropdown" href="#"><?php echo Common::t('Annual Tuition', 'post') ?><span class="collapse-expand"></span></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li>
                                <?php $active = ($select_award[0] == -1) ? 'active' : '' ?>
                                <?php
                                $a_params = $params;
                                $a_params['aw'] = -1;
                                ?>
                                <?php echo CHtml::link(Common::t('All', 'post'), $a_params, array('class' => "center-block $active")) ?>
                            </li>
                            <?php foreach (Post::item_alias('annual-tuition') as $key => $value): ?>
                                <li>
                                    <?php $active = ($select_award[0] == $key) ? 'active' : '' ?>
                                    <?php
                                    $a_params = $params;
                                    $a_params['aw'] = $key;
                                    ?>
                                    <?php echo CHtml::link(Common::t($value, 'post'), $a_params, array('class' => "center-block $active")) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    </th>
                    <th class="header-filter" height="41px">
                    <div class="dropdown">
                        <a class="center-block" data-toggle="dropdown" href="#"><?php echo Common::t('Deadline', 'post') ?><span class="collapse-expand"></span></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li>
                                <?php $active = ($select_month[0] == -1) ? 'active' : '' ?>
                                <?php
                                $d_params = $params;
                                $d_params['dl'] = -1;
                                ?>
                                <?php echo CHtml::link(Common::t('All', 'post'), $d_params, array('class' => "center-block $active")) ?>
                            </li>
                            <?php foreach (Post::item_alias('deadline') as $key => $value): ?>
                                <li>
                                    <?php $active = ($select_month[0] == $key) ? 'active' : '' ?>
                                    <?php
                                    $d_params = $params;
                                    $d_params['dl'] = $key;
                                    ?>
                                    <?php echo CHtml::link(Common::t($value, 'post'), $d_params, array('class' => "center-block $active")) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    </th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>

        <div class="table-main college-main">
            <table class="table">
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td style="width:16%;text-align:center;">
                            <?php
                            $checked = '';
                            if (isset($row->read_later[0])) {
                                $checked = ($row->id == $row->read_later[0]->object_id) ? 'checked' : '';
                            }
                            ?>
                            <input class="read" data-id="<?php echo $row->id ?>" type="checkbox" <?php echo $checked ?> />
                        </td>
                        <td style="padding-left:10px;" class="college-item">
                            <div class="img-list-frame no-padding">
                                <?php $college_logo = ($row->feature_image != '') ? $row->feature_image : '0.jpg'; ?> 
                                <img width="250" src="<?php echo Yii::app()->baseUrl ?>/images/college/<?php echo $college_logo ?>" class="img-responsive" />
                            </div>
                            <div class="content-list-frame">
                                <h2 class="hidden-xs hidden-sm"><a href="<?php echo Yii::app()->createUrl('internship/show', array('slug' => $row->slug)) ?>"><?php echo $row->post_title ?></a></h2>
                                <div class="college-row visible-sm visible-xs">
                                    <h2><a href="<?php echo Yii::app()->createUrl('internship/show', array('slug' => $row->slug)) ?>"><?php echo $row->post_title ?></a></h2>
                                    <?php $this->widget('LoginForm', array('type' => 'apply-button', 'title' => Common::t('Contact School Now', 'account'), 'post_id' => $row->id)) ?>
                                </div>
                                <p class="reward">
                                    <?php echo Common::strip_truncate($row->post_content, 1000) ?>
                                </p>
                            </div>
                            <div class="button-list-frame no-padding hidden-xs hidden-sm">
                                <?php $this->widget('LoginForm', array('type' => 'apply-button', 'title' => Common::t('Contact School Now', 'account'), 'post_id' => $row->id)) ?>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>

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
</div>

<?php $this->widget('LoginForm', array('type' => 'modal-login')) ?>
<?php $this->widget('LoginForm', array('type' => 'modal-recruit', 'title' => Common::t('Apply', 'account'))) ?>

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