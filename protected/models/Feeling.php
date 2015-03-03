<?php

/**
 * This is the model class for table "feeling".
 *
 * The followings are the available columns in table 'feeling':
 * @property string $feeling_id
 * @property string $title
 * @property string $create_date
 */
class Feeling extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'feeling';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('title', 'required'),
            array('title', 'length', 'max' => 256),
            array('create_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('feeling_id, title, create_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'feeling_id' => 'Feeling',
            'title' => 'Title',
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

        $criteria->compare('feeling_id', $this->feeling_id, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('create_date', $this->create_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Feeling the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }
    
    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->create_date = new CDbExpression('NOW()');
        }
        
        return TRUE;
    }

    public function behaviors() {
        return array(
            'ml' => array(
                'class' => 'application.models.behaviors.MultilingualBehavior',
                'langClassName' => 'FeelingLang',
                'langTableName' => 'feeling_lang',
                'langForeignKey' => 'feeling_id',
                'langField' => 'lang_id',
                'localizedAttributes' => array('title'), //attributes of the model to be translated
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
