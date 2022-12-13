<?php
/**
 * This class is used to handle Two Factor Auth
 * 
 * @package YoCoach
 * @author Fatbit Team
 */
class TwoFactorAuth extends MyAppModel
{   
    
    const DB_TBL = 'tbl_two_factor_auths';
    const DB_TBL_PREFIX = 'usauth_';

    public $isVerified = false;

    public function __construct(int $userId = 0)
    {
        parent::__construct(static::DB_TBL, 'usauth_user_id', $userId);
    }

    /**
     * Add Authentication Code
     * 
     * @param int $userId
     * @param string $otp
     * @param string $browser
     * @return bool
     */
    public function addCode(string $otp) 
    {
        $record = new TableRecord(static::DB_TBL);
        $record->assignValues([
            'usauth_user_id' => $this->getMainTableRecordId(),
            'usauth_otp' => $otp,
            'usauth_expiry' => date('Y-m-d H:i:s', strtotime('+5 min')),
            'usauth_browser' => MyUtility::getUserAgent(),
            'usauth_ip' => MyUtility::getUserIp(),
            'usauth_status' => 0,
            'usauth_created' => date('Y-m-d H:i:s'),
        ]);
        if (!$record->addNew()) {
            $this->error = $record->getError();
            return false;
        }
        return true;
    }

    /**
     * Get Authentication Code Detail
     * 
     * @param string $token
     * @return null|array
     */
    public function getCode(string $code)
    {
        $srch = new SearchBase(static::DB_TBL, 'usauth');
        $srch->addMultipleFields(['usauth_user_id', 'usauth_otp', 'usauth_expiry', 'usauth_browser', 'usauth_status', 'usauth_ip']);
        $srch->joinTable(User::DB_TBL, 'INNER JOIN', 'user.user_id = usauth.usauth_user_id', 'user');
        $srch->addDirectCondition('user_deleted IS NULL');
        $srch->addCondition('usauth_otp', '=', $code);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        return FatApp::getDb()->fetch($srch->getResultSet());
    }


    public function validateCode(string $code)
    {
        if (empty($code)) {
            $this->error = Label::getLabel('ERR_INVALID_REQUEST');
            return false;
        }
        $srch = new SearchBase(static::DB_TBL, 'usauth');
        $srch->addCondition('usauth_user_id', '=', $this->getMainTableRecordId());
        $srch->addCondition('usauth_otp', '=', $code);
        $srch->addCondition('usauth_browser', '=', MyUtility::getUserAgent());
        $srch->addCondition('usauth_expiry', '>=', date('Y-m-d H:i:s'));
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        if (!FatApp::getDb()->fetch($srch->getResultSet())) {
            $this->error = Label::getLabel('ERR_INVALID_AUTHENTICATION_CODE');
            return false;
        }
        return true;
    }

    public function isVerified() 
    {
        $srch = new SearchBase(static::DB_TBL, 'usauth');
        $srch->addCondition('usauth_user_id', '=', $this->getMainTableRecordId());
        $srch->addCondition('usauth_browser', '=', MyUtility::getUserAgent());
        $srch->addCondition('usauth_status', '=', AppConstant::ACTIVE);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        if (!FatApp::getDb()->fetch($srch->getResultSet())) {
            $this->error = Label::getLabel('ERR_USER_NOT_AUTHENTICATED');
            return false;
        }
        return true;
    }

    /**
     * Remove Code
     * 
     * @param int $userId
     * @return bool
     */
    public function removeCode(): bool
    {
        if (!FatApp::getDb()->deleteRecords(static::DB_TBL,
                        ['smt' => 'usauth_user_id = ? AND usauth_browser = ? AND usauth_status = ?',
                         'vals' => [$this->getMainTableRecordId(), MyUtility::getUserAgent(), AppConstant::INACTIVE]])) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }
        return true;
    }

    
    /**
     * Send Two Factor Authentication Code Email
     * 
     * @param array $user
     * @return bool
     */
    public function sendTwoFactorAuthenticationEmail(array $user, int $auth_code): bool
    {       
        $mail = new FatMailer($user['user_lang_id'], 'two_factor_authentication');
        $mail->setVariables(['{user_name}' => $user['user_first_name'].' '. $user['user_last_name'], '{auth_code}' => $auth_code]);
        if (!$mail->sendMail([$user['user_email']])) {
            $this->error = $mail->getError();
            return false;
        }
        return true;
    }

    public function send2FactorAuthCode(array $user) 
    {
        if ($this->isVerified()) {
            $this->isVerified = true;
            return true;
        }
        if (!$this->removeCode()) {
            return false;
        }       
        $auth_code = rand(100000, 999999);
        if (!$this->addCode($auth_code)) {
            return false;
        }
       
        if (!$this->sendTwoFactorAuthenticationEmail($user, $auth_code)) {
            return false;
        }       
        return true;
    }

    public function setup(int $authCode)
    {
        if ($this->isVerified()) {
            $this->isVerified = true;
            return true;
        }
        if (!$this->validateCode($authCode)) {
            return false;
        }
        if (!$this->updateStatus()) {
            $this->error = Label::getLabel('LBL_SOMETHING_WENT_WRONG_PLEASE_TRY_AGAIN');
            return false;
        }
        return true;      
    }

    public function updateStatus()
    {
        $record = new TableRecord(TwoFactorAuth::DB_TBL);
        $record->assignValues(['usauth_status' => AppConstant::ACTIVE]);
        if (!$record->update(['smt' => 'usauth_user_id = ?', 'vals' => [$this->getMainTableRecordId()]])) {
            return false;
        }
        return true;
    }

    public function removeAllCodes() 
    {
        if (!FatApp::getDb()->deleteRecords(TwoFactorAuth::DB_TBL, ['smt' => 'usauth_user_id = ?', 'vals' => [$this->getMainTableRecordId()]])) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }
        return true;
    }


}