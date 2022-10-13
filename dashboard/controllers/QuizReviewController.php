<?php

use GuzzleHttp\Psr7\Query;

/**
 * This Controller is used for user quiz solving
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class QuizReviewController extends DashboardController
{
    /**
     * Initialize
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
    }

    /**
     * Render Instructions Form
     *
     * @param int $id
     */
    public function index(int $id)
    {
        $id = FatApp::getPostedData('id', FatUtility::VAR_INT, 0);
        if ($id < 1) {
            FatUtility::dieJsonError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        
        $quiz = new QuizAttempt($id);
        $data = $quiz->getById();
        if (empty($data)) {
            FatUtility::exitWithErrorCode(404);
        }
        
        if (
            ($this->siteUserType == User::LEARNER && $data['quizat_user_id'] != $this->siteUserId) &&
            ($this->siteUserType == User::TEACHER && $data['quilin_user_id'] != $this->siteUserId)
        ) {
            FatUtility::dieJsonError(Label::getLabel('LBL_UNAUTHORIZED_ACCESS'));
        }

        if ($data['quizat_status'] != QuizAttempt::STATUS_COMPLETED) {
            Message::addErrorMessage(Label::getLabel('LBL_ACCESS_TO_PENDING_OR_IN_PROGRESS_QUIZZES_IS_NOT_ALLOWED'));
            if ($data['quilin_record_type'] == AppConstant::LESSON) {
                FatApp::redirectUser(MyUtility::makeUrl('Lessons'));
            } elseif ($data['quilin_record_type'] == AppConstant::GCLASS) {
                FatApp::redirectUser(MyUtility::makeUrl('Classes'));
            } else {
                $controller = ($this->siteUserType == User::LEARNER) ? 'Learner' : 'Teacher';
                FatApp::redirectUser(MyUtility::makeUrl($controller));
            }
        }

        $this->set('data', $data);
        $this->_template->render();
    }
}
