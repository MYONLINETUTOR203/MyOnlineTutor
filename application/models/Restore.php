<?php

/**
 * This class is used to handle restoration process through cron
 * 
 * @package YoCoach
 * @author Fatbit Team
 */
class Restore extends FatModel
{
    const BACKUP_FILE = CONF_INSTALLATION_PATH . "restore/database/yocoach-restore-db.sql";
    const USER_UPLOADS_PATH = CONF_INSTALLATION_PATH . "user-uploads";

    /* setup db names used for restoration */
    const DATABASE_FIRST = 'yocoach87h1y172_v3demo_1';
    const DATABASE_SECOND = 'yocoach87h1y172_v3demo_2';

    /* set restoration duration */
    const RESTORE_TIME_INTERVAL_HOURS = 4;
    const RESTORATION_SETUP_DATE = '2022-06-09 00:00:00';

    private $db;
    private $restoreDb;

    /**
     * Initialize
     */
    public function __construct()
    {
        /* Get the db to be restored */
        $this->restoreDb = (CONF_DB_NAME == static::DATABASE_FIRST) ? static::DATABASE_SECOND : static::DATABASE_FIRST;
        /* create a new connection */
        $this->db = new Database(CONF_DB_SERVER, CONF_DB_USER, CONF_DB_PASS, $this->restoreDb, true);
    }

    public function restoreDb()
    {
        if (!$this->isRestoredSuccessfully()) {
            if (!$this->restoreUserUploads()) {
                $this->error = "Unable to restore files";
                return false;
            }
            if (!$this->restoreDatabase()) {
                $this->error = "An error occurred while restoring database";
                return false;
            }
            if (!$this->executeUpdates()) {
                return false;
            }
            if (!$this->resetRestoreStatus()) {
                $this->error = "Unable to update restoration status";
                return false;
            }
        }
        return true;
    }

    /**
     * Function to check if db restoration was successful or not
     *
     * @return boolean
     */
    private function isRestoredSuccessfully()
    {
        $query = $this->db->query("SELECT * FROM `tbl_configurations` WHERE `conf_name` = 'CONF_RESTORED_SUCCESSFULLY'");
        $row = $this->db->fetch($query);
        if (!$row) {
            return false;
        }
        if ($row['conf_val'] > 0) {
            return true;
        }
        return false;
    }

    /**
     * function to initiate the restoration of user-uploads files & folders
     *
     * @return void
     */
    private function restoreUserUploads()
    {
        $source = CONF_INSTALLATION_PATH . "restore/user-uploads";
        $target = static::USER_UPLOADS_PATH;
        if (!$this->fullCopy($source, $target)) {
            return false;
        }
        return $this->recursiveDelete($target);
    }

    /**
     * Copy the user-uploads from the backup
     *
     * @return void
     */
    private function fullCopy($source, $target)
    {
        system('cp -R ' . $source . '/* ' . $target, $output);
        return ($output == 0);
    }


    /**
     * Delete the existing user-uploads folder & files recursively
     *
     * @return void
     */
    private function recursiveDelete($source)
    {
        system('rm -rf -d ' . $source . '/*-old', $output);
        return ($output == 0);
    }

    /**
     * Function to update restoration successful status
     *
     * @return bool
     */
    private function resetRestoreStatus()
    {
        if (!$this->db->query("UPDATE `" . $this->restoreDb . "`.`tbl_configurations` SET `conf_val` = '1' WHERE `conf_name` = 'CONF_RESTORED_SUCCESSFULLY';")) {
            return false;
        }

        return true;
    }

    /**
     * Function to drop all the tables and restore from backup file
     *
     * @return bool
     */
    private function restoreDatabase()
    {
        $backupFile = static::BACKUP_FILE;
        $dbServer = CONF_DB_SERVER;
        $dbUser = CONF_DB_USER;
        $dbPassword = CONF_DB_PASS;
        $mysqli = new mysqli($dbServer, $dbUser, $dbPassword, $this->restoreDb);
        $sql = "SHOW TABLES FROM $this->restoreDb";
        if ($rs = $mysqli->query($sql)) {
            while ($row = $rs->fetch_array()) {
                $tableName = $row["Tables_in_" . $this->restoreDb];
                $mysqli->query("DROP TABLE $this->restoreDb.$tableName");
            }
        }
        $cmd = "mysql --user=" . $dbUser . " --password=\"" . $dbPassword . "\" " . $this->restoreDb . " < " . $backupFile;
        system($cmd, $output);
        if ($output != 0) {
            return false;
        }
        return true;
    }

    private function executeUpdates()
    {
        $queries = $this->getQueries();
        $error = 0;
        foreach ($queries as $query) {
            if (!$this->db->query($query)) {
                $error = 1;
                $this->error = $this->db->getError();
                break;
            }
        }
        if ($error == 1) {
            return false;
        }
        return true;
    }

