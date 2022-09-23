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
    }

    /**
     * Generate Certificate Image & PDF
     *
     * @param string $token
     * @return bool
     */
    public function generateCertificate($content, $filename, $preview = false)
    {
        $mpdf = new \Mpdf\Mpdf([
            'orientation' => 'L',
            'margin_left' => 0,
            'margin_right' => 0,
            'margin_top' => 0,
            'margin_bottom' => 0,
            'mirrorMargins' => 0,
            'autoLangToFont' => true,
            'autoScriptToLang' => true,
        ]);
        $mpdf->SetDirectionality(Language::getAttributesById($this->langId, 'language_direction'));
        $mpdf->WriteHTML($content);
        $path = $this->getFilePath() . $filename;
        if ($preview == false) {
            $mpdf->Output(CONF_UPLOADS_PATH . $path, \Mpdf\Output\Destination::FILE);
            if (!$this->saveFile(Afile::TYPE_CERTIFICATE_PDF, $filename, $path)) {
                $this->error = Label::getLabel('LBL_AN_ERROR_HAS_OCCURRED_WHILE_GENERATING_CERTIFICATE!');
                return false;
            }
        } else {
            $mpdf->Output($filename, \Mpdf\Output\Destination::INLINE);
        }
        return true;
    }
    
   
    /**
     * Get formatted certificate content
     *
     * @param array $data
     * @return array
     */
    public function getFormattedContent(array $data)
    {
        $srch = CertificateTemplate::getSearchObject($data['lang_id']);
        $srch->addCondition('certpl_code', '=', 'evaluation_certificate');
        if (!$template = FatApp::getDb()->fetch($srch->getResultSet())) {
            $this->error = Label::getLabel('LBL_AN_ERROR_HAS_OCCURRED_WHILE_GENERATING_CERTIFICATE!');
            return false;
        }
        $content = $template['certpl_body'];
        $content = str_replace(
            [
                '{learner-name}',
                '{teacher-name}',
                '{quiz-name}',
                '{quiz-language}',
                '{quiz-completed-date}',
                '{certificate-number}',
                '{quiz-duration}'
            ],
            [
                ucwords($data['learner_first_name'] . ' ' . $data['learner_last_name']),
                ucwords($data['teacher_first_name'] . ' ' . $data['teacher_last_name']),
                '<span class=\"courseNameJs\">' . $data['quiz_title'] . '</span>',
                $data['quiz_clang_name'],
                MyDate::formatDate($data['quiz_completed']),
                $data['cert_number'],
                MyUtility::convertDuration($data['quiz_duration'])

            ],
            $content
        );
        return json_decode($content, true);
    }

    /**
     * Save created files data
     *
     * @param int $type
     * @param string $filename
     * @param string $path
     * @return bool
     */
    public function saveFile(int $type, string $filename, string $path)
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
        /* delete old file */
        $fileId = $record->getId();
        $stmt = [
            'vals' => [$type, 0, $this->getMainTableRecordId(), $fileId],
            'smt' => 'file_type = ? AND file_lang_id = ? AND file_record_id = ? AND file_id != ?'
        ];
        FatApp::getDb()->deleteRecords(Afile::DB_TBL, $stmt);
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
