CREATE TABLE `XT_WxMsgList` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID����',
  `opercode` int(11) NOT NULL COMMENT '�����룬2002���ͷ�������Ϣ����2003���ͷ�������Ϣ��',
  `openid` varchar(100) NOT NULL COMMENT 'openid',
  `text` text NOT NULL COMMENT '��Ϣ����',
  `ctime` datetime DEFAULT NULL COMMENT '����ʱ��',
  `worker` varchar(20) NOT NULL COMMENT '�����ͷ��ʺţ���ʽΪ���ʺ�ǰ׺@���ں�΢�ź�',
  `cdate` date NOT NULL COMMENT '��������',
  PRIMARY KEY (`id`),
  KEY `openid` (`openid`),
  KEY `cdate` (`cdate`),
  KEY `worker` (`worker`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='���ںſͷ������¼'