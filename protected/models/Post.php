<?php

/**
 * This is the model class for table "post".
 *
 * The followings are the available columns in table 'post':
 * @property string $id
 * @property string $post_author
 * @property string $post_type
 * @property string $post_group
 * @property string $post_date
 * @property string $post_title
 * @property string $winner_in_month
 * @property string $provided_by
 * @property string $provider
 * @property string $job_title
 * @property string $job_title_clean
 * @property string $position
 * @property string $position_clean
 * @property string $post_content
 * @property string $post_excerpt
 * @property string $quote_author
 * @property string $image
 * @property string $feature_image
 * @property string $school_logo
 * @property string $award
 * @property double $s_award
 * @property double $type_of_school
 * @property double $regular_admission
 * @property string $deadline
 * @property string $deadline_string
 * @property integer $flag_credit
 * @property integer $flag_partime
 * @property integer $flag_fulltime
 * @property integer $flag_paid
 * @property string $post_name
 * @property string $post_modified
 * @property string $campus
 * @property string $total_student
 * @property string $total_international
 * @property string $total_international_unit
 * @property string $total_asia
 * @property string $toefl_min
 * @property string $toefl_avg
 * @property string $application_fee
 * @property string $out_state_tutition
 * @property string $give_out_tutition
 * @property string $contract_type
 * @property string $contract_number
 * @property string $slug
 * @property integer $disp_flag
 */
class Post extends CActiveRecord {

