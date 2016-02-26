<?php

class dao_board {

    protected static $table_name = 'board';

    public function save($boardInfo) {
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
            'insert into %s (board_id, board_title, user_id, description, count, create_at, updated_at, create_time, update_time, status) values (:board_id, :board_title, :user_id, :description, :count, :create_at, :updated_at, :create_time, :update_time, :status)',
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

}
