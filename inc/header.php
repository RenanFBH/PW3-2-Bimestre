<?php
	if (!isset($_SESSION)) session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
	<!-- Head -->
	<head>
		<meta charset="utf-8">
		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta property="og:title" content="CRUD-PW3-2025" />
		<meta property="og:url" content="http://projetopw2024.great-site.net/" />
		<meta property="og:image" content="<?php echo BASEURL; ?>css/logo.ico" />
		<meta name="keywords" content="CRUD, PHP, Bootstrap, Sistema Web, MySQL, PW3 2025">
		<meta name="author" content="Renan e Gustavo">
		<link rel="icon" type="image/x-icon" href="<?php echo BASEURL; ?>css/logo.ico">		
		<link rel="stylesheet" href="<?php echo BASEURL; ?>css/bootstrap/bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo BASEURL; ?>css/fontawesome/all.min.css">
		<link rel="stylesheet" href="<?php echo BASEURL; ?>css/estilo.css">	
		<title>CRUD com Bootstrap</title>
	</head>
	<!-- Body -->
	<body>
		<!-- Navbar -->
		<nav class="navbar navbar-expand-lg fixed-top">
			<div class="container-fluid">
				<a class="navbar-brand me-auto" href="<?php echo BASEURL; ?>"><i class="fa-solid fa-house"></i> CRUD-PHP</a>			
				<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
					<div class="offcanvas-header">
						<h5 class="offcanvas-title" id="offcanvasNavbarLabel"><i class="fa-solid fa-house"></i> CRUD-PHP</h5>
						<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
					</div>
					<div class="offcanvas-body d-flex justify-content-between align-items-center w-100">
						<ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
<?php if (isset($_SESSION["user"])): ?>	
							<div class="w-100 d-flex d-lg-none justify-content-center my-3">
								<img src="<?php echo BASEURL . 'usuarios/fotos/' . $_SESSION['foto']; ?>" alt="Foto do Usuário" class="rounded-4 nav-link-2" id="foto-usuario"/>
							</div>
<?php else: ?>
								<a href="<?php echo BASEURL; ?>inc/login.php" class="login-button d-block d-lg-none" id="login-mobile">
								<i class="fa-solid fa-right-to-bracket"></i> Login
							</a>
<?php endif; ?>
							<li class="nav-item margin-nav-link">
								<div class="dropdown">
									<a class="nav-link mx-lg-2 dropdown-toggle text" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
										<i class="fa-solid fa-user"></i> 
										Clientes
									</a>
									<ul class="dropdown-menu text">
										<li>
											<a class="dropdown-item" href="<?php echo BASEURL; ?>customers/add.php"><i class="fa-solid fa-user-plus"></i> Novo Cliente</a>
										</li>
										<li>
											<a class="dropdown-item" href="<?php echo BASEURL; ?>customers"><i class="fa-solid fa-users"></i> Gerenciar Clientes</a>
										</li>								
									</ul>
								</div>
							</li>
							<li class="nav-item">
								<div class="dropdown">
									<a class="nav-link mx-lg-2 dropdown-toggle text" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
										<i class="fa-solid fa-user-tie"></i> 
										Gerentes
									</a>
									<ul class="dropdown-menu text">
										<li>
											<a class="dropdown-item" href="<?php echo BASEURL; ?>gerentes/add.php"><i class="fa-solid fa-user-plus"></i> Novo Gerente</a>
										</li>
										<li>
											<a class="dropdown-item" href="<?php echo BASEURL; ?>gerentes"><i class="fa-solid fa-users"></i> Gerenciar Gerentes</a>
										</li>								
									</ul>
								</div>
							</li>
<?php if (isset($_SESSION["user"])): ?>
<?php if ($_SESSION["user"] == "admin"): ?>
							<li class="nav-item margin-nav">
								<div class="dropdown">
									<a class="nav-link mx-lg-2 dropdown-toggle text" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
										<i class="fa-solid fa-address-card"></i> 
										Usuários
									</a>
									<ul class="dropdown-menu text">
										<li>
											<a class="dropdown-item" href="<?php echo BASEURL; ?>usuarios/add.php"><i class="fa-solid fa-user-plus"></i> Novo Usuário</a>
										</li>
										<li>
											<a class="dropdown-item" href="<?php echo BASEURL; ?>usuarios"><i class="fa-solid fa-users"></i> Gerenciar Usuários</a>
										</li>								
									</ul>
								</div>
							</li>
							<li class="nav-item margin-nav logout-nav" style="display:none;">
								<a class="nav-link mx-lg-2" href="<?php echo BASEURL; ?>inc/logout.php">
									<i class="fa-solid fa-right-from-bracket"></i> 
									Logout
								</a>
							</li>	
<?php endif; ?>
<?php endif; ?>
						</ul>
						<div class="d-flex align-items-center ms-auto margin-offcanvas">
<?php if (isset($_SESSION["user"])): ?>		
							<div class="dropstart img-navbar">
								<a  href="#" class="nav-link-2 mx-lg-2 text" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									<img src="<?php echo BASEURL . 'usuarios/fotos/' . $_SESSION['foto']; ?>" alt="Foto do Usuário" class="rounded-circle"  id="foto-usuario" >
								</a>	
								<ul class="dropdown-menu text">
									<li>
										<a class="dropdown-item" href="<?php echo BASEURL; ?>inc/logout.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
									</li>	
								</ul>
							</div>
<?php else: ?>
							<a href="<?php echo BASEURL; ?>inc/login.php" class="login-button login-mobile-2 d-none d-lg-block" id="login-mobile">
								<i class="fa-solid fa-right-to-bracket"></i> Login
							</a>
<?php endif; ?>
						</div>
					</div>
				</div>
				<button class="navbar-toggler pe-8" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
			</div>
		</nav>
		<!-- Main -->
		<main class="main-class">
