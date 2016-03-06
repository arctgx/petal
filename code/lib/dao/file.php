<?php
class dao_file {

    protected static $table_name = 'file';

    // 根据board id 获取画板信息
    public static function getByFileID ($fileID) {
        $sql = sprintf('SELECT * FROM %s WHERE file_id=:file_id', self::$table_name);
        $db = DbManager::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':file_id', $fileID, PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetch(PDO::FETCH_ASSOC);
        return empty($ret) ? array() : $ret;
    }

    // 获取未下载的图片信息
    public static function getUndlPicList($startID, $reqNum) {
        $sql = sprintf(
            'SELECT * FROM %s WHERE id>:id AND dl_status=%d LIMIT :req_num',
            self::$table_name, dict::$fileStatus['未下载']
        );
        $db = DbManager::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id',         $startID,                       PDO::PARAM_INT);
        $stmt->bindParam(':req_num',    $reqNum,                        PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($ret);
        return empty($ret) ? array() : $ret;
    }

    // 获取没有文件信息的图片
    public static function getNeedCompleteInfoPicList($startID, $reqNum) {
        $sql = sprintf(
            'SELECT * FROM %s WHERE id>:id AND dl_status=%d AND file_size=0 LIMIT :req_num',
            self::$table_name, dict::$fileStatus['已下载']
        );
        $db = DbManager::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id',         $startID,                       PDO::PARAM_INT);
        $stmt->bindParam(':req_num',    $reqNum,                        PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // var_dump($ret);
        return empty($ret) ? array() : $ret;
    }

    // 更新状态
    public static function upPicDownloaded($id) {
        $sql = sprintf(
            'UPDATE %s SET dl_status=%d, dl_time=%d WHERE id=:id',
            self::$table_name, dict::$fileStatus['已下载'], time()
        );
        $db = DbManager::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $ret = $stmt->execute();
        // printf("no %s err %s\n", serialize($stmt->errorCode()), serialize($stmt->errorInfo()));
        return $ret;
    }

    // 更新信息
    public static function upPicInfo($id, $fileInfo) {
        $sql = sprintf(
            'UPDATE %s SET file_size=:file_size, file_md5=:file_md5 WHERE id=:id',
            self::$table_name
        );
        $db = DbManager::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':id',        $id,                    PDO::PARAM_INT);
        $stmt->bindParam(':file_size', $fileInfo['file_size'], PDO::PARAM_INT);
        $stmt->bindParam(':file_md5',  $fileInfo['file_md5'],  PDO::PARAM_STR);
        $ret = $stmt->execute();
        // printf("no %s err %s\n", serialize($stmt->errorCode()), serialize($stmt->errorInfo()));
        return $ret;
    }

    // 保存一个board
    public static function save($fileInfo) {
        $curTime = time();

        $data = array(
            'file_id'        => $fileInfo['file_id'],
            'file_key'       => $fileInfo['file_key'],
            'file_type'      => $fileInfo['file_type'],
            'raw_text'       => $fileInfo['raw_text'],
            'create_time'    => $fileInfo['create_time'],
            'dl_time'        => $fileInfo['dl_time'],
            'dl_status'      => $fileInfo['dl_status'],
        );

        $db = DbManager::getDB();
        $sql = sprintf(
            'INSERT INTO %s (file_id, file_key, file_type, raw_text, create_time, dl_time, dl_status) values (:file_id, :file_key, :file_type, :raw_text, :create_time, :dl_time, :dl_status)',
            self::$table_name
        );
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':file_id',      $data['file_id'],      PDO::PARAM_INT);
        $stmt->bindParam(':file_key',     $data['file_key'],     PDO::PARAM_STR);
        $stmt->bindParam(':file_type',    $data['file_type'],    PDO::PARAM_STR);
        $stmt->bindParam(':raw_text',     $data['raw_text'],     PDO::PARAM_STR);
        $stmt->bindParam(':create_time',  $data['create_time'],  PDO::PARAM_INT);
        $stmt->bindParam(':dl_time',      $data['dl_time'],      PDO::PARAM_INT);
        $stmt->bindParam(':dl_status',    $data['dl_status'],    PDO::PARAM_INT);

        $ret = $stmt->execute();
        if (!$ret) {
            printf("mysql err \n");
            var_dump($stmt->errorInfo());
        }
        return $ret;
    }

}
