<ul class="nav navbar-nav navbar-right nav-lang">
    <li id="lang" class="dropdown nav-menu-dropdown">
        <a href="#" class="dropdown-toggle language-button" data-toggle="dropdown"><i class="glyphicon glyphicon-globe"></i><?php echo Common::t($languages[$language], 'common'); ?></a>
        <ul class="dropdown-menu">
        <?php foreach ($languages as $l => $lang): ?>

            <?php
            $params['language'] = $l;
    //        $image = CHtml::image(Yii::app()->baseUrl . '/images/flags/' . $l . '.png', $lang);
            if ($l === $language) {
    //            continue;
                echo '<li>' . CHtml::link(Common::t($lang, 'common'), $params, array('class' => "lang-btn $l-lang active")) . '</li>';
            } else {
                echo '<li>' . CHtml::link(Common::t($lang, 'common'), $params, array('class' => "lang-btn $l-lang")) . '</li>';
            }
            ?>

        <?php endforeach; ?>
        </ul>
    </li>
</ul>

<?php
$script = <<< EOD
$(function() {
    var showLang = $('.lang-btn.active');
    $('.nav-lang').hover(function() {
        $('.lang-btn').addClass('active');
    }, function() {
        $('.lang-btn').removeClass('active');
        showLang.addClass('active');
    });
});
EOD;
?>

<?php
Yii::app()->clientScript->registerScript('lang-selector-' . rand(), $script, CClientScript::POS_END);
?>