    private function getQueries()
    {
        $query = $this->db->query("SELECT `conf_val` from `" . CONF_DB_NAME . "`.`tbl_configurations` where `conf_name` = 'CONF_RESTORE_SCHEDULE_TIME'");
        $row = $this->db->fetch($query);
        $restoreDate = date('Y-m-d H:i:s');
        if ($row) {
            $restoreDate = $row['conf_val'];
        }
        $hours = round(abs(strtotime($restoreDate) - strtotime(static::RESTORATION_SETUP_DATE)) / 3600, 1);
        return [
            "UPDATE tbl_admin_auth_token SET  admauth_expiry = DATE_ADD(admauth_expiry, INTERVAL " . $hours . " hour),  admauth_last_access = DATE_ADD(admauth_last_access, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_admin_commissions SET comm_created = DATE_ADD(comm_created, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_attached_files SET file_added = DATE_ADD(file_added, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_bank_transfers SET bnktras_datetime = DATE_ADD(bnktras_datetime, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_blog_post SET  post_added_on = DATE_ADD(post_added_on, INTERVAL " . $hours . " hour), post_published_on = DATE_ADD(post_published_on, INTERVAL " . $hours . " hour), post_updated_on = DATE_ADD(post_updated_on, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_coupons_history SET  couhis_created = DATE_ADD(couhis_created, INTERVAL " . $hours . " hour),  couhis_released = DATE_ADD(couhis_released, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_faq SET faq_added_on = DATE_ADD(faq_added_on, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_orders SET order_addedon = DATE_ADD(order_addedon, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_order_classes SET  ordcls_starttime = DATE_ADD(ordcls_starttime, INTERVAL " . $hours . " hour),  ordcls_endtime = DATE_ADD(ordcls_endtime, INTERVAL " . $hours . " hour),  ordcls_updated = DATE_ADD(ordcls_updated, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_order_giftcards SET  ordgift_expiry = DATE_ADD(ordgift_expiry, INTERVAL " . $hours . " hour),  ordgift_usedon = DATE_ADD(ordgift_usedon, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_order_payments SET ordpay_datetime = DATE_ADD(ordpay_datetime, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_order_subscriptions SET  ordsub_startdate = DATE_ADD(ordsub_startdate, INTERVAL " . $hours . " hour),  ordsub_enddate = DATE_ADD(ordsub_enddate, INTERVAL " . $hours . " hour),  ordsub_created = DATE_ADD(ordsub_created, INTERVAL " . $hours . " hour),  ordsub_updated = DATE_ADD(ordsub_updated, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_rating_reviews SET ratrev_created = DATE_ADD(ratrev_created, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_reported_issues SET  repiss_reported_on = DATE_ADD(repiss_reported_on, INTERVAL " . $hours . " hour),  repiss_updated_on = DATE_ADD(repiss_updated_on, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_reported_issues_log SET reislo_added_on = DATE_ADD(reislo_added_on, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_themes SET theme_created = DATE_ADD(theme_created, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_users SET  user_created = DATE_ADD(user_created, INTERVAL " . $hours . " hour),  user_verified = DATE_ADD(user_verified, INTERVAL " . $hours . " hour),  user_deleted = DATE_ADD(user_deleted, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_user_transactions SET usrtxn_datetime = DATE_ADD(usrtxn_datetime, INTERVAL " . $hours . " hour);",
            "UPDATE `tbl_configurations` SET `conf_val` = '2020-01-01' WHERE `tbl_configurations`.`conf_name` = 'CONF_SALES_REPORT_GENERATED_DATE';",
            "UPDATE tbl_order_lessons SET  ordles_lesson_starttime = DATE_ADD(ordles_lesson_starttime, INTERVAL " . $hours . " hour),  ordles_lesson_endtime = DATE_ADD(ordles_lesson_endtime, INTERVAL " . $hours . " hour),  ordles_teacher_starttime = DATE_ADD(ordles_teacher_starttime, INTERVAL " . $hours . " hour),  ordles_teacher_endtime = DATE_ADD(ordles_teacher_endtime, INTERVAL " . $hours . " hour),  ordles_student_starttime = DATE_ADD(ordles_student_starttime, INTERVAL " . $hours . " hour),  ordles_student_endtime = DATE_ADD(ordles_student_endtime, INTERVAL " . $hours . " hour),  ordles_updated = DATE_ADD(ordles_updated, INTERVAL " . $hours . " hour);",
            "UPDATE tbl_group_classes SET  grpcls_start_datetime = DATE_ADD(grpcls_start_datetime, INTERVAL " . $hours . " hour),  grpcls_end_datetime = DATE_ADD(grpcls_end_datetime, INTERVAL " . $hours . " hour),  grpcls_teacher_starttime = DATE_ADD(grpcls_teacher_starttime, INTERVAL " . $hours . " hour),  grpcls_teacher_endtime = DATE_ADD(grpcls_teacher_endtime, INTERVAL " . $hours . " hour),  grpcls_added_on = DATE_ADD(grpcls_added_on, INTERVAL " . $hours . " hour);"
        ];
    }
}
