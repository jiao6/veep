<?php
//require_once("config/config.php");
//require_once("config/dsql.php");

class Pagination{
    //每页数据数量
    const DEFAULT_PAGE_SIZE_DEFAULT = 40;
    const LARGEST_PAGE_SIZE = 99999999;//每页最多

    private $recordCount = 9999;//总记录数
    private $pageSize    = DEFAULT_PAGE_SIZE_DEFAULT;//每页记录数
    private $pageNo      = 1;//当前页号
    private $nextCount   = 3;//前、后若干页
    private $destinationUrl   = "";//前后翻页的所用的目标 URL，带有参数
    private $pageNoPrev  = 1;//上一页页号
    private $pageNoNext  = 1;//下一页页号

    private $pageCount   = 1;//总页数
    /* 构造函数。总记录数量、每页记录数、当前页号  */
    function __construct($recordCount, $pageSize, $pageNo){
        $this->recordCount = $recordCount;
        $this->pageSize =    $pageSize > 0?$pageSize:LARGEST_PAGE_SIZE;
        $this->pageNo =      $pageNo;
        $this->getPageCount();
    }

    function setRecordCount($recordCount) {
        $this->recordCount = $recordCount;
    }
    function setPageSize($pageSize) {
        $this->pageSize = $pageSize;
    }
    function getPageSize() {
        return $this->pageSize;
    }
    function setPageNo($pageNo) {
        $this->pageNo = $pageNo;
    }
    /* 显示前、后若干页 */
    function setNextCount($nextCount) {
        $this->nextCount = $nextCount;
    }
    /* 翻页用的URL */
    function setDestinationUrl($destinationUrl) {
        $this->destinationUrl = $destinationUrl;
    }

    function getPageCount() {
        $this->pageCount = (int)(($this->recordCount - 1)/$this->pageSize) + 1;
        if (($this->pageNo) > ($this->pageCount)) {//重新算页数，页号可能会变
            $this->pageNo = $this->pageCount;
        }
        if ($this->pageNo <= 0)//小于等于零，跳到第一页。
            $this->pageNo = 1;
        //echo  $this->recordCount . "; pageSize=" . $this->pageSize . "; pageNo=" . $this->pageNo  . "; pageCount=" . $this->pageCount. "<br/>";
    	$this->pageNoPrev  = ($this->pageCount > 1)?($this->pageNo - 1):1;//上一页页号
    	$this->pageNoNext  = ($this->pageNo < $this->pageCount)?($this->pageNo + 1):$this->pageCount;//下一页页号
        return $this->pageCount;
    }
    function getPageNo() {
        return $this->pageNo;
    }
    //当前是第一页
    function isFirst() {
        if ($this->pageNo <= 1) {
            $this->pageNo = 1;
            return true;
        }
        return false;
    }
    //是否有上一页
    function hasPrev() {
        if ($this->isFirst()) {
            return false ;
        }
        return true;
    }
    //取得上一页的页号
    function getPrev() {
        if ($this->hasPrev()) {
            return $this->pageNo-1 ;
        }
        return 1;
    }

    function isLast() {
        if ($this->pageNo >= $this->pageCount) {
            //echo $this->pageNo ."; pageCount=".$this->pageCount . "<br/>";
            $this->pageNo = $this->pageCount;
            return true;
        }
        return false;
    }
    //是否有下一页
    function hasNext() {
        if ($this->isLast()) {
            return false;
        }
        return true;
    }
    function getNext() {
        if ($this->hasNext()) {
            return $this->pageNo+1 ;
        }
        return $this->pageNo;
    }
    /* 是否有记录 */
    function hasRecord() {
        if ($this->recordCount > 0) {
            return true;
        }
        return false;
    }
    /* 返回页数数组 */
    function getPageList() {
        if (!$this->hasRecord()) {
            return ;
        }
        /**/
        $cnt = $this->pageCount;
        for ($x=0; $x < $cnt; $x++) {
          $array[$x] = $x + 1;
          //echo $array[$x] . "<br/>";
        }
        return $array;
    }
    //获得前面的3页；
    function getPrevPageList() {
        if (!$this->hasPrev()) {//没有前面的页
            return;
        }
        $trg = $this->pageNo - $this->nextCount;
        if ($trg <= 1) $trg = 1;
        $c = 0;
        for ($x= $trg; $x <= ($this->pageNo - 1); $x++) {
          $array[$c] = $x;
          $c++;
          //echo $array[$x] . "<br/>";
        }
        return $array;
    }
    //获得后面的3页；

