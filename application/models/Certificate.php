<?php

use JonnyW\PhantomJs\Client;

/**
 * This class is used to handle certificates
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class Certificate extends MyAppModel
{
    const DB_TBL = 'tbl_order_courses';
    const DB_TBL_PREFIX = 'ordcrs_';
    const CERTIFICATE_NO_PREFIX = 'YC_';
    
    private $userId;
    private $langId;

    /**
     * Initialize certificate
     *
     * @param int $id
     */
    public function __construct(int $id = 0, int $userId = 0, int $langId = 0)
    {
        $this->userId = $userId;
        $this->langId = $langId;
        parent::__construct(static::DB_TBL, 'ordcrs_id', $id);
    }

    /**
     * Setup certificate data * generate certificate
     *
     * @return bool
     */
    public function setup() :bool
    {
        $db = FatApp::getDb();
        $this->setFldValue('ordcrs_certificate_number', static::CERTIFICATE_NO_PREFIX . uniqid());
        if (!$this->save()) {
            $this->error = Label::getLabel('LBL_AN_ERROR_HAS_OCCURRED_WHILE_GENERATING_CERTIFICATE!');
            return false;
        }
        $ordcrsId = $this->getMainTableRecordId();
        $db->startTransaction();
        $tokenObj = new TempToken();
        /* remove previous token */
        if (!$tokenObj->removeToken($ordcrsId)) {
            $db->rollbackTransaction();
            return false;
        }
        $token = $ordcrsId . '_' . FatUtility::getRandomString(15);
        if (!$tokenObj->addToken($ordcrsId, $token)) {
            $db->rollbackTransaction();
            return false;
        }
        if (!$this->generateCertificate($token)) {
            $db->rollbackTransaction();
            return false;
        }
        $db->commitTransaction();
        return true;
    }

    /**
     * Generate Certificate Image & PDF
     *
     * @param string $token
     * @return bool
     */
    private function generateCertificate(string $token)
    {
        $url = MyUtility::generateFullUrl('Certificates', 'generate', [$token], CONF_WEBROOT_FRONTEND);
        if (!$this->createImage($url)) {
            return false;
        }
        if (!$this->createPdf()) {
            $this->error = Label::getLabel('LBL_AN_ERROR_HAS_OCCURRED_WHILE_GENERATING_CERTIFICATE!');
            return false;
        }
        return true;
    }
    
    /**
     * Get Course & User details for certificate
     *
     * @param int     $ordcrsId
     * @return array
     */
    public function getDataForCertificate(int $ordcrsId)
    {
        $srch = new OrderCourseSearch($this->langId, $this->userId, 0);
        $srch->joinTable(CourseProgress::DB_TBL, 'INNER JOIN', 'ordcrs.ordcrs_id = crspro.crspro_ordcrs_id', 'crspro');
        $srch->joinTable(TeachLanguage::DB_TBL, 'INNER JOIN', 'tlang.tlang_id = course.course_tlang_id', 'tlang');
        $srch->joinTable(
            TeachLanguage::DB_TBL_LANG,
            'LEFT JOIN',
            'tlang.tlang_id = tlanglang.tlanglang_tlang_id AND tlanglang.tlanglang_lang_id = ' . $this->langId,
            'tlanglang'
        );
        $srch->applyPrimaryConditions();
        $srch->addSearchListingFields();
        $srch->addMultipleFields([
            'crspro_completed',
            'IFNULL(tlanglang.tlang_name, tlang.tlang_identifier) AS course_tlang_name',
            'learner.user_lang_id'
        ]);
        $srch->addCondition('ordcrs_id', '=', $ordcrsId);
        return FatApp::getDb()->fetch($srch->getResultSet());
    }

    /**
     * Get formatted certificate content
     *
     * @param array $data
     * @return array
     */
    public function getFormattedContent(array $data)
    {
        $srch = CertificateTemplate::getSearchObject($data['user_lang_id']);
        $srch->addCondition('certpl_code', '=', 'course_completion_certificate');
        if (!$template = FatApp::getDb()->fetch($srch->getResultSet())) {
            $this->error = Label::getLabel('LBL_AN_ERROR_HAS_OCCURRED_WHILE_GENERATING_CERTIFICATE!');
            return false;
        }
        $content = $template['certpl_body'];
        $content = str_replace(
            [
                '{learner-name}',
                '{teacher-name}',
                '{course-name}',
                '{course-language}',
                '{course-completed-date}',
                '{certificate-number}'
            ],
            [
                ucwords($data['learner_first_name'] . ' ' . $data['learner_last_name']),
                ucwords($data['teacher_first_name'] . ' ' . $data['teacher_last_name']),
                '<span class=\"courseNameJs\">' . $data['course_title'] . '</span>',
                $data['course_tlang_name'],
                MyDate::formatDate($data['crspro_completed']),
                $data['cert_number']
            ],
            $content
        );
        return json_decode($content, true);
    }

    public function generateSampleCertificate($filepath)
    {
        $url = MyUtility::generateFullUrl('Certificates', 'generateSample', [], CONF_WEBROOT_FRONTEND);
        if (!$this->createImage($url, $filepath, true)) {
            return false;
        }

        return true;
    }

    /**
     * Render Image in PDF & Save File
     *
     * @return string
     */
    private function createPdf()
    {
        $filename = 'certificate' . $this->getMainTableRecordId();
        $file =  $this->getFilePath() . $filename;
        $afile = new Afile(Afile::TYPE_CERTIFICATE_BACKGROUND_IMAGE);
        $sizes = $afile->getImageSizes('LARGE');
        $pdf = new TCPDF();
        $pdf->AddPage();
        $pdf->Image(CONF_UPLOADS_PATH . $file . '.jpg', 0, 0, $sizes[0], $sizes[1], 'JPG', '', '', true, 150, 'C', false, false, 1, true, false, true);
        /* get file path and save */
        $pdf->Output(CONF_UPLOADS_PATH . $file . '.pdf', 'F');
        /* save file data */
        if (!$this->saveFile(Afile::TYPE_CERTIFICATE_PDF, $filename . '.pdf', $file . '.pdf')) {
            $this->error = Label::getLabel('LBL_AN_ERROR_HAS_OCCURRED_WHILE_GENERATING_CERTIFICATE!');
            return false;
        }
        return true;
    }

    /**
     * Create Certificate Image
     *
     * @param string $url
     * @param string $filepath
     * @return string|bool
     */
    private function createImage(string $url, string $filepath = '', $isSample = false)
    {
        $afile = new Afile(Afile::TYPE_CERTIFICATE_BACKGROUND_IMAGE);
        $sizes = $afile->getImageSizes('LARGE');
        $width  = $sizes[0];
        $height = $sizes[1];
        $top    = 0;
        $left   = 0;

        $client = Client::getInstance();
        $request = $client->getMessageFactory()->createCaptureRequest($url, 'GET');

        $filename = 'certificate' . $this->getMainTableRecordId() . '.jpg';
        if (empty($filepath)) {
            $filepath = $this->getFilePath() . $filename;
        } else {
            $filepath = $filepath . $filename;
        }
        $request->setOutputFile(str_replace('\\', '/', CONF_UPLOADS_PATH . $filepath));
        $request->setViewportSize($width, $height);
        $request->setCaptureDimensions($width, $height, $top, $left);
        $response = $client->getMessageFactory()->createResponse();

        $executablePath = '';
        if (PHP_OS_FAMILY === "Windows") {
            $executablePath = CONF_UPLOADS_PATH . 'bin/phantomjs.exe';
        } elseif (PHP_OS_FAMILY === "Linux") {
            $executablePath = CONF_UPLOADS_PATH . 'bin/phantomjs';
        }
        if (empty($executablePath)) {
            $this->error = Label::getLabel('LBL_PHANTOMJS_EXECUTABLE_FILE_NOT_FOUND');
            return false;
        }
        
        if (!file_exists($executablePath)) {
            $this->error = Label::getLabel('LBL_PHANTOMJS_EXECUTABLE_FILE_NOT_FOUND');
            return false;
        }
        $client->getEngine()->setPath($executablePath);

        $client->send($request, $response);

        if ($response->getStatus() !== 200) {
            $this->error = Label::getLabel('LBL_AN_ERROR_HAS_OCCURRED_WHILE_GENERATING_CERTIFICATE!');
            return false;
        }
        /* save file data */
        if ($isSample == false) {
            if (!$this->saveFile(Afile::TYPE_CERTIFICATE_IMAGE, $filename, $filepath)) {
                $this->error = Label::getLabel('LBL_AN_ERROR_HAS_OCCURRED_WHILE_GENERATING_CERTIFICATE!');
                return false;
            }
        }
        return true;
    }

    /**
     * Save created files data
     *
     * @param int $type
     * @param string $filename
     * @param string $path
     * @return bool
     */
    private function saveFile(int $type, string $filename, string $path)
    {
        $record = new TableRecord(Afile::DB_TBL);
        $record->assignValues([
            'file_type' => $type,
            'file_record_id' => $this->getMainTableRecordId(),
            'file_name' => $filename,
            'file_path' => $path,
            'file_order' => 0,
            'file_added' => date('Y-m-d H:i:s')
        ]);
        if (!$record->addNew()) {
            $this->error = $record->getError();
            return false;
        }
        return true;
    }

    /**
     * function to get path for file uploading
     *
     * @return string
     */
    private function getFilePath()
    {
        $uploadPath = CONF_UPLOADS_PATH;
        $filePath = date('Y') . '/' . date('m') . '/';
        if (!file_exists($uploadPath . $filePath)) {
            mkdir($uploadPath . $filePath, 0777, true);
        }
        return $filePath;
    }
}
