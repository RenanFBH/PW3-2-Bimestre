<?php 
	include "config.php"; 
	include DBAPI; 
	if (!isset($_SESSION)) session_start();
	include(HEADER_TEMPLATE); 
	$erro = "";
	try {
		$db = open_database();
	} catch (Exception $e) {
		$erro = $e->getMessage();
	}
?>
<?php if ($db) : ?>
<?php if (!empty($_SESSION["message"])) : ?>
			<div class="error">
				<div class="alert alert-<?php echo $_SESSION["type"]; ?> alert-dismissible fade show mx-auto w-75 mt-3" role="alert">
					<h4 class="alert-heading text-center">ERRO!</h4>
					<hr>
					<p class="justify"><?php echo $_SESSION["message"]; ?></p>
					<a href="index.php" type="button" class="btn-close"></a>
				</div>
			</div>
<?php else: ?>
			<div class="margin d-flex flex-wrap justify-content-center gap-4 h-100 index cards-container">
				<!-- Clientes -->
				<div class="d-flex flex-column gap-4 col-md-3 col-12 mx-md-0 mx-auto">
					<a href="customers/add.php" class="text-decoration-none">
						<div class="card cards">
							<div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
								<i class="fa-solid fa-user-plus fa-5x mt-4 mb-4"></i>
								<h5 class="card-title">Novo Cliente</h5>
							</div>
						</div>
					</a>
					<a href="customers" class="text-decoration-none">
						<div class="card cards">
							<div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
								<i class="fa-solid fa-users fa-5x mt-4 mb-4"></i>
								<h5 class="card-title">Gerenciar Clientes</h5>
							</div>
						</div>
					</a>
				</div>
				<!-- Gerentes -->
				<div class="d-flex flex-column gap-4 col-md-3 col-12 mx-md-0 mx-auto">
					<a href="gerentes/add.php" class="text-decoration-none">
						<div class="card cards">
							<div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
								<i class="fa-solid fa-user-plus fa-5x mt-4 mb-4"></i>
								<h5 class="card-title">Novo Gerente</h5>
							</div>
						</div>
					</a>
					<a href="gerentes" class="text-decoration-none">
						<div class="card cards">
							<div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
								<i class="fa-solid fa-users fa-5x mt-4 mb-4"></i>
								<h5 class="card-title">Gerenciar Gerentes</h5>
							</div>
						</div>
					</a>
				</div>
<?php if (isset($_SESSION["user"])) : ?>
<?php if ($_SESSION["user"] == "admin") : ?>
				<!-- Usuários -->
				<div class="d-flex flex-column gap-4 col-md-3 col-12 mx-md-0 mx-auto">
					<a href="usuarios/add.php" class="text-decoration-none">
						<div class="card cards">
							<div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
								<i class="fa-solid fa-user-plus fa-5x mt-4 mb-4"></i>
								<h5 class="card-title">Novo Usuário</h5>
							</div>
						</div>
					</a>
					<a href="usuarios" class="text-decoration-none">
						<div class="card cards">
							<div class="card-body d-flex flex-column justify-content-center align-items-center text-center">
								<i class="fa-solid fa-users fa-5x mt-4 mb-4"></i>
								<h5 class="card-title">Gerenciar Usuários</h5>
							</div>
						</div>
					</a>
				</div>
<?php endif; ?>
<?php endif; ?>
			</div>
<?php endif; ?>
<?php else : ?>
			<div class="error">
				<div class="alert alert-danger alert-dismissible fade show mx-auto w-75 mt-3" role="alert">
					<h4 class="alert-heading text-center">ERRO!</h4>
					<hr>
					<p class="justify">Não foi possível conectar ao banco de dados!</p>
					<a href="index.php" type="button" class="btn-close"></a>
				</div>
			</div>
<?php endif; ?>
<?php 
	clear_messages(); 
	include(FOOTER_TEMPLATE);
?>