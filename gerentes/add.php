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
				<div class="card rounded-4 margin-2">
					<h2 class="text-center">Cadastrar Gerente</h2>
					<hr>
					<form action="add.php" method="post" enctype="multipart/form-data" class="row">
						<div class="col-lg-4">
							<div class="box">
								<div class="input-box" id="uploadArea">
									<h2 class="upload-area-title">Imagem do Gerente</h2>
									<div class="upload-wrapper" style="position:relative; width:100%; height:100%;">
										<input type="file" id="upload" name="foto" accept=".png, .jpg, .jpeg, .gif" hidden>
										<input type="hidden" name="remove_foto" id="remove_foto" value="0">
										<label for="upload" class="uploadlabel" id="uploadLabel"style="display:none; position:absolute; top:0; left:0; width:100%; height:100%;">
											<span><i class="fa fa-cloud-upload"></i></span>
											<p>Clique para fazer Upload</p>
										</label>
										<img id="imgPreview" src="fotos/semimagem.jpg" style="display: block; width: 100%; margin-top: 10px;" />									
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-8 row mobile-register">
							<div class="col-lg-6">
								<div class="wrapper">
									<div class="input-data">
										<input type="text" name="gerente[nome]" maxlength="50" required>
										<div class="underline"></div>
										<label>Nome / Razão Social</label>
									</div>
									<div class="input-data">
										<input  type="text" name="gerente[endereco]" maxlength="50" required>
										<div class="underline"></div>
										<label>Endereço</label>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="wrapper">
									<div class="input-data margin-input-mobile">
										<label class="margin-select margin-select-mobile" for="campo3">Data de nascimento</label>
										<input type="date" name="gerente[datanasc]" required>
										<div class="underline"></div>
									</div>	
									<div class="input-data margin-input margin-input-mobile">
										<label class="margin-select margin-select-mobile" for="campo3">Departamento</label>
										<select class="form-select" name="gerente[depto]" style="padding:15px; !important" required>
										<option value="" selected disabled>Selecionar</option>
										<option value="Administrativo">Administrativo</option>
										<option value="Recursos humanos">Recursos humanos</option>
										<option value="Jurídico">Jurídico</option>
										<option value="Comercial">Comercial</option>
										<option value="Finanças">Finanças</option>
										<option value="Vendas">Vendas</option>
										<option value="Criativo">Criativo</option>
										<option value="Logística">Logística</option>
										<option value="Produção">Produção</option>
										<option value="Engenharia">Engenharia</option>
										<option value="Desenvolvimento">Desenvolvimento</option>
										<option value="Marketing">Marketing</option>
										<option value="Atendimento">Atendimento</option>
										<option value="Qualidade">Qualidade</option>   
										</select>
									</div>
								</div>
							</div>							
						</div>
						<hr>
						<div class="col-lg-12 button-align">
							<a id="removeBtn" class="btn btn-custom-2 w-25 h-100" style="display:none;"><i class="fa-solid fa-trash"></i> Remover foto</a>
							<button type="submit" class="btn btn-custom-2 w-25 h-100"><i class="fa-solid fa-sd-card"></i> Salvar</button>
						</div>
					</form>
				</div>
			</div>
			<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
			<script> 
				const uploadInput = document.getElementById('upload');
				const uploadLabel = document.getElementById('uploadLabel');
				const removeBtn = document.getElementById('removeBtn');
				const imgPreview = document.getElementById('imgPreview');
				const inputUpload = document.getElementById('uploadLabel');
				const wrapper = document.querySelector('.upload-wrapper');

				window.onload = () => {
					if (imgPreview.src.includes("fotos/semimagem.jpg")) {
						removeBtn.style.display = 'none';
					} else {
						removeBtn.style.display = 'inline-block';
					}
				};
          
                wrapper.addEventListener('mouseover', () => {
					inputUpload.style.display = 'flex';
				});

				wrapper.addEventListener('mouseout', () => {
					inputUpload.style.display = 'none';
				});

				uploadInput.addEventListener('change', function () {
					const file = this.files[0];
					if (file) {
							const reader = new FileReader();
							reader.onload = function (e) {
							uploadLabel.style.display = 'none';
							imgPreview.src = e.target.result;
							imgPreview.style.display = 'block';
							removeBtn.style.display = 'inline-block'; 
						}
						reader.readAsDataURL(file);
					}
				});

				removeBtn.addEventListener('click', function () {
					uploadInput.value = ''; 
					imgPreview.style.display = 'block';
					imgPreview.src = "fotos/semimagem.jpg";
					removeBtn.style.display = 'none'; 
					document.getElementById('remove_foto').value = '1';
				});
			</script>
<?php include(FOOTER_TEMPLATE); ?>