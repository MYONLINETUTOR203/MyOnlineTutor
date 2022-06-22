<?php

class ZoomMeeting extends FatModel
{
    private $tool;
    private $settings;
    private $meeting;

    const DB_TBL_USERS = 'tbl_zoom_users';
    const BASE_URL = 'https://api.zoom.us/v2';
    /* User Roles */
    const ROLE_TEACHER = 1;
    const ROLE_LEARNER = 0;

    public function __construct()
    {
        $this->tool = [];
        $this->settings = [];
        $this->meeting = [];
        parent::__construct();
    }

    /**
     * Initialize Meeting Tool
     * 1. Load Meeting Tool
     * 2. Format Meeting Tool Settings
     * 3. Validate Meeting Tool Settings
     * 
     * @return bool
     */
    public function initMeetingTool(): bool
    {
        /* Load Zoom Meeting Tool */
        $this->tool = MeetingTool::getByCode(MeetingTool::ZOOM_MEETING);
        if (empty($this->tool)) {
            $this->error = Label::getLabel('LBL_ZOOM_MEETING_NOT_FOUND');
            return false;
        }
        /* Format Meeting Tool Settings */
        $settings = json_decode($this->tool['metool_settings'], 1) ?? [];
        foreach ($settings as $row) {
            $this->settings[$row['key']] = $row['value'];
        }
        /* Validate Meeting Tool Settings */
        if (empty($this->settings['api_key']) || empty($this->settings['api_secret']) || empty($this->settings['jwt_token'])) {
            $this->error = Label::getLabel("MSG_ZOOM_MEETING_NOT_CONFIGURED");
            return false;
        }
        return true;
    }

    /**
     * Create Meeting on Zoom
     * 
     * @param array $user = []
     * @param array $meeting = ['title', 'detail', 'duration', 'timezone', 'starttime']
     * @return bool
     */
    public function createMeeting(array $user, array $meeting)
    {
        $userId = FatUtility::int($user['user_id']);
        if (!$zoomUser = $this->getUser($userId)) {
            if (!$zoomUser = $this->createUser($user)) {
                return false;
            }
        }
        $zoomUser['first_name'] = $user['user_first_name'];
        $zoomUser['last_name'] = $user['user_last_name'];
        if ($user['user_type'] == User::TEACHER) {
            return  $this->getTeacherMeetingDetails($zoomUser, $meeting);
        } else {
            return $this->getLearnerMeetingDetails($zoomUser, $meeting);
        }
    }

    /**
     * get teacher meeting details function
     *
     * @param array $user
     * @param array $meeting
     * @return bool|array
     */
    public function getTeacherMeetingDetails(array $user, array $meeting)
    {
        $zoomMeeting = [
            'topic' => $meeting['title'],
            'agenda' => $meeting['title'],
            'duration' => $meeting['duration'],
            'timezone' => $meeting['timezone'],
            'start_time' => date('c', strtotime($meeting['starttime'])),
            'type' => 2
        ];
        $url = static::BASE_URL . '/users/' . $user['id'] . '/meetings';
        if (!$response = $this->exeCurlRequest($url, $zoomMeeting)) {
            return false;
        }
        if (empty($response['id'])) {
            $this->error = Label::getLabel('LBL_ERROR_TO_CREATE_MEETING');
            return false;
        }
        return $this->meeting = array_merge($response, [
            'user_first_name' => $user['first_name'],
            'user_last_name' => $user['last_name'],
            'user_email' => $user['email'],
            'user_role' => static::ROLE_TEACHER,
            'user_signature' => $this->generateSignature($response['id'], static::ROLE_TEACHER)
        ]);
    }

    /**
     * get learner meeting details function
     *
     * @param array $user
     * @param array $meeting
     * @return bool|array
     */
    public function getLearnerMeetingDetails(array $user, array $meeting)
    {
        $meetingObj = new Meeting($meeting['otherDetails']['teacher_id'], User::TEACHER);
        $meetingDetails = $meetingObj->getMeeting($meeting['recordId'], $meeting['recordType']);
        if (empty($meetingDetails)) {
            if (!$meetingObj->initMeeting($meeting['meetingToolId'])) {
                $this->error = $meetingObj->getError();
                return false;
            }
            if (!$meetingDetails = $meetingObj->createMeeting($meeting['recordId'], $meeting['recordType'], $meeting)) {
                return false;
            }
        }
        $meetingDetails = json_decode($meetingDetails['meet_details'], true);
        return $this->meeting = array_merge($meetingDetails, [
            'user_first_name' => $user['first_name'],
            'user_last_name' => $user['last_name'],
            'user_email' => $user['email'],
            'user_role' => static::ROLE_LEARNER,
            'user_signature' => $this->generateSignature($meetingDetails['id'], static::ROLE_LEARNER)
        ]);
    }

    /**
     * get join url function
     *
     * @param array $meeting 
     * @return string
     */
    public function getJoinUrl(): string
    {
        $meetingConfig = [
            "mn" => $this->meeting['id'], "name" => $this->meeting['user_first_name'] . ' ' . $this->meeting['user_last_name'],
            "pwd" => '', "role" =>  $this->meeting['user_role'],
            "email" => $this->meeting['user_email'],
            "lang" => "en-US",
            "signature" => $this->meeting['user_signature'],
            "leaveUrl" => MyUtility::makeUrl('Zoom', 'leave'),
            "china" => 0,
            'apiKey' => $this->settings['api_key']
        ];
        $configs = [];
        foreach ($meetingConfig as $key => $value) {
            $string = $this->encodeURIComponent($key) . '=' . $this->encodeURIComponent($value);
            array_push($configs, $string);
        }
        return MyUtility::makeUrl('Zoom', 'meeting') . '?' . implode("&", $configs);
    }

