<?php
class dao_BoardPic {

    protected static $table_name = 'board_pic';

    // 根据board id 获取画板信息
    public static function saveAndUp ($data) {
        $curTime = time();
        $sql = sprintf(
            'INSERT INTO %s (board_id, user_id, file_id, create_time, update_time) values (:board_id, :user_id, :file_id, :create_time, :update_time) ON DUPLICATE KEY UPDATE update_time=:update_time',
            self::$table_name
        );

        $db = DbManager::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':board_id',    $data['board_id'], PDO::PARAM_INT);
        $stmt->bindParam(':user_id',     $data['user_id'],  PDO::PARAM_INT);
        $stmt->bindParam(':file_id',     $data['file_id'],  PDO::PARAM_INT);
        $stmt->bindParam(':create_time', $curTime,          PDO::PARAM_INT);
        $stmt->bindParam(':update_time', $curTime,          PDO::PARAM_INT);
        $ret = $stmt->execute();
        return $ret;
    }

}
