/* �޸Ŀ��õ�����ֵ���ö�ֵ */
UPDATE LESSON SET STICKY = 0;
UPDATE LESSON SET STICKY = 10 WHERE ID < 111;
UPDATE LESSON SET SORT_ORDER = 1 where SORT_ORDER=0 or SORT_ORDER is NULL;


DROP TABLE IF EXISTS TAGS;
CREATE TABLE IF NOT EXISTS TAGS (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `NAME` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `SORT_ORDER` int(11) default 1000,
  `TYPE` int(11) default 9999, -- ����
  `STATUS` int(11) default 0,
  `CREATE_TIME` datetime default '2017-01-01 11:11:11',
  `ATTACH_AMOUNT` int(11) default 0,
  `SEARCH_AMOUNT` int(11) default 0,
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 AUTO_INCREMENT=10001;

-- truncate table TAGS;
insert into TAGS (ID, TYPE, NAME) VALUES (2010, 2010, 'ɵ��');
insert into TAGS (ID, TYPE, NAME) VALUES (2011, 2010, '��');
insert into TAGS (ID, TYPE, NAME) VALUES (2012, 2010, '����');
insert into TAGS (ID, TYPE, NAME) VALUES (2013, 2010, '����');

insert into TAGS (ID, TYPE, NAME) VALUES (2050, 2050, 'C����');
insert into TAGS (ID, TYPE, NAME) VALUES (2051, 2050, 'C++');
insert into TAGS (ID, TYPE, NAME) VALUES (2052, 2050, 'Java');
insert into TAGS (ID, TYPE, NAME) VALUES (2053, 2050, 'Pascal');

insert into TAGS (ID, TYPE, NAME) VALUES (2100, 2100, '�ʼǱ�');
insert into TAGS (ID, TYPE, NAME) VALUES (2101, 2100, '̨ʽ��');
insert into TAGS (ID, TYPE, NAME) VALUES (2102, 2100, 'С�ͻ�');
insert into TAGS (ID, TYPE, NAME) VALUES (2103, 2100, '����վ');

/* ���п��ý�ʦ���ڴ�ѧ���ֳ�Ϊ��ǩ */
INSERT into TAGS (NAME, type, CREATE_TIME)
SELECT DISTINCT(univer.name), 1030, now() from LESSON l, users u, university univer where l.STATUS=0 and l.TEACHER_ID=u.id and u.university_id=univer.id ORDER BY univer.id;

/* �����пεĽ�ʦ���ֳ�Ϊ��ǩ */
INSERT into TAGS (NAME, type, CREATE_TIME)
SELECT DISTINCT(u.truename), 1020, now() from LESSON l, users u where l.STATUS=0 and l.TEACHER_ID=u.id ORDER BY u.truename;

/* ���п������ֳ�Ϊ��ǩ */
INSERT into TAGS (NAME, type, CREATE_TIME)
SELECT DISTINCT(l.name), 1010, now()  from LESSON l where l.STATUS=0 ORDER BY l.NAME;


DROP TABLE IF EXISTS TAG_OF_DATA;
CREATE TABLE IF NOT EXISTS TAG_OF_DATA (
  `ID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `OBJECT_ID` int(11) default 0,
  `OBJECT_TYPE` int(11) default 0,
  `TAG_ID` int(11) default 0,
  `TAG_NAME` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `TAG_VALUE` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `SORT_ORDER` int(11) default 1000,
  `CREATE_TIME` datetime default '2017-01-01 11:11:11',
  `STATUS` int(11) default 0,
  PRIMARY KEY (`ID`)
) DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=100001;

/* ���������Ӵ�ѧ��ǩ */
INSERT into TAG_OF_DATA (OBJECT_TYPE, CREATE_TIME, OBJECT_ID, TAG_ID, TAG_NAME, STATUS) 
SELECT 300 as OBJECT_TYPE, NOW(), l.ID as OBJECT_ID, t.ID as TAG_ID, t.NAME AS TAG_NAME, 2 from tags t, LESSON l, users u, university univer where t.NAME=univer.name and l.STATUS=0 and l.TEACHER_ID=u.id and u.university_id=univer.id order by l.ID;

/* ���������ӽ�ʦ���ֱ�ǩ */
INSERT into TAG_OF_DATA (OBJECT_TYPE, CREATE_TIME, OBJECT_ID, TAG_ID, TAG_NAME, STATUS) 
SELECT 300 as OBJECT_TYPE, NOW(), l.ID as OBJECT_ID, t.ID as TAG_ID, t.NAME AS TAG_NAME, 2 from tags t, LESSON l, users u where 1=1 and l.STATUS=0 and l.TEACHER_ID=u.id and u.truename=t.NAME ORDER BY l.ID;

/* ���������ӿ������ֱ�ǩ */
INSERT into TAG_OF_DATA (OBJECT_TYPE, CREATE_TIME, OBJECT_ID, TAG_ID, TAG_NAME, STATUS) 
SELECT 300 as OBJECT_TYPE, NOW(), l.ID as OBJECT_ID, t.ID as TAG_ID, t.NAME AS TAG_NAME, 2 from tags t, LESSON l where 1=1 and l.STATUS=0 and l.name=t.NAME ORDER BY l.ID;

/* ��ǩ�ķ��ʴ��� */
UPDATE TAGS t set t.ATTACH_AMOUNT = (select count(TAG_ID) from TAG_OF_DATA td where td.TAG_ID=t.ID);

select * from TAGS;
select * from TAG_OF_DATA;

-- truncate table TAG_OF_DATA;
