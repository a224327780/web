<?php

namespace app\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\widgets\LinkPager;
use yii\helpers\Html;

class BasePager extends LinkPager {

    public $nextPageLabel = FALSE;
    public $prevPageLabel = FALSE;
    public $maxButtonCount = 5;
    public $registerLinkTags = TRUE;
    public $firstPageLabel = TRUE;
    public $lastPageLabel = TRUE;
    public $summary;
    public $pageCssClass = 'page-item';
    public $options = ['class' => 'pagination justify-content-center'];
    public $linkOptions = ['class' => 'page-link'];

    /**
     * @throws InvalidConfigException
     */
    public function init() {
        parent::init();
        $route = Yii::$app->controller->getRoute();
        $this->pagination->route = str_replace('/index', '/', $route);
    }

    protected function renderPageButtons() {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();
        list($beginPage, $endPage) = $this->getPageRange();

        // first page
        $firstPageLabel = $this->firstPageLabel === TRUE ? '1' : $this->firstPageLabel;
        if ($firstPageLabel !== FALSE && $beginPage > 0) {
            $buttons[] = $this->renderPageButton($firstPageLabel, 0, $this->pageCssClass, $currentPage <= 0, FALSE);
            $buttons[] = Html::tag('li', Html::tag('span', '...'), ['class' => $this->pageCssClass]);
        }

        // prev page
        if ($this->prevPageLabel !== FALSE) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            if ($currentPage > 0) {
                $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass, $currentPage <= 0, FALSE);
            }
        }

        // internal pages
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            $buttons[] = $this->renderPageButton($i + 1, $i, NULL, FALSE, $i == $currentPage);
        }

        // next page
        if ($this->nextPageLabel !== FALSE) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            if ($currentPage < $pageCount - 1) {
                $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->pageCssClass, $currentPage >= $pageCount - 1, FALSE);
            }
        }

        // last page
        $lastPageLabel = $this->lastPageLabel === TRUE ? $pageCount : $this->lastPageLabel;
        if ($lastPageLabel !== FALSE && $pageCount - 1 > $currentPage && $endPage + 1 < $pageCount) {
            $buttons[] = Html::tag('li', Html::tag('span', '...'), ['class' => 'page-item']);
            $buttons[] = $this->renderPageButton($lastPageLabel, $pageCount - 1, $this->pageCssClass, $currentPage >= $pageCount - 1, FALSE);
        }

        $summary = $this->summary ? "\n<li class='page-item'>{$this->summary}</li>" : '';
        return Html::tag('ul', implode("\n", $buttons) . $summary, $this->options);
    }
}