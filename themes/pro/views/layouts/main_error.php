<!DOCTYPE html>
<!--[if IE 8]>         <html class="no-js lt-ie9"> </html><![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8" />
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta name="description" content="" />
        <meta name="author" content="" />
        <meta name="robots" content="noindex, nofollow" />
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1.0" />
        <link rel="shortcut icon" href="<?php echo Yii::app()->theme->baseUrl ?>/img/favicon.ico" />
        <link rel="apple-touch-icon" href="<?php echo Yii::app()->theme->baseUrl ?>/img/icon57.png" sizes="57x57" />
        <link rel="apple-touch-icon" href="<?php echo Yii::app()->theme->baseUrl ?>/img/icon72.png" sizes="72x72" />
        <link rel="apple-touch-icon" href="<?php echo Yii::app()->theme->baseUrl ?>/img/icon76.png" sizes="76x76" />
        <link rel="apple-touch-icon" href="<?php echo Yii::app()->theme->baseUrl ?>/img/icon114.png" sizes="114x114" />
        <link rel="apple-touch-icon" href="<?php echo Yii::app()->theme->baseUrl ?>/img/icon120.png" sizes="120x120" />
        <link rel="apple-touch-icon" href="<?php echo Yii::app()->theme->baseUrl ?>/img/icon144.png" sizes="144x144" />
        <link rel="apple-touch-icon" href="<?php echo Yii::app()->theme->baseUrl ?>/img/icon152.png" sizes="152x152" />

        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/plugins.css" />
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/main.css" />
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/themes.css" />

        <script src="<?php echo Yii::app()->theme->baseUrl ?>/js/vendor/modernizr-2.7.1-respond-1.4.2.min.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        
        <style>
            #page-content{
                min-height: 0;
            }
        </style>
    </head>

    <body>

        <div id="page-content">

            <?php echo $content ?>

        </div>

        <footer class="clearfix">
            <div class="pull-left">
                <span id="year-copy"></span> &copy; <a href="http://lilelyconnect.com" target="_blank">Lilely Connect</a>
            </div>
        </footer>

    </body>
</html>