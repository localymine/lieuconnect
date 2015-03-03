<?php

/**
 * This is the model class for table "page".
 *
 * The followings are the available columns in table 'page':
 * @property string $id
 * @property string $post_author
 * @property string $post_type
 * @property string $post_title
 * @property string $post_content
 * @property integer $disp_flag
 * @property string $create_date
 * @property string $post_modified
 */
class Page extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'page';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('post_author, post_type, post_title, post_content', 'required'),
            array('disp_flag', 'numerical', 'integerOnly' => true),
            array('post_type', 'length', 'max' => 45),
            array('post_author, post_modified, create_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, post_author, post_type, post_title, post_content, disp_flag, create_date', 'safe', 'on' => 'search'),
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
            'id' => 'ID',
            'post_author' => 'Post Author',
            'post_type' => 'contact, privacy, terms',
            'post_title' => 'Post Title',
            'post_content' => 'Post Content',
            'disp_flag' => '1: show; 0: hidden',
            'create_date' => 'Create Date',
            'post_modified' => 'Post Modified',
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
        $criteria->compare('post_title', $this->post_title, true);
        $criteria->compare('post_content', $this->post_content, true);
        $criteria->compare('disp_flag', $this->disp_flag);
        $criteria->compare('create_date', $this->create_date, true);

        return new CActiveDataProvider($this, array(
//            'criteria' => $criteria,
            'criteria' => $this->ml->modifySearchCriteria($criteria),
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Page the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->create_date = $this->post_modified = new CDbExpression('NOW()');
        } else {
            $this->post_modified = new CDbExpression('NOW()');
        }

        $this->post_author = Yii::app()->user->id;

        return TRUE;
    }

    public function defaultScope() {
        return $this->ml->localizedCriteria();
    }

    public function scopes() {
        return array(
            'contact' => array(
                'condition' => "disp_flag = 1 AND post_type = 'contact' ",
                'limit' => 1,
            ),
            'about' => array(
                'condition' => "disp_flag = 1 AND post_type = 'about' ",
                'limit' => 1,
            ),
            'privacy' => array(
                'condition' => "disp_flag = 1 AND post_type = 'privacy' ",
                'limit' => 1,
            ),
            'terms' => array(
                'condition' => "disp_flag = 1 AND post_type = 'terms' ",
                'limit' => 1,
            ),
            'sort_by_date' => array(
                'order' => "create_date DESC",
            )
        );
    }

    public function behaviors() {
        return array(
            'ml' => array(
                'class' => 'application.models.behaviors.MultilingualBehavior',
                'langClassName' => 'PageLang',
                'langTableName' => 'page_lang',
                'langForeignKey' => 'post_id',
                'langField' => 'lang_id',
                'localizedAttributes' => array('post_title', 'post_content'), //attributes of the model to be translated
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

    public static function item_alias($type, $code = NULL) {
        $_items = array(
            // name
            'pages' => array(
                'contact' => 'Contact Page', 'about' => 'About', 'privacy' => 'Privacy Page', 'terms' => 'Terms Page'
            ),
        );

        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items) ? $_items[$type] : false;
    }

    public function change_status($id) {

        $sql = " UPDATE page SET disp_flag = disp_flag XOR 1 WHERE id = '" . (int) $id . "'";
        Yii::app()->db->createCommand($sql)->execute();

        return Page::model()->findByPk((int) $id);
    }

    public function get_faq_by_term_id($id) {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => "disp_flag = 1 AND post_type LIKE 'faq' AND term_taxonomy_id = " . (int) $id,
            'join' => "LEFT JOIN term_faq_relationships ON id = object_id",
            'order' => "term_taxonomy_id, post_id"
        ));
        return $this;
    }

}
