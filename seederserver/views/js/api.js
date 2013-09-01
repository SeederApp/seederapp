/* ==============================================================================================
 *	Objeto da Aplicação
 * =========================================================================================== */

var App = {
	init: function() {
		console.log('[ App iniciado ]');

		// Mensagem de Boas-Vindas
		Toast.regular('Bem-vindo ao BFB Template!', 3000);

		// Registrando no BBM
		Bbm.register();

		// Abre pagina inicial
		bb.pushScreen('main.html', 'main');
	},

/* ==============================================================================================
 *	Window Cover - Atualização da Imagem Cover (App minimizado)
 * =========================================================================================== */

	ui: {
		windowCover: {
			setup: function(path) {
				console.log('[ setup active frames ]');

				// Altera o cover (App minimizado)
				blackberry.ui.cover.setContent(blackberry.ui.cover.TYPE_IMAGE, {
					path: path
				});

				setTimeout(function(){
					blackberry.ui.cover.updateCover();
				}, 0);
			}
		}
	}
};



/* ==============================================================================================
 *	BBM - BlackBerry Messenger
 * =========================================================================================== */

var Bbm = {
	registered: false,

	// Registra a aplicação com blackberry.bbm.platform APIs.
	register: function() {
		blackberry.event.addEventListener('onaccesschanged', function(accessible, status) {
			if (status === 'unregistered') {
				blackberry.bbm.platform.register({
					uuid: '5b54bb3a-ab66-11e2-a242-f23c91aec05e' // unique uuid
				});
			} else if (status === 'allowed') {
				Bbm.registered = accessible;
			}
		}, false);
	},

	// Atualiza a mensagem do BBM
	updateMessage: function() {
		function dialogCallBack(selection) {
			var txt = selection.promptText;
			blackberry.bbm.platform.self.setPersonalMessage(
				txt,
				function(accepted) {});
		}

		// standard async dialog to get new 'personal message' for bbm
		blackberry.ui.dialog.standardAskAsync("Seu novo status", blackberry.ui.dialog.D_PROMPT, dialogCallBack, {
			title: "BBM Status"
		});
	},

	// Invitar contato a baixar o App via BBM
	inviteToDownload: function() {
		blackberry.bbm.platform.users.inviteToDownload();
	}
};



/* ==============================================================================================
 *  Compartilhar - com Email, Mapas, NFC, Twitter, Facebook ect...
 * =========================================================================================== */

var Invoke = {

	// Lista dos Targets disponiveis
	targets: function(uri) {
		var title = 'Share';
		var request = {
			action: 'bb.action.SHARE',
			uri: uri,
			target_type: ["APPLICATION", "VIEWER", "CARD"]
		};

		blackberry.invoke.card.invokeTargetPicker(request, title,
			// sucesso
			function() {},

			// erro
			function(e) {});
	},

	// BlackBerry World 
	blackberryWorld: {

		// Pagina de Vendedor
		vendor: function(id) {
			blackberry.invoke.invoke({
					target: 'sys.appworld',
					action: 'bb.action.OPEN',
					uri: 'appworld://vendor/' + id
				},

				// sucesso
				function() {},

				// erro
				function() {});
		}
	},

	// Email
	email: function(to, subject, body) {
		var message = to + '?subject=' + subject + '&body=' + body;
		blackberry.invoke.invoke({
			target: 'sys.pim.uib.email.hybridcomposer',
			action: 'bb.action.OPEN, bb.action.SENDMAIL',
			type: 'message/rfc822',
			uri: 'mailto:' + message
		});
	},

	// BlackBerry Maps
	maps: function(address) {
		blackberry.invoke.invoke({
			action: 'bb.action.NAVIGATETO',
			type: 'application/vnd.blackberry.string.address',
			data: address
		});
	},

	// NFC
	nfc: function(uri) {
		blackberry.invoke.invoke({
			target: "sys.NFCViewer",
			action: "bb.action.SHARE",
			uri: uri
		}, function() {}, function() {});
	},

	// Twitter
	twitter: function(shareText) {
		blackberry.invoke.invoke({
			target: "Twitter",
			action: "bb.action.SHARE",
			type: "text/plain",
			data: shareText
		}, function() {}, function() {});
	},

	//Facebook
	facebook: function(shareText) {
		blackberry.invoke.invoke({
			target: "Facebook",
			action: "bb.action.SHARE",
			type: "text/plain",
			data: shareText
		}, function() {}, function() {});
	},

	utils: {

		// Arquivo de Pastas do BlackBerry
		filePicker: function(success, cancel, failure) {

			var details = {
				mode: blackberry.invoke.card.FILEPICKER_MODE_PICKER,
				viewMode: blackberry.invoke.card.FILEPICKER_VIEWER_MODE_GRID,
				sortBy: blackberry.invoke.card.FILEPICKER_SORT_BY_NAME,
				sortOrder: blackberry.invoke.card.FILEPICKER_SORT_ORDER_DESCENDING
			};

			blackberry.invoke.card.invokeFilePicker(details, function(path) {
					success(path);
				},

				// cancel callback
				function(reason) {
					cancel(reason);
				},

				// erro callback
				function(error) {
					if (error) {
						failure(error);
					}
				}
			);
		},

		// Camera
		camera: function(success, cancel, failure) {
			var mode = blackberry.invoke.card.CAMERA_MODE_PHOTO;
			blackberry.invoke.card.invokeCamera(mode, function(path) {
					success(path);
				},

				function(reason) {
					cancel(reason);
				},

				function(error) {
					if (error) {
						failure(error);
					}
				}
			);
		}
	}
};



/* ==============================================================================================
 *	TOASTS - Alert do BlackBerry 10
 * =========================================================================================== */

var Toast = {
	//Somente Mensagem
	regular: function(text, timeout) {
		try {
			timeout = timeout || false; // Caso não use um tempo de aberto, usar 'false' como padrão
			var options = {
				timeout: timeout
			};
			blackberry.ui.toast.show(text, options);
		} catch (e) {
			console.log('toast: ' + text);
			console.log(e);
		}
	},
	//Mensagem + Botão
	withButton: function(text, btnText, btnCallback, timeout) {
		try {
			timeout = timeout || false;
			var options = {
				timeout: timeout,
				buttonText: btnText,
				buttonCallback: eval(btnCallback)
			};
			blackberry.ui.toast.show(text, options, timeout);
		} catch (e) {
			console.log('toast: ' + text);
			console.log(e);
		}
	}
};



/* ===========================================================================================
 *	SPINNERS/LOADERS
 * ======================================================================================== */
// spinner divs estão em spinners.html

var Spinner = {
	off: function(size) {
		var el = document.getElementById('spinner-' + size);
		el.style.display = 'none';
	},

	on: {
		small: function() {
			var el = document.getElementById('spinner-small');
			el.style.display = 'block';
		},

		medium: function() {
			var el = document.getElementById('spinner-medium');
			el.style.display = 'block';
		},

		large: function() {
			var el = document.getElementById('spinner-large');
			el.style.display = 'block';
		}
	}
};