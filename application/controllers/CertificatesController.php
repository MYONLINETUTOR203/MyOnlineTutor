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
            'teacher.user_first_name AS teacher_id',
            'learner.user_country_id',
            'orders.order_user_id',
        ]);
        $srch->addCondition('ordcrs_id', '=', $ordcrsId);
        if (!$order = FatApp::getDb()->fetch($srch->getResultSet())) {
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
     * Create layout for generating certificate image & Pdf
     *
     * @param string $token
     */
    public function generate(string $token)
    {
        $tokenObj = new TempToken();
        if (!$tokenData = $tokenObj->verify($token)) {
            FatUtility::dieWithError(Label::getLabel('LBL_THIS_URL_HAS_EXPIRED'));
        }
        if (
            !$certificate = Certificate::getAttributesById($tokenData['tmptok_ordcrs_id'], [
                'ordcrs_certificate_number',
            ])
        ) {
            FatUtility::dieWithError(Label::getLabel('LBL_INVALID_REQUEST'));
        }
        $ordcrsId = $tokenData['tmptok_ordcrs_id'];
        /* get course data */
        $cert = new Certificate(0, $this->siteUserId, $this->siteLangId);
        if (!$data = $cert->getDataForCertificate($ordcrsId)) {
            FatUtility::dieWithError(Label::getLabel('LBL_CONTENT_NOT_FOUND'));
        }
        /* get certificate template */
        $data['cert_number'] = $certificate['ordcrs_certificate_number'];
        if (!$content = $cert->getFormattedContent($data)) {
            FatUtility::dieWithError(Label::getLabel('LBL_CONTENT_NOT_FOUND'));
        }
        $this->set('content', $content);
        $this->set('layoutDir', Language::getAttributesById($data['user_lang_id'], 'language_direction'));
        $this->_template->render(false, false);
    }

    /**
     * Generating sample certificate with dummy data for testing at admin end.
     */
    public function generateSample()
    {
        /* Create dummy data */
        $data = [
            'learner_first_name' => 'Martha',
            'learner_last_name' => 'Christopher',
            'teacher_first_name' => 'John',
            'teacher_last_name' => 'Doe',
            'course_title' => 'English Language Learning - Beginners',
            'course_tlang_name' => 'English',
            'user_lang_id' => $this->siteLangId,
            'cert_number' => 'YC_h34uwh9e72w',
            'crspro_completed' => date('Y-m-d H:i:s'),
        ];
        $cert = new Certificate(0, $this->siteUserId, $this->siteLangId);
        $content = $cert->getFormattedContent($data);
        $this->sets([
            'content' => $content,
            'layoutDir' => Language::getAttributesById($this->siteLangId, 'language_direction')
        ]);
        $this->_template->render(false, false, 'certificates/generate.php');
    }
}
