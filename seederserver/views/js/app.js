// Abre Arquivo de Pastas (async)
function escolherArquivo() {
	Invoke.utils.filePicker(function(path) {
			//Arquivo Selecionado
			Toast.regular('Selecionado: ' + path, 3000);
		},
		//Cancelado
		function(reason) {
			Toast.regular('Cancelado: ' + reason);
		},
		//Erro
		function(error) {
			console.log(error);
		});
}

// Câmera (async)
function tirarFoto() {
	Invoke.utils.camera(function(path) {
			//Foto tirada
			Toast.regular('Foto: ' + path, 3000);
		},
		//Cancelado
		function(reason) {
			Toast.regular('Cancelado ' + reason);
		},
		//Erro
		function(error) {
			console.log(error);
		});
}

//Ação ao clicar no botão do toast
function toastCallback() {
	alert('Opa!');
}

//Spinner
function spinner(size) {
	eval('Spinner.on.' + size + '();');

	setTimeout(function() {
		Spinner.off(size);
	}, 2500);
}