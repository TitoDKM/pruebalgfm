<?php
require_once 'system/Core.php';

if($_SESSION['login_type'] === "GOOGLE") {
	$googleClient->revokeToken();
}

session_destroy();
header("Location: /");
?>