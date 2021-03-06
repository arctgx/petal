<?php

class Util {

    protected static $_user_dict = array();

    protected static $_board_dict = array();

    // 更新一个用户信息
    public static function upOneUser($userInfo) {
        if (isset(self::$_user_dict[$userInfo['user_id']])) {
            return 1;
        }
        self::$_user_dict[$userInfo['user_id']] = 1;

        $savedUserInfo = dao_user::getByUserID($userInfo['user_id']);
        if (!empty($savedUserInfo)) {
            printf("already has user,  id[%d] name[%s]\n", $userInfo['user_id'], $userInfo['user_name']);
            self::$_user_dict[$userInfo['user_id']] = 1;
            return 1;
        }

        $ret = dao_user::save($userInfo);
        printf("save user info, id[%d] name[%s], ret [%d]\n", $userInfo['user_id'], $userInfo['user_name'], intval($ret));
        return $ret;
    }

    // 更新一个画板信息
    public static function upOneBoardToDB($boardInfo) {
        $boardID = $boardInfo['board_id'];
        if (isset(self::$_board_dict[$boardID]) && self::$_board_dict[$boardID]>RUN_START_TIME) {
            return 1;
        }
        self::$_board_dict[$boardID] = 1;

        $savedBoardInfo = dao_board::getByBoardID($boardInfo['board_id']);

        $action = '';
        if (empty($savedBoardInfo)) {
            $ret = dao_board::save($boardInfo);
            $action = 'save';
        } elseif($boardInfo['updated_at'] > RUN_START_TIME) {
            $ret = dao_board::up($boardInfo);
            $action = 'update';
        } else {
            printf("board no need update, id %d\n", $boardInfo['board_id']);
            return true;
        }

        printf("%s board, borad id %d\n", $action, $boardInfo['board_id']);
        return $ret;
    }

    // 更新图片信息列表
    public static function upOnePicToDB($pinID, $boardID, $picID, $userID) {
        $curTime = time();
        $data = array(
            'pin_id'        => $pinID,
            'board_id'      => $boardID,
            'user_id'       => $userID,
            'file_id'       => $picID,
            'create_time'   => time(),
        );
        $ret = dao_BoardPic::saveAndUp($data);
        printf("save&up board_pic, pin_id[%d] board_id[%d] pic_id[%d] ret[%d]\n", $pinID, $boardID, $picID, intval($ret));
        return $ret;
    }

    // 更新文件信息 1 已经有不需要再更新 true 更新成功 false 更新失败
    public static function upOnePic($picInfo) {

        $fileSaveInfo = dao_file::getByFileID($picInfo['file_id']);
        if (!empty($fileSaveInfo)) {
            printf("file %d is saved\n", $picInfo['file_id']);
            return 1;
        }

        $ret = dao_file::save($picInfo);
        printf("save file, id[%d] ret[%d]\n", $picInfo['file_id'], intval($ret));
        return $ret;
    }
}