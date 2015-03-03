<?php

/**
 * This is the model class for table "cities".
 *
 * The followings are the available columns in table 'cities':
 * @property integer $cityID
 * @property string $cityName
 * @property integer $stateID
 * @property string $countryID
 * @property double $latitude
 * @property double $longitude
 */
class Cities extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'cities';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('cityName', 'required'),
            array('stateID', 'numerical', 'integerOnly' => true),
            array('latitude, longitude', 'numerical'),
            array('cityName', 'length', 'max' => 50),
            array('countryID', 'length', 'max' => 3),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('cityID, cityName, stateID, countryID, latitude, longitude', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'city' => array(self::BELONGS_TO, 'States', 'stateID'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'cityID' => 'City',
            'cityName' => 'City Name',
            'stateID' => 'State',
            'countryID' => 'Country',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
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

        $criteria->compare('cityID', $this->cityID);
        $criteria->compare('cityName', $this->cityName, true);
        $criteria->compare('stateID', $this->stateID);
        $criteria->compare('countryID', $this->countryID, true);
        $criteria->compare('latitude', $this->latitude);
        $criteria->compare('longitude', $this->longitude);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Cities the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function behaviors() {
        return array(
            'ml' => array(
                'class' => 'application.models.behaviors.MultilingualBehavior',
                'langClassName' => 'CitiesLang',
                'langTableName' => 'cities_lang',
                'langForeignKey' => 'cityID',
                'langField' => 'lang_id',
                'localizedAttributes' => array('cityName'), //attributes of the model to be translated
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
        );
    }

}
