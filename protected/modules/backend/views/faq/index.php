<?php
/* @var $this TermsController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "All FAQ" . ' | ' . Yii::app()->name;

Common::register('jquery.min.js', 'pro', CClientScript::POS_HEAD);
?>

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-brush"></i>All FAQ<br />
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'search-form',
                'method' => 'post',
                'enableAjaxValidation' => false,
                'htmlOptions' => array(
                    'role' => 'search',
                    'enctype' => 'multipart/form-data',
                    'class' => 'form-horizontal'
                ),
            ));
            ?>
            <div class="form-group">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" placeholder="Input keyword..." class="form-control" name="keyword" id="keyword" value="<?php echo $keyword ?>" />
                        <div class="input-group-btn">
                            <button class="search btn btn-danger" type="button">Search <span class="fa fa-search"></span></button>
                        </div>
                    </div>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="block-title">
                <h2>List FAQ</h2><div class="block-options pull-right"><i class="fa fa-star" style="color:#1BBAE1"></i>Show   <i class="fa fa-star" style="color:#ccc"></i>Hidden</div>
                <div class="table-responsive">

                    <table class="table table-vcenter table-striped">
                        <tr>
                            <th width="90%">FAQ Description</th>
                            <th width="10%">Category</th>
                            <th>&nbsp;</th>
                        </tr>
                        <?php foreach ($posts as $row): ?>
                            <tr>
                                <td>
                                    <!-- Nav tabs -->
                                    <ul class="nav nav-tabs">
                                        <li>ID: <strong><?php echo $row->id ?></strong></li>
                                        <?php
                                        foreach (Yii::app()->params['translatedLanguages'] as $l => $lang):
                                            if ($l === Yii::app()->params['defaultLanguage']) {
                                                $suffix = '';
                                                $active = ' active';
                                            } else {
                                                $suffix = '_' . $l;
                                                $active = '';
                                            }
                                            ?>
                                            <li class="<?php echo $active ?>"><a href="#<?php echo $lang . $row->id ?>" data-toggle="tab"><img width="16" src="<?php echo Yii::app()->baseUrl ?>/images/flags/<?php echo $l ?>.png"/></a></li>
                                        <?php endforeach; ?>
                                    </ul>
                                    <!-- Tab panes -->
                                    <div class="tab-content">
                                        <?php
                                        foreach (Yii::app()->params['translatedLanguages'] as $l => $lang):
                                            if ($l === Yii::app()->params['defaultLanguage']) {
                                                $suffix = '';
                                                $active = ' active';
                                            } else {
                                                $active = '';
                                                $suffix = '_' . $l;
                                            }
                                            ?>
                                            <div class="tab-pane fade in <?php echo $active ?>" id="<?php echo $lang . $row->id; ?>">
                                                <div class="block clearfix">
                                                    <?php
                                                    $post_title = 'post_title' . $suffix;
                                                    $post_content = 'post_content' . $suffix;
                                                    ?>
                                                    <div><strong><?php echo $row->{$post_title} ?></strong></div>
                                                    <p><?php echo Common::strip_nl_truncate($row->{$post_content}, 500) ?></p>
                                                    <div>Last Modified: <?php echo $row->post_modified ?></div>
                                                    <?php
                                                    unset($arr_cats);
                                                    ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $categories = TermFaqRelationships::model()->get_relate_terms($row->id, 'category')->findAll();
                                    $arr_cats = NULL;
                                    if ($categories != NULL) {
                                        foreach ($categories as $cat) {
                                            $t_name = $cat->termtaxonomy->terms->localized($l)->name;
                                            $arr_cats[] = $t_name;
                                        }
                                    }
                                    ?>
                                    <div><?php echo isset($arr_cats) ? join($arr_cats, ', ') : '' ?></div>
                                </td>
                                <td>
                                    <div class="block-options">
                                        <a title="" data-toggle="tooltip" data-postid="<?php echo $row->id ?>" class="status btn btn-alt btn-sm btn-default btn-option" data-original-title="Status"><i class="fa fa-star <?php echo ($row->disp_flag == 1) ? 'status-show' : 'status-hide' ?>"></i></a>
                                        <a title="" data-toggle="tooltip" class="btn btn-alt btn-sm btn-default btn-option" href="<?php echo Yii::app()->createUrl('backend/faq/update', array('id' => $row->id)) ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                                        <a title="" data-toggle="tooltip" data-postid="<?php echo $row->id ?>" class="delete btn btn-alt btn-sm btn-danger btn-option" data-original-title="Delete"><i class="fa fa-times"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 text-center">
        <?php
        // the pagination widget with some options to mess
        $this->widget('CLinkPager', array(
            'pages' => $pages,
            'currentPage' => $pages->getCurrentPage(),
            'itemCount' => $item_count,
            'pageSize' => $page_size,
            'maxButtonCount' => 5,
            'firstPageCssClass' => '',
            'firstPageLabel' => '<i class="fa fa-angle-double-left"></i>',
            'prevPageLabel' => '<i class="fa fa-angle-left"></i>',
            'nextPageLabel' => '<i class="fa fa-angle-right"></i>',
            'lastPageCssClass' => '',
            'lastPageLabel' => '<i class="fa fa-angle-double-right"></i>',
            'selectedPageCssClass' => 'active',
            'header' => '',
            'htmlOptions' => array('class' => 'pagination pagination-sm'),
        ));
        ?>
    </div>
</div>

<script>
    $(function() {
        var ajaxloader = '<div id="ajaxloader"><i class="ajaxloader fa fa-spinner fa-4x fa-spin text-info"></i></div>';

        $('a.status').click(function(e) {
            e.preventDefault();
            var tap = $(this).children('i');
            var postid = $(this).data('postid');
            var request = $.ajax({
                url: '<?php echo Yii::app()->createUrl('backend/faq/status') ?>',
                type: 'POST',
                data: {id: postid},
                dataType: 'json',
                beforeSend: function() {
                    $('body').append(ajaxloader);
                },
                success: function(msg) {
                    if (msg == 0) {
                        $(tap).removeClass('status-show').addClass('status-hide');
                    } else if (msg == 1) {
                        $(tap).removeClass('status-hide').addClass('status-show');
                    }
                    $('#ajaxloader').remove();
                },
                error: function() {
                    $('#ajaxloader').remove();
                }
            });
        });

        $("a.delete").click(function(e) {
            e.preventDefault();
            if (confirm('Are you sure you want to delete this?\nCan not recover after delete.')) {
                var postid = $(this).data('postid');
                var request = $.ajax({
                    url: '<?php echo Yii::app()->createUrl('backend/faq/delete') ?>',
                    type: 'POST',
                    data: {id: postid},
                    dataType: 'json',
                    beforeSend: function() {
                        $('body').append(ajaxloader);
                    },
                    success: function(msg) {
                        if (msg == 0) {
                            alert('Can not delete.');
                        } else if (msg == 1) {
                            location.href = location.href;
                        }
                        $('#ajaxloader').remove();
                    },
                    error: function() {
                        $('#ajaxloader').remove();
                    }
                });
            }
        });

        $('.search').click(function(e) {
            e.preventDefault();
            if ($('#keyword').val() != '')
                $('#search-form').submit();
        });
    });
</script>