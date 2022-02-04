<?php
require_once 'system/Core.php';
$catId = $_GET['id'];
$catData = $Site->getCatData($catId);
$catExists = !is_null($catData);
$currentCat = $catId;
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
					<h4><?=$catExists ? $catData['title'] : 'Categoría no encontrada';?></h4>
					<?php
					if(!$catExists) echo '<h5>La categoría que estás buscando no existe</h5>';
					else { ?>
						<div class="post-list-content">
							<div class="row row-cols-lg-5 row-cols-md-4 row-cols-2">
								<?php foreach($Site->getCatPosts($catId) as $post) { ?>
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
					<?php } ?>
			</div>
			<?php include TMPF.'footer.php'; ?>
		</div>
		<?php include TMPF.'scripts.php'; ?>
	</body>
</html>