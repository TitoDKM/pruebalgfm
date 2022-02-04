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
if(isset($_POST['register_form'])) $Site->tryRegister();
?><!DOCTYPE html>
<html lang="es">
	<head>
		<!--[if IE]>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<![endif]-->
		<meta charset="UTF-8">
		<meta name="language" content="es-ES" />
		<title>dBlog - Registro</title>
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
								<h3 class="blue-underline">Crear nueva cuenta</h3>
							</div>
							<div class="form-content">
								<?php if(!empty($_SESSION['register_error'])) { ?><div class="alert alert-danger" role="alert"><?=$_SESSION['register_error'];?></div><?php unset($_SESSION['register_error']); } ?>
								<form method="POST">
									<div class="mt-3">
										<label class="form-label">Correo electrónico</label>
										<input placeholder="Introduce tu correo electrónico" required name="email" type="email" class="form-control">
									</div>
									<div class="mt-3">
										<label class="form-label">Contraseña</label>
										<input placeholder="Introduce tu contraseña" required name="password" type="password" class="form-control">
									</div>
									<div class="mt-3">
										<label class="form-label">Repite tu contraseña</label>
										<input placeholder="Repite tu contraseña" required name="password2" type="password" class="form-control">
									</div>
									<div class="mt-3">
										<label class="form-label">Nombre</label>
										<input placeholder="Nombre real" required name="name" type="text" class="form-control">
									</div>
									<div class="mt-3">
										<label class="form-label">Apellidos</label>
										<input placeholder="Primer y/o segundo apellido" required name="lastname" type="text" class="form-control">
									</div>
									<div class="mt-3">
										<label class="form-label">Ubicación</label>
										<input placeholder="País o ciudad, según tú privacidad" required name="location" type="text" class="form-control">
									</div>
									<button type="submit" name="register_form" class="form-btn mt-3 btn">Finalizar registro</button>
								</form>
							</div>
							<div class="form-header mt-4">
								<h3>O si lo prefieres..</h3>
								<button class="form-btn btn" onClick="goTo('<?=$googleClient->createAuthUrl();?>');"><i class="bi bi-google"></i> Regístrate con Google</button>
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