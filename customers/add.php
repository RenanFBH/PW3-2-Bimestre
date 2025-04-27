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
				<div class="card rounded-4">
					<h2 class="text-center">Cadastrar Cliente</h2>
					<hr>
					<form action="add.php" onsubmit="return validar()" method="post" enctype="multipart/form-data" class="row">
						<div class="col-lg-4">
							<div class="box">
								<div class="input-box" id="uploadArea">
									<h2 class="upload-area-title">Imagem do Cliente</h2>
									<input type="file" id="upload" name="foto" accept=".png, .jpg, .jpeg, .gif" hidden>
									<label for="upload" class="uploadlabel" id="uploadLabel">
										<span><i class="fa fa-cloud-upload"></i></span>
										<p>Clique para fazer Upload</p>
									</label>
									<img id="imgPreview" style="display:none; width: 100%; margin-top: 10px;" />
								</div>
							</div>
						</div>
						<div class="col-lg-8 row align-items-center mobile-register">
							<div class="col-lg-4">
								<div class="wrapper">
									<div class="input-data">
										<input type="text" name="customer[name]" maxlength="50" required>
										<div class="underline"></div>
										<label>Nome / Razão Social</label>
									</div>
									<div class="input-data">
										<input id="inputCpfCnpj" name="customer[cpf_cnpj]"  type="text" required>
										<div class="underline"></div>
										<label>CNPJ / CPF</label>
									</div>
									<div class="input-data margin-input-mobile">
										<label class="margin-select margin-select-mobile" for="campo3">Data de nascimento</label>
										<input type="date" name="customer[birthdate]" required>
										<div class="underline"></div>
									</div>
									<div class="input-data">
										<input type="text" name="customer[address]" maxlength="50" required>
										<div class="underline"></div>
										<label>Endereço</label>
									</div>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="wrapper">
									<div class="input-data">
										<input type="text" name="customer[hood]" maxlength="50" required>
										<div class="underline"></div>
										<label>Bairro</label>
									</div>
									<div class="input-data">
										<input id="inputCep" name="customer[zip_code]" type="text" required>
										<div class="underline"></div>
										<label>CEP</label>
									</div>
									<div class="input-data">
										<input type="text" name="customer[city]" maxlength="50" required>
										<div class="underline"></div>
										<label>Município</label>
									</div>
									<div class="input-data">
										<input id="inputTel" name="customer[phone]" type="text" required>
										<div class="underline"></div>
										<label>Telefone</label>
									</div>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="wrapper">
									<div class="input-data">
										<input id="inputCel" name="customer[mobile]" type="text" required>
										<div class="underline"></div>
										<label>Celular</label>
									</div>
									<div class="input-data margin-input margin-input-mobile">
										<label class="margin-select margin-select-mobile" for="campo3">UF</label>
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
										<input id="inputIe" name="customer[ie]" type="text" required>
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
				const imgPreview = document.getElementById('imgPreview');
				const removeBtn = document.getElementById('removeBtn');
				const inputTel = document.getElementById('inputTel');
				const inputCel = document.getElementById('inputCel');
				const inputCep = document.getElementById('inputCep');
				const inputCpfCnpj = document.getElementById('inputCpfCnpj');
				const inputIe = document.getElementById('inputIe');


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
					imgPreview.style.display = 'none';
					imgPreview.src = '';
					uploadLabel.style.display = 'flex'; 
					removeBtn.style.display = 'none'; 
				});

				inputTel.oninput = function (e) {
					let cursorPosition = this.selectionStart;
					
					// Remove tudo que não for número
					var formatedNumber = this.value.replace(/\D/g, '');

					// Limita o número de dígitos a 11
					if (formatedNumber.length > 11) {
						formatedNumber = formatedNumber.substring(0, 11);
					}

					// Aplica a máscara: (XX) XXXXX-XXXX
					if (formatedNumber.length === 0) {
						this.value = '';
					} else if (formatedNumber.length <= 2) {
						this.value = '(' + formatedNumber;
					} else if (formatedNumber.length <= 6) {
						this.value = '(' + formatedNumber.substring(0, 2) + ') ' + formatedNumber.substring(2);
					} else if (formatedNumber.length <= 10) {
						this.value = '(' + formatedNumber.substring(0, 2) + ') ' +
									formatedNumber.substring(2, 7) + '-' +
									formatedNumber.substring(7);
					} else { // exatamente 11 dígitos
						this.value = '(' + formatedNumber.substring(0, 2) + ') ' +
									formatedNumber.substring(2, 7) + '-' +
									formatedNumber.substring(7, 11);
					}

					// Ajusta a posição do cursor se for backspace
					if (e.inputType === 'deleteContentBackward') {
						this.setSelectionRange(cursorPosition, cursorPosition);
					}
				};

				inputCel.oninput = function (e) {
					let cursorPosition = this.selectionStart;
					
					// Remove tudo que não for número
					var formatedNumber = this.value.replace(/\D/g, '');

					// Limita o número de dígitos a 11
					if (formatedNumber.length > 11) {
						formatedNumber = formatedNumber.substring(0, 11);
					}

					// Aplica a máscara: (XX) XXXXX-XXXX
					if (formatedNumber.length === 0) {
						this.value = '';
					} else if (formatedNumber.length <= 2) {
						this.value = '(' + formatedNumber;
					} else if (formatedNumber.length <= 6) {
						this.value = '(' + formatedNumber.substring(0, 2) + ') ' + formatedNumber.substring(2);
					} else if (formatedNumber.length <= 10) {
						this.value = '(' + formatedNumber.substring(0, 2) + ') ' +
									formatedNumber.substring(2, 7) + '-' +
									formatedNumber.substring(7);
					} else { // exatamente 11 dígitos
						this.value = '(' + formatedNumber.substring(0, 2) + ') ' +
									formatedNumber.substring(2, 7) + '-' +
									formatedNumber.substring(7, 11);
					}

					// Ajusta a posição do cursor se for backspace
					if (e.inputType === 'deleteContentBackward') {
						this.setSelectionRange(cursorPosition, cursorPosition);
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
					return true;
				}
			</script>
<?php include(FOOTER_TEMPLATE); ?>