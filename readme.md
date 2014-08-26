## 簡介：##

為幫助台北榮總蘇澳暨員山分院教學研究單位邁向無紙化作業，建立一個教育訓練系統網站，使全院員工可以透過這套系統進行
新增/報名/簽到簽退課程、登錄數位學習/院外研習學分、統計上課資料，以及整合兩院原本複雜的人事資料。從此上課點名不需要
再排隊簽到簽退紙本資料，也省去行政人員key in簽到紙本到電腦的時間。上課人員只需帶自己的身份證刷背後的二維條碼，即可
完成點名。課程管理者也可在此系統上公布課程資訊讓全院員工觀看、報名，使資訊更透明、方便瞭解。

教育訓練系統採用PHP的MVC framework CodeIgniter撰寫，開發環境使用xampp 1.8.3 套件，在windows 7 64bit 機器上開發。

##上production所需的環境如下:##
1. PHP 5.5

2. MySQL 5.5

3. Apache HTTP server 2.4 (以上三個套件可於xampp中安裝)

4. PHPMailer (已包含在壓縮檔內)

5. MySQL ODBC driver (連接院內資料庫查人事資料，外部系統不能使用)

6. Windows 作業系統 (XP or 7)


##設定環境：##

1. 將 edudbs.sql 匯入 MySQL 中，可以使用 phpmyadmin 的匯入資料庫上傳 edudbs.sql 匯入，編碼選擇 UTF8

2. 將 教育訓練系統.rar 解壓縮至 xampp 中的 htdocs 資料夾內，將展開兩個資料夾 ysvh_learn 跟 phpmailer

3. 修改 ysvh_learn 資料夾名稱為 education

4. 開啟 education 資料夾，修改 .htaccess 檔案

	i) 找到 RewriteRule ^(.*)$ /ysvh_learn/index.php/$1 [L,QSA] 這一行
	
	ii) 修改為 RewriteRule ^(.*)$ /education/index.php/$1 [L,QSA]

5. 修改 education/application/config/config.php 檔案

	i) 找到 $config['base_url']	= ''; 這一行
	
	ii) 修改為 $config['base_url']	= 'http://www.domain.com:port/education';  (IP與port位置隨你的http server環境修改)

6. 修改 education/application/config/database.php 檔案

	i) 找到 $db['default']['username'] = 'root';
		$db['default']['password'] = '';
		$db['default']['database'] = 'EDUDBS';
		$db['default']['dbdriver'] = 'mysql';
	
	ii) 根據你的 MySQL 帳號密碼修改 username 與 password， database設定為 EDUDBS 即可
	
7. 根據你的伺服器上 ODBC 版本，修改 education/application/models/education_model.php 檔案

	i) 找到 $conn = odbc_connect("Driver={MySQL ODBC 3.51 Driver};Server=$ip;Database=$database;", $user, $password); 這一行

	ii) 若你的 ODBC 版本名稱不叫做 MySQL ODBS 3.51 Driver (5.1 Driver) ，請根據控制台的 ODBC 管理員查找替換即可
	
	
8. 修改 education/application/controllers/control.php 檔案

	i) 找到 function check_session_expire() 

	ii) 將註解取消使 session expire time 設定為十分鐘
	
## 程式位置：##

此系統採用 [CodeIgniter](http://www.codeigniter.org.tw/) 撰寫，故可大致將程式以MVC架構分為三個部分：

**Controller** 放在 education/application/controllers/control.php 檔案，所有網址都由此決定。

**View** 放在 education/application/views 資料夾中，為網頁樣版 (templates)。

**Model** 放在 education/application/models/education_model.php 檔案，處理與資料庫溝通的程式碼。

**設定檔** 放在 education/application/config 資料夾內，決定網址routing(routes.php)、網站基本設定(config.php)、資料庫設定(database.php)等。