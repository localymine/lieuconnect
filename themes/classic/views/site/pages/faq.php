<?php
$this->pageTitle = Common::t('FAQ');

Common::register_js(Yii::app()->baseUrl . '/js/jquery-ui-1.10.4.custom.min.js', CClientScript::POS_END);

$script = <<< EOD
$(function(){
    $('.accordion').accordion();
    $('.faq-ref').click(function(){
        scrollToAnchor($(this).data('id'));
    });
});
EOD;
Common::register_script('faq-accordion-', $script, CClientScript::POS_END);
?>

<div class="main-frame">
    <div class="feature">
        <div class="dropdown">
            <a href="#" data-toggle="dropdown" class="lilely-btn faq-btn"><?php echo Common::t('Frequently Asked Questions (FAQ)', 'footer') ?><span class="collapse-expand"></span></a>
            <ul aria-labelledby="dLabel" role="menu" class="dropdown-menu">
                <?php foreach ($terms as $row): ?>
                    <li><a class="hand faq-ref" data-id="<?php echo $row->terms->slug ?>"><?php echo $row->terms->name ?></a></li>
                <?php endforeach; ?>
                <li><a href="<?php echo Yii::app()->createUrl('home') ?>"><?php echo Common::t('Home', 'home') ?></a></li>
            </ul>
        </div>
    </div>
    <div class="main faq">
        <?php foreach ($terms as $row): ?>
        <h2 id="<?php echo $row->terms->slug ?>" class="well well-sm">FAQ: <?php echo $row->terms->name ?></h2>
            <div class="accordion">
                <?php $model = Page::model()->localized($this->lang)->get_faq_by_term_id($row->term_id)->findAll() ?>
                <?php foreach ($model as $element): ?>
                    <h3 class=""><i class="fa fa-angle-double-right"></i> <?php echo $element->post_title ?></h3>
                    <div><p><?php echo $element->post_content ?></p></div>
                <?php endforeach; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php $this->widget('MoreStuff') ?>