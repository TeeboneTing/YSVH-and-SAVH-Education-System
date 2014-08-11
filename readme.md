�Ш|�V�m�t�αĥ�PHP��MVC framework CodeIgniter���g�A�}�o���Ҩϥ�xampp 1.8.3 �M��A�bwindows 7 64bit �����W�}�o�C

##�Wproduction�һݪ����Ҧp�U:##
1. PHP 5.5

2. MySQL 5.5

3. Apache HTTP server 2.4 (�H�W�T�ӮM��i��xampp���w��)

4. PHPMailer (�w�]�t�b���Y�ɤ�)

5. MySQL ODBC driver (�s���|����Ʈw�d�H�Ƹ�ơA�~���t�Τ���ϥ�)

6. Windows �@�~�t�� (XP or 7)


##�]�w���ҡG##
1. �ק��Ʈw edudbs.sql

	i) �}�� edudbs.sql
	
	ii) �M�� FULLTEXT KEY `PWLASTDATE` (`PWLASTDATE`)
	
	iii) �b��U�@���� ENGINE=InnoDB DEFAULT CHARSET=utf8; �קאּ ENGINE=MyISAM DEFAULT CHARSET=utf8;
	
	iv) �`�@���T�B�ݭn�ק� database engine �A�ק粒����s��
	
	
2. �N edudbs.sql �פJ MySQL ���A�i�H�ϥ� phpmyadmin ���פJ��Ʈw�W�� edudbs.sql �פJ�A�s�X��� UTF8

3. �N �Ш|�V�m�t��.rar �����Y�� xampp ���� htdocs ��Ƨ����A�N�i�}��Ӹ�Ƨ� ysvh_learn �� phpmailer

4. �ק� ysvh_learn ��Ƨ��W�٬� education

5. �}�� education ��Ƨ��A�ק� .htaccess �ɮ�

	i) ��� RewriteRule ^(.*)$ /ysvh_learn/index.php/$1 [L,QSA] �o�@��
	
	ii) �קאּ RewriteRule ^(.*)$ /education/index.php/$1 [L,QSA]

6. �ק� education/application/config/config.php �ɮ�

	i) ��� $config['base_url']	= ''; �o�@��
	
	ii) �קאּ $config['base_url']	= 'http://www.domain.com:port/education';  (IP�Pport��m�H�A��http server���ҭק�)

7. �ק� education/application/config/database.php �ɮ�

	i) ��� $db['default']['username'] = 'root';
		$db['default']['password'] = '';
		$db['default']['database'] = 'EDUDBS';
		$db['default']['dbdriver'] = 'mysql';
	
	ii) �ھڧA�� MySQL �b���K�X�ק� username �P password�A database�]�w�� EDUDBS �Y�i
	
8. �ھڧA�����A���W ODBC �����A�ק� education/application/models/education_model.php �ɮ�

	i) ��� $conn = odbc_connect("Driver={MySQL ODBC 3.51 Driver};Server=$ip;Database=$database;", $user, $password); �o�@��

	ii) �Y�A�� ODBC �����W�٤��s�� MySQL ODBS 3.51 Driver (5.1 Driver) �A�Юھڱ���x�� ODBC �޲z���d������Y�i
	
	
9. �ק� education/application/controllers/control.php �ɮ�

	i) ��� function check_session_expire() 

	ii) �N���Ѩ����� session expire time �]�w���Q����
