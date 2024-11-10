(function () {

	let getEl = function (selector) {
		return document.querySelector(selector);
	}

	const baseUrl = getEl('#baseUrl').value;
	const clientId = getEl('#client_id').value;
	if (!clientId) return;

	let ajax = function (method, url, data) {

		const promise = new Promise((resolve, reject) => {

			let xhr = new XMLHttpRequest();

			xhr.open(method, url, true);

			xhr.onload = function (e) {
				if (xhr.readyState === 4 && xhr.status === 200) {
					resolve(xhr);
				} else {
					reject()
				}
			}

			xhr.send(data);

		});

		return promise;

	}

	let showConsoleOutput = getEl('.cmd-output'),
		consoleOutputTrigger = getEl('.console-output-trigger');

	function isConsoleOutputHidden() {
		let displayValue = window.getComputedStyle(showConsoleOutput).display;

		if (displayValue === 'none') {
			return true;
		} else {
			return false;
		}
	}

	consoleOutputTrigger.addEventListener('click', e => {

		if (isConsoleOutputHidden()) {
			showConsoleOutput.style.display = 'flex';
			consoleOutputTrigger.textContent = 'Hide console';
		} else {
			showConsoleOutput.style.display = 'none';
			consoleOutputTrigger.textContent = 'Show console';
		}

	}, false);

	class Helpers {

		static getFormattedDate() {

			let dateObject = new Date,
				date = '';

			const day = String(dateObject.getDate()).padStart(2, '0');
			const month = String(dateObject.getMonth() + 1).padStart(2, '0');
			const year = dateObject.getFullYear();

			const time = dateObject.toTimeString().split(' ')[0];

			return `${day}-${month}-${year} ${time}`;

		}

	}



	class CommandResult {


		appendDataToContainer(data) {

			let html = '';

			for (let key in data) {
				html += `
<div class="cmd-output-row">
<pre>${key}

${data[key]}</pre>
</div>`;
			}

			this.commandResultsContainer.innerHTML = html;

		}

		sendAjax() {

			let data = new FormData();
			data.append('data-request', 'get-cmd-results');
			data.append('client_id', clientId);

			let promise = ajax('POST', baseUrl, data);

			promise.then(xhr => {
				this.appendDataToContainer(JSON.parse(xhr.response));
			});

		}

		constructor() {
			this.commandResultsContainer = getEl('.cmd-output-left');
		}

	}

	let c = new CommandResult;
	c.sendAjax();

	setInterval(() => { c.sendAjax(); }, 4000);

	class CommandLine {

		getCommandHistoryElementHtml(command) {

			const parser = new DOMParser();

			let htmlPattern = `
				<div class="cmd-output-row no-border no-margin">
					<p>${Helpers.getFormattedDate()}<br>${command}</p>
				</div>
			`;

			return parser.parseFromString(htmlPattern, 'text/html').querySelector('.cmd-output-row');

		}

		saveCommandToHistory(command) {

			let html = this.getCommandHistoryElementHtml(command);
			this.commandHistoryContainer.appendChild(html);

		}

		sendCommand(command) {
			let data = new FormData(this.form);
			ajax('POST', baseUrl, data);
		}

		bindEvents() {
			this.commandLine.addEventListener('keydown', e => {

				if (e.which === 13) {
					e.preventDefault();
					if (!e.target.value) return;
					this.saveCommandToHistory(e.target.value);
					if (this.sendCommand(e.target.value)) {
						this.saveCommandToHistory(e.target.value);
					}
					e.target.value = '';
				}

			}, false);
		}

		constructor() {
			this.commandLine = getEl('.console-command-line-input');
			this.form = getEl('#cmd-form');

			this.commandHistoryContainer = getEl('.cmd-output-right');

			this.bindEvents();
		}

	}

	new CommandLine;


})();