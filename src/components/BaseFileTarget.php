<?php

namespace app\components;

use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;
use yii\log\Logger;
use yii\log\Target;
use Yii;

class BaseFileTarget extends Target {

    private $logFile;
    public $logPath;
    public $logVars = [];

    /**
     * @throws Exception
     */
    public function init() {
        parent::init();
        if (NULL == $this->logPath) {
            $this->logPath = substr(md5(print_r($this->getLevels(), TRUE)), 8, 8);
        }
        $name = date('Y-m-d');
        $this->logFile = Yii::$app->getRuntimePath() . "/logs/{$this->logPath}/{$name}.log";
        $logPath = dirname($this->logFile);
        if (!is_dir($logPath)) {
            FileHelper::createDirectory($logPath, 0755, TRUE);
        }
    }

    /**
     * @throws InvalidConfigException
     */
    public function export() {
        $text = implode("\n", array_map([$this, 'formatMessage'], $this->messages)) . "\n";
        if (($fp = @fopen($this->logFile, 'a')) === FALSE) {
            throw new InvalidConfigException("Unable to append to log file: {$this->logFile}");
        }
        @flock($fp, LOCK_EX);
        @fwrite($fp, $text);
        @flock($fp, LOCK_UN);
        @fclose($fp);
    }

    public function formatMessage($message) {
        list($text, $level, $category, $timestamp) = $message;
        $level = Logger::getLevelName($level);
        if (!is_string($text)) {
            if ($text instanceof \Exception) {
                $text = (string)$text;
            } else {
                $text = VarDumper::export($text);
            }
        }
        $traces = [];
        if (isset($message[4])) {
            foreach ($message[4] as $trace) {
                $traces[] = "in {$trace['file']}:{$trace['line']}";
            }
        }

        $tmp = explode('.', $timestamp);
        if (isset($tmp[1])) {
            $t = date('Y-m-d H:i:s', $tmp[0]) . '.' . $tmp[1];
        } else {
            $t = $timestamp;
        }
        $prefix = $this->getMessagePrefix($message);
        return $t . " {$prefix}[$level][$category] $text" . (empty($traces) ? '' : "\n    " . implode("\n    ", $traces));
    }
}