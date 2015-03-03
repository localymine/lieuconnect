<?php

/**
 * This is the model class for table "profiles".
 *
 * The followings are the available columns in table 'profiles':
 * @property integer $user_id
 * @property string $lastname
 * @property string $firstname
 * @property string $image
 * @property string $phone
 * @property string $birth_date
 * @property integer $gender
 * @property string $address
 * @property string $country
 * @property integer $state
 * @property integer $city
 * @property string $zipcode
 * @property integer $grade_year
 * @property double $gpa
 * @property string $expectation
 * @property string $blast
 */
class AProfiles extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'profiles';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('gender, state, city, grade_year', 'numerical', 'integerOnly' => true),
            array('gpa', 'numerical'),
            array('lastname, firstname', 'length', 'max' => 50),
            array('image', 'length', 'max' => 255),
            array('phone', 'length', 'max' => 15),
            array('address', 'length', 'max' => 256),
            array('country', 'length', 'max' => 3),
            array('zipcode', 'length', 'max' => 10),
            array('birth_date, image', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('user_id, lastname, firstname, image, phone, birth_date, gender, address, country, state, city, zipcode, grade_year, gpa, expectation, blast', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'posts' => array(self::HAS_MANY, 'Post', 'user_id'),
            'user' => array(self::BELONGS_TO, 'AUsers', 'user_id'),
            'country_ref' => array(self::BELONGS_TO, 'Countries', 'country'),
            'state_ref' => array(self::BELONGS_TO, 'States', 'state'),
            'city_ref' => array(self::BELONGS_TO, 'Cities', 'city'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'user_id' => 'User',
            'lastname' => 'Lastname',
            'firstname' => 'Firstname',
            'image' => 'Image',
            'phone' => 'Phone',
            'birth_date' => 'Birth Date',
            'gender' => '0: female; 1: male',
            'address' => 'Address',
            'country' => 'Country',
            'state' => 'State',
            'city' => 'City',
            'zipcode' => 'Zipcode',
            'grade_year' => 'Grade Year',
            'gpa' => 'Gpa',
            'expectation' => 'Expectation',
            'blast' => 'Blast',
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

        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('lastname', $this->lastname, true);
        $criteria->compare('firstname', $this->firstname, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('birth_date', $this->birth_date, true);
        $criteria->compare('gender', $this->gender);
        $criteria->compare('address', $this->address, true);
        $criteria->compare('country', $this->country, true);
        $criteria->compare('state', $this->state);
        $criteria->compare('city', $this->city);
        $criteria->compare('zipcode', $this->zipcode, true);
        $criteria->compare('grade_year', $this->grade_year);
        $criteria->compare('gpa', $this->gpa);
        $criteria->compare('expectation', $this->expectation, true);
        $criteria->compare('blast', $this->blast, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AProfiles the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function beforeSave() {
        
        $this->firstname = Common::clean_text($this->firstname);
        $this->lastname = Common::clean_text($this->lastname);
        $this->phone = Common::clean_text($this->phone);
        $this->address = Common::clean_text($this->address);
        $this->zipcode = Common::clean_text($this->zipcode);
        $this->gpa = Common::clean_text($this->gpa);
        $this->expectation = Common::clean_text($this->expectation);
        
        return TRUE;
    }
    
    public function public_resume() {

        $sql = " UPDATE profiles SET public_resume = public_resume XOR 1 WHERE user_id = " . Yii::app()->user->id;
        Yii::app()->db->createCommand($sql)->execute();

    }
    
    public function public_profile() {

        $sql = " UPDATE profiles SET public_profile = public_profile XOR 1 WHERE user_id = " . Yii::app()->user->id;
        Yii::app()->db->createCommand($sql)->execute();

    }

}
