<?php
/**
 * This class is used to handle certificates
 *
 * @package YoCoach
 * @author Fatbit Team
 */
class Certificate extends MyAppModel
{
    const CERTIFICATE_NO_PREFIX = 'YC_';

    const TYPE_COURSE = 1;
    const TYPE_QUIZ = 2;
    
    private $userId;
    private $langId;
    private $id;
    private $type;

    /**
     * Initialize certificate
     *
     * @param int $id
     */
    public function __construct(int $id = 0, int $type = self::TYPE_QUIZ, int $userId = 0, int $langId = 0)
    {
        $this->userId = $userId;
        $this->langId = $langId;
        $this->id = $id;
        $this->type = $type;
    }

    public function generate($content)
    {
        if (!$content = $this->setupTemplate($content)) {
            return false;
        }
        if (!$this->setupId()) {
            return false;
        }
        if (!$data = $this->getData()) {
            return false;
        }
        if (!$content = $this->formatContent($content, $data)) {
            return false;
        }
        if (!$this->create($content)) {
            return false;
        }
        return true;
    }

    public function generatePreview($content, $type)
    {
        if (!$content = $this->setupTemplate($content)) {
            return false;
        }
        if (!$data = $this->getPreviewData()) {
            return false;
        }
        if (!$content = $this->formatContent($content, $data)) {
            return false;
        }
        if (!$this->create($content, true)) {
            return false;
        }
        return true;
    }

    public function setupId()
    {
        /* generate certificate */
        $certificateNumber = Certificate::CERTIFICATE_NO_PREFIX . uniqid();
        if ($this->type == static::TYPE_QUIZ) {
            $quiz = new QuizAttempt($this->id);
            $quiz->setFldValue('quizat_certificate_number', $certificateNumber) ;
            if (!$quiz->save()) {
                $this->error = Label::getLabel('LBL_AN_ERROR_HAS_OCCURRED_WHILE_GENERATING_CERTIFICATE!');
                return false;
            }
        } elseif ($this->type == static::TYPE_COURSE) {
            $course = new Course($this->id);
            $course->setFldValue('ordcrs_certificate_number', $certificateNumber);
            if (!$course->save()) {
                $this->error = Label::getLabel('LBL_AN_ERROR_HAS_OCCURRED_WHILE_GENERATING_CERTIFICATE!');
                return false;
            }
        } else {
            $this->error = Label::getLabel('LBL_INVALID_CERTIFICATE_TYPE');
            return false;
        }
        return true;
    }

    private function setupTemplate($content)
    {
        $code = 'course_completion_certificate';
        if ($this->type == static::TYPE_QUIZ) {
            $code = 'evaluation_certificate';
        }
        $srch = CertificateTemplate::getSearchObject($this->langId);
        $srch->addCondition('certpl_code', '=', $code);
        if (!$template = FatApp::getDb()->fetch($srch->getResultSet())) {
            $this->error = Label::getLabel('LBL_CERTIFICATE_TEMPLATE_NOT_FOUND');
            return false;
        }
        $template = json_decode($template['certpl_body'], true);
        $content = str_replace(
            ['{heading}', '{content-1}', '{learner}', '{content-2}', '{trainer}', '{certificate-number}'],
            [
                $template['heading'],
                $template['content_part_1'],
                $template['learner'],
                $template['content_part_2'],
                $template['trainer'],
                $template['certificate_number'],
            ],
            $content
        );
        return $content;
    }

