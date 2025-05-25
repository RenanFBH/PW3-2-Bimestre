<?php
	ob_start();
    include("functions.php");
	session_start();
	if (!isset($_SESSION)) session_start();
	if (isset($_SESSION["user"])){
		if ($_SESSION["user"] != "admin") {
			$_SESSION["message"] = "Você precisa ser administrador para acessar esse recurso!";
			$_SESSION["type"] = "danger";
			header("Location: " . BASEURL . "index.php");
			exit;
		}
	} else {
		$_SESSION["message"] = "Você deve estar logado e ser administrador para acessar esse recurso!";
			$_SESSION["type"] = "danger";
			header("Location: " . BASEURL . "index.php");
			exit;
	}
	if(isset($_GET['pdf'])){
		if($_GET['pdf'] == "ok"){
			pdf_usuarios();
		} else {
			pdf_usuarios($_GET['pdf']);
		}
	}		
	index();
	include(HEADER_TEMPLATE);
	ob_end_flush();
?>
			<div class="container-lg position-relative z-1 header-index rounded border">
				<header class="mt-2">
					<div class="row">
						<div class="col-12 mt-2">
							<h2 class="text-center"><i class="fa-solid fa-users"></i> USUÁRIOS</h2>
						</div>
						<div class="col-12 col-md-4 mt-2">
							<form name="filtro" action="index.php" method="post">
								<div class="input-group">
									<input type="text" class="form-control" maxlength="50" name="user" placeholder="Pesquisar Usuário..." required>
									<button type="submit" class="btn btn-custom-2"><i class='fas fa-search'></i> Consultar</button>
								</div>
							</form>
						</div>
						<div class="col-12 col-md-8 mt-2">
							<div class="d-flex flex-column flex-md-row justify-content-md-end gap-2">
								<a class="btn btn-custom-2" href="add.php"><i class="fa fa-plus"></i> Novo Usuário</a>
<?php if($_SERVER["REQUEST_METHOD"] == "POST") : ?>
								<a class="btn btn-custom-2" href="index.php?pdf=<?php echo $_POST['user']; ?>" download><i class="fa-solid fa-file-pdf"></i> Gerar PDF</a>
<?php else : ?>
								<a class="btn btn-custom-2" href="index.php?pdf=ok" download><i class="fa-solid fa-file-pdf"></i> Gerar PDF</a>
<?php endif; ?>
								<a class="btn btn-custom-2" href="index.php"><i class="fa fa-refresh"></i> Atualizar</a>
							</div>
						</div>
					</div>
				</header>
<?php if (!empty($_SESSION["message"])) : ?>
				<div class="alert alert-<?php echo $_SESSION["type"]; ?> alert-dismissible" role="alert">
					<?php echo $_SESSION["message"] . "\n"; ?>
					<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
				</div>
<?php endif; ?>
				<hr>
				<div class="table-responsive">
					<table id="tabela" class="table table-hover">
						<thead>
							<tr>
								<th>ID</th>
								<th>Nome</th>
								<th>Usuário</th>
								<th>Foto</th>
								<th style="width:15%;">Opções</th>
							</tr>
						</thead>
						<tbody>
<?php if ($usuarios) : ?>
<?php foreach ($usuarios as $usuario) : ?>
							<tr>
								<td><?php echo $usuario['id']; ?></td>
								<td><?php echo $usuario['nome']; ?></td>
								<td><?php echo $usuario['user']; ?></td>
								<td><?php
									if(!empty($usuario['foto'])){
										echo "<img src=\"fotos/{$usuario['foto']}\" class=\"shadow p-1 mb-1 bg-body rounded\" width=\"120px\" height=\"120px\">";
									}else{
										echo "<img src=\"fotos/semimagem.jpg\" class=\"shadow p-1 mb-1 bg-body rounded\" width=\"120px\" height=\"120px\">";
									}
								?></td>
								<td class="actions text-right">
									<div class="row">
										<a href="view.php?id=<?php echo $usuario['id']; ?>" class="btn btn-custom-2 col-10 mt-1"><i class="fa fa-eye"></i> Visualizar</a>
										<a href="edit.php?id=<?php echo $usuario['id']; ?>" class="btn btn-custom-2 col-10 mt-1"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
										<a href="#" class="btn btn-custom-2 col-10  mt-1" data-bs-toggle="modal" data-bs-target="#delete-modal-user" data-user="<?php echo $usuario['id']; ?>">
											<i class="fa fa-trash"></i> Excluir
										</a>
									</div>
								</td>
							</tr>
<?php endforeach; ?>
<?php else : ?>
							<tr>
								<td colspan="7">Nenhum registro encontrado.</td>
							</tr>
<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
			<br>
<?php include('modal.php');?>
<?php include(FOOTER_TEMPLATE); ?>