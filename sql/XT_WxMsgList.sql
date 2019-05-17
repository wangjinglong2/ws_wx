CREATE TABLE `XT_WxMsgList` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID编码',
  `opercode` int(11) NOT NULL COMMENT '操作码，2002（客服发送信息），2003（客服接收消息）',
  `openid` varchar(100) NOT NULL COMMENT 'openid',
  `text` text NOT NULL COMMENT '消息内容',
  `ctime` datetime DEFAULT NULL COMMENT '操作时间',
  `worker` varchar(20) NOT NULL COMMENT '完整客服帐号，格式为：帐号前缀@公众号微信号',
  `cdate` date NOT NULL COMMENT '建立日期',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`),
  KEY `cdate` (`cdate`),
  KEY `worker` (`worker`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='公众号客服聊天记录'