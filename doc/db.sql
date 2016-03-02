CREATE DATABASE petal_beauty DEFAULT CHARACTER SET utf8;

USE `petal_beauty`;

-- 更新任务
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL COMMENT '名称',
  `url_key` varchar(46) NOT NULL COMMENT 'url关键词',
  `update_time` int NOT NULL COMMENT '最后更新时间',
  `last_pin_id` bigint(20) NOT NULL DEFAULT 0 COMMENT '最后更新的 pin_id',

  PRIMARY KEY (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分类表';
INSERT INTO `category` (`name`, `url_key`, `update_time`, `last_pin_id`) VALUES ('美女', 'beauty', 0, 0);

-- 用户
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) NOT NULL COMMENT '用户id',
  `user_name` varchar(128) NOT NULL COMMENT '用户姓名',
  `user_url`  varchar(128) NOT NULL DEFAULT '' COMMENT '访问url',
  `create_time` int NOT NULL COMMENT '创建时间',

  PRIMARY KEY (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户';

-- 画板
DROP TABLE IF EXISTS `board`;
CREATE TABLE `board` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `board_id` bigint(64) NOT NULL COMMENT '画板id',
  `board_title` varchar(255) NOT NULL DEFAULT '' COMMENT '画板名称',
  `description` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `user_id` bigint NOT NULL COMMENT '创建用户id',
  `count` int NOT NULL DEFAULT 0 COMMENT '图片数',
  `create_at` int NOT NULL COMMENT '创建时间',
  `updated_at` int NOT NULL COMMENT '最后更新时间',

  `create_time` int NOT NULL COMMENT '抓取生成时间',
  `update_time` int NOT NULL COMMENT '最后抓取更新时间',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态 0 待抓取 1 抓取完成',

  PRIMARY KEY (`id`),
  key `board_id` (`board_id`),
  key `user_id` (`user_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='画板';


-- board_pic
DROP TABLE IF EXISTS `board_pic`;
CREATE TABLE `board_pic` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `board_id` bigint(20) NOT NULL COMMENT '画板id',
    `user_id` bigint(20) NOT NULL COMMENT '用户id',
    `file_id` bigint(20) NOT NULL COMMENT '图片id',
    `create_time` int NOT NULL COMMENT '生成时间',
    `update_time` int NOT NULL COMMENT '更新时间',
    `status` int NOT NULL DEFAULT 0 COMMENT '状态 0 需要更新 1 不需要',

    PRIMARY KEY (`id`),
    UNIQUE KEY `board_pic` (`board_id`, `file_id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='画板-图片关联表';

-- file
DROP TABLE IF EXISTS `file`;
CREATE TABLE `file` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `file_id` bigint(20) NOT NULL COMMENT '文件id',
    `file_key` varchar(128) NOT NULL COMMENT '文件key，用于拼文件url',
    `file_type` varchar(32) NOT NULL COMMENT '文件类型',
    `raw_text` varchar(255) NOT NULL DEFAULT '' COMMENT '文件描述',

    `create_time` int NOT NULL COMMENT '记录生成时间',
    `dl_time` int NOT NULL DEFAULT 0 COMMENT '下载完成时间',
    `dl_status` tinyint NOT NULL DEFAULT 0 COMMENT '下载状态 0 未下载 1 已经下载',

    PRIMARY KEY (`id`),
    KEY `file_id` (`file_id`),
    KEY `file_key` (`file_key`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图片文件表';
