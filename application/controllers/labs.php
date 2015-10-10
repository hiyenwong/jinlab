<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class labs extends CI_Controller {
	public function __construct() 
	{
		parent::__construct ();
		
		$this->load->library ( 'form_validation' );
		$this->load->library ( 'session' );
		$this->load->helper ( 'form' );
		$this->load->helper ( 'url' );
		$this->load->helper ( 'code_helper' );
		$this->Path = realpath(dirname(dirname(dirname(__FILE__))));
		require_once $this->Path.'/Config/Config.php';
		$this->data = $data;
		$this->ipForbidden = $ipForbidden;
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->ipCheck = false;					
		if ( (bool)stripos('请求IP:'.$this->ip,$this->ipForbidden))
			$this->ipCheck = true;
	}
	public function index() 
	{
		$data = $this->data;
		$data ['title'] = '站务实验室';
		$data ['Name'] = "";
		$data ['Pass'] = "";
		$rs = $this->session->userdata ( 'rs' );
		$data ['ipCheck'] = $this->ipCheck;
		$remember = $this->session->userdata('remember');
		
		if ( $remember != FALSE)
		{
			$remember = xmlRpcDecode($remember);
			$data ['Name'] = $remember['userName'];
			$data ['Pass'] = $remember['userPass'];
		}
		
		if ( isset ( $rs )) 
		{
			if ( $rs == 1) 
			{
				$data ['userName'] = $this->session->userdata ( 'userName' );
				$userInfo = xmlRpcDecode($this->session->userdata('userInfo'));
				$data ['rs'] = $rs;
				$data ['login'] = self::_login ($userInfo);
				$this->load->view ( 'Public/header', $data );
				$this->load->view ( 'Public/navigation', $data );
				$this->load->view ( 'index', $data );		
				$this->load->view ( 'Public/keyEnter' );
				$this->load->view ( 'Public/userAuto' );
				$this->load->view ( 'Public/footer' );
			} 
			else 
			{
				$data['rs'] = 0;				
				
				$this->form_validation->set_rules ( 'userName', '用户名', 'required' );
				$this->form_validation->set_rules ( 'userPass', '密码', 'required' );
				
				if ( $this->form_validation->run () === FALSE) 
				{
					$this->load->view ( 'Public/header', $data );
					$this->load->view ( 'Public/navigation', $data );					
					$this->load->view ( 'index', $data );
					$this->load->view ( 'Public/keyEnter' );
					$this->load->view ( 'Public/userAuto' );
					$this->load->view ( 'Public/footer' );
				} 
				else 
				{
					$remember = self::_remember('read');
					$data ['login'] = self::_login ();
					self::_remember('write',$remember);
					$data ['rs'] = $this->session->userdata ( 'rs' );
					$data ['userName'] = $this->session->userdata ( 'userName' );
					$data ['error'] = $this->session->userdata ( 'error' );
					$this->load->view ( 'Public/header', $data );
					$this->load->view ( 'Public/navigation', $data );
					$this->load->view ( 'index', $data );
					$this->load->view ( 'Public/keyEnter' );
					$this->load->view ( 'Public/userAuto' );
					$this->load->view ( 'Public/footer' );
				}
			}
		}
	}
	private function _login($array = "") {
		$this->load->library ( 'xmlrpc' );
		$this->xmlrpc->server ( 'http://10.100.1.191/woims/index.php/service.xml', 80 );
		$this->xmlrpc->method ( 'login' );
		
		if ($array == "") 
		{
			$request = array (
					'userName' => $this->input->post ( 'userName' ),
					'userPass' => $this->input->post ( 'userPass' ),
					'aulPrvid' => "1" 
			);
		} 
		else 
		{
			$request = $array;
		}
		
		$this->xmlrpc->request ( array ( xmlRpcEncode ( $request ) ) );
		
		if (! $this->xmlrpc->send_request ()) 
		{
			echo $this->xmlrpc->display_error ();
		} 
		else 
		{
			$string = $this->xmlrpc->display_response ();
			$string = xmlRpcDecode ( $string );
			$this->session->set_userdata ( 'rs', $string ['rs'] );
			if($string ['rs'] == 1){
				$this->session->set_userdata ('userInfo',xmlRpcEncode($request));
			}			
			if (isset ( $string ['userName'] )) {
				$this->session->set_userdata ( 'userName', $string ['userName'] );
			}
			if (isset ( $string ['userNum'] )) {
				$this->session->set_userdata ( 'userNum', $string ['userNum'] );
			}
			return $string;
		}
	}
	public function logout() {
		$this->session->unset_userdata ( 'rs' );
		$this->session->unset_userdata ( 'userName' );
		$this->session->unset_userdata ( 'userInfo' );
		Header("Location:".base_url());
	}
	
// 	public function forget() {
// 		$data ['title'] = '找回密码';
// 		$data ['base'] = $this->base;
// 		$data['css'] = $this->css;
// 		$data ['bootstrap'] = $this->bootstrap;
// 		$data ['bootstrap_responsive'] = $this->bootstrap_responsive;
// 		$data ['js'] = $this->js;
// 		$data ['bootstrap_js'] = $this->bootstrap_js;
// 		$data ['rs'] = 0;
// 		$this->session->unset_userdata ( 'rs' );
		
// 		$this->load->view ( 'public/header', $data );
// 		$this->load->view ( 'public/navigation', $data );
// 		$this->load->view ( 'webmaster/webmaster', $data );
// 		$this->load->view ( 'webmaster/forget', $data );
// 		$this->load->view ( 'public/footer' );
// 	}
	
	private function _remember($action,$remember=''){
		
		
		if($action == 'read'){
			if(isset($_POST ['remember'])){
				$remember = array (
						'userName' => $this->input->post ( 'userName' ),
						'userPass' => $this->input->post ( 'userPass' )
				);
				return $remember;
			}
		}
		if($action == 'write'){
			if($this->session->userdata ('rs') == 1){
				$this->session->set_userdata ('remember',xmlRpcEncode($remember));
			}
			
		}	
		if($action == 'clear'){
			$this->session->sess_destroy();
			$this->session->unset_userdata('remember');
		}
	
	}
}