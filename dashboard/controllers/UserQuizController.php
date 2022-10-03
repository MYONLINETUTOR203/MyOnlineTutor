<?php

/**
 * This Controller is used for user quiz solving
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class UserQuizController extends DashboardController
{
    /**
     * Initialize
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
        if ($this->siteUserType != User::LEARNER) {
            FatUtility::exitWithErrorCode(404);
        }
    }

    /**
     * Render Instructions Form
     */
    public function index(int $id)
    {
        $quiz = new QuizAttempt($id);
        $data = $quiz->getById();
        if (empty($data)) {
            FatUtility::exitWithErrorCode(404);
        }
        $this->set('data', $data);
        $this->_template->render();
    }

    /**
     * Start Quiz
     */
    public function start()
    {
        $id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($id < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $attempt = new QuizAttempt($id, $this->siteUserId, $this->siteUserType);
        if (!$attempt->start()) {
            FatUtility::dieJsonError($attempt->getError());
        }
        FatUtility::dieJsonSuccess(Label::getLabel('LBL_QUIZ_STARTED_SUCCESSFULLY'));
    }

    /**
     * Quiz questions forms
     *
     * @param int $id
     */
    public function questions(int $id)
    {
        $quiz = new QuizAttempt($id);
        $data = $quiz->getById();
        if (empty($data)) {
            FatUtility::exitWithErrorCode(404);
        }
        $this->set('data', $data);
        $this->_template->render();
    }
}
