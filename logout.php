<?php
	unset($_SESSION['memberID']); 
	session_destroy(); 
?>
	<div class="login_wrapper">
		<p class="signout_msg">로그아웃 되었습니다.</p>
	</div>
	
	<script type="text/javascript">
			location.href="?page=logout";
	</script>
