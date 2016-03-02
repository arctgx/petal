<?php

class spiderTask extends task_base {

    // 从一个 画板 开始更新
    public function boardAction() {
        printf("task start at %s\n", date('Y-m-d H:i:s'));

        // 参数
        $boardID = $this->getParam('boardID', 0);
        if ($boardID<=0) {
            printf("invalid boardID %d\n", $boardID);
            exit();
        } else {
            printf("boardID %d\n", $boardID);
        }

        $boardInfo = petal::boardInfo($boardID);

        $userData = ConvertData::extractUserDataFromBoardInfo($boardInfo);
        Util::upOneUser($userData);

        $boardData = ConvertData::extractBoardDataFromBoardInfo($boardInfo);
        Util::upOneBoardToDB($boardData);

        $maxID = 0;
        while (true) {
            $picListData = petal::getBoardPicList($boardID, $maxID);
            if (empty($picListData)) {
                break;
            }

            $curTime = time();
            foreach ($picListData as $onePic) {
                // var_dump($onePic);exit();
                $maxID = $onePic['pin_id'];

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
        }


        printf("task end at %s\n", date('Y-m-d H:i:s'));
    }

    // 美女分类
    public function beautyAction() {
        printf("task start at %s\n", date('Y-m-d H:i:s'));

        $processNum = $this->getParam('processNum', 10);
        if ($processNum<=0) {
            printf("invalid param processNum[%d]\n", $processNum);
            exit();
        } else {
            printf("param processNum[%d]\n", $processNum);
        }

        $max = 0;
        $cnt = 0;
        while ($cnt<=$processNum) {
            $picList = petal::getBeautyPicList($max);
            if (empty($picList)) {
                break;
            }

            $curTime = time();
            foreach ($picList as $onePic) {
                $max = $onePic['pin_id'];
                $userData = ConvertData::extractUserDataFromUserInfo($onePic['user']);
                Util::upOneUser($userData);

                $boardData = ConvertData::extractBoardDataFromBoardInfo($onePic['board']);
                Util::upOneBoardToDB($boardData);

                Util::upOnePicToDB($onePic['board_id'], $onePic['file_id'], $onePic['user_id']);

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
                $ret = Util::upOnePic($picData);
                if ($ret===true) {
                    $cnt++;
                }
                if ($cnt<=$processNum) {
                    break;
                }
            }
        }

        printf("task end at %s, process %d\n", date('Y-m-d H:i:s'), $cnt);
    }

}
