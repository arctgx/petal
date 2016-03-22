<?php
class dao_Follow {

    protected static $table_name     = 'follow';

    public static function getInfo ($followID, $followerID) {
        $sql = sprintf('SELECT * FROM %s WHERE follow=:follow AND follower=:follower', self::$table_name);
        $db = DbManager::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':follow',     $followID,   PDO::PARAM_INT);
        $stmt->bindParam(':follower',   $followerID, PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetch(PDO::FETCH_ASSOC);
        return empty($ret) ? array() : $ret;
    }

    public static function save($followID, $followerID) {
        $curTime = time();

        $db = DbManager::getDB();
        $sql = sprintf(
            'INSERT INTO %s (follow, follower, create_time) values (:follow, :follower, :create_time)',
            self::$table_name
        );
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':follow',       $followID,   PDO::PARAM_INT);
        $stmt->bindParam(':follower',     $followerID, PDO::PARAM_INT);
        $stmt->bindParam(':create_time',  $curTime,    PDO::PARAM_INT);

        $ret = $stmt->execute();
        if (!$ret) {
            printf("mysql err \n");
            var_dump($stmt->errorInfo());
        }
        return $ret;
    }

}
