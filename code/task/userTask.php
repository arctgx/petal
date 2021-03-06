<?php

class userTask extends task_base {

    //
    public function oneAction() {
        printf("task start at %s\n", date('Y-m-d H:i:s'));

        $userID = $this->getParam('user_id', 0);
        printf("user id [%s]\n", $userID);

        $this->upOneUserFollow($userID);

        printf("task end at %s\n", date('Y-m-d H:i:s'));
    }

    public function allAction() {
        printf("task start at %s\n", date('Y-m-d H:i:s'));
        $lastID = 0;
        $reqNum = 10;

        while (true) {
            $userList = dao_UserData::getUnprocessUser($lastID, $reqNum);
            if (empty($userList)) {
                break;
            }
            // var_dump($userList);exit();

            foreach ($userList as $one) {
                $lastID = $one['id'];
                $this->upOneUserFollow($one['user_id']);
                printf("[%s] %s %d done\n", date('Y-m-d H:i:s'), $one['user_name'], $one['id']);
            }
            // break; // for test
        }

        printf("task end at %s\n", date('Y-m-d H:i:s'));
    }

    protected function upOneUserFollow($userID) {
        // 用户关注
        $followList = petal::getUserFollow($userID);
        $cnt = count($followList);
        if (!empty($followerList)) {
            foreach ($followList as $oneFollow) {
                UserData::upOneUser($oneFollow);
                UserData::addFollow($oneFollow['user_id'], $userID);
            }
        }
        printf("[%s] user id[%d] follow [%d]\n", date('Y-m-d H:i:s'), $userID, $cnt);

        // 用户粉丝
        $followerList = petal::getUserFollower($userID);
        if (!empty($followerList)) {
            foreach ($followerList as $oneFollow) {
                UserData::upOneUser($oneFollow);
                UserData::addFollow($userID, $oneFollow['user_id']);
            }
        }
        $cnt = count($followerList);
        printf("[%s] user id[%d] follower [%d]\n", date('Y-m-d H:i:s'), $userID, $cnt);

        dao_UserData::setProcessed($userID);
    }

}
