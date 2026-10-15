<?php

/** Add a "Format SQL" button next to the SQL command textarea and format the typed SQL with sql-formatter on click
* @link https://www.adminer.org/plugins/#use
* @uses sql-formatter, https://unpkg.com/sql-formatter@15.8.2
* @author Adminer plugins, https://www.adminer.org/plugins/
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
class AdminerFormatSql {
	const DIST = "https://unpkg.com/sql-formatter@15.8.2/dist/";

	function head() {
		echo Adminer\script_src(self::DIST . "sql-formatter.min.js");
		echo Adminer\script("addEventListener('DOMContentLoaded', function () {
	var textarea = qs('textarea[name=query]');
	if (!textarea || typeof sqlFormatter == 'undefined') {
		return;
	}
	var button = document.createElement('input');
	button.type = 'button';
	button.value = 'Format SQL';
	button.className = 'format-sql';
	button.title = 'Format SQL with sql-formatter';
	button.onclick = function () {
		var query = textarea.value;
		if (!query.trim()) {
			return;
		}
		try {
			textarea.value = sqlFormatter.format(query);
			fire(textarea, 'change'); // updates the JUSH highlighting
		} catch (e) {
			alert('SQL formatting failed: ' + e.message);
		}
	};
	// place the button in a new line right after the SQL textarea
	textarea.parentNode.insertBefore(document.createElement('br'), textarea.nextSibling);
	textarea.parentNode.insertBefore(button, textarea.nextSibling.nextSibling);
});
");
	}

	function csp(&$csp) {
		// source map referenced by the script, loaded by DevTools
		$csp[0]["connect-src"] .= " " . self::DIST . "sql-formatter.min.cjs.map";
	}

}
