<?php
class Control extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model("education_model");
		$this->load->helper('url');
		session_name("YSVHLEARNTEACH");
		date_default_timezone_set('Asia/Taipei');
	}
	
	// If there is no activities in the past $expire seconds, logout the user.
	// 先註解掉，為了開發方便，上production時記得拿掉註解。
	function check_session_expire(){
		
		$expire = 1800;
		if(!isset($_SESSION['last_activity']) || time() - $_SESSION['last_activity'] > $expire)
			{ redirect("logout","GET"); }
		else // update last_activity timestamp
			{ $_SESSION['last_activity'] = time(); }
		
	}// End of function check_session_expire()
	
	public function home(){
		// Check session, if logged in, load to mainpage.
		// If not, load login page.
		session_start();
		if($_SERVER['REQUEST_METHOD'] === 'GET'){
			$login = isset( $_SESSION['login'] ) ? $_SESSION['login'] : false;
			if($login) // If session exist, redirect to mainpage.
				{ redirect("/mainpage","refresh"); }
			else{
				$_SESSION['last_activity'] = time();
				$data["title"] = "登入教育系統";
				if (!empty($_GET['login']) && $_GET['login'] === "fail")
					{ $data['msg'] = "帳號或密碼錯誤，請重新輸入。"; }
				$this->load->view("template/header",$data);
				$this->load->view("login",$data);
				$this->load->view("template/footer");
			}		
		} // End of Request Method: GET
		elseif($_SERVER['REQUEST_METHOD'] === 'POST'){  
			// Login information checking
			$usr = strtoupper($_POST['user']);
			$pwd = $_POST['password'];
			$ret = $this->education_model->check_employ($usr,$pwd);

			if ($ret->num_rows > 0){
				$_SESSION['login'] = true;
				$_SESSION['username'] = $ret->row()->EPNAME;
				$_SESSION['id'] = $usr;
				redirect("/mainpage","refresh");
			}else // redirect to login page and show "wrong user or password" message
				{ redirect("/?login=fail","refresh"); }		
		} // End of Request Method: POST
	}// End of function home()
	
	public function mainpage(){
		// Show maincontrol page (functions depends on user's permission)
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){ 
			
			//檢查有沒有問卷沒有填，有的話就redirect到問卷頁面
			$today = idate("Y",time())-1911;
			$today = strval($today)."/".date("m/d",time());
			
			$questionnaire = $this->db->get_where('signup',
					array(
							'IDCARDNO'=>$_SESSION['id'],
							'questionnaire'=>0,
							'DATETO <'=>$today
						)
					)->row_array();
			
			if(!empty($questionnaire)) { 
				
				$course_endday = $questionnaire['DATETO'];
				echo $course_endday;
				
				$date = explode("/", $course_endday); //西元年轉換
				$course_endday = strtotime(strval((int)$date[0]+1911)."-".$date[1]."-".$date[2]);
				
				//判斷課程結束隔天才填問卷
				if(time() - $course_endday > 86400 )
					{redirect("questionnaire?c_id=".$questionnaire['course_id'],"refresh");}	
					
			}// End of if(!empty($questionnaire))
			
			$data["title"] = "台北榮總員山蘇澳分院 教育訓練系統";
			$data['mainpage'] = true;
			$this->load->view("template/header2",$data);
			$this->load->view("mainpage",$data);
			$this->load->view("template/footer2");
			
		}
		else
			{ redirect("/","refresh");}
	}// End of function mainpage()
	
	public function personnel(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login'] &&
			$this->education_model->is_admin($_SESSION['id']) === 5){ 
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data['title'] = "人事系統";
			
				//總資料列數
				$data['total_rows'] = $this->education_model->join_count_all();
				//用query string判斷目前是第幾頁
				if(isset($_GET['page']) && !empty($_GET['page'])){$page = (int)$_GET['page'];}
				else{$page = 1;}
				//每頁筆數固定10筆
				$para['perpage'] = 10;
				//第幾筆開始撈
				$para['offset'] = $para['perpage'] * ($page-1);
				//撈資料
				$data['query'] = $this->education_model->join($para);
				
				//載入CI分頁模組，製作分頁連結
				$config['per_page'] = $para['perpage'];
				$config['base_url'] = base_url().'/personnel/?';
				$config['total_rows'] =$data['total_rows'];
				
				$this->set_pagination($config);
				
				$this->load->view("template/header2",$data);
				$this->load->view("personnel/personnel",$data);
				$this->load->view("template/footer2");

			}// End of Request Method: GET
			elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
				foreach($_POST as $key => $val){
					if($val != ''){
						//根據 $key去撈資料，把資料array加上BRANCHNO = $val後塞進FTCTL_EMPLOY
						//再從兩個table中delete掉共同帳號，並redirect回此頁，以GET method的方式。
						$ret = $this->education_model->get_ysvh_employ_by_ID($key);
						$ret['BRANCHNO'] = $val;
						$this->education_model->insert_employ($ret); //插入table
						
						$tables = array('YSVH_FTCTL_EMPLOY', 'SAVH_FTCTL_EMPLOY');
						$this->db->where('EPID', $key);
						$this->db->delete($tables); //刪除資料	
					}// End of if
				}// End of foreach
				$uri = "personnel/?".$_SERVER['QUERY_STRING'];
				redirect($uri,"GET");
			}// End of Request Method: POST 
		}// End of if(_SESSION['login'])
		else 
			{ redirect("/","refresh"); }
	}// End of function personnel()
	
	public function import_new_employ(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login'] &&
			$this->education_model->is_admin($_SESSION['id']) === 5){ 
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data["title"] = "更新新進人員";
				$this->load->view("template/header2",$data);
				$this->load->view("personnel/import",$data);
				$this->load->view("template/footer2");
			}// End of Request Method: GET
			
			elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){	
				// 匯入新進員工			
				$inserted = Array();
				
				foreach ($_POST as $key => $val){
					if(count(explode("-", $key)) === 1 && $val != "2"){
						if($val === "0"){
							$query = $this->education_model->get_remote_ysvh_employ_by_ID($key);
							$query["BRANCHNO"] = $val;
							if($this->education_model->insert_employ($query))
								{array_push($inserted,$query['EPNAME']);}
						}// End of if ($val === "0")
						else{
							$query = $this->education_model->get_remote_savh_employ_by_ID($key);
							$query["BRANCHNO"] = $val;
							/* DEBUG PRINT
							foreach ($query as $k => $v){
								echo "query $k : $v ";
							}
							*/
							if($this->education_model->insert_employ($query))
								{array_push($inserted,$query['EPNAME']);}
						}// End of else
					}// End of if (count(explode("-", $key)) === 1 && $val != "2")
					
					else{ //處理多重帳號問題
						if($val === "2"){
							echo "多重帳號：key: $key value: $val <br>";
							// 檢查 $key-branch的值，若為0 or 1則匯入
							if($_POST[$key."-branch"] === "0"){
								$query = $this->education_model->get_remote_ysvh_employ_by_ID($key);
								$query["BRANCHNO"] = "0";
								if($this->education_model->insert_employ($query))
									{array_push($inserted,$query['EPNAME']);}
							}
							elseif ($_POST[$key."-branch"] === "1"){
								$query = $this->education_model->get_remote_savh_employ_by_ID($key);
								$query["BRANCHNO"] = "1";
								if($this->education_model->insert_employ($query))
									{array_push($inserted,$query['EPNAME']);}
							}// End of elseif
						}// End of if ($val === "2")
					}// End of else 處理多重帳號問題
				
				}// End of foreach $_POST
				
				$data["title"] = "更新新進人員";
				$data["inserted"] = $inserted;
				$this->load->view("template/header2",$data);
				$this->load->view("personnel/import",$data);
				$this->load->view("template/footer2");
			
			}// End of Request Method: POST
		}// End of if(_SESSION['login'])
		else
			{ redirect("/","refresh"); }
	}// End of function import_new_employ()
	
	// Return new employ list in json format
	public function get_new_employ(){	
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			$query = $this->education_model->get_new_employ($_POST['from'],$_POST['to']);			
			// Use urlencode to workaround for json_encode without JSON_UNESCAPED_UNICODE
			// (解決 json_encode() 中文問題)
			array_walk_recursive($query, function(&$value, $key){
				if(is_string($value)) // big5 字串轉 utf8
					{ $value = urlencode(mb_convert_encoding($value,"utf8","big5")); }
			});
			$json = urldecode(json_encode($query));
			echo $json;
		}// End of Request Method: POST
	}// End of function get_new_employ()
	

	public function get_DPNAME($branch,$DPNO){
		$pack['BRANCHNO'] = $branch; $pack['DPNO'] = $DPNO;
		echo $this->education_model->get_dpname($pack);	
	}// End of function get_DPNAME()
	
	// 根據院區輸出全部部門資料
	public function get_all_DPNAME($branch){
		$query = $this->education_model->get_all_dpname($branch);
		array_walk_recursive($query, function(&$value, $key){
			if(is_string($value)) // big5 字串轉 utf8
			{ $value = urlencode($value); }
		});
		$json = urldecode(json_encode($query));
		echo $json;
	}// End of function get_all_DPNAME()
	
	// Return single employ data in json format
	public function get_remote_employ_by_ID($branch,$ID){
		if($branch === "0")
			{$query = $this->education_model->get_remote_ysvh_employ_by_ID($ID);}
		elseif ($branch === "1")
			{$query = $this->education_model->get_remote_savh_employ_by_ID($ID);}
		else
			{$query="";}
		// Use urlencode to workaround for json_encode without JSON_UNESCAPED_UNICODE
		// (解決 json_encode() 中文問題)
		array_walk_recursive($query, function(&$value, $key){
			if(is_string($value)) // big5 字串轉 utf8
				{ $value = urlencode(mb_convert_encoding($value,"utf8","big5")); }
		});
		$json = urldecode(json_encode($query));
		echo $json;
	}// End of function get_remote_employ_by_ID
	
	public function update_employ(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login'] &&
			$this->education_model->is_admin($_SESSION['id']) === 5){ 
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data["title"] = "人事異動(調院區)";
				$this->load->view("template/header2",$data);
				$this->load->view("personnel/update",$data);
				$this->load->view("template/footer2");
			}
			elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
				if(isset($_POST['search-text']))
					{$ret = $this->education_model->search_by_ID_or_name(strtoupper($_POST['search-text']));}
				else{$ret = "";}
				$data['search_result'] = $ret;
				$data["title"] = "人事異動(調院區)";
				$this->load->view("template/header2",$data);
				$this->load->view("personnel/update",$data);
				$this->load->view("template/footer2");
			}
		}
		else
		{ redirect("/","refresh"); }
	}// End of function import_new_employ()
	
	// 用POST method來更新資料庫人事異動
	public function update_employ_DB(){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			$item['IDCARDNO'] = $_POST['IDCARDNO'];
			$item['BRANCHNO'] = $_POST['BRANCHNO'];
			$item['DPNO'] = $_POST['DPNO'];
			$item['ADMIN'] = $_POST['ADMIN'];
			$ret = $this->education_model->update_employ($item);
			echo $ret;
		}// End of Request Method: POST
	}// End of function update_employ_DB()
	
	public function download_csv(){
		header("Content-type:application");
		header("Content-Disposition:attachment;filename=employ_list.csv");
		$para['perpage'] = $this->education_model->join_count_all();
		$para['offset'] = 0;
		$query = $this->education_model->join($para);
		foreach ($query as $e){
			$para1['DPNO'] = $this->education_model->get_ysvh_employ_by_ID($e['EPID'])['DPNO']; $para1['BRANCHNO'] = '0';
			$YSVH_DPNAME = $this->education_model->get_dpname($para1);
			$para2['DPNO'] = $this->education_model->get_savh_employ_by_ID($e['EPID'])['DPNO']; $para2['BRANCHNO'] = '1';
			$SAVH_DPNAME = $this->education_model->get_dpname($para2);
			echo $e["EPNO"].",". 
				mb_convert_encoding($e['EPNAME'],"big5","utf8").",".$e['IDCARDNO'].",".
				mb_convert_encoding($YSVH_DPNAME,"big5","utf8").",".
				mb_convert_encoding($SAVH_DPNAME,"big5","utf8").",".
				$e['INDAY']."\n";
		}
		/*
		// get YSVH employee
		$query = $this->db->get('ysvh_ftctl_employ');
		$query = $query->result_array();
		foreach ($query as $e){
			$para1['DPNO'] = $this->education_model->get_ysvh_employ_by_ID($e['EPID'])['DPNO']; $para1['BRANCHNO'] = '0';
			$YSVH_DPNAME = $this->education_model->get_dpname($para1);
			
			echo $e["EPNO"].",".
				mb_convert_encoding($e['EPNAME'],"big5","utf8").",".$e['IDCARDNO'].",".
				mb_convert_encoding($YSVH_DPNAME,"big5","utf8").",".
				"".",".
					$e['INDAY']."\n";
		}
		
		// get SAVH employee
		$query = $this->db->get('savh_ftctl_employ');
		$query = $query->result_array();
		foreach ($query as $e){
			$para2['DPNO'] = $this->education_model->get_savh_employ_by_ID($e['EPID'])['DPNO']; $para2['BRANCHNO'] = '1';
			$SAVH_DPNAME = $this->education_model->get_dpname($para2);
				
			echo $e["EPNO"].",". 
				mb_convert_encoding($e['EPNAME'],"big5","utf8").",".$e['IDCARDNO'].",".
				"".",".
				mb_convert_encoding($SAVH_DPNAME,"big5","utf8").",".
				$e['INDAY']."\n";
		}
		
		*/
		
	}// End of function download_csv()
	
	// 檢查講師格式，必須包含姓名 身分證字號 現職工作 三個欄位
	public function speaker_rule($str){
		$item = explode(" ", $str);
		if (count($item) < 3)
			{return FALSE;}
		else{
			$ret = $this->education_model->check_speaker($item[1]);
			if($ret > 0) return TRUE;
			else		 return FALSE;
		}
	}
	
	// 若為群組課程，則必須填入節數
	public function group_order_rule($str,$topic_type){
		if($topic_type === "1"){
			if(!empty($str)){ return TRUE; }
			else			{ return FALSE;}
		}
		else { return TRUE; }
	}
	
	// 檢查時間格式
	public function time_rule($str){
		$item = explode(":", $str);
		if(count($item) != 2)
			{return FALSE;}
		else{
			if($item[0] && $item[1]){
				$HH = (int)$item[0];
				$MM = (int)$item[1];
				if($HH < 0 || $HH >24) 			{ return FALSE; }
				elseif ($MM < 0 || $MM > 60) 	{ return FALSE; }
				else 							{ return TRUE; }
			}
			else { return FALSE; }
		}
	}
	
	// 如果課程類型為群組，則必須要有group topic
	public function topictype_rule($str){
		if($str === "1"){
			if($_POST['group_topic_show'])  { return TRUE; }
			else							{ return FALSE; }
		}else { return TRUE; }
	}
	
	public function new_course(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login'] &&
			$this->education_model->is_admin($_SESSION['id'])){
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data["title"] = "新增課程";
				$this->load->view("template/header2",$data);
				$this->load->view("course/new_course",$data);
				$this->load->view("template/footer2");
			}// End of Request Method: GET
			elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
				
				// Set form validation rules
				$this->form_validation->set_rules('topic_type','課程形式', 'required|callback_topictype_rule');
				$this->form_validation->set_rules('topic', '課程主題', 'required');
				$this->form_validation->set_rules('speaker', '講師', 'required|callback_speaker_rule');
				$this->form_validation->set_rules('place', '地點', 'required');
				$this->form_validation->set_rules('from', '開始日期', 'required');
				$this->form_validation->set_rules('to', '結束日期', 'required');
				$this->form_validation->set_rules('timefrom', '開始時間', 'required|callback_time_rule');
				$this->form_validation->set_rules('timeto', '結束時間', 'required|callback_time_rule');
				$this->form_validation->set_rules('credit', '時數', 'required|numeric');
				$this->form_validation->set_rules('limit', '人數上限', 'is_natural_no_zero');
				$this->form_validation->set_rules('course_type','類別','required');
				$this->form_validation->set_rules('class_type','課程類型','required');
				$this->form_validation->set_rules('group_order','節數','is_natural_no_zero|callback_group_order_rule['.$_POST['topic_type'].']');
				
				// Set validation error messages
				$this->form_validation->set_message('required', '<font color="red">需要填入 %s</font>');
				$this->form_validation->set_message('numeric', '<font color="red">%s 欄位必須為數字</font>');
				$this->form_validation->set_message('is_natural_no_zero', '<font color="red">%s 欄位必須為大於0的整數</font>');
				$this->form_validation->set_message('speaker_rule', '<font color="red">講師輸入格式錯誤</font>');
				$this->form_validation->set_message('group_order_rule', '<font color="red">群組課程請填入節數</font>');
				$this->form_validation->set_message('time_rule', '<font color="red">%s 格式錯誤，請輸入 小時:分鐘 (例：15:20)</font>');
				$this->form_validation->set_message('topictype_rule', '<font color="red">需要填入 群組主題</font>');
				
				if ($this->form_validation->run() === FALSE){
					$data["title"] = "新增課程";
					$data["course_type"] = $this->education_model->get_all_course_type();
					if(isset($_POST['group_topic_show']))
						{$data["group_topic"] = $_POST['group_topic_show'];}
					$this->load->view("template/header2",$data);
					$this->load->view('course/new_course',$data);
					$this->load->view("template/footer2");
				}// End of false validation
				
				else{ // 成功送出表單
					// 將表單存入資料庫
					$course_data = array(
						'TOPIC' => $_POST['topic'],
						'TOPIC_TYPE' => $_POST['topic_type'],
						'GROUP_ORDER' => $_POST['group_order'],
						'PLACE' => $_POST['place'],
						'DATEFROM' => $_POST['from'],
						'DATETO' => $_POST['to'],
						'TIMEFROM' => $_POST['timefrom'], 
						'TIMETO' => $_POST['timeto'],
						'CREDIT' => $_POST['credit'],
						'CLASS_TYPE' => $_POST['class_type'],
						'COURSE_TYPE' => $_POST['course_type'],
						'UPLIMIT' => $_POST['limit'],
						'INTRODUCTION' => $_POST['introduction'],
						'NOTE' => $_POST['note'],
						'MANAGERID' => $_SESSION['id']
					);
					
					// 插入群組主題
					$group_topic_date = array(
						'topic' => $_POST['group_topic_show'],
						'datefrom' => $_POST['from'],
						'dateto' => $_POST['to'],
						'credit' => $_POST['credit']
					);
					$g_id = $this->education_model->check_insert_group_topic($group_topic_date);
					if($g_id)
						{ $course_data['GROUP_TOPIC_ID'] = $g_id; }
						
					// 插入講師id
					// 先檢查speaker欄位，要含有名字 身分證字號 現職工作 三個欄位
					// 用身分證字號去check speaker
					$s_id = $this->education_model->check_speaker(explode(" ", $_POST['speaker'])[1]);
					$course_data['SPEAKER_ID'] = $s_id;
					
					// 新增課程到資料庫
					$this->education_model->insert_new_course($course_data);
					
					// 顯示新增成功頁面
					$data["title"] = "課程新增完畢";
					$data["type"] = 0; // 表示為新增成功頁面
					$this->load->view("template/header2",$data);
					$this->load->view('course/course_success');
					$this->load->view("template/footer2");
				}// End of success validation
				
			}// End  of Request Method: POST
		}// End of if(_SESSION['login'])
		else
			{ redirect("/","refresh"); }
	}// End of function new_course()
	
	//報名系統
	public function signup(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			if($_SERVER['REQUEST_METHOD'] === 'GET'){				
				//先檢查帳號資料是否有Email資料，若無則跳入輸入email的網頁
				if($this->education_model->check_email($_SESSION['id'])){
					$data["title"] = "報名系統";
					$this->load->view("template/header2",$data);
					$this->load->view('signup/signup',$data);
					$this->load->view("template/footer2");
				}
				else 
					{redirect("put_email","refresh");}

			}// End of Request Method: GET
		}// End of if(_SESSION['login'])
		else
			{ redirect("/","refresh"); }
	}// End of function signup()
	
	// 選擇要報名的課程，以及填入自假/公假來報名 www.domain.com/url?g_id=0&c_id=3  (g_id=0:個別課程)
	public function select_course(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data["title"] = "報名系統";
				$this->load->view("template/header2",$data);
				$this->load->view('signup/select_course');
				$this->load->view("template/footer2");
			}// End of Request Method: GET
			elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
				// 報名課程，存入資料庫
				foreach ($_POST as $k => $v){
					$item = explode("_", $k);					
					if($item[0] === "id"){
						$check = $this->db->get_where('signup',array('IDCARDNO'=>$_SESSION['id'],'course_id'=>$item[1]));
						$course_data = $this->db->get_where('course',array('id'=>$item[1]))->row_array();
						if(!$check->num_rows()){
							// 檢查確定沒有報名資訊以後，存入資料庫
							$signup = array(
								'IDCARDNO' => $_SESSION['id'],
								'course_id' => $item[1],
								'leave_type' => (int)$_POST['leavetype_'.$item[1]],
								'registered' => 1,
								'questionnaire' => 0,
								'DATEFROM' => $course_data['DATEFROM'],
								'DATETO' => $course_data['DATETO']
							);
							$this->db->insert('signup',$signup);
						}// End of if $check
					}//  End of if $item
				}// End of foreach
				
				// 顯示報名成功頁面
				$data["title"] = "報名成功";
				$this->load->view("template/header2",$data);
				$this->load->view('signup/select_course_success');
				$this->load->view("template/footer2");
				
			}// End of Request Method: POST
		}// End of if($_SESSION['login'])
		else
			{ redirect("/","refresh"); }
	}// End of function select_course()
	
	// 顯示我已報名的課程
	public function my_course(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			$data["title"] = "已報名的課程";
			$this->load->view("template/header2",$data);
			$this->load->view('signup/my_course');
			$this->load->view("template/footer2");
		}// End of if(_SESSION['login'])
		else
			{ redirect("/","refresh"); }	
	}
	
	//顯示已參加過的課程
	public function my_old_course(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			
			$today = idate('Y') - 1911;
			$today = strval($today)."/".date("m/d");
			
			if($_SERVER['REQUEST_METHOD'] === "GET"){
			
			//總資料列數
			$data['total_rows'] = $this->db->get_where('signup',
					array('IDCARDNO'=>$_SESSION['id'],'DATETO <'=>$today)
					)->num_rows();
			//用query string判斷目前是第幾頁
			if(isset($_GET['page']) && !empty($_GET['page'])){$page = (int)$_GET['page'];}
			else{$page = 1;}
			//每頁筆數固定10筆
			$para['perpage'] = 10;
			//第幾筆開始撈
			$para['offset'] = $para['perpage'] * ($page-1);
			//撈資料
			$this->db->limit($para['perpage'],$para['offset']);
			$this->db->order_by('id','desc');
			
			$query = $this->db->get_where('signup',array(
					'IDCARDNO'=>$_SESSION['id'],
					'DATETO <'=>$today
			));
			$data['my_course'] = $query->result_array();
		
			$config['per_page'] = $para['perpage'];
			$config['base_url'] = base_url().'/my_old_course/?';
			$config['total_rows'] =$data['total_rows'];
			
			// 設定分頁參數
			$this->set_pagination($config);
			
			}// End of Request Method: GET
			elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
				if(!$_POST['filter_search']){
					switch ($_POST['search_type']) {
							
						case "keyword":
							
							// 搜尋講師
							$this->db->like('NAME', $_POST['query']);
							$this->db->or_like('IDCARDNO',$_POST['query']);
							$speaker = $this->db->get_where('speaker')->result_array();
							$ret = array();
			
							foreach ($speaker as $e){
								$this->db->order_by("DATEFROM", "desc");
								$q = $this->db->get_where('course',
										array('DATETO <'=> $today,'SPEAKER_ID'=>$e['id']));
								$ret = array_merge($ret,$q->result_array());
							}
			
							// 搜尋個別課程中含有主題 簡介 備註關鍵字
							$this->db->like('TOPIC', $_POST['query']);
							$this->db->or_like('INTRODUCTION',$_POST['query']);
							$this->db->or_like('NOTE',$_POST['query']);
							$this->db->or_like('PLACE',$_POST['query']);
							$this->db->order_by("DATEFROM", "desc");
							$query = $this->db->get_where('course',array('DATETO <'=> $today));
							$ret = array_merge($ret,$query->result_array());
			
							// 搜尋群組主題中含有主題關鍵字，找到對應的課程in course table
							$this->db->like('group_topic', $_POST['query']);
							$this->db->order_by("DATEFROM", "desc");
							$query = $this->db->get_where('group_course',array('DATETO <'=> $today));
							foreach ($query->result_array() as $e){
								$q = $this->db->get_where('course',array('GROUP_TOPIC_ID'=>$e['id']));
								$ret = array_merge($ret,$q->result_array());
							}
							break;	
								
						case "topic":
							
							// 搜尋個別課程中含有主題關鍵字
							$this->db->like('TOPIC', $_POST['query']);
							$this->db->order_by("DATEFROM", "desc");
							$query = $this->db->get_where('course',array('DATETO <'=> $today));
							$ret = $query->result_array();
			
							// 搜尋群組主題中含有主題關鍵字，找到對應的課程in course table
							$this->db->like('group_topic', $_POST['query']);
							$this->db->order_by("DATEFROM", "desc");
							$query = $this->db->get_where('group_course',array('DATETO <'=> $today));
							foreach ($query->result_array() as $e){
								$q = $this->db->get_where('course',array('GROUP_TOPIC_ID'=>$e['id']));
								$ret = array_merge($ret,$q->result_array());
							}	
							break;
								
						case "speaker":
							
							$this->db->like('NAME', $_POST['query']);
							$this->db->or_like('IDCARDNO',$_POST['query']);
							$speaker = $this->db->get_where('speaker')->result_array();
							
							$ret = array();
							foreach ($speaker as $e){
								$this->db->order_by("DATEFROM", "desc");
								$q = $this->db->get_where('course',
										array('DATETO <'=> $today,'SPEAKER_ID'=>$e['id']));
								$ret = array_merge($ret,$q->result_array());
							}
							break;
							
					}// End of switch ($_POST['search_type'])
					
				}// End of if(!$_POST['filter_search'])
				else{ // 過濾搜尋
					
					$where = array('DATETO <' => $today);
					if($_POST['course_type']){ $where['COURSE_TYPE'] = $_POST['course_type']; }
					if($_POST['datefrom']){ $where['DATEFROM >='] = $_POST['datefrom']; }
					if($_POST['dateto']){ $where['DATETO <='] = $_POST['dateto']; }
					
					$this->db->order_by("DATEFROM", "desc");
					$ret = $this->db->get_where('course',$where)->result_array();

					if($_POST['leave_type']){ $where2['leave_type'] = $_POST['leave_type']; }
					
				}// End of else(!$_POST['filter_search'])

				// 比對已參加過課程的資料，有在signup table裡面的才列出來
				$where2['IDCARDNO'] = $_SESSION['id'];  $where2['DATETO <'] = $today;
				$query = $this->db->get_where('signup',$where2)->result_array();

				$ret2 = array();
				foreach ($query as $e){
					$found = false;
					foreach ($ret as $i) { if ($e['course_id'] === $i['id']) {$found = true;} }
					if($found){ array_push($ret2, $e); }
				}
				
				$data['my_course'] = $ret2;
			
			}// End of Request Method: POST
			
			$data["title"] = "我已參加過的課程";
			$this->load->view("template/header2",$data);
			$this->load->view('signup/my_old_course');
			$this->load->view("template/footer2");
		}// End of if(_SESSION['login'])
		else
		{ redirect("/","refresh"); }
	}
	
	// 顯示舊課程
	public function old_course(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			
			$today = idate("Y",time())-1911;
			$today = strval($today)."/".date("m/d",time());
			
			
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
			
				//總資料列數
				$data['total_rows'] = $this->db->get_where('course',array('DATETO <'=> $today))->num_rows();
				//用query string判斷目前是第幾頁
				if(isset($_GET['page']) && !empty($_GET['page'])){$page = (int)$_GET['page'];}
				else{$page = 1;}
				//每頁筆數固定10筆
				$para['perpage'] = 10;
				//第幾筆開始撈
				$para['offset'] = $para['perpage'] * ($page-1);
				//撈資料
				$this->db->order_by("DATEFROM", "desc");
				$this->db->limit($para['perpage'],$para['offset']);
				$query = $this->db->get_where('course',array('DATETO <'=> $today));
				$data['old_course_data'] = $query->result_array();
			
				$config['per_page'] = $para['perpage'];
				$config['base_url'] = base_url().'/old_course/?';
				$config['total_rows'] =$data['total_rows'];

				$this->set_pagination($config);
				
			}// End of Request Method: GET
			elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
				if(!$_POST['filter_search']){
					switch ($_POST['search_type']) {
					
					case "keyword":
						// 搜尋講師
						$this->db->like('NAME', $_POST['query']);
						$this->db->or_like('IDCARDNO',$_POST['query']);
						$speaker = $this->db->get_where('speaker')->result_array();
						$ret = array();
						
						foreach ($speaker as $e){
							$this->db->order_by("DATEFROM", "desc");
							$q = $this->db->get_where('course',
									array('DATETO <'=> $today,'SPEAKER_ID'=>$e['id']));
							$ret = array_merge($ret,$q->result_array());
						}
						
						// 搜尋個別課程中含有主題 簡介 備註 地點關鍵字
						$this->db->like('TOPIC', $_POST['query']);
						$this->db->or_like('INTRODUCTION',$_POST['query']);
						$this->db->or_like('NOTE',$_POST['query']);
						$this->db->or_like('PLACE',$_POST['query']);
						$this->db->order_by("DATEFROM", "desc");
						$query = $this->db->get_where('course',array('DATETO <'=> $today));
						$ret = array_merge($ret,$query->result_array());
						
						// 搜尋群組主題中含有主題關鍵字，找到對應的課程in course table
						$this->db->like('group_topic', $_POST['query']);
						$this->db->order_by("DATEFROM", "desc");
						$query = $this->db->get_where('group_course',array('DATETO <'=> $today));
						foreach ($query->result_array() as $e){
							$q = $this->db->get_where('course',array('GROUP_TOPIC_ID'=>$e['id']));
							$ret = array_merge($ret,$q->result_array());
						}
						
						$data['old_course_data'] = $ret;
					break;
					
					
					case "topic":
						// 搜尋個別課程中含有主題關鍵字
						$this->db->like('TOPIC', $_POST['query']);
						$this->db->order_by("DATEFROM", "desc");
						$query = $this->db->get_where('course',array('DATETO <'=> $today));
						$ret = $query->result_array();
						
						// 搜尋群組主題中含有主題關鍵字，找到對應的課程in course table
						$this->db->like('group_topic', $_POST['query']);
						$this->db->order_by("DATEFROM", "desc");
						$query = $this->db->get_where('group_course',array('DATETO <'=> $today));
						foreach ($query->result_array() as $e){
							$q = $this->db->get_where('course',array('GROUP_TOPIC_ID'=>$e['id']));
							$ret = array_merge($ret,$q->result_array());
						}
						
						$data['old_course_data'] = $ret;
					break;
					
					case "speaker":
						$this->db->like('NAME', $_POST['query']);
						$this->db->or_like('IDCARDNO',$_POST['query']);
						$speaker = $this->db->get_where('speaker')->result_array();
						$ret = array();
						
						foreach ($speaker as $e){
							$this->db->order_by("DATEFROM", "desc");
							$q = $this->db->get_where('course',
									array('DATETO <'=> $today,'SPEAKER_ID'=>$e['id']));
							$ret = array_merge($ret,$q->result_array());
						}
						
						$data['old_course_data'] = $ret;
					break;
					}// End of switch ($_POST['search_type'])
				}// End of if(!$_POST['filter_search'])
				else{ // 過濾搜尋
					
					$where = array('DATETO <' => $today);
					if($_POST['course_type']){ $where['COURSE_TYPE'] = $_POST['course_type']; }
					if($_POST['datefrom']){ $where['DATEFROM >='] = $_POST['datefrom']; }
					if($_POST['dateto']){ $where['DATETO <='] = $_POST['dateto']; }
					
					$this->db->order_by("DATEFROM", "desc");
					$ret = $this->db->get_where('course',$where);
					
					$data['old_course_data'] = $ret->result_array();
					
				}
			}// End of Request Method: POST
			
			$data["title"] = "舊課程列表";
			$this->load->view("template/header2",$data);
			$this->load->view('signup/old_course',$data);
			$this->load->view("template/footer2");
		}// End of if(_SESSION['login'])
		else
		{ redirect("/","refresh"); }
	}
	
	// 顯示課程詳細資訊 www.domain.com/url?c_id=2
	public function course_detail(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data["title"] = "課程詳細資訊";
				$this->load->view("template/header2",$data);
				$this->load->view('signup/course_detail');
				$this->load->view("template/footer2");
			}// End of Request Method: GET
		}// End of if(_SESSION['login'])
		else
			{ redirect("/","refresh"); }
	}
	
	// 顯示講師詳細資訊  www.domain.com/url?s_id=1
	public function speaker_detail(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data["title"] = "講師詳細資訊";
				$this->load->view("template/header2",$data);
				$this->load->view('signup/speaker_detail');
				$this->load->view("template/footer2");
			}// End of Request Method: GET
		}// End of if(_SESSION['login'])
		else
			{ redirect("/","refresh"); }
	}
	
	// 取消報名
	public function cancel(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			if(isset($_GET['c_id']) && isset($_GET['id'])){
				$data["title"] = "取消報名";
				$data['course_data'] = $this->db->get_where('course',array('id'=>$_GET['c_id']))->row_array();
				$data['signup_data'] = $this->db->get_where('signup',array('IDCARDNO'=>$_GET['id'],'course_id'=>$_GET['c_id']))->row_array();
				
				// delete signup data here...
				$this->db->delete('signup', array('IDCARDNO'=>$_GET['id'],'course_id'=>$_GET['c_id']));
				
				$this->load->view("template/header2",$data);
				$this->load->view('signup/cancel',$data);
				$this->load->view("template/footer2");
			}// End of if isset()
		}// End of if(_SESSION['login'])
		else
		{ redirect("/","refresh"); }
	}
	
	// 簽到簽退系統
	public function signin(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login'] &&
			$this->education_model->is_admin($_SESSION['id'])){
			$data["title"] = "簽到簽退系統";
			$this->load->view("template/header2",$data);
			$this->load->view('signin/signin');
			$this->load->view("template/footer2");
		}// End of if(_SESSION['login'])
		else
		{ redirect("/","refresh"); }
	}
	
	// 簽到簽退某一課程
	public function signin_course(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login'] &&
			$this->education_model->is_admin($_SESSION['id'])){
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data["title"] = "簽到簽退系統";
				$this->load->view("template/header2",$data);
				$this->load->view('signin/signin_course');
				$this->load->view("template/footer2");
			}// End of Request Method: GET
			elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
				// 1. 資料庫撈員工資料，有則比對signin資料表，有內容則插intime/outtime
				// 2. 若signin資料表沒有資料，則把員工資料塞進signin資料表並顯示為現場報名並簽到/退
				// 3. 若無員工資料則比對院外人士並簽到(TODO)
				// 4. 若院外也沒有資料，不做任何事
				
				$check = $this->db->get_where('signup',array(
						'IDCARDNO'=>$_POST['signin_id'],
						'course_id'=>$_POST['course_id']
						))->row_array();
				if(!empty($check)){
					$this->db->where(array(
						'IDCARDNO' => $_POST['signin_id'],
						'course_id' => $_POST['course_id']
					));
					if($_POST['time'] === "in"){
						// 簽到
						$this->db->update('signup',array('intime'=>date("H:i")));
					}elseif($_POST['time'] === "out"){
						// 簽退
						$this->db->update('signup',array('outtime'=>date("H:i")));
					}
				}else{
					$employ = $this->db->get_where('ftctl_employ',array('IDCARDNO'=>$_POST['signin_id']))->row_array();	
					$course_data = $this->db->get_where('course',array('id'=>$_POST["course_id"]))->row_array();
					$item = array(
							'IDCARDNO' => $_POST['signin_id'],
							'course_id' => $_POST['course_id'],
							'leave_type' => 0,
							'registered' => 0,
							'DATEFROM' => $course_data['DATEFROM'],
							'DATETO' => $course_data['DATETO'],
							'questionnaire' => 0
					);// End of $item
					if($employ){
						// 院內現場報名者
						if($_POST['time'] === "in"){
							$item['intime'] = date("H:i");
							$this->db->insert('signup',$item);
						}elseif($_POST['time'] === "out"){
							$item['outtime'] = date("H:i");
							$this->db->insert('signup',$item);
						}
					}else{
						// TODO: check 院外資料 or 查無此人 不做任何動作
					}
				}// End of if-else(!empty($check))
				$data["title"] = "簽到簽退系統";
				$data["c_id"] = $_POST["course_id"];
				$this->load->view("template/header2",$data);
				$this->load->view('signin/signin_course');
				$this->load->view("template/footer2");
			}// End of Request Method: POST
		}// End of if(_SESSION['login'])
		else
		{ redirect("/","refresh"); }
	}
	
	
	// 如果使用者資料沒有email，進入報名系統時會要求填入email
	// 修改email也用這個頁面
	public function put_email(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data["title"] = "報名系統";
				$this->load->view("template/header2",$data);
				$this->load->view('signup/email');
				$this->load->view("template/footer2");
			}// End of Request Method: GET
			elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
				//檢查欄位是否為email，確定的話就存進資料庫，再redirect到signup頁面
				
				$this->form_validation->set_rules('email','E-Mail', 'required|valid_email');
				
				$this->form_validation->set_message('required', '<font color="red">需要填入 %s</font>');
				$this->form_validation->set_message('valid_email', '<font color="red">E-Mail 格式不正確</font>');
				
				if ($this->form_validation->run() === FALSE){
					$data["title"] = "報名系統";
					$this->load->view("template/header2",$data);
					$this->load->view('signup/email');
					$this->load->view("template/footer2");
				}// End of if form_validation fail
				else{
					// 存入資料庫並轉至signup頁面
					$this->db->where('EPID',$_SESSION['id']);
					$this->db->update('ftctl_employ',array('EMAIL' => $_POST['email']));
					redirect("signup","refresh");
				}
				
			}// End of Request Method: POST
		}// End of if(_SESSION['login'])
		else
			{ redirect("/","refresh"); }
	}// End of function put_email()
	
	// 課程滿意度調查表
	public function questionnaire(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data["title"] = "教育訓練課程滿意度調查表";
				
				// 先get topic 跟 課程日期
				if(isset($_GET['c_id'])){
					$topic_dates = $this->education_model->get_topic_dates($_GET['c_id']);
					$data['topic'] = $topic_dates['topic'];
					$data['datefrom'] = $topic_dates['datefrom'];
					$data['dateto'] = $topic_dates['dateto'];
				}
				
				$this->load->view("template/header",$data);
				$this->load->view('questionnaire',$data);
				$this->load->view("template/footer");
			}// End of Request Method: GET
			elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
				// 儲存問卷
				$this->form_validation->set_rules('topic_id','課程主題', 'required');
				$this->form_validation->set_rules('Q1','第一題', 'required');
				$this->form_validation->set_rules('Q2','第二題', 'required');
				$this->form_validation->set_rules('Q3','第三題', 'required');
				$this->form_validation->set_rules('Q4','第四題', 'required');
				$this->form_validation->set_rules('Q5','第五題', 'required');
				$this->form_validation->set_rules('Q6','第六題', 'required');
				$this->form_validation->set_rules('Q7','第七題', 'required');
				$this->form_validation->set_rules('Q8','第八題', 'required');
				$this->form_validation->set_rules('Q9','第九題', 'required');
				$this->form_validation->set_rules('Q10','第十題', 'required');
				
				$this->form_validation->set_message('required', '<font color="red">需要填入 %s</font>');
				
				if ($this->form_validation->run() === FALSE){
					$data["title"] = "教育訓練課程滿意度調查表";
					
					// 先get topic 跟 課程日期
					if(isset($_POST['topic_id'])){
						$topic_dates = $this->education_model->get_topic_dates($_POST['topic_id']);
						$data['topic'] = $topic_dates['topic'];
						$data['datefrom'] = $topic_dates['datefrom'];
						$data['dateto'] = $topic_dates['dateto'];
					}
					
					$this->load->view("template/header",$data);
					$this->load->view('questionnaire',$data);
					$this->load->view("template/footer");
				}// End of false validation
				
				else{ // 成功送出表單
					// 將表單存入資料庫
					$answer = array(
						'TOPIC_ID' => $_POST['topic_id'],
						'Q1' => $_POST['Q1'],
						'Q2' => $_POST['Q2'],
						'Q3' => $_POST['Q3'],
						'Q4' => $_POST['Q4'],
						'Q5' => $_POST['Q5'],
						'Q6' => $_POST['Q6'],
						'Q7' => $_POST['Q7'],
						'Q8' => $_POST['Q8'],
						'Q9' => $_POST['Q9'],
						'Q10' => $_POST['Q10'],
						'Q11' => $_POST['Q11']
					);
					
					$this->db->insert('questionnaire',$answer);
					
					//將signup table中對應的user id及其課程(course_id)那筆資料，update questionnaire那一欄改成1 
					//表示已經填過問卷了
					$this->education_model->update_questionnaire();
					
					// 返回首頁
					redirect("mainpage","refresh");
				}// End of success validation
				
			}// End of Request Method: POST
		}// End of if(_SESSION['login'])
		else
			{ redirect("/","refresh"); }
	}
	
	// Autocomplete ajax URL that return 群組主題 search results in JSON format
	public function get_group_topic(){
		$term = $_GET['term'];
		$this->db->select('group_topic');
		$this->db->from('group_course');
		$this->db->like('group_topic',$term);
		$this->db->distinct();
		$query = $this->db->get();
		$query = $query->result_array();
		
		$ret = array();
		foreach ($query as $e) // 解決json encode 中文顯示問題
			{$ret[] = urlencode($e['group_topic']);}
		$json = urldecode(json_encode($ret));
		echo $json;
		
	}// End of function get_group_topic()
	
	// Autocomplete ajax URL that return 講師 search results in JSON format
	public function get_speaker(){
		$term = $_GET['term'];
		$this->db->select('*');
		$this->db->from('speaker');
		$this->db->like('NAME',$term);
		$this->db->or_like('IDCARDNO',$term);
		$query = $this->db->get();
		$query = $query->result_array();
		
		$ret = array();
		foreach ($query as $e){ // 解決json encode 中文顯示問題
			$ret[] = urlencode($e['NAME'])." ".$e['IDCARDNO']." ".urlencode($e['CURRENTWORK']);
		}
		$json = urldecode(json_encode($ret));
		echo $json;
	}// End of function get_speaker()
	
	// 檢查講師姓名是否存於資料庫中，如果是，回傳 1 ，反之回傳0
	function check_speaker(){
		$q = $_GET['speaker'];
		$this->db->select('*');
		$this->db->from('speaker');
		$this->db->where('NAME',$q);
		$query = $this->db->get();
		$query = $query->row_array();
		
		if(count($query) > 0)
			{ echo $query['NAME']." ".$query['IDCARDNO']." ".$query['CURRENTWORK']; }
		else
			{ echo 0; }
	}// End of function check_speaker()
	
	// ajax post to add new speaker into database
	public function add_speaker(){	
		if($_SERVER['REQUEST_METHOD'] === 'POST'){

			// 插入講師資料庫
			$item = array(
				'NAME' => $_POST['NAME'],
				'IDCARDNO' => $_POST['IDCARDNO'],
				'WORKEXP' => $_POST['WORKEXP'],
				'CURRENTWORK' => $_POST['CURRENTWORK'],
				'EDUCATION' => $_POST['EDUCATION']
			);
			$this->db->insert('speaker',$item);

			// 從資料庫取資料做驗證，由於身分證是唯一的，所以以身分證字號做驗證
			$this->db->select("*");
			$query = $this->db->get_where('speaker',array('IDCARDNO' => $_POST['IDCARDNO']));
			$query = $query->row_array();
			echo $query['NAME']." ".$query['IDCARDNO']." ".$query['CURRENTWORK'];
		
		}// End of Request Method: POST
	}// End of function add_speaker()
	
	// 簽到時修改假別
	public function change_leave_type(){
		if($_SERVER['REQUEST_METHOD'] === 'POST'){
			$this->db->where(array('course_id'=>$_POST['course_id'],'IDCARDNO'=>$_POST['IDCARDNO']));
			$this->db->update('signup',array('leave_type'=>$_POST['leave_type']));
			$ret = $this->db->get_where('signup',array('course_id'=>$_POST['course_id'],'IDCARDNO'=>$_POST['IDCARDNO']));
			$ret = $ret->row_array()['leave_type'];
			echo $ret;
		}// End of Request Method: POST
	}// End of function change_leave_type
	
	
	// 修改課程 www.domain.com/url?c_id=4
	public function modify_course(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login'] &&
			$this->education_model->is_admin($_SESSION['id'])){
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				if(isset($_GET['c_id'])){	
					$data["title"] = "修改課程";
					$this->load->view("template/header2",$data);
					$this->load->view('course/modify_course');
					$this->load->view("template/footer2");
				}elseif (isset($_GET['g_id'])){
					// 提供選單，選擇小節來修改
					$data["title"] = "修改課程";
					$this->load->view("template/header2",$data);
					$this->load->view('course/modify_group_course');
					$this->load->view("template/footer2");
				}
			}// End of Request Method: GET
			elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
				$course_data = $this->db->get_where('course',array('id'=>$_POST['c_id']))->row_array();
				$item = array();
					
				if($_POST['topic'] != $course_data['TOPIC']) { $item['TOPIC'] = $_POST['topic']; }
				if($_POST['group_order'] != $course_data['GROUP_ORDER']) { $item['GROUP_ORDER'] = $_POST['group_order']; }
				if($_POST['place'] != $course_data['PLACE']) { $item['PLACE'] = $_POST['place']; }
				if($_POST['from'] != $course_data['DATEFROM']) { $item['DATEFROM'] = $_POST['from']; }
				if($_POST['to'] != $course_data['DATETO']) { $item['DATETO'] = $_POST['to']; }
				if($_POST['timefrom'] != $course_data['TIMEFROM']) { $item['TIMEFROM'] = $_POST['timefrom']; }
				if($_POST['timeto'] != $course_data['TIMETO']) { $item['TIMETO'] = $_POST['timeto']; }
				if($_POST['credit'] != $course_data['CREDIT']) { $item['CREDIT'] = $_POST['credit']; }
				if($_POST['course_type'] != $course_data['COURSE_TYPE']) { $item['COURSE_TYPE'] = $_POST['course_type']; }
				if($_POST['class_type'] != $course_data['CLASS_TYPE']) { $item['CLASS_TYPE'] = $_POST['class_type']; }
				if($_POST['limit'] != $course_data['UPLIMIT']) { $item['UPLIMIT'] = $_POST['limit']; }
				if($_POST['note'] != $course_data['NOTE']) { $item['NOTE'] = $_POST['note']; }
				if($_POST['introduction'] != $course_data['INTRODUCTION']) { $item['INTRODUCTION'] = $_POST['introduction']; }
				
				// 更新講師id
				$s_id = $this->education_model->check_speaker(explode(" ", $_POST['speaker'])[1]);
				if($s_id != $course_data['SPEAKER_ID']) { $item['SPEAKER_ID'] = $s_id; }
				
				// 更新課程資訊
				$this->db->where('id',$_POST['c_id']);
				$this->db->update('course',$item);
				
				// 更新群組總學分數
				if($course_data['TOPIC_TYPE'] === "1" && $_POST['credit'] != $course_data['CREDIT']){
					$group_data = $this->db->get_where('group_course',
							array('id'=>$course_data['GROUP_TOPIC_ID']))->row_array();
					$credit = $group_data['CREDIT'] - $course_data['CREDIT'] + $_POST['credit'];
					$this->db->where('id',$course_data['GROUP_TOPIC_ID']);
					$this->db->update('group_course',array('CREDIT'=>$credit));
				}
				
				// 更新報名資料 signup table中的日期
				$this->db->where('id',$_POST['c_id']);
				$this->db->update('signup',array('DATEFROM' => $_POST['from'], 'DATETO' => $_POST['to']));
				
				// 顯示修改成功頁面
				$data["title"] = "課程修改完畢";
				$data["type"] = 1; // 表示為修改成功頁面
				$this->load->view("template/header2",$data);
				$this->load->view('course/course_success');
				$this->load->view("template/footer2");
			
			}// End of Request Method: POST
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function modify_course()
	
	// www.domain.com/url?c_id=3,4,5
	public function delete_course(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login'] &&
			$this->education_model->is_admin($_SESSION['id'])){
			
			$courses = explode(",", $_GET['c_id']); // 分解query string成多個課程
			foreach ($courses as $e){
				$course_data = $this->db->get_where('course',array('id'=>$e))->row_array();
				// 若為群組課
				if($course_data['TOPIC_TYPE'] === "1"){
					$g_count = $this->db->get_where('course',
							array('GROUP_TOPIC_ID'=>$course_data['GROUP_TOPIC_ID']))->num_rows();
					if($g_count === 1){ // 檢查是否是群組中最後一個小節，是的話要delete group_topic
						$this->db->delete('group_course',array('id'=>$course_data['GROUP_TOPIC_ID']));
					}elseif($g_count > 1){ // 扣掉群組課此小節的學分數
						$credit = $this->db->get_where('group_course',
								array('id'=>$course_data['GROUP_TOPIC_ID']))->row_array()['CREDIT'];
						$credit = $credit - $course_data['CREDIT'];
					}// End of if($g_count === 1)
				}// End of if($course_data['TOPIC_TYPE'] === "1")
				
				// 刪除課程資訊
				$this->db->delete('course', array('id' => $e));
				// 也要刪除報名的資料
				$this->db->delete('signup',array('course_id'=>$e));
			}// End of foreach

			redirect("signup","refresh");
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function delete_course()

	// Ajax post to modify group topic and dates
	public function modify_group_course(){
		//修改日期，連對應的小節也要修改日期
		$item = array(
			'DATEFROM' => $_POST['from'],
			'DATETO' => $_POST['to']
		);
		$this->db->where('GROUP_TOPIC_ID',$_POST['g_id']);
		$this->db->update('course',$item);
		
		// 也要修改報名資料中的日期
		foreach ($this->db->get_where('course',
				array('GROUP_TOPIC_ID'=>$_POST['g_id']))->result_array() as $e){
			$this->db->where('course_id',$e['id']);
			$this->db->update('signup',$item);
				
		}
		
		$item['group_topic'] = $_POST['group_topic'];
		$this->db->where('id',$_POST['g_id']);
		$this->db->update('group_course',$item);
		
		
		echo "done";
		
	}
	
	// 設定分頁
	function set_pagination(&$config){
		
		//載入CI分頁模組，製作分頁連結
		$this->load->library('pagination');
		
		$config['page_query_string'] = TRUE; //將分頁連結改為query string模式
		
		$config['query_string_segment'] = 'page'; //改變預設分頁字串per_page
		
		//$config['per_page'] = 10;
		$config['num_links'] = 3;
		
		$config['use_page_numbers'] = TRUE;
		//修改分頁css
	
		$config['full_tag_open'] = '<div class="pagination"><ul>';
		$config['full_tag_close'] = '</ul></div>';
			
		$config['first_link'] = '&laquo;';//自訂開始分頁連結名稱
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';
			
		$config['last_link'] = '&raquo;'; //自訂結束分頁連結名稱
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';
			
		$config['next_link'] = '下一頁 >';
		$config['next_tag_open'] = '<li class="next page">'; //自訂下一頁標籤
		$config['next_tag_close'] = '</li>';
			
		$config['prev_link'] = '< 上一頁';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a>';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';

		
		//初始化分頁連結
		$this->pagination->initialize($config);
		
	}
	
	
	public function db_test(){
		
		/*
		$this->db->select('SAVH_FTCTL_EMPLOY.IDCARDNO');
		$this->db->from('SAVH_FTCTL_EMPLOY');
		$this->db->join('YSVH_FTCTL_EMPLOY','YSVH_FTCTL_EMPLOY.EPID = SAVH_FTCTL_EMPLOY.EPID');
		$common = array();
		foreach ($this->db->get()->result_array() as $e){
			array_push($common, $e['IDCARDNO']);
		
		}
		
		$this->db->select('*');
		$this->db->from('SAVH_FTCTL_EMPLOY');
		$this->db->where_not_in('IDCARDNO',$common);
		
		$q = $this->db->get()->result_array();
		foreach ($q as $e){
			$e['BRANCHNO'] = 1;
			$this->db->insert('FTCTL_EMPLOY',$e);
			echo $e['EPNAME']." inserted.<br>";
		}
		*/
		
		/*
		foreach ($this->db->get('savh_ftctl_depart')->result_array() as $e){
			$e['BRANCHNO'] = 1;
			$this->db->insert('ftctl_depart',$e);
			echo $e['DPNAME']." inserted.<br>";
		}
		*/
		

	}// End of function db_test()
	
	
	// 數位學習首頁
	public function elearning(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
				$data["title"] = "數位學習首頁";
				
				//總資料列數
				$data['total_rows'] = $this->db->get_where('elearning',
					array('EPID'=>$_SESSION['id'],'SEEN'=>1,'AUTH_STATUS'=>1))->num_rows();
				//用query string判斷目前是第幾頁
				if(isset($_GET['page']) && !empty($_GET['page'])){$page = (int)$_GET['page'];}
				else{$page = 1;}
				//每頁筆數固定10筆
				$para['perpage'] = 10;
				//第幾筆開始撈
				$para['offset'] = $para['perpage'] * ($page-1);
				//撈資料
				$this->db->limit($para['perpage'],$para['offset']);
				$this->db->order_by('DATE','desc');
				
				$data['query'] = $this->db->get_where('elearning',
					array('EPID'=>$_SESSION['id'],'SEEN'=>1,'AUTH_STATUS'=>1))->result_array();
				
				
				//載入CI分頁模組，製作分頁連結
				$config['per_page'] = $para['perpage'];
				$config['base_url'] = base_url().'/elearning/?';
				$config['total_rows'] =$data['total_rows'];
				
				$this->set_pagination($config);
				
				
				$this->load->view("template/header2",$data);
				$this->load->view('elearning/elearning_main');
				$this->load->view("template/footer2");		
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function elearning()
	
	// 數位學習認證追縱進度
	public function elearning_trace(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			$data["title"] = "數位學習登錄追蹤進度";
			$this->load->view("template/header2",$data);
			$this->load->view('elearning/elearning_trace');
			$this->load->view("template/footer2");
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function elearning_trace()
	
	// 數位學習認證確認
	// elearning_auth_accept?e_id=
	public function elearning_auth_accept(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data["title"] = "數位學習認證確認";
				$this->load->view("template/header2",$data);
				$this->load->view('elearning/elearning_auth_accept');
				$this->load->view("template/footer2");
			}// End of Request Method: GET
			
			elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
				if($_POST['accept']){
					$this->db->where('id',$_POST['e_id']);
					$this->db->update('elearning',array('SEEN'=>1));
				}
				redirect("elearning_trace","refresh");
			}// End of Request Method: POST
		
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function elearning_auth_accept()
	
	
	// 數位學習課程認證
	public function elearning_auth(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			$data["title"] = "數位學習課程認證";
			$this->load->view("template/header2",$data);
			$this->load->view('elearning/elearning_auth');
			$this->load->view("template/footer2");
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function elearning_auth()
	
	// 數位學習課程認證某一課程
	// elearning_auth_course?e_id=
	public function elearning_auth_course(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('status', '認證是否同意', 'required');
			
			$this->form_validation->set_message('required', '<font color="red">需要填入 %s</font>');
			
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data["title"] = "數位學習課程認證";
				$this->load->view("template/header2",$data);
				$this->load->view('elearning/elearning_auth_course');
				$this->load->view("template/footer2");
			}// End of Request Method: GET
			elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
				
				if ($this->form_validation->run() === FALSE){
					$data["title"] = "數位學習課程認證";
					$data["e_id"] = $_POST['e_id'];
					$this->load->view("template/header2",$data);
					$this->load->view('elearning/elearning_auth_course',$data);
					$this->load->view("template/footer2");
				}// End of form validation error
				
				else{
					$item = array(
						'AUTH_STATUS' => $_POST['status'],
						'AUTH_COMMENT' => $_POST['comment']	
					);
					
					$this->db->where('id',$_POST["e_id"]);
					$this->db->update('elearning',$item);
					
					redirect("elearning_auth","refresh");
					
				}// End of else form validation error
				
			}// End of Request Method: POST
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function elearning_auth_course()
	
	//登錄數位學習時數
	public function elearning_log(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
					
			$this->form_validation->set_rules('topic', '課程主題', 'required');
			$this->form_validation->set_rules('date', '日期', 'required');
			$this->form_validation->set_rules('timefrom', '開始時間', 'required|callback_time_rule');
			$this->form_validation->set_rules('timeto', '結束時間', 'required|callback_time_rule');
			$this->form_validation->set_rules('credit', '時數', 'required|numeric');
			$this->form_validation->set_rules('course_type', '類別', 'required');
			
			// Set validation error messages
			$this->form_validation->set_message('required', '<font color="red">需要填入 %s</font>');
			$this->form_validation->set_message('numeric', '<font color="red">%s 欄位必須為數字</font>');
			$this->form_validation->set_message('time_rule', '<font color="red">%s 格式錯誤，請輸入 小時:分鐘 (例：15:20)</font>');

			
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data["title"] = "登錄數位學習時數";
				$data["error"] = "";
				$this->load->view("template/header2",$data);
				$this->load->view('elearning/elearning_log',$data);
				$this->load->view("template/footer2");
			}// End of Request Method: GET
			elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
				
				if ($this->form_validation->run() === FALSE){
					$data["title"] = "登錄數位學習時數";
					$data["error"] = "";
					$this->load->view("template/header2",$data);
					$this->load->view('elearning/elearning_log',$data);
					$this->load->view("template/footer2");
				}// End of form validation error
				else{
					// Upload files
					$config['upload_path'] = './uploads/';
					$config['allowed_types'] = 'jpg|png|pdf|zip';
					$config['encrypt_name'] = TRUE;
				
					$this->load->library('upload',$config);
					
					if ( ! $this->upload->do_upload()){
						$data = array('error' => $this->upload->display_errors(),'title'=>'登錄數位學習時數');
						$this->load->view("template/header2",$data);
						$this->load->view('elearning/elearning_log',$data);
						$this->load->view("template/footer2");
					}
					else{ // Upload success and insert into database.
						
						$repeat = $this->db->get_where('elearning',array(
							'TOPIC'=>$_POST['topic'],
							'EPID' => $_SESSION['id'],
							'DATE' => $_POST['date'],
							'CREDIT' => $_POST['credit']
						))->num_rows();
						
						if($repeat) { $data['repeat'] = 1; }
						else {
						
							$item = array(
								'TOPIC' => $_POST['topic'],
								'EPID' => $_SESSION['id'],
								'BRANCHNO' => $this->education_model->search_by_ID_or_name($_SESSION['id'])[0]['BRANCHNO'],
								'DATE' => $_POST['date'],
								'TIMEFROM' => $_POST['timefrom'],
								'TIMETO' => $_POST['timeto'],
								'CREDIT' => $_POST['credit'],
								'COURSE_TYPE' => $_POST['course_type'],
								'DOC_LOCATION' => $this->upload->data()['full_path'],
								'AUTH_STATUS' => 0,
								'SEEN' => 0
							);
						
							$this->db->insert('elearning',$item);
						}// End of else ($repeat)
						
						// Show success page
						$data['title'] = "登錄數位學習課程成功";
						$data['filedata'] = $this->upload->data();
						$this->load->view("template/header2",$data);
						$this->load->view('elearning/elearning_success',$data);
						$this->load->view("template/footer2");
						
					}// End of else ($this->upload->do_upload())
				
				}// End of else form validation error
				
			}// End of Request Method: POST
		}// End of if(_SESSION['login'])
		else
		{ redirect("/","refresh"); }
	}// End of function elearning_log()
	
	// 下載認證文件檔
	// elearning_docs?$e_id=
	public function elearning_docs(){
		$e_data = $this->db->get_where('elearning',array('id'=>$_GET['e_id']))->row_array();
		$file = $e_data['DOC_LOCATION'];
		$ext_name = explode(".", basename($file))[1];
		$filename = 'elearning_doc_'.$_GET['e_id'].'.'.$ext_name;
		
		if (file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.$filename);
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			ob_clean();
			flush();
			readfile($file);
			exit;
		}
	}// End of function elearning_docs()
	
	
	// 院外研習心得
	public function extracurricular(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			
			//總資料列數
			$data['total_rows'] = $this->db->get_where('extracurricular',
				array('EPID'=>$_SESSION['id'],'SEEN'=>1,'AUTH_STATUS'=>1))->num_rows();
			//用query string判斷目前是第幾頁
			if(isset($_GET['page']) && !empty($_GET['page'])){$page = (int)$_GET['page'];}
			else{$page = 1;}
			//每頁筆數固定10筆
			$para['perpage'] = 10;
			//第幾筆開始撈
			$para['offset'] = $para['perpage'] * ($page-1);
			//撈資料
			$this->db->limit($para['perpage'],$para['offset']);
			$this->db->order_by('DATE','desc');
				
			$data['query'] = $this->db->get_where('extracurricular',
				array('EPID'=>$_SESSION['id'],'SEEN'=>1,'AUTH_STATUS'=>1))->result_array();
				
				
			//載入CI分頁模組，製作分頁連結
			$config['per_page'] = $para['perpage'];
			$config['base_url'] = base_url().'/extracurricular/?';
			$config['total_rows'] =$data['total_rows'];
			
			$this->set_pagination($config);
				
			$data["title"] = "院外研習心得";
			$this->load->view("template/header2",$data);
			$this->load->view('extracurricular/extracurricular_main');
			$this->load->view("template/footer2");
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function extracurricular()
	
	
	function expsuggest_rule($str,$leave_type){
		if($leave_type === "1") { return TRUE; }
		else {  if(empty($str)) { return FALSE;}
				else 			{ return TRUE; }
		}
	}
	
	// 登錄院外研習心得報告
	public function extracurricular_log(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('topic', '課程主題', 'required');
			$this->form_validation->set_rules('date', '日期', 'required');
			$this->form_validation->set_rules('host', '主辦單位', 'required');
			$this->form_validation->set_rules('place', '地點', 'required');
			$this->form_validation->set_rules('course_type', '類別', 'required');
			$this->form_validation->set_rules('leave_type', '假別', 'required');
			$this->form_validation->set_rules('selfassess', '自我評估', 'required');
			//$this->form_validation->set_rules('learned', '您學到了什麼', 'required');
			
				
			// Set validation error messages
			$this->form_validation->set_message('required', '<font color="red">需要填入 %s</font>');
			$this->form_validation->set_message('expsuggest_rule', '<font color="red">需要填入 %s</font>');
			
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data["error"] = "";
				$data["title"] = "院外研習心得報告";
				$this->load->view("template/header2",$data);
				$this->load->view('extracurricular/extracurricular_log',$data);
				$this->load->view("template/footer2");
			}// End of Request Method: GET
			elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
				
				$this->form_validation->set_rules('expsuggest', '心得建議', 'callback_expsuggest_rule['.$_POST['leave_type'].']');
				
				if ($this->form_validation->run() === FALSE){
					$data["title"] = "院外研習心得報告";
					$data["error"] = "";
					$this->load->view("template/header2",$data);
					$this->load->view('extracurricular/extracurricular_log',$data);
					$this->load->view("template/footer2");
				}// End of form validation error
				else{
					// Upload files
					$config['upload_path'] = './uploads/';
					$config['allowed_types'] = 'jpg|png|pdf|zip';
					$config['encrypt_name'] = TRUE;
				
					$this->load->library('upload',$config);
						
					if ( ! $this->upload->do_upload()){
						$data = array('error' => $this->upload->display_errors(),'title'=>'院外研習心得報告');
						$this->load->view("template/header2",$data);
						$this->load->view('extracurricular/extracurricular_log',$data);
						$this->load->view("template/footer2");
					}
					else{ // Upload success and insert into database.
					
						$repeat = $this->db->get_where('extracurricular',array(
								'TOPIC'=>$_POST['topic'],
								'EPID' => $_SESSION['id'],
								'DATE' => $_POST['date'],
						))->num_rows();
						
						if($repeat) { $data['repeat'] = 1; }
						else {
						
							$item = array(
								'TOPIC' => $_POST['topic'],
								'EPID' => $_SESSION['id'],
								'BRANCHNO' => $this->education_model->search_by_ID_or_name($_SESSION['id'])[0]['BRANCHNO'],
								'DATE' => $_POST['date'],
								'HOST' => $_POST['host'],
								'PLACE' => $_POST['place'],
								'COURSE_TYPE' => $_POST['course_type'],
								'leave_type' => $_POST['leave_type'],
								'selfassess' => $_POST['selfassess'],
								'learned' => $_POST['learned'],
								'expsuggest' => $_POST['expsuggest'],
								'DOC_LOCATION' => $this->upload->data()['full_path'],
								'AUTH_STATUS' => 0,
								'SEEN' => 0
							);
					
							$this->db->insert('extracurricular',$item);
						}// End of else ($repeat)
						// Show success page
						$data['title'] = "院外研習心得報告成功";
						$data['filedata'] = $this->upload->data();
						$this->load->view("template/header2",$data);
						$this->load->view('extracurricular/extracurricular_success',$data);
						$this->load->view("template/footer2");
					
					}// End of else ($this->upload->do_upload())
					
					
				}// End of else form validation error
			
			}// End of Request Method: POST
			
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function extracurricular_log()
	
	
	// 數位學習課程認證
	public function extracurricular_auth(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			$data["title"] = "院外研習心得認證";
			$this->load->view("template/header2",$data);
			$this->load->view('extracurricular/extracurricular_auth');
			$this->load->view("template/footer2");
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function extracurricular_auth()
	
	// 院外研習認證某一課程
	// extracurricular_auth_course?e_id=
	public function extracurricular_auth_course(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
				
			$this->load->helper(array('form', 'url'));
			$this->load->library('form_validation');
				
			$this->form_validation->set_rules('status', '認證是否同意', 'required');
				
			$this->form_validation->set_message('required', '<font color="red">需要填入 %s</font>');
				
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data["title"] = "院外研習心得認證";
				$this->load->view("template/header2",$data);
				$this->load->view('extracurricular/extracurricular_auth_course');
				$this->load->view("template/footer2");
			}// End of Request Method: GET
			elseif ($_SERVER['REQUEST_METHOD'] === 'POST'){
	
				if ($this->form_validation->run() === FALSE){
					$data["title"] = "院外研習心得認證";
					$data["e_id"] = $_POST['e_id'];
					$this->load->view("template/header2",$data);
					$this->load->view('extracurricular/extracurricular_auth_course',$data);
					$this->load->view("template/footer2");
				}// End of form validation error
	
				else{
					$item = array(
							'AUTH_STATUS' => $_POST['status'],
							'AUTH_COMMENT' => $_POST['comment']
					);
						
					$this->db->where('id',$_POST["e_id"]);
					$this->db->update('extracurricular',$item);
						
					redirect("extracurricular_auth","refresh");
						
				}// End of else form validation error
	
			}// End of Request Method: POST
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function extracurricular_auth_course()
	
	// 院外研習認證追縱進度
	public function extracurricular_trace(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			$data["title"] = "院外研習登錄追蹤進度";
			$this->load->view("template/header2",$data);
			$this->load->view('extracurricular/extracurricular_trace');
			$this->load->view("template/footer2");
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function extracurricular_trace()
	
	// 院外研習認證確認
	// extracurricular_auth_accept?e_id=
	public function extracurricular_auth_accept(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			$data["accept_page"] = True;
			
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data["title"] = "院外研習認證確認";
				$this->load->view("template/header2",$data);
				$this->load->view('extracurricular/extracurricular_auth_accept');
				$this->load->view("template/footer2");
			}// End of Request Method: GET
				
			elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
				if($_POST['accept']){
					$this->db->where('id',$_POST['e_id']);
					$this->db->update('extracurricular',array('SEEN'=>1));
				}
				redirect("extracurricular_trace","refresh");
			}// End of Request Method: POST
	
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function extracurricular_auth_accept()
	
	// 院外研習課程詳細資料
	// extracurricular_detail?e_id=
	public function extracurricular_detail(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			$data["accept_page"] = False;
				
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$data["title"] = "院外研習認證詳細資料";
				$this->load->view("template/header2",$data);
				$this->load->view('extracurricular/extracurricular_auth_accept');
				$this->load->view("template/footer2");
			}// End of Request Method: GET
	
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function extracurricular_detail()
	
	
	// 下載院外研習認證文件檔
	// extracurricular_docs?$e_id=
	public function extracurricular_docs(){
		$e_data = $this->db->get_where('extracurricular',array('id'=>$_GET['e_id']))->row_array();
		$file = $e_data['DOC_LOCATION'];
		$ext_name = explode(".", basename($file))[1];
		$filename = 'extracurricular_doc_'.$_GET['e_id'].'.'.$ext_name;
	
		if (file_exists($file)) {
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename='.$filename);
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			ob_clean();
			flush();
			readfile($file);
			exit;
		}
	}// End of function elearning_docs()
	
	
	// 設定
	public function settings(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			$data["title"] = "設定";
			$this->load->view("template/header2",$data);
			$this->load->view('settings/settings');
			$this->load->view("template/footer2");
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function settings()
	
	// 修改密碼
	public function password(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			
			if ($_SERVER['REQUEST_METHOD'] === "POST"){
				$data['message'] = "<font color='red'>";
				/* 1. 檢查舊密碼是否輸入正確
				 * 2. 檢查新密碼兩個是否一樣
				 * 3. 更新資料庫， message設為修改成功
				 */
				
				$user_info = $this->education_model->search_by_ID_or_name($_SESSION['id'])[0];
				if($user_info['PASSWORD'] != $_POST['old_pwd']){
					$data['message'] = $data['message']."舊密碼輸入錯誤 ";
				}elseif ($_POST['new_pwd'] != $_POST['new_pwd_again'] || empty($_POST['new_pwd'])){
					$data['message'] = $data['message']."新密碼輸入錯誤，請重新輸入。";
				}else{
					$this->db->where('EPID',$_SESSION['id']);
					$this->db->update('FTCTL_EMPLOY',array('PASSWORD'=>$_POST['new_pwd']));
					$data['message'] = $data['message']."密碼修改成功！";
				}
				$data['message'] = $data['message']."</font>";
			}
			
			$data["title"] = "修改密碼";
			$this->load->view("template/header2",$data);
			$this->load->view('settings/password');
			$this->load->view("template/footer2");
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function password()
	
	
	// 修改類別
	public function course_type_settings(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			
			
			if($_SERVER['REQUEST_METHOD'] === 'POST'){
				
				$this->db->select_max('id');
				$last_id = (int)$this->db->get('course_type')->row_array()['id'];
				
				$data['message'] = "<font color='red'>";
				
				foreach ($_POST as $k => $v){
					$key = explode("-", $k);
					$check = $this->db->get_where('course_type',array('id'=>$key[0]))->num_rows();
					if(!$check && !empty($v)){ // ID不存在就 insert
						$this->db->insert('course_type',array('id'=>$key[0],$key[1]=> $v));
					}else{ // 存在就update
						$this->db->where('id',$key[0]);
						$this->db->update('course_type',array($key[1] => $v));
					}
				}// End of foreach($_POST)
				$data['message'] = $data['message']."更新完成。</font>";
			}// End of Request Method: POST
			
			$data["title"] = "修改課程類別";
			$this->load->view("template/header2",$data);
			$this->load->view('settings/course_type');
			$this->load->view("template/footer2");
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function course_type_settings()
	
	// 刪除類別 by Ajax post
	public function delete_course_type(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login'] &&
		 	$this->education_model->is_admin($_SESSION['id'] === 5)){
			
			if ($_SERVER['REQUEST_METHOD'] === "POST"){
				$this->db->delete('course_type',array('id'=>$_POST['id']));
				echo "1";
			}// End of Request Method: POST
			
		}// End of if(_SESSION['login'])
	}// End of function delete_course_type()
	
	// 修改簽到退時間
	public function signin_settings(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']&&
		 	$this->education_model->is_admin($_SESSION['id'] === 5)){
			
			if($_SERVER['REQUEST_METHOD'] === "POST"){
				$data['course'] = $this->education_model->search_course($_POST['course_name']);
			}// End of Request Method: POST 
			
			$data["title"] = "修改簽到退時間";
			$this->load->view("template/header2",$data);
			$this->load->view('settings/signin_settings');
			$this->load->view("template/footer2");
			
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function signin_settings()
	
	// 修改簽到退時間 (選擇課程)
	public function signin_settings_select(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']&&
		$this->education_model->is_admin($_SESSION['id'] === 5)){
				
			if($_SERVER['REQUEST_METHOD'] === "POST"){
				$employ = $this->db->get_where('ftctl_employ',array('IDCARDNO'=>$_POST['signin_id']))->row_array();
				$course_data = $this->db->get_where('course',array('id'=>$_POST["course_id"]))->row_array();
				$item = array(
						'IDCARDNO' => $_POST['signin_id'],
						'course_id' => $_POST['course_id'],
						'leave_type' => 0,
						'registered' => 0,
						'DATEFROM' => $course_data['DATEFROM'],
						'DATETO' => $course_data['DATETO'],
						'questionnaire' => 0
				);// End of $item
				if($employ){
					// 院內現場報名者
					if($_POST['time'] === "in"){
						$item['intime'] = date("H:i");
						$this->db->insert('signup',$item);
					}elseif($_POST['time'] === "out"){
						$item['outtime'] = date("H:i");
						$this->db->insert('signup',$item);
					}
				}else{
					// TODO: check 院外資料 or 查無此人 不做任何動作
				}
				$data["c_id"] = $_POST["course_id"];
			}// End of Request Method: POST
				
			$data["title"] = "修改簽到退時間";
			$this->load->view("template/header2",$data);
			$this->load->view('settings/signin_settings_select');
			$this->load->view("template/footer2");
				
		}// End of if(_SESSION['login'])
		else { redirect("/","refresh"); }
	}// End of function signin_settings_select()
	
	// Ajax post 修改時間
	public function modify_time(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']&&
		$this->education_model->is_admin($_SESSION['id'] === 5)){
		
			if($_SERVER['REQUEST_METHOD'] === "POST"){
				$this->db->where(array('IDCARDNO'=> $_POST['id'], 'course_id'=> $_POST['c_id']));
				
				if (isset($_POST['intime'])){
					$this->db->update('signup',array('intime'=>$_POST['intime']));
				}elseif(isset($_POST['outtime'])){
					$this->db->update('signup',array('outtime'=>$_POST['outtime']));
				}
				
				echo "1";
				
			}// End of Request Method: POST
			
		}// End of if(_SESSION['login'])
	}// End of function modify_time()
	
	public function send_email(){
		session_start();
		$this->check_session_expire();
		if(isset($_SESSION['login'])  && $_SESSION['login']){
			$data["title"] = "Email 通知系統";
			$this->load->view("template/header2",$data);
			
			if($_SERVER['REQUEST_METHOD'] === 'GET'){
				$this->load->view('send_email');
			}// End of Request Method: GET
			
			elseif($_SERVER['REQUEST_METHOD'] === 'POST'){
				$emails = explode(",", $_POST['to']);
				$content = str_replace("\n", "<br>", $_POST['content']);
				$data['send_ret'] = $this->education_model->send_email($emails,$_POST['subject'],$content);
				$this->load->view('send_email',$data);
			}// End of Request Method: POST
			
			$this->load->view("template/footer2");
			
		}// End of if(login)
		else { redirect("/","refresh"); }
	}
	
	public function logout(){
		session_start();
		session_destroy();
		redirect("/","refresh");
	}// End of function logout()
	
}// End of class Control