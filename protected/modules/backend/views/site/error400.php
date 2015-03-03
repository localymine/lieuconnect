<div id="error-container">
    <div class="error-options">
        <h3><i class="fa fa-chevron-circle-left text-muted"></i> <a href="<?php echo Yii::app()->createUrl('backend/dashboard') ?>">Top Page</a></h3>
    </div>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text-center">
            <h1><i class="fa fa-exclamation-triangle text-info animation-pulse"></i> 400</h1>
            <h2 class="h3">Oops, we are sorry but your request contains bad syntax and cannot be fulfilled..</h2>
            <h2 class="h3"><?php echo $error['message'] ?></h2>
        </div>
    </div>
</div>