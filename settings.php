<?php
require_once 'system/Core.php';
$Site->checkLogged(true);
if(isset($_POST['settings_form'])) $Site->saveProfile();
?><!DOCTYPE html>
<html lang="es">
	<head>
		<!--[if IE]>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<![endif]-->
		<meta charset="UTF-8">
		<meta name="language" content="es-ES" />
		<title>dBlog - Editar mi perfil</title>
		<?php include TMPF.'head.php'; ?>
	</head>
	<body>
		<div class="web-container">
			<?php include TMPF.'nav.php'; ?>
			<div class="container">
				<div class="row">
					<div class="align-self-center col">
						<div class="form-container">
							<div class="form-header">
								<h3 class="blue-underline">Mi perfil</h3>
							</div>
							<div class="form-content">
								<?php if(!empty($_SESSION['settings_error'])) { ?><div class="alert alert-danger" role="alert"><?=$_SESSION['settings_error'];?></div><?php unset($_SESSION['settings_error']); } ?>
								<?php if(!empty($_SESSION['settings_success'])) { ?><div class="alert alert-success" role="alert"><?=$_SESSION['settings_success'];?></div><?php unset($_SESSION['settings_success']); } ?>
								<form method="POST">
									<div class="row">
										<div class="col">
											<div class="mt-3">
												<label class="form-label">Nombre</label>
												<input name="name" type="text" required value="<?=$myData['first_name'];?>" class="form-control">
											</div>
											<div class="mt-3">
												<label class="form-label">Correo electrónico</label>
												<input disabled value="<?=$myData['email'];?>" name="email" type="email" class="form-control">
												<small class="text-muted form-text"><em>Establecido en el registro</em></small>
											</div>
											<div class="mt-3">
												<label class="form-label">Ubicación</label>
												<input name="location" type="text" required value="<?=$myData['location'];?>" class="form-control">
												<small class="text-muted form-text"><em>Precisión según privacidad personal</em></small>
											</div>
										</div>
										<div class="col">
											<div class="mt-3">
												<label class="form-label">Apellidos</label>
												<input name="lastname" required value="<?=$myData['last_name'];?>" type="text" class="form-control">
											</div>
											<div class="mt-3">
												<label class="form-label">Número de teléfono</label>
												<input name="phone" type="number" value="<?=$myData['phone'];?>" class="form-control">
												<small class="text-muted form-text"><em>Opcional</em></small>
											</div>
											<div class="mt-3">
												<label class="form-label">Biografía</label>
												<textarea name="biography" class="form-control" placeholder="Pequeña carta de presentación"><?=$myData['biography'];?></textarea>
												<small class="text-muted form-text"><em>Opcional</em></small>
											</div>
										</div>
									</div>
									<button type="submit" name="settings_form" class="form-btn mt-3 btn">Guardar cambios</button>
								</form>
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