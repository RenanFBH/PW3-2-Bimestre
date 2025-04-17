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
			<div class="container view-container margin ">
				<div class="card mb-3 rounded-4">
					<div class="row g-0">
						<div class="col-md-4">
							<img src="<?php
								if (!empty($gerente['foto'])) {
									echo "fotos/" . $gerente['foto']; 
								} else {
									echo "fotos/semimagem.jpg";
								}
							?>" class="img-fluid rounded-4" id="img-border" alt="foto do usuário">
						</div>
						<div class="col-md-8">
							<div class="card-body">
								<div class="d-flex align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Nome / Razão Social:</h4>
									<p class="mb-0 p-1"><?php echo $gerente['nome']; ?></p>
								</div>
								<div class="d-flex align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Endereço:</h4>
									<p class="mb-0 p-1"><?php echo $gerente['endereco']; ?></p>
								</div>
								<div class="d-flex align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Data de Nascimento:</h4>
									<p class="mb-0 p-1"><?php echo formatadata($gerente['datanasc'], "d/m/Y"); ?></p>
								</div>
								<div class="d-flex align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Departamento:</h4>
									<p class="mb-0 p-1"><?php echo $gerente['depto']; ?></p>
								</div>
								<div class="d-flex align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Data de Cadastro:</h4>
									<p class="mb-0 p-1"><?php echo formatadata($gerente['criacao'], "d/m/Y - H:i:s"); ?></p>
								</div>
								<div class="d-flex align-items-baseline flex-wrap">
									<h4 class="card-title mb-0 me-2">Data de Alteração:</h4>
									<p class="mb-0 p-1"><?php echo formatadata($gerente['modificacao'], "d/m/Y - H:i:s"); ?></p>
								</div>
							</div>
						</div>
					</div>
					<div class="row mt-2 text-center">
						<div class="col-md-6 d-flex gap-2">
							<a href="edit.php?id=<?php echo $gerente['id']; ?>" class="btn btn-custom w-100 d-flex align-items-center justify-content-center text-center">
								<i class="fa-solid fa-pen-to-square me-1"></i> Editar
							</a>
						</div>
						<div class="col-md-6 d-flex gap-2">
							<a href="index.php" class="btn btn-custom w-100 d-flex align-items-center justify-content-center text-center">
								<i class="fa-solid fa-rotate-left me-1"></i> Voltar
							</a>
						</div>
					</div>
				</div>
			</div>
<?php include(FOOTER_TEMPLATE); ?>