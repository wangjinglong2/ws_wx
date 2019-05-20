CREATE TABLE `XT_WxMsg` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID编码',
  `groupid` int(11) NOT NULL DEFAULT '0',
  `xtuser` varchar(100) NOT NULL COMMENT '接收用户',
  `message` text NOT NULL COMMENT '消息内容',
  `pdate` datetime DEFAULT NULL COMMENT '发送日期',
  `flag` int(1) DEFAULT '0' COMMENT '消息状态',
  PRIMARY KEY (`id`),
  KEY `xtuser` (`xtuser`),
  KEY `pdate` (`pdate`),
  KEY `groupid` (`groupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='消息列表'