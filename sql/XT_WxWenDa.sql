CREATE TABLE `XT_WxWenDa` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID����',
  `type` int(1) NOT NULL DEFAULT '0' COMMENT '����',
  `protype` varchar(20) NOT NULL COMMENT '��Ʒ����',
  `keywords` varchar(50) NOT NULL COMMENT '�ؼ���',
  `question` varchar(200) NOT NULL COMMENT '����',
  `answer` text NOT NULL COMMENT '��',
  `creater` varchar(50) NOT NULL DEFAULT '' COMMENT '������',
  `createtime` datetime DEFAULT NULL COMMENT '����ʱ��',
  PRIMARY KEY (`id`),
  KEY `protype` (`protype`),
  KEY `keywords` (`keywords`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='��װ�ۺ��ʴ𼯺�'