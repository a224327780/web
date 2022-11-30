<?php


namespace app\controllers;


use app\components\AppController;
use app\helpers\AppHelper;
use app\models\Meta;
use app\models\Post;
use Yii;
use yii\web\NotFoundHttpException;

class NewsController extends AppController {

    protected $title = '新闻中心';
    protected $type = Post::TYPE_NEWS;

    public function actionIndex() {
        $model = new Post();
        $params = $this->getParam();
        $params['type'] = $this->type;
        if (!empty($params['wd'])) {
            $model->where = ['like', 'title', $params['wd']];
            unset($params['wd']);
        }
        $dataProvider = $model->search($params);
        $this->view->title = $this->title;
        if (isset($params['meta_id'])) {
            $metaData = Meta::getMetaDataByType($this->type);
            if (isset($metaData[$params['meta_id']])) {
                $this->view->title = $metaData[$params['meta_id']]['name'];
            }
        }
        return $this->render(compact('dataProvider'), "/{$this->id}/{$this->action->id}");
    }

    public function actionDetail($id = NULL) {
        $model = Post::find()->with(['category'])->where(['id' => $id])->one();
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
        $this->view->title = $model->title;
        $this->description = AppHelper::convertText($model->content);
        return $this->render(compact('model'), "/{$this->id}/{$this->action->id}");
    }
}