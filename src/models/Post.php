<?php

namespace app\models;

use app\components\BaseActiveRecord;
use app\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "{{%post}}".
 *
 * @property int $id
 * @property int $type
 * @property int $parent_id
 * @property int $meta_id
 * @property string $title
 * @property string $content
 * @property string $alias
 * @property string $thumbnail
 * @property int $view_count
 * @property int $update_time
 *
 * @property $category
 */
class Post extends BaseActiveRecord {

    public $tags;

    const TYPE_PAGE = 2;
    const TYPE_NEWS = 1;
    const TYPE_PRODUCT = 3;


    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%post}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['meta_id', 'parent_id', 'type', 'view_count', 'update_time'], 'integer'],
            [['content', 'title'], 'required'],
            [['content', 'alias'], 'string'],
            [['title', 'thumbnail'], 'string', 'max' => 180],
            [['alias'], 'unique', 'skipOnEmpty' => TRUE],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'meta_id' => Yii::t('app', 'Meta Id'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'type' => Yii::t('app', 'Type'),
            'title' => Yii::t('app', 'Title'),
            'content' => Yii::t('app', 'Content'),
            'thumbnail' => Yii::t('app', 'Thumbnail'),
            'view_count' => Yii::t('app', 'View Count'),
            'alias' => Yii::t('app', 'Alias'),
            'update_time' => Yii::t('app', 'Update Time'),
        ];
    }

    public function getCategory(){
        return $this->hasOne(Meta::class, ['id' => 'meta_id']);
    }

    public function getPostCategory($type) {
        $data = [];
        $items = Meta::category($type);
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
}
