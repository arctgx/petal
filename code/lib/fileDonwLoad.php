<?php

class fileDonwLoad {

    protected static $_type = array(
        'image/gif'     => 'gif',
        'image/jpeg'    => 'jpg',
        'image/jpg'     => 'jpg',
        'image/pjpeg'   => 'jpg',
        'image/png'     => 'png',
    );

    protected static $_default_type = 'jpeg';

    protected static $_dl_path = '';

    protected static $_ch = null;

    protected static function _init() {
        if (self::$_dl_path == '') {
            $taskConf = config::getTaskConf();
            self::$_dl_path = $taskConf['download_path'];
            if (SYS == 'WIN') {
                self::$_dl_path = mb_convert_encoding(self::$_dl_path, 'gbk', 'utf8');
            }
            // var_dump(self::$_dl_path);exit();
            self::$_ch = curl_init();
        }
    }

    // 下载一个图片
    public static function dlOnePic($key, $type, $retry=4) {
        $timeOut = 8;
        $tryTimes = 1;
        while ($tryTimes < $retry) {
            if ($tryTimes>1) {
                printf("retry %s\n", $key);
            }
            $ret = self::_dl_one_pic($key, $type, $timeOut);
            if ($ret) {
                return true;
            }
            $timeOut *= 2;
            $tryTimes++;
        }
        return false;
    }

    protected static function _dl_one_pic($key, $type, $timeOut) {
        self::_init();

        $url = 'http://img.hb.aicdn.com/'.$key;

        $nameMd5 = md5($key);
        $savePath = self::$_dl_path.$nameMd5[0].DIRECTORY_SEPARATOR.$nameMd5[1].DIRECTORY_SEPARATOR;
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }
        $filename = self::getFileName($key, $type);
        $fhOutput = fopen($savePath.$filename, 'w');

        // $ch = curl_init($url);
        $ch = curl_copy_handle(self::$_ch);

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FILE, $fhOutput);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeOut);

        // curl_setopt($ch, CURLOPT_FORBID_REUSE, false);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        //     'Connection: Keep-Alive',
        //     'Keep-Alive: 300',
        // ));

        $ret = curl_exec($ch);
        if ($ret==false) {
            printf("curl return false, url[%s] time[%s], httpcode[%d], errno[%d], error[%s]\n", $url, date('Y-m-d H:i:s'), curl_getinfo($ch, CURLINFO_HTTP_CODE), curl_errno($ch), curl_error($ch));
            curl_close($ch);
            return false;
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode!=200) {
            printf("curl return http_code %d, url[%s] time[%s]\n", $httpCode, $url, date('Y-m-d H:i:s'));
            // curl_close($ch);
            return false;
        }
        // curl_close($ch);
        return true;
    }

    public static function getFileInfo ($key, $type) {
        self::_init();

        $nameMd5 = md5($key);
        $savePath = self::$_dl_path.$nameMd5[0].DIRECTORY_SEPARATOR.$nameMd5[1].DIRECTORY_SEPARATOR;
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }
        $filename = self::getFileName($key, $type);

        $filePath = $savePath . $filename;
        if (!file_exists($filePath)) {
            // printf("file %s not exists\n", $filePath);
            return false;
        }

        $fileSize = filesize($filePath);
        $fileMd5  = md5_file($filePath);
        return array(
            'file_size' => $fileSize,
            'file_md5'  => $fileMd5,
        );
    }

    public static function getFileName($key, $type) {
        if (isset(self::$_type[$type])) {
            return $key.'.'.self::$_type[$type];
        }
        return $key.'.'.self::$_default_type;
    }
}
