<?php
//������������ � mysql �������
//��� - localhost
//���� - root
//������ - ����
$mysql_connect=mysql_connect("localhost","root","");
//�������� ���� ������ mysite
$db=mysql_select_db("new");
mysql_query("SET CHARACTER SET 'utf8'");
?>