<?php

/**
 * This is the model class for table "countries".
 *
 * The followings are the available columns in table 'countries':
 * @property string $countryID
 * @property string $countryName
 * @property string $localName
 * @property string $webCode
 * @property string $region
 * @property string $continent
 * @property double $latitude
 * @property double $longitude
 * @property double $surfaceArea
 * @property integer $population
 */
class Countries extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'countries';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('localName, webCode, region, continent', 'required'),
            array('population', 'numerical', 'integerOnly' => true),
            array('latitude, longitude, surfaceArea', 'numerical'),
            array('countryID', 'length', 'max' => 3),
            array('countryName', 'length', 'max' => 52),
            array('localName', 'length', 'max' => 45),
            array('webCode', 'length', 'max' => 2),
            array('region', 'length', 'max' => 26),
            array('continent', 'length', 'max' => 13),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('countryID, countryName, localName, webCode, region, continent, latitude, longitude, surfaceArea, population', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'country' => array(self::HAS_MANY, 'AProfiles', 'countryID'),
            'state' => array(self::HAS_MANY, 'States', 'countryID'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'countryID' => 'Country',
            'countryName' => 'Country Name',
            'localName' => 'Local Name',
            'webCode' => 'Web Code',
            'region' => 'Region',
            'continent' => 'Continent',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'surfaceArea' => 'Surface Area',
            'population' => 'Population',
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

        $criteria->compare('countryID', $this->countryID, true);
        $criteria->compare('countryName', $this->countryName, true);
        $criteria->compare('localName', $this->localName, true);
        $criteria->compare('webCode', $this->webCode, true);
        $criteria->compare('region', $this->region, true);
        $criteria->compare('continent', $this->continent, true);
        $criteria->compare('latitude', $this->latitude);
        $criteria->compare('longitude', $this->longitude);
        $criteria->compare('surfaceArea', $this->surfaceArea);
        $criteria->compare('population', $this->population);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Countries the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
