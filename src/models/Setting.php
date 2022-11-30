<?php

namespace app\models;

use app\components\BaseActiveRecord;
use Yii;
use yii\caching\TagDependency;

/**
 * This is the model class for table "{{%Setting}}".
 *
 * @property string $id
 * @property string $value
 * @property int $user_id
 */
class Setting extends BaseActiveRecord {

    protected static $settings = [];
    protected static $cache_id = 'setting';

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%setting}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['id', 'user_id'], 'required'],
            [['web_name', 'web_description', 'web_keyword'], 'required'],
            [['uid'], 'integer'],
            [['id'], 'string', 'max' => 70],
            [['value', 'web_header'], 'string', 'max' => 500],
            [['web_icp', 'weibo_client_id', 'weibo_client_secret', 'google_client_id', 'google_client_secret', 'facebook_client_id', 'facebook_client_secret'], 'string', 'max' => 100],
            [['id', 'user_id'], 'unique', 'targetAttribute' => ['id', 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'value' => Yii::t('app', 'Value'),
            'user_id' => Yii::t('app', 'User Id'),
            'web_name' => Yii::t('app', 'Site name'),
            'web_description' => Yii::t('app', 'Site description'),
            'web_keyword' => Yii::t('app', 'Site keywords'),
            'web_title' => Yii::t('app', 'Site title'),
            'web_header' => Yii::t('app', 'Site header'),
            'web_icp' => Yii::t('app', 'Site ICP'),
            'enable_css_compress' => Yii::t('app', 'Enable CSS compression'),
            'enable_js_compress' => Yii::t('app', 'Enable JS compression'),
            'enable_html_compress' => Yii::t('app', 'Enable HTML compression'),
            'statistics_code' => Yii::t('app', 'Statistical code'),
            'ad_one' => Yii::t('app', 'Advertising one'),
            'ad_two' => Yii::t('app', 'Advertising two'),
            'ad_three' => Yii::t('app', 'Advertising three'),
            'ad_four' => Yii::t('app', 'Advertising four'),
            'ad_five' => Yii::t('app', 'Advertising five'),
        ];
    }

    public function __get($name) {
        return self::get($name);
    }

    protected static function access($uid = 0) {
        $dependency = new TagDependency(['tags' => self::$cache_id]);

        $settings = static::find()->cache(0, $dependency)->where(['user_id' => $uid])->asArray()->all();
        $data = [];
        foreach ($settings as $setting) {
            $data[$setting['id']] = $setting['value'];
        }
        return $data;
    }

    public static function get($name, $default = NULL) {
        if (empty(self::$settings)) {
            self::$settings = self::access();
        }

        if (isset(self::$settings[$name]) && !empty(self::$settings[$name])) {
            return self::$settings[$name];
        }
        return $default;
    }


    public function afterSave($insert, $changedAttributes) {
        TagDependency::invalidate(Yii::$app->cache, self::$cache_id);
        parent::afterSave($insert, $changedAttributes);
    }

}
