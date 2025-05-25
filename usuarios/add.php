<?php 
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
	add();
	include(HEADER_TEMPLATE);
?>
            <div class="container">
				<div class="card rounded-4 margin-2">
					<h2 class="text-center">Cadastrar Usuário</h2>
					<hr>
					<form action="add.php" method="post" enctype="multipart/form-data" class="row">
						<div class="col-lg-6">
							<div class="box">
								<div class="input-box" id="uploadArea">
									<h2 class="upload-area-title">Imagem do Usuário</h2>
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
						<div class="col-lg-6 row align-items-center mobile-register">
							<div class="col-lg-12 align-items-center">
								<div class="wrapper me-5 ms-5">
									<div class="input-data" style="margin-top:10%; !important">
										<input type="text" name="usuario[nome]" maxlength="30" required>
										<div class="underline"></div>
										<label>Nome / Razão Social</label>
									</div>
									<div class="input-data" style="margin-top:10%; !important">
										<input  type="text" name="usuario[user]" maxlength="20" required>
										<div class="underline"></div>
										<label>Login</label>
									</div>
									<div class="input-data d-flex position-relative" style="margin-top:10%;">
										<input type="password" name="usuario[password]" id="passwordInput" minlength="5" maxlength="20" required style="padding-right: 40px;">
										<div class="underline"></div>
										<label>Senha</label>
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