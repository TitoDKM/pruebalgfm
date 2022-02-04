<?php

class Site extends MySQL {

    public $siteUrl;

    function __construct($args)
    {
        $this->siteUrl = $args['siteUrl'];
        MySQL::__construct($args);
    }

	function getCatPosts($catId) {
		MySQL::where("category", $catId);
		MySQL::orderBy("id", "DESC");
		return MySQL::get("posts");
	}

	function getCatData($catId) {
		MySQL::where("id", $catId);
		return MySQL::getOne("categories");
	}

	function minifyBody($body) {
		$body = preg_replace('/<\/?[^>]+(>|$)/', "", $body);
		return substr($body, 0, 200);
	}

	function getPostData($postId) {
		MySQL::where("id", $postId);
		return MySQL::getOne("posts");
	}

	function tryGoogleLogin($googleData) {
		MySQL::where("email", $googleData['email']);
		MySQL::where("login_type", "GOOGLE");
		$userData = MySQL::getOne("users");
		if($userData) {
			$_SESSION['user_id'] = $userData['id'];
			$_SESSION['login_type'] = "GOOGLE";
			header("Location: /");
		} else {
			$user = Array("email" => $googleData['email'],
				"first_name" => $googleData['givenName'],
				"last_name" => $googleData['familyName'],
				"password" => md5($googleData['id']),
				"login_type" => "GOOGLE");
			$userId = MySQL::insert("users", $user);
			$_SESSION['user_id'] = $userId;
			$_SESSION['login_type'] = "GOOGLE";
			header("Location: /settings");
		}
	}

	function tryLogin() {
		$email = $_POST['email'];
		$password = $_POST['password'];
		if(empty($email) || empty($password)) {
			$_SESSION['login_error'] = "Rellena todos los campos para iniciar sesión";
		} else {
			MySQL::where("email", $email);
			$tempData = MySQL::getOne("users");
			if(is_null($tempData)) {
				$_SESSION['login_error'] = "El correo electrónico introducido no existe en nuestra base de datos";
			} else {
				if(md5($password) !== $tempData['password']) {
					$_SESSION['login_error'] = "La contraseña introducida es incorrecta";
				} else {
					if($tempData['login_type'] == "GOOGLE") {
						$_SESSION['login_error'] = "Esta cuenta únicamente permite el inicio de sesión con Google";
					} else {
						$_SESSION['user_id'] = $tempData['id'];
						$_SESSION['login_type'] = "DEFAULT";
						header("Location: /");
					}
				}
			}
		}
	}

	function tryRegister() {
		$email = $_POST['email'];
		$password = $_POST['password'];
		$password2 = $_POST['password2'];
		$name = $_POST['name'];
		$lastname = $_POST['lastname'];
		$location = $_POST['location'];
		if(empty($email) || empty($password) || empty($password2) || empty($name) || empty($lastname) || empty($location)) {
			$_SESSION['register_error'] = "Rellena todos los campos para completar el registro";
		} else {
			if($password !== $password2) {
				$_SESSION['register_error'] = "Las contraseñas introducidas no coinciden";
			} else {
				MySQL::where("email", $email);
				if(MySQL::has("users")) {
					$_SESSION['register_error'] = "El correo electrónico introducido ya está en uso";
				} else {
					$user = Array("email" => $email,
						"first_name" => $name,
						"last_name" => $lastname,
						"password" => md5($password),
						"photo" => "/statics/images/default.jpeg",
						"login_type" => "DEFAULT");
					$userId = MySQL::insert("users", $user);
					$_SESSION['user_id'] = $userId;
					$_SESSION['login_type'] = "DEFAULT";
					header("Location: /");
				}
			}
		}
	}

	function checkLogged($needLogged) {
		$logged = array_key_exists('user_id', $_SESSION);
		if($logged && !$needLogged) {
			header("Location: /");
			die();
		} else if(!$logged && $needLogged) {
			header("Location: /login");
			die();
		}
	}

	function userData($userId) {
		MySQL::where("id", $userId);
		$result = MySQL::getOne("users");
		if(!$result) return Array("first_name" => "Anónimo", "last_name" => "", "photo" => "/statics/images/default.jpeg");
		else return $result;
	}

