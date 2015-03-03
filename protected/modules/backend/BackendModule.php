<?php

class BackendModule extends CWebModule {

    public $loginUrl = array("/user/login");

    public function init() {
        // this method is called when the module is being created
        // you may place code here to customize the module or the application
        // import the module-level models and components
//        Yii::app()->sourceLanguage = 'vi';
//        Yii::app()->language = Yii::app()->sourceLanguage;
//        Yii::app()->language = Yii::app()->params->defaultLanguage;

        $this->setImport(array(
            'user.models.*',
            'user.components.*',
            'backend.models.*',
            'backend.components.*',
            'backend.messages.*',
            'uploader.models.*',
            'uploader.components.*',
        ));

        Yii::app()->setComponents(array(
            'errorHandler' => array(
                'errorAction' => 'backend/site/error',
            )
        ));
    }

    public function beforeControllerAction($controller, $action) {
        if (parent::beforeControllerAction($controller, $action)) {
            // this method is called before any module controller action is performed
            if (Yii::app()->user->id == NULL) {
//                Yii::app()->request->redirect(Yii::app()->homeUrl . 'user/login');

                $loginUrl = Yii::app()->getModule('user')->loginUrl;
                Yii::app()->request->redirect(Yii::app()->createUrl($loginUrl[0]));
                exit;
            }else{
                $superuser = Yii::app()->user->isAdmin();
                if ($superuser == 0){
                    $loginUrl = Yii::app()->getModule('user')->loginUrl;
                    Yii::app()->request->redirect(Yii::app()->createUrl($loginUrl[0]));
                    Yii::app()->end();
                }
            }
            

            return true;
        } else
            return false;
    }

    /**
     * @param $str
     * @param $params
     * @param $dic
     * @return string
     */
    public static function t($str = '', $language = null, $source = null, $dic = 'terms', $params = array()) {
        if (Yii::t("BackendModule", $str) == $str) {
            return Yii::t("BackendModule." . $dic, $str, $params, $source, $language);
        } else {
            return Yii::t("BackendModule", $str, $params, $source, $language);
        }
    }

}
