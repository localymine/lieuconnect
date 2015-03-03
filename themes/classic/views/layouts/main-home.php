<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="language" content="en" >
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <!--<link rel="shortcut icon" href="<?php // echo Yii::app()->theme->baseUrl ?>/ico/favicon.ico">-->
        <title><?php echo $this->pageTitle ?></title>
        <!-- Bootstrap core CSS -->
        <link href="<?php echo Yii::app()->theme->baseUrl ?>/css/bootstrap.min.css" rel="stylesheet">
        <!-- font awesome CSS -->
        <link href="<?php echo Yii::app()->theme->baseUrl ?>/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <!-- Custom styles for this template -->
        <link href="<?php echo Yii::app()->theme->baseUrl ?>/css/style.css" rel="stylesheet">
        
        <script>
            <?php echo Yii::app()->setting->getValue('FACEBOOK_SCRIPT') ?>
            <?php echo Yii::app()->setting->getValue('TWITTER_SCRIPT') ?>
            <?php echo Yii::app()->setting->getValue('GOOGLE_ANALYTICS') ?>
        </script>
        
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    </head>
    <body>
        <div id="fb-root"></div>
        <!-- container -->
        <div class="container">
            <!-- cover row -->
            <div class="row">
                
                <!-- menu -->
                <?php $this->widget('Menu') ?>
                <!-- / menu -->
                
                <div class="slider-banner">
                    <div class="container">
                        <div class="row">
                            <!-- slider carousel -->
                            <?php echo $content ?>
                            <!-- / slider carousel -->
                        </div>
                        <!-- footer -->
                        <div class="row">
                            <?php $this->widget('Footer') ?>
                        </div>
                        <!-- // footer -->
                    </div>
                </div>
                
            </div>
            <!-- / cover row -->
        </div>
        <!-- /container -->
        <script type="text/javascript" src="<?php echo Yii::app()->baseUrl ?>/js/jquery-1.11.0.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/js/common.js"></script>
    </body>
</html>
