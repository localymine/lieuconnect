<?php

class SearchController extends FrontController {

    public $lang = 'vi';
    public $pageSize = 20;
    public $cate_major = 'major';
    public $cate_location = 'location';
    public $pageSizeScholarship = '20';
    public $pageSizeInternship = '20';
    public $pageSizeCollege = '20';

    public function init() {
        $this->lang = Yii::app()->language;

        $this->pageSize = Yii::app()->setting->getValue('SIZE_OF_SEARCH_ALL');
        $this->pageSizeScholarship = Yii::app()->setting->getValue('SIZE_OF_SEARCH_SCHOLARSHIP');
        $this->pageSizeInternship = Yii::app()->setting->getValue('SIZE_OF_SEARCH_INTERNSHIP');
        $this->pageSizeCollege = Yii::app()->setting->getValue('SIZE_OF_SEARCH_COLLEGE');
    }

    public function actionIndex() {
        $model = new Post;
        $select_title = array();
        //
        $result = NULL;
        $item_count = 0;
        $page_size = $this->pageSize;

        // create params for url
        $route = Yii::app()->controller->route;
        $params = $_GET;
        Common::pop_by_key('high-school', $params);
        Common::pop_by_key('college', $params);
        array_unshift($params, $route);

        if (isset($_GET['kw']) && trim($_GET['kw']) != '') {

            $keyword = $_GET['kw'];
            $select_title[] = isset($_GET['t']) ? $_GET['t'] : array();
            $select_type_of_school[] = isset($_GET['tos']) ? $_GET['tos'] : array();
            $select_award[] = isset($_GET['aw']) ? $_GET['aw'] : array();
            $select_month[] = isset($_GET['dl']) ? $_GET['dl'] : array();   // deadline

            if (trim($keyword) != '') {

                // $filter['title'] = $select_title[0];
                $filter['type_of_school'] = $select_type_of_school[0];
                $filter['award'] = $select_award[0];
                $filter['deadline'] = $select_month[0];
                
                // tracking
                $log = new Logs();
                $log->root = 'logs/search';
                $log->content = array(
                    array(
                        Yii::app()->user->id,
                        isset(Yii::app()->user->username) ? Yii::app()->user->username : '',
                        '',
                        'all',
                        $_GET['kw'],
                        '',
                        '',
                        Common::get_current_date(),
                    ),
                );
                $log->track();

                $item_count = $model->search_all_count($keyword, $filter)->count();

                // the pagination itself
                $pages = new CPagination($item_count);
                $pages->setPageSize($page_size);

                $result = $model->localized($this->lang)->search_all($keyword, $filter, $pages->limit, $pages->offset)->findAll();
            }

            $this->render('result', array(
                'keyword' => $keyword,
                'select_title' => $select_title,
                'type_of_school' => $select_type_of_school,
                'select_award' => $select_award,
                'select_month' => $select_month,
                'result' => $result,
                'item_count' => $item_count,
                'page_size' => $page_size,
                'pages' => $pages,
                'params' => $params,
            ));
        } else {
            $this->render('no_result');
        }
    }

