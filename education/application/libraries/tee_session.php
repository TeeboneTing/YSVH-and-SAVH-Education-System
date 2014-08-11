<?php
class Tee_session
{
	public function __construct()
	{
		session_start();//載入時會執行
	}

	//設定Session值
	public function set( $key, $value )
	{
		$_SESSION[$key] = $value;
	}

	//取出Session值，如果沒有值回傳null
	public function get( $key )
	{
		return isset( $_SESSION[$key] ) ? $_SESSION[$key] : null;
	}

	//刪除Session
	public function delete( $key )
	{
		unset( $_SESSION[$key] );
	}
}
?>