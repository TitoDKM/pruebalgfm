<?php
require_once 'system/Core.php';
$Site->checkLogged(true);
if(isset($_POST['create_form'])) $Site->createPost();
?><!DOCTYPE html>
<html lang="es">
	<head>
		<!--[if IE]>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<![endif]-->
		<meta charset="UTF-8">
		<meta name="language" content="es-ES" />
		<title>dBlog - Nueva entrada</title>
		<?php include TMPF.'head.php'; ?>
		<script src="https://cdn.tiny.cloud/1/xd116zalk9rdgrhgu671k0xtpe7uavm4tapzerr3ddmz4m6w/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
	</head>
	<body>
		<div class="web-container">
			<?php include TMPF.'nav.php'; ?>
			<div class="container">
				<div class="row">
					<div class="align-self-center col">
						<div class="form-container">
							<div class="form-header">
								<h3 class="blue-underline">Publicar nueva entrada</h3>
							</div>
							<div class="form-content">
								<?php if(!empty($_SESSION['create_error'])) { ?><div class="alert alert-danger" role="alert"><?=$_SESSION['create_error'];?></div><?php unset($_SESSION['create_error']); } ?>
								<form method="POST" enctype="multipart/form-data">
									<div class="mt-3">
										<label class="form-label">Título</label>
										<input placeholder="Título del post" required name="title" type="text" class="form-control">
									</div>
									<div class="mt-3">
										<label class="form-label">Categoría</label>
										<select name="category" class="form-select">
											<option value="-1">Sin categoría</option>
											<?php foreach($Site->getCategories() as $cat) { ?>
												<option value="<?=$cat['id'];?>"><?=$cat['title'];?></option>
											<?php } ?>
										</select>
									</div>
									<div class="mt-3">
										<label class="form-label">Permitir comentarios</label>
										<select name="comments" class="form-select">
											<option value="0">No</option>
											<option value="1">Sólo registrados</option>
											<option value="2">Cualquier visitante</option>
										</select>
									</div>
									<div class="mt-3">
										<label class="form-label">Imagen destacada</label>
										<input type="file" name="image" class="form-control">
									</div>
									<div class="mt-3">
										<label class="form-label">Contenido de la entrada</label>
										<textarea name="body"></textarea>
									</div>
									<button type="submit" name="create_form" class="form-btn mt-3 btn">Publicar entrada</button>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php include TMPF.'footer.php'; ?>
			<script>
				tinymce.init({
					selector: 'textarea',
					plugins: 'a11ychecker advcode casechange export formatpainter linkchecker autolink lists checklist media mediaembed pageembed permanentpen powerpaste table advtable tinycomments tinymcespellchecker',
					toolbar: 'a11ycheck addcomment showcomments casechange checklist code export formatpainter pageembed permanentpen table',
					toolbar_mode: 'floating',
					tinycomments_mode: 'embedded',
					tinycomments_author: 'Author name',
					language: 'es'
				});
			</script>
		</div>
		<?php include TMPF.'scripts.php'; ?>
	</body>
</html>