    const STATUS_SHOW = 1;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'post';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('post_title', 'required'),
            array('winner_in_month, flag_credit, flag_partime, flag_fulltime, flag_paid, disp_flag', 'numerical', 'integerOnly' => true),
            array('s_award, total_student, total_international, total_asia, toefl_min, toefl_avg, application_fee, out_state_tutition, give_out_tutition, contract_number', 'numerical'),
            array('post_author', 'length', 'max' => 20),
            array('post_type, contract_type', 'length', 'max' => 45),
            array('provided_by, job_title, job_title_clean, position, position_clean, slug', 'length', 'max' => 256),
            array('provider, campus', 'length', 'max' => 126),
            array('image, feature_image, school_logo', 'file', 'types' => 'jpg, gif, png', 'maxSize' => 1024 * 1024 * 10, 'allowEmpty' => true, 'on' => 'insert'), // 10MB
            array('image, feature_image, school_logo', 'file', 'types' => 'jpg, gif, png', 'maxSize' => 1024 * 1024 * 10, 'allowEmpty' => true, 'on' => 'update'),
            array('quote_author', 'length', 'max' => 128),
            array('award, type_of_school, post_name', 'length', 'max' => 200),
            array('post_content, post_group, post_date, post_excerpt, regular_admission, deadline, deadline_string, post_modified, contract_type, total_international_unit', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, post_author, post_type, post_group, post_date, post_title, winner_in_month, provided_by, provider, job_title, job_title_clean, position, position_clean, post_content, post_excerpt, image, feature_image, award, s_award, type_of_school, deadline, deadline_string, flag_credit, flag_partime, flag_fulltime, flag_paid, post_name, post_modified, campus, slug, disp_flag', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'post_by' => array(self::BELONGS_TO, 'AProfiles', 'post_author'),
            'post_user' => array(self::BELONGS_TO, 'AUsers', 'post_author'),
            'pick_relate' => array(self::HAS_MANY, 'PickRelationships', 'term_id'),
            'read_later' => array(self::HAS_MANY, 'ReadLater', 'object_id', 'condition' => 'user_id = ' . Yii::app()->user->id),
            'read_later_user' => array(self::HAS_MANY, 'ReadLater', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'post_author' => 'Post Author',
            'post_type' => 'Post Type',
            'post_group' => 'Post Group',
            'post_date' => 'Post Date',
            'post_title' => 'Post Title',
            'winner_in_month' => 'Winner in Month',
            'provided_by' => 'Provided By',
            'provider' => 'Provider',
            'job_title' => 'Job Title',
            'job_title_clean' => 'Job Title Clean',
            'position' => 'Position',
            'position_clean' => 'Position Clean',
            'post_content' => 'Post Content',
            'post_excerpt' => 'Post Excerpt',
            'image' => 'Image',
            'feature_image' => 'Feature Image',
            'award' => 'Award',
            's_award' => 'for search',
            'type_of_school' => 'Type of School',
            'regular_admission' => 'Regular Admission',
            'deadline' => 'Deadline',
            'deadline_string' => 'Deadline String',
            'flag_credit' => 'College Credit Required',
            'flag_partime' => 'Partime',
            'flag_fulltime' => 'Fulltime',
            'flag_paid' => 'Unpaid/Paid',
            'post_name' => 'Post Name',
            'post_modified' => 'Post Modified',
            'campus' => 'Campus',
            'total_student' => 'Total Student',
            'total_international' => 'Total International',
            'total_asia' => 'Total Asia',
            'toefl_min' => 'Toefl Min',
            'toefl_avg' => 'Toefl Average',
            'slug' => 'Slug',
            'disp_flag' => 'Disp Flag',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);
        $criteria->compare('post_author', $this->post_author, true);
        $criteria->compare('post_type', $this->post_type, true);
        $criteria->compare('post_group', $this->post_group, true);
        $criteria->compare('post_date', $this->post_date, true);
        $criteria->compare('post_title', $this->post_title, true);
        $criteria->compare('winner_in_month', $this->winner_in_month, true);
        $criteria->compare('provided_by', $this->provided_by, true);
        $criteria->compare('provider', $this->provider, true);
        $criteria->compare('post_content', $this->post_content, true);
        $criteria->compare('job_title', $this->job_title, true);
        $criteria->compare('job_title_clean', $this->job_title_clean, true);
        $criteria->compare('position', $this->position, true);
        $criteria->compare('position_clean', $this->position_clean, true);
        $criteria->compare('post_excerpt', $this->post_excerpt, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('feature_image', $this->feature_image, true);
        $criteria->compare('award', $this->award, true);
        $criteria->compare('s_award', $this->s_award);
        $criteria->compare('type_of_school', $this->type_of_school);
        $criteria->compare('deadline', $this->deadline, true);
        $criteria->compare('deadline_string', $this->deadline_string, true);
        $criteria->compare('flag_credit', $this->flag_credit);
        $criteria->compare('flag_partime', $this->flag_partime);
        $criteria->compare('flag_fulltime', $this->flag_fulltime);
        $criteria->compare('flag_paid', $this->flag_paid);
        $criteria->compare('post_name', $this->post_name, true);
        $criteria->compare('post_modified', $this->post_modified, true);
        $criteria->compare('campus', $this->campus, true);
        $criteria->compare('slug', $this->slug, true);
        $criteria->compare('disp_flag', $this->disp_flag);

        return new CActiveDataProvider($this, array(
//            'criteria' => $criteria,
            'criteria' => $this->ml->modifySearchCriteria($criteria),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Post the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function behaviors() {
        return array(
            'ml' => array(
                'class' => 'application.models.behaviors.MultilingualBehavior',
                'langClassName' => 'PostLang',
                'langTableName' => 'post_lang',
                'langForeignKey' => 'post_id',
                'langField' => 'lang_id',
                'localizedAttributes' => array('post_title', 'provided_by', 'job_title', 'job_title_clean', 'position', 'position_clean', 'post_content', 'post_excerpt', 'quote_author', 'image', 'feature_image', 'award', 's_award', 'post_name', 'slug'), //attributes of the model to be translated
                'localizedPrefix' => 'l_',
                'languages' => Yii::app()->params['translatedLanguages'], // array of your translated languages. Example : array('fr' => 'Français', 'en' => 'English')
                'defaultLanguage' => Yii::app()->params['defaultLanguage'], //your main language. Example : 'fr'
                'createScenario' => 'insert',
                'localizedRelation' => 'i18nPost',
                'multilangRelation' => 'multilangPost',
                'forceOverwrite' => false,
                'forceDelete' => true,
                'dynamicLangClass' => true, //Set to true if you don't want to create a 'PostLang.php' in your models folder
            ),
//            'SlugBehavior' => array(
//                'class' => 'application.models.behaviors.SlugBehavior',
//                'slug_col' => 'slug',
//                'title_col' => 'post_title',
//                'max_slug_chars' => 255,
//                'overwrite' => false
//            ),
            'sluggable' => array(
                'class' => 'ext.behaviors.SluggableBehavior.SluggableBehavior',
                'columns' => array('post_title'),
                'unique' => true,
                'update' => false,
                'useInflector' => true,
            ),
        );
    }

    public static function item_alias($type, $code = NULL) {
        $_items = array(
            // name
            'scholarship' => array(
                'corporate' => Common::t('Corporate'),
                'college' => Common::t('College'),
            ),
            // name
            'internship' => array(
                'profit' => Common::t('Profit'),
                'non-profit' => Common::t('Non-Profit'),
                'college' => Common::t('College'),
                'government' => Common::t('Government'),
            ),
            // condition
            'internship-condition' => array(
                'credit' => Common::t('College credit required'),
                'partime' => Common::t('Part-time'),
                'fulltime' => Common::t('Full-time'),
            ),
            // COMPENSATION - den bu, boi thuong
            'paid' => array(
                '0' => Common::t('Unpaid'),
                '1' => Common::t('Paid'),
            ),
            // DEADLINE
            'deadline' => array(
                'ongo' => Common::t('Ongoing'),
                'spri-sem' => Common::t('Spring semester'),
                'summ-sem' => Common::t('Summer semester'),
                'fall-sem' => Common::t('Fall semester'),
                'jan' => Common::t('January'),
                'feb' => Common::t('February'),
                'mar' => Common::t('March'),
                'apr' => Common::t('April'),
                'may' => Common::t('May'),
                'jun' => Common::t('June'),
                'jul' => Common::t('July'),
                'aug' => Common::t('August'),
                'sep' => Common::t('September'),
                'oct' => Common::t('October'),
                'nov' => Common::t('November'),
                'dec' => Common::t('December'),
            ),
            // TYPE OF SCHOOL
            'type-of-school' => array(
                '2-yr' => Common::t('2-year/Community college'),
                '4-yr' => Common::t('4-year college or university'),
                'single-sex' => Common::t('Single-sex college'),
                'public' => Common::t('Public'),
                'private' => Common::t('Private'),
            ),
            // CAMPUS SETTING
            'campus-setting' => array(
                'rural' => Common::t('Rural'), // nong thon
                'suburban' => Common::t('Suburban'), // ngoai o
                'urban' => Common::t('Urban'), // do thi
            ),
            // ANNUAL TUITION - hoc thuong nien
            'annual-tuition' => array(
                'inexpensive' => Common::t('$ (<$20,000) Inexpensive', 'post'),
                'moderate' => Common::t('$$ ($20,000-$29,000) Moderate', 'post'),
                'expensive' => Common::t('$$$ ($30,000-$39,000) Expensive', 'post'),
                'very-expensive' => Common::t('$$$$ ($40,000+) Very expensive', 'post'),
            ),
            'month' => array(
                '1' => Common::t('January'),
                '2' => Common::t('February'),
                '3' => Common::t('March'),
                '4' => Common::t('April'),
                '5' => Common::t('May'),
                '6' => Common::t('June'),
                '7' => Common::t('July'),
                '8' => Common::t('August'),
                '9' => Common::t('September'),
                '10' => Common::t('October'),
                '11' => Common::t('November'),
                '12' => Common::t('December'),
            ),
            // LILELYCONNECT
            'story-group' => array(
                'campus-life' => Common::t('Campus life'),
                'cultural-immersion' => Common::t('Cultural immersion'),
                'diversity-issues' => Common::t('Diversity Issues'),
                'inspiration' => Common::t('Inspiration'),		
                'health-safety' => Common::t('Health & Safety'),
            ),
            'alphabet-order' => array(
                '0' => Common::t('A->Z'),
                '1' => Common::t('Z->A'),
            ),
            //
            'contract-type' => array(
                '0' => Common::t('None'),
                '1' => Common::t('Excellent Aid'),
                '2' => Common::t('Best Aid'),
                '3' => Common::t('Good Aid'),
                '4' => Common::t('Some Aid'),
            ),
            //
            'cal-unit' => array(
                '' => 'none',
                '%' => '%',
            ),
        );

        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items) ? $_items[$type] : false;
    }

    public static function sort_amount($code = NULL) {
        $_items = array(
            '1' => Common::t('Price Low to High'),
            '2' => Common::t('Price High to Low'),
        );

        if (isset($code))
            return isset($_items[$code]) ? $_items[$code] : false;
        else
            return isset($_items) ? $_items : false;
    }

    public function defaultScope() {
        return $this->ml->localizedCriteria();
    }

    public function scopes() {
        return array(
            'winner' => array(
                'condition' => "disp_flag = " . self::STATUS_SHOW . " AND post_type = 'winner' ",
                'limit' => 12,
            ),
            'story' => array(
                'condition' => "disp_flag = " . self::STATUS_SHOW . " AND post_type = 'story' ",
                'limit' => 3,
            ),
            'scholarship' => array(
                'condition' => "disp_flag = " . self::STATUS_SHOW . " AND post_type = 'scholarship' ",
                'limit' => 12,
            ),
            'internship' => array(
                'condition' => "disp_flag = " . self::STATUS_SHOW . " AND post_type = 'internship' ",
                'limit' => 12,
            ),
            'college' => array(
                'condition' => "disp_flag = " . self::STATUS_SHOW . " AND post_type = 'college' ",
                'limit' => 12,
            ),
            'sort_by_date' => array(
                'order' => "post_date DESC",
            )
        );
    }

    public function get_post_type($post_id) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => "id = " . (int) $post_id,
            'select' => 'post_type'
        ));
        return $this;
    }

