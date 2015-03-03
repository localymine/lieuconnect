<?php
/* @var $this TermsController */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = "Our Picks College" . ' | ' . Yii::app()->name;

Common::register('jquery.min.js', 'pro', CClientScript::POS_HEAD);
Common::register('jquery-ui-1.10.4.custom.min.js', 'pro', CClientScript::POS_END);

//Common::register('style.css', 'pro/jPaginate');
//Common::register('jquery.paginate.js', 'pro/jPaginate', CClientScript::POS_END);

?>

<div class="content-header">
    <div class="header-section">
        <h1>
            <i class="gi gi-brush"></i>Pick College<br />
            <small>These picks will appear on right side bar.</small>
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="block">
            <div class="block-title"><h2>Our Picks</h2>
                <div class="block-options pull-right"><button id="update-pick" class="btn btn-sm btn-danger" type="button"><i class="fa fa-gears"></i></i> Update</button></div>
            </div>
            <div id="ourpicks" class="form-group">
                <ul id="sortable" class="gallery ui-helper-reset">
                    <?php if ($our_picks != '') $this->renderPartial('college/_pick_list', array('posts' => $our_picks), false); ?>
                </ul>
            </div>
        </div>

        <div class="block">
            <div class="block-title">
                <h2>List College</h2><div class="block-options pull-right"></div>
            </div>

            <div class="">
                <ul id="gallery" class="gallery ui-helper-reset">
                    <?php if ($list != ''): ?>
                        <?php echo $list ?>
                    <?php else: ?>
                        <?php $this->renderPartial('college/_list', array('posts' => $posts), false); ?>
                    <?php endif; ?>
                </ul>
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
            'maxButtonCount' => 200,
            'firstPageCssClass' => 'hidden',
            'firstPageLabel' => '<i class="fa fa-angle-double-left"></i>',
            'previousPageCssClass' => 'hidden',
            'prevPageLabel' => '<i class="fa fa-angle-left"></i>',
            'nextPageCssClass' => 'hidden',
            'nextPageLabel' => '<i class="fa fa-angle-right"></i>',
            'lastPageCssClass' => 'hidden',
            'lastPageLabel' => '<i class="fa fa-angle-double-right"></i>',
            'selectedPageCssClass' => '',
            'header' => '',
            'id' => 'link_pager',
            'htmlOptions' => array('class' => 'pagination pagination-sm'),
        ));
        ?>
<!--    
        <div class="col-md-12 text-center">
            <div id="jpagination" class="jPaginate"></div>
        </div>
-->
    </div>
</div>

