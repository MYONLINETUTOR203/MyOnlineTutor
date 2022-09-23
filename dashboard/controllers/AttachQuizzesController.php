<?php

/**
 * This Controller is used for attach quizzes with classes, lessons and courses
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class AttachQuizzesController extends DashboardController
{
    /**
     * Initialize
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
        if ($this->siteUserType != User::TEACHER) {
            FatUtility::exitWithErrorCode(404);
        }
    }
    /**
     * Render Search Form
     */
    public function index()
    {
        $frm = QuizSearch::getSearchForm();

        $recordId = FatApp::getPostedData('recordId', FatUtility::VAR_INT, 0);
        $recordType = FatApp::getPostedData('recordType', FatUtility::VAR_INT, 0);

        if (!in_array($recordType, [AppConstant::LESSON, AppConstant::GCLASS, AppConstant::COURSE])) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_DATA_SENT'));
        }

        /* @TODO: validate record id */

        $quizFrm = QuizSearch::getQuizForm();
        $quizFrm->fill(['quilin_record_id' => $recordId, 'quilin_record_type' => $recordType]);

        $this->sets([
            'quizFrm' => $quizFrm,
            'frm' => $frm
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Search & List Quizzes
     */
    public function search()
    {
        $frm = QuizSearch::getSearchForm();
        $post = $frm->getFormDataFromArray(FatApp::getPostedData());

        $srch = new QuizSearch($this->siteLangId, $this->siteUserId, $this->siteUserType);
        $srch->applyPrimaryConditions();
        $srch->applySearchConditions($post);
        $srch->addSearchListingFields();
        $srch->setPageSize($post['pagesize']);
        $srch->setPageNumber($post['pageno']);
        $srch->addOrder('quiz_active', 'DESC');
        $srch->addOrder('quiz_id', 'DESC');
        $srch->addCondition('quiz_active', '=', AppConstant::YES);
        $srch->addCondition('quiz_status', '=', Quiz::STATUS_PUBLISHED);

        $this->sets([
            'quizzes' => $srch->fetchAndFormat(),
            'types' => Quiz::getTypes(),
            'status' => Quiz::getStatuses(),
            'post' => $post,
            'recordCount' => $srch->recordCount(),
        ]);

        $html = $this->_template->render(false, false, 'attach-quizzes/search.php', true, true);
        $loadMore = 0;
        $nextPage = $post['pageno'];
        if ($post['pageno'] < ceil($srch->recordCount() / $post['pagesize'])) {
            $loadMore = 1;
            $nextPage = $post['pageno'] + 1;
        }

        FatUtility::dieJsonSuccess([
            'html' => $html,
            'loadMore' => $loadMore,
            'nextPage' => $nextPage
        ]);
    }
}
