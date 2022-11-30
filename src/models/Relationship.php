<?php

namespace app\models;

use app\components\BaseActiveRecord;
use Yii;

/**
 * This is the model class for table "{{%relationship}}".
 *
 * @property int $owner_id
 * @property int $meta_type
 * @property int $meta_id
 *
 * @property $owner
 * @property $meta
 */
class Relationship extends BaseActiveRecord {
    /**
     * {@inheritdoc}
     */
    public static function tableName() {
        return '{{%relationship}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['owner_id', 'meta_id'], 'required'],
            [['owner_id', 'meta_id', 'meta_type'], 'integer'],
            [['owner_id', 'meta_id'], 'unique', 'targetAttribute' => ['owner_id', 'meta_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels() {
        return [
            'owner_id' => Yii::t('app', 'Owner ID'),
            'meta_id' => Yii::t('app', 'Meta ID'),
        ];
    }

    public function getMeta() {
        return $this->hasOne(Meta::class, ['id' => 'meta_id']);
    }

    public function getOwner() {
        return $this->hasOne(Book::class, ['id' => 'owner_id']);
    }
}
