<?php

namespace app\actions;

use app\components\BaseController;
use yii\base\Action;
use yii\base\Event;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

class UploadAction extends Action {

    /* @var $controller BaseController */
    public $controller;

    private $errors = array(
        UPLOAD_ERR_INI_SIZE => '上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。',
        UPLOAD_ERR_FORM_SIZE => '上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。',
        UPLOAD_ERR_PARTIAL => '文件只有部分被上传。',
        UPLOAD_ERR_NO_FILE => '没有文件被上传。',
        UPLOAD_ERR_NO_TMP_DIR => '找不到临时文件夹',
        UPLOAD_ERR_CANT_WRITE => '文件写入失败。',
    );

    const EVENT_AFTER_UPLOAD = 'afterUpload';

    public function run() {
        if (empty($_FILES)) {
            return $this->error('empty Upload');
        }

        $allowExt = array('jpg', 'gif', 'png', 'jpeg');
        $data = [];
        foreach ($_FILES as $_file) {
            if ($_file['error'] > 0) {
                return $this->error(ArrayHelper::getValue($this->errors, $_file['error'], '位置错误'));
            }
            $res = $this->__upload($_file, $allowExt);
            if ($res['code'] != 0) {
                return $res;
            }
            $data[] = $res['data'];
        }

        $mid = \Yii::$app->controller->module->id;
        if ($mid != 'api' && count($data) <= 1) {
            $data = current($data);
        }
        return $this->success($data);
    }

    protected function __upload($_file, $allowExt = []) {
        $dir = $this->controller->get('dir', date('Ym'));
        $path = "/uploads/{$dir}/";
        $absolutePath = APP_ROOT . $path;

        FileHelper::createDirectory($absolutePath);

        $fileExt = strtolower(pathinfo($_file['name'], PATHINFO_EXTENSION));
        $fileExt = str_replace('jpeg', 'jpg', $fileExt);
        $fileName = strtolower(substr(md5(uniqid()), 8, 16)) . ".{$fileExt}";

        if (!empty($allowExt) && !in_array($fileExt, $allowExt, TRUE)) {
            return $this->error('上传文件类型不允许.');
        }

//        $name = $this->controller->getParam('name');
//        if ($name) {
//            $fileName = $name;
//        }

        $fileAbsolutePath = $absolutePath . $fileName;
        if (!file_exists($fileAbsolutePath)) {
            if (!move_uploaded_file($_file['tmp_name'], $fileAbsolutePath)) {
                return $this->error('保存文件失败');
            }
        }

        $file = $path . $fileName;
        $src = \Yii::$app->request->getHostInfo() . $file;

        $event = new Event();
        $event->data = compact('file');
        $this->trigger(self::EVENT_AFTER_UPLOAD, $event);

        return $this->success(['file' => $file, 'src' => $src]);
    }

    protected function success($data) {
        $type = $this->controller->get('type');
        if ($type == 'edit') {
            return $this->controller->exitJson(['url' => $data['file'], 'error' => 0]);
        }
        return $this->controller->success($data);
    }

    protected function error($msg) {
        $type = $this->controller->get('type');
        if ($type == 'edit') {
            return $this->controller->exitJson(['message' => $msg, 'error' => 1]);
        }
        return $this->controller->error($msg);
    }
}