<?php 
	include("functions.php"); 
    session_start();
    if (!isset($_SESSION)) session_start();
    if (!isset($_GET['id']) || empty($_GET['id'])) {
        $_SESSION["message"] = "ID inválido ou não fornecido!";
        $_SESSION["type"] = "danger";
        header("Location: " . BASEURL . "index.php");
        exit;
    }
    if (isset($_SESSION["user"])) {	
    } else {
        $_SESSION["message"] = "Você deve estar logado para acessar esse recurso!";
        $_SESSION["type"] = "danger";
		header("Location: " . BASEURL . "index.php");
        exit;
    }
    view($_GET["id"]);
    include(HEADER_TEMPLATE); 
?>
			<div class="container">
				<div class="card rounded-4 margin-2">
					<h2 class="text-center">Visualizar Cliente <?php echo $customer['id']; ?></h2>
					<hr>
					<div class="row">
						<div class="col-lg-4">
							<img src="fotos/<?php echo $customer['foto']; ?>" class="img-fluid rounded-4 foto-view mb-3" id="img-border" alt="foto do cliente"/>
						</div>
						<div class="col-lg-8 row mobile-register">
							
							<div class="col-lg-6">
								<div class="align-items-baseline">
									<h4 class="card-title mb-0 me-2">Nome / Razão Social:</h4>
									<p class="mb-0 p-0"><?php echo $customer['name']; ?></p>
								</div>
								<hr>
								<div class="align-items-baseline">
									<h4 class="card-title mb-0 me-2">CPF / CNPJ:</h4>
									<p class="mb-0 p-0"><?php echo format_cpf_cnpj($customer['cpf_cnpj']); ?></p>
								</div>
								<hr>
								<div class="align-items-baseline">
									<h4 class="card-title mb-0 me-2">Data de Nascimento:</h4>
									<p class="mb-0 p-0"><?php echo formatadata($customer['birthdate'], "d/m/Y"); ?></p>
								</div>
								<hr>
								<div class="align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Endereço:</h4>
									<p class="mb-0 p-0"><?php echo $customer['address']; ?></p>
								</div>
								<hr>
								<div class="align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Bairro:</h4>
									<p class="mb-0 p-0"><?php echo $customer['hood']; ?></p>
								</div>
								<hr>
								<div class="align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">CEP:</h4>
									<p class="mb-0 p-0"><?php echo cep($customer['zip_code']); ?></p>
								</div>
								<hr>
								<div class="align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Data de Cadastro:</h4>
									<p class="mb-0 p-0"><?php echo formatadata($customer['created'], "d/m/Y - H:i:s"); ?></p>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Data de Alteração:</h4>
									<p class="mb-0 p-0"><?php echo formatadata($customer['modified'], "d/m/Y - H:i:s"); ?></p>
								</div>
								<hr>
								<div class="align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Cidade:</h4>
									<p class="mb-0 p-0"><?php echo $customer['city']; ?></p>
								</div>
								<hr>
								<div class="align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Telefone:</h4>
									<p class="mb-0 p-0"><?php echo telefone($customer['phone']); ?></p>
								</div>
								<hr>
								<div class="align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Celular:</h4>
									<p class="mb-0 p-0"><?php echo telefone($customer['mobile']); ?></p>
								</div>
								<hr>
								<div class="align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">UF:</h4>
									<p class="mb-0 p-0"><?php echo $customer['state']; ?></p>
								</div>
								<hr>
								<div class="align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Inscrição Estadual:</h4>
									<p class="mb-0 p-0"><?php echo number_format($customer['ie'], 0, ",", "."); ?></p>
								</div>
							</div>
						</div>
						<hr>
						<div class="col-lg-12 button-align">
							<a href="edit.php?id=<?php echo $customer['id']; ?>" class="btn btn-custom-2 w-25 h-100"><i class="fa-solid fa-pen-to-square me-1"></i> Editar</a>
							<a href="index.php" class="btn btn-custom-2 w-25 h-100"><i class="fa-solid fa-circle-left"></i> Voltar</a>
						</div>
					</form>
				</div>
			</div>
<?php include(FOOTER_TEMPLATE); ?>