<?php

class userData {

    // 更新一个用户信息
    public static function upOneUser($userInfo) {

        $savedUserInfo = dao_UserData::getByUserID($userInfo['user_id']);
        if (!empty($savedUserInfo)) {
            // printf("already has user,  id[%d] name[%s]\n", $userInfo['user_id'], $userInfo['user_name']);
            return 1;
        }

        $ret = dao_UserData::save($userInfo);
        // printf("save user info, id[%d] name[%s], ret [%d]\n", $userInfo['user_id'], $userInfo['user_name'], intval($ret));
        return $ret;
    }

    public static function addFollow($followID, $followerID) {
        $saveInfo = dao_Follow::getInfo($followID, $followerID);
        if (!empty($saveInfo)) {
            // printf("already has follow info [follow_id[%d] follower_id[%d]]\n", $followID, $followerID);
            return 1;
        }
        $ret = dao_Follow::save($followID, $followerID);
        // printf("save follow info follow_id[%d] follower_id[%d] ret[%d]\n", $followID, $followerID, intval($ret));
        return $ret;
    }

}