<?php
	ob_start();
	include("functions.php");
	session_start();
	index();
	include(HEADER_TEMPLATE); 
	if (isset($_GET['pdf'])) {
		if ($_GET['pdf'] == "ok") {
			pdf_clientes();
		} else {
			pdf_clientes($_GET['pdf']);
		}
	}
	ob_end_flush();
?>
			<div class="container-lg position-relative z-1 header-index rounded border">
				<header class="mt-2">
					<div class="row">
						<div class="col-12 mt-2">
							<h2 class="text-center"><i class="fa-solid fa-users"></i> CLIENTES</h2>
						</div>
						<div class="col-12 col-md-4 mt-2">
							<form name="filtro" action="index.php" method="post">
								<div class="input-group">
									<input type="text" class="form-control" maxlength="50" name="name" placeholder="Pesquisar Cliente..." required>
									<button type="submit" class="btn btn-custom-2"><i class='fas fa-search'></i> Consultar</button>
								</div>
							</form>
						</div>
						<div class="col-12 col-md-8 mt-2">
							<div class="d-flex flex-column flex-md-row justify-content-md-end gap-2">
								<a class="btn btn-custom-2" href="add.php"><i class="fa fa-plus"></i> Novo Cliente</a>
<?php if($_SERVER["REQUEST_METHOD"] == "POST" and isset($_SESSION["user"])) : ?>
								<a class="btn btn-custom-2" href="index.php?pdf=<?php echo $_POST['name']; ?>" download><i class="fa-solid fa-file-pdf"></i> Gerar PDF</a>
<?php elseif (isset($_SESSION["user"])) : ?>
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
								<th>CPF/CNPJ</th>
								<th>Cidade</th>
								<th>UF</th>
								<th>Telefone</th>
								<th>Atualizado em</th>
								<th>Foto</th>
								<th style="width:15%;">Opções</th>
							</tr>
						</thead>
						<tbody>
<?php if ($customers) : ?>
<?php foreach ($customers as $customer) : ?>
							<tr>
								<td><?php echo $customer['id']; ?></td>
								<td><?php echo $customer['name']; ?></td>
								<td><?php echo format_cpf_cnpj($customer['cpf_cnpj']); ?></td>
								<td><?php echo $customer['city']; ?></td>
								<td><?php echo $customer['state']; ?></td>
								<td><?php echo telefone($customer['phone']); ?></td>
								<td><?php echo formatadata($customer['modified'],"d/m/Y - H:i:s"); ?></td>
	<?php $data = new Datetime ($customer['modified'],new DateTimeZone("America/Sao_Paulo")); ?>
								<td><?php
									if(!empty($customer['foto'])){
										echo "<img src=\"fotos/{$customer['foto']}\" class=\"shadow p-1 mb-1 bg-body rounded\" width=\"100px\" height=\"100px\">";
									}else{
										echo "<img src=\"fotos/semimagem.jpg\" class=\"shadow p-1 mb-1 bg-body rounded\" width=\"100px\" height=\"100px\">";
									}
								?></td>
								<td class="actions text-right">
									<div class="row">
										<a href="view.php?id=<?php echo $customer['id']; ?>" class="btn btn-custom-2 col-10 mt-1"><i class="fa fa-eye"></i> Visualizar</a>
										<a href="edit.php?id=<?php echo $customer['id']; ?>" class="btn btn-custom-2 col-10 mt-1"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
										<a href="#" class="btn btn-custom-2 col-10  mt-1" data-bs-toggle="modal" data-bs-target="#delete-modal-customer" data-customer="<?php echo $customer['id']; ?>">
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