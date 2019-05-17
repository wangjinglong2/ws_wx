CREATE TABLE `XT_WxSet` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `nonworkdays` date NOT NULL COMMENT '非工作日日期',
  `comment` varchar(255) NOT NULL DEFAULT '' COMMENT '提示内容',
  `state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '状态',
  `creater` varchar(50) DEFAULT NULL COMMENT '创建人',
  `createtime` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='非工作日自动回复提醒设置'