    public function get_story($post_group = '', $limit = 3, $current_page = 1) {
        $offset = $limit * ($current_page - 1);

        if ($post_group == '') {
            $this->getDbCriteria()->mergeWith(array(
                'condition' => "disp_flag = " . self::STATUS_SHOW . " AND post_type = 'story' ",
                'order' => 'post_date DESC',
                'limit' => $limit,
                'offset' => $offset,
            ));
        } else {
            $this->getDbCriteria()->mergeWith(array(
                'condition' => "disp_flag = " . self::STATUS_SHOW . " AND post_type = 'story' AND post_group = :post_group ",
                'order' => 'post_date DESC',
                'limit' => $limit,
                'offset' => $offset,
                'params' => array('post_group' => $post_group),
            ));
        }

        return $this;
    }

    public function get_post_by_author($post_author, $limit = 3, $current_page = 1) {

        $offset = $limit * ($current_page - 1);

        $this->getDbCriteria()->mergeWith(array(
            'condition' => "disp_flag = :disp_flag AND post_author = :post_author ",
            'order' => 'post_date DESC',
            'limit' => $limit,
            'offset' => $offset,
            'params' => array('disp_flag' => self::STATUS_SHOW, 'post_author' => (int) $post_author),
        ));

        return $this;
    }

    public function get_scholarship($limit = 3, $current_page = 1) {
        $offset = $limit * ($current_page - 1);

        $this->getDbCriteria()->mergeWith(array(
            'condition' => "disp_flag = " . self::STATUS_SHOW . " AND post_type = 'scholarship' ",
            'order' => 'post_date DESC',
            'limit' => $limit,
            'offset' => $offset,
        ));

        return $this;
    }

