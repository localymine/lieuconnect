<?php
/* @var $this ScholarshipController */
$this->pageTitle = Common::t('Result', 'post') . ' - ' . Common::t('Scholarship', 'post');
?>

<?php $this->renderPartial('internship/_form_search', array('keyword' => $keyword, 'select_major' => $select_major, 'select_location' => $select_location)) ?>

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
                            <th class="header-filter" style="width:45%;" height="41px">
                    <div class="dropdown inline-block" style="width: 70%;">
                        <a class="center-block" data-toggle="dropdown" href="#"><?php echo Common::t('Name', 'post') ?> <span class="collapse-expand"></span></a>
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
                    <div class="dropdown inline-block" style="width: 28%;">
                        <a class="center-block" data-toggle="dropdown" href="#">&nbsp;&nbsp;&nbsp; <span class="collapse-expand"></span></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li>
                                <?php $active = ($intern_condition[0] == -1) ? 'active' : '' ?>
                                <?php
                                $icon_params = $params;
                                $icon_params['icon'] = -1;
                                ?>
                                <?php echo CHtml::link(Common::t('All', 'post'), $icon_params, array('class' => "center-block $active")) ?>
                            </li>
                            <?php foreach (Post::item_alias('internship-condition') as $key => $value): ?>
                                <li>
                                    <?php $active = ($intern_condition[0] == $key) ? 'active' : '' ?>
                                    <?php
                                    $icon_params = $params;
                                    $icon_params['icon'] = $key;
                                    ?>
                                    <?php echo CHtml::link(Common::t($value, 'post'), $icon_params, array('class' => "center-block $active")) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    </th>
                    <th class="header-filter" style="width:19%;" height="41px">
                    <div class="dropdown">
                        <a class="center-block" data-toggle="dropdown" href="#"><?php echo Common::t('Compensation', 'post') ?><span class="collapse-expand"></span></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li>
                                <?php $active = ($compensation[0] == -1) ? 'active' : '' ?>
                                <?php
                                $comp_params = $params;
                                $comp_params['com'] = -1;
                                ?>
                                <?php echo CHtml::link(Common::t('All', 'post'), $comp_params, array('class' => "center-block $active")) ?>
                            </li>
                            <?php foreach (Post::item_alias('paid') as $key => $value): ?>
                                <li>
                                    <?php $active = ($compensation[0] == $key) ? 'active' : '' ?>
                                    <?php
                                    $com_params = $params;
                                    $com_params['com'] = $key;
                                    ?>
                                    <?php echo CHtml::link(Common::t($value, 'post'), $com_params, array('class' => "center-block $active")) ?>
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

        <div class="table-main">
            <table class="table">
                <?php $i = 0; ?>
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
                        <td class="intership-type-cell" style="width:45%;padding-left:10px;">
                            <p style="width:80%;float:left;">
                                <a href="<?php echo Yii::app()->createUrl('internship/show', array('slug' => $row->slug)) ?>"><?php echo $row->post_title ?></a>
                            </p>
                            <p style="float:right;">
                                <?php
                                $active_credit = ($row->flag_credit) ? 'visible' : 'hidden';
                                $active_partime = ($row->flag_partime) ? 'visible' : 'hidden';
                                $active_fulltime = ($row->flag_fulltime) ? 'visible' : 'hidden';
                                ?>
                                <span style="visibility:<?php echo $active_credit ?>" class="intership-credit intershipicon" data-toggle="tooltip" data-placement="top" title="<?php echo Common::t('College credit required') ?>"></span>
                                <span style="visibility:<?php echo $active_partime ?>" class="intership-fulltime intershipicon" data-toggle="tooltip" data-placement="top" title="<?php echo Common::t('Part-time') ?>"></span>
                                <span style="visibility:<?php echo $active_fulltime ?>" class="intership-halftime intershipicon" data-toggle="tooltip" data-placement="top" title="<?php echo Common::t('Full-time') ?>"></span>
                            </p>
                        </td>
                        <td style="width:19%;text-align:right;padding-right:15px;">
                            <?php echo $row->award ?>
                        </td>
                        <td>
                            <?php if (isset($row->deadline_string) && $row->deadline_string != ''): ?>
                                <?php echo Post::item_alias('deadline', $row->deadline_string) ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php $i++; ?>
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