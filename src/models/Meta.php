<?php

namespace app\models;

use app\components\BaseActiveRecord;
use app\helpers\ArrayHelper;
use Yii;
use yii\caching\TagDependency;
use yii\db\Exception;

/**
 * This is the model class for table "{{%meta}}".
 *
 * @property int $id
 * @property int $parent_id
 * @property int $type
 * @property string $name
 * @property string $description
 * @property string $slug
 * @property int $sort
 * @property int $count
 * @property int $is_hide
 */
class Meta extends BaseActiveRecord {

    const TYPE_NEWS = 1;
    const TYPE_PRODUCT = 2;
    const TYPE_LINK = 3;
    const TYPE_BANNER = 4;

    const CACHE_NAME = 'meta_cache';

    public static $typeLabels = [];

    public function init() {
        parent::init();
        self::$typeLabels = [
            self::TYPE_NEWS => Yii::t('app', 'News'),
            self::TYPE_PRODUCT => Yii::t('app', 'Product')
        ];
    }


    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%meta}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['name'], 'required'],
            [['parent_id', 'type', 'sort', 'count', 'is_hide'], 'integer'],
            [['name'], 'string', 'max' => 160],
            [['description'], 'string', 'max' => 200],
            [['slug'], 'string', 'max' => 100],
            [['slug'], 'required', 'on' => 'link'],
            [['slug'], 'unique', 'skipOnEmpty' => TRUE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        $attributeLabels = [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'type' => Yii::t('app', 'Type'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
            'slug' => Yii::t('app', 'Slug'),
            'sort' => Yii::t('app', 'Sort'),
            'count' => Yii::t('app', 'Count'),
            'is_hide' => Yii::t('app', 'Is Hide'),
        ];
        if (Yii::$app->controller->id == 'link') {
            $attributeLabels['slug'] = Yii::t('app', 'URL');
        }
        return $attributeLabels;
    }

    public function getTypeName() {
        return yii\helpers\ArrayHelper::getValue(self::$typeLabels, $this->type);
    }

    public function getSaveCategory() {
        $data = [];
        $items = Meta::category(null);
        ArrayHelper::arrayDepthSort($items, $lists);

        if (!$this->isNewRecord) {
            $lists = ArrayHelper::removeChild($lists, $this->id);
        }

        if (empty($lists)) {
            return $data;
        }

        foreach ($lists as $list) {
            $data[$list['id']] = $list['_title'];
        }
        return $data;
    }

    public static function category($type) {
        return self::find()->filterWhere(['type' => $type, 'is_hide' => Meta::NO])->asArray()->all();
    }

    public function refreshCache() {
        TagDependency::invalidate(Yii::$app->cache, self::CACHE_NAME);
        parent::refreshCache();
    }

    public static function getMetaDataByType($type) {
        $dependency = new TagDependency(['tags' => self::CACHE_NAME]);
        $data = self::find()->cache(0, $dependency)->where(['is_hide' => self::NO])->asArray()->orderBy('id desc')->all();
        $data = \yii\helpers\ArrayHelper::index($data, 'id', 'type');
        return \yii\helpers\ArrayHelper::getValue($data, $type, []);
    }

    public static function getMenuItems($type) {
        $data = self::getMetaDataByType($type);
        $items = [];
        foreach ($data as $datum) {
            if ($datum['parent_id'] <= 0 && in_array($datum['type'], [self::TYPE_NEWS, self::TYPE_PRODUCT])) {
                continue;
            }
            $url = $datum['slug'];
            if (intval($datum['type']) === self::TYPE_NEWS) {
                $url = ['/news', 'meta_id' => $datum['id']];
            }
            if (intval($datum['type']) === self::TYPE_PRODUCT) {
                $url = ['/product', 'meta_id' => $datum['id']];
            }
            $items[] = ['label' => $datum['name'], 'url' => $url];
        }
        return $items;
    }
}
