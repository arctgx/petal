<?php
class dict {

    // board 表常量
    public static $boradStatus = array(
        '待抓取'   => 0,
        '抓取完成' => 1,
    );

    // file 表常量
    public static $fileStatus = array(
        '未下载'   => 0,
        '已下载'   => 1,
        '下载失败' => 2, // 下载失败 404
    );

}