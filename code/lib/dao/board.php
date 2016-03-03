<?php

class dao_board {

    protected static $table_name = 'board';

    // 根据board id 获取画板信息
    public static function getByBoardID ($boardID) {
        $sql = sprintf('SELECT * FROM %s WHERE board_id=:board_id', self::$table_name);
        $db = DbManager::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':board_id', $boardID, PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetch(PDO::FETCH_ASSOC);
        return empty($ret) ? array() : $ret;
    }

    // 更新一个board
    public static function up($boardInfo) {
        $curTime = time();

        $data = array(
            'board_id'          => $boardInfo['board_id'],
            'board_title'       => $boardInfo['board_title'],
            'user_id'           => $boardInfo['user_id'],
            'description'       => $boardInfo['description'],
            'count'             => $boardInfo['count'],
            'create_at'         => $boardInfo['create_at'],
            'updated_at'        => $boardInfo['updated_at'],

            'create_time'       => $curTime,
            'update_time'       => $curTime,
            'status'            => dict::$boradStatus['待抓取'],
        );

        $db = DbManager::getDB();
        $sql = sprintf(
            'UPDATE %s SET board_title=:board_title, description=:description, count=:count, updated_at=:updated_at, update_time=:update_time WHERE board_id=:board_id',
            self::$table_name
        );
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':board_title',    $data['board_title'],     PDO::PARAM_STR);
        $stmt->bindParam(':description',    $data['description'],     PDO::PARAM_STR);
        $stmt->bindParam(':count',          $data['count'],           PDO::PARAM_INT);
        $stmt->bindParam(':board_id',       $data['board_id'],        PDO::PARAM_INT);
        $stmt->bindParam(':updated_at',     $data['updated_at'],      PDO::PARAM_INT);
        $stmt->bindParam(':update_time',    $data['update_time'],     PDO::PARAM_INT);

        $ret = $stmt->execute();
        if (!$ret) {
            printf("mysql err \n");
            var_dump($stmt->errorInfo());
        }
        return $ret;
    }

    // 保存一个board
    public static function save($boardInfo) {
        $curTime = time();

        $data = array(
            'board_id'          => $boardInfo['board_id'],
            'board_title'       => $boardInfo['board_title'],
            'user_id'           => $boardInfo['user_id'],
            'description'       => $boardInfo['description'],
            'count'             => $boardInfo['count'],
            'create_at'         => $boardInfo['create_at'],
            'updated_at'        => $boardInfo['updated_at'],

            'create_time'       => $curTime,
            'update_time'       => $curTime,
            'status'            => dict::$boradStatus['待抓取'],
        );

        $db = DbManager::getDB();
        $sql = sprintf(
            'INSERT INTO %s (board_id, board_title, user_id, description, count, create_at, updated_at, create_time, update_time, status) values (:board_id, :board_title, :user_id, :description, :count, :create_at, :updated_at, :create_time, :update_time, :status)',
            self::$table_name
        );
        $stmt = $db->prepare($sql);

        $stmt->bindParam(':board_id',       $data['board_id'],        PDO::PARAM_INT);
        $stmt->bindParam(':board_title',    $data['board_title'],     PDO::PARAM_STR);
        $stmt->bindParam(':user_id',        $data['user_id'],         PDO::PARAM_INT);
        $stmt->bindParam(':description',    $data['description'],     PDO::PARAM_STR);
        $stmt->bindParam(':count',          $data['count'],           PDO::PARAM_INT);
        $stmt->bindParam(':create_at',      $data['create_at'],       PDO::PARAM_INT);
        $stmt->bindParam(':updated_at',     $data['updated_at'],      PDO::PARAM_INT);
        $stmt->bindParam(':create_time',    $data['create_time'],     PDO::PARAM_INT);
        $stmt->bindParam(':update_time',    $data['update_time'],     PDO::PARAM_INT);
        $stmt->bindParam(':status',         $data['status'],          PDO::PARAM_STR);

        $ret = $stmt->execute();
        if (!$ret) {
            printf("mysql err \n");
            var_dump($stmt->errorInfo());
        }
        return $ret;
    }

    // 获取需要处理的board列表
    public static function getNeedProcessBoardList($lastID, $reqNum) {
        $sql = sprintf(
            'SELECT * FROM %s WHERE id>:board_id AND status=%d LIMIT :req_num',
            self::$table_name, dict::$boradStatus['待抓取']
        );
        // printf("sql %s\n", $sql);
        $db = DbManager::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':board_id', $lastID,  PDO::PARAM_INT);
        $stmt->bindParam(':req_num',  $reqNum,  PDO::PARAM_INT);
        $stmt->execute();
        $ret = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return empty($ret) ? array() : $ret;
    }

    public static function setProcessed ($boardID) {
        $sql = sprintf(
            'UPDATE %s SET status=%d, update_time=%d WHERE board_id=:board_id',
            self::$table_name, dict::$boradStatus['抓取完成'], time()
        );

        $db = DbManager::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':board_id', $boardID, PDO::PARAM_INT);
        $ret = $stmt->execute();
        return $ret;
    }
}
