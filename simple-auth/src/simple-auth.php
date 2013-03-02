<?php
	
	if(!isset($_SESSION['roles']))
		$_SESSION['roles'] = array();
	
	function set_roles($roles) {
		$_SESSION['roles'] = $roles;	
	}
	
	function check_login($redirect = FALSE) {
		if($redirect && !$_SESSION['roles']) {
			header( 'Location: ' . LOGIN_URL );
			exit;
		}
		
		return $_SESSION['roles'] ? true : false;
	}

	function check_role($roles, $redirect = FALSE) {
		$has_role = false;
		foreach($roles as $role)
			if(in_array($_SESSION['roles'], $role)) {
				$has_role = true;
				break;
			}
	
		if($redirect && !$has_role) {
			header( 'Location: ' . LOGIN_URL );
			exit;
		}

		return $has_role;
	}

?>