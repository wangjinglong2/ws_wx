CREATE TABLE `XT_WxMsg` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID����',
  `groupid` int(11) NOT NULL DEFAULT '0',
  `xtuser` varchar(100) NOT NULL COMMENT '�����û�',
  `message` text NOT NULL COMMENT '��Ϣ����',
  `pdate` datetime DEFAULT NULL COMMENT '��������',
  `flag` int(1) DEFAULT '0' COMMENT '��Ϣ״̬',
  PRIMARY KEY (`id`),
  KEY `xtuser` (`xtuser`),
  KEY `pdate` (`pdate`),
  KEY `groupid` (`groupid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='��Ϣ�б�'