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
     * Generate Certificate
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
        if (!$data['crspro_completed']) {
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
            /* generate certificate */
            $cert = new Certificate($data['crspro_ordcrs_id'], $this->siteUserId, $this->siteLangId);
            if (!$cert->setup()) {
                /* reset certificate number */
                $ordcrs->setFldValue('ordcrs_certificate_number', '');
                $ordcrs->save();
                FatUtility::dieWithError($cert->getError());
            }
        }
        FatApp::redirectUser(MyUtility::makeUrl('Certificates', 'view', [$data['crspro_ordcrs_id']], CONF_WEBROOT_FRONTEND));
    }
}
