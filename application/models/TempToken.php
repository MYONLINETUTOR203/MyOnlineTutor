<?php

/**
 * This class is used to handle User Verification
 * 
 * @package YoCoach
 * @author Fatbit Team
 */
class TempToken extends MyAppModel
{

    const DB_TBL = 'tbl_temp_tokens';
    const DB_TBL_PREFIX = 'tmptok_';

    /**
     * Initialize Verification
     * 
     * @param int $id
     */
    public function __construct(int $id = 0)
    {
        parent::__construct(static::DB_TBL, 'tmptok_id', $id);
    }

    /**
     * Add Verification Token
     * 
     * @param int    $ordcrsId
     * @param string $token
     * @return bool
     */
    public function addToken(int $ordcrsId, string $token): bool
    {
        $this->assignValues([
            'tmptok_ordcrs_id' => $ordcrsId,
            'tmptok_token' => $token
        ]);
        if (!$this->save()) {
            $this->error = $this->getError();
            return false;
        }
        return true;
    }

    /**
     * Remove Token
     * 
     * @param int $userId
     * @return bool
     */
    public function removeToken(int $ordcrsId): bool
    {
        if (!FatApp::getDb()->deleteRecords(static::DB_TBL,
                        ['smt' => 'tmptok_ordcrs_id = ?', 'vals' => [$ordcrsId]])) {
            $this->error = FatApp::getDb()->getError();
            return false;
        }
        return true;
    }

    /**
     * Verify Token & Remove
     *
     * @param string $token
     * @return bool|array
     */
    public function verify(string $token)
    {
        $srch = new SearchBase(static::DB_TBL, 'tmptok');
        $srch->addFld('tmptok_ordcrs_id');
        $srch->joinTable(Certificate::DB_TBL, 'INNER JOIN', 'ordcrs.ordcrs_id = tmptok.tmptok_ordcrs_id', 'ordcrs');
        $srch->addCondition('tmptok_token', '=', $token);
        $srch->doNotCalculateRecords();
        $srch->setPageSize(1);
        if (!$tokenData = FatApp::getDb()->fetch($srch->getResultSet())) {
            $this->error = Label::getLabel('LBL_INVALID_URL_REQUESTED');
            return false;
        }
        /* remove token */
        $this->removeToken($tokenData['tmptok_ordcrs_id']);
        return $tokenData;
    }
}