    public function get_internship($limit = 3, $current_page = 1) {
        $offset = $limit * ($current_page - 1);

        $this->getDbCriteria()->mergeWith(array(
            'condition' => "disp_flag = " . self::STATUS_SHOW . " AND post_type = 'internship' ",
            'order' => 'post_date DESC',
            'limit' => $limit,
            'offset' => $offset,
        ));

        return $this;
    }

    public function get_college($limit = 3, $current_page = 1) {
        $offset = $limit * ($current_page - 1);

        $this->getDbCriteria()->mergeWith(array(
            'condition' => "disp_flag = " . self::STATUS_SHOW . " AND post_type = 'college' ",
            'order' => 'post_date DESC',
            'limit' => $limit,
            'offset' => $offset,
        ));

        return $this;
    }

    public function get_post_by_slug($slug, $post_type = 'winner') {

        $this->getDbCriteria()->mergeWith(array(
            'condition' => "disp_flag = " . self::STATUS_SHOW . " AND post_type = '$post_type' AND (slug = '$slug' OR l_slug = '$slug')",
        ));

        return $this;
    }

    /* ---------- Search Engine ---------------- */

    public function all_condition($keyword, $filter = NULL) {
        $condition = " disp_flag = " . self::STATUS_SHOW . " ";
        if (trim($keyword) != '') {
            $keyword = Common::clean_text($keyword);
            $keyword = trim(Slug::to_alphabet($keyword));
            $t_keys = Common::make_keywords($keyword);
            $condition .= " AND (post_name LIKE '%$keyword%' ";
            foreach ($t_keys as $key) {
                $condition .= " OR post_content LIKE '%$key%' ";
                $condition .= " OR post_name LIKE '%$key%' ";
                $condition .= " OR job_title_clean LIKE '%$key%' ";
                $condition .= " OR position_clean LIKE '%$key%' ";
                $condition .= " OR deadline_string LIKE '%$key%' ";
            }
            $condition .= ")";
        }

        // filter
        if ($filter != NULL) {
            if ($filter['type_of_school'] != NULL) {
                $type_of_school = trim($filter['type_of_school']);
                if ($type_of_school != -1) {
                    $condition .= " AND type_of_school LIKE '$type_of_school' ";
                }
            }
            //
            if ($filter['award'] != NULL) {
                $award = trim($filter['award']);
                if ($award != -1) {
                    switch ($award) {
                        case 'inexpensive':
                            $condition .= " AND s_award < 20000 ";
                            break;
                        case 'moderate':
                            $condition .= " AND (s_award BETWEEN 20000 AND 29000) ";
                            break;
                        case 'expensive':
                            $condition .= " AND (s_award BETWEEN 30000 AND 39000) ";
                            break;
                        case 'very-expensive':
                            $condition .= " AND s_award >= 40000 ";
                            break;
                    }
                }
            }
            //
            if ($filter['deadline'] != NULL) {
                $deadline = trim($filter['deadline']);
                if ($deadline != -1) {
                    $condition .= " AND deadline_string LIKE '$deadline' ";
                }
            }
        }

        return $condition;
    }

