<div class="header-navbar container-fluid">
	<nav class="navbar header"><a class="navbar-brand mr-auto" href="/"><img src="/statics/images/logo_dblog.png" alt="dBlog"></a>
		<div class="header-search"><input class="search-input" type="text" id="search-input" placeholder="Busca lo que necesitas"><i class="bi bi-search search-icon"></i></div>
		<div class="dropdown">
			<a class="dropdown-toggle nav-link" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
				<div class="pull-left">
					<img src="/statics/images/default.jpeg" height="25" class="user-photo" alt="User photo" />
				</div>
			</a>
			<ul class="dropdown-menu" aria-labelledby="userDropdown">
				<?php if(!$imOnline) { ?>
				<li><a class="dropdown-item" href="/login">Iniciar sesión</a></li>
				<li><a class="dropdown-item" href="/register">Registro</a></li>
				<?php } else { ?>
				<li><a class="dropdown-item" href="/settings">Ajustes</a></li>
				<li><a class="dropdown-item" href="/logout">Cerrar sesión</a></li>
				<?php } ?>
			</ul>
		</div>
	</nav>
</div>
<div class="header-menu container-fluid">
	<nav class="navbar navbar-expand-lg header-menu-container">
		<div class="w-auto input-group">
			<button type="button" class="btn btn-cats">Categorías</button>
			<button type="button" class="dropdown-toggle dropdown-toggle-split btn btn-cats" id="filter-button" data-bs-toggle="dropdown" aria-expanded="false">
				<span class="visually-hidden">Toggle Dropdown</span>
			</button>
			<ul class="dropdown-menu">
				<?php foreach($Site->getCategories() as $cat) { ?>
				<li><a class="dropdown-item" href="/category/<?=$cat['id'];?>"><?=$cat['title'];?></a></li>
				<?php } ?>
			</ul>
		</div>
		<ul class="navbar-nav header-navbar-items">
			<?php foreach($Site->featuredCats() as $cat) { ?>
			<li class="nav-item cursor-pointer<?php if(!empty($currentCat) && $currentCat == $cat['id']) echo ' selected';?>" onclick="goTo('/category/<?=$cat['id'];?>');"><?=$cat['title'];?></li>
			<?php } ?>
		</ul>
		<div class="navbar-nav ms-auto">
			<?php if($imOnline) { ?><button type="button" class="newpost-btn btn btn-none" onclick="goTo('/create');"><i class="bi-plus-lg"></i></button><?php } ?>
		</div>
	</nav>
</div>
<?php if($imOnline) $myData = $Site->userData($_SESSION['user_id']); ?>
