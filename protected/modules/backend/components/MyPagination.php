<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MyPagination
 *
 * @author khangld
 */
class MyPagination extends CLinkPager {

    public function __construct() {
        
    }

    // override function CHtml::link -> CHtml::ajaxLink
//    protected function createPageButton($label, $page, $class, $hidden, $selected) {
//        if ($hidden || $selected)
//            $class.=' ' . ($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
//        return '<li class="' . $class . '">' . CHtml::ajaxLink($label, $this->createPageUrl($page)) . '</li>';
//    }

    protected function createPageButtons() {
        if (($pageCount = $this->getPageCount()) <= 1)
            return array();

        list($beginPage, $endPage) = $this->getPageRange();
        $currentPage = $this->getCurrentPage(false); // currentPage is calculated in getPageRange()
        $buttons = array();

        // first page
        $buttons[] = $this->createPageButton($this->firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0, false);

        // prev page
        if (($page = $currentPage - 1) < 0)
            $page = 0;
        $buttons[] = $this->createPageButton($this->prevPageLabel, $page, $this->previousPageCssClass, $currentPage <= 0, false);

        // internal pages
        for ($i = $beginPage; $i <= $endPage;  ++$i)
            $buttons[] = $this->createPageButton($i + 1, $i, $this->internalPageCssClass, false, $i == $currentPage);

        // next page
        if (($page = $currentPage + 1) >= $pageCount - 1)
            $page = $pageCount - 1;
        $buttons[] = $this->createPageButton($this->nextPageLabel, $page, $this->nextPageCssClass, $currentPage >= $pageCount - 1, false);

        // last page
        $buttons[] = $this->createPageButton($this->lastPageLabel, $pageCount - 1, $this->lastPageCssClass, $currentPage >= $pageCount - 1, false);

        return $buttons;
    }

}