    public function search_all_count($keyword, $filter = NULL) {
        $condition = $this->all_condition($keyword, $filter);

        $this->getDbCriteria()->mergeWith(array(
            'join' => 'LEFT JOIN term_relationships ON id = object_id',
            'condition' => $condition,
            'group' => 'id',
        ));

        return $this;
    }

    public function search_all($keyword, $filter = NULL, $limit = 10, $offset = 0) {
        $condition = $this->all_condition($keyword, $filter);

        $this->getDbCriteria()->mergeWith(array(
            'join' => 'LEFT JOIN term_relationships ON id = object_id',
            'condition' => $condition,
            'limit' => $limit,
            'offset' => $offset,
            'group' => 'id',
            'order' => 'post_date DESC',
        ));

        return $this;
    }

    public function scholarship_condition($keyword, $category, $show_in_major, $show_in_location, $filter = NULL) {
        $condition = " disp_flag = " . self::STATUS_SHOW . " AND post_type = 'scholarship' ";
        if (trim($keyword) != '') {
            $keyword = trim(Slug::to_alphabet($keyword));
            $t_keys = Common::make_keywords($keyword);
            $condition .= " AND (post_name LIKE '%$keyword%' ";
            foreach ($t_keys as $key) {
                $condition .= " OR post_name LIKE '%$key%' ";
            }
            $condition .= ")";
        }

        $t_cate[$show_in_major] = $category[0];
        $t_cate[$show_in_location] = $category[1];
        $t_cat = array();
        foreach ($t_cate as $key => $value) {
            if ($value == -1) {
                $all_cat = TermTaxonomy::model()->get_all_terms_by($key)->findAll();
                foreach ($all_cat as $value) {
                    $t_cat[] = $value->term_taxonomy_id;
                }
            } else {
                $t_cat[] = $value;
            }
        }
        $condition .= " AND term_taxonomy_id IN (" . implode(',', $t_cat) . ") ";

        // filter
        if ($filter != NULL) {
            if ($filter['type_of_school'] != NULL) {
                $type_of_school = trim($filter['type_of_school']);
                if ($type_of_school != -1) {
                    $condition .= " AND type_of_school LIKE '$type_of_school' ";
                }
            }
            //
            if ($filter['award'] != NULL) {
                $award = trim($filter['award']);
                if ($award != -1) {
                    switch ($award) {
                        case 'inexpensive':
                            $condition .= " AND s_award < 20000 ";
                            break;
                        case 'moderate':
                            $condition .= " AND (s_award BETWEEN 20000 AND 29000) ";
                            break;
                        case 'expensive':
                            $condition .= " AND (s_award BETWEEN 30000 AND 39000) ";
                            break;
                        case 'very-expensive':
                            $condition .= " AND s_award >= 40000 ";
                            break;
                    }
                }
            }
            //
            if ($filter['deadline'] != NULL) {
                $deadline = trim($filter['deadline']);
                if ($deadline != -1) {
                    $condition .= " AND deadline_string LIKE '$deadline' ";
                }
            }
        }

        return $condition;
    }

