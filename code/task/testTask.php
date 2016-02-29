<?php

class testTask extends task_base {

    public function testAction() {

        $boardID = 1161553;
        $boardID = 19723473;
        // 获取
        $boardInfo = petal::boardInfo($boardID);
        var_dump($boardInfo);

        // 更新用户信息
        $userInfo = array(
            'user_id'   => $boardInfo['user']['user_id'],
            'user_name' => $boardInfo['user']['username'],
        );
        Util::upOneUser($userInfo);

        // 更新画板信息
        $boardData = array(
            'board_id'       => $boardInfo['board_id'],
            'board_title'    => $boardInfo['title'],
            'user_id'        => $boardInfo['user_id'],
            'description'    => $boardInfo['description'],
            'count'          => $boardInfo['pin_count'],
            'create_at'      => $boardInfo['created_at'],
            'updated_at'     => $boardInfo['updated_at'],
        );
        Util::upOneBoardToDB($boardData);

        $picListData = petal::getBoardPicList($boardID);
        $curTime = time();
        foreach ($picListData as $onePic) {
            // var_dump($onePic);exit();

            // 更新 画板与图片的从属关系
            Util::upOnePicToDB($onePic['board_id'], $onePic['file_id'], $onePic['user_id']);

            // 更新图片信息
            $fileInfo = $onePic['file'];
            $picData = array(
                'file_id'       => $onePic['file_id'],
                'file_key'      => $fileInfo['key'],
                'file_type'     => $fileInfo['type'],
                'raw_text'      => (string)$onePic['raw_text'],
                'create_time'   => $curTime,
                'dl_time'       => 0,
                'dl_status'     => dict::$fileStatus['未下载'],
            );
            Util::upOnePic($picData);

        }

        printf("task end\n");

    }


}
