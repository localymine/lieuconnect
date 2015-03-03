<?php
$this->pageTitle = Common::t('Advertise', 'home') . ' | ' . 'Lilely Connect';
?>

<div class="main-frame">
    <div class="feature">
        <span class="lilely-btn no-clickable student-btn">Advertise</span>
    </div>
    <div class="main forgot-password">
        <?php $this->renderPartial('_' . $this->lang . '_form', array('model' => $model, 'model_ads_relate' => $model_ads_relate)) ?>
    </div>
</div>