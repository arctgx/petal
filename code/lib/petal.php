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
    public static function getBoardPicList($boardID, $maxID) {
        printf("get board pic list, board %d maxid[%d]\n", $boardID, $maxID);

        $picList = array();
        if ($maxID==0) {
            $url = sprintf("http://api.huaban.com/boards/%d/pins/?limit=20", $boardID);
        } else {
            $url = sprintf("http://api.huaban.com/boards/%d/pins/?limit=20&max=%d", $boardID, $maxID);
        }

        try {
            $data = self::curl_get($url, array());
        } catch (Exception $e) {
            printf("%s\n", $e->getMessage());
            return $picList;
        }

        $boardPicList = json_decode($data, true);
        if ($boardPicList === NULL) {
            printf("json_decode fail, boardID %d, data %s\n", $boardID, $data);
            return $picList;
        }

        $boardPicList = $boardPicList['pins'];
        if (empty($boardPicList)) {
            return $picList;
        }

        // var_dump($boardPicList);exit();
        foreach ($boardPicList as $onePic) {
            $maxID = $onePic['pin_id'];
            $picList[] = $onePic;
        }

        return $picList;
    }

    // 获取美女分类下的图片列表
    public static function getBeautyPicList($max=0) {
        printf("get beauty class info, max is %d\n", $max);

        if ($max==0) {
            $url = sprintf('http://api.huaban.com/favorite/beauty?limit=20');
        } else {
            $url = sprintf('http://api.huaban.com/favorite/beauty?max=%d&limit=20', $max);
        }

        try {
            $data = self::curl_get($url, array());
        } catch (Exception $e) {
            printf("%s\n", $e->getMessage());
            return false;
        }

        $beautyInfo = json_decode($data, true);
        if ($beautyInfo === NULL) {
            printf("json_decode fail, max %d, data %s\n", $max, $data);
            return false;
        }

        return isset($beautyInfo['pins']) ? $beautyInfo['pins'] : array();
    }

    // 获取婚礼分类下的图片列表
    public static function getWeddingPicList($max=0) {
        printf("get wedding class info, max is %d\n", $max);

        if ($max==0) {
            $url = sprintf('http://api.huaban.com/favorite/wedding_events?limit=20');
        } else {
            $url = sprintf('http://api.huaban.com/favorite/wedding_events?max=%d&limit=20', $max);
        }

        try {
            $data = self::curl_get($url, array());
        } catch (Exception $e) {
            printf("%s\n", $e->getMessage());
            return false;
        }

        $dataInfo = json_decode($data, true);
        if ($dataInfo === NULL) {
            printf("json_decode fail, max %d, data %s\n", $max, $data);
            return false;
        }

        return isset($dataInfo['pins']) ? $dataInfo['pins'] : array();
    }

    // 获取某个用户的关注者
    public static function getUserFollow($userID) {
        printf("getUserFollow, userID %d\n", $userID);

        $retList = array();
        $lastID = 0;
        while (true) {
            if ($lastID==0) {
                $url = sprintf('http://api.huaban.com/users/%s/following/?limit=20', $userID);
                // $url = sprintf('http://huaban.com/%s/following/?limit=20', $userID);
            } else {
                $url = sprintf('http://api.huaban.com/users/%s/following/?max=%s&limit=20', $userID, $lastID);
                // $url = sprintf('http://huaban.com/%s/following/?max=%s&limit=20', $userID, $lastID);
            }
            // printf("call url[%s]\n", $url);

            try {
                $headers = array(
                    'X-Requested-With' => 'XMLHttpRequest',
                );
                $data = self::curl_get($url, array(), $headers);
            } catch (Exception $e) {
                printf("err %s\n", $e->getMessage());
                break;
            }

            $followingData = json_decode($data, true);
            if ($followingData === NULL) {
                printf("json_decode fail, lastID %d, data %s\n", $lastID, $data);
                break;
            }
            if (!isset($followingData['users']) || empty($followingData['users'])) {
                break;
            }
            foreach ($followingData['users'] as $one) {
                // var_dump($one);exit();
                $retList[] = array(
                    'user_id'     => $one['user_id'],
                    'user_name'   => $one['username'],
                    'user_url'    => $one['urlname'],
                    // 'create_time' => $one['']
                );

                $lastID = $one['seq'];
            }

        }

        return $retList;

    }

    // 获取某个用户的粉丝
    public static function getUserFollower($userID) {
        // http://api.huaban.com/users/12247884/followers/?limit=40
        printf("getUserFollower, userID %d\n", $userID);

        $retList = array();
        $lastID = 0;
        while (true) {
            if ($lastID==0) {
                $url = sprintf('http://api.huaban.com/users/%s/followers/?limit=20', $userID);
                // $url = sprintf('http://huaban.com/%s/following/?limit=20', $userID);
            } else {
                $url = sprintf('http://api.huaban.com/users/%s/followers/?max=%s&limit=20', $userID, $lastID);
                // $url = sprintf('http://huaban.com/%s/following/?max=%s&limit=20', $userID, $lastID);
            }
            // printf("call url[%s]\n", $url);

            try {
                // $headers = array(
                //     'X-Requested-With' => 'XMLHttpRequest',
                // );
                // $data = self::curl_get($url, array(), $headers);
                $data = self::curl_get($url, array());
            } catch (Exception $e) {
                printf("err %s\n", $e->getMessage());
                break;
            }

            $followerData = json_decode($data, true);
            if ($followerData === NULL) {
                printf("json_decode fail, lastID %d, data %s\n", $lastID, $data);
                break;
            }
            if (!isset($followerData['users']) || empty($followerData['users'])) {
                break;
            }
            foreach ($followerData['users'] as $one) {
                // var_dump($one);exit();
                $retList[] = array(
                    'user_id'     => $one['user_id'],
                    'user_name'   => $one['username'],
                    'user_url'    => $one['urlname'],
                );

                $lastID = $one['seq'];
            }

        }

        return $retList;
    }

    protected static function curl_get($url, $params, $headers=array()) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            $url);
        curl_setopt($ch, CURLOPT_HEADER,         0);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,        5);
        if (is_array($headers) && !empty($headers)) {
            $setHeader = self::convertHeader($headers);
            curl_setopt($ch, CURLOPT_HTTPHEADER , $setHeader);
        }

        if (curl_errno($ch)) {
            $msg = sprintf("curl_err, url, no %d, msg[%s]", $url, curl_errno($ch), curl_error($ch));
            throw new Exception($msg, 1);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    protected static function convertHeader($headerArr) {
        $ret = array();
        if (is_array($headerArr) && !empty($headerArr)) {
            foreach ($headerArr as $key => $value) {
                $ret[] = $key.':'.$value;
            }
        }
        return $ret;
    }
}