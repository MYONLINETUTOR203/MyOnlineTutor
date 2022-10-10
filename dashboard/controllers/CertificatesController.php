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
     * @return void
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
            $content = $this->getContent(Certificate::TYPE_COURSE);
            $cert = new Certificate($ordcrsData['ordcrs_id'], Certificate::TYPE_QUIZ, $this->siteUserId, $this->siteUserType);
            if (!$cert->generateCertificate($content)) {
                FatUtility::dieWithError($cert->getError());
            }
        }
        FatApp::redirectUser(MyUtility::makeUrl('Certificates', 'view', [$data['crspro_ordcrs_id']], CONF_WEBROOT_FRONTEND));
    }

    public function quiz(int $id)
    {
        if ($id < 0 || $_SESSION['certificate_type'] != Certificate::TYPE_QUIZ) {
            FatUtility::exitWithErrorCode(404);
        }
        $quiz = new QuizAttempt($id, $this->siteUserId);
        $data = $quiz->getById();
        if (
            empty($data) ||
            $data['quizat_user_id'] != $this->siteUserId ||
            $data['quizat_status'] != QuizAttempt::STATUS_COMPLETED ||
            $data['quizat_active'] == AppConstant::NO ||
            $data['quilin_certificate'] == AppConstant::NO
        ) {
            FatUtility::exitWithErrorCode(404);
        }

        /* check if certificate already generated */
        if (!empty($data['quizat_certificate_number'])) {
            /* get certificate html */
            $content = $this->getContent();
            $cert = new Certificate($id, Certificate::TYPE_QUIZ, $this->siteUserId, $this->siteUserType);
            if (!$cert->generateCertificate($content)) {
                FatUtility::dieWithError($cert->getError());
            }
        }
        FatApp::redirectUser(MyUtility::makeUrl('Certificates', 'evaluationCertificate', [$id], CONF_WEBROOT_FRONTEND));
    }

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

    
    // public function getCertificateContent(array $data)
    // {
    //     /* get course data */
    //     $cert = new Certificate(0, $this->siteUserId, $this->siteLangId);
    //     if (!$data = $cert->getDataForCertificate($data['ordcrs_id'])) {
    //         FatUtility::dieWithError(Label::getLabel('LBL_CONTENT_NOT_FOUND'));
    //     }
    //     /* get certificate template */
    //     $data['cert_number'] = $data['ordcrs_certificate_number'];
    //     $data['lang_id'] = $this->siteLangId;
    //     if (!$content = $cert->getFormattedContent($data)) {
    //         FatUtility::dieWithError($cert->getError());
    //     }
    //     /* get background and logo images */
    //     $afile = new Afile(Afile::TYPE_CERTIFICATE_BACKGROUND_IMAGE, 0);
    //     $backgroundImg = $afile->getFile(0, false);
    //     if (!isset($backgroundImg['file_path']) || !file_exists(CONF_UPLOADS_PATH . $backgroundImg['file_path'])) {
    //         $backgroundImg = CONF_INSTALLATION_PATH . 'public/images/noimage.jpg';
    //     } else {
    //         $backgroundImg = CONF_UPLOADS_PATH . $backgroundImg['file_path'];
    //     }
    //     $this->set('backgroundImg', $backgroundImg);
    //     $afile = new Afile(Afile::TYPE_CERTIFICATE_LOGO, $this->siteLangId);
    //     $logoImg = $afile->getFile(0, false);
    //     if (!isset($logoImg['file_path']) || !file_exists(CONF_UPLOADS_PATH . $logoImg['file_path'])) {
    //         $logoImg = CONF_INSTALLATION_PATH . 'public/images/noimage.jpg';
    //     } else {
    //         $logoImg = CONF_UPLOADS_PATH . $logoImg['file_path'];
    //     }
    //     $this->set('logoImg', $logoImg);
    //     $this->set('content', $content);
    //     $this->set('layoutDir', Language::getAttributesById($this->siteLangId, 'language_direction'));
    //     return $this->_template->render(false, false, 'certificates/generate.php', true);
    // }
}
