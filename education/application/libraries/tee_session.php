<?php
class Tee_session
{
	public function __construct()
	{
		session_start();//���J�ɷ|����
	}

	//�]�wSession��
	public function set( $key, $value )
	{
		$_SESSION[$key] = $value;
	}

	//���XSession�ȡA�p�G�S���Ȧ^��null
	public function get( $key )
	{
		return isset( $_SESSION[$key] ) ? $_SESSION[$key] : null;
	}

	//�R��Session
	public function delete( $key )
	{
		unset( $_SESSION[$key] );
	}
}
?>