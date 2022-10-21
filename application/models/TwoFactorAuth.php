<?php
/**
 * This class is used to handle Two Factor Auth
 * 
 * @package YoCoach
 * @author Fatbit Team
 */
class TwoFactorAuth extends FatModel
{   
    
    const DB_TBL = 'tbl_two_factor_auths';
    const DB_TBL_PREFIX = 'usauth_';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Add Authentication Code
     * 
     * @param int $userId
     * @param string $otp
     * @param string $browser
     * @return bool
     */
    public function addTwoFactorCode(int $userId, string $otp) 
    {
        $record = new TableRecord(static::DB_TBL);
        $record->assignValues([
            'usauth_user_id' => $userId,
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


    public function validateCode(int $userId, string $code)
    {
        if (empty($userId) || empty($code)) {
            $this->error = Label::getLabel('ERR_INVALID_REQUEST');
            return false;
        }
        $srch = new SearchBase(static::DB_TBL, 'usauth');
        $srch->addCondition('usauth_user_id', '=', $userId);
        $srch->addCondition('usauth_otp', '=', $code);
        $srch->addCondition('usauth_browser', '=', MyUtility::getUserAgent());
        $srch->addCondition('usauth_ip', '=', MyUtility::getUserIp());
        $srch->addCondition('usauth_expiry', '>=', date('Y-m-d H:i:s'));
        $srch->getResultSet();
        if ($srch->recordCount() < 1) {
            $this->error = Label::getLabel('ERR_INVALID_AUTHENTICATION_CODE');
            return false;
        }
        return true;
    }

    public function isVerified(int $userId) 
    {
        if (empty($userId)) {
            $this->error = Label::getLabel('ERR_INVALID_REQUEST');
            return false;
        }
        $srch = new SearchBase(static::DB_TBL, 'usauth');
        $srch->addCondition('usauth_user_id', '=', $userId);
        $srch->addCondition('usauth_browser', '=', MyUtility::getUserAgent());
        $srch->addCondition('usauth_ip', '=', MyUtility::getUserIp());
        $srch->getResultSet();
        if ($srch->recordCount() < 1) {
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
    public function removeCode(int $userId): bool
    {
        if (!FatApp::getDb()->deleteRecords(static::DB_TBL,
                        ['smt' => 'usauth_user_id = ?', 'vals' => [$userId]])) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }
        return true;
    }

    public function checkUserVerified(int $userId)
    {
        if (!$this->isVerified($userId)) {
            $this->error = $this->getError();
            return false;
        }
        return true;    
    }
}