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
    edit();
    include(HEADER_TEMPLATE); 
?>
            <div class="container">
				<div class="card rounded-4 margin-2">
					<h2 class="text-center">Editar Gerente <?php echo $gerente["id"]; ?></h2>
					<hr>
					<form action="edit.php?id=<?php echo $gerente['id']; ?>" method="post" enctype="multipart/form-data" class="row">
                        <div class="col-lg-4">
						    <div class="box">
								<div class="input-box" id="uploadArea">
									<h2 class="upload-area-title">Imagem do Gerente</h2>
									<div class="upload-wrapper" style="position:relative; width:100%; max-width:100%; height:90%;">
										<input type="file" id="upload" name="foto" accept=".png, .jpg, .jpeg, .gif" hidden>
										<input type="hidden" name="remove_foto" id="remove_foto" value="0">
										<label for="upload" class="uploadlabel" id="uploadLabel"style="display:none; position:absolute; top:0; left:0; width:100%; height:100%;">
											<span><i class="fa fa-cloud-upload"></i></span>
											<p>Clique para fazer Upload</p>
										</label>
										<img id="imgPreview" src="fotos/<?php echo $gerente['foto']; ?>" style="display: block; width: 100%; margin-top: 10px;" />
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-8 row mobile-register">
							<div class="col-lg-6">
								<div class="wrapper">
									<div class="input-data">
										<input type="text" name="gerente[nome]" maxlength="50" value="<?php echo $gerente['nome']; ?>" required>
										<div class="underline"></div>
										<label>Nome / Razão Social</label>
									</div>
									<div class="input-data">
										<input  type="text" name="gerente[endereco]" maxlength="50" value="<?php echo $gerente['endereco']; ?>" required>
										<div class="underline"></div>
										<label>Endereço</label>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="wrapper">
									<div class="input-data margin-input-mobile">
										<label class="margin-select margin-select-mobile" for="campo3">Data de nascimento</label>
										<input type="date" name="gerente[datanasc]" value="<?php echo formatadata($gerente['datanasc'], "Y-m-d"); ?>" required>
										<div class="underline"></div>
									</div>	
									<div class="input-data margin-input margin-input-mobile">
										<label class="margin-select margin-select-mobile" for="campo3">Departamento</label>
										<select class="form-select" name="gerente[depto]" required>
										    <option value="" disabled>Selecionar</option>
                                            <option value="Administrativo" <?php echo ($gerente['depto'] == 'Administrativo') ? 'selected' : ''; ?>>Administrativo</option>
                                            <option value="Recursos humanos" <?php echo ($gerente['depto'] == 'Recursos humanos') ? 'selected' : ''; ?>>Recursos humanos</option>
                                            <option value="Jurídico" <?php echo ($gerente['depto'] == 'Jurídico') ? 'selected' : ''; ?>>Jurídico</option>
                                            <option value="Comercial" <?php echo ($gerente['depto'] == 'Comercial') ? 'selected' : ''; ?>>Comercial</option>
                                            <option value="Finanças" <?php echo ($gerente['depto'] == 'Finanças') ? 'selected' : ''; ?>>Finanças</option>
                                            <option value="Vendas" <?php echo ($gerente['depto'] == 'Vendas') ? 'selected' : ''; ?>>Vendas</option>
                                            <option value="Criativo" <?php echo ($gerente['depto'] == 'Criativo') ? 'selected' : ''; ?>>Criativo</option>
                                            <option value="Logística" <?php echo ($gerente['depto'] == 'Logística') ? 'selected' : ''; ?>>Logística</option>
                                            <option value="Produção" <?php echo ($gerente['depto'] == 'Produção') ? 'selected' : ''; ?>>Produção</option>
                                            <option value="Engenharia" <?php echo ($gerente['depto'] == 'Engenharia') ? 'selected' : ''; ?>>Engenharia</option>
                                            <option value="Desenvolvimento" <?php echo ($gerente['depto'] == 'Desenvolvimento') ? 'selected' : ''; ?>>Desenvolvimento</option>
                                            <option value="Marketing" <?php echo ($gerente['depto'] == 'Marketing') ? 'selected' : ''; ?>>Marketing</option>
                                            <option value="Atendimento" <?php echo ($gerente['depto'] == 'Atendimento') ? 'selected' : ''; ?>>Atendimento</option>
                                            <option value="Qualidade" <?php echo ($gerente['depto'] == 'Qualidade') ? 'selected' : ''; ?>>Qualidade</option>    
										</select>
									</div>
								</div>
							</div>							
						</div>
						<hr>
						<div class="col-lg-12 button-align">
							<a id="removeBtn" class="btn btn-custom-2 w-25 h-100"><i class="fa-solid fa-trash"></i> Remover foto</a>
							<button type="submit" class="btn btn-custom-2 w-25 h-100"><i class="fa-solid fa-sd-card"></i> Salvar</button>
						</div>
					</form>
				</div>
			</div>
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