<?php
//    session_save_path("../session_temp");
    session_start();
    define("SYSTEM_PATH","..");
    define("ABSOLUTE_PATH", "http://group.t-brothers.com/");
    
    //TIMEZONE
    putenv("TZ=America/Vancouver");
    $today = date("Y-m-d H:i:s");
    
    //PARAMETERS
   
    $mode = $_POST['mode'];
    
//    setcookie("LANGUAGE", $LANGUAGE, time() + 60*60*24*30*12, "/", "192.168.2.62");
    
	include_once "db_configms.php";
    include_once "common_class.php";

//    include_once SYSTEM_PATH."/includes/Class_Tables.php";
    
//    $BoardList = new BoardList();			//�Խ��� 
//    $BoardData = new BoardData();		//�Խ��� ����
//    $SI = new SiteInformation();				//����Ʈ ����
//    $Member = new Member();					//ȸ��
//    $IMG = new Images();							//Images ��ǰ
    
    //check member
//    $Member->ReadMember($_SESSION['memberId']);
               
    //site information
//    $SI->ReadSiteInformation($siId);
    
    $cURL = $_SERVER['REQUEST_URI'];
    $cURL = base64_encode($cURL);


?>

<script language="JavaScript">
    /// <summary>
	/// �ش� Char�� �ѱ� ������ �ִ��� Ȯ�� �մϴ�.
	/// </summary>
	/// <param name="c"></param>
	/// <returns>�ѱ���������</returns>
	public bool isHangle(char c)
	{
		bool ret = false;

		if (c >= '\xAC00' && c <= '\xD7AF')
		{
			ret = true;
		}
		else if (c >= '\x3130' && c <= '\x318F')
		{
			ret = true;
		}

		return ret;
	}

	/// <summary>
	/// ���ڿ��� �ѱ��� ���ԵǾ� �ִ��� ���θ� ��ȯ�մϴ�.
	/// </summary>
	/// <param name="c"></param>
	/// <returns></returns>
	public bool isHangle(string c)
	{
		bool ret = false;
		for (int i = 0; i < c.Length; i++)
		{
			char cur = c[i];
			if (isHangle(cur))
			{
				ret = true;
				break;
			}
		}

		return ret;

	}
</script>

