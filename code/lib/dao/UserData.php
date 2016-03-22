<?php
class dao_UserData {

    protected static $table_name = 'user_data';

    protected static $status = array(
        '未处理'   => 0,
        '处理完成' => 1,
    );

    public static function getByUserID ($userID) {
        $sql = sprintf('SELECT * FROM %s WHERE user_id=:user_id', self::$table_name);
        $db = DbManager::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetch(PDO::FETCH_ASSOC);
        return empty($ret) ? array() : $ret;
    }

    public static function save($userInfo) {
        $curTime = time();

        $db = DbManager::getDB();
        $sql = sprintf(
            'INSERT INTO %s (user_id, user_name, user_url, create_time) values (:user_id, :user_name, :user_url, :create_time)',
            self::$table_name
        );
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':user_id',      $userInfo['user_id'],    PDO::PARAM_STR);
        $stmt->bindParam(':user_name',    $userInfo['user_name'],  PDO::PARAM_STR);
        $stmt->bindParam(':user_url',     $userInfo['user_url'],   PDO::PARAM_STR);
        $stmt->bindParam(':create_time',  $curTime,                PDO::PARAM_INT);

        $ret = $stmt->execute();
        if (!$ret) {
            printf("mysql err \n");
            var_dump($stmt->errorInfo());
        }
        return $ret;
    }

    // 获取未处理过的用户
    public static function getUnprocessUser($startID, $reqNum) {
        $sql = sprintf(
            'SELECT * FROM %s WHERE id>:id AND status=%d LIMIT :req_num',
            self::$table_name, self::$status['未处理']
        );
        $db = DbManager::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id',      $startID, PDO::PARAM_INT);
        $stmt->bindParam(':req_num', $reqNum,  PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return empty($ret) ? array() : $ret;
    }

    public static function setProcessed($userID) {
        $sql = sprintf(
            'UPDATE %s SET status=%d WHERE user_id=:user_id',
            self::$table_name, self::$status['处理完成']
        );
        $db = DbManager::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
        $ret = $stmt->execute();
        return $ret;
    }

}
