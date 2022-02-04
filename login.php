<?php
require_once 'system/Core.php';
$Site->checkLogged(false);

if(isset($_GET['code'])) {
	$token = $googleClient->fetchAccessTokenWithAuthCode($_GET['code']);
	$googleClient->setAccessToken($token['access_token']);

	$google_oauth = new Google_Service_Oauth2($googleClient);
	$google_account_info = $google_oauth->userinfo->get();
	$email =  $google_account_info->email;
	$name =  $google_account_info->name;

	$Site->tryGoogleLogin($google_account_info);
}
if(isset($_POST['login_form'])) $Site->tryLogin();
?><!DOCTYPE html>
<html lang="es">
	<head>
		<!--[if IE]>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<![endif]-->
		<meta charset="UTF-8">
		<meta name="language" content="es-ES" />
		<title>dBlog - Iniciar sesión</title>
		<?php include TMPF.'head.php'; ?>
		<script src="https://accounts.google.com/gsi/client" async defer></script>
	</head>
	<body>
		<div class="web-container">
			<?php include TMPF.'nav.php'; ?>
			<div class="container">
				<div class="row">
					<div class="align-self-center col">
						<div class="form-container">
							<div class="form-header">
								<h3 class="blue-underline">Inicio de sesión</h3>
							</div>
							<div class="form-content">
								<?php if(!empty($_SESSION['login_error'])) { ?><div class="alert alert-danger" role="alert"><?=$_SESSION['login_error'];?></div><?php unset($_SESSION['login_error']); } ?>
								<form method="POST">
									<div class="mt-3">
										<label class="form-label">Correo electrónico</label>
										<input placeholder="Introduce tu correo electrónico" required name="email" type="email" class="form-control">
									</div>
									<div class="mt-3">
										<label class="form-label">Contraseña</label>
										<input placeholder="Introduce tu contraseña" required name="password" type="password" class="form-control">
									</div>
									<button type="submit" name="login_form" class="form-btn mt-3 btn">Iniciar sesión</button>
								</form>
							</div>
							<div class="form-header mt-4">
								<h3>O si lo prefieres..</h3>
								<button class="form-btn btn" onClick="goTo('<?=$googleClient->createAuthUrl();?>');"><i class="bi bi-google"></i> Iniciar sesión con Google</button>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php include TMPF.'footer.php'; ?>
		</div>
		<?php include TMPF.'scripts.php'; ?>
	</body>
</html>