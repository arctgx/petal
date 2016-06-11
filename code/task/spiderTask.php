<?php

class spiderTask extends task_base {

    // 从一个 画板 开始更新
    public function boardAction() {
        printf("task start at %s\n", date('Y-m-d H:i:s'));

        // 参数
        $boardID = $this->getParam('boardID', 0);
        if ($boardID<=0) {
            printf("invalid boardID %d\n", $boardID);
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
                Util::upOnePicToDB($onePic['pin_id'], $onePic['board_id'], $onePic['file_id'], $onePic['user_id']);

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
        dao_board::setProcessed($boardID);

        printf("task end at %s\n", date('Y-m-d H:i:s'));
    }

    // 美女分类
    public function beautyAction() {
        printf("task start at %s\n", date('Y-m-d H:i:s'));

        $all = $this->getParam('all', 0);
        $beauryCategoryID = 1;
        if ($all) {
            $max = 0;
        } else {
            $info = dao_Category::getInfoByID($beauryCategoryID);
            // var_dump($info);exit();
            $max = $info['last_pin_id'];
        }
        printf("max id %d\n", $max);

        $cnt = 0;
        while (true) {
            dao_Category::upPinID($beauryCategoryID, $max);

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
            }
        }

        printf("task end at %s, process %d\n", date('Y-m-d H:i:s'), $cnt);
    }

    // 婚礼分类
    public function weddingAction() {
        printf("task start at %s\n", date('Y-m-d H:i:s'));

        $all = $this->getParam('all', 0);
        $weddingCategoryID = 2;
        if ($all) {
            $max = 0;
        } else {
            $info = dao_Category::getInfoByID($weddingCategoryID);
            // var_dump($info);exit();
            $max = $info['last_pin_id'];
        }
        printf("max id %d\n", $max);

        $cnt = 0;
        while (true) {
            dao_Category::upPinID($weddingCategoryID, $max);

            $picList = petal::getWeddingPicList($max);
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
            }
        }

        printf("task end at %s, process %d\n", date('Y-m-d H:i:s'), $cnt);
    }

    // 美图分类
    public function quotesAction() {
        printf("task start at %s\n", date('Y-m-d H:i:s'));

        $all = $this->getParam('all', 0);
        $quotesID = 3;
        if ($all) {
            $max = 0;
        } else {
            $info = dao_Category::getInfoByID($quotesID);
            // var_dump($info);exit();
            $max = $info['last_pin_id'];
        }
        printf("max id %d\n", $max);

        $cnt = 0;
        while (true) {
            dao_Category::upPinID($quotesID, $max);

            $picList = petal::getQuotesPicList($max);
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
            }
        }

        printf("task end at %s, process %d\n", date('Y-m-d H:i:s'), $cnt);
    }

    // 动漫分类
    public function animeAction() {
        printf("task start at %s\n", date('Y-m-d H:i:s'));

        $all = $this->getParam('all', 0);
        $animeID = 4;
        if ($all) {
            $max = 0;
        } else {
            $info = dao_Category::getInfoByID($animeID);
            // var_dump($info);exit();
            $max = $info['last_pin_id'];
        }
        printf("max id %d\n", $max);

        $cnt = 0;
        while (true) {
            dao_Category::upPinID($animeID, $max);

            $picList = petal::getAnimePicList($max);
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
            }
        }

        printf("task end at %s, process %d\n", date('Y-m-d H:i:s'), $cnt);
    }

    // 从数据库里更新
    public function boardPicAction() {
        printf("task begin at %s\n", date('Y-m-d H:i:s'));

        $lastID = 0;
        while (true) {
            $boardList = dao_board::getNeedProcessBoardList($lastID, 10);
            if (empty($boardList)) {
                break;
            }
            foreach ($boardList as $oneBoard) {
                $lastID = $oneBoard['id'];
                // todo
                // var_dump($oneBoard);

                $boardID = $oneBoard['board_id'];
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
                        Util::upOnePicToDB($onePic['pin_id'], $onePic['board_id'], $onePic['file_id'], $onePic['user_id']);

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
                dao_board::setProcessed($boardID);

            }
            printf("last id %d\n", $lastID);
            // break; // for test
        }

        printf("task end at %s\n", date('Y-m-d H:i:s'));
    }

}
