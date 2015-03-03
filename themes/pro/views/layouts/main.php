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
        <link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl ?>/css/style.css" />
        
        <script src="http://www.google-analytics.com/ga.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl ?>/js/vendor/modernizr-2.7.1-respond-1.4.2.min.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    </head>

    <body>
        <div id="page-container" class="sidebar-full">
            <div id="sidebar">
                <?php $this->widget('menu.components.Sidebar') ?>
            </div>
            
            <div id="main-container">
                
                <header class="navbar navbar-default">
                   <?php $this->widget('menu.components.Headerbar') ?>
                </header>
                
                <div id="page-content">
                    
                    <?php echo $content ?>
                    
                </div>
                
                <footer class="clearfix">
                    <div class="pull-left">
                        <span id="year-copy"></span> &copy; <a href="http://lilelyconnect.com" target="_blank">Lilely Connect</a>
                    </div>
                </footer>
            </div>
        </div>
        <a href="#" id="to-top"><i class="fa fa-angle-double-up"></i></a>
        
        <?php $this->widget('backend.components.ModalSetting') ?>

        <script src="<?php echo Yii::app()->theme->baseUrl ?>/js/jquery.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl ?>/js/vendor/bootstrap.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl ?>/js/plugins.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl ?>/js/app.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl ?>/js/ckeditor/ckeditor.js"></script>
        <script>
	<?php echo Yii::app()->setting->getValue('GOOGLE_ANALYTICS') ?>
	</script>
    </body>
</html>