<?php

/**
 * This is the model class for table "slide".
 *
 * The followings are the available columns in table 'slide':
 * @property string $id
 * @property string $post_author
 * @property string $title
 * @property string $description
 * @property string $image
 * @property string $create_date
 * @property string $sorting
 */
class Slide extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'slide';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('image', 'file', 'types' => 'jpg, gif, png', 'maxSize' => 1024 * 1024 * 10, 'allowEmpty' => true, 'on' => 'insert'), // 10MB
            array('image', 'file', 'types' => 'jpg, gif, png', 'maxSize' => 1024 * 1024 * 10, 'allowEmpty' => true, 'on' => 'update'),
            array('title, image', 'length', 'max' => 256),
            array('sorting', 'length', 'max' => 10),
            array('post_author, description, create_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, post_author, title, description, image, create_date, sorting', 'safe', 'on' => 'search'),
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
            'post_author' => 'Author',
            'title' => 'Title',
            'description' => 'Description',
            'image' => 'Image',
            'create_date' => 'Create Date',
            'sorting' => 'Sorting',
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
        $criteria->compare('title', $this->title, true);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('image', $this->image, true);
        $criteria->compare('create_date', $this->create_date, true);
        $criteria->compare('sorting', $this->sorting, true);

        return new CActiveDataProvider($this, array(
//            'criteria' => $criteria,
            'criteria' => $this->ml->modifySearchCriteria($criteria),
        ));
    }

    public function behaviors() {
        return array(
            'ml' => array(
                'class' => 'application.models.behaviors.MultilingualBehavior',
                'langClassName' => 'SlideLang',
                'langTableName' => 'slide_lang',
                'langForeignKey' => 'slide_id',
                'langField' => 'lang_id',
                'localizedAttributes' => array('title', 'description', 'image'), //attributes of the model to be translated
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

    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->create_date = new CDbExpression('NOW()');
        } else {
            $this->create_date = new CDbExpression('NOW()');
        }

        $this->post_author = Yii::app()->user->id;

        return TRUE;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Slide the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function get_slide($limit = 5, $current_page = 1) {
        $offset = $limit * ($current_page - 1);

        $this->getDbCriteria()->mergeWith(array(
            'order' => 'sorting ASC, create_date DESC',
            'limit' => $limit,
            'offset' => $offset,
        ));

        return $this;
    }

    public function get_max() {

        $sql = "SELECT MAX(sorting) AS `max` FROM slide";
        $connection = Yii::app()->db;
        $query = $connection->createCommand($sql)->queryRow();

        return $query;
    }

    public function update_sort($ids = NULL) {

        $result = 0;
        if ($ids != NULL) {

            $connection = Yii::app()->db;
            $transaction = $connection->beginTransaction();


            try {
                $count = 0;
                foreach ($ids as $id) {
                    $sql = "UPDATE slide SET sorting = $count  WHERE id= " . (int) $id;
                    $connection->createCommand($sql)->execute();
                    $count++;
                }

                $transaction->commit();
                $result = 1;
            } catch (Exception $e) {
                $transaction->rollback();
                $result = 0;
            }
        }
        return $result;
    }

}