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
    </head>

    <body>
        <div id="login-background">
            <?php $num = rand(1,10) ?>
            <img src="<?php echo Yii::app()->theme->baseUrl ?>/img/placeholders/headers/login_header<?php echo $num ?>.jpg" alt="Login Background" class="animation-pulseSlow" />
        </div>
        <div id="login-container" class="animation-fadeIn">
            <?php echo $content; ?>
        </div>

        <?php $this->widget('user.components.ModalTerms') ?>

        <script src="<?php echo Yii::app()->theme->baseUrl ?>/js/jquery.min.js"></script>
        <!--<script>!window.jQuery && document.write(unescape('%3Cscript src="<?php echo Yii::app()->theme->baseUrl ?>/js/vendor/jquery-1.11.0.min.js"%3E%3C/script%3E'));</script>-->
        <script src="<?php echo Yii::app()->theme->baseUrl ?>/js/vendor/bootstrap.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl ?>/js/plugins.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl ?>/js/app.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl ?>/js/pages/login.js"></script>
        <script>
            $(function() {
                Login.init();
            });
        </script>
<!--        <script>var _gaq = _gaq || [];
            _gaq.push(["_setAccount", "UA-16158021-6"]), _gaq.push(["_setDomainName", "pixelcave.com"]), _gaq.push(["_trackPageview"]), function() {
                var t = document.createElement("script");
                t.type = "text/javascript", t.async = !0, t.src = ("https:" == document.location.protocol ? "https://ssl" : "http://www") + ".google-analytics.com/ga.js";
                var e = document.getElementsByTagName("script")[0];
                e.parentNode.insertBefore(t, e)
            }();</script>-->
    </body>
</html>