    public function actionScholarship() {
        $model = new Post;
        $select = array();
        $select_major = array();
        $select_location = array();
        $select_provider = array();
        //
        $result = NULL;
        $filter = NULL;
        $item_count = 0;
        $page_size = $this->pageSizeScholarship;

        // create params for url
        $route = Yii::app()->controller->route;
        $params = $_GET;
        Common::pop_by_key('high-school', $params);
        Common::pop_by_key('college', $params);
        array_unshift($params, $route);

        if (isset($_GET['k']) && trim($_GET['k']) != '') {

            $keyword = $_GET['k'];
            $select_major[] = $_GET['m'];   // major
            $select_location[] = $_GET['l'];    // location
            $select_award[] = isset($_GET['aw']) ? $_GET['aw'] : array();
            $select_provider[] = isset($_GET['p']) ? $_GET['p'] : array();  //
            $select_type_of_school[] = isset($_GET['tos']) ? $_GET['tos'] : array();    // type of school
            $select_month[] = isset($_GET['dl']) ? $_GET['dl'] : array();   // deadline

            if (isset($_GET['high-school'])) {
                // tracking
                $log = new Logs();
                $log->root = 'logs/search';
                $log->content = array(
                    array(
                        Yii::app()->user->id,
                        isset(Yii::app()->user->username) ? Yii::app()->user->username : '',
                        'high-school',
                        'scholarship',
                        $_GET['k'],
                        $_GET['m'],
                        $_GET['l'],
                        Common::get_current_date(),
                    ),
                );
                $log->track();
            } else if (isset($_GET['college'])) {
                // tracking
                $log = new Logs();
                $log->root = 'logs/search';
                $log->content = array(
                    array(
                        Yii::app()->user->id,
                        isset(Yii::app()->user->username) ? Yii::app()->user->username : '',
                        'college',
                        'scholarship',
                        $_GET['k'],
                        $_GET['m'],
                        $_GET['l'],
                        Common::get_current_date(),
                    ),
                );
                $log->track();
            }

            if (trim($keyword) != '') {

                $select = array_merge($select_major, $select_location);
                $filter['provider'] = $select_provider[0];
                $filter['type_of_school'] = $select_type_of_school[0];
                $filter['award'] = $select_award[0];
                $filter['deadline'] = $select_month[0];

                $item_count = $model->scholarship_search_count($keyword, $select, $this->cate_major, $this->cate_location, $filter)->count();

                // the pagination itself
                $pages = new CPagination($item_count);
                $pages->setPageSize($page_size);

                $result = $model->localized($this->lang)->scholarship_search($keyword, $select, $this->cate_major, $this->cate_location, $filter, $pages->limit, $pages->offset)->findAll();
            }

            $this->render('scholarship/result', array(
                'keyword' => $keyword,
                'select_major' => $select_major,
                'select_location' => $select_location,
                'select_provider' => $select_provider,
                'type_of_school' => $select_type_of_school,
                'select_award' => $select_award,
                'select_month' => $select_month,
                'result' => $result,
                'item_count' => $item_count,
                'page_size' => $page_size,
                'pages' => $pages,
                'params' => $params,
            ));
        } else {
            $this->render('no_result', array(
                'keyword' => '',
                'select_major' => $select_major,
                'select_location' => $select_location,
            ));
        }
    }

    public function actionInternship() {
        $model = new Post;
        $select = array();
        $select_major = array();
        $select_location = array();
        $select_provider = array();
        //
        $result = NULL;
        $filter = NULL;
        $item_count = 0;
        $page_size = $this->pageSize;

        // create params for url
        $route = Yii::app()->controller->route;
        $params = $_GET;
        Common::pop_by_key('high-school', $params);
        Common::pop_by_key('college', $params);
        array_unshift($params, $route);

        if (isset($_GET['k']) && trim($_GET['k']) != '') {

            $keyword = $_GET['k'];
            $select_major[] = $_GET['m'];
            $select_location[] = $_GET['l'];
            $select_award[] = isset($_GET['aw']) ? $_GET['aw'] : array();
            $select_provider[] = isset($_GET['p']) ? $_GET['p'] : array();
            $select_type_of_school[] = isset($_GET['tos']) ? $_GET['tos'] : array();
            $select_intern_condition[] = isset($_GET['icon']) ? $_GET['icon'] : array();
            $select_compensation[] = isset($_GET['com']) ? $_GET['com'] : array();
            $select_month[] = isset($_GET['dl']) ? $_GET['dl'] : array();   // deadline

            if (isset($_GET['high-school'])) {
                // tracking
                $log = new Logs();
                $log->root = 'logs/search';
                $log->content = array(
                    array(
                        Yii::app()->user->id,
                        isset(Yii::app()->user->username) ? Yii::app()->user->username : '',
                        'high-school',
                        'internship',
                        $_GET['k'],
                        $_GET['m'],
                        $_GET['l'],
                        Common::get_current_date(),
                    ),
                );
                $log->track();
            } else if (isset($_GET['college'])) {
                // tracking
                $log = new Logs();
                $log->root = 'logs/search';
                $log->content = array(
                    array(
                        Yii::app()->user->id,
                        isset(Yii::app()->user->username) ? Yii::app()->user->username : '',
                        'college',
                        'internship',
                        $_GET['k'],
                        $_GET['m'],
                        $_GET['l'],
                        Common::get_current_date(),
                    ),
                );
                $log->track();
            }

            if (trim($keyword) != '') {

                $select = array_merge($select_major, $select_location);
                $filter['provider'] = $select_provider[0];
                $filter['type_of_school'] = $select_type_of_school[0];
                $filter['intern_condition'] = $select_intern_condition[0];
                $filter['compensation'] = $select_compensation[0];
                $filter['award'] = $select_award[0];
                $filter['deadline'] = $select_month[0];

                $item_count = $model->internship_search_count($keyword, $select, $this->cate_major, $this->cate_location, $filter)->count();

                // the pagination itself
                $pages = new CPagination($item_count);
                $pages->setPageSize($page_size);

                $result = $model->localized($this->lang)->internship_search($keyword, $select, $this->cate_major, $this->cate_location, $filter, $pages->limit, $pages->offset)->findAll();
            }


            $this->render('internship/result', array(
                'keyword' => $keyword,
                'select_major' => $select_major,
                'select_location' => $select_location,
                'select_provider' => $select_provider,
                'type_of_school' => $select_type_of_school,
                'intern_condition' => $select_intern_condition,
                'compensation' => $select_compensation,
                'select_award' => $select_award,
                'select_month' => $select_month,
                'result' => $result,
                'item_count' => $item_count,
                'page_size' => $page_size,
                'pages' => $pages,
                'params' => $params,
            ));
        } else {
            $this->render('no_result', array(
                'keyword' => '',
                'select_major' => $select_major,
                'select_location' => $select_location,
            ));
        }
    }

