<?php
class dao_Category {

    protected static $table_name = 'category';

    // 更新
    public static function getInfoByID($id) {
        $sql = sprintf(
            'SELECT * FROM %s WHERE id=:id',
            self::$table_name
        );
        $db = DbManager::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id,  PDO::PARAM_INT);
        $ret = $stmt->execute();
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return empty($data) ? array() : $data;
    }

    public static function upPinID($id, $pinID) {
        $sql = sprintf(
            'UPDATE %s SET update_time=%d, last_pin_id=:last_pin_id WHERE id=:id',
            self::$table_name, time()
        );
        $db = DbManager::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id',             $id,     PDO::PARAM_INT);
        $stmt->bindParam(':last_pin_id',    $pinID,  PDO::PARAM_INT);
        $ret = $stmt->execute();

        return $ret;
    }

}
