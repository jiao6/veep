/* */

update TEACHER l set NAME = (select truename from users as u where u.id = l.ID); -- 获得教师名字
update TEACHER l set UNIVERSITY_NAME = (select university from users as u where u.id = l.ID); -- 获得大学编号
update TEACHER l set UNIVERSITY_ID = (select UNIVERSITY_ID from users as u where u.id = l.ID); -- 获得大学名字


/*
1 管理员    admin   VRsygc2016
7 付费教师  yfchen@bit.edu.cn   cyfer321	1057 lfx@bit.edu.cn / LFX
8 普通教师 yfchen2@bit.edu.cn	123
831 学生 1076088026@qq.com  yan19980610
*/
