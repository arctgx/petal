<?php

// 访问花瓣网
class petal {

    // 根据 boardid 获取画板信息
    public static function boardInfo ($boardID) {
        printf("get board info, boardID is %d\n", $boardID);

        $url = sprintf('http://api.huaban.com/boards/%d', $boardID);
        try {
            $data = self::curl_get($url, array());
        } catch (Exception $e) {
            printf("%s\n", $e->getMessage());
            return false;
        }

        $boardInfo = json_decode($data, true);
        if ($boardInfo === NULL) {
            printf("json_decode fail, boardID %d, data %s\n", $boardID, $data);
            return false;
        }

        $boardInfo = $boardInfo['board'];
        return $boardInfo;
        /*
        return array(
            'board_id'       => $boardInfo['board_id'],
            'board_title'    => $boardInfo['title'],
            'user_id'        => $boardInfo['user_id'],
            'description'    => $boardInfo['description'],
            'count'          => $boardInfo['pin_count'],
            'create_at'      => $boardInfo['created_at'],
            'updated_at'     => $boardInfo['updated_at'],
        );
        */
    }

    // 根据 boardID 获取画板下所有图片列表
    public static function getBoardPicList($boardID) {
        printf("get board pic list, board id is %d\n", $boardID);

        $picList = array();
        $maxID = 0;
        $baseUrl = sprintf("http://api.huaban.com/boards/%d/pins/?limit=20", $boardID);

        while (true) {
            if ($maxID == 0) {
                $url = $baseUrl;
            } else {
                $url = $baseUrl."&max=".$maxID;
            }

            printf("url %s\n", $url);
            try {
                $data = self::curl_get($url, array());
            } catch (Exception $e) {
                printf("%s\n", $e->getMessage());
                break;
            }

            $boardPicList = json_decode($data, true);
            if ($boardPicList === NULL) {
                printf("json_decode fail, boardID %d, data %s\n", $boardID, $data);
                break;
            }
            $boardPicList = $boardPicList['pins'];
            if (empty($boardPicList)) {
                break;
            }
            // var_dump($boardPicList);exit();
            foreach ($boardPicList as $onePic) {
                $maxID = $onePic['pin_id'];
                $picList[] = $onePic;
            }

            // for test
            // break;
        }

        return $picList;
    }

    protected static function curl_get($url, $params) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            $url);
        curl_setopt($ch, CURLOPT_HEADER,         0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (curl_errno($ch)) {
            $msg = sprintf("curl_err, url, no %d, msg[%s]", $url, curl_errno($ch), curl_error($ch));
            throw new Exception($msg, 1);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

}