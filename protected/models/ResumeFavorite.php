<?php

/**
 * This is the model class for table "resume_favorite".
 *
 * The followings are the available columns in table 'resume_favorite':
 * @property string $id
 * @property integer $user_id
 * @property integer $resume_id
 * @property string $music
 * @property string $tvshow
 * @property string $movie
 * @property string $quote
 * @property string $book
 * @property string $website
 * @property string $create_date
 */
class ResumeFavorite extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'resume_favorite';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, resume_id', 'required'),
            array('user_id, resume_id', 'numerical', 'integerOnly' => true),
            array('music, tvshow, movie, quote, book, website, create_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, resume_id, music, tvshow, movie, quote, book, website, create_date', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'resume' => array(self::BELONGS_TO, 'Resume', 'id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'user_id' => 'User',
            'resume_id' => 'Resume',
            'music' => 'Music',
            'tvshow' => 'Tvshow',
            'movie' => 'Movie',
            'quote' => 'Quote',
            'book' => 'Book',
            'website' => 'Website',
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
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('resume_id', $this->resume_id);
        $criteria->compare('music', $this->music, true);
        $criteria->compare('tvshow', $this->tvshow, true);
        $criteria->compare('movie', $this->movie, true);
        $criteria->compare('quote', $this->quote, true);
        $criteria->compare('book', $this->book, true);
        $criteria->compare('website', $this->website, true);
        $criteria->compare('create_date', $this->create_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ResumeFavorite the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function beforeSave() {
        
        $this->music = Common::clean_text($this->music);
        $this->tvshow = Common::clean_text($this->tvshow);
        $this->movie = Common::clean_text($this->movie);
        $this->quote = Common::clean_text($this->quote);
        $this->book = Common::clean_text($this->book);
        $this->website = Common::clean_text($this->website);
        
        if ($this->isNewRecord) {
            $this->create_date = new CDbExpression('NOW()');
        }

        return TRUE;
    }

    public function delete_item($id, $resume_id) {
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        $sql = 'DELETE FROM resume_favorite WHERE id = ' . (int) $id . ' AND resume_id = ' . (int) $resume_id . ' AND user_id = ' . Yii::app()->user->id;

        try {

            $connection->createCommand($sql)->execute();

            $transaction->commit();
            $result = 1;
        } catch (Exception $e) {
            $transaction->rollback();
            $result = 0;
        }

        return $result;
    }
}
