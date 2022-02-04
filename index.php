<?php
require_once 'system/Core.php';
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
			<div class="post-list-container">
					<h4>Últimas publicaciones</h4>
					<div class="post-list-content">
						<div class="row row-cols-lg-5 row-cols-md-4 row-cols-2">
						<?php foreach($Site->latestPosts() as $post) { ?>
						<div class="col">
							<div class="cursor-pointer card" onclick="goTo('/post/<?=$post['id'];?>');" style="width: 15rem; height: 385px; margin-top: 20px;">
								<img class="card-img-top" src="<?=$post['image'];?>">
								<div class="blue card-body">
									<div class="card-title h5">
										<div class="card-title-main d-flex"><?=$post['title'];?></div>
										<div class="card-title-sub"><?=date("d/m/Y", strtotime($post['date']));?></div>
									</div>
									<p class="card-text">
										<?=$Site->minifyBody($post['body']);?>
									</p>
								</div>
							</div>
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