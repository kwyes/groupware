<?php

function db_selectQuery($query)
{
    global $conn;
    if($query)
    {
        mssql_select_db( "htmDB", $conn );
        @mssql_query($LANGUAGE_SET);

		$this_result = mssql_query($query, $conn);
    }
    return $this_result;
}

function db_selectQuery_dbgal($query)
{
    global $conn_dbgal;
    if($query)
    {
        mssql_select_db( "dbgal", $conn_dbgal );
        @mssql_query($LANGUAGE_SET);

        $this_result = mssql_query($query, $conn_dbgal);
    }
    return $this_result;
}

function db_selectQuery_db1gal($query)
{
    global $conn_dbgal;
    if($query)
    {
        mssql_select_db( "db1gal", $conn_dbgal );
        @mssql_query($LANGUAGE_SET);

        $this_result = mssql_query($query, $conn_dbgal);
    }
    return $this_result;
}

function db_selectQuery_dbgalsry($query)
{
    global $conn_dbgal_sry;
    if($query)
    {
        mssql_select_db( "dbgal", $conn_dbgal_sry );
        @mssql_query($LANGUAGE_SET);

        $this_result = mssql_query($query, $conn_dbgal_sry);
    }
    return $this_result;
}

function db_selectQuery_db1galsry($query)
{
    global $conn_dbgal_sry;
    if($query)
    {
        mssql_select_db( "db1gal", $conn_dbgal_sry );
        @mssql_query($LANGUAGE_SET);

        $this_result = mssql_query($query, $conn_dbgal_sry);
    }
    return $this_result;
}

function db_selectQuery_dbhannam($query)
{
    global $conn_dbgal;
    if($query)
    {
        mssql_select_db( "dbhannam", $conn_dbgal );
        @mssql_query($LANGUAGE_SET);

        $this_result = mssql_query($query, $conn_dbgal);
    }
    return $this_result;
}

function db_num_rowsQuery($query)
{
    if($query)
    {
        $this_result = mssql_num_rows($query);
    }
    return $this_result;
}

function db_fatch_arryQuery($query)
{
    $this_result = @mssql_fetch_array($query);
    return $this_result;
}

function get_Dept($str_Dept)
{
	$str_Comp = $_SESSION['memberCID'];
	if($str_Dept)
    {
		$query = "SELECT deptName ".
						"FROM Department WHERE companyID =$str_Comp  AND deptID=$str_Dept";
		$query_result = mssql_query($query);
		$row = mssql_fetch_array($query_result);

		$string = $row['deptName'];

		if($string) {
			if( preg_match("/[\xA1-\xFE][\xA1-\xFE]/", $$string) == 1) {
		        $string = iconv('euc-kr', 'utf-8', $string);
			}
		    return $string;
		} else {
	        return "";
		}
    }
    else
    {
        return "";
    }
}

function get_coop_Dept($str_Comp, $str_Dept)
{
	if($str_Comp)
    {
		$query = "SELECT deptName ".
						"FROM Department WHERE companyID=$str_Comp AND deptID=$str_Dept";
		$query_result = mssql_query($query);
		$row = mssql_fetch_array($query_result);

		$string = $row['deptName'];

		if($string) {
			if( preg_match("/[\xA1-\xFE][\xA1-\xFE]/", $$string) == 1) {
		        $string = iconv('euc-kr', 'utf-8', $string);
			}
		    return $string;
		} else {
	        return "";
		}
    }
    else
    {
        return "";
    }
}

function get_duty($str)
{
	if($str)
    {
		$query = "SELECT dutyName ".
						"FROM Duty WHERE dutyID=$str";
		$query_result = mssql_query($query);
		$row = mssql_fetch_array($query_result);

		$string = $row['dutyName'];

	    return $string;
    }
    else
    {
        return "";
    }
}