    public function scholarship_search_count($keyword, $category, $show_in_major, $show_in_location, $filter) {
        $condition = $this->scholarship_condition($keyword, $category, $show_in_major, $show_in_location, $filter);

        $this->getDbCriteria()->mergeWith(array(
            'join' => 'LEFT JOIN term_relationships ON id = object_id',
            'condition' => $condition,
            'group' => 'id',
        ));

        return $this;
    }

    public function scholarship_search($keyword, $category, $show_in_major, $show_in_location, $filter = NULL, $limit = 10, $offset = 0) {

        $condition = $this->scholarship_condition($keyword, $category, $show_in_major, $show_in_location, $filter);

        $this->getDbCriteria()->mergeWith(array(
            'join' => 'LEFT JOIN term_relationships ON id = object_id',
            'condition' => $condition,
            'limit' => $limit,
            'offset' => $offset,
            'group' => 'id',
            'order' => 'post_date DESC',
        ));

        return $this;
    }

    public function internship_condition($keyword, $category, $show_in_major, $show_in_location, $filter = NULL) {
        $condition = " disp_flag = " . self::STATUS_SHOW . " AND post_type = 'internship' ";
        if (trim($keyword) != '') {
            $keyword = trim(Slug::to_alphabet($keyword));
            $t_keys = Common::make_keywords($keyword);
            $condition .= " AND (post_name LIKE '%$keyword%' OR job_title_clean LIKE '%$keyword%' OR position_clean LIKE '%$keyword%' ";
            foreach ($t_keys as $key) {
                $condition .= " OR post_name LIKE '%$key%' OR job_title_clean LIKE '%$key%' OR position_clean LIKE '%$key%' ";
            }
            $condition .= ")";
        }

        $t_cate[$show_in_major] = $category[0];
        $t_cate[$show_in_location] = $category[1];
        $t_cat = array();
        foreach ($t_cate as $key => $value) {
            if ($value == -1) {
                $all_cat = TermTaxonomy::model()->get_all_terms_by($key)->findAll();
                foreach ($all_cat as $value) {
                    $t_cat[] = $value->term_taxonomy_id;
                }
            } else {
                $t_cat[] = $value;
            }
        }
        $condition .= " AND term_taxonomy_id IN (" . implode(',', $t_cat) . ")";

        // filter
        if ($filter != NULL) {
            if ($filter['type_of_school'] != NULL) {
                $type_of_school = trim($filter['type_of_school']);
                if ($type_of_school != -1) {
                    $condition .= " AND type_of_school LIKE '$type_of_school' ";
                }
            }
            //
            if ($filter['intern_condition'] != NULL) {
                $intern_condition = trim($filter['intern_condition']);
                if ($intern_condition != -1) {
                    switch ($award) {
                        case 'credit':
                            $condition .= " AND flag_credit = 1 ";
                            break;
                        case 'partime':
                            $condition .= " AND flag_partime = 1 ";
                            break;
                        case 'fulltime':
                            $condition .= " AND flag_fulltime = 1 ";
                            break;
                    }
                }
            }
            //
            if ($filter['compensation'] != NULL) {
                $compensation = (int) trim($filter['compensation']);
                if ($compensation != -1 && in_array($compensation, array(0, 1))) {
                    $condition .= " AND flag_paid = $compensation ";
                }
            }
            //
            if ($filter['deadline'] != NULL) {
                $deadline = trim($filter['deadline']);
                if ($deadline != -1) {
                    $condition .= " AND deadline_string LIKE '$deadline' ";
                }
            }
        }

        return $condition;
    }

    public function internship_search_count($keyword, $category, $show_in_major, $show_in_location, $filter = NULL) {
        $condition = $this->internship_condition($keyword, $category, $show_in_major, $show_in_location, $filter);

        $this->getDbCriteria()->mergeWith(array(
            'join' => 'LEFT JOIN term_relationships ON id = object_id',
            'condition' => $condition,
            'group' => 'id',
        ));

        return $this;
    }

