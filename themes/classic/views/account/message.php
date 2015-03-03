<?php
$this->pageTitle = Yii::app()->name . ' - ' . Common::t('Activation', 'account');
?>


<div class="main-frame">
    <div class="feature">
        <span class="lilely-btn no-clickable"><?php echo Common::t('Activation', 'account') ?></span>
    </div>
    <div class="">
        <p class="well">
            <?php echo $content; ?>
        </p>
    </div>
</div>