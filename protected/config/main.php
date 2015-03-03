<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'LieuConnect!',
    'charset' => 'UTF-8',
    'defaultController' => 'home',
    'sourceLanguage' => 'en',
    'language' => 'vi',
    'theme' => 'classic',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.components.*',
        'application.extensions.*',
        'application.extensions.YiiMailer',
        'application.modules.menu.models.*',
        'application.modules.menu.components.*',
        'application.modules.backend.models.*',
        'application.modules.backend.components.*',
        'application.modules.user.models.*',
        'application.modules.user.components.*',
    ),
    'modules' => array(
//        'backend' => array(
//            'ipFilters' => array('127.0.0.1'), //Allow from
//        ),
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'admin',
        // If removed, Gii defaults to localhost only. Edit carefully to taste.
//            'ipFilters' => array('127.0.0.1', '::1'),
        ),
        'menu',
        'user' => array(
            'tableUsers' => 'users',
            'tableProfiles' => 'profiles',
            'tableProfileFields' => 'profiles_fields',
//            encrypting method (php hash function)
            'hash' => 'md5',
//            send activation email
            'sendActivationMail' => true,
//            allow access for non-activated users
            'loginNotActiv' => false,
//            activate user on registration (only sendActivationMail = false)
            'activeAfterRegister' => false,
//            automatically login from registration
            'autoLogin' => true,
//            registration path
            'registrationUrl' => array('/user/registration'),
//            recovery password path
            'recoveryUrl' => array('/user/recovery'),
//            login form path
            'loginUrl' => array('/user/login'),
//            page after login
            'returnUrl' => array('/user/profile'),
//            page after logout
            'returnLogoutUrl' => array('/user/login'),
        ),
        'backend' => array(
//            login form path
            'loginUrl' => array('/user/login'),
        ),
        'uploader' => array(
            'userModel' => 'user', //change to the model that has the pix column
            'userPixColumn' => 'image', //column to save the filename
            'folder' => 'avatars', //the dest folder(should be in the same folder level as protected folder)
        ),
    ),
    // application components
    'components' => array(
        'session' => array(
            'autoStart' => true,
            'timeout' => 86400, // 24 hours, 3 hours = 10800
        ),
        'user' => array(
            // enable cookie-based authentication
            'class' => 'WebUser',
            'allowAutoLogin' => true,
            'autoRenewCookie' => true,
            'authTimeout' => 31557600,
//            'returnUrl' => array('/user/profile'),
            'loginUrl' => array('/account/login'),
//            'loginUrl' => array('/backend/site/login'),
        ),
        'request' => array(
            'class' => 'ext.localeurls.LocaleHttpRequest',
            'languages' => array('vi' => 'Vietnamese', 'en' => 'English', 'es' => 'Español'),
            // advance
            'detectLanguage' => true,
            'languageCookieLifetime' => 31536000,
            'persistLanguage' => true,
            'redirectDefault' => false,
        ),
        // uncomment the following to enable URLs in path-format
        'urlManager' => array(
            'class' => 'ext.localeurls.LocaleUrlManager',
            'languageParam' => 'language', // advance
            'urlFormat' => 'path', // use "path" format, e.g. "post/show?id=4" rather than "r=post/show&id=4"
            'rules' => array(
                // custom rules
                'winner/show/<slug:[a-zA-Z0-9-]+>/' => 'winner/show',
                'story/<group:[a-zA-Z0-9-]+>/' => 'story',
                'story/show/<slug:[a-zA-Z0-9-]+>/' => 'story/show',
                'scholarship/show/<slug:[a-zA-Z0-9-]+>/' => 'scholarship/show',
                'internship/show/<slug:[a-zA-Z0-9-]+>/' => 'internship/show',
                'college/show/<slug:[a-zA-Z0-9-]+>/' => 'college/show',
                
                // default controller url setup     
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
//                'backend/<controller:\w+>'=>'<controller>backend/index',
//                'backend/<controller:\w+>/<id:\d+>'=>'<controller>backend/view',
//                'backend/<controller:\w+>/<id:\d+>/<action:\w+>'=>'<controller>backend/<action>',
//                'backend/<controller:\w+>/<action:\w+>'=>'<controller>backend/<action>',
                '<module:\w+>/<controller:\w+>/<id:\d+>' => '<module>/<controller>/view',
                '<module:\w+>/<controller:\w+>/<action:\w+>/<id:\d+>' => '<module>/<controller>/<action>',
                '<module:\w+>/<controller:\w+>/<action:\w+>' => '<module>/<controller>/<action>'
            ),
            'showScriptName' => false, // do not show "index.php" in URLs
            'appendParams' => false, // do not append parameters as name/value pairs (DO NOT CHANGE THIS)
//            'urlSuffix' => '.html',
        ),
        // connection MySQL database
        'db' => array(
            'connectionString' => 'mysql:host=localhost;dbname=db_lilely',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix' => '',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'file' => array(
            'class' => 'application.extensions.file.CFile',
        ),
        'upload' => array(
            'class' => 'application.extensions.Upload',
        ),
        'setting' => array('class' => 'WebSetting'),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                // uncomment the following to show log messages on web pages
                array(
                    'class' => 'CFileLogRoute',
                    'logFile' => 'trace.log',
                    'levels' => 'trace',
                    'categories' => 'application.*',
                ),
            /*
              array (
              'class'=>'CFileLogRoute',
              'logFile' => 'system.log',
              'categories'=>'system.db.*',
              ),
             */
            ),
        ),
//        'cache' => array(
//            'class' => 'system.caching.CFileCache',
//            'class' => 'CDbCache',
//        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // use for backend dashboard
        'ga_email' => '',
        'ga_password' => '',
        'ga_profile_id' => '',
        'ga_request_url' => '',
        /* ------------------------------------------------------------------ */
        'adminEmail' => 'webmaster@example.com',
        'siteUrl' => 'http://localhost/lilely',
        /* ------------------------------------------------------------------ */
        'translatedLanguages' => array('vi' => 'Vietnamese', 'en' => 'English', 'es' => 'Español'),
        'defaultLanguage' => 'vi',
        'pageSize' => 5,
        /* ------------------------------------------------------------------ */
        'show_in' => array(
            'location' => 'Location',
            'major' => 'Major',
            'school-type' => 'School Type',
            'school-funding' => 'School Funding',
            'admission-difficult' => 'Admission Difficult',
            'faq' => 'FAQ',
        ),
        /* ------------------------------------------------------------------ */
        // -- mail setting -- //
        'sender' => 'LilelyConnect',
        'from' => 'admin@lilely.com',
        // -- / mail setting -- //
        /* ------------------------------------------------------------------ */
        'set_mail_path' => 'http://localhost/lilely/images/mail/',
        'set_avatars_path' => 'avatars/',
        'set_school_logo' => 'images/school-logo/',
        'set_slide_path' => 'images/slide/',
        'set_story_path' => 'images/story/',
        'set_winner_path' => 'images/winner/',
        'set_scholarship_path' => 'images/scholarship/',
        'set_internship_path' => 'images/internship/',
        'set_college_path' => 'images/college/',
        'set_photos_path' => 'photos/',
    ),
);
