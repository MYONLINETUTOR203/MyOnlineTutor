<?php

defined('SYSTEM_INIT') or die('Invalid Usage.');
$arrFlds = [
    'listserial' => Label::getLabel('LBL_Sr._No'),
    'ques_title' => Label::getLabel('LBL_QUESTION_TITLE'),
    'ques_cate_name' => Label::getLabel('LBL_CATEGORY'),
    'ques_subcate_name' => Label::getLabel('LBL_SUBCATEGORY'),
    'full_name' => Label::getLabel('LBL_TEACHER'),
    'ques_created' => Label::getLabel('LBL_ADDED_ON'),
    'action' => Label::getLabel('LBL_ACTION'),
];

$tbl = new HtmlElement('table', ['width' => '100%', 'class' => 'table table-responsive', 'id' => 'questionsList']);
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arrFlds as $val) {
    $e = $th->appendElement('th', [], $val);
}
$srNo = $page == 1 ? 0 : $pageSize * ($page - 1);
foreach ($arrListing as $sn => $row) {
    $srNo++;
    $tr = $tbl->appendElement('tr', ['id' => $row['ques_id']]);
    foreach ($arrFlds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'listserial':
                $td->appendElement('plaintext', [], $srNo);
                break;
            case 'ques_title':
                $td->appendElement('plaintext', [], $row['ques_title']);
                break;
            case 'ques_cate_name':
                $td->appendElement('plaintext', [], $row['ques_cate_name']);
                break; 
            case 'ques_subcate_name':
                    $td->appendElement('plaintext', [], $row['ques_subcate_name']);
                break; 
            case 'full_name':
                $td->appendElement('plaintext', [], $row['full_name']);
                break;
            case 'ques_created':
                $td->appendElement('plaintext', [], MyDate::formatDate($row['ques_created']));
                break;    
            case 'action':
                $ul = $td->appendElement("ul", ["class" => "actions actions--centered"]);
                $li = $ul->appendElement("li", ['class' => 'droplink']);
                $li->appendElement('a', ['href' => 'javascript:void(0)', 'class' => 'button small green', 'title' => Label::getLabel('LBL_OPTIONS')], '<i class="ion-android-more-horizontal icon"></i>', true);
                $innerDiv = $li->appendElement('div', ['class' => 'dropwrap']);
                $innerUl = $innerDiv->appendElement('ul', ['class' => 'linksvertical']);

                $innerLiEdit = $innerUl->appendElement('li');
                $innerLiEdit->appendElement('a', ['href' => 'javascript:void(0);', 'onclick' => 'view("'.$row['ques_id'].'")', 'class' => 'button small green', 'title' => Label::getLabel('LBL_VIEW')], Label::getLabel('LBL_VIEW'), true);
        
                break;
            default:
                $td->appendElement('plaintext', [], CommonHelper::renderHtml($row[$key] ?? '-'));
                break;
        }
    }
}

if (count($arrListing) == 0) {
    $tbl->appendElement('tr')->appendElement('td', ['colspan' => count($arrFlds)], Label::getLabel('LBL_NO_RECORDS_FOUND'));
}
echo $tbl->getHtml();
echo FatUtility::createHiddenFormFromData($post, ['name' => 'frmPaging']);
$pagingArr = ['pageCount' => ceil($recordCount / $post['pagesize']), 'pageSize' => $post['pagesize'], 'page' => $post['page'], 'recordCount' => $recordCount];
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
?>