    function getNextPageList() {
        if (!$this->hasNext()) {//没有后面的页
            return;
        }/**/
        $trg = $this->pageNo + $this->nextCount;
        if ($trg >= $this->pageCount) $trg = $this->pageCount;
        $c = 0;
        for ($x= ($this->pageNo + 1); $x <= $trg; $x++) {
          $array[$c] = $x;
          $c++;
          //echo $array[$x] . "<br/>";
        }
        return $array;
    }
    function toString($url, $queryString, $PARAM_PAGE_SIZE, $PARAM_PAGE_NO) {
    	//echo "recordCount=" + $this->recordCount;
    	if ($this->recordCount < 1) return; //无记录，不显示
        $pageSizeList = array(10, self::DEFAULT_PAGE_SIZE_DEFAULT, 100);//显示记录数, PAGINATION::LARGEST_PAGE_SIZE
        $destUrl = $url. $queryString;
        $queryPageSize = "&".$PARAM_PAGE_SIZE."=";

        echo "<script>var dest='$destUrl';";
        echo "var query='". $PARAM_PAGE_SIZE ."';";
        echo "var queryPageSize='". $queryPageSize ."'; "."\n\r";
        echo "function goto(sel){dest += sel.value; document.location=dest + queryPageSize + selPageSize.value;}" ."\n\r";
        echo "function turnPage(link) {"."\n\r";
        echo "var pos = link.indexOf(queryPageSize); //alert('link=' + link + '; pos=' + pos);". "\n\r";
        echo "if (pos < 0)link = link + queryPageSize + selPageSize.value; //避免重复加入pagesize 参数". "\n\r";
        echo "else {link = link + queryPageSize + selPageSize.value;}; //避免重复加入pagesize 参数". "\n\r";
        echo "//alert(link);" . "\n\r";
        echo "document.location=link;" . "\n\r";
        echo "}";
        echo "</script>" . "\n\r";

        echo "<div id='text14'>" . "\n\r";

        echo "<a class=\"button\" href='javascript:turnPage(\"". $destUrl . 1 ."\")'>首页</a>" . "\n\r";
        $pageList = $this->getPrevPageList();
        //echo "dddd=" . sizeof($pageList);
        foreach($pageList as $aPage) {
            echo "<a href='javascript:turnPage(\"". $destUrl . $aPage ."\")'>$aPage</a>" . "\n\r";
        }

        if ($this->hasPrev()) echo "<a href='javascript:turnPage(\"". $destUrl . $this->pageNoPrev ."\")'>上一页</a>" . "\n\r";
        echo "" . $this->pageNo ;
        if ($this->hasNext()) echo "<a href='javascript:turnPage(\"". $destUrl . $this->pageNoNext ."\")'>下一页</a>" . "\n\r";
        $pageList = $this->getNextPageList();
        foreach($pageList as $aPage) {
            echo "<a href='javascript:turnPage(\"". $destUrl . $aPage ."\")'>$aPage</a>" . "\n\r";
        }

        echo "<a href='javascript:turnPage(\"". $destUrl . $this->pageCount ."\")'>尾页</a>" . "\n\r";
        $str1 = "<select id='selPageSize' name='selPageSize' style='width:40px'>" ."\n\r";//产生每页记录数的 select
        foreach($pageSizeList as $aPage) {
            $str3 = $aPage;//显示文本
            if ($aPage == PAGINATION::LARGEST_PAGE_SIZE) {
                $str3 = "全部";// 每页 99999999 个的话，显示全部
            }
            if ($aPage == $this->pageSize) {
                $str2 = "<option value='$aPage' selected>$str3</option>". "\n\r";
            } else {
                $str2 = "<option value='$aPage'>$str3</option>". "\n\r";
            }
            $str1 .= $str2;
        }
        $str1.= "</select>". "\n\r";
        echo "共 ". $this->recordCount ." 条记录，". $this->pageCount ." 页。每页 ". $str1 ." 条。" . "\n\r";
        $pageList = $this->getPageList();
        if ($this->pageCount > 1) {//超过 1 页才显示页列表
            echo "去第<select id='selGoto' name='selGoto' onchange='goto(this)' style='width:40px'>". "\n\r";//
            $selected = "";
            foreach($pageList as $aPage) {
                if ($aPage == $this->pageNo) {$selected = "selected";} else {$selected = "";}
                echo"<option value='$aPage' $selected >$aPage</option>\n\r";//
            }
            echo "</select>页";
        }
        echo"</div>" . "\n\r";

    }
}
?>
