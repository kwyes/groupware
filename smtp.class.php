<?
class mime
{
	protected $mime_version	= "1.0";	// mime version
	protected $mime_content_type = "text/plain";
	protected $mime_charset	= "ks_c_5601-1987";
	protected $mime_encoding = "base64";
	protected $mime_boundary = null;
	protected $mime_xmailer	= null;

	public function __construct()
	{
	}

	public function set_content_type($content_type = null)
	{
		$this->mime_content_type = $content_type != null ? $content_type : "text/html";
	}

	public function set_charset($charset = null)
	{
		$this->mime_charset = $charset != null ? $charset : "euc_kr";
	}

	public function set_boundary($boundary = null)
	{
		$this->mime_boundary = $boundary ? $boundary : null;
	}

	public function set_encoding($encoding = null)
	{
		$this->mime_encoding = $encoding != null ? $encoding : "base64";
	}

	public function set_xmailer($xmailer = null)
	{
		$this->mime_xmailer = $xmailer != null ? $xmailer : $_SERVER['HTTP_USER_AGENT'];
//		$this->mime_xmailer = $xmailer != null ? $xmailer : $_SERVER['184.70.148.118'];
	}

	public function __destruct()
	{
	}
}

class sendmail extends mime
{
//	protected $smtp_server = null;
	protected $smtp_server = '111.111.111.111';
	protected $smtp_port = 0;
	protected $smtp_timeout = 0;
	protected $smtp_socketopen = 0;
	protected $smtp_status = false;
	protected $smtp_CRLF = "\r\n";
	protected $smtp_attachfile_name	= null;
	protected $smtp_attachfile_size	= 0;
	protected $smtp_attachfile_type	= null;
	protected $smtp_cmd_status	= array();
	protected $mail_from = null;
	protected $mail_to = null;
	protected $mail_subject = null;
	protected $mail_priority = 3;
	protected $mail_notification = 0;
	protected $mail_body = null;

	public function __construct($server = '111.111.111.111', $port = 0, $timeout = 0)
	{
		$this->smtp_server = ($server != null) ? $server : "localhost";
		$this->smtp_port = ($port != 0) ? $port : 25;
		$this->smtp_timeout = ($timeout != 0) ? $timeout : 30;
	}

	public function encoding($str)
	{
		// =?ks_c_5601-1987?B? : 네이버 ks => euc-kr , 구글 ks => utf8
		return "=?ks_c_5601-1987?B?" . base64_encode($str) . "?=";
	}

	private function _mail_env ($to, $from, $subject, $body, $priority = 3, $notification = 0)
	{
		$this->mail_from = $from;
		$this->mail_to = $to;
		$this->mail_subject = $subject;
		$this->mail_body = $body;
		$this->mail_priority = $priority;
		$this->mail_notification = $notification;
	}

	private function _smtp_base_header()
	{
		$this->_smtp_cmd("From: $this->mail_from");
		$this->_smtp_cmd("To: $this->mail_to");
		$this->_smtp_cmd("Subject: $this->mail_subject");
		$this->_smtp_cmd("MIME-Version: $this->mime_version");
		$this->_smtp_cmd("X-Priority: $this->mail_priority");
		$this->_smtp_cmd("X-Mailer: $this->mime_xmailer");
	}

	private function _smtp_base_content()
	{
		$this->_smtp_cmd("Content-Type: $this->mime_content_type; charset=\"$this->mime_charset\"");
		$this->_smtp_cmd("Content-Transfer-Encoding: $this->mime_encoding");
	}


	private function _smtp_multipart_content($attach = false)
	{
		if($attach)
		{
			$this->_smtp_cmd("Content-Type: multipart/mixed; boundary=\"$this->mime_boundary\"");
		} else {
			$this->_smtp_cmd("Content-Type: multipart/alternative; boundary=\"$this->mime_boundary\"");
		}
		$this->_smtp_cmd("$this->smtp_CRLF");
		$this->_smtp_cmd("This is a multi-part message in MIME format.");
	}

	private function _smtp_attachfile_content()
	{
		$this->_smtp_cmd("Content-Type: $this->smtp_attachfile_type");
		$this->_smtp_cmd("Content-Transfer-Encoding: $this->mime_encoding");
		$this->_smtp_cmd("Content-Dis: attachment; filename=\"$this->smtp_attachfile_name\"");
	}

	private function _smtp_start_boundary()
	{
		$this->_smtp_cmd("$this->smtp_CRLF");
		$this->_smtp_cmd("--$this->mime_boundary");
	}

	private function _smtp_end_boundary()
	{
		$this->_smtp_cmd("--$this->mime_boundary--");
	}

	public function open()
	{
		$this->smtp_socketopen = @fsockopen($this->smtp_server, $this->smtp_port, $errno, $errstr, $this->smtp_timeout);
		if($this->_smtp_code($this->_receive()) != 220)
		{
			throw new Exception("메일서버에 접속할 수 없습니다.");
		}
		else
		{
			$this->smtp_status = true;
		}

		return $this->smtp_status;
	}

	private function _smtp_helo()
	{
		$this->_smtp_cmd("HELO $this->smtp_server");
		if($this->_smtp_code($this->_receive()) == 250)
		{
			return true;
		}
		return false;
	}