    public function internship_search($keyword, $category, $show_in_major, $show_in_location, $filter = NULL, $limit = 10, $offset = 0) {

        $condition = $this->internship_condition($keyword, $category, $show_in_major, $show_in_location, $filter);

        $this->getDbCriteria()->mergeWith(array(
            'join' => 'LEFT JOIN term_relationships ON id = object_id',
            'condition' => $condition,
            'limit' => $limit,
            'offset' => $offset,
            'group' => 'id',
            'order' => 'post_date DESC',
        ));

        return $this;
    }

    public function college_condition($keyword, $category, $show_in_major, $show_in_location, $filter = NULL) {
        $condition = " disp_flag = " . self::STATUS_SHOW . " AND post_type = 'college' ";
        if (trim($keyword) != '') {
            $keyword = Common::clean_text($keyword);
            $keyword = trim(Slug::to_alphabet($keyword));
            $t_keys = Common::make_keywords($keyword);
            $condition .= " AND (post_name LIKE '%$keyword%' ";
            foreach ($t_keys as $key) {
                $condition .= " OR post_name LIKE '%$key%' ";
            }
            $condition .= ")";
        }

        $t_cate[$show_in_major] = $category[0];
        $t_cate[$show_in_location] = $category[1];
        $t_cat = array();
        foreach ($t_cate as $key => $value) {
            if ($value == -1) {
                $all_cat = TermTaxonomy::model()->get_all_terms_by($key)->findAll();
                foreach ($all_cat as $value) {
                    $t_cat[] = $value->term_taxonomy_id;
                }
            } else {
                $t_cat[] = $value;
            }
        }
        $condition .= " AND term_taxonomy_id IN (" . implode(',', $t_cat) . ")";

        // filter
        if ($filter != NULL) {
            if ($filter['type_of_school'] != NULL) {
                $type_of_school = trim($filter['type_of_school']);
                if ($type_of_school != -1) {
                    $condition .= " AND type_of_school LIKE '$type_of_school' ";
                }
            }
            //
            if ($filter['campus_setting'] != NULL) {
                $campus_setting = trim($filter['campus_setting']);
                if ($campus_setting != -1) {
                    $condition .= " AND campus LIKE '$campus_setting' ";
                }
            }
            //
            if ($filter['award'] != NULL) {
                $award = trim($filter['award']);
                if ($award != -1) {
                    switch ($award) {
                        case 'inexpensive':
                            $condition .= " AND s_award < 20000 ";
                            break;
                        case 'moderate':
                            $condition .= " AND (s_award BETWEEN 20000 AND 29000) ";
                            break;
                        case 'expensive':
                            $condition .= " AND (s_award BETWEEN 30000 AND 39000) ";
                            break;
                        case 'very-expensive':
                            $condition .= " AND s_award >= 40000 ";
                            break;
                    }
                }
            }
            //
            if ($filter['deadline'] != NULL) {
                $deadline = trim($filter['deadline']);
                if ($deadline != -1) {
                    $condition .= " AND deadline_string LIKE '$deadline' ";
                }
            }
        }

        return $condition;
    }

    public function college_search_count($keyword, $category, $show_in_major, $show_in_location, $filter = NULL) {
        $condition = $this->college_condition($keyword, $category, $show_in_major, $show_in_location, $filter);

        $this->getDbCriteria()->mergeWith(array(
            'join' => 'LEFT JOIN term_relationships ON id = object_id',
            'condition' => $condition,
            'group' => 'id',
        ));

        return $this;
    }

    public function college_search($keyword, $category, $show_in_major, $show_in_location, $filter = NULL, $limit = 10, $offset = 0) {

        $condition = $this->college_condition($keyword, $category, $show_in_major, $show_in_location, $filter);

        $this->getDbCriteria()->mergeWith(array(
            'join' => 'LEFT JOIN term_relationships ON id = object_id',
            'condition' => $condition,
            'limit' => $limit,
            'offset' => $offset,
            'group' => 'id',
            'order' => 'post_date DESC',
        ));

        return $this;
    }

    /* ---------- // Search Engine ---------------- */

    /* ---------- Read Later ---------------- */

    public function get_read_later_count($post_type = NULL) {
        if ($post_type != NULL) {
            $this->getDbCriteria()->mergeWith(array(
                'join' => 'LEFT JOIN read_later ON id = object_id',
                'condition' => 'user_id = :user_id AND post_type = :post_type',
                'params' => array('user_id' => Yii::app()->user->id, 'post_type' => $post_type),
            ));
            return $this;
        } else {
            $this->getDbCriteria()->mergeWith(array(
                'join' => 'LEFT JOIN read_later ON id = object_id',
                'condition' => 'user_id = :user_id',
                'params' => array('user_id' => Yii::app()->user->id),
            ));
            return $this;
        }
    }