    /**
     * Get Course & User details for certificate
     *
     * @param int     $ordcrsId
     * @return array
     */
    private function getData()
    {
        switch ($this->type) {
            case static::TYPE_COURSE:
                $srch = new OrderCourseSearch($this->langId, $this->userId, 0);
                $srch->joinTable(CourseLanguage::DB_TBL, 'INNER JOIN', 'clang.clang_id = course.course_clang_id', 'clang');
                $srch->joinTable(
                    CourseLanguage::DB_TBL_LANG,
                    'LEFT JOIN',
                    'clang.clang_id = clanglang.clanglang_clang_id AND clanglang.clanglang_lang_id = ' . $this->langId,
                    'clanglang'
                );
                $srch->applyPrimaryConditions();
                $srch->addSearchListingFields();
                $srch->addMultipleFields([
                    'crspro_completed',
                    'IFNULL(clanglang.clang_name, clang.clang_identifier) AS course_clang_name',
                    'learner.user_lang_id',
                    'ordcrs_certificate_number AS cert_number',
                    'course_duration'
                ]);
                $srch->addCondition('ordcrs_id', '=', $this->id);
                $data = FatApp::getDb()->fetch($srch->getResultSet());
                break;
            case static::TYPE_QUIZ:
                $quiz = new QuizAttempt($this->id);
                $data = $quiz->getById();
                $learner = User::getAttributesById($data['quizat_user_id'], [
                    'user_first_name as learner_first_name', 'user_last_name as learner_last_name'
                ]);
                $teacher = User::getAttributesById($data['quilin_user_id'], [
                    'user_first_name as teacher_first_name', 'user_last_name as teacher_last_name'
                ]);
                $data['quiz_duration'] = strtotime($data['quizat_updated']) - strtotime($data['quizat_started']);
                $data = $data + $learner + $teacher;
                break;
        }

        return $data;
    }

    private function getPreviewData()
    {
        return [
            'learner_first_name' => 'Martha',
            'learner_last_name' => 'Christopher',
            'teacher_first_name' => 'John',
            'teacher_last_name' => 'Doe',
            'quilin_title' => 'English Language Learning - Beginners',
            'course_title' => 'English Language Learning - Beginners',
            'course_clang_name' => 'English',
            'cert_number' => 'YC_h34uwh9e72w',
            'quizat_certificate_number' => 'YC_h34uwh9e72w',
            'quizat_updated' => date('Y-m-d H:i:s'),
            'crspro_completed' => date('Y-m-d H:i:s'),
            'quiz_duration' => 900,
            'course_duration' => 900,
            'quizat_scored' => 85,
        ];
    }

    /**
     * Generate Certificate PDF
     *
     * @param string $token
     * @return bool
     */
    public function create($content, $preview = false)
    {
        $filename = 'certificate' . $this->id . '.pdf';
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
    public function formatContent(string $content, array $data)
    {
        if ($this->type == static::TYPE_QUIZ) {
            $title = htmlentities(stripslashes(utf8_encode($data['quilin_title'])), ENT_QUOTES);
        } else {
            $title = htmlentities(stripslashes(utf8_encode($data['course_title'])), ENT_QUOTES);
        }
        $content = str_replace(
            [
                '{learner-name}',
                '{teacher-name}',
                '{quiz-name}',
                '{quiz-completed-date}',
                '{certificate-number}',
                '{quiz-duration}',
                '{course-name}',
                '{course-language}',
                '{course-completed-date}',
                '{course-duration}',
                '{quiz-score}'
            ],
            [
                ucwords($data['learner_first_name'] . ' ' . $data['learner_last_name']),
                '<b>' . ucwords($data['teacher_first_name'] . ' ' . $data['teacher_last_name']) . '</b>',
                '<span class=\"courseNameJs\">' . $title . '</span>',
                isset($data['quizat_updated']) ? MyDate::formatDate($data['quizat_updated']) : '',
                '<b>' . (($this->type == static::TYPE_QUIZ) ? $data['quizat_certificate_number'] : $data['cert_number']) . '</b>',
                isset($data['quiz_duration']) ? MyUtility::convertDuration($data['quiz_duration'], true, true, true) : '',
                '<span class=\"courseNameJs\">' . $title . '</span>',
                isset($data['course_clang_name']) ? $data['course_clang_name'] : '',
                isset($data['crspro_completed']) ? MyDate::formatDate($data['crspro_completed']) : '',
                isset($data['course_duration']) ? MyUtility::convertDuration($data['course_duration'], true, true, true) : '',
                isset($data['quizat_scored']) ? MyUtility::formatPercent($data['quizat_scored']) : '',
            ],
            $content
        );
        return $content;
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
            'file_record_id' => $this->id,
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
