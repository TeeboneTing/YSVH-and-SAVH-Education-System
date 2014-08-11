<?php
class Education_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}

	function connect_remoteDB_odbc($ip){
		// mysqli and PDO dont support MySQL 4.0
		// Use MySQL ODBC 3.51 Driver. This is a 32-bit Driver
		$database="";
		$user="";
		$password="";
		$conn = odbc_connect("Driver={MySQL ODBC 3.51 Driver};Server=$ip;Database=$database;", $user, $password);
		return $conn;
	}

	public function odbc_query($ip,$sql){
		$conn = $this->connect_remoteDB_odbc($ip);
		$result = odbc_exec($conn,$sql);
		return $result;
	}
	
	//$sql = "SELECT * FROM FTCTL_EMPLOY WHERE epid = '$usr' AND password = '$pwd'";
	public function check_employ($usr,$pwd){
		$where = array('epid'=>$usr,'password'=>$pwd);
		return $this->db->get_where('FTCTL_EMPLOY',$where);
	}
	
	public function check_ysvh_employ($usr,$pwd){
		$where = array('epid'=>$usr,'password'=>$pwd);
		return $this->db->get_where('YSVH_FTCTL_EMPLOY',$where);
	}

	public function check_savh_employ($usr,$pwd){
		$where = array('epid'=>$usr,'password'=>$pwd);
		return $this->db->get_where('SAVH_FTCTL_EMPLOY',$where);
	}
	
	// 檢查使用者是否有填email
	public function check_email($ID){
		$this->db->select('EMAIL');
		$this->db->from('ftctl_employ');
		$this->db->where('EPID',$ID);
		$query = $this->db->get()->row_array();
		return !empty($query['EMAIL']);
	}
	
	public function join($para){
		$this->db->select('*');
		$this->db->from('YSVH_FTCTL_EMPLOY');
		$this->db->join('SAVH_FTCTL_EMPLOY','YSVH_FTCTL_EMPLOY.EPID = SAVH_FTCTL_EMPLOY.EPID');
		$this->db->limit($para['perpage'],$para['offset']);
		return $this->db->get()->result_array();
	}
	
	public function join_count_all(){
		$this->db->select('*');
		$this->db->from('YSVH_FTCTL_EMPLOY');
		$this->db->join('SAVH_FTCTL_EMPLOY',
				'YSVH_FTCTL_EMPLOY.EPID = SAVH_FTCTL_EMPLOY.EPID');
		return $this->db->count_all_results();
	}
	
	public function get_dpname($para){
		$this->db->select('DPNAME');
		$query = $this->db->get_where('FTCTL_DEPART',
				array('DPNO'=>$para['DPNO'],'BRANCHNO' => $para['BRANCHNO']));
		$query = $query->result();
		$ret = $para['DPNO'];
		if(isset($query[0])) $ret = $query[0]->DPNAME;
		return $ret;
	}
	
	// 根據不同的分院撈其全部的部門名稱
	public function get_all_dpname($branch){
		$query = $this->db->get_where('FTCTL_DEPART',array('BRANCHNO'=>$branch));
		return $query->result_array();
	}
	
	// 用帳號撈員工資料
	public function get_ysvh_employ_by_ID($ID){
		$this->db->select('*');
		$query = $this->db->get_where('YSVH_FTCTL_EMPLOY',array('EPID'=>$ID));
		return $query->row_array();
	}
	
	public function get_savh_employ_by_ID($ID){
		$this->db->select('*');
		$query = $this->db->get_where('SAVH_FTCTL_EMPLOY',array('EPID'=>$ID));
		return $query->row_array();
	}
	
	// 用姓名或身分證字號搜尋員工名單
	public function search_by_ID_or_name($query){
		$this->db->select('*');
		$this->db->from('FTCTL_EMPLOY');
		//$this->db->where('EPID',$query);
		//$this->db->or_where('EPNAME',$query);
		$this->db->like('EPNAME', $query);
		$this->db->or_like('EPID', $query);
		$result = $this->db->get();
		return $result->result_array();
	}
	
	public function get_remote_ysvh_employ_by_ID($ID){
		$sql = "SELECT * FROM FTCTL_EMPLOY WHERE EPID = \"$ID\"";
		$ysvh_ret = $this->odbc_query("10.60.6.16", $sql);
		$ret = odbc_fetch_array($ysvh_ret);
		foreach ($ret as $key => &$val){
			if(is_string($val)) // big5 轉 utf8 編碼
				{$val = mb_convert_encoding($val,"utf8","big5");}
			$ret[$key] = $val;
		}
		return $ret;
	}
	
	public function get_remote_savh_employ_by_ID($ID){
		$sql = "SELECT * FROM FTCTL_EMPLOY WHERE EPID = \"$ID\"";
		$savh_ret = $this->odbc_query("192.168.1.52", $sql);
		$ret = odbc_fetch_array($savh_ret);
		foreach ($ret as $key => &$val){
			if(is_string($val)) // big5 轉 utf8 編碼
				{$val = mb_convert_encoding($val,"utf8","big5");}
			$ret[$key] = $val;
		}
		return $ret;
		
	}
	
	public function insert_employ($item){
		//插入前先檢查是否資料庫內已經有資料，若有則不插入
		$query = $this->db->get_where('FTCTL_EMPLOY',array('EPID'=>$item['EPID']));
		$query = $query->row_array();
		if (isset($query['EPID'])){
			echo "資料庫內已經有".$query['EPNAME']."的資料了<br>";
			return 0;
		}else{
			$this->db->insert('FTCTL_EMPLOY',$item);
			return 1;
		}
	}
	
	// 根據 IDCARDNO 來更新部門跟院區
	// 以及更新使用者權限
	public function update_employ($item){
		
		// 更新部門&院區
		$data = array(	'BRANCHNO' => $item['BRANCHNO'],
						'DPNO' => $item['DPNO']
						);
		
		$this->db->where('IDCARDNO',$item['IDCARDNO']);
		$this->db->update('FTCTL_EMPLOY',$data);
		
		// 更新使用者權限
		if($item['ADMIN'] > 0){
			$check = $this->db->get_where('admin_user',array('EPID' => $item['IDCARDNO']))->num_rows();
			$admin_data = array('EPID' => $item['IDCARDNO'],'level' => $item['ADMIN']);
			if($check){ // update
				$this->db->where('EPID',$item['IDCARDNO']);
				$this->db->update('admin_user',$admin_data);
			}else{ // insert
				$this->db->insert('admin_user',$admin_data);
			}
		}else{
			$this->db->delete('admin_user', array('EPID' => $item['IDCARDNO']));
		}
		
		return 1;
	}
	
	// 找出新進人員新進日期從$from到$to範圍之內
	public function get_new_employ($from,$to){
		$sql = "SELECT * FROM FTCTL_EMPLOY WHERE INDAY>='".$from."' AND INDAY<='".$to."';";
		$ysvh_ret = $this->odbc_query("10.60.6.16", $sql);
		$savh_ret = $this->odbc_query("192.168.1.52", $sql);
		$result = Array();
		while ($e = odbc_fetch_array($ysvh_ret)){
			$e['BRANCHNO'] = '0';
			array_push($result, $e);
		}// End of while $ysvh_ret
		while ($e = odbc_fetch_array($savh_ret)){
			// 先檢查是否有重複的帳號出現在$result中，有的話把BRANCHNO設為2然後不用array_push()
			$found = 0;
			foreach ($result as $k => $v){
				if(array_search($e['EPID'], $v)){
					$found = 1;
					$result[$k]['BRANCHNO'] = '2';					
				}// End of if array_search()
			}// End of foreach $result
			if(!$found){
				$e['BRANCHNO'] = '1';
				array_push($result, $e);
			}// End of if not found
			
		}// End of while $savh_ret
		return $result;
	}// End of function get_new_employ()
	
	// 取得所有課程類別
	public function get_all_course_type(){
		$query = $this->db->get('course_type');
		return $query->result_array();
	}// End of function get_all_course_type()
	
	// 用課程類別id取得類別名稱
	public function get_course_type_by_ID($ID){
		$query = $this->db->get_where('course_type',array('id' => $ID));
		return $query->row_array();
	}
	
	public function insert_new_course($item){
		$this->db->insert('course',$item);
	}
	
	// 檢查群組主題是否值為空，為空直接回傳0 有值的話檢查group_course table裡面有沒有存此群組主題
	// 有的話不用插入，直接回傳其id 沒有的話插入group_topic table並回傳其id
	public function check_insert_group_topic($para){
		if(empty($para['topic'])){ return 0; }
		else{
			$query = $this->db->get_where('group_course',array(
				'group_topic'=>$para['topic'],
				'DATEFROM'=>$para['datefrom'],
				'DATETO'=>$para['dateto']
			));
			$query = $query->row_array();
			if (isset($query['group_topic'])){
				// 更新群組學分數 (加起來)
				$new_credit = array( 'CREDIT' => $query['CREDIT'] + $para['credit']);
				$this->db->where('id', $query['id']);
				$this->db->update('group_course',$new_credit);
				return $query['id'];
			}
			else{
				$this->db->insert('group_course',array(
					'group_topic' => $para['topic'],
					'DATEFROM' => $para['datefrom'],
					'DATETO' => $para['dateto'],
					'CREDIT' => $para['credit']
				));
				$query = $this->db->get_where('group_course',array(
					'group_topic'=>$para['topic'],
					'DATEFROM'=>$para['datefrom'],
					'DATETO'=>$para['dateto']
				));
				$query = $query->row_array();
				return $query['id'];
			}// End of else
		}// End of else(empty($g_topic))
		
	}// End of function check_insert_group_topic()
	
	//TODOs: 檢查speaker的方式要改，不能只靠名字(因為會重複)，要加入ID以及職稱進去搜尋
	public function check_speaker($speaker){
		$this->db->select("*");
		$this->db->from('speaker');
		$this->db->where('NAME',$speaker);
		$this->db->or_where('IDCARDNO',$speaker);
		$query = $this->db->get();
		$query = $query->row_array();
		if (isset($query['NAME']))
			{return $query['id'];}
		else{
			return 0;
		}// End of else
	}// End of check_speaker()
	
	// 根據課程id 取得課程主題 開始、結束日期 in an array{'topic'=>...,'datefrom'=>...,'dateto'=>...}
	public function get_topic_dates($c_id){
		$query = $this->db->get_where('course',array('id'=>$c_id))->row_array();
		
		$ret = array(
			'topic' => $query['TOPIC'],
			'datefrom' => $query['DATEFROM'],
			'dateto' => $query['DATETO']
		);
		if($query['TOPIC_TYPE'] === "1") {$ret['topic'] = $this->db->get_where('group_course',
				array('id'=>$query['GROUP_TOPIC_ID']))->row_array()['group_topic'].":".$query['TOPIC'];}
		return $ret;
	}// End of function get_topic_date
	
	// 填完問卷要去註記簽到表上已填過問卷
	public function update_questionnaire(){
		$q = array('questionnaire' => 1);
		$this->db->where(array('IDCARDNO'=>$_SESSION['id'],'course_id'=>$_POST['topic_id']));
		$this->db->update('signup',$q);
	}
	
	public function is_admin($ID){
		// return the admin table EPID with it's level value
		// if there is no such ID in admin table, return 0
		$ret = 0;
		$this->db->select('level');
		$query = $this->db->get_where('admin_user',Array('EPID' => $ID));
		$query = $query->row_array();
		if(count($query)){
			return (int)$query['level'];
		}
		return $ret;
	}
	
	public function send_email($to,$subject,$content){
		require("../phpmailer/class.phpmailer.php");
	
		$mail = new PHPMailer();
		$mail->IsSMTP();
		$mail->SMTPAuth = true; // turn on SMTP authentication
	
		$mail->Username = "";
		$mail->Password = "";
		//這邊是你的gmail帳號和密碼
	
		$mail->FromName = "台北榮總蘇澳暨員山分院 教育訓練系統";
		$mail->From = "";
	
		foreach ($to as $i){ $mail->AddAddress($i,""); }
		// 加入收件人
	
		$mail->WordWrap = 50;
		//每50行斷一次行
	
		//$mail->AddAttachment("/XXX.rar");
		// 附加檔案可以用這種語法(記得把上一行的//去掉)
	
		$mail->IsHTML(true); // send as HTML
	
		$mail->Subject = $subject;
		// 信件標題
		$mail->Body = $content;
		//信件內容(html版，就是可以有html標籤的如粗體、斜體之類)
		//$mail->AltBody = "第一封信";
		//信件內容(純文字版)
	
		if(!$mail->Send()){
			return "寄信發生錯誤：" . $mail->ErrorInfo;
			//如果有錯誤會印出原因
		}
		else{
			return "寄信成功";
		}
	
	}
	
	// 搜尋課程名稱並回傳 個別and群組課程
	public function search_course($query){
		
		// 撈個別課程
		$this->db->like('TOPIC',$query);
		$q = $this->db->get('course')->result_array();
		$return = array();
		
		foreach ($q as $e){
			$item = array(
				'TOPIC' => $e['TOPIC'],
				'id' => $e['id'],
				'TOPIC_TYPE' => $e['TOPIC_TYPE'],
				'DATEFROM' => $e['DATEFROM'],
				'DATETO' => $e['DATETO'],
				'TIMEFROM' => $e['TIMEFROM'],
				'TIMETO' => $e['TIMETO'],
				'CREDIT' => $e['CREDIT'],
				'COURSE_TYPE' => $this->get_course_type_by_ID($e['COURSE_TYPE'])['type_name'],
				'SPEAKER' => $this->db->get_where('speaker',array('id'=>$e['SPEAKER_ID']))->row_array()['NAME'],
				'PLACE' => $e['PLACE']
			);
			
			if($e['TOPIC_TYPE'] === "1"){
				$group = $this->db->get_where('group_course',array('id'=>$e['GROUP_TOPIC_ID']))->row_array();
				$item['GROUP_TOPIC'] = $group['group_topic'];
			}
			
			array_push($return, $item);
		}
		
		// 撈群組課程
		$this->db->like('group_topic',$query);
		$q = $this->db->get('group_course')->result_array();
		
		if (count($q)){
			$g_array = array();
			foreach ($q as $e){
				array_push($g_array, $e['id']);
			}
		
			$this->db->where_in('GROUP_TOPIC_ID',$g_array);
			$q = $this->db->get('course')->result_array();
		
			foreach ($q as $e){
				$item = array(
					'TOPIC' => $e['TOPIC'],
					'id' => $e['id'],
					'TOPIC_TYPE' => $e['TOPIC_TYPE'],
					'DATEFROM' => $e['DATEFROM'],
					'DATETO' => $e['DATETO'],
					'TIMEFROM' => $e['TIMEFROM'],
					'TIMETO' => $e['TIMETO'],
					'CREDIT' => $e['CREDIT'],
					'COURSE_TYPE' => $this->get_course_type_by_ID($e['COURSE_TYPE'])['type_name'],
					'SPEAKER' => $this->db->get_where('speaker',array('id'=>$e['SPEAKER_ID']))->row_array()['NAME'],
					'PLACE' => $e['PLACE']
				);
						
				$group = $this->db->get_where('group_course',array('id'=>$e['GROUP_TOPIC_ID']))->row_array();
				$item['GROUP_TOPIC'] = $group['group_topic'];
							
				array_push($return, $item);
			}
		}// End if count $q
		
		return $return;
	}
	
	/*
	public function test(){
		$this->db->select('*');
		$this->db->from('FTCTL_EMPLOY');
		$this->db->where("INDAY >=","102/01/01","INDAY <=","103/04/04");
		return $this->db->get()->result_array();
	}
	*/
	
}