    public function getAppUrl()
    {
        if ($this->meeting['user_role'] == static::ROLE_LEARNER) {
            return $this->meeting['join_url'];
        } elseif ($this->meeting['user_role'] == static::ROLE_TEACHER) {
            return $this->meeting['start_url'];
        }
    }

    /**
     * Get Zoom User
     *
     * @param string $email
     * @return bool|array
     */
    private function getZoomUser(string $email)
    {
        /* Execute Curl Request */
        $url = self::BASE_URL . "/users/" . $email . "?encrypted_email=false";
        if (!$response = $this->exeCurlRequest($url, [], 'GET')) {
            return false;
        }
        if (empty($response['id'])) {
            $this->error = Label::getLabel('LBL_CONTACT_WITH_ADMIN_ISSUE_WITH_MEETING_TOOL');
            return false;
        }
        return $response;
    }

    /**
     * Get User
     *
     * @param int $userId
     * @return bool|array
     */
    private function getUser(int $userId)
    {
        $srch = new SearchBase(static::DB_TBL_USERS);
        $srch->addCondition('zmusr_user_id', '=', $userId);
        $srch->addMultipleFields(['zmusr_zoom_id', 'zmusr_details']);
        $srch->doNotCalculateRecords();
        $user = FatApp::getDb()->fetch($srch->getResultSet());
        if (empty($user)) {
            $this->error = Label::getLabel('LBL_ZOOM_USER_NOT_FOUND');
            return false;
        }
        return json_decode($user['zmusr_details'], true);
    }

    /**
     * Create Zoom User
     * 
     * @param array $user
     * @return bool|array
     */
    private function createUser(array $user)
    {
        $request = [
            'action' => 'custCreate',
            'user_info' => [
                'type' => 1,
                'email' => $user['user_email'],
                'first_name' => $user['user_first_name'],
                'last_name' => $user['user_last_name'],
            ]
        ];
        /* Execute Curl Request */
        $url = self::BASE_URL . "/users";
        if (!$response = $this->exeCurlRequest($url, $request)) {
            return false;
        }
        /**
         * 1005 is user already exists in the account
         */
        if (!empty($response['code']) && $response['code'] == 1005) {
            if (!$response = $this->getZoomUser($user['user_email'])) {
                return false;
            }
        } elseif (empty($response['id'])) {
            $this->error = Label::getLabel('LBL_CONTACT_WITH_ADMIN_ISSUE_WITH_MEETING_TOOL');
            return false;
        }
        /* Map Zoom User with Users */
        $record = new TableRecord(static::DB_TBL_USERS);
        $record->assignValues([
            'zmusr_user_id' => $user['user_id'],
            'zmusr_zoom_id' => $response['id'],
            'zmusr_details' => json_encode($response)
        ]);
        if (!$record->addNew([], $record->getFlds())) {
            $this->error = $record->getError();
            return false;
        }
        return $response;
    }

    /**
     * End Zoom Meeting
     * 
     * @param array $meeting
     * @return bool
     */
    public function endMeeting(array $meeting): bool
    {
        $role = $meeting['user_role'] ?? 0;
        if (empty($meeting['id']) || ($meeting['meet_record_type'] == AppConstant::GCLASS && $role == static::ROLE_LEARNER)) {
            return true;
        }
        $url = self::BASE_URL . '/meetings/' . $meeting['id'] . '/status';
        $this->exeCurlRequest($url, ["action" => "end"], 'PUT');
        return true;
    }

    /**
     * Generate signature
     *
     * @param integer $meetingId
     * @param integer $role
     * @return string
     */
    private function generateSignature(int $meetingId, int $role): string
    {
        $time = time() * 1000 - 30000;
        $data = base64_encode($this->settings['api_key']  . $meetingId . $time . $role);
        $hash = hash_hmac('sha256', $data, $this->settings['api_secret'], true);
        $_sig = $this->settings['api_key'] . "." . $meetingId . "." . $time . "." . $role . "." . base64_encode($hash);
        return rtrim(strtr(base64_encode($_sig), '+/', '-_'), '=');
    }

    /**
     * encode URI Component function
     *
     * @param string $str
     * @return string
     */
    private function encodeURIComponent(string $str): string
    {
        $revert = array('%21' => '!', '%2A' => '*', '%27' => "'", '%28' => '(', '%29' => ')');
        return strtr(rawurlencode($str), $revert);
    }

    /**
     * Execute Curl Request
     *
     * @param string $url
     * @param array $params
     * @return boolean
     */
    private function exeCurlRequest(string $url, array $params, string $method = 'POST')
    {
        $postfields = json_encode($params);
        $headers = [
            'Content-type: application/json',
            'Content-length: ' . strlen($postfields),
            'Authorization: Bearer ' . $this->settings['jwt_token']
        ];
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postfields);
        $curlResult = curl_exec($curl);
        if (curl_errno($curl)) {
            $this->error = 'Error:' . curl_error($curl);
            return false;
        }
        curl_close($curl);
        $response = json_decode($curlResult, true) ?? [];
        if (empty($response)) {
            $this->error = Label::getLabel('LBL_CONTACT_WITH_ADMIN_ISSUE_WITH_MEETING_TOOL');
            return false;
        }
        return $response;
    }
}
