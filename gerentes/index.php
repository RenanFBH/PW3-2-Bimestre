<?php
	ob_start();
	include("functions.php");
	session_start();
	if (!isset($_SESSION)) session_start();
	if (isset($_SESSION["user"])){
	} else {
		$_SESSION["message"] = "Você deve estar logado para acessar esse recurso!";
			$_SESSION["type"] = "danger";
			header("Location: " . BASEURL . "index.php");
			exit;
	}
	index();
	include(HEADER_TEMPLATE); 
	if (isset($_GET['pdf'])) {
		if ($_GET['pdf'] == "ok") {
			pdf_gerentes();
		} else {
			pdf_gerentes($_GET['pdf']);
		}
	}
	ob_end_flush();
?>
			<div class="container-lg position-relative z-1 header-index rounded border">
				<header class="mt-2">
					<div class="row">
						<div class="col-12 mt-2">
							<h2 class="text-center"><i class="fa-solid fa-users"></i> GERENTES</h2>
						</div>
						<div class="col-12 col-md-4 mt-2">
							<form name="filtro" action="index.php" method="post">
								<div class="input-group">
									<input type="text" class="form-control" maxlength="50" name="nome" placeholder="Pesquisar Gerente..." required>
									<button type="submit" class="btn btn-custom-2"><i class='fas fa-search'></i> Consultar</button>
								</div>
							</form>
						</div>
						<div class="col-12 col-md-8 mt-2">
							<div class="d-flex flex-column flex-md-row justify-content-md-end gap-2">
								<a class="btn btn-custom-2" href="add.php"><i class="fa fa-plus"></i> Novo Gerente</a>
<?php if($_SERVER["REQUEST_METHOD"] == "POST") : ?>
								<a class="btn btn-custom-2" href="index.php?pdf=<?php echo $_POST['nome']; ?>" download><i class="fa-solid fa-file-pdf"></i> Gerar PDF</a>
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
								<th>Endereço</th>
								<th>Departamento</th>
								<th>Data de nascimento</th>
								<th>Foto</th>
								<th style="width:15%;">Opções</th>
							</tr>
						</thead>
						<tbody>
<?php if ($gerentes) : ?>
<?php foreach ($gerentes as $gerente) : ?>
							<tr>
								<td><?php echo $gerente['id']; ?></td>
								<td><?php echo $gerente['nome']; ?></td>
								<td><?php echo $gerente['endereco']; ?></td>
								<td><?php echo $gerente['depto']; ?></td>
<?php $data = new Datetime ($gerente['datanasc'], new DateTimeZone("America/Sao_Paulo")); ?>
								<td><?php echo formatadata($gerente['datanasc'],"d/m/Y"); ?></td>
								<td><?php
									if(!empty($gerente['foto'])){
										echo "<img src=\"fotos/{$gerente['foto']}\" class=\"shadow p-1 mb-1 bg-body rounded\" width=\"100px\" height=\"100px\">";
									}else{
										echo "<img src=\"fotos/semimagem.jpg\" class=\"shadow p-1 mb-1 bg-body rounded\" width=\"100px\" height=\"100px\">";
									}
								?></td>
								<td class="actions text-right">
									<div class="row">
										<a href="view.php?id=<?php echo $gerente['id']; ?>" class="btn btn-custom-2 col-10 mt-1"><i class="fa fa-eye"></i> Visualizar</a>
										<a href="edit.php?id=<?php echo $gerente['id']; ?>" class="btn btn-custom-2 col-10 mt-1"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
										<a href="#" class="btn btn-custom-2 col-10  mt-1" data-bs-toggle="modal" data-bs-target="#delete-modal-gerente" data-gerente="<?php echo $gerente['id']; ?>">
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
<?php 
	include("modal.php");
	include(FOOTER_TEMPLATE); 
?>