	function saveProfile() {
		$name = $_POST['name'];
		$location = $_POST['location'];
		$lastname = $_POST['lastname'];
		$phone = $_POST['phone'];
		$biography = $_POST['biography'];
		if(empty($name) || empty($location) || empty($lastname)) {
			$_SESSION['settings_error'] = "Los campos de nombre, apellidos y ubicación son obligatorios";
		} else {
			$newData = Array("first_name" => $name,
				"last_name" => $lastname,
				"phone" => $phone,
				"biography" => $biography,
				"location" => $location);
			MySQL::where("id", $_SESSION['user_id']);
			MySQL::update("users", $newData);
			$_SESSION['settings_success'] = "Perfil actualizado correctamente";
		}
	}

	function featuredCats() {
		MySQL::where("featured", "1");
		return MySQL::get("categories");
	}

	function latestPosts() {
		MySQL::orderBy("id", "DESC");
		return MySQL::get("posts", 25);
	}

	function getCategories() {
		return MySQL::get("categories");
	}

	function getComments($postId) {
		MySQL::where("post_id", $postId);
		return MySQL::get("comments");
	}

	function addComment($postData) {
		$comment = $_POST['comment'];
		$online = array_key_exists('user_id', $_SESSION);
		if(empty($comment)) {
			$_SESSION['comments_error'] = "No puedes enviar un comentario en blanco";
		} else {
			if($postData['comments_type'] === 1 && !$online) $_SESSION['comments_error'] = "Necesitas iniciar sesión para enviar comentarios";
			else if($postData['comments_type'] === 0) $_SESSION['comments_error'] = "Esta publicación no admite comentarios";
			else if(($postData['comments_type'] === 1 && $online) || $postData['comments_type'] === 2) {
				$commentData = Array("author" => "0",
					"post_id" => $postData['id'],
					"body" => $comment);
				if($online) {
					$commentData['author'] = $_SESSION['user_id'];
				}
				MySQL::insert("comments", $commentData);
			}
		}
	}

	function createPost() {
		$title = $_POST['title'];
		$category = $_POST['category'];
		$comments = $_POST['comments'];
		$body = $_POST['body'];
		$image = $_FILES['image'];
		if(empty($title) || empty($body)) {
			$_SESSION['create_error'] = "Los campos de título y contenido son obligatorios";
		} else {
			if($image['error'] == 0) {
				$imageExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
				$allowedTypes = Array("png" => "image/png", "jpg" => "image/jpg", "jpg" => "image/jpeg");
				$uploadsFolder = "C:\\xampp\\htdocs\\statics\\images\\uploads\\posts\\";
				if(!in_array($image['type'], $allowedTypes)){
					$_SESSION['create_error'] = "El formato de la imagen subida no está admitido";
                } else {
					$name = md5(time()) . "." . $imageExtension;
                    move_uploaded_file($image['tmp_name'], $uploadsFolder . $name);
					$postData = Array("author" => $_SESSION['user_id'],
						"body" => $body,
						"title" => $title,
						"category" => $category,
						"image" => "/statics/images/uploads/posts/" . $name,
						"comments_type" => $comments);
					$postId = MySQL::insert("posts", $postData);
					header("Location: /post/" . $postId);
				}
			}
		}
	}

	function editPost($postId) {
		$title = $_POST['title'];
		$category = $_POST['category'];
		$comments = $_POST['comments'];
		$body = $_POST['body'];
		$image = $_FILES['image'];
		if(empty($title) || empty($body)) {
			$_SESSION['edit_error'] = "Los campos de título y contenido son obligatorios";
		} else {
			$name = "";
			if($image['name'] != "" && $image['error'] == 0) {
				$imageExtension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));
				$allowedTypes = Array("png" => "image/png", "jpg" => "image/jpg", "jpg" => "image/jpeg");
				$uploadsFolder = "C:\\xampp\\htdocs\\statics\\images\\uploads\\posts\\";
				if(!in_array($image['type'], $allowedTypes)){
					$_SESSION['edit_error'] = "El formato de la imagen subida no está admitido";
					return;
                } else {
					$name = md5(time()) . "." . $imageExtension;
                    move_uploaded_file($image['tmp_name'], $uploadsFolder . $name);
				}
			}	
			$postData = Array("author" => $_SESSION['user_id'],
			"body" => $body,
			"title" => $title,
			"category" => $category,
			"comments_type" => $comments);
			if($name != "")
				$postData['image'] = "/statics/images/uploads/posts/" . $name;
			MySQL::where("id", $postId);
			MySQL::update("posts", $postData);
			header("Location: /post/" . $postId);
		}
	}

	function searchPosts($search) {
		MySQL::where("title", "%".$search."%", "LIKE");
		MySQL::orWhere("body", "%".$search."%", "LIKE");
		return MySQL::get("posts");
	}
}