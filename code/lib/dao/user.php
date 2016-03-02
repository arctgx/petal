<?php
class dao_user {

    protected static $table_name = 'user';

    // 根据board id 获取画板信息
    public static function getByUserID ($userID) {
        $sql = sprintf('SELECT * FROM %s WHERE user_id=:user_id', self::$table_name);
        $db = DbManager::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':user_id', $userID, PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetch(PDO::FETCH_ASSOC);
        return empty($ret) ? array() : $ret;
    }

    // 保存一个board
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

}
