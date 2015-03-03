<?php

class Sidebar extends CWidget {

    public $controller = '';

    //put your code here
    public function init() {
        // this method is called by CController::beginWidget()
    }

    public function run() {

        $controllers_actions = array(
            array('controller' => array('dashboard'), 'action' => array('index')),  // 0
            array('controller' => array('terms'), 'action' => array('index')),  // 1
            array('controller' => array('post'), 'action' => array('story', 'addStory', 'updateStory', 'pickStory', 'scholarship', 'addScholarship', 'updateScholarship', 'pickScholarship', 'internship', 'addInternship', 'updateInternship', 'pickInternship', 'college', 'addCollege', 'updateCollege', 'pickCollege', 'winner', 'addWinner', 'updateWinner')), // 2
            array('controller' => array('profile'), 'action' => array('profile')),  // user/admin/ // 3
            array('controller' => array('tags'), 'action' => array('index')), // 4
            array('controller' => array('slide'), 'action' => array('index')), // 5
            array('controller' => array('recruit'), 'action' => array('index')), // 6
            array('controller' => array('resume'), 'action' => array('index')), // 7
            array('controller' => array('feeling'), 'action' => array('index')), // 8
            array('controller' => array('practiceTest'), 'action' => array('index')), // 9
            array('controller' => array('page'), 'action' => array('index', 'create', 'update')), // 10
            array('controller' => array('faq'), 'action' => array('index', 'create', 'update')), // 11
            array('controller' => array('advertise'), 'action' => array('index')), // 12
            array('controller' => array('setting'), 'action' => array('index')), // 13
            array('controller' => array('statistics'), 'action' => array('index')), // 14
        );
        
        $flags_open_menu = array(FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE); // <> controller
        $flags_active_menu = array(FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE, FALSE);

        $controller = Yii::app()->controller->id;
        $action = Yii::app()->controller->action->id;
        $module = isset(Yii::app()->controller->module->id) ? Yii::app()->controller->module->id : '';
        $c = 0;
        foreach ($controllers_actions as $row) {
            $controllers = $row['controller'];
            $actions = $row['action'];
            if (in_array($controller, $controllers)) {
                $flags_open_menu[$c] = TRUE;
                $i = 0;
                foreach($actions as $value) {
                    if ($action == $value){
                        $flags_active_menu[$i] = TRUE;
                        break;
                    }
                    $i++;
                }
                break;
            }
            $c++;
        }

        $module_user = Yii::app()->getModule('user'); 
        $admin = $module_user->isAdmin();
        
        $this->render('sidebar', array(
            'module' => $module,
            'controller' => $controller,
            'action' => $action,
            'flags_open' => $flags_open_menu,
            'flags_active' => $flags_active_menu,
            'admin' => $admin,
        ));
    }

}
