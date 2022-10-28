<?php

/**
 * This Controller is used for handling course learning process
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class CertificatesController extends MyAppController
{
    /* Image Sizes */
    const SIZE_SMALL = 'SMALL';
    const SIZE_MEDIUM = 'MEDIUM';
    const SIZE_LARGE = 'LARGE';

    /**
     * Initialize Tutorials
     *
     * @param string $action
     */
    public function __construct(string $action)
    {
        parent::__construct($action);
    }

    /**
     * Index
     */
    public function index()
    {
        FatUtility::exitWithErrorCode(404);
    }

    /**
     * Render Certificate Detail Page
     *
     * @param int $ordcrsId
     */
    public function view(int $ordcrsId)
    {
        /* get course and user data */
        $srch = new OrderCourseSearch($this->siteLangId, 0, 0);
        $srch->applyPrimaryConditions();
        $srch->addSearchListingFields();
        $srch->addMultipleFields([
            'teacher.user_id AS teacher_id',
            'learner.user_country_id',
            'orders.order_user_id',
            'ordcrs_certificate_number'
        ]);
        $srch->addCondition('ordcrs_id', '=', $ordcrsId);
        if (!$order = FatApp::getDb()->fetch($srch->getResultSet())) {
            FatUtility::exitWithErrorCode(404);
        }
        if (empty($order['ordcrs_certificate_number'])) {
            FatUtility::exitWithErrorCode(404);
        }
        /* get country name */
        $srch = Country::getSearchObject(false, $this->siteLangId);
        $srch->addCondition('country_id', '=', $order['user_country_id']);
        $srch->addFld('IFNULL(c_l.country_name, c.country_identifier) AS country_name');
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $order['country_name'] = '';
        if ($country = FatApp::getDb()->fetch($srch->getResultSet())) {
            $order['country_name'] = $country['country_name'];
        }
        /* get teacher stats */
        $srch = new SearchBase(TeacherStat::DB_TBL);
        $srch->addCondition('testat_user_id', '=', $order['teacher_id']);
        $srch->addMultipleFields(['testat_ratings', 'testat_reviewes']);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $order['teacher_rating'] = $order['teacher_reviewes'] = 0;
        if ($stats = FatApp::getDb()->fetch($srch->getResultSet())) {
            $order['teacher_rating'] = $stats['testat_ratings'];
            $order['teacher_reviewes'] = $stats['testat_reviewes'];
        }
        $this->sets([
            'ordcrsId' => $ordcrsId,
            'order' => $order,
        ]);
        $this->_template->render();
    }

    /**
     * Render Certificate Detail Page For Quiz
     *
     * @param int $attemptId
     */
    public function evaluation(int $attemptId)
    {
        $quiz = new QuizAttempt($attemptId);
        if (!$data = $quiz->getById()) {
            FatUtility::exitWithErrorCode(404);
        }
        if ($data['quizat_active'] == AppConstant::NO) {
            FatUtility::exitWithErrorCode(404);
        }

        if ($data['quilin_record_type'] == AppConstant::GCLASS) {
            $srch = new GroupClassSearch($this->siteLangId, 0, 0);
            $srch->addCondition('grpcls_id', '=', $data['quilin_record_id']);
            $srch->addMultipleFields([
                'IFNULL(gclang.grpcls_title, grpcls.grpcls_title) as session_title',
                'grpcls_slug',
                'grpcls_parent',
                'teacher.user_first_name as teacher_first_name',
                'teacher.user_last_name as teacher_last_name',
                'teacher.user_username as teacher_username',
                'teacher.user_username',
                'testat.testat_ratings',
                'testat.testat_reviewes'
            ]);
            $srch->setPageSize(1);
            $session = FatApp::getDb()->fetch($srch->getResultSet());
            $learner = User::getAttributesById($data['quizat_user_id'], [
                'user_first_name as learner_first_name', 'user_last_name as learner_last_name'
            ]);
            if ($session['grpcls_parent'] > 0) {
                $session['grpcls_slug'] = GroupClass::getAttributesById($session['grpcls_parent'], 'grpcls_slug');
            }
            $session = $session + $learner;
        } else {
            $srch = new LessonSearch($this->siteLangId, 0, 0);
            $srch->joinTable(User::DB_TBL_STAT, 'INNER JOIN', 'testat.testat_user_id = teacher.user_id', 'testat');
            $srch->addCondition('ordles_id', '=', $data['quilin_record_id']);
            $srch->setPageSize(1);
            $srch->addMultipleFields([
                'ordles_tlang_id',
                'ordles_duration',
                'teacher.user_first_name as teacher_first_name',
                'teacher.user_last_name as teacher_last_name',
                'teacher.user_username as teacher_username',
                'testat.testat_ratings',
                'testat.testat_reviewes',
                'learner.user_first_name as learner_first_name',
                'learner.user_last_name as learner_last_name',
            ]);
            $session = FatApp::getDb()->fetch($srch->getResultSet());
            $title = Label::getLabel('LBL_{teach-lang},_{n}_minutes_of_Lesson');
            $session['session_title'] = str_replace(
                ['{teach-lang}', '{n}'],
                [
                    TeachLanguage::getLangById($session['ordles_tlang_id'], $this->siteLangId),
                    $session['ordles_duration']
                ],
                $title
            );
        }

        $this->sets([
            'id' => $attemptId,
            'data' => $data,
            'session' => $session,
        ]);
        $this->_template->render();
    }
}
