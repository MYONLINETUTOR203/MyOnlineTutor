<?php

/**
 * Quizzes Controller
 *
 * @package YoCoach
 * @author Fatbit Team
 */

class QuizzesController extends AdminBaseController
{

    /**
     * Initialize Categories
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
        $this->objPrivilege->canViewQuizzes();
    }

    /**
     * Render Search Form
     */
    public function index()
    {
        $frm = QuizSearch::getSearchForm();
        $frm->fill(['pagesize' => FatApp::getConfig('CONF_ADMIN_PAGESIZE')]);
        $this->set("frmSearch", $frm);
        $this->_template->render();
    }

    /**
     * Search & List Quizzes
     */
    public function search()
    {
        $form = QuizSearch::getSearchForm();
        if (!$post = $form->getFormDataFromArray(FatApp::getPostedData())) {
            FatUtility::dieJsonError(current($form->getValidationErrors()));
        }
        $srch = new QuizSearch($this->siteLangId, 0, User::SUPPORT);
        $srch->applySearchConditions($post);
        $srch->applyPrimaryConditions();
        $srch->addSearchListingFields();
        $srch->setPageSize($post['pagesize']);
        $srch->setPageNumber($post['pageno']);
        $srch->addOrder('quiz_active', 'DESC');
        $srch->addOrder('quiz_id', 'DESC');
        $data = $srch->fetchAndFormat();
        $this->sets([
            'arrListing' => $data,
            'postedData' => $post,
            'page' => $post['pageno'],
            'post' => $post,
            'pageSize' => $post['pagesize'],
            'pageCount' => $srch->pages(),
            'recordCount' => $srch->recordCount(),
        ]);
        $this->_template->render(false, false);
    }

    /**
     * Render Quiz View
     *
     * @param int $quizId
     */
    public function view(int $quizId)
    {
        $srch = new QuizSearch($this->siteLangId, 0, User::SUPPORT);
        $srch->addCondition('quiz_id', '=', $quizId);
        $srch->applyPrimaryConditions();
        $srch->addSearchListingFields();
        $data = $srch->fetchAndFormat();
        if (empty($data)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_QUIZ_NOT_FOUND'));
        }
        $this->set('quiz', current($data));
        $this->_template->render(false, false);
    }

    /**
     * Render Quiz Questions Listing
     *
     * @param int $quizId
     */
    public function questions(int $quizId)
    {
        $data = Quiz::getById($quizId);
        if (empty($data)) {
            FatUtility::dieJsonError(Label::getLabel('LBL_QUIZ_NOT_FOUND'));
        }

        $srch = new QuizQuestionSearch($this->siteLangId, 0, User::SUPPORT);
        $srch->addCondition('quique_quiz_id', '=', $quizId);
        $srch->applyPrimaryConditions();
        $srch->addSearchListingFields();
        $srch->addCondition('cate.cate_status', '=', AppConstant::ACTIVE);
        $srch->addCondition('cate.cate_deleted', 'IS', 'mysql_func_NULL', 'AND', true);
        $srch->addOrder('quique_order', 'ASC');
        $this->set('questions', $srch->fetchAndFormat());
        $this->_template->render(false, false);
    }
}
