<?php

class ConvertData {

    // 从 board 信息中提取用户信息
    public static function extractUserDataFromBoardInfo($boardInfo) {
        return array(
            'user_id'   => $boardInfo['user']['user_id'],
            'user_name' => $boardInfo['user']['username'],
            'user_url'  => $boardInfo['user']['urlname'],
        );
    }

    // 从user信息中
    public static function extractUserDataFromUserInfo($userInfo) {
        return array(
            'user_id'   => $userInfo['user_id'],
            'user_name' => $userInfo['username'],
            'user_url'  => $userInfo['urlname'],
        );
    }

    // 从 board 信息中提取画板信息
    public static function extractBoardDataFromBoardInfo($boardInfo) {
        return array(
            'board_id'       => $boardInfo['board_id'],
            'board_title'    => $boardInfo['title'],
            'user_id'        => $boardInfo['user_id'],
            'description'    => $boardInfo['description'],
            'count'          => $boardInfo['pin_count'],
            'create_at'      => $boardInfo['created_at'],
            'updated_at'     => $boardInfo['updated_at'],
        );
    }
}
