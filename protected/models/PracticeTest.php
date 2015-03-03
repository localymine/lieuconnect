<?php

/**
 * This is the model class for table "practice_test".
 *
 * The followings are the available columns in table 'practice_test':
 * @property string $post_id
 * @property string $post_author
 * @property string $title
 * @property string $link
 * @property string $sort
 * @property string $create_date
 * @property integer $disp_flag
 */
class PracticeTest extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'practice_test';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('post_author, title, link', 'required'),
            array('disp_flag', 'numerical', 'integerOnly' => true),
            array('post_author', 'length', 'max' => 20),
            array('title, link', 'length', 'max' => 1024),
            array('sort, create_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('post_id, post_author, title, link, create_date, disp_flag', 'safe', 'on' => 'search'),
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
            'post_id' => 'Post',
            'post_author' => 'Post Author',
            'title' => 'Title',
            'link' => 'Link',
            'sort' => 'Sort',
            'create_date' => 'Create Date',
            'disp_flag' => '1: show; 0: hidden',
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

        $criteria->compare('post_id', $this->post_id, true);
        $criteria->compare('post_author', $this->post_author, true);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('link', $this->link, true);
        $criteria->compare('sort', $this->sort, true);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('disp_flag', $this->disp_flag);

        return new CActiveDataProvider($this, array(
            'criteria' => $this->ml->modifySearchCriteria($criteria),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return PracticeTest the static model class
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
                'langClassName' => 'PracticeTestLang',
                'langTableName' => 'practice_test_lang',
                'langForeignKey' => 'post_id',
                'langField' => 'lang_id',
                'localizedAttributes' => array('title', 'link'), //attributes of the model to be translated
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

    public function defaultScope() {
        return $this->ml->localizedCriteria();
    }
    
    public function scopes() {
        return array(
            'order_by_sort' => array(
                'order' => "sort",
            ),
        );
    }
    
    public function change_status($id) {

        $sql = " UPDATE practice_test SET disp_flag = disp_flag XOR 1 WHERE post_id = '" . (int) $id . "'";
        Yii::app()->db->createCommand($sql)->execute();
    }

    public function get_list($limit, $page) {
        
        $offset = $limit * ($page - 1);
        
        $this->getDbCriteria()->mergeWith(array(
            'condition' => "disp_flag = 1",
            'order' => 'sort ASC',
            'limit' => $limit,
            'offset' => $offset,
        ));
        return $this;
    }
}
