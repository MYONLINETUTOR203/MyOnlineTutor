<?php

defined('SYSTEM_INIT') or die('Invalid Usage.');
$arrFlds = [
    'listserial' => Label::getLabel('LBL_Sr._No'),
    'course_title' => Label::getLabel('LBL_COURSE_NAME'),
    'user_name' => Label::getLabel('LBL_TEACHER_NAME'),
    'coapre_status' => Label::getLabel('LBL_STATUS'),
    'coapre_created' => Label::getLabel('LBL_REQUESTED_ON'),
    'action' => Label::getLabel('LBL_ACTION')
];
$tbl = new HtmlElement('table', ['width' => '100%', 'class' => 'table table-responsive']);
$th = $tbl->appendElement('thead')->appendElement('tr');
foreach ($arrFlds as $val) {
    $e = $th->appendElement('th', [], $val);
}
$srNo = 0;
foreach ($arrListing as $sn => $row) {
    $srNo++;
    $tr = $tbl->appendElement('tr');
    foreach ($arrFlds as $key => $val) {
        $td = $tr->appendElement('td');
        switch ($key) {
            case 'listserial':
                $td->appendElement('plaintext', [], $srNo);
                break;
            case 'user_name':
                $td->appendElement('plaintext', [], $row['user_first_name']. ' '.$row['user_last_name']);
                break;
            case 'coapre_status':
                $td->appendElement('plaintext', [], $requestStatus[$row['coapre_status']]);
                break;
            case 'action':
                $ul = $td->appendElement("ul", ["class" => "actions actions--centered"]);
                $li = $ul->appendElement("li", ['class' => 'droplink']);
                $li->appendElement('a', ['href' => 'javascript:void(0)', 'class' => 'button small green', 'title' => Label::getLabel('LBL_ACTION')], '<i class="ion-android-more-horizontal icon"></i>', true);
                $innerDiv = $li->appendElement('div', ['class' => 'dropwrap']);
                $innerUl = $innerDiv->appendElement('ul', ['class' => 'linksvertical']);

                $innerLi = $innerUl->appendElement('li');
                $innerLi->appendElement('a', ['href' => 'javascript:void(0)', 'class' => 'button small green', 'title' => Label::getLabel('LBL_VIEW'), "onclick" => "view(" . $row['coapre_id'] . ");"], Label::getLabel('LBL_VIEW'), true);

                $innerLi = $innerUl->appendElement('li');
                $innerLi->appendElement('a', ['href' =>  'javascript:void(0);', 'class' => 'button small green', 'title' => Label::getLabel('LBL_PREVIEW'), 'onclick' => 'userLogin("' . $row['user_id'] . '", "' . $row['coapre_course_id'] . '")'], Label::getLabel('LBL_PREVIEW'), true);

                if ($canEdit && $row['coapre_status'] == Course::REQUEST_PENDING) {
                    $innerLi = $innerUl->appendElement('li');
                    $innerLi->appendElement('a', ['href' => 'javascript:void(0)', 'class' => 'button small green', 'title' => Label::getLabel('LBL_CHANGE_STATUS'), "onclick" => "changeStatusForm(" . $row['coapre_id'] . ");"], Label::getLabel('LBL_CHANGE_STATUS'), true);
                }
                break;
            default:
                $td->appendElement('plaintext', [], $row[$key] ?? '-');
                break;
        }
    }
}

if (count($arrListing) == 0) {
    $tbl->appendElement('tr')->appendElement('td', ['colspan' => count($arrFlds)], Label::getLabel('LBL_NO_RECORDS_FOUND'));
}
echo $tbl->getHtml();
echo FatUtility::createHiddenFormFromData($postedData, ['name' => 'frmCommPaging']);
$pagingArr = ['pageCount' => ceil($recordCount / $pageSize), 'page' => $page, 'pageSize' => $pageSize, 'recordCount' => $recordCount];
$this->includeTemplate('_partial/pagination.php', $pagingArr, false);
