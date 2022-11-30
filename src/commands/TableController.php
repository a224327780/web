<?php


namespace app\commands;


use Yii;
use yii\console\Controller;

class TableController extends Controller {

    public function actionIndex($name) {
        try{
            $db = Yii::$app->db;
            $schema = $db->getSchema()->getTableSchema($name);
            $headers = ['英文列表', '数据类型', '是否主键', '非空', '默认值', '备注'];

            $fp = fopen(DATA_ROOT . "/{$name}.csv", 'a');
            fputcsv($fp, $headers);
            foreach ($schema->columns as $key => $column) {
                $body = [
                    $key,
                    $column->dbType,
                    $column->isPrimaryKey ? 'Y' : 'N',
                    $column->allowNull ? 'Y' : 'N',
                    $column->defaultValue,
                    $column->comment
                ];
                fputcsv($fp, $body);
            }
            fclose($fp);
        }catch (\Exception $e){
            print_r($e);
        }

    }
}