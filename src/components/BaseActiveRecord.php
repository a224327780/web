<?php

namespace app\components;

use app\helpers\AppHelper;
use yii;
use yii\caching\TagDependency;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Exception;

class BaseActiveRecord extends ActiveRecord {

    public $pageSize = 15;
    public $sortAttributes = ['id'];
    public $defaultOrder = ['id' => SORT_DESC];
    public $with = [];
    public $where = [];
    public $order;

    public $saveSuccessMessage = 'Successfully saved.';
    public $deleteSuccessMessage = 'successfully deleted.';

    public $statusLabels = [];

    const NO = 0;
    const YES = 1;

    public static $statusColors = [
        self::NO => 'warning',
        self::YES => 'info'
    ];

    public function init() {
        parent::init();
        $this->on(ActiveRecord::EVENT_AFTER_DELETE, [$this, 'refreshCache']);
        $this->on(ActiveRecord::EVENT_AFTER_INSERT, [$this, 'refreshCache']);
        $this->on(ActiveRecord::EVENT_AFTER_UPDATE, [$this, 'refreshCache']);
    }

    public function behaviors() {
        return parent::behaviors();
    }

    public function search($params = [], $isArray = FALSE) {
        $query = static::find();
        $query->with = $this->with;
        $query->asArray($isArray);

        if (!empty($params)) {
            foreach ($params as $attribute => $value) {
                if ($this->isAttributeSafe($attribute)) {
                    $query->andFilterWhere([$attribute => $value]);
                }
            }
        }

        if ($this->order) {
            $query->orderBy($this->order);
        }
        $query->andFilterWhere($this->where);

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => ['attributes' => $this->sortAttributes, 'defaultOrder' => $this->defaultOrder],
            'pagination' => $this->pageSize === FALSE ? FALSE : ['defaultPageSize' => $this->pageSize],
        ]);
    }

    public function getError() {
        return current($this->getFirstErrors());
    }

    public function beforeSave($insert) {
        if ($this->hasAttribute('update_time')) {
            $this->setAttribute('update_time', time());
        }
        return parent::beforeSave($insert);
    }

    /**
     * @param bool $validation
     * @param null $fields
     * @return array|bool
     * @throws Exception
     */
    public function save($validation = TRUE, $fields = NULL) {
        if (!parent::save($validation, $fields)) {
            throw new Exception($this->getError());
        }
        return self::retOK(NULL, Yii::t('app', $this->saveSuccessMessage));
    }

    /**
     * @return array|false|int
     * @throws Exception
     */
    public function delete() {
        if (!parent::delete()) {
            throw new Exception($this->getError());
        }
        return self::retOK(NULL, Yii::t('app', $this->deleteSuccessMessage));
    }

    public function isOk($res) {
        return $res['code'] == AppHelper::SUCCESS_CODE;
    }

    public static function retErr($msg = '', $code = AppHelper::ERROR_CODE, $data = NULL) {
        if (is_array($msg)) {
            $msg = yii\helpers\Json::encode($msg);
        }
        return AppHelper::error($msg, $code, $data);
    }

    public static function retOK($data = NULL, $msg = 'ok') {
        return AppHelper::success($data, $msg);
    }

    public function isNo($field = 'status') {
        $v = intval($this->getAttribute($field));
        return $v === intval(static::NO);
    }

    public function isYes($field = 'status') {
        $v = intval($this->getAttribute($field));
        return $v === intval(static::YES);
    }

    protected static function getUid($force = FALSE) {
        if (Yii::$app->user->isGuest && !$force) {
            $str = sprintf('%s-%s', Yii::$app->request->remoteIP, Yii::$app->request->userAgent);
            $str = substr(md5($str), 8, 8);
            return substr(bin2hex($str), 0, 9);
        }
        return Yii::$app->user->id;
    }

    public function refreshCache() {
//        $args = func_get_args();
//        Yii::error(print_r($args, TRUE));
        TagDependency::invalidate(Yii::$app->cache, static::class);
    }
}