    public function get_read_later($post_type = NULL, $limit = 10, $offset = 1) {
        $offset = $limit * ($offset - 1);
        if ($post_type != NULL) {
            $this->getDbCriteria()->mergeWith(array(
                'join' => 'LEFT JOIN read_later ON (id = object_id) ',
                'condition' => 'user_id = :user_id AND post_type = :post_type',
                'params' => array('user_id' => Yii::app()->user->id, 'post_type' => $post_type),
                'order' => 'create_date DESC',
                'limit' => $limit,
                'offset' => $offset,
            ));
            return $this;
        } else {
            $this->getDbCriteria()->mergeWith(array(
                'join' => 'LEFT JOIN read_later ON id = object_id',
                'condition' => 'user_id = :user_id',
                'params' => array('user_id' => Yii::app()->user->id),
                'order' => 'create_date DESC',
                'limit' => $limit,
                'offset' => $offset,
            ));
            return $this;
        }
    }

    public function read_post($object_id) {

        $this->getDbCriteria()->mergeWith(array(
            'join' => 'LEFT JOIN read_later ON id = object_id',
            'condition' => "disp_flag = " . self::STATUS_SHOW . " AND object_id = :object_id AND user_id = :user_id ",
            'params' => array('user_id' => Yii::app()->user->id, 'object_id' => (int) $object_id),
        ));

        return $this;
    }

    /* ---------- // Read Later ---------------- */

    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->post_date = $this->post_modified = new CDbExpression('NOW()');
        } else {
            $this->post_modified = new CDbExpression('NOW()');
        }

        $this->post_author = Yii::app()->user->id;

        return TRUE;
    }

    public function change_status($id, $post_type) {

        $sql = " UPDATE post SET disp_flag = disp_flag XOR 1 WHERE post_type = '" . $post_type . "' AND id = '" . (int) $id . "'";
        Yii::app()->db->createCommand($sql)->execute();

        return Post::model()->findByPk((int) $id);
    }

    public function delete_post($id, $post_type, $post_author = '') {

        $result = 0;
        if ($post_author == '') {

            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();

            $sql1 = 'DELETE FROM term_relationships WHERE object_id = ' . (int) $id;
            $sql2 = "DELETE p, l FROM post p LEFT JOIN post_lang l ON p.id = l.post_id WHERE post_type = '$post_type' AND p.id = " . (int) $id;

            try {

                $connection->createCommand($sql1)->execute();
                $connection->createCommand($sql2)->execute();

                $transaction->commit();
                $result = 1;
            } catch (Exception $e) {
                $transaction->rollback();
                $result = 0;
            }
        } else {

            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();

            $sql1 = 'DELETE FROM term_relationships WHERE object_id = ' . (int) $id;
            $sql2 = "DELETE p, l FROM post p LEFT JOIN post_lang l ON p.id = l.post_id WHERE post_type = '$post_type' AND p.id = " . (int) $id . " AND post_author = " . $post_author;

            try {

                $connection->createCommand($sql1)->execute();
                $connection->createCommand($sql2)->execute();

                $transaction->commit();
                $result = 1;
            } catch (Exception $e) {
                $transaction->rollback();
                $result = 0;
            }
        }

        return $result;
    }

    /*
     * limit  as per_page
     * ofsset = per_page * (current_page - 1)
     */

    public function get_new_feed($limit, $current_page = 1) {

        $offset = $limit * ($current_page - 1);

        $criteria = new CDbCriteria(array(
            'order' => 'post_date DESC',
            'limit' => $limit,
            'offset' => $offset,
        ));

        $model = Post::model()->multilang()->findAll($criteria);

        return $model;
    }

    public function total_post_type() {

        $sql = "SELECT post_type, COUNT(*) AS total FROM post GROUP BY post_type";

        $connection = Yii::app()->db;
        $model = $connection->createCommand($sql)->queryAll();

        $sum = array();
        foreach ($model as $row) {
            $key = $row['post_type'];
            $sum[$key] = $row['total'];
        }

        return (object) $sum;
    }

}