<script type="text/javascript">
    $(function() {
        var ajaxloader = '<div id="ajaxloader"><i class="ajaxloader fa fa-spinner fa-4x fa-spin text-info"></i></div>';

        var $gallery = $('#gallery'), $ourpicks = $('#ourpicks');

        // let the gallery items be draggable
        $('li', $gallery).draggable({
            cacel: 'a.ui-icon', // clicking an icon won't initiate dragging
            revert: "invalid", // not dropped, the item will revert back
            helper: "clone",
            cursor: "move"
            /*
            stop: function(event) {
                $('#sortable').sortable('refresh');
                $('#sortable').sortable('refreshPositions');
            }
            */
        });

        // let the trash be droppable, accepting the gallery items
        $ourpicks.droppable({
            accept: '#gallery > li',
            activeClass: 'ui-state-highlight',
            drop: function(event, ui) {
                moveToBox(ui.draggable);
            }
        });

        // let the gallery be droppable as well, accepting items from the ourpicks
        $gallery.droppable({
            accept: '#ourpicks li',
            activeClass: 'custom-state-active',
            drop: function(event, ui) {
                deleteFromBox(ui.draggable);
            }
        });

        // move to box our picks 
        var recycle_icon = '<a title="Delete" class="ui-icon ui-icon-trash pull-right pr10"><i class="fa fa-trash-o"></i></a>';
        function moveToBox($item) {

            var postid = $item.data('postid');
            var data = new Array();
            data = get_current_picks();
            if ($.inArray(postid, data) == -1) {
                $item.fadeOut(function() {
                    var $list = $('ul', $ourpicks).length ? $('ul', $ourpicks) : $('<ul id="sortable" class="gallery ui-helper-reset"/>').appendTo($ourpicks);
                    $item.find("a.ui-icon-plus").remove();
                    $item.append(recycle_icon).appendTo($list).fadeIn(function() {
                        $item.animate({width: "70px"}).find('img.img-news').animate({width: "70px"});
                        $item.find('.hold-img-news').css({width: "68px", height: "70px"});
                    });
                });
            } else {
                alert('This item existed in Our Picks');
            }
        }

        // image recycle
        var plus_icon = '<a title="Add" class="ui-icon ui-icon-plus pull-right pr10"><i class="fa fa-plus"></i></a>';
        function deleteFromBox($item) {
            $item.fadeOut(function() {
                $item.find('a.ui-icon-trash').remove();
                $item.css({width: '100px'}).append(plus_icon)
                        .find('img.img-news').css({width: "126px"}).end().appendTo($gallery).fadeIn();
                $item.find('.hold-img-news').css({width: "98px", height: "100px"});
            });
        }

        // resolve the icons behavior with event delegation
        $(document).on('click', 'ul.gallery > li', function(event) {
            var $item = $(this), $target = $(event.target).parent();
            if ($target.is('a.ui-icon-trash')) {
                deleteFromBox($item);
            } else if ($target.is('a.ui-icon-plus')) {
                moveToBox($item);
            } else if ($target.is('a.ui-icon-edit')) {
                location.href = $target.attr('href');
            } else if ($target.is('a.ui-icon-left')) {
                var curr = $("#sortable li:first");
                curr.parent().append(curr);
            } else if ($target.is('a.ui-icon-right')) {
                var curr = $("#sortable li:last");
                curr.parent().prepend(curr);
            }
            return false;
        });

        $('#sortable').sortable({
            opacity: 0.6,
            delay: 100,
            cursor: 'move'
        }).disableSelection();

        $('#link_pager a').each(function() {
            $(this).click(function(ev) {
                $('body').append(ajaxloader);
                ev.preventDefault();
                $.get(this.href, {ajax: true}, function(html) {
                    $('#gallery').html(html);
                    $('#ajaxloader').remove();
                });
            });
        });

        function get_current_picks() {
            var data = new Array();
            $('#sortable').each(function() {
                $(this).find('li').each(function() {
                    var current = $(this);
                    data.push(current.data('postid'));
                })
            });
            return data;
        }

        $('#update-pick').click(function(e) {
            var data = new Array();
            data = get_current_picks();
            //
            if (data.length === 0) {
                //
            } else {
                // send data
                var jsonString = JSON.stringify(data);
                $.ajax({
                    type: 'POST',
                    url: '<?php echo Yii::app()->createUrl("backend/post/updatePickCollege"); ?>',
                    data: {data: jsonString},
                    dataType: 'json',
                    cache: false,
                    beforeSend: function() {
                        $('body').append(ajaxloader);
                    },
                    success: function(res) {
                        if (res == 1) {
                            alert('Your picks is updated.');
                        } else {
                            alert('Error occurred during updating')
                        }
                        $('#ajaxloader').remove();
                    }
                });
            }
        });

/*
        $("#jpagination").paginate({
            count: <?php // echo $pages->pageCount ?>,
            start: 1,
            display: 10,
            border: false,
            border_color: '#1BBAE1',
            text_color: '#1BBAE1',
            background_color: '#fff',
            border_hover_color: '#1BBAE1',
            text_hover_color: '#fff',
            background_hover_color: '#1BBAE1',
            images: false,
            mouse: 'press',
            onChange: function(page){
                var href = '<?php // echo Yii::app()->createUrl('backend/post/pickCollege?page=') ?>' + page;
                $('body').append(ajaxloader);
                $.get(href, {ajax: true}, function(html) {
                    $('#gallery').html(html);
                    $('#ajaxloader').remove();
                });
            }
        });
*/
    });
</script>