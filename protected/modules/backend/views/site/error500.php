<div id="error-container">
    <div class="error-options">
        <h3><i class="fa fa-chevron-circle-left text-muted"></i> <a href="<?php echo Yii::app()->createUrl('backend/dashboard') ?>">Top Page</a></h3>
    </div>
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2 text-center">
            <h1><i class="fa fa-cog fa-spin text-danger"></i> 500</h1>
            <h2 class="h3">Oops, we are sorry but our server encountered an internal error..<br />But do not worry, we are on it..</h2>
            <h2 class="h3"><?php echo $error['message'] ?></h2>
        </div>
    </div>
</div>