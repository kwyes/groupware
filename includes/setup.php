<?
// session connection
session_cache_limiter("nocache, must-revalidate");
session_start();
$timeout = 3600;	// second
if(isset($_SESSION['start_time'])) {
	$elapsed_time = time() - $_SESSION['start_time'];
	if($elapsed_time >= $timeout) {
		echo "<script>alert('LOGOUT');location.href='?page=logout';</script>";
	}
}

// DB connection
include_once "includes/general.php";
?>