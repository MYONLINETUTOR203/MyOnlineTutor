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

    const TYPE_QUIZ = 1;
    
    private $id;
    private $code;
    private $userId;
    private $langId;

    /**
     * Initialize certificate
     *
     * @param int    $id
     * @param string $code
     * @param int    $userId
     * @param int    $langId
     */
    public function __construct(int $id = 0, string $code, int $userId = 0, int $langId = 0)
    {
        $this->id = $id;
        $this->code = $code;
        $this->userId = $userId;
        $this->langId = $langId;
    }

    /**
     * Generate Certificate
     *
     * @param string $content
     * @return bool
     */
    public function generate(string $content)
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
        if (!$this->setupMetaTags($data)) {
            return false;
        }
        return true;
    }

    /**
     * Generate Certificate & Preview
     *
     * @param string $content
     * @return bool
     */
    public function generatePreview(string $content)
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

    /**
     * Generate & save certificate id
     *
     * @return bool
     */
    public function setupId()
    {
        /* generate certificate */
        $certificateNumber = Certificate::CERTIFICATE_NO_PREFIX . uniqid();
        if ($this->code == 'evaluation_certificate') {
            $quiz = new QuizAttempt($this->id);
            $quiz->setFldValue('quizat_certificate_number', $certificateNumber) ;
            if (!$quiz->save()) {
                $this->error = Label::getLabel('LBL_AN_ERROR_HAS_OCCURRED_WHILE_GENERATING_CERTIFICATE!');
                return false;
            }
        } elseif ($this->code == 'course_completion_certificate') {
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

    /**
     * Get & setup certificate template
     *
     * @param string $content
     * @return string|bool
     */
    private function setupTemplate(string $content)
    {
        $srch = CertificateTemplate::getSearchObject($this->langId);
        $srch->addCondition('certpl_code', '=', $this->code);
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
     * Generate Certificate PDF
     *
     * @param string $content
     * @param bool $preview
     * @return bool
     */
    public function create(string $content, bool $preview = false)
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
     * @param string $content
     * @param array  $data
     * @return string
     */
    public function formatContent(string $content, array $data)
    {
        $title = '';
        if ($this->code == 'evaluation_certificate') {
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
                isset($data['quizat_updated']) ? MyDate::formatDate($data['quizat_updated'], 'Y-m-d') : '',
                '<b>' . (($this->code == 'evaluation_certificate') ? $data['quizat_certificate_number'] : $data['cert_number']) . '</b>',
                isset($data['quiz_duration']) ? MyUtility::convertDuration($data['quiz_duration'], true, true, true) : '',
                '<span class=\"courseNameJs\">' . $title . '</span>',
                isset($data['course_clang_name']) ? $data['course_clang_name'] : '',
                isset($data['crspro_completed']) ? MyDate::formatDate($data['crspro_completed'], 'Y-m-d') : '',
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
     * Get certificate replacers data
     *
     * @return array
     */
    private function getData()
    {
        switch ($this->code) {
            case 'course_completion_certificate':
                $srch = new OrderCourseSearch($this->langId, $this->userId, 0);
                $srch->joinTable(
                    CourseLanguage::DB_TBL,
                    'INNER JOIN',
                    'clang.clang_id = course.course_clang_id',
                    'clang'
                );
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
            case 'evaluation_certificate':
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

    /**
     * Get preview dummy data
     *
     * @return array
     */
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
            'quizat_updated' => date('Y-m-d'),
            'crspro_completed' => date('Y-m-d'),
            'quiz_duration' => 900,
            'course_duration' => 900,
            'quizat_scored' => 85,
        ];
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

    /**
     * Setup meta tags for generated certificate
     *
     * @param array $data
     * @return bool
     */
    private function setupMetaTags(array $data)
    {
        /* get user name */
        $username = User::getAttributesById($data['quizat_user_id'], "CONCAT(user_first_name, ' ', user_last_name)");
        $content = $data['quilin_title'] . ' | ' . ucwords($username);

        $meta = new MetaTag();
        $meta->assignValues([
            'meta_controller' => 'Certificates',
            'meta_action' => 'evaluation',
            'meta_type' => MetaTag::META_GROUP_QUIZ_CERTIFICATE,
            'meta_record_id' => $data['quizat_id'],
            'meta_identifier' => $content . ' ' . $this->id
        ]);
        if (!$meta->save()) {
            $this->error = $meta->getError();
            return false;
        }
        $url = MyUtility::makeFullUrl('Certificates', 'evaluation', [$data['quizat_id']], CONF_WEBROOT_FRONTEND);
        if (
            !FatApp::getDb()->insertFromArray(
                MetaTag::DB_LANG_TBL,
                [
                    'metalang_meta_id' => $meta->getMainTableRecordId(),
                    'metalang_lang_id' => $this->langId,
                    'meta_title' => $content,
                    'meta_og_url' => $url,
                    'meta_og_title' => $content
                ]
            )
        ) {
            $this->error = $meta->getError();
            return false;
        }
        return true;
    }
}
