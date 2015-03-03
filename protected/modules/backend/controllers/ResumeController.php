<?php

class ResumeController extends Controller {

    public function accessRules() {
        $module = Yii::app()->getModule('user');
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('about', 'info', 'export'),
                'users' => array('@'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionAbout() {
        $id = (int) $_POST['id'];

        $user = AUsers::model()->findByPk($id);
        $profile = $user->profile;

        if ($profile->public_profile) {

            $user = AUsers::model()->findByPk($id);
            $profile = $user->profile;

            $arr['username'] = $user->username;
            $arr['firstname'] = $profile->firstname;
            $arr['lastname'] = $profile->lastname;
            $arr['gender'] = AUsers::itemAlias('Gender', $profile->gender);
            $arr['birth_date'] = Common::date_format($profile->birth_date, 'Y-m-d');
            $arr['email'] = $user->email;
            $arr['phone'] = $profile->phone;
            $arr['address'] = $profile->address;
            $arr['state'] = $profile->state_ref->stateName;
            $arr['city'] = $profile->city_ref->cityName;
            $arr['country'] = $profile->country_ref->countryName;
            $arr['expectation'] = $profile->expectation;
            $arr['grade_year'] = $profile->grade_year;
            $arr['gpa'] = $profile->gpa;
//        $arr['create_at'] = $user->create_at;
            //
        echo json_encode($arr);
        } else {
            $arr_resume['CODE'] = 'ERR';
            echo json_encode($arr_resume);
        }
    }

    public function actionInfo() {
        $id = (int) $_POST['id'];

        $user = AUsers::model()->findByPk($id);
        $profile = $user->profile;

        if ($profile->public_resume) {

            $arr_resume = array();

            // Extra-Curricular & Activity
            $re_extra = new ResumeExtra;
            $da_extra = $re_extra->model()->findAllByAttributes(array('user_id' => $id));
            $i = 0;
            foreach ($da_extra as $row) {
                $arr_resume['extra'][$i]['type'] = $row->resume->type;
                $arr_resume['extra'][$i]['activity'] = $row->activity;
                $arr_resume['extra'][$i]['position'] = $row->position;
                $arr_resume['extra'][$i]['description'] = $row->description;
                $arr_resume['extra'][$i]['start'] = $row->start;
                $arr_resume['extra'][$i]['end'] = $row->end;
                $arr_resume['extra'][$i]['uptonow'] = $row->uptonow;
                $i++;
            }
            $arr_resume['tm_extra'] =  $this->renderPartial('_extra', array('model' => $da_extra) , TRUE, TRUE);

            // Experience
            $re_exper = new ResumeExper;
            $da_exper = $re_exper->model()->findAllByAttributes(array('user_id' => $id));
            $i = 0;
            foreach ($da_exper as $row) {
                $arr_resume['exper'][$i]['type'] = $row->resume->type;
                $arr_resume['exper'][$i]['employer'] = $row->employer;
                $arr_resume['exper'][$i]['position'] = $row->position;
                $arr_resume['exper'][$i]['industry'] = $row->industry->title;
                $arr_resume['exper'][$i]['description'] = $row->description;
                $arr_resume['exper'][$i]['start'] = $row->start;
                $arr_resume['exper'][$i]['end'] = $row->end;
                $arr_resume['exper'][$i]['uptonow'] = $row->uptonow;
            }
            $arr_resume['tm_exper'] =  $this->renderPartial('_experience', array('model' => $da_exper) , TRUE, TRUE);

            // Education
            $re_educa = new ResumeEducation;
            $da_educa = $re_educa->model()->findAllByAttributes(array('user_id' => $id));
            $i = 0;
            foreach ($da_educa as $row) {
                $arr_resume['educa'][$i]['type'] = $row->resume->type;
                $arr_resume['educa'][$i]['school_name'] = $row->school_name;
                $arr_resume['educa'][$i]['grade_year'] = $row->grade_year;
                $arr_resume['educa'][$i]['gpa'] = $row->gpa;
                $arr_resume['educa'][$i]['class_rank'] = $row->class_rank;
            }
            $arr_resume['tm_educa'] =  $this->renderPartial('_education', array('model' => $da_educa) , TRUE, TRUE);

            // Honors & Awards
            $re_honor = new ResumeHonorAward;
            $da_honor = $re_honor->model()->findAllByAttributes(array('user_id' => $id));
            $i = 0;
            foreach ($da_honor as $row) {
                $arr_resume['honor'][$i]['type'] = $row->resume->type;
                $arr_resume['honor'][$i]['description'] = $row->description;
            }
            $arr_resume['tm_honor'] =  $this->renderPartial('_honor_award', array('model' => $da_honor) , TRUE, TRUE);

            // Specialities & Skills
            $re_skill = new ResumeSkill;
            $da_skill = $re_skill->model()->findAllByAttributes(array('user_id' => $id));
            $i = 0;
            foreach ($da_skill as $row) {
                $arr_resume['skill'][$i]['type'] = $row->resume->type;
                $arr_resume['skill'][$i]['description'] = $row->description;
            }
            $arr_resume['tm_skill'] =  $this->renderPartial('_specialties_skills', array('model' => $da_skill) , TRUE, TRUE);

            // Interests
            $re_interest = new ResumeInterest;
            $da_interest = $re_interest->model()->findAllByAttributes(array('user_id' => $id));
            $i = 0;
            foreach ($da_interest as $row) {
                $arr_resume['interest'][$i]['type'] = $row->resume->type;
                $arr_resume['interest'][$i]['description'] = $row->description;
            }
            $arr_resume['tm_interest'] =  $this->renderPartial('_interests', array('model' => $da_interest) , TRUE, TRUE);

            // Favorites
            $re_favorite = new ResumeFavorite;
            $da_favorite = $re_favorite->model()->findAllByAttributes(array('user_id' => $id));
            $i = 0;
            foreach ($da_favorite as $row) {
                $arr_resume['favorite'][$i]['type'] = $row->resume->type;
                $arr_resume['favorite'][$i]['music'] = $row->music;
                $arr_resume['favorite'][$i]['tvshow'] = $row->tvshow;
                $arr_resume['favorite'][$i]['movie'] = $row->movie;
                $arr_resume['favorite'][$i]['quote'] = $row->quote;
                $arr_resume['favorite'][$i]['book'] = $row->book;
                $arr_resume['favorite'][$i]['website'] = $row->website;
            }
            $arr_resume['tm_favorite'] =  $this->renderPartial('_favorites', array('model' => $da_favorite) , TRUE, TRUE);

            echo json_encode($arr_resume);
        } else {
            $arr_resume['CODE'] = 'ERR';
            echo json_encode($arr_resume);
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex() {
        $model = new AUsers;

        $item_count = 0;
        $page_size = 50;
        $pages = NULL;

        $item_count = $model->superuser()->active()->count();

        // the pagination itself
        $pages = new CPagination($item_count);
        $pages->setPageSize($page_size);

        $criteria = new CDbCriteria(array(
            'join' => 'LEFT JOIN profiles ON user.id = profiles.user_id ',
            'order' => 'public_resume DESC, public_profile DESC, lastvisit_at DESC',
            'limit' => $pages->limit,
            'offset' => $pages->offset,
        ));

        $posts = $model->superuser()->active()->findAll($criteria);

        $this->render('index', array(
            'posts' => $posts,
            'item_count' => $item_count,
            'page_size' => $page_size,
            'pages' => $pages,
        ));
    }

    /**
     * export to csv.
     */
    public function actionExport() {

//        if (isset($_POST['Recruit'])) {
//
//            $data = array();
//            $Criteria = new CDbCriteria();
//            $Criteria->condition = ' DATE_FORMAT(create_date,"%Y-%m-%d") BETWEEN "' . $_POST['Recruit']['startDate'] . '" AND "' . $_POST['Recruit']['endDate'] . '"';
//            $model = Recruit::model()->findAll($Criteria);
//            $i = 0;
//            foreach ($model as $row) {
//                $data[$i]['no'] = $i;
//                $data[$i]['id'] = $row->id;
//                $data[$i]['post_title'] = $row->post->post_title;
//                $data[$i]['first_name'] = $row->first_name;
//                $data[$i]['last_name'] = $row->last_name;
//                $data[$i]['gender'] = ($row->gender == -1) ? 'NA' : ($row->gender == 1) ? 'Male' : 'Female';
//                $data[$i]['birth_date'] = Common::date_format($row->birth_date, 'Y-m-d');
//                $data[$i]['email'] = $row->email;
//                $data[$i]['phone'] = $row->phone;
//                $data[$i]['address'] = $row->address;
//                $data[$i]['state'] = $row->state->stateName;
//                $data[$i]['city'] = $row->city->cityName;
//                $data[$i]['country'] = $row->country->countryName;
//                $data[$i]['zipcode'] = $row->zipcode;
//                $data[$i]['school_name'] = $row->school_name;
//                $data[$i]['grade_year'] = $row->grade_year;
//                $data[$i]['gpa'] = $row->gpa;
//                $data[$i]['feeling'] = $row->feeling->title;
//                $data[$i]['create_date'] = $row->create_date;
//                $i++;
//            }
//
//            /* excel xml */
//            $fields[0]['no'] = "No.";
//            $fields[0]['id'] = "ID";
//            $fields[0]['post_title'] = "Post Title";
//            $fields[0]['first_name'] = "First Name";
//            $fields[0]['last_name'] = "Last Name";
//            $fields[0]['gender'] = "Gender";
//            $fields[0]['birth_date'] = "Birth Date";
//            $fields[0]['email'] = "Email";
//            $fields[0]['phone'] = "Phone";
//            $fields[0]['address'] = "Address";
//            $fields[0]['state'] = "State";
//            $fields[0]['city'] = "City";
//            $fields[0]['country'] = "Country";
//            $fields[0]['zipcode'] = "Zip/Postal";
//            $fields[0]['school_name'] = "School Name";
//            $fields[0]['grade_year'] = "Grade Year";
//            $fields[0]['gpa'] = "GPA";
//            $fields[0]['feeling'] = "Feeling";
//            $fields[0]['create_date'] = "Create Date";
//
//            $filename = 'Recruit_' . $_POST['Recruit']['startDate'] . '_' . $_POST['Recruit']['endDate'] . '-' . microtime();
//
//            Yii::import('application.extensions.Excel_XML');
//            $xls = new Excel_XML('UTF-8', 'UTF-8', date('Y-M'));
//            $xls->addArray($fields);
//            $xls->addArray($data);
//            $xls->generateXML($filename);
//            exit;
//        }
    }

}
