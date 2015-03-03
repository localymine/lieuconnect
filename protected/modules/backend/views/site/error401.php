<div id="error-container">
    <div class="error-options">
        <h3><i class="fa fa-chevron-circle-left text-muted"></i> <a href="<?php echo Yii::app()->createUrl('backend/dashboard') ?>">Top Page</a></h3>
    </div>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text-center">
            <h1 class="animation-fadeIn"><i class="fa fa-times-circle-o text-muted"></i> 401</h1>
            <h2 class="h3">Oops, we are sorry but you are not authorized to access this page..</h2>
            <h2 class="h3"><?php echo $error['message'] ?></h2>
        </div>
    </div>
</div>