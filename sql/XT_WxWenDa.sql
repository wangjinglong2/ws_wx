CREATE TABLE `XT_WxWenDa` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID编码',
  `type` int(1) NOT NULL DEFAULT '0' COMMENT '分类',
  `protype` varchar(20) NOT NULL COMMENT '产品类型',
  `keywords` varchar(50) NOT NULL COMMENT '关键字',
  `question` varchar(200) NOT NULL COMMENT '问题',
  `answer` text NOT NULL COMMENT '答案',
  `creater` varchar(50) NOT NULL DEFAULT '' COMMENT '创建人',
  `createtime` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `protype` (`protype`),
  KEY `keywords` (`keywords`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='整装售后问答集合'