function save_Header($ID, $Type, $Seq, $Status, $CompanyID, $UserID, $SubmitDate, $Subject) {
	$Subject = Br_dconv($Subject);
	$query = "INSERT INTO E_DOC_Header (ID, Type, Seq, Status, CompanyID, UserID, SubmitDate, Subject) ".
			 "VALUES ($ID, $Type, $Seq, $Status, $CompanyID, '$UserID', '$SubmitDate', '$Subject') ";
	$result = mssql_query($query);
	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function update_Header($ID, $Type, $Seq, $Status, $CompanyID, $UserID, $SubmitDate, $Subject) {
	$Subject = Br_dconv($Subject);
	$query = "UPDATE E_DOC_Header SET ".
				"Status = $Status, ".
				"CompanyID = $CompanyID, ".
				//"UserID = '$UserID', ".
				"SubmitDate = '$SubmitDate', ".
				"Subject = '$Subject', ".
				"RegDate = GETDATE() ".
				"WHERE ID = $ID AND Type = $Type AND Seq = $Seq ";
	$result = mssql_query($query);
	if($result) {
		return $result;
	} else {
		return  "";
	}
}



function update_bHeader($ID, $Type, $Seq, $Status, $CompanyID, $UserID, $SubmitDate, $Subject) {
	$Subject = Br_dconv($Subject);
	$query = "UPDATE E_DOC_Header SET ".
				"Status = $Status, ".
				//"UserID = '$UserID', ".
				"RegDate = GETDATE() ".
				"WHERE ID = $ID AND Type = $Type AND Seq = $Seq ";
	$result = mssql_query($query);
	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function create_DocID($DocID, $DocSeq) {
	if($DocSeq < '10') {
		$Seq = "-0".$DocSeq;
	} else {
		$Seq = "-".$DocSeq;
	}
	return $DocID.$Seq;
}

function check_ApprovalUser_wait($Type, $DocID, $DocSeq) {
	// 1.결재완료 2.결재진행중
	$Status = 2;
	$Result = "";

	$query = "SELECT TOP 1 ApprovalUserID FROM ApprovalList ".
			 "WHERE DocID = $DocID AND DocType = $Type AND DocSeq = $DocSeq AND ApprovalStatus = $Status ".
			 "ORDER BY ApprovalUserSeq ASC";
	$result = mssql_query($query);
	$row = mssql_fetch_array($result);
	if($row['ApprovalUserID'] != NULL) {
		$Result = $row['ApprovalUserID'];
	}

	return $Result;
}

// NEED TO BE DELETED
function check_ApprovalUser_done($Type, $DocID, $DocSeq, $UserID) {
	// 1.결재완료 2.결재진행중 5.반려
	$Status1 = 1;
	$Status2 = 5;
	$Result = "";

	$query = "SELECT TOP 1 ApprovalUserID FROM ApprovalList ".
			 "WHERE DocID = $DocID AND DocType = $Type AND DocSeq = $DocSeq AND (ApprovalStatus = $Status1 OR ApprovalStatus = $Status2) AND ApprovalUserID = '$UserID' ".
			 "ORDER BY ApprovalUserSeq ASC";
	$result = mssql_query($query);
	$row = mssql_fetch_array($result);
	if($row['ApprovalUserID'] != NULL) {
		$Result = $row['ApprovalUserID'];
	}

	return $Result;
}

function check_is_read($Type, $DocID, $DocSeq, $UserID) {
	$query = "SELECT is_read FROM ApprovalList ".
			 "WHERE DocID = $DocID AND DocType = $Type AND DocSeq = $DocSeq AND ApprovalUserID = '$UserID'";
	$result = mssql_query($query);
	$row = mssql_fetch_array($result);

	return $row['is_read'];
}
/////// BusinessTrip ///////
function save_Businesstrip($DocID, $DocSeq, $DocType, $DocCompanyID, $DocDeptID, $DocSubmitDate, $UserID, $DocStatus, $bname1, $bpos1, $bdep1, $bname2, $bpos2, $bdep2, $bname3, $bpos3, $bdep3) {


	$query = "INSERT INTO BusinessEm (DocID, DocSeq, DocType, DocCompanyID, DocDeptID, DocSubmitDate, UserID, ApprovalStatus, Bname1, Bpos1, Bdep1, Bname2, Bpos2, Bdep2, Bname3, Bpos3, Bdep3) ".
			 "VALUES ($DocID, $DocSeq, $DocType, $DocCompanyID, $DocDeptID, '$DocSubmitDate', '$UserID', $DocStatus,'$bname1','$bpos1','$bdep1','$bname2','$bpos2','$bdep2','$bname3','$bpos3','$bdep3')";
	$result = mssql_query($query);

	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function save_Businesstrip1($DocID, $DocSeq, $DocType, $DocCompanyID, $DocDeptID, $DocSubmitDate, $UserID, $Subject, $ApprovalStatus,$hotelpurpose,$hotelpay,$airpurpose,$airpay,$transpurpose,$transpay,$mealpurpose,$mealpay,$etcpurpose,$etcpay,$totalpurpose,$totalpay) {
	$Subject = Br_dconv($Subject);
//	$Contents = Br_dconv($Contents);
	$hotelpurpose = Br_dconv($hotelpurpose);
	$hotelpay = Br_dconv($hotelpay);
	$airpurpose = Br_dconv($airpurpose);
	$airpay = Br_dconv($airpay);
	$transpurpose = Br_dconv($transpurpose);
	$transpay = Br_dconv($transpay);
	$mealpurpose = Br_dconv($mealpurpose);
	$mealpay = Br_dconv($mealpay);
	$etcpurpose = Br_dconv($etcpurpose);
	$etcpay = Br_dconv($etcpay);
	$totalpurpose = Br_dconv($totalpurpose);
	$totalpay = Br_dconv($totalpay);

	$query = "INSERT INTO Businesstrip (DocID, DocSeq, DocType, DocCompanyID, DocDeptID, DocSubmitDate, UserID, Subject, ApprovalStatus,Hotelpurpose,Hotelpay,Airpurpose,Airpay,Transpurpose,Transpay,Mealpurpose,Mealpay,Etcpurpose,Etcpay,Totalpurpose,Totalpay) ".
			 "VALUES ($DocID, $DocSeq, $DocType, $DocCompanyID, $DocDeptID, '$DocSubmitDate', '$UserID', '$Subject', $ApprovalStatus,'$hotelpurpose','$hotelpay','$airpurpose','$airpay','$transpurpose','$transpay','$mealpurpose','$mealpay','$etcpurpose','$etcpay','$totalpurpose','$totalpay')";
	$result = mssql_query($query);

	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function save_Businesstrip2($DocID, $DocSeq, $DocType, $DocCompanyID, $DocDeptID, $DocSubmitDate, $UserID, $ApprovalStatus,$mainbusinesspurpose,$businessduration,$businesspurpose,$acheive,$resultandproblem) {



//	$Subject = Br_dconv($Subject);
//	$Contents = Br_dconv($Contents);
	$mainbusinesspurpose = Br_dconv($mainbusinesspurpose);
	$businessduration = Br_dconv($businessduration);


	$arrnum = sizeof($businesspurpose);

	for($i = 0; $i < $arrnum; $i++){
		$listnum = $i+1;

		$businesspurpose[$i] = Br_dconv($businesspurpose[$i]);
		$acheive[$i] = Br_dconv($acheive[$i]);
		$resultandproblem[$i] = Br_dconv($resultandproblem[$i]);



		$query = "INSERT INTO Businesstrip2 (DocID, DocSeq, DocType, DocCompanyID, DocDeptID, DocSubmitDate, UserID, ApprovalStatus,Mainpurpose,Duration,Specificpurpose,Acheive,Result,Listnum) ".
			 "VALUES ($DocID, $DocSeq, $DocType, $DocCompanyID, $DocDeptID, '$DocSubmitDate', '$UserID', $ApprovalStatus,'$mainbusinesspurpose','$businessduration', '".$businesspurpose[$i]."','".$acheive[$i]."','".$resultandproblem[$i]."',$listnum)";

		$result = mssql_query($query);




	}
	if($result) {
			return $result;
		} else {
			return  "";
		}



}

function save_Businesstrip3($DocID, $DocSeq, $DocType, $DocCompanyID, $DocDeptID, $DocSubmitDate, $UserID,$DocStatus,$businessdate,$companyvisit,$resultforvisit) {



	$arrnum = sizeof($businessdate);

	for($i = 0; $i < $arrnum; $i++){
		$listnum = $i+1;

		$businessdate[$i] = Br_dconv($businessdate[$i]);
		$companyvisit[$i] = Br_dconv($companyvisit[$i]);
		$resultforvisit[$i] = Br_dconv($resultforvisit[$i]);

		$query = "INSERT INTO Businesstrip3 (DocID, DocSeq, DocType, DocCompanyID, DocDeptID, DocSubmitDate, UserID, ApprovalStatus,BusinessDate,CompanyVisit,ResultForVisit,Listnum) ".
			 "VALUES ($DocID, $DocSeq, $DocType, $DocCompanyID, $DocDeptID, '$DocSubmitDate', '$UserID', $ApprovalStatus,'".$businessdate[$i]."','".$companyvisit[$i]."','".$resultforvisit[$i]."',$listnum)";

		$result = mssql_query($query);




	}

	if($result) {
			return $result;
		} else {
			return  "";
		}



}






function save_BusinessApproval($DocID, $DocType, $DocSeq, $ApprovalUserID, $ApprovalStatus) {
	$iCount = 0;

	for($i = 0; $i < 9; $i++) {
		if($ApprovalUserID[$i]) {
			$iCount++;
			$query = "INSERT INTO ApprovalList (DocID, DocType, DocSeq, ApprovalUserSeq, ApprovalUserID, ApprovalStatus) ".
					 "VALUES ($DocID, $DocType, $DocSeq, $iCount, '$ApprovalUserID[$i]', $ApprovalStatus)";
			$result = mssql_query($query);
		}
	}

	if($iCount > 0) {
		return $iCount;
	} else {
		return  "";
	}
}

function save_BusinesstripAttach($DocID, $DocSeq, $FileSeq, $uporgfile, $orgfile, $UserID) {
	$query = "INSERT INTO BusinessAttach (DocID, DocSeq, FileSeq, NewFilename, OriginalFilename, UserID) ".
			 "VALUES ('$DocID', '$DocSeq', '$FileSeq', '$uporgfile', '$orgfile', '$UserID')";
	$result = mssql_query($query);
	if($result) {
		return $result;
	} else {
		return  "";
	}
}
/* save salesjournal attach */
function save_saleAttach($ID, $Seq, $FileSeq, $uporgfile, $orgfile, $userID) {
	$query = "INSERT INTO salesJournalAttach (ID, Seq, FileSeq, NewFilename, OriginalFilename, UserID) ".
			 "VALUES ('$ID', '$Seq', '$FileSeq', '$uporgfile', '$orgfile', '$userID')";
	$result = mssql_query($query);
	if($result) {
		return $result;
	} else {
		return  "";
	}
}









function update_Business($DocID, $DocSeq, $DocType, $DocCompanyID, $DocDeptID, $DocSubmitDate, $UserID, $DocStatus, $bname1, $bpos1, $bdep1, $bname2, $bpos2, $bdep2, $bname3, $bpos3, $bdep3) {
//	$Subject = Br_dconv($Subject);
//	$Contents = Br_dconv($Contents);
//	$bname1 = Br_dconv($bname1);
//	$bpos1 = Br_dconv($bpos1);
//	$bdep1 = Br_dconv($bdep1);
//	$bname2 = Br_dconv($bname2);
//	$bpos2 = Br_dconv($bpos2);
//	$bdep2 = Br_dconv($bdep2);
//	$bname3 = Br_dconv($bname3);
//	$bpos3 = Br_dconv($bpos3);
//	$bdep3 = Br_dconv($bdep3);



	if($DocStatus == 2) {
		$query = "UPDATE BusinessEm SET ".
				 "ApprovalStatus = $DocStatus, ".
                 "Bname1 = '$bname1', ".
				 "Bpos1 = '$bpos1', ".
				 "Bdep1 = '$bdep1', ".
			     "Bname2 = '$bname2', ".
                 "Bpos2 = '$bpos2', ".
                 "Bdep2 = '$bdep2', ".
                 "Bname3 = '$bname3', ".
                 "Bpos3 = '$bpos3', ".
                 "Bdep3 = '$bdep3', ".
				 "ApprovalDate = NULL, ".
				 "RegDate = GETDATE() ".
				 "WHERE DocID = $DocID AND DocSeq = $DocSeq AND DocType = $DocType ";
	} else if($DocStatus == 3 || $DocStatus == 4) {
		$query = "UPDATE BusinessEm SET ".
				 "ApprovalStatus = $DocStatus, ".
				 "Bname1 = '$bname1', ".
				 "Bpos1 = '$bpos1', ".
				 "Bdep1 = '$bdep1', ".
			     "Bname2 = '$bname2', ".
                 "Bpos2 = '$bpos2', ".
                 "Bdep2 = '$bdep2', ".
                 "Bname3 = '$bname3', ".
                 "Bpos3 = '$bpos3', ".
                 "Bdep3 = '$bdep3', ".
				 "RegDate = GETDATE() ".
				 "WHERE DocID = $DocID AND DocSeq = $DocSeq AND DocType = $DocType ";
	} else if($DocStatus == 1) {
		$DocStatus = 4;
		$query = "UPDATE BusinessEm SET ".
				 "ApprovalStatus = $DocStatus, ".
				 "Bname1 = '$bname1', ".
				 "Bpos1 = '$bpos1', ".
				 "Bdep1 = '$bdep1', ".
			     "Bname2 = '$bname2', ".
                 "Bpos2 = '$bpos2', ".
                 "Bdep2 = '$bdep2', ".
                 "Bname3 = '$bname3', ".
                 "Bpos3 = '$bpos3', ".
                 "Bdep3 = '$bdep3', ".
				 "RegDate = GETDATE() ".
				 "WHERE DocID = $DocID AND DocSeq = $DocSeq AND DocType = $DocType ";
	}
	$result = mssql_query($query);
	if($result) {
		return $result;
	} else {
		return  "";
	}
}


function update_Business1($DocID, $DocSeq, $DocType, $DocCompanyID, $DocDeptID, $DocSubmitDate, $UserID, $Subject, $DocStatus,$hotelpurpose,$hotelpay,$airpurpose,$airpay,$transpurpose,$transpay,$mealpurpose,$mealpay,$etcpurpose,$etcpay,$totalpurpose,$totalpay) {
	$Subject = Br_dconv($Subject);
//	$Contents = Br_dconv($Contents);
	$hotelpurpose = Br_dconv($hotelpurpose);
	$hotelpay = Br_dconv($hotelpay);
	$airpurpose = Br_dconv($airpurpose);
	$airpay = Br_dconv($airpay);
	$transpurpose = Br_dconv($transpurpose);
	$transpay = Br_dconv($transpay);
	$mealpurpose = Br_dconv($mealpurpose);
	$mealpay = Br_dconv($mealpay);
	$etcpurpose = Br_dconv($etcpurpose);
	$etcpay = Br_dconv($etcpay);
	$totalpurpose = Br_dconv($totalpurpose);
	$totalpay = Br_dconv($totalpay);


	if($DocStatus == 2) {
		$query = "UPDATE Businesstrip SET ".
				 "ApprovalStatus = $DocStatus, ".
                 "Hotelpurpose = '$hotelpurpose', ".
				 "Hotelpay = '$hotelpay', ".
				 "Airpurpose = '$airpurpose', ".
			     "Airpay = '$airpay', ".
                 "Transpurpose = '$transpurpose', ".
                 "Transpay = '$transpay', ".
                 "Mealpurpose = '$mealpurpose', ".
                 "Mealpay = '$mealpay', ".
                 "Etcpurpose = '$etcpurpose', ".
                 "Etcpay = '$etcpay', ".
                 "Totalpurpose = '$totalpurpose', ".
                 "Totalpay = '$totalpay', ".
				 "ApprovalDate = NULL, ".
				 "RegDate = GETDATE() ".
				 "WHERE DocID = $DocID AND DocSeq = $DocSeq AND DocType = $DocType ";
	} else if($DocStatus == 3 || $DocStatus == 4) {
		$query = "UPDATE Businesstrip SET ".
				 "ApprovalStatus = $DocStatus, ".
				 "Hotelpurpose = '$hotelpurpose', ".
				 "Hotelpay = '$hotelpay', ".
				 "Airpurpose = '$airpurpose', ".
			     "Airpay = '$airpay', ".
                 "Transpurpose = '$transpurpose', ".
                 "Transpay = '$transpay', ".
                 "Mealpurpose = '$mealpurpose', ".
                 "Mealpay = '$mealpay', ".
                 "Etcpurpose = '$etcpurpose', ".
                 "Etcpay = '$etcpay', ".
                 "Totalpurpose = '$totalpurpose', ".
                 "Totalpay = '$totalpay', ".
				 "RegDate = GETDATE() ".
				 "WHERE DocID = $DocID AND DocSeq = $DocSeq AND DocType = $DocType ";
	} else if($DocStatus == 1) {
		$DocStatus = 4;
		$query = "UPDATE Businesstrip SET ".
				 "ApprovalStatus = $DocStatus, ".
				 "Hotelpurpose = '$hotelpurpose', ".
				 "Hotelpay = '$hotelpay', ".
				 "Airpurpose = '$airpurpose', ".
			     "Airpay = '$airpay', ".
                 "Transpurpose = '$transpurpose', ".
                 "Transpay = '$transpay', ".
                 "Mealpurpose = '$mealpurpose', ".
                 "Mealpay = '$mealpay', ".
                 "Etcpurpose = '$etcpurpose', ".
                 "Etcpay = '$etcpay', ".
                 "Totalpurpose = '$totalpurpose', ".
                 "Totalpay = '$totalpay', ".
				 "RegDate = GETDATE() ".
				 "WHERE DocID = $DocID AND DocSeq = $DocSeq AND DocType = $DocType ";
	}
	$result = mssql_query($query);
	if($result) {
		return $result;
	} else {
		return  "";
	}
}


function update_Business2($DocID, $DocSeq, $DocType, $DocCompanyID, $DocDeptID, $DocSubmitDate, $UserID, $DocStatus,$mainbusinesspurpose,$businessduration,$businesspurpose,$acheive,$resultandproblem) {

	$mainbusinesspurpose = Br_dconv($mainbusinesspurpose);
	$businessduration = Br_dconv($businessduration);


	$arrnum = sizeof($businesspurpose);



	if($DocStatus == 2) {

		for($i = 0; $i < $arrnum; $i++){
			$listnum = $i+1;
			$businesspurpose[$i] = Br_dconv($businesspurpose[$i]);
			$acheive[$i] = Br_dconv($acheive[$i]);
			$resultandproblem[$i] = Br_dconv($resultandproblem[$i]);





			$query = "UPDATE Businesstrip2 SET ".
				     "ApprovalStatus = $DocStatus, ".
					 "Mainpurpose = '$mainbusinesspurpose', ".
					 "Duration = '$businessduration', ".
					 "Specificpurpose = '".$businesspurpose[$i]."', ".
					 "Acheive = '".$acheive[$i]."', ".
					 "Result = '".$resultandproblem[$i]."', ".
					 "ApprovalDate = NULL, ".
					 "RegDate = GETDATE() ".
					 "WHERE DocID = $DocID AND DocSeq = $DocSeq AND DocType = $DocType AND Listnum = $listnum";
			$result = mssql_query($query);
	}

	} else if($DocStatus == 3 || $DocStatus == 4) {

		for($i = 0; $i < $arrnum; $i++){
			$listnum = $i+1;
			$businesspurpose[$i] = Br_dconv($businesspurpose[$i]);
			$acheive[$i] = Br_dconv($acheive[$i]);
			$resultandproblem[$i] = Br_dconv($resultandproblem[$i]);


			$query = "UPDATE Businesstrip2 SET ".
					 "ApprovalStatus = $DocStatus, ".
					 "DocCompanyID = $DocCompanyID, ".
					 "Mainpurpose = '$mainbusinesspurpose', ".
					 "Duration = '$businessduration', ".
					 "Specificpurpose = '".$businesspurpose[$i]."', ".
					 "Acheive = '".$acheive[$i]."', ".
					 "Result = '".$resultandproblem[$i]."', ".
					 "RegDate = GETDATE() ".
					 "WHERE DocID = $DocID AND DocSeq = $DocSeq AND DocType = $DocType AND Listnum = $listnum ";
			$result = mssql_query($query);
		}
	}	else if($DocStatus == 1) {
		$DocStatus = 4;
		for($i = 0; $i < $arrnum; $i++){
			$listnum = $i+1;
			$businesspurpose[$i] = Br_dconv($businesspurpose[$i]);
			$acheive[$i] = Br_dconv($acheive[$i]);
			$resultandproblem[$i] = Br_dconv($resultandproblem[$i]);


			$query = "UPDATE Businesstrip2 SET ".
					 "ApprovalStatus = $DocStatus, ".
					 "DocCompanyID = $DocCompanyID, ".
					 "Mainpurpose = '$mainbusinesspurpose', ".
					 "Duration = '$businessduration', ".
					 "Specificpurpose = '".$businesspurpose[$i]."', ".
					 "Acheive = '".$acheive[$i]."', ".
					 "Result = '".$resultandproblem[$i]."', ".
					 "RegDate = GETDATE() ".
					 "WHERE DocID = $DocID AND DocSeq = $DocSeq AND DocType = $DocType AND Listnum = $listnum ";
			$result = mssql_query($query);
		}
	}

	if($result) {
		return $result;
	} else {
		return  "";
	}
}



function update_Business3($DocID, $DocSeq, $DocType, $DocCompanyID, $DocDeptID, $DocSubmitDate, $UserID,$DocStatus,$businessdate,$companyvisit,$resultforvisit) {




	$arrnum = sizeof($businessdate);



	if($DocStatus == 2) {

		for($i = 0; $i < $arrnum; $i++){
			$listnum = $i+1;
			$businessdate[$i] = Br_dconv($businessdate[$i]);
			$companyvisit[$i] = Br_dconv($companyvisit[$i]);
			$resultforvisit[$i] = Br_dconv($resultforvisit[$i]);





			$query = "UPDATE Businesstrip3 SET ".
					 "ApprovalStatus = $DocStatus, ".
					 "BusinessDate = '".$businessdate[$i]."', ".
					 "CompanyVisit = '".$companyvisit[$i]."', ".
					 "ResultForVisit = '".$resultforvisit[$i]."', ".
					 "ApprovalDate = NULL, ".
					 "RegDate = GETDATE() ".
					 "WHERE DocID = $DocID AND DocSeq = $DocSeq AND DocType = $DocType AND Listnum = $listnum";
			$result = mssql_query($query);
	}

	} else if($DocStatus == 3 || $DocStatus == 4) {

		for($i = 0; $i < $arrnum; $i++){
			$listnum = $i+1;
			$businessdate[$i] = Br_dconv($businessdate[$i]);
			$companyvisit[$i] = Br_dconv($companyvisit[$i]);
			$resultforvisit[$i] = Br_dconv($resultforvisit[$i]);


			$query = "UPDATE Businesstrip3 SET ".
					 "ApprovalStatus = $DocStatus, ".
					 "BusinessDate = '".$businessdate[$i]."', ".
					 "CompanyVisit = '".$companyvisit[$i]."', ".
					 "ResultForVisit = '".$resultforvisit[$i]."', ".
					 "RegDate = GETDATE() ".
					 "WHERE DocID = $DocID AND DocSeq = $DocSeq AND DocType = $DocType AND Listnum = $listnum ";
			$result = mssql_query($query);
		}
	} else if($DocStatus == 1) {
		$DocStatus = 4;
		for($i = 0; $i < $arrnum; $i++){
			$listnum = $i+1;
			$businessdate[$i] = Br_dconv($businessdate[$i]);
			$companyvisit[$i] = Br_dconv($companyvisit[$i]);
			$resultforvisit[$i] = Br_dconv($resultforvisit[$i]);


			$query = "UPDATE Businesstrip3 SET ".
					 "ApprovalStatus = $DocStatus, ".
					 "BusinessDate = '".$businessdate[$i]."', ".
					 "CompanyVisit = '".$companyvisit[$i]."', ".
					 "ResultForVisit = '".$resultforvisit[$i]."', ".
					 "RegDate = GETDATE() ".
					 "WHERE DocID = $DocID AND DocSeq = $DocSeq AND DocType = $DocType AND Listnum = $listnum ";
			$result = mssql_query($query);
		}
	}

	if($result) {
		return $result;
	} else {
		return  "";
	}
}











/////// Document ///////
function get_DocSeq($today, $doc_type) {
	$DocSeq = 1;
	$query = "Select max(Seq)+1 as DocSeq From E_DOC_Header ".
			 "Where convert(char(10), RegDate, 126) = '$today' and type= $doc_type";
	$result = mssql_query($query);
	$row = mssql_fetch_array($result);
	if($row['DocSeq']) {
		$DocSeq = $row['DocSeq'];
	}

	return  $DocSeq;
}

function save_Doc($DocID, $DocSeq, $DocType, $DocCompanyID, $DocDeptID, $DocSubmitDate, $UserID, $Subject, $Contents, $ApprovalStatus) {
	$Subject = Br_dconv($Subject);
	$Contents = Br_dconv($Contents);

	$query = "INSERT INTO Doc (DocID, DocSeq, DocType, DocCompanyID, DocDeptID, DocSubmitDate, UserID, Subject, Contents, ApprovalStatus) ".
			 "VALUES ($DocID, $DocSeq, $DocType, $DocCompanyID, $DocDeptID, '$DocSubmitDate', '$UserID', '$Subject', '$Contents', $ApprovalStatus)";
	$result = mssql_query($query);

	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function save_DocApproval($DocID, $DocType, $DocSeq, $ApprovalUserID, $ApprovalStatus) {
	$iCount = 0;

	for($i = 0; $i < 9; $i++) {
		if($ApprovalUserID[$i]) {
			$iCount++;
			$query = "INSERT INTO ApprovalList (DocID, DocType, DocSeq, ApprovalUserSeq, ApprovalUserID, ApprovalStatus) ".
					 "VALUES ($DocID, $DocType, $DocSeq, $iCount, '$ApprovalUserID[$i]', $ApprovalStatus)";
			$result = mssql_query($query);
		}
	}

	if($iCount > 0) {
		return $iCount;
	} else {
		return  "";
	}
}

function save_DocAttach($DocID, $DocSeq, $FileSeq, $uporgfile, $orgfile, $UserID) {
	$query = "INSERT INTO DocAttach (DocID, DocSeq, FileSeq, NewFilename, OriginalFilename, UserID) ".
			 "VALUES ('$DocID', '$DocSeq', '$FileSeq', '$uporgfile', '$orgfile', '$UserID')";
	$result = mssql_query($query);
	if($result) {
		return $result;
	} else {
		return  "";
	}
}

/////// Document ///////

function getDocumentID($today, $docType)
{
	//1:기안서, 2:협조문, 3:지출결의서
	$Numid = 1;
	$query = "Select max(Seq)+1 as DocID From E_DOC_Header ".
					"Where ID = '$today' AND [Type] = '$docType' ";
	$result = mssql_query($query);
	$row = mssql_fetch_array($result);
	if($row['DocID']) {
		$Numid = $row['DocID'];
	}

	return  $Numid;
}

function getFormID()
{
	$Numid = 1;
	$query = "Select max(DocID)+1 as DocID From Docform ";
	$result = mssql_query($query);
	$row = mssql_fetch_array($result);
	if($row['DocID']) {
		$Numid = $row['DocID'];
	}
	return  $Numid;
}

function getnoteID()
{
	$Numid = 1;
	$query = "Select max(bdId)+1 as bdId From board_data ";
	$result = mssql_query($query);
	$row = mssql_fetch_array($result);
	if($row['bdId']) {
		$Numid = $row['bdId'];
	}
	return  $Numid;
}


function getmainID()
{
	$Numid = 1;
	$query = "Select max(mainId)+1 as mainId From board_data ";
	$result = mssql_query($query);
	$row = mssql_fetch_array($result);
	if($row['mainId']) {
		$Numid = $row['mainId'];
	}
	return  $Numid;
}





function gethelpID()
{
	$Numid = 1;
	$query = "Select max(helpId)+1 as helpId From board_data ";
	$result = mssql_query($query);
	$row = mssql_fetch_array($result);
	if($row['helpId']) {
		$Numid = $row['helpId'];
	}
	return  $Numid;
}


function getfreeID()
{
	$Numid = 1;
	$query = "Select max(freeId)+1 as freeId From board_data ";
	$result = mssql_query($query);
	$row = mssql_fetch_array($result);
	if($row['freeId']) {
		$Numid = $row['freeId'];
	}
	return  $Numid;
}



function save_contents($docid, $docType, $docseq, $doc_subject, $Contents, $payto, $amount, $PaymentMethod, $Currency, $runcompid, $link_doc)
{
	//1:기안서, 2:협조문, 3:지출결의서
	$UserID = $_SESSION['memberID'];
//	$CompanyID =  $_SESSION['memberCID'];
	$DeptID =  $_SESSION['memberDID'];

	$doc_subject = Br_dconv($doc_subject);
	$Contents = Br_dconv($Contents);
	/*
	if($link_doc != "") {
		$query = "INSERT INTO Voucher ".
					"(VoucherID, VoucherSeq, VoucherType, CompanyID, UserID, PayTo, PaymentMethod, CurrencyType, Amount , Subject, Contents, ApprovalStatus, LinkedDoc) ".
					"VALUES ".
					"($docid,$docseq,'$docType',$runcompid,'$UserID','$payto','$PaymentMethod','$Currency',$amount,'$doc_subject', '$Contents','2', '$link_doc')";
	} else {
		$query = "INSERT INTO Voucher ".
					"(VoucherID, VoucherSeq, VoucherType, CompanyID, UserID, PayTo, PaymentMethod, CurrencyType, Amount , Subject, Contents, ApprovalStatus) ".
					"VALUES ".
					"($docid,$docseq,'$docType',$runcompid,'$UserID','$payto','$PaymentMethod','$Currency',$amount,'$doc_subject', '$Contents','2')";
	}
	*/
	if($link_doc != "") {
		$query = "INSERT INTO Voucher ".
					"(VoucherID, VoucherSeq, VoucherType, CompanyID, UserID, PayTo, PaymentMethod, CurrencyType, Amount , Subject, Contents, ApprovalStatus, LinkedDoc, VouSubmitDate) ".
					"VALUES ".
					"($docid,$docseq,'$docType',$runcompid,'$UserID','$payto','$PaymentMethod','$Currency',$amount,'$doc_subject', '$Contents','2', '$link_doc',GETDATE())";
	} else {
		$query = "INSERT INTO Voucher ".
					"(VoucherID, VoucherSeq, VoucherType, CompanyID, UserID, PayTo, PaymentMethod, CurrencyType, Amount , Subject, Contents, ApprovalStatus, VouSubmitDate) ".
					"VALUES ".
					"($docid,$docseq,'$docType',$runcompid,'$UserID','$payto','$PaymentMethod','$Currency',$amount,'$doc_subject', '$Contents','2',GETDATE())";
	}
	$result = mssql_query($query);

	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function save_coop_contents($docid, $docType, $docseq, $doc_subject, $Contents, $runcompid, $rundeptid, $CoopList, $today)
{
	//1:기안서, 2:협조문, 3:지출결의서
	$UserID = $_SESSION['memberID'];

	$doc_subject = Br_dconv($doc_subject);
	$Contents = Br_dconv($Contents);
	if($CoopList == 1) {
		$query = "INSERT INTO Cooperation ".
					"(DocID,DocSeq,DocType,CompanyID,DeptID,CoopList,UserID,Subject,Contents,ApprovalStatus, SubmitDate) ".
					"VALUES ".
					"($docid, $docseq, $docType, $runcompid, $rundeptid, $CoopList, '$UserID', '$doc_subject', '$Contents', 2, GETDATE())";
	} else {
		$query = "INSERT INTO Cooperation ".
					"(DocID, DocSeq, DocType, CompanyID, DeptID, CoopList) ".
					"VALUES ".
					"($docid, $docseq, $docType, $runcompid, $rundeptid, $CoopList)";
	}
	$result = mssql_query($query);

	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function save_form_contents($docid, $subject, $desc, $usecompid, $userid)
{
	$subject = Br_dconv($subject);
	$desc = Br_dconv($desc);
	$query = "INSERT INTO Docform ".
					"(DocId, DocCompany, DocSubject, DocDesc, UserID) ".
					"VALUES ".
					"($docid, $usecompid, '$subject', '$desc','$userid')";
	$result = mssql_query($query);
	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function save_note_contents($docid, $type, $usecompid, $userid, $stDate, $edDate, $subject, $Content, $period,$helpid,$freeid,$mainid)
{
	$subject = Br_dconv($subject);
	$Content = Br_dconv($Content);
	if($usecompid == "") $usecompid = 0;

	$query = "INSERT INTO board_data ".
					"(bdId, boardId, CompanyId, UserId, bdTitle, bdDescription,StartDate,EndDate,period,helpId,freeId,mainId) ".
					"VALUES ".
					"($docid, $type, $usecompid, '$userid', '$subject', '$Content', '$stDate', '$edDate', $period,$helpid,$freeid,$mainid)";
	$result = mssql_query($query);
	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function update_Doc($DocID, $DocType, $DocSeq, $DocCompanyID, $DocSubmitDate, $Subject, $Contents, $ApprovalStatus) {
	$Subject = Br_dconv($Subject);
	$Contents = Br_dconv($Contents);
	if($ApprovalStatus == 2) {
		$query = "UPDATE Doc SET ".
				 "DocCompanyID = $DocCompanyID, ".
				 "DocSubmitDate = '$DocSubmitDate', ".
				 "Subject = '$Subject', ".
				 "Contents = '$Contents', ".
				 "ApprovalStatus = $ApprovalStatus, ".
				 "ApprovalDate = NULL, ".
				 "RegDate = GETDATE() ".
				 "WHERE DocID = $DocID AND DocSeq = $DocSeq AND DocType = $DocType ";
	} else if($ApprovalStatus == 3 || $ApprovalStatus == 4) {
		$query = "UPDATE Doc SET ".
				 "DocCompanyID = $DocCompanyID, ".
				 "DocSubmitDate = '$DocSubmitDate', ".
				 "Subject = '$Subject', ".
				 "Contents = '$Contents', ".
				 "ApprovalStatus = $ApprovalStatus, ".
				 "RegDate = GETDATE() ".
				 "WHERE DocID = $DocID AND DocSeq = $DocSeq AND DocType = $DocType ";
	}
	$result = mssql_query($query);
	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function update_voucher_contents($docid, $docType, $docseq, $Status, $doc_subject, $Contents, $payto, $amount, $PaymentMethod, $Currency, $runcompid, $link_doc)
{
	$doc_subject = Br_dconv($doc_subject);
	$Contents = Br_dconv($Contents);
	if($link_doc == "") {
		$query = "UPDATE Voucher SET ".
				"CompanyID = $runcompid, ".
	//			"UserID = '$UserID',".
				"PayTo = '$payto', ".
				"PaymentMethod = '$PaymentMethod',".
				"CurrencyType = '$Currency',".
				"Amount = $amount, ".
				"LinkedDoc = NULL, ".
				"Subject = '$doc_subject',".
				"Contents= '$Contents',".
				"ApprovalStatus = $Status ".
				"WHERE VoucherID = $docid AND VoucherSeq = $docseq ";
	} else {
		$query = "UPDATE Voucher SET ".
				"CompanyID = $runcompid, ".
	//			"UserID = '$UserID',".
				"PayTo = '$payto', ".
				"PaymentMethod = '$PaymentMethod',".
				"CurrencyType = '$Currency',".
				"Amount = $amount, ".
				"LinkedDoc = '$link_doc', ".
				"Subject = '$doc_subject',".
				"Contents= '$Contents',".
				"ApprovalStatus = $Status ".
				"WHERE VoucherID = $docid AND VoucherSeq = $docseq ";
	}
	$result = mssql_query($query);

	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function update_note_contents($bdId,$Type,$usecompid,$stDate,$edDate,$subject,$Contents,$period)
{
	$UserID = $_SESSION['memberID'];
	$subject = Br_dconv($subject);
	$Contents = Br_dconv($Contents);
	$query = "UPDATE board_data SET ".
			"boardId = $Type, ".
			"CompanyId = $usecompid, ".
			"StartDate = '$stDate', ".
			"EndDate = '$edDate',".
			"bdTitle = '$subject',".
			"bdDescription = '$Contents', ".
			"period = $period, ".
			"UserId = '$UserID' ".
			"WHERE bdId = $bdId ";
	$result = mssql_query($query);

	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function update_coop_contents($docid, $docType, $docseq, $doc_subject, $Contents, $runcompid, $rundeptid, $CoopList, $Status)
{
	$doc_subject = Br_dconv($doc_subject);
	$Contents = Br_dconv($Contents);
	if($CoopList == 1) {
		$query = "UPDATE Cooperation SET ".
				 "CompanyID = $runcompid, ".
				 "DeptID = $rundeptid, ".
				 "Subject = '$doc_subject',".
				 "Contents= '$Contents',".
				 "SubmitDate = GETDATE(), ".
				 "ApprovalStatus = $Status ".
				 "WHERE DocID = $docid AND DocSeq = $docseq ";
	} else {
		$query = "INSERT INTO Cooperation ".
				 "(DocID, DocSeq, DocType, CompanyID, DeptID, CoopList) ".
				 "VALUES ".
				 "($docid, $docseq, $docType, $runcompid, $rundeptid, $CoopList)";
	}
	$result = mssql_query($query);

	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function delete_coop_list($docid, $docseq) {
	$CoopList = 1;
	$query = "DELETE FROM Cooperation WHERE DocID = $docid AND DocSeq = $docseq AND CoopList != $CoopList";
	$result = mssql_query($query);

	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function save_approval($today, $doc_id, $docType, $theVariable, $AppStatus)
{
	//1:기안서, 2:협조문, 3:지출결의서
	$DocID = str_replace("-", "", $today);
	$CompanyID = $_SESSION['memberCID'];
	$DeptID = $_SESSION['memberDID'];
	$UserID = $_SESSION['memberID'];
	$iCount = 0;

	for($i=0; $i<=9;$i++){
		$iCount++;
		if($theVariable[$i]) {
			$query = "INSERT INTO ApprovalList ".
							"(DocID, DocType, DocSeq, ApprovalUserSeq, ApprovalUserID,ApprovalStatus) ".
							"VALUES ".
							"($DocID, $docType,$doc_id, $iCount, '$theVariable[$i]', $AppStatus)";
			$result = mssql_query($query);
		}
	}

	if($iCount > 0) {
		return $iCount;
	} else {
		return  "";
	}
}

function update_approval($DocID, $Seq, $docType, $theVariable, $AppStatus)
{
	//1:기안서, 2:협조문, 3:지출결의서
	$iCount = 0;

	$query = "DELETE FROM ApprovalList ".
			"WHERE DocID = $DocID AND DocType = $docType AND DocSeq = $Seq ";
	$rst = mssql_query($query);

	for($i=0; $i<=9;$i++){
		$iCount++;
		if($theVariable[$i]) {
			$query = "INSERT INTO ApprovalList ".
							"(DocID, DocType, DocSeq, ApprovalUserSeq, ApprovalUserID,ApprovalStatus) ".
							"VALUES ".
							"($DocID, $docType,$Seq, $iCount, '$theVariable[$i]', $AppStatus)";
			$result = mssql_query($query);
		}
	}

	if($iCount > 0) {
		return $iCount;
	} else {
		return  "";
	}
}

function save_approval_submit($docid, $docseq, $doctype, $approvaluser, $comment, $approvalstatus, $today)
{
	//1:기안서, 2:협조문, 3:지출결의서 4: 출장계획서
	$comment = Br_dconv($comment);
	$query = "UPDATE ApprovalList SET ApprovalComment = '$comment', ApprovalStatus=$approvalstatus, ApprovalDate='$today', RegDate='$today' ".
					"WHERE DocID = $docid AND DocType = $doctype AND DocSeq = $docseq AND ApprovalUserID = '$approvaluser' AND ProcessSeq = 0 ";
	$result = mssql_query($query);
	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function save_approval_businesstrip($docid, $docseq, $doctype, $approvalstatus, $today) {
	$query = "UPDATE BusinessEm SET ApprovalDate = '$today', ApprovalStatus = $approvalstatus ".
			 "WHERE DocID = $docid AND DocSeq = $docseq ";
	$result = mssql_query($query);

	$query = "UPDATE Businesstrip SET ApprovalDate = '$today', ApprovalStatus = $approvalstatus ".
			 "WHERE DocID = $docid AND DocSeq = $docseq ";
	$result = mssql_query($query);

	$query = "UPDATE Businesstrip2 SET ApprovalDate = '$today', ApprovalStatus = $approvalstatus ".
			 "WHERE DocID = $docid AND DocSeq = $docseq ";
	$result1 = mssql_query($query);

	$query = "UPDATE Businesstrip3 SET ApprovalDate = '$today', ApprovalStatus = $approvalstatus ".
			 "WHERE DocID = $docid AND DocSeq = $docseq ";
	$result12 = mssql_query($query);


	$query = "UPDATE E_DOC_Header SET Status = $approvalstatus ".
			 "WHERE ID = $docid AND Type = $doctype AND Seq = $docseq ";
	$result2 = mssql_query($query);

	if($result2) {
		return $result2;
	} else {
		return  "";
	}
}







function save_approval_proposal($docid, $docseq, $doctype, $approvalstatus, $today) {
	$query = "UPDATE Doc SET ApprovalDate = '$today', ApprovalStatus = $approvalstatus ".
			 "WHERE DocID = $docid AND DocSeq = $docseq ";
	$result = mssql_query($query);

	$query = "UPDATE E_DOC_Header SET Status = $approvalstatus ".
			 "WHERE ID = $docid AND Type = $doctype AND Seq = $docseq ";
	$result2 = mssql_query($query);

	if($result2) {
		return $result2;
	} else {
		return  "";
	}
}

function save_approval_coop($docid, $docseq, $doctype, $approvalstatus, $today) {
	$query = "UPDATE Cooperation SET ApprovalDate = '$today', ApprovalStatus = $approvalstatus ".
			 "WHERE DocID = $docid AND DocSeq = $docseq ";
	$result = mssql_query($query);

	$query = "UPDATE E_DOC_Header SET Status = $approvalstatus ".
			 "WHERE ID = $docid AND Type = $doctype AND Seq = $docseq ";
	$result2 = mssql_query($query);

	if($result2) {
		return $result2;
	} else {
		return  "";
	}
}

function save_approval_voucher($docid, $docseq, $doctype, $approvalstatus, $today)
{
	$query = "UPDATE Voucher SET ApprovalDate = '$today', ApprovalStatus = '$approvalstatus' ".
					"WHERE VoucherID = $docid AND VoucherSeq = $docseq ";
	$result = mssql_query($query);

	$query = "UPDATE E_DOC_Header SET Status = '$approvalstatus' ".
					"WHERE ID = $docid AND Type = $doctype AND Seq = $docseq ";
	$result2 = mssql_query($query);

	if($result2) {
		return $result2;
	} else {
		return  "";
	}
}

function save_image($today, $doc_id, $docType,  $uploadfile, $uploadfile_org, $docNum)
{
	//1:기안서, 2:협조문, 3:지출결의서
	$vid = str_replace("-", "", $today);
	$UserID = $_SESSION['memberID'];

	$query = "INSERT INTO VoucherAttach (VouAttachID, VouSeq, VouNum, NewFilename, OriginalFilename, UserID ) ".
				"VALUES ($vid, $doc_id, $docNum,'$uploadfile','$uploadfile_org','$UserID')";
	$result = mssql_query($query);
	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function save_form_image($docid, $uploadfile)
{
//	$uploadfile = Br_dconv($uploadfile);
	$query = "UPDATE Docform SET DocFilename = '$uploadfile' ".
				"WHERE DocId = $docid";
	$result = mssql_query($query);
	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function save_note_image($docid, $uploadfile, $uploadfile_org, $docNum)
{
	$UserID = $_SESSION['memberID'];
	$query = "INSERT INTO board_Attach (DocID, FileSeq, NewFilename, OriginalFilename, UserID ) ".
				"VALUES ($docid, $docNum,'$uploadfile','$uploadfile_org','$UserID')";
	$result = mssql_query($query);
	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function save_coop_image($today, $doc_id, $docType,  $uploadfile, $uploadfile_org, $docNum)
{
	//1:기안서, 2:협조문, 3:지출결의서
	$vid = str_replace("-", "", $today);
	$UserID = $_SESSION['memberID'];

	$query = "INSERT INTO CoopAttach (CoopAttachID, CoopSeq, CoopNum, NewFilename, OriginalFilename, UserID ) ".
				"VALUES ($vid, $doc_id, $docNum,'$uploadfile','$uploadfile_org','$UserID')";
	$result = mssql_query($query);
	if($result) {
		return $result;
	} else {
		return  "";
	}
}

function delete_images($ID, $Seq, $Type)
{
	if($Type == 2) {
		$sql="SELECT VouNum, NewFilename FROM CoopAttach where CoopAttachID=$ID AND CoopSeq =$Seq ";
		$rst=mssql_query($sql);

		while($row=mssql_fetch_array($rst)) {
			$del_file="./VouAttach/".$row['NewFilename'];
			if($row['NewFilename'] && is_file($del_file)) unlink($del_file);
			$Num = $row['CoopNum'];

			$sql="DELETE FROM CoopAttach where CoopAttachID=$ID AND CoopSeq =$Seq AND CoopNum=$Num ";
			$rst2=mssql_query($sql);
			$row2=mssql_fetch_array($rst2);
		}
	} else if($Type == 3) {
		$sql="SELECT VouNum, NewFilename FROM VoucherAttach where VouAttachID=$ID AND VouSeq =$Seq AND VouNum=$Num ";
		$rst=mssql_query($sql);

		while($row=mssql_fetch_array($rst)) {
			$del_file="./VouAttach/".$row['NewFilename'];
			if($row['NewFilename'] && is_file($del_file)) unlink($del_file);
			$Num = $row['VouNum'];

			$sql="DELETE FROM VoucherAttach where VouAttachID=$ID AND VouSeq =$Seq AND VouNum=$Num ";
			$rst2=mssql_query($sql);
			$row2=mssql_fetch_array($rst2);
		}
	}
}

function inq_DocID($str_subject, $docType, $today)
{
	//1:기안서, 2:협조문, 3:지출결의서
	$UserID = $_SESSION['memberID'];
	$str_subject = Br_dconv($str_subject);

	$query = "SELECT ID FROM E_DOC_Header ".
					"WHERE [Type] = $docType AND Subject = '$str_subject' AND UserID = '$UserID' AND convert(char(10), RegDate, 126) = '$today' ";
	$query_result = mssql_query($query);
	$result = mssql_fetch_array($query_result);

	if($result) {
		return $result['ID'];
	} else {
		return  "";
	}
}

function get_company_name($str)
{
	$query = "SELECT  companyID, companyName, companyDesc From Company ".
					"WHERE companyID = $str ";
	$query_result = mssql_query($query);
	$result = mssql_fetch_array($query_result);

	if($result) {
		return $result['companyName'];
	} else {
		return  "";
	}
}

function get_company_sname($str)
{
	$query = "SELECT  companyID, companyName, companyDesc From Company ".
					"WHERE companyID = $str ";
	$query_result = mssql_query($query);
	$result = mssql_fetch_array($query_result);

	if($result) {
		return $result['companyDesc'];
	} else {
		return  "";
	}
}

function get_dept_name($str, $str2)
{
	$query = "SELECT  deptName From Department ".
					"WHERE companyID = $str AND deptID = $str2";
	$query_result = mssql_query($query);
	$result = mssql_fetch_array($query_result);

	if($result) {
		return $result['deptName'];
	} else {
		return  "";
	}
}

function get_ApprovalStatus($str)
{
	$query = "SELECT  StatusID, StatusDesc From ApprovalStatus ".
					"WHERE StatusID = $str ";
	$query_result = mssql_query($query);
	$result = mssql_fetch_array($query_result);

	if($result) {
		return $result['StatusDesc'];
	} else {
		return  "";
	}
}

function get_user_name($str)
{
	$query = "SELECT memID, memName From Member ".
					"WHERE memID = '$str' ";
	$query_result = mssql_query($query);
	$result = mssql_fetch_array($query_result);

	if($result) {
		return Br_iconv($result['memName']);
	} else {
		return  "";
	}
}

function get_user_dept($str)
{
	$query = "SELECT deptID From Member ".
					"WHERE memID = '$str' ";
	$query_result = mssql_query($query);
	$result = mssql_fetch_array($query_result);

	if($result) {
		return Br_iconv($result['deptID']);
	} else {
		return  "";
	}
}

function get_docName($str)
{
	$query = "SELECT docID, docName From DocKind ".
					"WHERE docID = $str ";
	$query_result = mssql_query($query);
	$result = mssql_fetch_array($query_result);

	if($result) {
		return Br_iconv($result['docName']);
	} else {
		return  "";
	}
}

function get_doc_approval($str)
{
	if($str) {
		$query = "SELECT  StatusID, StatusDesc From ApprovalStatus ".
						"WHERE StatusID = $str ";
		$query_result = mssql_query($query);
		$result = mssql_fetch_array($query_result);
	}

	if($result) {
		return Br_iconv($result['StatusDesc']);
	} else {
		return  "";
	}
}

function get_docimg_approval($str)
{
	if ($str) {
		$strF="<img width='54' height='54' style='padding-top: 9px;' src='/images/0".$str."_img.png'>";
	} else {
		$strF="";
	}
	return $strF;
}

//character encoding
function Br_iconv($string)
{
    if($string == " ")
    {
        return "";
    }
    else if($string)
    {
        $string = iconv('euc-kr', 'utf-8', $string);
        return $string;
    }
    else
    {
        return false;
    }
}

function Br_iconv_c($string)
{
    if($string == " ")
    {
        return "";
    }
    else if($string)
    {
        $string = iconv('euc-kr', 'utf-8', $string);
        return $string;
    }
    else
    {
        return false;
    }
}

function Br_dconv_c($string)
{
    if($string)
    {
        $string = iconv('utf-8', 'GB2312', $string);
        return $string;
    }
    else
    {
        return false;
    }
}

function Br_dconv($string)
{
    if($string)
    {
        $string = iconv('utf-8', 'euc-kr', $string);
        return $string;
    }
    else
    {
        return false;
    }
}

function Br_imgnumber($string)
{

    if($string)
    {
        $string0 = substr($string, 0, 1);
        $string1 = substr($string, 1, 1);
        $string2 = substr($string, 2, 1);
        $string3 = substr($string, 3, 1);
        $string4 = substr($string, 4, 1);
        $string5 = substr($string, 5, 1);
        $string6 = substr($string, 6, 1);
        $string7 = substr($string, 7, 1);

        $string = ($string0) ? "<img src='".SYSTEM_PATH."/images_site/cent_".$string0.".gif'>" : "";
        $string .= ($string1) ? "<img src='".SYSTEM_PATH."/images_site/cent_".$string1.".gif'>" : "";
        $string .= ($string2) ? "<img src='".SYSTEM_PATH."/images_site/cent_".$string2.".gif'>" : "";
        $string .= ($string3) ? "<img src='".SYSTEM_PATH."/images_site/cent_".$string3.".gif'>" : "";
        $string .= ($string4) ? "<img src='".SYSTEM_PATH."/images_site/cent_".$string4.".gif'>" : "";
        $string .= ($string5) ? "<img src='".SYSTEM_PATH."/images_site/cent_".$string5.".gif'>" : "";
        $string .= ($string6) ? "<img src='".SYSTEM_PATH."/images_site/cent_".$string6.".gif'>" : "";
        $string .= ($string7) ? "<img src='".SYSTEM_PATH."/images_site/cent_".$string7.".gif'>" : "";

        return $string;
    }
    else
    {
        return false;
    }
}


//insert - convert my to ms - table name / where / sort / top
function Br_LIMITQUERY($table, $where, $sort, $page_section)
{
    if($page_section)
    {
        $fno = explode(',', $page_section);
        $first = $fno[0];
        $second = $fno[1];

        $query = "select * from  (select ROW_NUMBER() over($sort) as ROWNUM, * from $table $where) T where  T.ROWNUM BETWEEN ($first+1) AND ($first+$second)";
    }
    else
    {
        $query = "select * from $table $where $sort";
    }
    return $query;
}

function Br_ReadIndex($type, $id, $table, $field, $row, $cpage, $scale, $where, $sort)
{
    if($type == "count")
    {
        $query = "select count($id) as row from $table where $where";
        $sel = Br_selectQuery($query);
        $fat = Br_fatch_arryQuery($sel);
        return $fat['row'];
    }
    else if($type == "array")
    {
        if($cpage)
        {
            //페이지 인덱스 구하기
            $cpage_que = $cpage * $scale;
            if($cpage_que == $scale || $cpage == 0)
            {
                $cpage_que = "0";
            }
            else
            {
                $cpage_que = $cpage_que - $scale;
            }

            //마지막 장 갯수 구하기
            $top = $row - $cpage_que;
            if($top > $scale)
            {
                $top = $scale;
            }
            else
            {
                $top = $top;
            }

            $query = "select top $top $field from $table where $id not in (select top $cpage_que $id from $table where $where $sort) and $where $sort";
        }
        else
        {
            $query = "select $field from $table where $where $sort";
        }
        return $result = Br_selectQuery($query);
    }
    else
    {
        return false;
    }
}

//page view
function Page_View1($cpage1, $scale1)
{
    $start_q1 = $scale1 * ($cpage1 - 1);
    return $start_q1;
}

function Page_View2($scale1, $cpage1, $all, $pagenum, $Get_next, $types="cpage1", $fragId = null)
{
    $frag = "";
    if ( $fragId ) $frag = "#$fragId";

    if($all%$scale1)
    {     //whole page
        $tpage = intval($all/$scale1) + 1;
    }
    else
    {
        $tpage = intval($all/$scale1);
    }

    $startpage = $pagenum*intval(($cpage1-1)/$pagenum)+1;

    $tempendpage = $startpage + $pagenum - 1;
    if($tempendpage > $tpage)
    {
        $endpage = $tpage;
    }
    else
    {
        $endpage = $tempendpage;
    }

    $end = ceil($all/$scale1);

    $Page_view1 = $Page_view2 = $Page_view4 = $Page_view5 = $Page_view6 = "";
    if($cpage1 != 1)
    {
        $Page_view1 = " <a href='".$_SERVER['PHP_SELF']."?$Get_next&$types=1$frag'><img src='".SYSTEM_PATH."/images/arrow2_left.gif' border='0' align='absmiddle'></a>   ";
    }

    if($startpage > 1)
    {
        $tempnum = $startpage - 1;
        $Page_view2 = " <a href='".$_SERVER['PHP_SELF']."?$Get_next&$types=$tempnum$frag'><img src='".SYSTEM_PATH."/images/arrow1_left.gif' border='0' align='absmiddle'></a>     ";
    }

    for($j=$startpage; $j<=$endpage; $j++)
    {
        if($j == $cpage1)
        {
            $Page_view4 = $Page_view4."&nbsp;<font color=A86A20><b>$j</b></font>&nbsp;";     //current page
        }
        else
        {
            $Page_view4 = $Page_view4."&nbsp;<a href='".$_SERVER['PHP_SELF']."?$Get_next&$types=$j$frag'>$j</a>&nbsp;"; //else page
        }
    }

    if($endpage < $tpage)
    {
        $tempnum = $endpage + 1;
        $Page_view5 = "<a href='".$_SERVER['PHP_SELF']."?$Get_next&$types=$tempnum$frag'><img    src='".SYSTEM_PATH."/images/arrow1_right.gif' border='0' align='absmiddle'></a>"; //back
    }

    if($cpage1 != $end && $all > $scale1)
    {
        $Page_view6 = " <a href='".$_SERVER['PHP_SELF']."?$Get_next&$types=$end$frag'><img    src='".SYSTEM_PATH."/images/arrow2_right.gif' border='0' align='absmiddle'></a>";//last
    }

    return $Page_view1.$Page_view2.$Page_view4.$Page_view5.$Page_view6;
}

function Page_View3($scale1, $cpage1, $all, $pagenum, $Get_next, $types="cpage1", $fragId = null)
{
    $frag = "";
    if ( $fragId ) $frag = "#$fragId";

    if($all%$scale1)
    {     //whole page
        $tpage = intval($all/$scale1) + 1;
    }
    else
    {
        $tpage = intval($all/$scale1);
    }

    $startpage = $pagenum*intval(($cpage1-1)/$pagenum)+1;

    $tempendpage = $startpage + $pagenum - 1;
    if($tempendpage > $tpage)
    {
        $endpage = $tpage;
    }
    else
    {
        $endpage = $tempendpage;
    }

    $end = ceil($all/$scale1);

    $Page_view1 = $Page_view2 = $Page_view4 = $Page_view5 = $Page_view6 = "";
    if($cpage1 != 1)
    {
        $Page_view1 =  "<a href='".$_SERVER['PHP_SELF']."?$Get_next&$types=1$frag'><img src='".SYSTEM_PATH."/images/arrow2_left.gif' border='0' align='absmiddle' onclick='show_loading_boardlist();'></a>   ";
    }

    if($startpage > 1)
    {
        $tempnum = $startpage - 1;
        $Page_view2 = "<a href='".$_SERVER['PHP_SELF']."?$Get_next&$types=$tempnum$frag'><img src='".SYSTEM_PATH."/images/arrow1_left.gif' border='0' align='absmiddle' onclick='show_loading_boardlist();'></a>     ";
    }

    for($j=$startpage; $j<=$endpage; $j++)
    {
        if($j == $cpage1)
        {
            $Page_view4 = $Page_view4."&nbsp;<font color=A86A20><b>$j</b></font>&nbsp;";     //current page
        }
        else
        {
            $Page_view4 = $Page_view4."&nbsp;<a href='".$_SERVER['PHP_SELF']."?$Get_next&$types=$j$frag' onclick='show_loading_boardlist();'>$j</a>&nbsp;"; //else page
        }
    }

    if($endpage < $tpage)
    {
        $tempnum = $endpage + 1;
        $Page_view5 = "<a href='".$_SERVER['PHP_SELF']."?$Get_next&$types=$tempnum$frag'><img    src='".SYSTEM_PATH."/images/arrow1_right.gif' border='0' align='absmiddle' onclick='show_loading_boardlist();'></a>"; //back
    }

    if($cpage1 != $end && $all > $scale1)
    {
        $Page_view6 = " <a href='".$_SERVER['PHP_SELF']."?$Get_next&$types=$end$frag'><img    src='".SYSTEM_PATH."/images/arrow2_right.gif' border='0' align='absmiddle' onclick='show_loading_boardlist();'></a>";//last
    }

    return $Page_view1.$Page_view2.$Page_view4.$Page_view5.$Page_view6;
}


//file upload
function Br_Move_Img($hename, $photoName, $directory, $temp, $sFactor, $fileSize)
{
    $enhowak = explode(".",$photoName);
    $imname = time();
    $heName = $hename.$imname;
    $howak = strtolower($enhowak[count($enhowak)-1]);

    //check file size
    if($fileSize > 4000 * 1024)
    {
        echo "<script>alert('Picture file size should be smaller than 4MB');history.go(-1);</script>";
        return false;
        exit;
        //return $saveAll = "";
    }
    else
    {
        if($howak != gif && $howak != jpg && $howak != jpeg && $howak != png && $howak != pdf && $howak != bmp)
        {
            echo "<script>alert('Picture file must be in either JPG or GIF format.');history.go(-1);</script>";
            return false;
            exit;
        }
        else
        {
            $chname = $heName.".".$howak;
            $moiMg=move_uploaded_file($temp, $directory.$chname);
            //file permission
            chmod($directory.$chname, 0777);
            return $saveAll = array($directory, $chname, $hename, $imname);
        }
    }
}

//file upload
function Br_Move_file($hename, $photoName, $directory, $temp, $sFactor, $fileSize)
{
    $enhowak = explode(".",$photoName);
    $imname = time();
    $heName = $hename.$imname;
    $howak = strtolower($enhowak[count($enhowak)-1]);

    //check file type
    if($howak != pdf && $howak != mp3)
    {
        echo "<script>alert('File must be PDF format or MP3 format.');history.go(-1);</script>";
        return false;
        exit;
    }

    //check file size
    if($fileSize > 2000 * 1024){
        echo "<script>alert('File size should be smaller than 2MB');history.go(-1);</script>";
        return false;
        exit;
    }

    $chname = $heName.".".$howak;

    $moiMg=move_uploaded_file($temp, $directory.$chname);
    chmod($directory.$chname, 0777);
    return $saveAll = array($directory, $chname, $hename, $imname);

}

function Br_Del_Img($directory, $file_name)
{
    //thumb
    $sub_file = $directory."sub/".$file_name;
    $original_file = $directory.$file_name;

	//file check
    if(file_exists($sub_file))
    {
        unlink($sub_file);
    }

	if(file_exists($original_file))
    {
        unlink($original_file);
    }
}


function Br_wordcut2($str, $len, $tail="..")
{
    if(strlen($str) > $len)
    {
        for($i=0; $i<$len; $i++) if(ord($str[$i])>127) $i++;
        $str = substr($str, 0, $i);
        $str .= $tail;
    }
    return $str;
}

function Br_wordcut($String, $MaxLen, $ShortenStr="..")
{
    $news_textt = $String;
    $str = $news_textt;
    //
    if(strlen($str) > $MaxLen)
    {
        //$str = preg_replace("/\s+/", ' ', preg_replace("/(\r\n|\r|\n)/", " ", $str));
        $str = preg_replace("/(\r\n|\r|\n)/", " ", $str);
        //
        if(strlen($str) >= $MaxLen)
        {
            //$words=explode(' ',preg_replace("/(\r\n|\r|\n)/"," ",$str));
            $words = preg_split('/( |-|=|_|,|\.)/i', $str, -1, PREG_SPLIT_DELIM_CAPTURE);

            $str = '';
            $i = 0;
            while(strlen($str) + strlen($words[$i]) < $MaxLen)
            {

                $str.=$words[$i];
                $i++;
            }
            //
            $news_textt = trim($str);
            $news_textt .= "..";

        }
    }
    return $news_textt;
}

//make display time
function Br_display_date($full_date)
{
    $return_date = date( "M d, Y", strtotime($full_date));
    return $return_date;
}

function Br_display_time($full_date)
{
    $front_date = date( "M d, Y", strtotime($full_date));
    $end_time = substr($full_date, 11, 2);
    $end_min = substr($full_date, 14, 2);
    if($end_time > 12)
    {
        $end_hour = $end_time - 12;
        $end_noon = "PM";
    }
    else
    {
        $end_hour = $end_time;
        $end_noon = "AM";
    }
    $return_date = $end_hour.":".$end_min." ".$end_noon;

    return $return_date;
}
function Br_date_korean($today_date)
{
    //calculate date
    if($date = substr($today_date, 3, 2) < 10)
    {
        $year = substr($today_date, 5, 4);
        $month = substr($today_date, 0, 2);
        $date = "0".substr($today_date, 3, 2);
        $time = substr($today_date, 11, 7);
    }
    else
    {
        $year = substr($today_date, 6, 4);
        $month = substr($today_date, 0, 2);
        $date = substr($today_date, 3, 2);
        $time = substr($today_date, 11, 7);
    }

    $today_date = $year."-".$month."-".$date;

    switch(date("D", strtotime($today_date)))
    {
        case "Mon" : $date_ko = "월";
        break;
        case "Tue" : $date_ko = "화";
        break;
        case "Wed" : $date_ko = "수";
        break;
        case "Thu" : $date_ko = "목";
        break;
        case "Fri" : $date_ko = "금";
        break;
        case "Sat" : $date_ko = "토";
        break;
        case "Sun" : $date_ko = "일";
        break;
    }

    $result = date("Y.m.d(".$date_ko.")",  strtotime($today_date));
    $result = $result." ".$time;
    return $result;
}

function Br_date_korean_forConverted($today_date)
{
    switch(date("D", strtotime($today_date)))
    {
        case "Mon" : $date_ko = "월";
        break;
        case "Tue" : $date_ko = "화";
        break;
        case "Wed" : $date_ko = "수";
        break;
        case "Thu" : $date_ko = "목";
        break;
        case "Fri" : $date_ko = "금";
        break;
        case "Sat" : $date_ko = "토";
        break;
        case "Sun" : $date_ko = "일";
        break;
    }

    $result = date("Y.m.d (".$date_ko.")",  strtotime($today_date));
    return $result;
}

function Br_datetime_korean_forConverted($today_date)
{
    switch(date("D", strtotime($today_date)))
    {
        case "Mon" : $date_ko = "월";
        break;
        case "Tue" : $date_ko = "화";
        break;
        case "Wed" : $date_ko = "수";
        break;
        case "Thu" : $date_ko = "목";
        break;
        case "Fri" : $date_ko = "금";
        break;
        case "Sat" : $date_ko = "토";
        break;
        case "Sun" : $date_ko = "일";
        break;
    }

    $result = date("Y.m.d (".$date_ko.") H:i",  strtotime($today_date));
    return $result;
}

function Br_date_english($today_date)
{
    $result = date("Y.m.d (D)",  strtotime($today_date));
    return $result;
}

function Br_date_convert($date)
{
    $year = substr($date, 6, 4);
    $month = substr($date, 0, 2);
    $date = substr($date, 3, 2);

    $result = $year."-".$month."-".$date." 00:00:00";

    return $result;

}

function Br_check_email($email)
{
    if (eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$", $email))
    {
        $str='1';
    }
    else
    {    //uncorrect
        $str='0';
    }
    return $str;
}

function Br_CheckMobileAccess($level, $current_link = false)
{
    if(!$level)
    {
        echo "<script>alert('Incorrect Access Information.');history.go(-1);</script>";
    }

    $MemberLevel = new Member();
    $MemberLevel->ReadMember($_SESSION['memberId']);
    if($MemberLevel->memberType == "admin")
    {
        $member_level = "10";
    }
    else if($MemberLevel->memberType == "manager")
    {
        $member_level = "3";
    }
    else if($MemberLevel->memberType == "regular")
    {
        $member_level = "2";
    }
    else
    {
        $member_level = "1";
    }

    if($level == "2" && $member_level == "1")
    {//member only
         echo "<script>location.replace('".SYSTEM_PATH."/mobile/frame.php?main=login&current_link=".$current_link."');</script>";
         exit;
    }
    else if($level == "3" && ($member_level == "1" || $member_level == "2"))
    {//admin only
         echo "<script>alert('이 페이지는 관리자 이상만 접근 할 수 있습니다.');</script>";
         echo "<script>location.replace('".SYSTEM_PATH."/mobile/frame.php');</script>";
         exit;
    }
    else if($level == "10" && ($member_level == "1" || $member_level == "2" || $member_level == "3"))
    {//admin only
         echo "<script>alert('이 페이지는 개발자 혹은 최종 관리자만 접근 할 수 있습니다.');</script>";
         echo "<script>location.replace('".SYSTEM_PATH."/mobile/frame.php');</script>";
         exit;
    }
}

function Br_CheckPageAccess($level, $current_link = false)
{
    if(!$level)
    {
        echo "<script>alert('Incorrect Access Information.');history.go(-1);</script>";
    }

    $MemberLevel = new Member();
    $MemberLevel->ReadMember($_SESSION['memberId']);
    if($MemberLevel->memberType == "admin")
    {
        $member_level = "10";
    }
    else if($MemberLevel->memberType == "manager")
    {
        $member_level = "3";
    }
    else if($MemberLevel->memberType == "regular")
    {
        $member_level = "2";
    }
    else
    {
        $member_level = "1";
    }

    if($level == "2" && $member_level == "1")
    {//member only
         echo "<script>location.replace('".SYSTEM_PATH."/main/frame.php?main=member&sub=login&current_link=".$current_link."');</script>";
         exit;
    }
    else if($level == "3" && ($member_level == "1" || $member_level == "2"))
    {//admin only
         echo "<script>alert('이 페이지는 관리자 이상만 접근 할 수 있습니다.');</script>";
         echo "<script>location.replace('".SYSTEM_PATH."/main/frame.php');</script>";
         exit;
    }
    else if($level == "10" && ($member_level == "1" || $member_level == "2" || $member_level == "3"))
    {//admin only
//         echo "<script>alert('이 페이지는 개발자 혹은 최종 관리자만 접근 할 수 있습니다.');</script>";
         echo "<script>alert('You are not permit.');</script>";
         echo "<script>location.replace('".SYSTEM_PATH."/main/frame.php');</script>";
         exit;
    }
}

function Br_Image_Update($file_name, $old_image, $photo_name, $photo_size, $photo_temp, $file_path)
{
    $enhowak = explode(".",$photoName);
    $howak = strtolower($enhowak[count($enhowak)-1]);

    if($photo_name && !$old_image)
    {//insert new
        $photo1 = Br_Move_Img($file_name, $photo_name, SYSTEM_PATH."/".$file_path."/", $photo_temp,"300", $photo_size);
        $imageFile1 = $photo1[1];

        //make thumb
        $file_fullpath =  SYSTEM_PATH."/".$file_path."/".$imageFile1;
        $sub_fullpath =  SYSTEM_PATH."/".$file_path."/sub/".$imageFile1;
        //$sub_fullpath2 =  SYSTEM_PATH."/".$file_path."/sub2/".$imageFile1;
        //$sub_fullpath3 =  SYSTEM_PATH."/".$file_path."/sub3/".$imageFile1;


        if($howak != "pdf" || $howak != "swf")
        {
            br_MakeThumbnail($file_fullpath, $sub_fullpath);
            //br_MakeThumbnail2($file_fullpath, $sub_fullpath2);
            //br_MakeThumbnail3($file_fullpath, $sub_fullpath3);

        }
    }
    else if($photo_name && $old_image)
    {//update - insert new and delete old
        $photo1 = Br_Move_Img($file_name, $photo_name, SYSTEM_PATH."/".$file_path."/", $photo_temp, "300", $photo_size);
        Br_Del_Img(SYSTEM_PATH."/".$file_path."/", $old_image);
        $imageFile1 = $photo1[1];

        //make thumb
        $file_fullpath = SYSTEM_PATH."/".$file_path."/".$imageFile1;
        $sub_fullpath = SYSTEM_PATH."/".$file_path."/sub/".$imageFile1;
        //$sub_fullpath2 =  SYSTEM_PATH."/".$file_path."/sub2/".$imageFile1;
        //$sub_fullpath3 =  SYSTEM_PATH."/".$file_path."/sub3/".$imageFile1;

        if($howak != "pdf" || $howak != "swf")
        {
            br_MakeThumbnail($file_fullpath, $sub_fullpath);
            //br_MakeThumbnail2($file_fullpath, $sub_fullpath2);
            //br_MakeThumbnail3($file_fullpath, $sub_fullpath3);

        }
    }
    else if(!$photo_name && $old_image)
    {//keep old picture
        $imageFile1 = $old_image;
    }
    else { $imageFile1 = ""; }

    return $imageFile1;
}



function Br_Image_Upload($photo_name, $photo_size, $photo_temp, $file_path)
{
    $enhowak = explode(".",$photo_name);
    $howak = strtolower($enhowak[count($enhowak)-1]);

	$photo1 = Br_Move_Img($file_name, $photo_name, SYSTEM_PATH."/".$file_path."/", $photo_temp,"300", $photo_size);
	$imageFile1 = $photo1[1];

	$file_fullpath =  SYSTEM_PATH."/".$file_path."/".$imageFile1;
	$sub_fullpath =  SYSTEM_PATH."/".$file_path."/sub/".$imageFile1;


	$chname = $heName.".".$howak;
	$moiMg=move_uploaded_file($temp, $directory.$chname);
	//file permission
	chmod($directory.$chname, 0777);
//	return $saveAll = array($directory, $chname, $hename, $imname);
     return $imageFile1;
}



//make date
function getDateToday($write_time)
{
    $compare_date = date("Y-m-d", strtotime($write_time));
    $today_date = date("Y-m-d");
    if($compare_date == $today_date)
    {
        $result = substr($write_time, 10, 6);
    }
    else
    {
        $result = date("Y-m-d", strtotime($write_time));
    }

    if($result)
    {
        return $result;
    }
    return false;
}
//make country
function Br_MakeCountry($type)
{
    $type_que="select * from country order by name";
    $type_sel=Br_selectQuery($type_que);
    while (( $rows=Br_fatch_arryQuery($type_sel)) != false ) {
        if(empty($type))
            echo "<option value='".$rows["code"]."'>".$rows["name"]."</option>";
        else {
            $selected= ($rows['code']==$type || $rows['name'] == $type) ? 'selected' : "";
            echo "<option value='".$rows["code"]."' ".$selected.">".$rows["name"]."</option>";
        }
    }
}

//location
function Br_MakeLocation($type)
{
    $type_que="select * from br_location order by rank asc";
    $type_sel=Br_selectQuery($type_que);
    while (( $rows=Br_fatch_arryQuery($type_sel)) != false ) {
        if(empty($type))
            echo "<option value='".$rows["code"]."'>".Br_iconv($rows["name"])."</option>";
        else {
            $selected= ($rows['code']==$type || $rows['name'] == $type) ? 'selected' : "";
            echo "<option value='".$rows["code"]."' ".$selected.">".Br_iconv($rows["name"])."</option>";
        }
    }
}
function Br_ReadLocation($code)
{
    $que = "select * from br_location where code = '$code'";
    $sel = Br_selectQuery($que);
    $fat = Br_fatch_arryQuery($sel);
    $result = $fat['name'];

    if($result)
    {
        return $result;
    }
    return false;
}

//type
function Br_MakeType($boardId, $type = false)
{
    $type_que="select * from br_type where boardId = '$boardId' order by rank asc";
    $type_sel=Br_selectQuery($type_que);
    while (( $rows=Br_fatch_arryQuery($type_sel)) != false ) {
        if(empty($type))
            echo "<option value='".$rows["code"]."'>".Br_iconv($rows["name"])."</option>";
        else {
            $selected= ($rows['code']==$type || $rows['name'] == $type) ? 'selected' : "";
            echo "<option value='".$rows["code"]."' ".$selected.">".Br_iconv($rows["name"])."</option>";
        }
    }
}
function Br_ReadType($code)
{
    $que = "select * from br_type where code = '$code'";
    $sel = Br_selectQuery($que);
    $fat = Br_fatch_arryQuery($sel);
    $result = $fat['name'];

    if($result)
    {
        return $result;
    }
    return false;
}
//property type
function Br_MakePropertyType($type)
{
    $type_que="select * from br_propertytype order by rank asc";
    $type_sel=Br_selectQuery($type_que);
    while (( $rows=Br_fatch_arryQuery($type_sel)) != false ) {
        if(empty($type))
            echo "<option value='".$rows["code"]."'>".$rows["name"]."</option>";
        else {
            $selected= ($rows['code']==$type || $rows['name'] == $type) ? 'selected' : "";
            echo "<option value='".$rows["code"]."' ".$selected.">".$rows["name"]."</option>";
        }
    }
}
function Br_ReadPropertyType($code)
{
    $que = "select * from br_propertytype where code = '$code'";
    $sel = Br_selectQuery($que);
    $fat = Br_fatch_arryQuery($sel);
    $result = $fat['name'];

    if($result)
    {
        return $result;
    }
    return false;
}

//CAR COMPANY
function Br_MakeCarCompany($type)
{
    $type_que="select * from br_carcompany order by code asc";
    $type_sel=Br_selectQuery($type_que);
    while (( $rows=Br_fatch_arryQuery($type_sel)) != false ) {
        if(empty($type))
            echo "<option value='".$rows["code"]."'>".$rows["name"]."</option>";
        else {
            $selected= ($rows['code']==$type || $rows['name'] == $type) ? 'selected' : "";
            echo "<option value='".$rows["code"]."' ".$selected.">".$rows["name"]."</option>";
        }
    }
}
function Br_ReadCarCompany($code)
{
    $que = "select * from br_carcompany where code = '$code'";
    $sel = Br_selectQuery($que);
    $fat = Br_fatch_arryQuery($sel);
    $result = $fat['name'];

    if($result)
    {
        return $result;
    }
    return false;
}

//CAR COMPANY
function Br_MakeCarColor($type)
{
    $type_que="select * from br_carcolor order by rank asc";
    $type_sel=Br_selectQuery($type_que);
    while (( $rows=Br_fatch_arryQuery($type_sel)) != false ) {
        if(empty($type))
            echo "<option value='".$rows["code"]."'>".$rows["name"]."</option>";
        else {
            $selected= ($rows['code']==$type || $rows['name'] == $type) ? 'selected' : "";
            echo "<option value='".$rows["code"]."' ".$selected.">".$rows["name"]."</option>";
        }
    }
}
function Br_ReadCarColor($code)
{
    $que = "select * from br_carcolor where code = '$code'";
    $sel = Br_selectQuery($que);
    $fat = Br_fatch_arryQuery($sel);
    $result = $fat['name'];

    if($result)
    {
        return $result;
    }
    return false;
}

function br_MakeThumbnail($source_path, $thumbnail_path)
{
    chmod($source_path, 0777);

    $width = "30";
    $height = "30";

    list($img_width,$img_height, $type) = getimagesize($source_path);
    if ($type!=1 && $type!=2 && $type!=3 && $type!=15) return;
    if ($type==1) $img_sour = imagecreatefromgif($source_path);
    else if ($type==2 ) $img_sour = imagecreatefromjpeg($source_path);
    else if ($type==3 ) $img_sour = imagecreatefrompng($source_path);
    else if ($type==15) $img_sour = imagecreatefromwbmp($source_path);
    if ($img_width > $img_height) {
        $w = round($height*$img_width/$img_height);
        $h = $height;
        $x_last = round(($w-$width)/2);
        $y_last = 0;
    } else {
        $w = $width;
        $h = round($width*$img_height/$img_width);
        $x_last = 0;
        $y_last = round(($h-$height)/2);
    }
    if ($img_width < $width && $img_height < $height) {
        $img_last = imagecreatetruecolor($width, $height);
        $x_last = round(($width - $img_width)/2);
        $y_last = round(($height - $img_height)/2);

        //$white = imagecolorallocate($img_last,255,255,255);
        //imagefill($img_last, 0, 0, $white);

        imagecopy($img_last,$img_sour,$x_last,$y_last,0,0,$w,$h);
        imagedestroy($img_sour);

        $white = imagecolorallocate($img_last,255,255,255);
        imagefill($img_last, 0, 0, $white);
    } else {
        $img_dest = imagecreatetruecolor($w,$h);
        imagecopyresampled($img_dest, $img_sour,0,0,0,0,$w,$h,$img_width,$img_height);
        $img_last = imagecreatetruecolor($width,$height);

        $white = imagecolorallocate($img_last,255,255,255);
        imagefill($img_last, 0, 0, $white);

        imagecopy($img_last,$img_dest,0,0,$x_last,$y_last,$w,$h);
        imagedestroy($img_dest);
        //fill white
        //$white = imagecolorallocate($img_last,255,255,255);
        //imagefill($img_last, 0, 0, $white);
    }
    if ($thumbnail_path)
    {
        if ($type==1) imagegif($img_last, $thumbnail_path, 100);
        else if ($type==2 ) imagejpeg($img_last, $thumbnail_path, 100);
        else if ($type==3 ) imagepng($img_last, $thumbnail_path, 100);
        else if ($type==15) imagebmp($img_last, $thumbnail_path, 100);
    }
    else
    {
        if ($type==1) imagegif($img_last);
        else if ($type==2 ) imagejpeg($img_last);
        else if ($type==3 ) imagepng($img_last);
        else if ($type==15) imagebmp($img_last);
    }
    imagedestroy($img_last);
}

function br_MakeThumbnail2($source_path, $thumbnail_path)
{
    $width = "226";
    $height = "170";

    list($img_width,$img_height, $type) = getimagesize($source_path);
    if ($type!=1 && $type!=2 && $type!=3 && $type!=15) return;
    if ($type==1) $img_sour = imagecreatefromgif($source_path);
    else if ($type==2 ) $img_sour = imagecreatefromjpeg($source_path);
    else if ($type==3 ) $img_sour = imagecreatefrompng($source_path);
    else if ($type==15) $img_sour = imagecreatefromwbmp($source_path);
    if ($img_width > $img_height) {
        $w = round($height*$img_width/$img_height);
        $h = $height;
        $x_last = round(($w-$width)/2);
        $y_last = 0;
    } else {
        $w = $width;
        $h = round($width*$img_height/$img_width);
        $x_last = 0;
        $y_last = round(($h-$height)/2);
    }
    if ($img_width < $width && $img_height < $height) {
        $img_last = imagecreatetruecolor($width, $height);
        $x_last = round(($width - $img_width)/2);
        $y_last = round(($height - $img_height)/2);

        $white = imagecolorallocate($img_last,255,255,255);
        imagefill($img_last, 0, 0, $white);
        imagecopy($img_last,$img_sour,$x_last,$y_last,0,0,$w,$h);
        imagedestroy($img_sour);
        $white = imagecolorallocate($img_last,255,255,255);
        imagefill($img_last, 0, 0, $white);
    } else {
        $img_dest = imagecreatetruecolor($w,$h);
        imagecopyresampled($img_dest, $img_sour,0,0,0,0,$w,$h,$img_width,$img_height);
        $img_last = imagecreatetruecolor($width,$height);

        $white = imagecolorallocate($img_last,255,255,255);
        imagefill($img_last, 0, 0, $white);

        imagecopy($img_last,$img_dest,0,0,$x_last,$y_last,$w,$h);
        imagedestroy($img_dest);
        //fill white
        $white = imagecolorallocate($img_last,255,255,255);
        imagefill($img_last, 0, 0, $white);
    }
    if ($thumbnail_path) {
        if ($type==1) imagegif($img_last, $thumbnail_path, 80);
        else if ($type==2 ) imagejpeg($img_last, $thumbnail_path, 80);
        else if ($type==3 ) imagepng($img_last, $thumbnail_path, 80);
        else if ($type==15) imagebmp($img_last, $thumbnail_path, 80);
    } else {
        if ($type==1) imagegif($img_last);
        else if ($type==2 ) imagejpeg($img_last);
        else if ($type==3 ) imagepng($img_last);
        else if ($type==15) imagebmp($img_last);
    }
    imagedestroy($img_last);
}
function br_MakeThumbnail3($source_path, $thumbnail_path)
{
    $width = "450";
    $height = "338";

    list($img_width,$img_height, $type) = getimagesize($source_path);
    if ($type!=1 && $type!=2 && $type!=3 && $type!=15) return;
    if ($type==1) $img_sour = imagecreatefromgif($source_path);
    else if ($type==2 ) $img_sour = imagecreatefromjpeg($source_path);
    else if ($type==3 ) $img_sour = imagecreatefrompng($source_path);
    else if ($type==15) $img_sour = imagecreatefromwbmp($source_path);
    if ($img_width > $img_height) {
        $w = round($height*$img_width/$img_height);
        $h = $height;
        $x_last = round(($w-$width)/2);
        $y_last = 0;
    } else {
        $w = $width;
        $h = round($width*$img_height/$img_width);
        $x_last = 0;
        $y_last = round(($h-$height)/2);
    }
    if ($img_width < $width && $img_height < $height) {
        $img_last = imagecreatetruecolor($width, $height);
        $x_last = round(($width - $img_width)/2);
        $y_last = round(($height - $img_height)/2);

        $white = imagecolorallocate($img_last,255,255,255);
        imagefill($img_last, 0, 0, $white);
        imagecopy($img_last,$img_sour,$x_last,$y_last,0,0,$w,$h);
        imagedestroy($img_sour);
        $white = imagecolorallocate($img_last,255,255,255);
        imagefill($img_last, 0, 0, $white);
    } else {
        $img_dest = imagecreatetruecolor($w,$h);
        imagecopyresampled($img_dest, $img_sour,0,0,0,0,$w,$h,$img_width,$img_height);
        $img_last = imagecreatetruecolor($width,$height);

        $white = imagecolorallocate($img_last,255,255,255);
        imagefill($img_last, 0, 0, $white);
        imagecopy($img_last,$img_dest,0,0,$x_last,$y_last,$w,$h);
        imagedestroy($img_dest);
        //fill white
        $white = imagecolorallocate($img_last,255,255,255);
        imagefill($img_last, 0, 0, $white);
    }
    if ($thumbnail_path) {
        if ($type==1) imagegif($img_last, $thumbnail_path, 80);
        else if ($type==2 ) imagejpeg($img_last, $thumbnail_path, 80);
        else if ($type==3 ) imagepng($img_last, $thumbnail_path, 80);
        else if ($type==15) imagebmp($img_last, $thumbnail_path, 80);
    } else {
        if ($type==1) imagegif($img_last);
        else if ($type==2 ) imagejpeg($img_last);
        else if ($type==3 ) imagepng($img_last);
        else if ($type==15) imagebmp($img_last);
    }
    imagedestroy($img_last);
}

function go_sendmail($docId, $docType, $docSeq, $subject, $contents, $Idseq)
{
	$query = "SELECT DocID, DocType, DocSeq, ApprovalUserSeq, ApprovalUserID From ApprovalList ".
			"WHERE DocID = '$docId' AND DocType = $docType AND DocSeq = $docSeq AND ApprovalUserSeq = $Idseq ";
	$rst = mssql_query($query);
	$row = mssql_fetch_array($rst);
	$user = $row['ApprovalUserID'];

	if($user) {
		$o_user = $_SESSION['memberID'];

		$query = "SELECT memID, memName, memEmail From Member ".
				"WHERE memID = '$o_user' ";
		$rst = mssql_query($query);
		$row = mssql_fetch_array($rst);
		$fromEmail = $row['memEmail'];

		$fromName = Br_iconv($_SESSION['memberName']);

		$query = "SELECT memID, memName, memEmail From Member ".
				"WHERE memID = '$user' ";
		$query_result = mssql_query($query);
		$result = mssql_fetch_array($query_result);

//		if(isHangle($result['memName'])) {
			$toName = Br_iconv($result['memName']);
//		} else {
//			$toName = $result['memName'];
//		}
		$toEmail = $result['memEmail'];

		if($toEmail) {
			sendMail("경영지원실-전산팀", "itdiv@hannamsm.com", $toName, $toEmail, $subject, $contents, $isDebug=0);
//			sendMail($fromName, $fromEmail, $toName, $toEmail, $subject, $contents, $isDebug=0);
			if($Idseq == 1) {
//				sendMail("경영지원실-전산팀", "itdiv@hannamsm.com", $toName, "tbrotherscanada001@gmail.com", $subject, $contents, $isDebug=0);
//				sendMail("경영지원실-전산팀", "itdiv@hannamsm.com", $toName, "hkterrykim001@gmail.com", $subject, $contents, $isDebug=0);
			}
		}
	}
}

?>
