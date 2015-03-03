<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property string $activkey
 * @property string $create_at
 * @property string $lastvisit_at
 * @property integer $superuser
 * @property integer $status
 */
class AUsers extends CActiveRecord {

    const STATUS_NOACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_BANNED = -1;

    /**
     * @var string
     * @desc hash method (md5,sha1 or algo hash function http://www.php.net/manual/en/function.hash.php)
     */
    public $hash = 'md5';

    /**
     * @var boolean
     * @desc use email for activation user account
     */
    public $sendActivationMail = true;

    /**
     * @var boolean
     * @desc allow auth for is not active user
     */
    public $loginNotActiv = false;

    /**
     * @var boolean
     * @desc activate user on registration (only $sendActivationMail = false)
     */
    public $activeAfterRegister = false;

    /**
     * @var boolean
     */
    public $captcha = array('registration' => true);
    
    static private $_user;
    static private $_users = array();
    static private $_userByName = array();
    static private $_admin;
    static private $_admins;

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'users';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('username, email, superuser, status', 'required'),
            array('username', 'length', 'max' => 20, 'min' => 3, 'message' => Common::t("Incorrect username (length between 3 and 20 characters).", 'account')),
            array('username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u', 'message' => Common::t("Incorrect symbols (A-z0-9).", 'account')),
            array('username', 'unique', 'message' => Common::t("This user's name already exists.", 'account')),
            array('password', 'length', 'max' => 128, 'min' => 4, 'message' => Common::t("Incorrect password (minimal length 4 symbols).", 'account')),
            array('email', 'email'),
            array('email', 'unique', 'message' => Common::t("This user's email address already exists.", 'account')),
            array('status', 'in', 'range' => array(self::STATUS_NOACTIVE, self::STATUS_ACTIVE, self::STATUS_BANNED)),
            array('superuser', 'in', 'range' => array(0)),
            array('create_at', 'default', 'value' => date('Y-m-d H:i:s'), 'setOnEmpty' => true, 'on' => 'insert'),
            array('lastvisit_at', 'default', 'value' => '0000-00-00 00:00:00', 'setOnEmpty' => true, 'on' => 'insert'),
            array('superuser, status', 'numerical', 'integerOnly' => true),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id, username, password, email, activkey, create_at, lastvisit_at, superuser, status', 'safe', 'on' => 'search'),
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
            'profile' => array(self::HAS_ONE, 'AProfiles', 'user_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'email' => 'Email',
            'activkey' => 'Activkey',
            'create_at' => 'Create At',
            'lastvisit_at' => 'Lastvisit At',
            'superuser' => 'Superuser',
            'status' => 'Status',
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

        $criteria->compare('id', $this->id);
        $criteria->compare('username', $this->username, true);
        $criteria->compare('password', $this->password, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('activkey', $this->activkey, true);
        $criteria->compare('create_at', $this->create_at, true);
        $criteria->compare('lastvisit_at', $this->lastvisit_at, true);
        $criteria->compare('superuser', $this->superuser);
        $criteria->compare('status', $this->status);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public function getCreatetime() {
        return strtotime($this->create_at);
    }

    public function setCreatetime($value) {
        $this->create_at = date('Y-m-d H:i:s', $value);
    }

    public function getLastvisit() {
        return strtotime($this->lastvisit_at);
    }

    public function setLastvisit($value) {
        $this->lastvisit_at = date('Y-m-d H:i:s', $value);
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return AUsers the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function scopes() {
        return array(
            'active' => array(
                'condition' => 'status=' . self::STATUS_ACTIVE,
            ),
            'notactive' => array(
                'condition' => 'status=' . self::STATUS_NOACTIVE,
            ),
            'banned' => array(
                'condition' => 'status=' . self::STATUS_BANNED,
            ),
            'superuser' => array(
                'condition' => 'superuser=0', // normal user
            ),
            'curators' => array(
                'condition' => 'superuser=1 OR superuser=2', // normal user
            ),
            'notsafe' => array(
                'select' => 'id, username, password, email, activkey, create_at, lastvisit_at, superuser, status',
            ),
        );
    }

    public function defaultScope() {
        return array(
            'alias' => 'user',
            'select' => 'user.id, user.username, user.email, user.create_at, user.lastvisit_at, user.superuser, user.status',
        );
    }
    
    public function beforeSave() {
        
        $this->email = Common::clean_text($this->email);
        
        return TRUE;
    }

    public static function itemAlias($type, $code = NULL) {
        $_items = array(
            'UserStatus' => array(
                self::STATUS_NOACTIVE => Common::t('Not active'),
                self::STATUS_ACTIVE => Common::t('Active'),
                self::STATUS_BANNED => Common::t('Banned'),
            ),
            'AdminStatus' => array(
                '0' => Common::t('Normal User'),
                '1' => Common::t('Admin'),
                '2' => Common::t('Sub-Admin'),
            ),
            'Gender' => array(
                '-1' => Common::t('--'),
                '0' => Common::t('Female'),
                '1' => Common::t('Male'),
            ),
        );
        if (isset($code))
            return isset($_items[$type][$code]) ? $_items[$type][$code] : false;
        else
            return isset($_items[$type]) ? $_items[$type] : false;
    }

    /**
     * @return hash string.
     */
    public static function encrypting($string = "") {
        $hash = AUsers::model()->hash;
        if ($hash == "md5")
            return md5($string);
        if ($hash == "sha1")
            return sha1($string);
        else
            return hash($hash, $string);
    }

    public function existsEmail($email) {
        $email = strtolower(trim($email));

        $this->getDbCriteria()->mergeWith(array(
            'condition' => "email LIKE :email",
            'params' => array('email' => $email)
        ));

        return $this;
    }

    public function existsUsername($username) {
        $username = strtolower(trim($username));

        $this->getDbCriteria()->mergeWith(array(
            'condition' => "LOWER(username) LIKE :username",
            'params' => array('username' => $username)
        ));

        return $this;
    }

    /**
     * @param $place
     * @return boolean 
     */
    public static function doCaptcha($place = '') {
        if (!extension_loaded('gd'))
            return false;
        if (in_array($place, AUsers::model()->captcha))
            return AUsers::model()->captcha[$place];
        return false;
    }

    /**
     * Send mail method
     */
    public static function sendActiveMail($email, $holder) {

        $mail = new YiiMailer;

        $sender = Yii::app()->params['sender'];
        $mail->setFrom(Yii::app()->params['from'], $sender);
        $mail->setTo($email);
        $mail->setSubject(Common::t('Activation Mail', 'mail'));
        $mail->setView('activation');
        //
        $param = http_build_query(array(
            'email' => $holder['email'],
            'activkey' => $holder['activkey'],
        ));
        $data['active_link'] = Yii::app()->params['siteUrl'] . '/account/activation?' . $param;
        $mail->setData($data);
        $mail->send();
    }
    
    public static function sendRecoverMail($mailto, $holder){
        
        $mail = new YiiMailer;

        $sender = Yii::app()->params['sender'];
        $mail->setFrom(Yii::app()->params['from'], $sender);
        $mail->setTo($mailto);
        $mail->setSubject(Common::t('You have requested the password recovery site {site_name}', 'mail', array('{site_name}' => Yii::app()->name)));
        $mail->setView('recovery');
        //
        $param = http_build_query(array(
            'email' => $holder['email'],
            'activkey' => $holder['activkey'],
        ));
        $data['recover_link'] = Yii::app()->params['siteUrl'] . '/account/recover?' . $param;
        $data['site_name'] = Yii::app()->name;
        $mail->setData($data);
        $mail->send();
    }

    /**
     * Return safe user data.
     * @param user id not required
     * @return user object or false
     */
    public static function user($id = 0, $clearCache = false) {
        if (!$id && !Yii::app()->user->isGuest)
            $id = Yii::app()->user->id;
        if ($id) {
            if (!isset(self::$_users[$id]) || $clearCache)
                self::$_users[$id] = AUsers::model()->with(array('profile'))->findbyPk($id);
            return self::$_users[$id];
        } else
            return false;
    }

    /**
     * Return safe user data.
     * @param user name
     * @return user object or false
     */
    public static function getUserByName($username) {
        if (!isset(self::$_userByName[$username])) {
            $_userByName[$username] = AUsers::model()->findByAttributes(array('username' => $username));
        }
        return $_userByName[$username];
    }

    public function get_curators($limit = 3, $current_page = 1) {
        $offset = $limit * ($current_page - 1);

        $this->getDbCriteria()->mergeWith(array(
            'condition' => "status= :status AND superuser = 1 OR superuser = 2 ",
            'limit' => $limit,
            'offset' => $offset,
            'params' => array('status' => self::STATUS_ACTIVE),
        ));

        return $this;
    }

}
