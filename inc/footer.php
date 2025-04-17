
		</main> 
		<!-- Footer -->
		<footer class="footer-class text-white pt-2 pb-4"> 
			<div class="container text-center text-md-left">
				<div class="row text-center text-md-left">
					<div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
						<h5 class="text-uppercase mb-4 font-weight-bold footer-title">Empresa</h5>
						<p>&copy;2024 - <?php $data = new Datetime("now", new DateTimeZone("America/Sao_Paulo")); echo $data->format("Y"); ?>  - RENAN E GUSTAVO</p>	
					</div>
					<div class="col-md-3 col-lg-3 col-xl-3 mx-auto mt-3">
						<h5 class="text-uppercase mb-4 font-weight-bold footer-title">Fornecedores</h5>
						<p>Trabalhamos com fornecedores comprometidos com a qualidade, responsabilidade e eficiência. Valorizamos parcerias sólidas para garantir os melhores resultados aos nossos clientes.</p>
					</div>
					<div class="col-md-4 col-lg-4 col-xl-4 mx-auto mt-3">
						<h5 class="text-uppercase mb-4 font-weight-bold footer-title">Redes Sociais</h5>
						<div class="row">
							<a href="#" class="mt-2 footer-links"><i class="fa-brands fa-facebook fa-xl"></i> Facebook</a>
							<a href="#" class="mt-2 footer-links"><i class="fa-brands fa-instagram fa-xl"></i> Instagram</a>
							<a href="#" class="mt-2 footer-links"><i class="fa-brands fa-whatsapp fa-xl"></i> Whatsapp</a>
							<a href="#" class="mt-2 footer-links"><i class="fa-brands fa-linkedin fa-xl"></i> Linkedin</a>
						</div>
					</div>
				</div>
			</div>
		</footer>	
		<script src="<?php echo BASEURL; ?>js/jquery-3.7.1.js"></script>
		<script src="<?php echo BASEURL; ?>js/fontawesome/all.min.js"></script>
		<script src="<?php echo BASEURL; ?>js/bootstrap/bootstrap.bundle.min.js"></script>
		<script src="<?php echo BASEURL; ?>js/main.js"></script>
		<script src="<?php echo BASEURL; ?>js/script.js"></script>
	</body>
</html>