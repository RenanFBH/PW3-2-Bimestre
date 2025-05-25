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
					<h2 class="text-center">Editar Cliente <?php echo $customer["id"]; ?></h2>
					<hr>
					<form action="edit.php?id=<?php echo $customer['id']; ?>" onsubmit="return validar()" method="post" enctype="multipart/form-data" class="row">
						<div class="col-lg-4">
							<div class="box">
								<div class="input-box" id="uploadArea">
									<h2 class="upload-area-title">Imagem do Cliente</h2>
									<div class="upload-wrapper" style="position:relative; width:100%; height:100%;">
										<input type="file" id="upload" name="foto" accept=".png, .jpg, .jpeg, .gif" hidden>
										<input type="hidden" name="remove_foto" id="remove_foto" value="0">
										<label for="upload" class="uploadlabel" id="uploadLabel"style="display:none; position:absolute; top:0; left:0; width:100%; height:100%;">
											<span><i class="fa fa-cloud-upload"></i></span>
											<p>Clique para fazer Upload</p>
										</label>
										<img id="imgPreview" src="fotos/<?php echo $customer['foto']; ?>" style="display: block; width: 100%; margin-top: 10px;" />									
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-8 row align-items-center mobile-register">
							<div class="col-lg-4">
								<div class="wrapper">
									<div class="input-data">
										<input type="text" name="customer[name]"  maxlength="50" value="<?php echo $customer['name']; ?>" required>
										<div class="underline"></div>
										<label>Nome / Razão Social</label>
									</div>
									<div class="input-data">
										<input id="inputCpfCnpj" name="customer[cpf_cnpj]"  type="text" value="<?php echo $customer['cpf_cnpj']; ?>" required>
										<div class="underline"></div>
										<label>CNPJ / CPF</label>
									</div>
									<div class="input-data margin-input-mobile">
										<label class="margin-select margin-select-mobile" for="campo3">Data de nascimento</label>
										<input type="date" name="customer[birthdate]" value="<?php echo formatadata($customer['birthdate'], "Y-m-d"); ?>" required>
										<div class="underline"></div>
									</div>
									<div class="input-data">
										<input type="text" name="customer[address]" maxlength="50" value="<?php echo $customer['address']; ?>" required>
										<div class="underline"></div>
										<label>Endereço</label>
									</div>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="wrapper">
									<div class="input-data">
										<input type="text" name="customer[hood]" maxlength="50" value="<?php echo $customer['hood']; ?>" required>
										<div class="underline"></div>
										<label>Bairro</label>
									</div>
									<div class="input-data">
										<input id="inputCep" name="customer[zip_code]"type="text" value="<?php echo $customer['zip_code']; ?>" required>
										<div class="underline"></div>
										<label>CEP</label>
									</div>
									<div class="input-data">
										<input type="text" name="customer[city]" maxlength="50" value="<?php echo $customer['city']; ?>" required>
										<div class="underline"></div>
										<label>Município</label>
									</div>
									<div class="input-data">
										<input id="inputTel" name="customer[phone]" type="text" value="<?php echo $customer['phone']; ?>" required>
										<div class="underline"></div>
										<label>Telefone</label>
									</div>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="wrapper">
									<div class="input-data">
										<input id="inputCel" name="customer[mobile]" type="text" value="<?php echo $customer['mobile']; ?>" required>
										<div class="underline"></div>
										<label>Celular</label>
									</div>
									<div class="input-data margin-input margin-input-mobile">
										<label class="margin-select margin-select-mobile" for="campo3">UF</label>
										<select class="form-select" name="customer[state]" required>
										<option value="" selected disabled>Selecionar</option>
										<option value="AC" <?php echo ($customer['state'] == 'AC') ? 'selected' : ''; ?>>AC</option>
										<option value="AL" <?php echo ($customer['state'] == 'AL') ? 'selected' : ''; ?>>AL</option>
										<option value="AP" <?php echo ($customer['state'] == 'AP') ? 'selected' : ''; ?>>AP</option>
										<option value="AM" <?php echo ($customer['state'] == 'AM') ? 'selected' : ''; ?>>AM</option>
										<option value="BA" <?php echo ($customer['state'] == 'BA') ? 'selected' : ''; ?>>BA</option>
										<option value="CE" <?php echo ($customer['state'] == 'CE') ? 'selected' : ''; ?>>CE</option>
										<option value="DF" <?php echo ($customer['state'] == 'DF') ? 'selected' : ''; ?>>DF</option>
										<option value="ES" <?php echo ($customer['state'] == 'ES') ? 'selected' : ''; ?>>ES</option>
										<option value="GO" <?php echo ($customer['state'] == 'GO') ? 'selected' : ''; ?>>GO</option>
										<option value="MA" <?php echo ($customer['state'] == 'MA') ? 'selected' : ''; ?>>MA</option>
										<option value="MT" <?php echo ($customer['state'] == 'MT') ? 'selected' : ''; ?>>MT</option>
										<option value="MS" <?php echo ($customer['state'] == 'MS') ? 'selected' : ''; ?>>MS</option>
										<option value="MG" <?php echo ($customer['state'] == 'MG') ? 'selected' : ''; ?>>MG</option>
										<option value="PA" <?php echo ($customer['state'] == 'PA') ? 'selected' : ''; ?>>PA</option>
										<option value="PB" <?php echo ($customer['state'] == 'PB') ? 'selected' : ''; ?>>PB</option>
										<option value="PR" <?php echo ($customer['state'] == 'PR') ? 'selected' : ''; ?>>PR</option>
										<option value="PE" <?php echo ($customer['state'] == 'PE') ? 'selected' : ''; ?>>PE</option>
										<option value="PI" <?php echo ($customer['state'] == 'PI') ? 'selected' : ''; ?>>PI</option>
										<option value="RJ" <?php echo ($customer['state'] == 'RJ') ? 'selected' : ''; ?>>RJ</option>
										<option value="RN" <?php echo ($customer['state'] == 'RN') ? 'selected' : ''; ?>>RN</option>
										<option value="RS" <?php echo ($customer['state'] == 'RS') ? 'selected' : ''; ?>>RS</option>
										<option value="RO" <?php echo ($customer['state'] == 'RO') ? 'selected' : ''; ?>>RO</option>
										<option value="RR" <?php echo ($customer['state'] == 'RR') ? 'selected' : ''; ?>>RR</option>
										<option value="SC" <?php echo ($customer['state'] == 'SC') ? 'selected' : ''; ?>>SC</option>
										<option value="SP" <?php echo ($customer['state'] == 'SP') ? 'selected' : ''; ?>>SP</option>
										<option value="SE" <?php echo ($customer['state'] == 'SE') ? 'selected' : ''; ?>>SE</option>
										<option value="TO" <?php echo ($customer['state'] == 'TO') ? 'selected' : ''; ?>>TO</option>
										</select>
									</div>
									<div class="input-data">
										<input id="inputIe" name="customer[ie]" type="text" value="<?php echo $customer['ie']; ?>" required>
										<div class="underline"></div>
										<label>Inscrição Estadual</label>
									</div>
									<div class="input-data" style="visibility:hidden;"></div>
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
				const inputTel = document.getElementById('inputTel');
				const inputCel = document.getElementById('inputCel');
				const inputCep = document.getElementById('inputCep');
				const inputCpfCnpj = document.getElementById('inputCpfCnpj');
				const inputIe = document.getElementById('inputIe');
				const imgPreview = document.getElementById('imgPreview');
				const inputUpload = document.getElementById('uploadLabel');
				const wrapper = document.querySelector('.upload-wrapper');

				window.onload = () => {
					if (imgPreview.src.includes("fotos/semimagem.jpg")) {
						removeBtn.style.display = 'none';
					} else {
						removeBtn.style.display = 'inline-block';
					}

					inputTel.oninput();
					inputCel.oninput();
					inputCep.oninput();
					inputCpfCnpj.oninput();
					inputIe.oninput();
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

				inputTel.oninput = function () {
					// Remove letras e caracteres especiais (exceto ponto)
					var removeChar = this.value.replace(/[^0-9\.]/g, '');

					// Remove os pontos
					var removeDot = removeChar.replace(/\./g, '');

					// Remove qualquer caractere que não seja número
					var formatedNumber = removeDot.replace(/\D/g, '');

					// Aplica a máscara: (XX) XXXXX-XXXX
					if (formatedNumber.length === 0) {
						this.value = '';
					} else if (formatedNumber.length === 1) {
						this.value = '(' + formatedNumber;
					} else if (formatedNumber.length === 2) {
						this.value = '(' + formatedNumber + ') ';
					} else if (formatedNumber.length <= 6) {
						// Do 3º ao 6º dígito (até completar 5 fora dos parênteses)
						this.value = '(' + formatedNumber.substring(0, 2) + ') ' + formatedNumber.substring(2);
					} else {
						// Quando tiver 6 ou mais dígitos após os dois primeiros
						this.value = '(' + formatedNumber.substring(0, 2) + ') ' +
									formatedNumber.substring(2, 7) + '-' +
									formatedNumber.substring(7, 11);
					}
				};

				inputCel.oninput = function () {
					// Remove letras e caracteres especiais (exceto ponto)
					var removeChar = this.value.replace(/[^0-9\.]/g, '');

					// Remove os pontos
					var removeDot = removeChar.replace(/\./g, '');

					// Remove qualquer caractere que não seja número
					var formatedNumber = removeDot.replace(/\D/g, '');

					// Aplica a máscara: (XX) XXXXX-XXXX
					if (formatedNumber.length === 0) {
						this.value = '';
					} else if (formatedNumber.length === 1) {
						this.value = '(' + formatedNumber;
					} else if (formatedNumber.length === 2) {
						this.value = '(' + formatedNumber + ') ';
					} else if (formatedNumber.length <= 6) {
						// Do 3º ao 6º dígito (até completar 5 fora dos parênteses)
						this.value = '(' + formatedNumber.substring(0, 2) + ') ' + formatedNumber.substring(2);
					} else {
						// Quando tiver 6 ou mais dígitos após os dois primeiros
						this.value = '(' + formatedNumber.substring(0, 2) + ') ' +
									formatedNumber.substring(2, 7) + '-' +
									formatedNumber.substring(7, 11);
					}
				};

				inputCep.oninput = function () {
					// Remove qualquer caractere que não seja número
					var removeChar = this.value.replace(/\D/g, '');

					// Aplica a máscara: XXXXX-XXX
					if (removeChar.length <= 5) {
						this.value = removeChar; // Apenas os 5 primeiros números
					} else {
						this.value = removeChar.substring(0, 5) + '-' + removeChar.substring(5, 8);
					}
				};

				inputCpfCnpj.oninput = function () {
					let raw = this.value.replace(/\D/g, '');

					// Limita a 14 dígitos
					raw = raw.substring(0, 14);

					if (raw.length <= 11) {
						// CPF: XXX.XXX.XXX-XX
						if (raw.length <= 3) {
							this.value = raw;
						} else if (raw.length <= 6) {
							this.value = raw.substring(0, 3) + '.' + raw.substring(3);
						} else if (raw.length <= 9) {
							this.value = raw.substring(0, 3) + '.' + raw.substring(3, 6) + '.' + raw.substring(6);
						} else {
							this.value = raw.substring(0, 3) + '.' + raw.substring(3, 6) + '.' + raw.substring(6, 9) + '-' + raw.substring(9);
						}
					} else {
						// CNPJ: XX.XXX.XXX/XXXX-XX
						if (raw.length <= 2) {
							this.value = raw;
						} else if (raw.length <= 5) {
							this.value = raw.substring(0, 2) + '.' + raw.substring(2);
						} else if (raw.length <= 8) {
							this.value = raw.substring(0, 2) + '.' + raw.substring(2, 5) + '.' + raw.substring(5);
						} else if (raw.length <= 12) {
							this.value = raw.substring(0, 2) + '.' + raw.substring(2, 5) + '.' +
										raw.substring(5, 8) + '/' + raw.substring(8, 12);
						} else {
							this.value = raw.substring(0, 2) + '.' + raw.substring(2, 5) + '.' +
										raw.substring(5, 8) + '/' + raw.substring(8, 12) + '-' + raw.substring(12, 14);
						}
					}
				};

				inputIe.oninput = function () {
					// Remove qualquer caractere que não seja número
					let removeChar = this.value.replace(/\D/g, '');

					// Limita o valor a no máximo 8 dígitos
					removeChar = removeChar.substring(0, 8);

					// Aplica a formatação "XX.XXX.XXX"
					if (removeChar.length <= 2) {
						this.value = removeChar;
					} else if (removeChar.length <= 5) {
						this.value = removeChar.substring(0, 2) + '.' + removeChar.substring(2);
					} else {
						this.value = removeChar.substring(0, 2) + '.' + removeChar.substring(2, 5) + '.' + removeChar.substring(5);
					}
				};

				function validar() {
					const rawtel = inputTel.value.replace(/\D/g, ''); 
					const rawcel = inputCel.value.replace(/\D/g, ''); 
					const rawcep = inputCep.value.replace(/\D/g, ''); 				
					const rawcpfcnpj = inputCpfCnpj.value.replace(/\D/g, ''); 
					const rawie = inputIe.value.replace(/\D/g, ''); 

					if (rawtel.length !== 11) {
						alert("Telefone deve conter exatamente 11 dígitos.");
						inputTel.focus();
						return false;
					}

					if (rawcel.length !== 11) {
						alert("Celular deve conter exatamente 11 dígitos.");
						inputCel.focus();
						return false;
					}

					if (rawcep.length !== 8) {
						alert("CEP deve conter exatamente 8 dígitos.");
						inputCep.focus();
						return false;
					}

					if (rawcpfcnpj.length !== 11 && rawcpfcnpj.length !== 14) {
						alert("CPF deve conter 11 dígitos ou CNPJ 14 dígitos.");
						inputCpfCnpj.focus();
						return false;
					}

					if (rawie.length < 8 || rawie.length > 9) {
						alert("Inscrição Estadual deve conter entre 8 e 9 dígitos.");
						inputIe.focus();
						return false;
					}
					inputTel.value = rawtel;
					inputCel.value = rawcel;
					inputCep.value = rawcep;
					inputCpfCnpj.value = rawcpfcnpj;
					inputIe.value = rawie;
					if (!imgPreview.src.includes("fotos/semimagem.jpg")) {
						removeBtn.style.display = 'inline-block';
					}
					return true;
				}
			</script>
<?php include(FOOTER_TEMPLATE); ?>