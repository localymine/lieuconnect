<?php

/**
 * This is the model class for table "resume_exper".
 *
 * The followings are the available columns in table 'resume_exper':
 * @property string $id
 * @property integer $user_id
 * @property integer $resume_id
 * @property string $employer
 * @property string $position
 * @property integer $industry_id
 * @property string $description
 * @property string $start
 * @property string $end
 * @property integer $uptonow
 * @property string $create_date
 */
class ResumeExper extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'resume_exper';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, resume_id, employer, industry_id', 'required'),
            array('user_id, resume_id, industry_id, uptonow', 'numerical', 'integerOnly' => true),
            array('employer, position', 'length', 'max' => 512),
            array('description', 'length', 'max' => 1024),
            array('start, end, create_date', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, user_id, resume_id, employer, position, industry_id, description, start, end, uptonow, create_date', 'safe', 'on' => 'search'),
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
            'industry' => array(self::BELONGS_TO, 'Industry', 'industry_id'),
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
            'employer' => 'Employer',
            'position' => 'Position',
            'industry_id' => 'Industry',
            'description' => 'Description',
            'start' => 'Start',
            'end' => 'End',
            'uptonow' => '0:use end date; 1: up to now',
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
        $criteria->compare('employer', $this->employer, true);
        $criteria->compare('position', $this->position, true);
        $criteria->compare('industry_id', $this->industry_id);
        $criteria->compare('description', $this->description, true);
        $criteria->compare('start', $this->start, true);
        $criteria->compare('end', $this->end, true);
        $criteria->compare('uptonow', $this->uptonow);
        $criteria->compare('create_date', $this->create_date, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ResumeExper the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function beforeSave() {
        
        $this->employer = Common::clean_text($this->employer);
        $this->position = Common::clean_text($this->position);
        $this->description = Common::clean_text($this->description);
        
        if ($this->isNewRecord) {
            $this->create_date = new CDbExpression('NOW()');
        }

        return TRUE;
    }
    
    public function delete_item($id, $resume_id) {
        $connection = Yii::app()->db;
        $transaction = $connection->beginTransaction();

        $sql = 'DELETE FROM resume_exper WHERE id = ' . (int) $id . ' AND resume_id = ' . (int) $resume_id . ' AND user_id = ' . Yii::app()->user->id;

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
