/* */

update TEACHER l set NAME = (select truename from users as u where u.id = l.ID); -- ��ý�ʦ����
update TEACHER l set UNIVERSITY_NAME = (select university from users as u where u.id = l.ID); -- ��ô�ѧ���
update TEACHER l set UNIVERSITY_ID = (select UNIVERSITY_ID from users as u where u.id = l.ID); -- ��ô�ѧ����


/*
1 ����Ա    admin   VRsygc2016
7 ���ѽ�ʦ  yfchen@bit.edu.cn   cyfer321	1057 lfx@bit.edu.cn / LFX
8 ��ͨ��ʦ yfchen2@bit.edu.cn	123
831 ѧ�� 1076088026@qq.com  yan19980610
*/
