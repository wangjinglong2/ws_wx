CREATE TABLE `XT_WxUser` (
  `openid` varchar(100) NOT NULL DEFAULT '',
  `nickname` varchar(30) DEFAULT NULL,
  `sex` int(1) DEFAULT NULL,
  `city` varchar(10) CHARACTER SET ucs2 DEFAULT NULL,
  `headimgurl` varchar(100) CHARACTER SET ucs2 DEFAULT NULL,
  `subscribe_time` int(11) DEFAULT NULL,
  `comment` varchar(100) DEFAULT NULL,
  `flag` tinyint(4) NOT NULL DEFAULT '0',
  `idtype` tinyint(4) NOT NULL DEFAULT '1' COMMENT '���ں����1��������2��������',
  PRIMARY KEY (`openid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8