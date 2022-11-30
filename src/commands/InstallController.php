<?php

namespace app\commands;

use app\helpers\InstallHelper;
use Exception;
use Yii;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use YiiRequirementChecker;

class InstallController extends Controller {

    protected $db_file;
    protected $local_file;

    public function beforeAction1($action) {
        if (!parent::beforeAction($action)) {
            return FALSE;
        }
        $this->db_file = RUNTIME_ROOT . '/config/db.php';
        $this->local_file = RUNTIME_ROOT . '/config/local.php';
        if (is_readable($this->db_file)) {
            $this->info(Yii::t('app', 'The system has been installed.'), Console::FG_RED);
            return FALSE;
        }

        try {
            $size = Console::getScreenSize();
            $size = $size[0];
        } catch (Exception $e) {
            $size = 90;
        }
        $bar_size = $size / 2 - 7;
        $s = str_repeat('=', $bar_size);
        $this->info("{$s}Install{$s}");

        return $this->checkRequirement();
    }

    protected function checkRequirement() {
        require_once(YII_ROOT . '/requirements/YiiRequirementChecker.php');
        $checker = new YiiRequirementChecker();
        $result = $checker->checkYii()->getResult();
        foreach ($result['requirements'] as $item) {
            if (!$item['condition'] && $item['mandatory']) {
                $this->info("{$item['name']} {$item['memo']}", Console::FG_RED);
            }
        }
        return !$result['summary']['errors'];
    }

    public function actionIndex() {
        if (!is_dir(RUNTIME_ROOT . '/config')) {
            mkdir(RUNTIME_ROOT . '/config');
        }
        $app_env = $this->confirm($this->ansiFormat('是否生产环境', Console::FG_YELLOW), FALSE);
        $db_host = $this->prompt($this->ansiFormat('请输入数据库地址:', Console::FG_YELLOW), ['default' => 'localhost']);
        $db_port = $this->prompt($this->ansiFormat('请输入数据库端口:', Console::FG_YELLOW), ['default' => 3306]);
        $db_name = $this->prompt($this->ansiFormat('请输入数据库名:', Console::FG_YELLOW), ['default' => 'hahamh']);
        $db_user = $this->prompt($this->ansiFormat('请输入数据库用户:', Console::FG_YELLOW), ['default' => 'root']);
        $db_pass = $this->prompt($this->ansiFormat('请输入数据库密码:', Console::FG_YELLOW), ['default' => '88888']);
        $db_prefix = $this->prompt($this->ansiFormat('请输入数据库表前缀:', Console::FG_YELLOW), ['default' => 'ii_']);

        $username = $this->prompt($this->ansiFormat('请输入管理员账号:', Console::FG_YELLOW), ['default' => 'root']);
        $password = $this->prompt($this->ansiFormat('请输入管理员账号:', Console::FG_YELLOW), ['default' => '88888']);

        $params['db'] = compact('db_name', 'db_host', 'db_pass', 'db_port', 'db_prefix', 'db_user');
        $params['env'] = $app_env ? 'prod' : 'dev';
        $params['user'] = compact('username', 'password');

        $res = InstallHelper::install($params);
        $this->info($res['msg']);
        return ExitCode::OK;
    }

    public function actionLink() {
        $dirList = ['themes', 'uploads'];
        foreach ($dirList as $dir) {
            $srcDir = DATA_ROOT . "/{$dir}";
            $destDir = PUBLIC_ROOT . "/{$dir}";
            $status = symlink($srcDir, $destDir) ? 'done' : 'fail';
            $this->info("The [{$srcDir}] link has been connected to [{$destDir}]. \nThe links have been created.");
        }
        return ExitCode::OK;
    }

    public function info($string, $color = Console::FG_YELLOW) {
        return parent::stdout("{$string}\n", $color);
    }
}