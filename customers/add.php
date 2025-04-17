<?php 
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
	add();
	include(HEADER_TEMPLATE);
?>
			<div class="container">
				<div class="card">
					<h2 class="text-center">Cadastrar</h2>
					<hr>
					<form action="add.php" method="post" enctype="multipart/form-data" class="row">
						<div class="col-4">
							<div>
								<img src="<?php
									if (!empty($customer['foto'])) {
										echo "fotos/" . $customer['foto']; 
									} else {
										echo "fotos/semimagem.jpg";
									}
								?>" class="img-fluid rounded-4" id="img-border" alt="foto do usuário">
								<input type="file" class="form-control mt-3" id="foto" name="customer[foto]">
							</div>
						</div>
						<div class="col-8 row">
							<div class="col-4">
								<div class="wrapper">
									<div class="input-data">
										<input type="text" required>
										<div class="underline"></div>
										<label>Nome / Razão Social</label>
									</div>
									<div class="input-data">
										<input type="text" required>
										<div class="underline"></div>
										<label>CNPJ / CPF</label>
									</div>
									<div class="input-data">
										<input type="text" required>
										<div class="underline"></div>
										<label>Data de Nascimento</label>
									</div>
									<div class="input-data">
										<input type="text" required>
										<div class="underline"></div>
										<label>Endereço</label>
									</div>
								</div>
							</div>
							<div class="col-4">
								<div class="wrapper">
									<div class="input-data">
										<input type="text" required>
										<div class="underline"></div>
										<label>Bairro</label>
									</div>
									<div class="input-data">
										<input type="text" required>
										<div class="underline"></div>
										<label>CEP</label>
									</div>
									<div class="input-data">
										<input type="text" required>
										<div class="underline"></div>
										<label>Município</label>
									</div>
									<div class="input-data">
										<input type="text" required>
										<div class="underline"></div>
										<label>Telefone</label>
									</div>
								</div>
							</div>
							<div class="col-4">
								<div class="wrapper">
									<div class="input-data">
										<input type="text" required>
										<div class="underline"></div>
										<label>Celular</label>
									</div>
									<div class="">
										<label for="campo3">UF</label>
										<select class="form-select" name="customer[state]" required>
											<option value="" selected disabled>Selecionar</option>
											<option value="AC">AC</option>
											<option value="AL">AL</option>
											<option value="AP">AP</option>
											<option value="AM">AM</option>
											<option value="BA">BA</option>
											<option value="CE">CE</option>
											<option value="DF">DF</option>
											<option value="ES">ES</option>
											<option value="GO">GO</option>
											<option value="MA">MA</option>
											<option value="MT">MT</option>
											<option value="MS">MS</option>
											<option value="MG">MG</option>
											<option value="PA">PA</option>
											<option value="PB">PB</option>
											<option value="PR">PR</option>
											<option value="PE">PE</option>
											<option value="PI">PI</option>
											<option value="RJ">RJ</option>
											<option value="RN">RN</option>
											<option value="RS">RS</option>
											<option value="RO">RO</option>
											<option value="RR">RR</option>
											<option value="SC">SC</option>
											<option value="SP">SP</option>
											<option value="SE">SE</option>
											<option value="TO">TO</option>
										</select>
									</div>
									<div class="input-data">
										<input type="text" required>
										<div class="underline"></div>
										<label>Inscrição Estadual</label>
									</div>
								</div>
							</div>	
						</div>
						<hr>
						<div class="row mt-2 text-center">
							<div class="col-md-6"></div>
							<div class="col-md-6 text-end">
							<button type="submit" class="btn btn-custom-2 w-25 h-100"><i class="fa-solid fa-trash"></i> Remover foto</button>
								<button type="submit" class="btn btn-custom-2 w-25 h-100"><i class="fa-solid fa-sd-card"></i> Salvar</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
			<script>
				$(document).ready(() => {
					$("#foto").change(function () {
						const file = this.files[0];
						if (file) {
							let reader = new FileReader();
							reader.onload = function (event) {
								$("#imgPreview").attr("src", event.target.result);
							};
							reader.readAsDataURL(file);
						}
					});
				});
			</script>
<?php include(FOOTER_TEMPLATE); ?>