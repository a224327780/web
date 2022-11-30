<?php

namespace app\models;

use app\components\BaseActiveRecord;
use app\helpers\AppHelper;
use app\helpers\BookHelper;
use Yii;
use yii\caching\TagDependency;
use yii\db\Exception;

/**
 * This is the model class for table "{{%book}}".
 *
 * @property int $id
 * @property int $author_id 作者
 * @property string $title 标题
 * @property string $thumbnail 封面图
 * @property string $description 简介
 * @property string $letter 字母
 * @property int $view_count 人气
 * @property int $favorite_count 收藏数
 * @property int $comment_count 评论数
 * @property string $score 评分
 * @property int $country 地区
 * @property int $up_count 赞
 * @property int $down_count 踩
 * @property int $is_vip
 * @property int $price
 * @property int $is_finish 进度
 * @property int $is_publish 是否发布
 * @property string $last_item 更新至
 * @property int $update_time 更新时间
 * @property int $create_time 发布时间
 *
 * @property $tags
 * @property $author
 * @property $items
 * @property $relationships
 * @property $lastItem
 */
class Book extends BaseActiveRecord {

    const FINISH_YES = 2;
    const FINISH_NO = 1;

    const COUNTRY_CN = 1;
    const COUNTRY_JP = 2;
    const COUNTRY_EN = 3;
    const COUNTRY_KR = 4;
    const COUNTRY_HK = 5;

    public static $finishLabels = [
        self::FINISH_NO => '连载',
        self::FINISH_YES => '完结',
    ];

    public static $countryLabels = [
        self::COUNTRY_CN => '内地',
        self::COUNTRY_JP => '日本',
        self::COUNTRY_EN => '欧美',
        self::COUNTRY_KR => '韩国',
        self::COUNTRY_HK => '港台',
    ];

    public function init() {
        parent::init();
        $this->is_publish = self::YES;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%book}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['author_id', 'view_count', 'favorite_count', 'comment_count', 'country', 'up_count', 'down_count', 'is_vip', 'price', 'is_finish', 'is_publish', 'update_time', 'create_time'], 'integer'],
            [['score'], 'number'],
            [['title', 'thumbnail'], 'required'],
            [['title', 'thumbnail', 'last_item'], 'string', 'max' => 160],
            [['description'], 'string', 'max' => 255],
            [['letter'], 'string', 'max' => 6],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'author_id' => Yii::t('app', 'Author ID'),
            'title' => Yii::t('app', 'Title'),
            'thumbnail' => Yii::t('app', 'Thumbnail'),
            'description' => Yii::t('app', 'Description'),
            'letter' => Yii::t('app', 'Letter'),
            'view_count' => Yii::t('app', 'View Count'),
            'favorite_count' => Yii::t('app', 'Favorite Count'),
            'comment_count' => Yii::t('app', 'Comment Count'),
            'score' => Yii::t('app', 'Score'),
            'country' => Yii::t('app', 'Country'),
            'up_count' => Yii::t('app', 'Up Count'),
            'down_count' => Yii::t('app', 'Down Count'),
            'is_finish' => Yii::t('app', 'Is Finish'),
            'is_vip' => Yii::t('app', 'Is vip'),
            'is_publish' => Yii::t('app', 'Is Publish'),
            'last_item' => Yii::t('app', 'Last Item'),
            'update_time' => Yii::t('app', 'Update Time'),
            'create_time' => Yii::t('app', 'Create Time'),
        ];
    }

    public function beforeValidate() {
        if (!empty($this->description) && mb_strlen($this->description, 'UTF-8') > 250) {
            $this->description = mb_substr($this->description, 0, 250);
        }
        return parent::beforeValidate();
    }

    /**
     * @param $insert
     * @return bool
     * @throws \Exception
     */
    public function beforeSave($insert) {
        if ($insert) {
            $this->letter = AppHelper::getLetter($this->title);
            if (empty($this->country)) {
                $this->country = self::COUNTRY_CN;
            }
            $this->create_time = time();
        }
        return BaseActiveRecord::beforeSave($insert);
    }

    public function getItems() {
        $dependency = new TagDependency(['tags' => "book_item_{$this->id}"]);
        return $this->hasMany(BookItem::class, ['book_id' => 'id'])->cache(3600, $dependency)->asArray()->orderBy('sort asc');
    }

    public function getTags() {
        return $this->hasMany(Relationship::class, ['owner_id' => 'id'])->with(['meta'])->asArray();
    }

    public function getAuthor() {
        return $this->hasOne(Meta::class, ['id' => 'author_id'])->asArray();
    }

    public function getRelationships() {
        return $this->hasMany(Relationship::class, ['owner_id' => 'id'])->asArray()->indexBy('meta_id');
    }

    public function getLastItem() {
        return $this->hasOne(BookItem::class, ['book_id' => 'id'])->orderBy('id desc');
    }

    /**
     * @param $params
     * @return array
     * @throws Exception
     */
    public function saveBook($params) {
        if (!isset($params['title'])) {
            return self::retErr();
        }
        Yii::$app->db->close();

        $model = static::findOne(['title' => $params['title']]);
        if (!$model) {
            $model = $this;
        }

        if ($model->getIsNewRecord() && !$model->load($params, '')) {
            return self::retErr();
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model->save();

            if (isset($params['tag']) && !empty($params['tag'])) {
                Meta::saveMetaRelation($params['tag'], $model->id, Meta::TYPE_TAG);
            }

            if (isset($params['author']) && !empty($params['author'])) {
                $authorId = Meta::saveMeta(['name' => $params['author']], Meta::TYPE_AUTHOR);
                $model->author_id = $authorId;
                $model->save(FALSE);
            }

            if (isset($params['items']) && !empty($params['items'])) {
                $item = new BookItem();
                $item->saveItem($params['items'], $model->id);
            }

            $lastItem = BookItem::find()->where(['book_id' => $model->id])->orderBy('id desc')->asArray()->one();
            if ($lastItem) {
                $lastItemName = BookHelper::getFullBookItemName($lastItem);
                if ($model->last_item !== $lastItemName) {
                    $model->update_time = time();
                    $model->last_item = $lastItemName;
                    $model->save(FALSE);
                }
            }

            $transaction->commit();
        } catch (Exception $e) {
            $transaction->rollBack();
            return static::retErr($e->getMessage());
        }
        return static::retOK();
    }

    public function isFinish() {
        return intval($this->is_finish) === intval(self::FINISH_YES);
    }

    public function getFinishName() {
        if ($this->isFinish()) {
            return self::$finishLabels[self::FINISH_YES];
        }
        return "更至 {$this->last_item}";
    }
}