	private function _smtp_mailfrom($from)
	{
		$this->_smtp_cmd("MAIL FROM: $this->mail_from");
		if($this->_smtp_code($this->_receive()) == 250)
		{
			return true;
		}
		return false;
	}

	private function _smtp_rcptto($to)
	{
		$this->_smtp_cmd("RCPT TO: $this->mail_to");
		if($this->_smtp_code($this->_receive()) == 250)
		{
			return true;
		}
		return false;
	}

	private function _smtp_data()
	{
		$this->_smtp_cmd("DATA");
		if($this->_smtp_code($this->_receive()) == 354)
		{
			return true;
		}
		return false;
	}

	private function _smtp_end()
	{
		$this->_smtp_cmd(".");
		if($this->_smtp_code($this->_receive()) == 250)
		{
			return true;
		}
		return false;
	}

	private function _smtp_quit()
	{
		$this->_smtp_cmd("QUIT");
		if($this->_smtp_code($this->_receive()) == 221)
		{
			return true;
		}
		return false;
	}

	public function close()
	{
		if(@fclose($this->smtp_socketopen) == false)
		{
			throw new Exception("해당 메일서버에 접속되어있지 않습니다.");
		}
		return true;
	}

	private function _smtp_send($files = null)
	{
		$this->mime_boundary = "======" . uniqid(rand()) . "/$_SERVER[SERVER_NAME]";

		$mail_body = nl2br(stripslashes($this->mail_body));

		if($this->mime_encoding == "base64")
		{
			$mail_body = chunk_split(base64_encode($mail_body));
		}

		$this->smtp_cmd_status[] = $this->_smtp_helo();
		$this->smtp_cmd_status[] = $this->_smtp_mailfrom($this->mail_from);
		$this->smtp_cmd_status[] = $this->_smtp_rcptto($this->mail_to);
		$this->smtp_cmd_status[] = $this->_smtp_data();

		$this->_smtp_base_header();

		if(is_array($files))
		{
			$this->_smtp_multipart_content(true);
			$this->_smtp_start_boundary();
			$this->_smtp_base_content();
			$this->_smtp_cmd("$this->smtp_CRLF");
			$this->_smtp_cmd($mail_body);
			$this->_smtp_cmd("$this->smtp_CRLF");

			for($i = 0; $i < sizeof($files); $i++)
			{
				if(is_uploaded_file($files[tmp_name][$i]))
				{
					$this->smtp_attachfile_name = $this->_check_empty($files[name][$i]);
					$this->smtp_attachfile_size = $files[size][$i];
					$this->smtp_attachfile_type = $files[type][$i];

					$attachfile_handle = @fopen($files[tmp_name][$i], "r");
					$attachfile_body = @fread($attachfile_handle, $files[size][$i]);

					if($this->mime_encoding == "base64")
					{
						$attachfile_body = chunk_split(base64_encode($attachfile_body));
					}

					$this->_smtp_start_boundary();
					$this->_smtp_attachfile_content();

					$this->_smtp_cmd("$this->smtp_CRLF");
					$this->_smtp_cmd($attachfile_body);
					$this->_smtp_cmd("$this->smtp_CRLF");

					@fclose($attachfile_handle);
					}
			}
		} else {
			$this->_smtp_multipart_content(false);

			$this->mime_content_type	= "text/plain";

			$this->_smtp_start_boundary();
			$this->_smtp_base_content();
			$this->_smtp_cmd("$this->smtp_CRLF");
			$this->_smtp_cmd($mail_body);
			$this->_smtp_cmd("$this->smtp_CRLF");

			$this->mime_content_type	= "text/html";

			$this->_smtp_start_boundary();
			$this->_smtp_base_content();
			$this->_smtp_cmd("$this->smtp_CRLF");
			$this->_smtp_cmd($mail_body);
			$this->_smtp_cmd("$this->smtp_CRLF");
		}

		$this->_smtp_end_boundary();

		$this->smtp_cmd_status[] = $this->_smtp_end();
		$this->smtp_cmd_status[] = $this->_smtp_quit();

		foreach($this->smtp_cmd_status as $key => $value)
		{
			if($value == false)
			{
				return false;
				break;
			}
		}
		return true;
	}

	private function _smtp_cmd($str)
	{
		@fputs($this->smtp_socketopen, $str . $this->smtp_CRLF);
	}

	private function _smtp_code($str)
	{
		return substr($str, 0, 3);
	}

	private function _receive()
	{
		return @fgets($this->smtp_socketopen, 1024);
	}

	private function _check_empty($filename)
	{
		return preg_replace("/ /i", "_", $filename);
	}

	public function send($to, $from, $subject, $text, $priority = 3, $files = null)
	{
		$this->set_xmailer($_SERVER[HTTP_USER_AGENT]);
//		$this->set_xmailer($_SERVER['111.111.111.111']);
		$this->set_content_type("text/html");
		$this->set_charset("euc_kr");
		$this->set_encoding("base64");

		// 기본 중요도는 3(Normal) 으로 지정
		$this->_mail_env($to, $from, $this->encoding($subject), $text, $priority);

		if($this->_smtp_send($files) == false)
		{
//			throw new Exception("메일을 보내는 중 오류가 발생하였습니다.");
		}
	}

	public function __destruct()
	{
	}
};
?>