    public function actionCollege() {
        $model = new Post;
        $select = array();
        $select_major = array();
        $select_location = array();
        $select_title = array();
        //
        $result = NULL;
        $item_count = 0;
        $page_size = $this->pageSizeCollege;

        // create params for url
        $route = Yii::app()->controller->route;
        $params = $_GET;
        Common::pop_by_key('high-school', $params);
        Common::pop_by_key('college', $params);
        array_unshift($params, $route);

        if (isset($_GET['k']) && trim($_GET['k']) != '') {

            $keyword = $_GET['k'];
            $select_major[] = $_GET['m'];
            $select_location[] = $_GET['l'];
//            $select_title[] = isset($_GET['title']) ? $_GET['title'] : array();
            $select_award[] = isset($_GET['aw']) ? $_GET['aw'] : array();
            $select_type_of_school[] = isset($_GET['tos']) ? $_GET['tos'] : array();
            $select_campus_setting[] = isset($_GET['camp']) ? $_GET['camp'] : array();
            $select_month[] = isset($_GET['dl']) ? $_GET['dl'] : array();   // deadline

            if (isset($_GET['high-school'])) {
                // tracking
                $log = new Logs();
                $log->root = 'logs/search';
                $log->content = array(
                    array(
                        Yii::app()->user->id,
                        isset(Yii::app()->user->username) ? Yii::app()->user->username : '',
                        'high-school',
                        'college',
                        $_GET['k'],
                        $_GET['m'],
                        $_GET['l'],
                        Common::get_current_date(),
                    ),
                );
                $log->track();
            } else if (isset($_GET['college'])) {
                // tracking
                $log = new Logs();
                $log->root = 'logs/search';
                $log->content = array(
                    array(
                        Yii::app()->user->id,
                        isset(Yii::app()->user->username) ? Yii::app()->user->username : '',
                        'college',
                        'college',
                        $_GET['k'],
                        $_GET['m'],
                        $_GET['l'],
                        Common::get_current_date(),
                    ),
                );
                $log->track();
            }

            if (trim($keyword) != '') {

                $select = array_merge($select_major, $select_location);
                // $filter['title'] = $select_title[0];
                $filter['type_of_school'] = $select_type_of_school[0];
                $filter['campus_setting'] = $select_campus_setting[0];
                $filter['award'] = $select_award[0];
                $filter['deadline'] = $select_month[0];

                $item_count = $model->college_search_count($keyword, $select, $this->cate_major, $this->cate_location, $filter)->count();

                // the pagination itself
                $pages = new CPagination($item_count);
                $pages->setPageSize($page_size);

                $result = $model->localized($this->lang)->college_search($keyword, $select, $this->cate_major, $this->cate_location, $filter, $pages->limit, $pages->offset)->findAll();
            }


            $this->render('college/result', array(
                'keyword' => $keyword,
                'select_major' => $select_major,
                'select_location' => $select_location,
                // 'select_title' => $select_title,
                'type_of_school' => $select_type_of_school,
                'campus_setting' => $select_campus_setting,
                'select_award' => $select_award,
                'select_month' => $select_month,
                'result' => $result,
                'item_count' => $item_count,
                'page_size' => $page_size,
                'pages' => $pages,
                'params' => $params,
            ));
        } else {
            $this->render('no_result', array(
                'keyword' => '',
                'select_major' => $select_major,
                'select_location' => $select_location,
            ));
        }
    }

}
