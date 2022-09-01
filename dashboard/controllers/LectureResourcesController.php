<?php

/**
 * This Controller is used for handling lecture resources
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class LectureResourcesController extends DashboardController
{

    /**
     * Initialize lectures
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
    }

    /**
     * Render Lecture Resource Form
     *
     * @param int $lectureId
     * @return void
     */
    public function index(int $lectureId)
    {
        $lectureId = FatUtility::int($lectureId);
        if ($lectureId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        /* validate lecture id */
        $obj = new LectureSearch();
        if (!$lecture = $obj->getById($lectureId, ['lecture_title', 'lecture_section_id', 'lecture_order', 'lecture_course_id'])) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        $obj = new Lecture($lectureId);
        $data = $obj->getMedia(Lecture::TYPE_RESOURCE_UPLOAD_FILE);
        $data['lecsrc_lecture_id'] = $lectureId;
        $data['lecsrc_course_id'] = $lecture['lecture_course_id'];

        /* get form and fill */
        $frm = $this->getForm(Lecture::TYPE_RESOURCE_UPLOAD_FILE);
        $frm->fill($data);

        /* get resources list */
        $lectureObj = new Lecture($lectureId);
        $resources = $lectureObj->getResources();

        $this->sets([
            'frm' => $frm,
            'lecture' => $lecture,
            'lectureId' => $lectureId,
            'resources' => $resources,
            'filesize' => Afile::getAllowedUploadSize()
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Setup Uploaded Resource File
     *
     * @return json
     */
    public function setup()
    {
        $frm = $this->getForm(Lecture::TYPE_RESOURCE_LIBRARY);
        if (!$post = $frm->getFormDataFromArray(FatApp::getPostedData(), ['resources'])) {
            FatUtility::dieJsonError(current($frm->getValidationErrors()));
        }
        
        if ($post['lecsrc_type'] == Lecture::TYPE_RESOURCE_LIBRARY) {
            $resources = FatApp::getPostedData('resources');
            if (empty($resources) || count($resources) < 1) {
                FatUtility::dieJsonError(Label::getLabel('LBL_PLEASE_SELECT_RESOURCES_FROM_THE_LIST'));
            }
            foreach ($resources as $resource) {
                $lecture = new Lecture($post['lecsrc_lecture_id']);
                if (!$lecture->setupResources(
                    $post['lecsrc_id'],
                    Lecture::TYPE_RESOURCE_LIBRARY,
                    $resource,
                    $post['lecsrc_course_id']
                )) {
                    FatUtility::dieJsonError($resource->getError());
                }
            }
        } else {
            if (empty($_FILES['resource_files']['name'])) {
                FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
            }
            /* save resource file */
            $resource = new Resource();
            if (!$resource->saveFile($_FILES['resource_files'], $this->siteUserId)) {
                FatUtility::dieJsonError($resource->getError());
            }
            $lecture = new Lecture($post['lecsrc_lecture_id']);
            if (
                !$lecture->setupResources(
                    $post['lecsrc_id'],
                    Lecture::TYPE_RESOURCE_UPLOAD_FILE,
                    $resource->getMainTableRecordId(),
                    $post['lecsrc_course_id']
                )
            ) {
                FatUtility::dieJsonError($resource->getError());
            }
        }
        FatUtility::dieJsonSuccess([
            'lectureId' => $post['lecsrc_lecture_id'],
            'msg' => Label::getLabel('LBL_SETUP_SUCCESSFUL')
        ]);
    }

    /**
     * Get Form
     *
     * @return Form
     */
    private function getForm(int $type): Form
    {
        $frm = new Form('frmLectureMedia');

        if ($type == Lecture::TYPE_RESOURCE_LIBRARY) {
            $frm->addCheckBox('', 'resources[]', '');
        } else {
            $frm->addFileUpload(Label::getLabel('LBl_UPLOAD_RESOURCE'), 'resource_files[]', ['id' => 'resource_file']);
        }
        $fld = $frm->addHiddenField('', 'lecsrc_lecture_id');
        $fld = $frm->addHiddenField('', 'lecsrc_type', $type);
        $fld->requirements()->setRequired();
        $fld->requirements()->setInt();
        $fld = $frm->addHiddenField('', 'lecsrc_course_id', $type);
        $fld->requirements()->setRequired();
        $fld->requirements()->setInt();
        $fld = $frm->addHiddenField('', 'lecsrc_id')->requirements()->setInt();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SAVE'));
        return $frm;
    }

    /**
     * Delete binded resource
     *
     * @param int $resourceId
     * @return json
     */
    public function delete(int $resourceId)
    {
        $resourceId = FatUtility::int($resourceId);
        if ($resourceId < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        $srch = new SearchBase(Lecture::DB_TBL_LECTURE_RESOURCE, 'lecsrc');
        $srch->addCondition('lecsrc.lecsrc_id', '=', $resourceId);
        $srch->addCondition('lecsrc.lecsrc_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addMultipleFields(['lecsrc_course_id', 'course_user_id']);
        $srch->joinTable(
            Course::DB_TBL,
            'INNER JOIN',
            'course.course_id = lecsrc.lecsrc_course_id',
            'course'
        );
        if (!$resource = FatApp::getDb()->fetch($srch->getResultSet())) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        if ($resource['course_user_id'] != $this->siteLangId) {
            FatUtility::dieJsonError(Label::getLabel('LBL_UNAUTHORIZED_ACCESS'));
        }

        $db = FatApp::getDb();
        $data = ['lecsrc_deleted' => date('Y-m-d H:i:s')];
        $where = [
            'smt' => 'lecsrc_id = ?',
            'vals' => [$resourceId]
        ];
        if (!$db->updateFromArray(Lecture::DB_TBL_LECTURE_RESOURCE, $data, $where)) {
            FatUtility::dieJsonError($db->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_REMOVED_SUCCESSFULLY'));
    }

    public function resources(int $lectureId)
    {
        $frm = $this->getSearchForm();
        $this->set('frm', $frm);

        /* validate lecture id */
        $obj = new LectureSearch();
        if (!$lecture = $obj->getById($lectureId, ['lecture_course_id'])) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }

        $resrcFrm = $this->getForm(Lecture::TYPE_RESOURCE_LIBRARY);
        $resrcFrm->fill([
            'lecsrc_lecture_id' => $lectureId, 'lecsrc_course_id' => $lecture['lecture_course_id'], 'resources' => []
        ]);
        $this->set('resrcFrm', $resrcFrm);
        $this->_template->render(false, false);
    }


    public function search(int $lectureId)
    {
        $post = FatApp::getPostedData();
        $srch = new ResourceSearch(0, 0, 0);
        $srch->applySearchConditions($post + ['user_id' => $this->siteUserId]);
        $srch->applyPrimaryConditions();
        $srch->addSearchListingFields();
        $srch->addOrder('resrc_id', 'DESC');
        $srch->setPageSize($post['pagesize']);
        $srch->setPageNumber($post['page']);
        $resources = $srch->fetchAndFormat();
        $frm = $this->getForm(Lecture::TYPE_RESOURCE_LIBRARY);
        $frm->fill(['lecsrc_lecture_id' => $lectureId, 'resources' => []]);
        $this->sets([
            'resources' => $resources,
            'post' => $post,
            'recordCount' => $srch->recordCount(),
            'resrcFrm' => $frm,
        ]);
        $loadMore = 0;
        $nextPage = $post['page'];
        if ($post['page'] < ceil($srch->recordCount() / $post['pagesize'])) {
            $loadMore = 1;
            $nextPage = $post['page'] + 1;
        }
        $html = $this->_template->render(false, false, 'lecture-resources/search.php', true);
        FatUtility::dieJsonSuccess([
            'html' => $html,
            'loadMore' => $loadMore,
            'nextPage' => $nextPage,
        ]);
    }
    
    /**
     * Get Search Form
     *
     * @return Form
     */
    private function getSearchForm(): Form
    {
        $frm = new Form('frmResourceSearch');
        $frm->addTextBox(Label::getLabel('LBL_KEYWORD'), 'keyword', '', [
            'placeholder' => Label::getLabel('LBL_KEYWORD'),
            'id' => 'planKeyword'
        ]);
        $frm->addHiddenField('', 'pagesize', AppConstant::PAGESIZE)->requirements()->setInt();
        $frm->addHiddenField('', 'page', 1)->requirements()->setInt();
        $frm->addSubmitButton('', 'btn_submit', Label::getLabel('LBL_SEARCH'));
        return $frm;
    }
}
