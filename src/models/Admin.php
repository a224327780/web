<?php

namespace app\models;

use app\components\BaseActiveRecord;
use app\helpers\AppHelper;
use app\helpers\EncryptHelper;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yii\web\UserEvent;

/**
 * This is the model class for table "{{%admin}}".
 *
 * @property int $id
 * @property int $gid
 * @property string $username
 * @property string $nickname
 * @property string $password
 * @property string $avatar
 * @property int $last_login_time
 * @property int $last_login_ip
 * @property int $status
 * @property int $create_time
 */
class Admin extends BaseActiveRecord implements IdentityInterface {

    const GROUP_ADMIN = 1;

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 2;

    const rememberTime = 259200;

    public $rememberMe = TRUE;

    public $newPwd;
    public $newPwd2;
    public $oldPwd;

    public $password2;

    public $statusLabels = [];

    public static $groupLabels = [
        self::GROUP_ADMIN => '管理员组',
    ];

    public function init() {
        parent::init();
        $this->newPwd = $this->password2 = '';
        $this->status = static::YES;
        Yii::$app->user->on(\yii\web\User::EVENT_AFTER_LOGIN, [$this, 'afterLogin']);

        $this->statusLabels = [
            self::STATUS_DISABLED => Yii::t('app', 'Disabled'),
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%admin}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['gid', 'last_login_time', 'last_login_ip', 'status', 'create_time'], 'integer'],
            [['username', 'nickname', 'avatar'], 'string', 'max' => 120],
            [['password'], 'string', 'max' => 16],

            [['username', 'password'], 'required', 'on' => 'login'],
            [['username', 'password', 'password2', 'gid'], 'required', 'on' => 'register'],

            [['newPwd', 'newPwd2'], 'required', 'on' => 'pwd'],
            [['newPwd'], 'compare', 'compareAttribute' => 'newPwd2', 'on' => 'pwd'],
            [['username'], 'unique', 'on' => 'register']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        $attributeLabels = [
            'id' => Yii::t('app', 'ID'),
            'gid' => Yii::t('app', 'Gid'),
            'username' => Yii::t('app', 'Username'),
            'nickname' => Yii::t('app', 'Nickname'),
            'password' => Yii::t('app', 'Password'),
            'avatar' => Yii::t('app', 'Avatar'),
            'last_login_time' => Yii::t('app', 'Last Login Time'),
            'last_login_ip' => Yii::t('app', 'Last Login Ip'),
            'status' => Yii::t('app', 'Status'),
            'create_time' => Yii::t('app', 'Create Time'),
        ];
        $attributeLabels['rememberMe'] = Yii::t('app', 'Remember Me');
        $attributeLabels['newPwd2'] = Yii::t('app', 'Confirm New Password');
        $attributeLabels['password2'] = Yii::t('app', 'Confirm Password');
        $attributeLabels['newPwd'] = Yii::t('app', 'New Password');
        $attributeLabels['oldPwd'] = Yii::t('app', 'Original Password');
        return $attributeLabels;
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id) {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null) {
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId() {
        return $this->id;
    }

    public function getAuthKey() {
        return EncryptHelper::md5(APP_KEY);
    }

    public function getGroupName() {
        return ArrayHelper::getValue(static::$groupLabels, $this->gid);
    }

    public function getStatusName() {
        return ArrayHelper::getValue($this->statusLabels, $this->status);
    }

    public function validateAuthKey($authKey) {
        return EncryptHelper::md5(APP_KEY) === $authKey;
    }

    /**
     * @return bool
     */
    public function isActive() {
        return intval($this->status) === self::YES;
    }

    public function isDisable() {
        return intval($this->status) === self::NO;
    }

    public function isAdmin() {
        return intval($this->gid) === self::GROUP_ADMIN;
    }

    public function login() {
        if (!$this->validate()) {
            return static::retErr($this->getError());
        }

        $user = self::findOne(['username' => $this->username]);
        if (!$user) {
            return self::retErr(Yii::t('app', 'Username or password is incorrect.'));
        }
        $_pass = EncryptHelper::md5($this->password);
        if ($_pass !== $user['password']) {
            return self::retErr(Yii::t('app', 'Username or password is incorrect.'), 1, [$_pass, $user->password, APP_KEY]);
        }

        if (!$user->isActive()) {
            return self::retErr(Yii::t('app', 'Account is disabled.'));
        }

        $this->id = $user->id;

        Yii::$app->user->login($user, $this->rememberMe ? self::rememberTime : 0);

        $res = static::retOK();
        $res['redirect'] = Yii::$app->user->getReturnUrl(['/admin']);
        return $res;
    }

    /**
     * @param UserEvent $event
     * @throws Exception
     */
    public function afterLogin(UserEvent $event) {
        /* @var $user Admin */
        $user = $event->identity;
        if (!$user->getIsNewRecord()) {
            $user->last_login_ip = AppHelper::getClientIp();
            $user->last_login_time = time();
            $user->save();
        }
    }
}
