/**
 * Passa os dados do cliente para o Modal, e atualiza o link para exclusão
 */
$('#delete-modal-customer').on('show.bs.modal', function (event) {
  
	var button = $(event.relatedTarget);
	var id = button.data('customer');

	var modal = $(this);
	modal.find(".modal-title").text("Excluir Cliente: " + id);
	modal.find(".modal-body").text("Deseja realmente excluir este cliente? " + id);
	modal.find("#confirm").attr('href', 'delete.php?id=' + id);
});
 $('#delete-modal-gerente').on('show.bs.modal', function (event) {
  
	var button = $(event.relatedTarget);
	var id = button.data('gerente');

	var modal = $(this);
	modal.find(".modal-title").text("Excluir Gerente: " + id);
	modal.find(".modal-body").text("Deseja realmente excluir este gerente? " + id);
	modal.find("#confirm").attr('href', 'delete.php?id=' + id);
});
$('#delete-modal-user').on('show.bs.modal', function (event) {
  
	var button = $(event.relatedTarget);
	var id = button.data('user');

	var modal = $(this);
	modal.find(".modal-title").text("Excluir Usuário: " + id);
	modal.find(".modal-body").text("Deseja realmente excluir este usuário? " + id);
	modal.find("#confirm").attr('href', 'delete.php?id=' + id);
});
