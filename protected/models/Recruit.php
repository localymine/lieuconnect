<?php

/**
 * This is the model class for table "recruit".
 *
 * The followings are the available columns in table 'recruit':
 * @property string $id
 * @property string $user_id
 * @property string $post_id
 * @property string $post_type
 * @property string $first_name
 * @property string $last_name
 * @property string $address
 * @property string $country_id
 * @property integer $state_id
 * @property integer $city_id
 * @property string $zipcode
 * @property string $email
 * @property string $phone
 * @property integer $gender
 * @property string $birth_date
 * @property integer $grade_year
 * @property string $school_name
 * @property string $gpa
 * @property string $feeling_id
 * @property integer $status
 * @property string $create_date
 */
class Recruit extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'recruit';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('state_id, city_id, gender, grade_year, status', 'numerical', 'integerOnly' => true),
            array('user_id, post_id', 'length', 'max' => 20),
            array('post_type, first_name, last_name, email, school_name', 'length', 'max' => 256),
            array('address', 'length', 'max' => 512),
            array('country_id', 'length', 'max' => 3),
            array('zipcode, phone', 'length', 'max' => 16),
            array('gpa, feeling_id', 'length', 'max' => 10),
            array('birth_date, create_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, post_id, post_type, first_name, last_name, address, country_id, state_id, city_id, zipcode, email, phone, gender, birth_date, grade_year, school_name, gpa, feeling_id, status, create_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'post' => array(self::BELONGS_TO, 'Post', 'post_id'),
            'user' => array(self::BELONGS_TO, 'AUsers', 'user_id'),
            'country' => array(self::BELONGS_TO, 'Countries', 'country_id'),
            'state' => array(self::BELONGS_TO, 'States', 'state_id'),
            'city' => array(self::BELONGS_TO, 'Cities', 'city_id'),
            'feeling' => array(self::BELONGS_TO, 'Feeling', 'feeling_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'post_id' => 'Post',
            'post_type' => 'Post Type',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'address' => 'Address',
            'country_id' => 'Country',
            'state_id' => 'State',
            'city_id' => 'City',
            'zipcode' => 'Zipcode',
            'email' => 'Email',
            'phone' => 'Phone',
            'gender' => 'Gender',
            'birth_date' => 'Birth Date',
            'grade_year' => 'Grade Year',
            'school_name' => 'School Name',
            'gpa' => 'Gpa',
            'feeling_id' => 'Feeling',
            'status' => 'Status',
            'create_date' => 'Create Date',
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
        $criteria->compare('user_id', $this->user_id, true);
        $criteria->compare('post_id', $this->post_id, true);
        $criteria->compare('post_type', $this->post_type, true);
        $criteria->compare('first_name', $this->first_name, true);
        $criteria->compare('last_name', $this->last_name, true);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('country_id', $this->country_id, true);
        $criteria->compare('state_id', $this->state_id);
        $criteria->compare('city_id', $this->city_id);
        $criteria->compare('zipcode', $this->zipcode, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('gender', $this->gender);
        $criteria->compare('birth_date', $this->birth_date, true);
        $criteria->compare('grade_year', $this->grade_year);
        $criteria->compare('school_name', $this->school_name, true);
        $criteria->compare('gpa', $this->gpa, true);
        $criteria->compare('feeling_id', $this->feeling_id, true);
        $criteria->compare('status', $this->status);
        $criteria->compare('create_date', $this->create_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Recruit the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->create_date = new CDbExpression('NOW()');
        }
        
        $this->user_id = Yii::app()->user->id;

        return TRUE;
    }
    
    public static function item_alias($type, $code = NULL) {
        $_items = array(
            // name
            'post_type' => array(
                'scholarship' => Common::t('Scholarship'),
                'internship' => Common::t('Internship'),
                'college' => Common::t('College'),
            ),
        );

        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items) ? $_items[$type] : false;
    }

    public function count_apply_in_day() {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => "user_id = :user_id AND DATE_FORMAT(create_date, '%Y-%m-%d') = :current_date",
            'params' => array('user_id' => Yii::app()->user->id, 'current_date' => date('Y-m-d'))
        ));
        return $this;
    }
    
    public function status($id){
        $sql = " UPDATE recruit SET status = 1 WHERE id = '" . (int) $id . "'";
        Yii::app()->db->createCommand($sql)->execute();

        return Post::model()->findByPk((int) $id);
    }
}
