CREATE TABLE `XT_WxSet` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `nonworkdays` date NOT NULL COMMENT '�ǹ���������',
  `comment` varchar(255) NOT NULL DEFAULT '' COMMENT '��ʾ����',
  `state` tinyint(4) NOT NULL DEFAULT '0' COMMENT '״̬',
  `creater` varchar(50) DEFAULT NULL COMMENT '������',
  `createtime` datetime DEFAULT NULL COMMENT '����ʱ��',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='�ǹ������Զ��ظ���������'