<?php

namespace app\models;

use app\components\BaseActiveRecord;
use app\helpers\AppHelper;
use Yii;
use yii\caching\TagDependency;
use yii\db\Exception;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%book_item}}".
 *
 * @property int $id
 * @property int $book_id
 * @property int $gid
 * @property string $name
 * @property string $content
 * @property int $sort
 * @property int $price
 */
class BookItem extends BaseActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%book_item}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['book_id', 'gid', 'price', 'sort'], 'integer'],
            [['content'], 'required'],
            [['content'], 'string'],
            [['name'], 'string', 'max' => 120],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'book_id' => Yii::t('app', 'Book ID'),
            'gid' => Yii::t('app', 'Gid'),
            'name' => Yii::t('app', 'Name'),
            'content' => Yii::t('app', 'Content'),
        ];
    }

    /**
     * @param $items
     * @param $bookId
     * @param bool $is_delete_item
     * @throws Exception
     */
    public function saveItem($items, $bookId, $is_delete_item = FALSE) {
        if ($is_delete_item) {
            BookItem::deleteAll(['book_id' => $bookId]);
        }
        $items = Json::decode($items);
        $n = count($items);
        $data = [];
        $count = static::find()->where(['book_id' => $bookId])->count();
        foreach ($items as $k => $item) {
            $params = [];
            $params['name'] = $item['name'];
            $params['content'] = Json::encode($item['images']);
            $params['book_id'] = $bookId;
            $model = static::find()->where(['book_id' => $bookId, 'name' => $item['name']])->one();
            if (!$model) {
                $count += 1;
                $params['sort'] = $count;
                if ($n < 10) {
                    $model = new static();
                } else {
                    $data[] = $params;
                }
            }
            if ($n < 10) {
                $model->setAttributes($params);
                $model->save();
            }
        }
        if(!empty($data)){
            $fields = array_keys(current($data));
            Yii::$app->db->createCommand()->batchInsert(BookItem::tableName(), $fields, $data)->execute();
        }

//        TagDependency::invalidate(Yii::$app->cache, "book_item_{$bookId}");
    }

    public function getContent() {
        $data = Json::decode($this->content);
        foreach ($data as $k => & $v){
            if($this->book_id === 4466 && $this->id >= 209735 && $k < 3){
                unset($data[$k]);
                continue;
            }
            $v = AppHelper::formatImage($v);
        }
        return $data;
    }

}
