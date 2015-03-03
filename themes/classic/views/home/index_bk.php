<?php
/* @var $this HomeController */

$this->pageTitle = Yii::app()->name;

Common::register_js(Yii::app()->baseUrl . '/js/jquery-1.11.0.min.js', CClientScript::POS_HEAD);
Common::register('common.js', 'classic', CClientScript::POS_HEAD);
?>

<div>
    <h2>Student of the Month Scholarship  Winner</h2>
    <?php if ($model_winner != '') $this->renderPartial('_monthly_winner', array('posts' => $model_winner), false); ?>
</div>

<div>
    <h2>LilelyConnect</h2>
    <ul>
        <?php if ($model_story != '') $this->renderPartial('_lilelyconnnect_story', array('posts' => $model_story), false); ?>
        <li id="holder-view-more"><a id="view-more"><?php echo Common::t('Load More Stories', 'post') ?></a></li>
    </ul>
</div>


<script type="text/javascript">
    $(function() {
        $('#view-more').click(function(e) {
            $('body').append(ajaxloader);
            e.preventDefault();
            $.get('<?php echo Yii::app()->createUrl('home') ?>', {ajax: true},
            function(html) {
                if (html != 0) {
                    $(html).insertBefore('#holder-view-more');
                } else {
                    $('#holder-view-more').remove();
                }
                $('#ajaxloader').remove();
            });
        });
    });
</script>