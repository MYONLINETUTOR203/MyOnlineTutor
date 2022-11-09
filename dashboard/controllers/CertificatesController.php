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
        $ordcrs = new OrderCourse($data['crspro_ordcrs_id'], $this->siteUserId);
        if (!$ordcrsData = $ordcrs->getOrderCourseById()) {
            FatUtility::exitWithErrorCode(404);
        }
        /* return if course do not offer certificate */
        if (Course::getAttributesById($ordcrsData['ordcrs_course_id'], 'course_certificate') == AppConstant::NO) {
            FatUtility::exitWithErrorCode(404);
        }
        /* check if certificate already generated */
        if (empty($ordcrsData['ordcrs_certificate_number'])) {
            /* get certificate html */
            $content = $this->getContent();
            $cert = new Certificate(
                $ordcrsData['ordcrs_id'],
                'course_completion_certificate',
                $this->siteUserId,
                $this->siteUserType
            );
            if (!$cert->generate($content)) {
                FatUtility::dieWithError($cert->getError());
            }
        }
        FatApp::redirectUser(
            MyUtility::makeUrl('Certificates', 'view', [$data['crspro_ordcrs_id']], CONF_WEBROOT_FRONTEND)
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
        $data = $quiz->get();
        if ($data['quizat_active'] == AppConstant::NO) {
            FatUtility::exitWithErrorCode(404);
        }
        if (!$quiz->canDownloadCertificate()) {
            FatUtility::exitWithErrorCode(404);
        }

        /* check if certificate already generated */
        if (empty($data['quizat_certificate_number'])) {
            /* get content */
            $content = $this->getContent();

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
     * @return string
     */
    private function getContent()
    {
        /* get background and logo images */
        $afile = new Afile(Afile::TYPE_CERTIFICATE_BACKGROUND_IMAGE, 0);
        $backgroundImg = $afile->getFile(0, false);
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
