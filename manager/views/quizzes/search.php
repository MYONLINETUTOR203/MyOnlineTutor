<?php

defined('SYSTEM_INIT') or die('Invalid Usage.');
$arrFlds = [
    'listserial' => Label::getLabel('LBL_Sr._No'),
    'quiz_title' => Label::getLabel('LBL_TITLE'),
    'quiz_type' => Label::getLabel('LBL_TYPE'),
    'quiz_teacher' => Label::getLabel('LBL_TEACHER'),
    'quiz_questions' => Label::getLabel('LBL_NO._OF_QUESTIONS'),
    'quiz_duration' => Label::getLabel('LBL_DURATION'),
    'quiz_attempts' => Label::getLabel('LBL_ATTEMPTS'),
    'quiz_passmark' => Label::getLabel('LBL_PASS_PERCENT'),
    'quiz_status' => Label::getLabel('LBL_STATUS'),
    'quiz_created' => Label::getLabel('LBL_ADDED_ON'),
    'action' => Label::getLabel('LBL_ACTION'),
];
$types = Quiz::getTypes();
$status = Quiz::getStatuses();

$tbl = new HtmlElement('table', ['width' => '100%', 'class' => 'table table-responsive', 'id' => 'questionsList']);
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arrFlds as $k => $val) {
    $width = ($k == 'quiz_title') ? '20%' : '';
    $e = $th->appendElement('th', ['width' => $width], $val);
}
$srNo = $page == 1 ? 0 : $pageSize * ($page - 1);
foreach ($arrListing as $sn => $row) {
    $srNo++;
    $tr = $tbl->appendElement('tr', ['id' => $row['quiz_id']]);
    foreach ($arrFlds as $key => $val) {
        $width = ($key == 'quiz_title') ? '20%' : '';
        $td = $tr->appendElement('td', ['width' => $width]);
        switch ($key) {
            case 'listserial':
                $td->appendElement('plaintext', [], $srNo);
                break;
            case 'quiz_title':
                $td->appendElement('plaintext', [], CommonHelper::renderHtml($row['quiz_title']));
                break;
            case 'quiz_type':
                $td->appendElement('plaintext', [], $types[$row['quiz_type']]);
                break; 
            case 'quiz_duration':
                $duration = ($row['quiz_duration']) ? MyUtility::convertDuration($row['quiz_duration']) : '-';
                $td->appendElement('plaintext', [], $duration);
                break; 
            case 'quiz_teacher':
                $teacher = ucwords($row['teacher_first_name'] . ' ' . $row['teacher_last_name']);
                $td->appendElement('plaintext', [], $teacher);
                break; 
            case 'quiz_passmark':
                $passmark = ($row['quiz_passmark']) ? MyUtility::formatPercent($row['quiz_passmark']) : '-';
                $td->appendElement('plaintext', [], $passmark);
                break;        
            case 'quiz_status':
                $td->appendElement('plaintext', [], $status[$row['quiz_status']]);
                break;
            case 'quiz_created':
                $td->appendElement('plaintext', [], MyDate::formatDate($row['quiz_created']));
                break;
            case 'action':
                $ul = $td->appendElement("ul", ["class" => "actions actions--centered"]);
                $li = $ul->appendElement("li", ['class' => 'droplink']);
                $li->appendElement('a', ['href' => 'javascript:void(0)', 'class' => 'button small green', 'title' => Label::getLabel('LBL_OPTIONS')], '<i class="ion-android-more-horizontal icon"></i>', true);
                $innerDiv = $li->appendElement('div', ['class' => 'dropwrap']);
                $innerUl = $innerDiv->appendElement('ul', ['class' => 'linksvertical']);

                $innerLiEdit = $innerUl->appendElement('li');
                $innerLiEdit->appendElement('a', ['href' => 'javascript:void(0);', 'onclick' => 'view("'.$row['quiz_id'].'")', 'class' => 'button small green', 'title' => Label::getLabel('LBL_VIEW')], Label::getLabel('LBL_VIEW'), true);

                $innerLiEdit = $innerUl->appendElement('li');
                $innerLiEdit->appendElement('a', ['href' => 'javascript:void(0);', 'onclick' => 'questions("' . $row['quiz_id'] . '")', 'class' => 'button small green', 'title' => Label::getLabel('LBL_QUESTIONS')], Label::getLabel('LBL_QUESTIONS'), true);
        
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
$pagingArr = ['pageCount' => ceil($recordCount / $post['pagesize']), 'pageSize' => $post['pagesize'], 'page' => $post['pageno'], 'recordCount' => $recordCount];
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);