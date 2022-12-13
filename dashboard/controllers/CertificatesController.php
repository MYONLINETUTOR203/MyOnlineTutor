<?php

/**
 * This Controller is used for handling course learning process
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class CertificatesController extends DashboardController
{
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
     * Generate Certificate for course
     *
     * @param int $progressId
     */
    public function index($progressId)
    {
        if ($progressId < 1) {
            FatUtility::exitWithErrorCode(404);
        }
        $data = CourseProgress::getAttributesById($progressId, ['crspro_completed', 'crspro_ordcrs_id']);
        /* return if course not completed */
        if (empty($data['crspro_completed'])) {
            FatUtility::exitWithErrorCode(404);
        }

        $srch = new OrderCourseSearch($this->siteLangId, $this->siteUserId, $this->siteUserType);
        $srch->applyPrimaryConditions();
        $srch->addCondition('ordcrs.ordcrs_id', '=', $data['crspro_ordcrs_id']);
        $srch->addCondition('ordcrs.ordcrs_status', '!=', OrderCourse::CANCELLED);
        $srch->addMultipleFields([
            'ordcrs.ordcrs_id', 'ordcrs.ordcrs_course_id', 'orders.order_user_id', 'ordcrs.ordcrs_certificate_number',
            'course_quilin_id', 'course.course_id', 'course_certificate', 'course_certificate_type', 'ordcrs_status',
            'crspro_completed'
        ]);
        $ordcrsData = $srch->fetchAndFormat(true);
        $ordcrsData = current($ordcrsData);
        if (empty($ordcrsData)) {
            FatUtility::exitWithErrorCode(404);
        }

        /* return if course do not offer certificate */
        if ($ordcrsData['can_download_certificate'] === false) {
            FatUtility::exitWithErrorCode(404);
        }

        $code = 'course_completion_certificate';
        $id = $ordcrsData['ordcrs_id'];
        $certificateNo = $ordcrsData['ordcrs_certificate_number'];
        if ($ordcrsData['course_certificate_type'] == Certificate::TYPE_COURSE_EVALUATION) {
            $certificateNo = $ordcrsData['quizat_certificate_number'];
            $code = 'course_evaluation_certificate';
            $id = $ordcrsData['quizat_id'];
        }

        /* check if certificate already generated */
        if (empty($certificateNo)) {
            /* get certificate html */
            $content = $this->getContent($code);
            $cert = new Certificate($id, $code, $this->siteUserId, $this->siteLangId);
            if (!$cert->generate($content)) {
                FatUtility::dieWithError($cert->getError());
            }
        }

        $action = ($ordcrsData['course_certificate_type'] == Certificate::TYPE_COURSE_EVALUATION) ? 'evaluation' : 'view';
        FatApp::redirectUser(
            MyUtility::makeUrl('Certificates', $action, [$id], CONF_WEBROOT_FRONTEND)
        );
    }

    /**
     * Generate Certificate for quiz
     *
     * @param int $id
     */
    public function quiz(int $id)
    {
        if ($id < 0 || $_SESSION['certificate_type'] != Certificate::TYPE_QUIZ_EVALUATION) {
            FatUtility::exitWithErrorCode(404);
        }
        $quiz = new QuizAttempt($id, $this->siteUserId);
        if (!$quiz->validate(QuizAttempt::STATUS_COMPLETED)) {
            FatUtility::exitWithErrorCode(404);
        }
        $data = $quiz->getData();
        if ($data['quizat_active'] == AppConstant::NO) {
            FatUtility::exitWithErrorCode(404);
        }
        if (!$quiz->canDownloadCertificate()) {
            FatUtility::exitWithErrorCode(404);
        }

        /* check if certificate already generated */
        if (empty($data['quizat_certificate_number'])) {
            /* get content */
            $content = $this->getContent('evaluation_certificate');

            /* generate */
            $cert = new Certificate($id, 'evaluation_certificate', $this->siteUserId, $this->siteLangId);
            if (!$cert->generate($content)) {
                FatUtility::dieWithError($cert->getError());
            }
        }
        FatApp::redirectUser(MyUtility::makeUrl('Certificates', 'evaluation', [$id], CONF_WEBROOT_FRONTEND));
    }

    /**
     * Get html content for certificate
     *
     * @param string $code
     * @return string
     */
    private function getContent(string $code)
    {
        $srch = new SearchBase(CertificateTemplate::DB_TBL);
        $srch->addCondition('certpl_code', '=', $code);
        $srch->addCondition('certpl_lang_id', '=', $this->siteLangId);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        $srch->addFld('certpl_id');
        $template = FatApp::getDb()->fetch($srch->getResultSet());


        /* get background and logo images */
        $afile = new Afile(Afile::TYPE_CERTIFICATE_BACKGROUND_IMAGE, 0);
        $backgroundImg = $afile->getFile($template['certpl_id'], false);
        if (!isset($backgroundImg['file_path']) || !file_exists(CONF_UPLOADS_PATH . $backgroundImg['file_path'])) {
            $backgroundImg = CONF_INSTALLATION_PATH . 'public/images/noimage.jpg';
        } else {
            $backgroundImg = CONF_UPLOADS_PATH . $backgroundImg['file_path'];
        }
        $this->set('backgroundImg', $backgroundImg);

        $afile = new Afile(Afile::TYPE_CERTIFICATE_LOGO, $this->siteLangId);
        $logoImg = $afile->getFile(0, false);
        if (!isset($logoImg['file_path']) || !file_exists(CONF_UPLOADS_PATH . $logoImg['file_path'])) {
            $logoImg = CONF_INSTALLATION_PATH . 'public/images/noimage.jpg';
        } else {
            $logoImg = CONF_UPLOADS_PATH . $logoImg['file_path'];
        }
        $this->set('logoImg', $logoImg);
        
        $this->set('layoutDir', Language::getAttributesById($this->siteLangId, 'language_direction'));
        $content = $this->_template->render(false, false, 'certificates/generate.php', true);
        return $content;
    }
}
