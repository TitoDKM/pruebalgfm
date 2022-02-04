<?php
require_once 'system/Core.php';
$postId = $_GET['id'];
$postData = $Site->getPostData($postId);
$postExists = !is_null($postData);
if(!$postExists)
	header("Location: /");
$currentCat = $postData['category'];
if(isset($_POST['form_comments'])) $Site->addComment($postData);
?><!DOCTYPE html>
<html lang="es">
	<head>
		<!--[if IE]>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<![endif]-->
		<meta charset="UTF-8">
		<meta name="language" content="es-ES" />
		<title>dBlog - Inicio</title>
		<?php include TMPF.'head.php'; ?>
	</head>
	<body>
		<div class="web-container">
			<?php include TMPF.'nav.php'; ?>
			<div class="post-container">
				<div class="post-header">
					<h1><?=$postData['title'];?></h1>
					<?php if($imOnline && $myData['id'] == $postData['author']) { ?><em><a href="/edit?id=<?=$postData['id'];?>">Editar entrada</a></em><?php } ?>
					<div class="post-category cursor-pointer" onclick="goto('/category/<?=$postData['category'];?>');"><i class="bi bi-folder"></i>&nbsp;&nbsp;Viajes</div>
					<div class="post-image"><img align="center" src="<?=$postData['image'];?>" /></div>
				</div>
				<div class="post-content">
					<?=$postData['body'];?>
				</div>
				<hr>
				<div class="comments-container">
					<div class="comments-header">
						<h3>Comentarios</h3>
					</div>
					<div class="comments-content">
						<?php $postComments = $Site->getComments($postId); 
						if(sizeof($postComments) == 0 && $postData['comments_type'] !== 0) echo '<h5>Aún no hay comentarios</h5>';
						foreach($postComments as $comment) {
							$authorData = $Site->userData($comment['author']); ?>
						<div class="comments-content d-flex mb-4">
							<div class="comment-photo">
								<img src="<?=$authorData['photo'];?>" />
							</div>
							<div class="comment-content">
								<div class="comment-author"><?=$authorData['first_name'];?> <?=$authorData['last_name'];?></div>
								<div class="comment-body"><?=$comment['body'];?></div>
							</div>
						</div>
						<?php } 
						if($postData['comments_type'] === 1 && !$imOnline) echo '<em>Necesitas iniciar sesión para enviar comentarios</em>';
						if($postData['comments_type'] === 0) echo '<em>Esta publicación no admite comentarios</em>';
						if(($postData['comments_type'] === 1 && $imOnline) || $postData['comments_type'] === 2) { ?>
						<div class="comments-add mt-4">
						<?php if(!empty($_SESSION['comments_error'])) { ?><div class="alert alert-danger" role="alert"><?=$_SESSION['comments_error'];?></div><?php unset($_SESSION['comments_error']); } ?>
							<h5>Añadir comentario</h5>
							<form method="POST">
								<textarea class="form-control" name="comment"></textarea>
								<button type="submit" name="form_comments" required class="form-btn mt-3 btn">Enviar comentario</button>
						</form>
						</div>
						<?php } ?>
					</div>
				</div>
			</div>
			<?php include TMPF.'footer.php'; ?>
		</div>
		<?php include TMPF.'scripts.php'; ?>
	</body>
</html>