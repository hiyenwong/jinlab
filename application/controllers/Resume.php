<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class resume extends CI_Controller {
	// 系统地址
	var $baseUrl = 'http://resumeqanew.51job.com';
	
	// 登录地址1
	var $logUrl = '/system/AdmLogin.aspx';
	
	// 注销地址
	var $outUrl = '/system/AdmSignout.aspx';
	
	// ID搜索
	var $idSearch = '/Resume/SchDft.aspx';
	
	// 简历搜索
	var $resumeSearch = '/Resume/SchResume.aspx';
	
	// 历史标签
	var $historyMark = '/Resume/SchBucket.aspx';
	
	// 加标签
	var $mark = '/Resume/ActBucket.aspx';
	
	// 加评语
	var $remark = '/Resume/ActComment.aspx';
	
	// 恶意投递
	var $badAction = '/RsmCheck/BadActionResume.aspx';
	
	// 恶意关键字
	var $badWord = '/RsmCheck/BadWordResume.aspx';
	
	// Cookie文件地址
	var $cookieJar = '';
	
	// 代理
	var $proxy = "http://10.100.10.100:3128";
	
	public function __construct() {
		parent::__construct ();
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library ('session');
		$this->load->model('ResumeModel');
		$this->Path = realpath(dirname(dirname(dirname(__FILE__))));
		require_once $this->Path.'/Config/Config.php';
		$this->data = $data;
		$this->message = $message;
		$baseUrl = $this->baseUrl;
		$this->logUrl    = $baseUrl.$this->logUrl;
		$this->outUrl    = $baseUrl.$this->outUrl;
		$this->badAction = $baseUrl.$this->badAction;
		$this->badWord   = $baseUrl.$this->badWord;
		$this->idSearch = $baseUrl.$this->idSearch;
		$this->resumeSearch = $baseUrl.$this->resumeSearch;
		$this->historyMark = $baseUrl.$this->historyMark;
		$this->mark = $baseUrl.$this->mark;
		$this->remark = $baseUrl.$this->remark;
		$this->userNum = $this->session->userdata('userNum');
		$this->ip = $_SERVER['REMOTE_ADDR'];
		$this->ipForbidden = $ipForbidden;
		$this->ipCheck = false;				
		if ( (bool)stripos('请求IP:'.$this->ip,$this->ipForbidden))
			$this->ipCheck = true;		
		if ( !file_exists($this->Path.'/Download/'.$this->userNum))
		{ 
			mkdir ($this->Path.'/Download/'.$this->userNum);
		}
		$this->savePath = $this->Path.'/Download/'.$this->userNum.'/';
		$this->downloadPath = 'Download/'.$this->userNum.'/';
		$this->cookieJar = $this->Path.'/Cookie/'.$this->userNum.'-resume.cookie';	
		$this->zipName = 'Resume.zip';
	}	
	public function index(){
		$data = $this->data;
		$message = $this->message;
		$data ['title'] = '客服屏蔽简历';				
		$data ['userName'] = $this->session->userdata('userName');
		$data ['rs'] = $this->session->userdata ('rs');
		$data ['ipCheck'] = $this->ipCheck;
		$data ['form'] = '1';
		
		if ( $data['rs'] == 1)
		{
			if ( $this->input->post('login') == '登陆')
			{
				$this->_logout();
				$login = $this->_login();
				$data ['message'] = str_replace('Resume QA系统','<a href="http://resumeqanew.51job.com" target="_blank">Resume QA系统</a>！', $message[($login[0])]);
				$data ['message'] = str_replace('修改密码','<a href="http://resumeqanew.51job.com/system/alterpassword.aspx?name='.$login[1].'" target="_blank">修改密码</a>', $data ['message']);
				if ( $login[0] == 1)
					$data['form'] = '0';				
			}
			if ( $this->input->post('lock') == '邮件内容识别')
			{
				$data['message'] = str_replace('Resume QA系统','<a href="http://resumeqanew.51job.com" target="_blank">Resume QA系统</a>', $message[1]);
				$data['table'] = $this->_table();
				$data['form'] = '2';
			}
			$data ['quickLogin'] = $this->_quickLogin();
			$this->load->view('Public/header',$data );
			$this->load->view('Public/navigation',$data );
			$this->load->view('Resume/resume',$data);
			$this->load->view('Public/footer');
		}
		else{
			Header("Location:".base_url()."labs/?login=0");
		}
		
	}
	public function phpcurl()
	{		
		$data ['rs'] = $this->session->userdata ('rs');
		if ( $data ['rs'] == 1)
		{
			$action = $this->input->post('action');
			$data['title'] = $this->input->post('title');
			if ( $action == 'searchUserid')
			{
				$userid = $this->input->post('id');
				$data['html'] = $this->_useridSearch($userid,true);
			}
			if ( $action == 'mark')
			{
				$id = $this->input->post('id');
				$reason = $this->input->post('reason');
				if ($this->_mark($id))
				{
					if ($this->_remark($id,$reason))
					{
						$data['html'] = '加标签&加评语成功！';
					}
					else 
					{
					$data['html'] = '加评语失败！';
					}
				}
				else 
				{
					$data['html'] = '加标签失败！';
				}
				
			}
			if ( $action == 'searchResume')
			{
				$reason = $this->input->post('reason');
				$resumeid = $this->input->post('id');
				$language = $this->input->post('l');
				$data ['jquery'] = true;
				$data ['jsfile'] = true;
				$data ['js']  = "<script type='text/javascript'>\n";
				$data ['js'] .= "(function() {\n";
				$data ['js'] .= "length = $($('#userid').prev()).find('tr').length;\n";
				$data ['js'] .= "$($('#userid').prev()).find('tr')[length-3].remove();\n";
				$data ['js'] .= "$('#Label_NoOtherData').parent().parent().next().remove();\n";
				$data ['js'] .= "$('#Label_NoOtherData').parent().parent().parent().parent().css('width','80%');\n";
				$data ['js'] .= "$('#Label_NoOtherData').parent().parent().parent().parent().css('margin','0 10%');\n";
				$data ['js'] .= "})();\n";
				$data ['js'] .= "</script>";
				$data ['tool']  = "<form style='width:80%;margin:0 10%;'>";
				$data ['tool'] .= "<input type='button' style='height:60px;width:10%;float:left' value='锁定+评语' onclick='mark(".$resumeid.")'/>";
				$data ['tool'] .= "<textarea name='reason' id='reason' style='height:60px;resize:none;width:90%;float:right'>".$reason."</textarea>";
				$data ['tool'] .= "</form>";
				$data ['tool'] .= "<div style='clear:both'></div>";				
				$data ['html'] = $this->_resumeSearch($resumeid,'all',$language);
			}	
			if ( $action == 'save')
			{
				$id = $this->input->post('id');	
				$this->_delete();
				if ( $this->_save($id) != false)
				{
					$data ['html'] = '下载成功！';
				}						
			}		
			if ( $action == 'download')
			{
				$id = $this->input->post('id');
				if ( (bool)stripos($id,','))			
					$id = explode(',',$id);
				$data ['html'] = $this->_download($id);								
			}
			if ( $action == 'auto')
			{
				$idArr = $this->input->post('idArr');
				$idArr = explode(',',$idArr);
				$this->_delete();
				$this->_save($idArr);
			}
			$this->load->view('resume/phpCurl',$data);
		}
		else
		{
			Header("Location:".base_url()."labs/?login=0");
		}
	}	
	private function _quickLogin()
	{
		$array = $this->ResumeModel->read();
		$html = '';
		foreach ( $array as $arr)
		{
			$html .= '<a class="btn btn-large" style="margin:5px" href="javascript:void(0)" onclick="resumeLogin(\''.$arr->userName.'\',\''.$arr->passWord.'\')">'.$arr->userName.'</a>';
		}
		return $html;
	}
	private function _delete()
	{
		$file_dir = $this->savePath;
		$filesnames = scandir($file_dir);
// 		var_dump($filesnames);
		foreach ( $filesnames as $arr)
		{
			if ( $arr != '.' && $arr != '..')
				unlink($this->savePath.$arr);
		}
	}
	private function _download($id)
	{
		$file_dir = $this->downloadPath;		
		if ( is_array($id))
		{
			if ( count($id) == 1)
			{
				$this->_download($id[0]);			
			}
			else 
			{
				$zipFile = new ZipArchive;
				$zipName = $this->zipName;
				$zip = $zipFile->open($file_dir.$zipName,ZIPARCHIVE::OVERWRITE);
				foreach ( $id as $arr)
				{
					$file_name = $arr.'.htm';
					if ( $zip === true)
					{
						$zipFile->addFile($file_dir.$file_name,basename($file_dir.$file_name));						
					}					
				}
				$zipFile->close();
				if ( !file_exists($file_dir.$zipName))
				{
					$this->_save($id);
					$this->_download($id);
					return;
				}
				else 
				{
					Header("Location:".base_url().$file_dir.$zipName);
				}
			}
		}
		else 
		{
			$file_name = $id.'.htm';
			if ( !file_exists($file_dir.$file_name))
			{
				$this->_save($id);
				$this->_download($id);
				return;
			}
			$file = fopen($file_dir.$file_name,"r"); // 打开文件
			// 输入文件标签
			Header("Content-type: application/octet-stream");
			Header("Accept-Ranges: bytes");
			Header("Accept-Length: ".filesize($file_dir . $file_name));
			Header("Content-Disposition: attachment; filename=" . $file_name);
			// 输出文件内容
			$result = fread($file,filesize($file_dir . $file_name));
			fclose($file);	
			return $result;
		}		
	}
	private function _save($id)
	{
		$file_dir = $this->savePath;		
		if ( is_array($id))
		{
			foreach ( $id as $arr)
			{				
				$this->_save($arr);
			}
		}
		else 
		{
			$href = $this->resumeSearch.'?ret_flag=1&err_info=&userid=&id='.$id.'&l=C&S=c&key=&m=f';
			$html = $this->_curl($href, '', true);
			$post = $this->_getFormVal($html, 'form1');
			$post['topDownLoad'] 		= '下载';
			$post['__EVENTVALIDATION']  = $post['keyword'];
			$post['keyword'] 			= '';
			$postStr = http_build_query($post);
			$html = $this->_curl($href, $postStr, true);
			$html = preg_replace('/CENTER/', 'center', $html);
			$html = preg_replace('/\.\./', 'http://resumeqanew.51job.com', $html);
			return file_put_contents($file_dir.$id.".htm",$html);
		}		
	}
	private function _resumeSearch($resumeid,$act = 'all',$language = 'C')
	{
		if ( $act == 'all')
		{
			$html  = $this->_resumeSearch($resumeid,$act = 'mark',$language);
			$html .= $this->_resumeSearch($resumeid,$act = 'resume',$language);			
			return $html;
		}
		if ( $act == 'resume')
		{
			$href = $this->resumeSearch.'?ret_flag=1&err_info=&userid=&id='.$resumeid.'&l='.$language.'&S=c&key=&m=f';
			$html = $this->_curl($href, '', true);	
			preg_match('/此简历不存在！/',$html,$match);
			if ( !empty($match))
				return $html;			
			$post = $this->_getFormVal($html, 'form1');
			$post['topDownLoad'] 		= '下载';
			$post['__EVENTVALIDATION']  = $post['keyword'];
			$post['keyword'] 			= '';
			$postStr = http_build_query($post);		
			$html = $this->_curl($href, $postStr, true);
			$html = str_replace('../Attach', $this->baseUrl.'/Attach', $html);
			$html = str_replace('../App_Themes', $this->baseUrl.'/App_Themes', $html);
			$html = str_replace('SchResume.aspx?id', 'phpCurl?action=searchResume&resumeid', $html);
			$html = str_replace('Schresume.aspx?id', 'phpCurl?action=searchResume&resumeid', $html);
			$html = str_replace('../im/space.gif', '', $html);
			$html = str_replace('../img/dot.gif', $this->baseUrl.'/img/dot.gif', $html);
			return $html;	
		}			
		if ( $act == 'mark')
		{
			$href = $this->historyMark.'?id='.$resumeid;
			$html = $this->_curl($href, '', true);
			$html = str_replace('../App_Themes', $this->baseUrl.'/App_Themes', $html);
			$html = str_replace('~/im/space.gif', '', $html);
			$html = str_replace('~/img/space.gif', '', $html);
			return $html;
		}
	}
	private function _useridSearch($userid,$act = '')
	{
		$html = $this->_curl($this->idSearch, '', TRUE);
		$post = $this->_getFormVal($html, 'aspnetForm');
		$post['ctl00$myContentPlaceHolder$idtype'] = '0';
		$post['ctl00$myContentPlaceHolder$userid'] = $userid;
		$post['ctl00$myContentPlaceHolder$searchuserid'] = '搜索';
		$postStr = http_build_query($post);
		$html = $this->_curl($this->idSearch, $postStr, TRUE);
		if ( $act)
		{
			return $html;
		}
		preg_match_all('/SchResume\.aspx\?id=(.*?)&amp;l=C/', $html,$match);
		return $match[1];
	}
	private function _mark($id)
	{
// 		$id = $this->input->post('id');
// 		$id = '313144839';
		$html = $this->_curl($this->mark.'?id='.$id, '', TRUE);
// 		echo $html;
		$post = $this->_getFormVal($html, 'form1');
		$post['DropDownList1'] = '581';
		$post['__EVENTTARGET'] = 'DropDownList1';
		$post['__EVENTARGUMENT'] = '';
		$post['__LASTFOCUS'] = '';
		$postStr = http_build_query($post);
		$html = $this->_curl($this->mark.'?id='.$id, $postStr, TRUE);
		$post = $this->_getFormVal($html, 'form1');
		$post['DropDownList1'] = '581';
		$post['DropDownList2'] = '582';
		$post['btnSubmit'] = '添加';
		$post['__EVENTTARGET'] = '';
		$post['__EVENTARGUMENT'] = '';
		$post['__LASTFOCUS'] = '';
		$postStr = http_build_query($post);
		$html = $this->_curl($this->mark.'?id='.$id, $postStr, TRUE);
// 		var_dump($post);
// 		echo $html;
		preg_match('/添加标签成功！/',$html,$match);		
		if ( !empty($match))
			return true;
		return false;
	}
	private function _remark($id,$reason)
	{
// 		$id = $this->input->post('id');
// 		$id = '313144839';
		$html = $this->_curl($this->remark.'?id='.$id, '', TRUE);
// 		echo $html;
		$post = $this->_getFormVal($html, 'form1');
		$post['RsmComments'] = $reason;
		$post['Button1'] = ' 添 加 ';
		$post['__EVENTTARGET'] = '';
		$post['__EVENTARGUMENT'] = '';
		$postStr = http_build_query($post);
		$html = $this->_curl($this->remark.'?id='.$id, $postStr, TRUE);
// 		var_dump($post);
// 		echo $html;
		preg_match('/提交OK!/',$html,$match);
		if ( !empty($match))
			return true;
		return false;
	}
	private function _mail()
	{
		$str = $this->input->post('mail');
// 		$str = preg_replace('/\r|\t|\n*/', ' ', $str);
		$str = preg_replace('/、|,|，|\/|\\\\|\s+/', ' ', $str);
		$array = explode(' ',$str);		
		//排除错误识别为ID的符号
		$error = array('、','\\','】',']','‘','\'',' ','　','/');
		foreach ( $array as $key=>$arr)
		{
			if ( $arr == '' || in_array($arr,$error) )
			{
				unset($array[$key]);
			}
		}
// 		var_dump($array);
		$result = array();
		$result['reason'] = '无';
		$reason = '';
		foreach ( $array as $arr)
		{			
			preg_match('/申请屏蔽简历/', $arr,$match);
			preg_match('/客户/', $arr,$match_n);
			if ( !(empty($match) && !empty($match_n)))
			{		
				$reason .= $arr;				
				if ( !empty($match))
				{
// 					preg_match('/(.*?申请屏蔽简历)/', $arr,$match);
// 					$result['reason'] = $match[0]."。";
					$reason .= '。';
					$result['reason'] = $reason;
				}
				else 
				{
					$reason .= '，';
				}
				preg_match('/用户号|用户ID/', $arr,$match_u);
				preg_match('/简历ID|简历号/', $arr,$match_r);			
				if ( !empty($match_u))
				{
					$result['userid'][0] = '用户ID：';		
					$result['userRepeat'][0] = '重复用户ID';						
				}
				if ( !empty($result['userid']) && empty($result['resumeid']) && empty($match_r))
				{
					$arr = preg_replace('/用户号|用户|用户ID|：|；|:|;/', '', $arr);
					if ( in_array($arr,$result['userid']))
					{
						array_push($result['userRepeat'],$arr);;
					}
					else
					{
						array_push($result['userid'],$arr);
					}	
					$result['userid'] = array_filter($result['userid']);
				}
				if ( !empty($match_r))
				{
					$result['resumeid'][0] = '简历ID：';
					$result['resumeRepeat'][0] = '重复简历ID';				
				}
				if ( !empty($result['resumeid']) && empty($match_r))
				{
					if ( in_array($arr,$result['resumeid']))
					{
						array_push($result['resumeRepeat'],$arr);;
					}
					else
					{
						array_push($result['resumeid'],$arr);
					}
				}
			}
		}
		
		return $result;
	}
	private function _table(){
		$mail = $this->_mail();
		$this->reason = $mail['reason'];
		$table  = "<table class='table table-bordered'>\n<tr>\n";
		$table .= "<td colspan=16>屏蔽原因：</td>\n";
		$table .= "</tr>\n<tr>\n";
		$table .= "<td colspan=16 id='reason'>".$mail['reason']."</td>\n";
		$table .= "</tr>\n<tr>\n";
		$table .= "<td colspan=1><input type='checkbox' checked='checked' onclick='checkAll(this)'/></td>\n";
		$table .= "<td colspan=3>用户ID</td>\n";
		$table .= "<td colspan=1 style='width:20px'></td>\n";
		$table .= "<td colspan=3>简历ID</td>\n";
		$table .= "<td colspan=1 style='width:20px'></td>\n";
		$table .= "<td colspan=2>是否能打开</td>\n";
		$table .= "<td colspan=2>是否已屏蔽</td>\n";
		$table .= "<td colspan=2>是否有评语</td>\n";
		$table .= "<td colspan=1>保存</td>\n";
		$table .= "</tr>\n";
		if ( !empty($mail['userid']))
		{
			foreach ( $mail['userid'] as $arr)
			{
				$i = '';
				if ( $arr != '用户ID：')
					$resumeidArr = $this->_useridSearch($arr);
				if ( in_array($arr, $mail['userRepeat']))
				{
					$i = 1;
					foreach ( $mail['userRepeat'] as $ar)
					{
						if ( $arr == $ar)
						{
							$i += 1;
						}
					}
				}
				if (!empty($resumeidArr))
				{
					$k = 1;
					foreach ( $resumeidArr as $array)
					{
						if ( $k == 1)
						{
							$class = '';
							$user = $arr;
						}							
						else 
						{
							$class = ' con';
							$user = '';
						}							
		$table .= $this->_tableTR($user,$array,$i,$class);
						$k++;
					}
				}
				else 
				{
		$table .= $this->_tableTR($arr,'该用户不存在！',$i);
				}
			}
		}
		if ( !empty($mail['resumeid']))
		{
			foreach ( $mail['resumeid'] as $arr)
			{
				$j = '';
				if ( in_array($arr, $mail['resumeRepeat']))
				{
					$j = 1;
					foreach ( $mail['resumeRepeat'] as $ar)
					{
						if ( $arr == $ar)
						{
							$j += 1;
						}
					}
				}
		$table .= $this->_tableTR('',$arr,$j);				
			}
		}
		$table .= "</table>\n";
		return $table;
	}
	private function _tableTR($userid='',$resumeid='',$repeat='',$class='')
	{
		if ( $userid == '用户ID：' || $resumeid == '简历ID：')
			return;		
		$tr  = "<tr id=''>\n";
		$tr .= "<td colspan=1><input class='checkAll' name='resumeCheck' resumeid='".$resumeid."' checked='checked' type='checkbox'/></td>\n";
		
		if ($userid == '')
		{
		$tr .= "<td colspan=3 class='".$class."'></td>\n";
		$tr .= "<td colspan=1></td>\n";
		}
		else
		{
			$args = json_encode(array(
					array('title','用户ID：'.$userid),
					array('action','searchUserid'),
					array('id',$userid)
			));
// 		$tr .= "<td colspan=3><a id='userid_".$userid."' class='userid' href='".base_url()."resume/phpCurl?action=searchUserid&userid=".$userid."' target='_blank'>".$userid."</a></td>\n";
		$tr .= "<td colspan=3 class='".$class."'><a id='userid_".$userid."' class='userid' href='javascript:void(0)' onclick='openPostWindow(\"".base_url()."resume/phpCurl\",".$args.",".$userid.")'>".$userid."</a></td>\n";
			if ( $repeat != '')
		$tr .= "<td colspan=1 style='color:red'>(".$repeat.")</td>\n";
			else 
		$tr .= "<td colspan=1></td>\n";
		}
		
		if ($resumeid == '')
		{
		$tr .= "<td colspan=3></td>\n";
		$tr .= "<td colspan=1></td>\n";			
		}
		else
		{
			if ( $resumeid == '该用户不存在！')
		$tr .= "<td colspan=3 style='color:red'>该用户不存在！</td>\n";
			else
			{
				$args = json_encode(array(
						array('title','简历ID：'.$resumeid),
						array('action','searchResume'),
						array('id',$resumeid),
						array('reason',$this->reason)
				));				
// 		$tr .= "<td colspan=3><a href='".base_url()."resume/phpCurl?action=searchResume&resumeid=".$resumeid."' target='_blank'>".$resumeid."</a></td>\n";
		$tr .= "<td colspan=3><a id='resumeid_".$resumeid."' href='javascript:void(0)' onclick='openPostWindow(\"".base_url()."resume/phpCurl\",".$args.",".$resumeid.")'>".$resumeid."</a></td>\n";
			}
			if ( $repeat != '' && $userid == '' && $class == '')
		$tr .= "<td colspan=1 style='color:red'>(".$repeat.")</td>\n";
			else 
		$tr .= "<td colspan=1></td>\n";
		
		$result = $this->_tableCheck($resumeid);
		
		$tr .= "<td colspan=2><img style='width:20px' op='".$result['open']."' src='".base_url()."application/views/public/image/".$result['open'].".gif'/></td>\n";
		$tr .= "<td colspan=2><img style='width:20px' src='".base_url()."application/views/public/image/".$result['mark'].".gif'/></td>\n";
		$tr .= "<td colspan=2><img style='width:20px' src='".base_url()."application/views/public/image/".$result['remark'].".gif'/></td>\n";
		$tr .= "<td colspan=1><img style='cursor:pointer;width:20px' onclick='download(\"".$resumeid."\",this)' src='".base_url()."application/views/public/image/save.png'/></td>\n";
		$tr .= "</tr>\n";
		}
		return $tr;
	}
	private function _tableCheck($resumeid)
	{
		$result = array(
						'open'		=>	'wrong',
						'mark'		=>	'wrong',
						'remark'	=>	'wrong',
					);
		if ( $resumeid == '该用户不存在！')
			return $result;
		$html = $this->_resumeSearch($resumeid);
		preg_match('/该用户的其他简历/',$html,$match);
		if ( !empty($match))
			$result['open'] = 'right';
		else 
			return $result; 
		preg_match('/<span id="DataList1_ctl00_Label3">(.*?)<\/span>/', $html,$match);		
		if ( !empty($match) && ($match[1] == '垃圾简历' || $match[1] =='伪造简历'))
			$result['mark'] = 'right';
		preg_match('/该简历暂时还没有评语/',$html,$match);
		if ( empty($match))
			$result['remark'] = 'right';
		return $result;
	}
	/**
	 * 登录系统
	 * 	 
	 * @return string
	 */
	private function _login()
  	{
  		$message = $this->message;
  		$param = array(
  				'username'	=> $this->input->post('username'),
  				'password'	=> $this->input->post('password')
  		);
	    $HTML = $this->_curl($this->logUrl);
	  	$pData = $this->_getFormVal($HTML, 'aspnetForm');
	  	$pData['ctl00$myContentPlaceHolder$Submit']   = "登  录";
	    $pData['ctl00$myContentPlaceHolder$UserName'] = $param['username'];
	    $pData['ctl00$myContentPlaceHolder$PWD']      = $param['password'];
	    $postStr = http_build_query($pData);
	    $HTML = $this->_curl($this->logUrl, $postStr);
//      echo $HTML;
// 		判断是否登录成功
	    $online = (bool)stripos($HTML, $message[0]) !== FALSE;
	    $success = (bool)stripos($HTML, $message[1]) !== FALSE;
	    $error = (bool)stripos($HTML, $message[2]) !== FALSE;
	    $over = (bool)stripos($HTML, $message[3]) !== FALSE;
	    $result = array($online,$success,$error,$over);
	    
	  	foreach ( $result as $key=>$arr)
	    {
	    	if ( $arr)
	    	{
	    		if ( $key == 1)
	    			$this->ResumeModel->save($param['username'],$param['password']);
	    		return array($key,$param['username']);
	    	}
	    }
  	}
	/**
	 * 注销系统
	 *
	 */
	private function _logout()
	{
		$this->_curl($this->outUrl, '', TRUE);
	}
	/**
	 * 提取网页中的HIDDEN属性和值
	 *
	 * @param string $html HTML
	 * @param string $name Form Name
	 * @return array
	 */
	private function _getFormVal($html, $name)
	{
		// 如果限定表单
		if ( $name) {
			preg_match('/<form name="'.$name.'".*?>(.*?)<\/form>/si', $html, $match);
			$html = trim($match[1]);
		}
		// 提取HIDDEN内容
		preg_match_all('/<input type="hidden" name="(.*?)".*?value="(.*?)".*?\/>/is', $html, $match);
		// 组合
		foreach ($match[1] as $key=>$val)
			$hideArr[trim($val)] = trim($match[2][$key]);
		// Return
		return $hideArr;
	}
	private function _curl($url, $post = false, $cookie = false ,$proxy = false)
  	{
	    $ch = curl_init ( $url );
	    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, TRUE);
	    curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, TRUE);
	    curl_setopt ( $ch, CURLOPT_TIMEOUT, 3600);
	    if ( $post){
	    	curl_setopt ( $ch, CURLOPT_POST, true);
	    	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post);
	    }
	    if($proxy){
	    	curl_setopt ($ch, CURLOPT_PROXY, $this->proxy);
	    }
	    if ( $cookie) // 传入Cookie
	    	curl_setopt ( $ch, CURLOPT_COOKIEFILE, $this->cookieJar );
	    curl_setopt ( $ch, CURLOPT_COOKIEJAR, $this->cookieJar );
	    $html = curl_exec ( $ch );
	    curl_close($ch);
	    // Return
	    return $html;
  	}	
}