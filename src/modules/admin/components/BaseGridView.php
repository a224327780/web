<?php
namespace admin\components;

use yii\grid\GridView;
use yii\widgets\BaseListView;

class BaseGridView extends GridView {

    public $tableOptions = ['class' => 'table'];
    public $layout = "{items}\n{pager}";
    public $summaryOptions = ['class' => 'summary', 'tag' => 'span'];
    public $pager = ['class' => 'app\components\BasePager'];

    public function renderSection($name) {
        switch ($name) {
            case '{summary}':
                return $this->renderSummary();
            case '{items}':
                return $this->renderItems();
            case '{pager}':
                $this->pager['summary'] = $this->renderSummary();
                return $this->renderPager();
            case '{sorter}':
                return $this->renderSorter();
            default:
                return FALSE;
        }
    }

    public function run() {
        BaseListView::run();
    }
}