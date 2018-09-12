<?
$page = ($_GET['page']) ? $_GET['page'] : $_POST['page'];
$menu = ($_GET['menu']) ? $_GET['menu'] : $_POST['menu'];
$sub = ($_GET['sub']) ? $_GET['sub'] : $_POST['sub'];
$url = $_SERVER['REQUEST_URI'];

include_once "includes/setup.php";
if($page != "userSearch" && $page != "fUserSearch" && $page != "addLastUser" && $page != "faddLastUser" && $page != "itemSpotList") {
	include_once "includes/frame_header.php";
}

if(!isset($_SESSION['memberID'])) {
	if($page == "login_registration") {
		include_once "login_registration.php";
	} else {
		include_once "login.php";
	}
} else if(isset($_SESSION['memberID'])) {
	switch($page) {
		default : {
//			include_once "includes/frame_header.php";
			include_once "error.php";
			break;
		}
		case "" : {
			include_once "login.php";
			break;
		}
		case "login" : {
			include_once "login.php";
			break;
		}
		case "logout" : {
			include_once 'logout.php';
			break;
		}
		case "userSearch" : {
			include_once 'iframe_userList.php';
			break;
		}
		case "fUserSearch" : {
			include_once 'iframe_fUserList.php';
			break;
		}
		case "addLastUser" : {
			include_once 'iframe_userList.php';
			break;
		}
		case "faddLastUser" : {
			include_once 'iframe_fUserList.php';
			break;
		}
		case "itemSpotList" : {
			include_once 'iframe_itemSpotCheck.php';
			break;
		}
		case "e_doc" : {
			include_once 'includes/leftmenu_e_doc.php';

			switch($menu) {
				default : {
					include_once 'e_doc_main.php';
					break;
				}
				case "" : {
					include_once 'e_doc_main.php';
					break;
				}
				case "personalFolder" : {
					include_once 'personal_folder.php';
					break;
				}
				case "all" : {
					include_once 'all_doc_list.php';
					break;
				}
				case "view_all" : {
					include_once 'e_doc_view.php';
					break;
				}
				case "expense_all" : {
					include_once 'all_expense_list.php';
					break;
				}
				// 결재 문서
				case "form" : {
					switch($sub) {
						case "proposal" : {
							include_once 'e_doc_proposal.php';
							break;
						}
						case "expense" : {
							include_once 'e_doc_voucher.php';
							break;
						}
						case "expense_v" : {
							include_once 'view_document.php';
							break;
						}
						case "helpful" : {
							include_once 'e_doc_cooperation.php';
							break;
						}
						case "item_spot_check" : {
							include_once 'e_doc_itemSpotCheck.php';
							break;
						}
						case "sales_journal" : {
							include_once 'e_doc_salesJournal.php';
							break;
						}
						case "businesstrip" : {
							include_once 'e_doc_businesstrip.php';
							break;
						}
					}
					break;
				}

				// 받은 결재 문서
				case "receive" : {
					switch($sub) {
						case "wait" : {
							include_once 'receive_wait_list.php';
							break;
						}
						case "done" : {
							include_once 'receive_done_list.php';
							break;
						}
						case "receive_folder" : {
							include_once 'receive_done_list.php';
							break;
						}
						case "view_wait" :
						case "view_done" :
						case "view_folder" :
							include_once 'e_doc_view.php';
							break;
					}
					break;
				}

				// 올린 결재 문서
				case "offer" : {
					switch($sub) {
						case "save" : {
							include_once 'offer_save_list.php';
							break;
						}
						case "recovery" : {
							include_once 'offer_recovery_list.php';
							break;
						}
						case "submit" : {
							include_once 'offer_submit_list.php';
							break;
						}
						case "reject" : {
							include_once 'offer_reject_list.php';
							break;
						}
						case "complete" : {
							include_once 'offer_complete_list.php';
							break;
						}
						case "offer_folder" : {
							include_once 'offer_complete_list.php';
							break;
						}
						case "view_save" :
							include_once 'e_doc_update.php';
							break;
						case "view_recovery" :
						case "view_submit" :
						case "view_reject" :
						case "view_complete" :
						case "view_folder" :
							include_once 'e_doc_view.php';
							break;
						case "edit_save" :
						case "edit_recovery" :
							include_once 'e_doc_update.php';
							break;
						case "result_view" :
							include_once 'e_doc_brief.php';
							break;
					}
					break;
				}
				case "dept_receive" : {
					switch($sub) {
						case "list" : {
							include_once 'receive_dept_list.php';
							break;
						}
						case "view_dept" : {
							include_once 'e_doc_view.php';
							break;
						}
					}
					break;
				}
				case "itemSpotCheck" : {
					switch($sub) {
						case "itemSpotCheckList" : {
							include_once 'itemSpotCheck_list.php';
							break;
						}
						case "itemSpotCheckCheck" : {
							include_once 'e_doc_itemSpotCheck_check.php';
							break;
						}
						case "itemSpotCheckView" : {
							include_once 'e_doc_itemSpotCheck_view.php';
							break;
						}
					}
					break;
				}
			}
			break;
		}
		case "community" : {
			include_once 'includes/leftmenu_board.php';
			switch($menu) {
				case "note" : {
					switch($sub) {
						case "write" : {
							include_once 'board.php';
							break;
						}
						case "view" : {
							include_once 'board_view.php';
							break;
						}
						case "edit" : {
							include_once 'board_update.php';
							break;
						}
						default : {
							include_once 'community.php';
							break;
						}
					}
					break;
				}
				case "album" : {
					switch($sub) {
						default : {
							include_once 'album_list.php';
							break;
						}
						case "list" : {
							include_once 'album_list.php';
							break;
						}
						case "view" : {
							include_once 'album_view.php';
							break;
						}
						case "write" : {
							include_once 'album_write.php';
							break;
						}
						case "update" :
							include_once 'album_update.php';
							break;
					}
					break;
				}
				case "free" : 
				case "help" : 
				{
					switch($sub) {
						case "view" : {
							include_once 'free_view.php';
							break;
						}
						case "write" : {
							include_once 'freeboard.php';
							break;
						}
						case "edit" : {
							include_once 'freeboard_update.php';
							break;
						}
						default : {
							include_once 'free.php';
							break;
						}
					}
					break;
				}
				default : {
					include_once "community.php";
					break;
				}
			}
			break;
		}
		case "doc" : {
			include_once 'includes/leftmenu_docmanage.php';
			switch($menu) {
				case "upload" : {
					include_once 'docform.php';
					break;
				}
				default : {
					include_once 'docmanage.php';
					break;
				}
			}
			break;
		}
		case "meminfor" : {
			include_once 'includes/leftmenu_info.php';
			switch($menu) {
				default : {
					include_once 'meminfor.php';
					break;
				}
				case "inq" : {
					include_once 'meminfor.php';
					break;
				}
				case "up" : {
					include_once 'meminfor_up.php';
					break;
				}
				case "pwd" : {
					include_once 'meminfor_pwd.php';
					break;
				}
			}
			break;
		}

		case "hr" : {
			if($_SESSION['hr_level']) {
				include_once 'includes/leftmenu_humanResource.php';
				switch($menu) {
					default : {
						include_once 'hr_list.php';
						break;
					}
					case "list" : {
						include_once 'hr_list.php';
						break;
					}
					case "view" : {
						include_once 'hr_view.php';
						break;
					}
					case "new" : {
						include_once 'hr_new.php';
						break;
					}
					case "modify" : {
						include_once 'hr_modify.php';
						break;
					}
				}
				break;
			} else {
				echo "<script>alert('접근 권한이 없습니다.')</script>";
				echo "<script>location.href='?page=e_doc'</script>";
				break;
			}
		}

		case "property" : {
			if($_SESSION['memberLevel'] == 1 || $_SESSION['memberID'] == "jenniferlee" || $_SESSION['memberID'] == "hjmo" || $_SESSION['memberID'] == "jaylim" || $_SESSION['memberID'] == "jkwoo777" || $_SESSION['memberID'] == "nhk") {

			include_once 'includes/leftmenu_property.php';
			switch($menu) {
				default : {
					include_once 'property_write.php';
					break;
				}
				case "write" : {
					include_once 'property_write.php';
					break;
				}
				case "history" : {
					include_once 'property_history.php';
					break;
				}
				case "view" : {
					include_once 'property_view.php';
					break;
				}
				case "modifyCL" : {
					//include_once 'property_test.php';
					include_once 'property_modifyCL.php';
					break;
				}
			}
			break;
			}else {
				echo "<script>alert('접근 권한이 없습니다.')</script>";
				echo "<script>location.href='?page=e_doc'</script>";
				break;
			}
		}

		case "admin" : {
			include_once 'includes/leftmenu_admin.php';
			switch($menu) {
				default : {
					include_once 'admin_userRegistration.php';
					break;
				}
				case "userRegistration" : {
					include_once 'admin_userRegistration.php';
					break;
				}
				case "userManagement" : {
					include_once 'admin_userManagement.php';
					break;
				}
				case "userManagement_view" : {
					include_once 'admin_userManagement_view.php';
					break;
				}
				case "docManagement" : {
					include_once 'admin_docManagement.php';
					break;
				}
				case "docManagement_view" : {
					include_once 'admin_docManagement_view.php';
					break;
				}
			}
			break;
		}

	}
}

if($page != "userSearch" && $page != "fUserSearch" && $page != "addLastUser" && $page != "faddLastUser" && $page != "itemSpotList") {
	include_once "includes/frame_footer.php";
}
include_once 'includes/db_disconnect.php';
?>