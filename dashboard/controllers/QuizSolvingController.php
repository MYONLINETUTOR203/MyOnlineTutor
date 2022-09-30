<?php

/**
 * This Controller is used for quiz solving
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class QuizSolvingController extends DashboardController
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
        $quiz = new QuizLinked($id);
        if (!$data = $quiz->getById()) {
            FatUtility::exitWithErrorCode(404);
        }
        $this->set('data', $data);
        $this->_template->render();
    }
}
