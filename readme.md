教育訓練系統採用PHP的MVC framework CodeIgniter撰寫，開發環境使用xampp 1.8.3 套件，在windows 7 64bit 機器上開發。

##上production所需的環境如下:##
1. PHP 5.5

2. MySQL 5.5

3. Apache HTTP server 2.4 (以上三個套件可於xampp中安裝)

4. PHPMailer (已包含在壓縮檔內)

5. MySQL ODBC driver (連接院內資料庫查人事資料，外部系統不能使用)

6. Windows 作業系統 (XP or 7)


##設定環境：##
1. 修改資料庫 edudbs.sql

	i) 開啟 edudbs.sql
	
	ii) 尋找 FULLTEXT KEY `PWLASTDATE` (`PWLASTDATE`)
	
	iii) 在其下一行找到 ENGINE=InnoDB DEFAULT CHARSET=utf8; 修改為 ENGINE=MyISAM DEFAULT CHARSET=utf8;
	
	iv) 總共有三處需要修改 database engine ，修改完成後存檔
	
	
2. 將 edudbs.sql 匯入 MySQL 中，可以使用 phpmyadmin 的匯入資料庫上傳 edudbs.sql 匯入，編碼選擇 UTF8

3. 將 教育訓練系統.rar 解壓縮至 xampp 中的 htdocs 資料夾內，將展開兩個資料夾 ysvh_learn 跟 phpmailer

4. 修改 ysvh_learn 資料夾名稱為 education

5. 開啟 education 資料夾，修改 .htaccess 檔案

	i) 找到 RewriteRule ^(.*)$ /ysvh_learn/index.php/$1 [L,QSA] 這一行
	
	ii) 修改為 RewriteRule ^(.*)$ /education/index.php/$1 [L,QSA]

6. 修改 education/application/config/config.php 檔案

	i) 找到 $config['base_url']	= ''; 這一行
	
	ii) 修改為 $config['base_url']	= 'http://www.domain.com:port/education';  (IP與port位置隨你的http server環境修改)

7. 修改 education/application/config/database.php 檔案

	i) 找到 $db['default']['username'] = 'root';
		$db['default']['password'] = '';
		$db['default']['database'] = 'EDUDBS';
		$db['default']['dbdriver'] = 'mysql';
	
	ii) 根據你的 MySQL 帳號密碼修改 username 與 password， database設定為 EDUDBS 即可
	
8. 根據你的伺服器上 ODBC 版本，修改 education/application/models/education_model.php 檔案

	i) 找到 $conn = odbc_connect("Driver={MySQL ODBC 3.51 Driver};Server=$ip;Database=$database;", $user, $password); 這一行

	ii) 若你的 ODBC 版本名稱不叫做 MySQL ODBS 3.51 Driver (5.1 Driver) ，請根據控制台的 ODBC 管理員查找替換即可
	
	
9. 修改 education/application/controllers/control.php 檔案

	i) 找到 function check_session_expire() 

	ii) 將註解取消使 session expire time 設定為十分鐘
