<?php

/** Select a foreign key in the edit form from a searchable, paged list showing the data of the referenced rows
* @link https://github.com/rimuln/adminer-plugins
* @author Lumír Návrat
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
class AdminerEditForeignSearch extends Adminer\Plugin {
	protected $pageSize;
	protected $labelColumns;
	protected $scriptPrinted = false;
	protected $error = "";

	/**
	* @param int $pageSize number of rows on one page of the list
	* @param int $labelColumns number of text columns of the referenced row printed next to its key
	*/
	function __construct(int $pageSize = 20, int $labelColumns = 3) {
		$this->pageSize = $pageSize;
		$this->labelColumns = $labelColumns;
	}

	/** Answer the requests of the list, the user is already logged in here */
	function afterConnect() {
		if (!isset($_POST["fk_search"])) {
			return;
		}
		header("Content-Type: application/json; charset=utf-8");
		$table = ($_GET["edit"] != "" ? $_GET["edit"] : $_GET["select"]);
		$foreignKey = ($table != "" ? $this->foreignKey($table, $_POST["fk_search"]) : null);
		if (!Adminer\verify_token() || !$foreignKey) {
			header("HTTP/1.1 403 Forbidden");
			echo json_encode(array("error" => ($foreignKey ? "Invalid CSRF token." : "Unknown foreign key.")));
			exit;
		}
		$return = $this->withSchema($foreignKey, function () use ($foreignKey) {
			list($id, $labels) = $this->columns($foreignKey);
			$from = "SELECT $id" . ($labels ? ", " . implode(", ", $labels) : "") . " FROM " . Adminer\table($foreignKey["table"]);
			if (isset($_POST["fk_id"])) {
				$rows = Adminer\get_rows(Adminer\limit($from, " WHERE $id = " . Adminer\q($_POST["fk_id"]), 1), null, "");
				return array("rows" => $this->rows($rows));
			}
			$where = array();
			$search = trim((string) $_POST["q"]);
			if ($search != "") {
				$pattern = Adminer\q("%$search%");
				foreach (array_merge(array($id), $labels) as $column) {
					$where[] = (Adminer\JUSH == "pgsql" ? "CAST($column AS text) ILIKE $pattern" : "$column LIKE $pattern");
				}
			}
			$page = min(max(0, (int) $_POST["page"]), 1000000); // a huge page would overflow OFFSET to a float
			$rows = Adminer\get_rows(Adminer\limit(
				$from,
				($where ? " WHERE " . implode(" OR ", $where) : "") . " ORDER BY " . ($labels ? "$labels[0], " : "") . $id,
				$this->pageSize + 1,
				$page * $this->pageSize
			), null, "");
			$connectionError = Adminer\connection()->error;
			return array(
				"rows" => $this->rows(array_slice($rows, 0, $this->pageSize)),
				"more" => count($rows) > $this->pageSize,
				"page" => $page,
			) + (!$rows && $connectionError ? array("error" => $connectionError) : array());
		});
		echo json_encode($return, JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE);
		exit;
	}

	function editInput($table, $field, $attrs, $value) {
		if (is_array($value) || !($foreignKey = $this->foreignKey($table, $field["field"]))) {
			return;
		}
		$label = "";
		if ($value !== null && $value !== "") {
			$label = $this->withSchema($foreignKey, function () use ($foreignKey, $value) {
				list($id, $labels) = $this->columns($foreignKey);
				if (!$labels) {
					return "";
				}
				$rows = Adminer\get_rows(Adminer\limit(
					"SELECT $id, " . implode(", ", $labels) . " FROM " . Adminer\table($foreignKey["table"]),
					" WHERE $id = " . Adminer\q($value),
					1
				), null, "");
				if (!$rows && Adminer\connection()->error) {
					$this->error = Adminer\connection()->error;
				}
				$rows = $this->rows($rows);
				return ($rows ? $rows[0][1] : "");
			});
		}
		$error = $this->error;
		$this->error = "";
		$return = "<span class='fk-combo' data-column='" . Adminer\h($field["field"]) . "'>"
			. "<input$attrs value='" . Adminer\h($value) . "' size='10' class='fk-id' autocomplete='off' title='" . Adminer\h($foreignKey["table"] . "." . $foreignKey["target"][0]) . "'> "
			. "<input type='search' class='fk-q' size='40' autocomplete='off' placeholder='" . Adminer\h($this->lang('Search in %s…', $foreignKey["table"])) . "' value='" . Adminer\h($label) . "' data-label='" . Adminer\h($label) . "'>"
			. "<div class='fk-list' hidden></div>"
			. ($error != "" ? " <span class='error'>" . Adminer\h($error) . "</span>" : "")
			. "</span>"
		;
		if (!$this->scriptPrinted) {
			$this->scriptPrinted = true;
			$return .= $this->assets();
		}
		return $return;
	}

	/** Get the single-column foreign key of a column
	* @return ?array ForeignKey
	*/
	protected function foreignKey(string $table, string $column) {
		static $foreignKeys = array();
		if (!isset($foreignKeys[$table])) {
			$foreignKeys[$table] = Adminer\column_foreign_keys($table);
		}
		foreach ((array) $foreignKeys[$table][$column] as $foreignKey) {
			if (count($foreignKey["source"]) == 1 && ($foreignKey["db"] == "" || $foreignKey["db"] == Adminer\DB)) {
				return $foreignKey;
			}
		}
		return null;
	}

	/** Run a callback in the schema of the referenced table */
	protected function withSchema(array $foreignKey, callable $callback) {
		$schema = $_GET["ns"];
		$otherSchema = ($foreignKey["ns"] != "" && $foreignKey["ns"] != $schema && function_exists('Adminer\set_schema'));
		if ($otherSchema) {
			Adminer\set_schema($foreignKey["ns"]); // fields() and table() work in the current schema
		}
		$return = $callback();
		if ($otherSchema) {
			Adminer\set_schema($schema);
		}
		return $return;
	}

	/** Get the escaped key column and the escaped text columns describing the referenced row
	* @return array{string, list<string>}
	*/
	protected function columns(array $foreignKey): array {
		static $columns = array();
		$key = $foreignKey["ns"] . "." . $foreignKey["table"];
		if (!isset($columns[$key])) {
			$labels = array();
			foreach (Adminer\fields($foreignKey["table"]) as $name => $field) {
				if ($name != $foreignKey["target"][0] && preg_match('~char|text~', $field["type"]) && count($labels) < $this->labelColumns) {
					$labels[] = Adminer\idf_escape($name);
				}
			}
			$columns[$key] = array(Adminer\idf_escape($foreignKey["target"][0]), $labels);
		}
		return $columns[$key];
	}

	/** Convert result rows to [key, label] pairs
	* @return list<array{string, string}>
	*/
	protected function rows(array $rows): array {
		$return = array();
		foreach ($rows as $row) {
			$id = array_shift($row);
			$return[] = array((string) $id, implode(" · ", array_filter($row, function ($val) {
				return $val !== null && $val !== "";
			})));
		}
		return $return;
	}

	protected function assets(): string {
		$texts = json_encode(array(
			"empty" => $this->lang('No rows.'),
			"prev" => $this->lang('previous'),
			"next" => $this->lang('next'),
			"page" => $this->lang('page'),
		), JSON_UNESCAPED_UNICODE);
		return "<style>
.fk-combo { position: relative; display: inline-block; white-space: nowrap; }
.fk-list { position: absolute; z-index: 10; left: 0; top: 100%; min-width: 100%; max-height: 24em; overflow: auto; background: var(--bg, #fff); color: var(--fg, #000); border: 1px solid #999; box-shadow: 2px 2px 6px rgba(0, 0, 0, .3); }
.fk-item { padding: 2px 6px; cursor: pointer; }
.fk-item b { display: inline-block; min-width: 4em; margin-right: 6px; }
.fk-item:hover, .fk-item.fk-active { background: #006AEB; color: #fff; }
.fk-nav { display: flex; gap: 6px; align-items: center; justify-content: space-between; padding: 3px 6px; border-top: 1px solid #ccc; font-size: 90%; }
.fk-nav button { cursor: pointer; }
.fk-empty { padding: 3px 6px; font-style: italic; }
</style>\n" . Adminer\script("(() => {
const texts = $texts;
const url = location.href;
function post(combo, params) {
	const data = new FormData();
	data.append('token', combo.closest('form').querySelector('[name=\"token\"]').value);
	data.append('fk_search', combo.dataset.column);
	for (const key in params) {
		data.append(key, params[key]);
	}
	return fetch(url, { method: 'POST', body: data, credentials: 'same-origin' }).then(response => response.text().then(text => {
		try {
			return JSON.parse(text);
		} catch (e) {
			// print whatever the server returned instead of JSON, e.g. a PHP error
			const body = new DOMParser().parseFromString(text, 'text/html').body; // inert document, unlike innerHTML it doesn't load images
			throw new Error('HTTP ' + response.status + ': ' + body.textContent.replace(/\\s+/g, ' ').trim().slice(0, 300));
		}
	}));
}
function message(list, text) {
	list.textContent = '';
	list.append(Object.assign(document.createElement('div'), { className: 'fk-empty', textContent: text }));
	list.hidden = false;
}
// the script is printed after the first combo, the others are not parsed yet
document.addEventListener('DOMContentLoaded', () => document.querySelectorAll('.fk-combo').forEach(combo => {
	const id = combo.querySelector('.fk-id');
	const q = combo.querySelector('.fk-q');
	const list = combo.querySelector('.fk-list');
	let page = 0, timer, request = 0, active = -1;
	const term = () => (q.value === q.dataset.label ? '' : q.value);
	function items() {
		return list.querySelectorAll('.fk-item');
	}
	function highlight(i) {
		const all = items();
		if (!all.length) {
			return;
		}
		active = (i + all.length) % all.length;
		all.forEach((item, n) => item.classList.toggle('fk-active', n == active));
		all[active].scrollIntoView({ block: 'nearest' });
	}
	const fun = combo.closest('tr') && combo.closest('tr').querySelector('select[name^=\"function[\"]');
	function clear() {
		id.value = '';
		q.value = q.dataset.label = '';
	}
	function choose(row) {
		if (fun && fun.value == 'NULL') {
			fun.value = ''; // a chosen row overrides NULL
			fun.dispatchEvent(new Event('change', { bubbles: true }));
		}
		id.value = row[0];
		q.value = q.dataset.label = row[1];
		id.dispatchEvent(new Event('input', { bubbles: true }));
		id.dispatchEvent(new Event('change', { bubbles: true }));
		close();
	}
	function close() {
		list.hidden = true;
		active = -1;
		if (q.value !== q.dataset.label) {
			q.value = q.dataset.label;
		}
	}
	function load() {
		const current = ++request;
		post(combo, { q: term(), page }).then(result => {
			if (current != request) {
				return;
			}
			list.textContent = '';
			active = -1;
			if (result.error) {
				list.append(Object.assign(document.createElement('div'), { className: 'fk-empty', textContent: result.error }));
			} else if (!result.rows.length) {
				list.append(Object.assign(document.createElement('div'), { className: 'fk-empty', textContent: texts.empty }));
			}
			(result.rows || []).forEach(row => {
				const item = document.createElement('div');
				item.className = 'fk-item';
				item.append(Object.assign(document.createElement('b'), { textContent: row[0] }), row[1]);
				item.addEventListener('mousedown', event => {
					event.preventDefault();
					choose(row);
				});
				list.append(item);
			});
			if (page || result.more) {
				const nav = document.createElement('div');
				nav.className = 'fk-nav';
				const button = (text, enabled, delta) => {
					const b = Object.assign(document.createElement('button'), { type: 'button', textContent: text, disabled: !enabled });
					b.addEventListener('mousedown', event => {
						event.preventDefault();
						page += delta;
						load();
					});
					return b;
				};
				nav.append(button('◂ ' + texts.prev, page > 0, -1), texts.page + ' ' + (page + 1), button(texts.next + ' ▸', result.more, 1));
				list.append(nav);
			}
			list.hidden = false;
		}).catch(error => current == request && message(list, error.message));
	}
	q.addEventListener('focus', () => {
		q.select();
		page = 0;
		load();
	});
	q.addEventListener('click', () => {
		if (list.hidden) { // focus stays in the field after choosing a row
			page = 0;
			load();
		}
	});
	q.addEventListener('input', () => {
		page = 0;
		clearTimeout(timer);
		timer = setTimeout(load, 250);
	});
	q.addEventListener('blur', close);
	q.addEventListener('keydown', event => {
		if (event.key == 'ArrowDown' || event.key == 'ArrowUp') {
			event.preventDefault();
			if (list.hidden) {
				load();
			} else {
				highlight(active + (event.key == 'ArrowDown' ? 1 : -1));
			}
		} else if (event.key == 'Enter') {
			event.preventDefault(); // don't submit the form
			const all = items();
			if (!list.hidden && all[Math.max(0, active)]) {
				all[Math.max(0, active)].dispatchEvent(new MouseEvent('mousedown'));
			}
		} else if (event.key == 'Escape' && !list.hidden) {
			event.preventDefault();
			event.stopPropagation();
			close();
		} else if (event.key == 'PageDown' && !list.hidden && list.querySelector('.fk-nav button:last-child:not(:disabled)')) {
			event.preventDefault();
			page++;
			load();
		} else if (event.key == 'PageUp' && !list.hidden && page > 0) {
			event.preventDefault();
			page--;
			load();
		}
	});
	id.addEventListener('input', () => {
		if (id.value === '') {
			q.value = q.dataset.label = '';
		}
	});
	if (fun) {
		fun.addEventListener('change', () => {
			if (fun.value == 'NULL') {
				clear();
				close();
			}
		});
	}
	id.addEventListener('change', event => {
		if (!event.isTrusted || id.value === '') {
			return;
		}
		post(combo, { fk_id: id.value }).then(result => {
			q.value = q.dataset.label = (result.rows && result.rows.length ? result.rows[0][1] : '?');
			if (result.error) {
				message(list, result.error);
			}
		}).catch(error => message(list, error.message));
	});
}));
})();");
	}

	protected $translations = array(
		'cs' => array(
			'' => 'Výběr cizího klíče v editačním formuláři z vyhledávacího stránkovaného seznamu s daty odkazovaných řádků',
			'Search in %s…' => 'Hledat v %s…',
			'No rows.' => 'Žádné řádky.',
			'previous' => 'předchozí',
			'next' => 'další',
			'page' => 'strana',
		),
		'de' => array( // Claude Opus 5.5
			'' => 'Wählt den Fremdschlüssel im Bearbeitungsformular aus einer durchsuchbaren, seitenweisen Liste mit den Daten der referenzierten Zeilen aus',
			'Search in %s…' => 'In %s suchen…',
			'No rows.' => 'Keine Zeilen.',
			'previous' => 'vorherige',
			'next' => 'nächste',
			'page' => 'Seite',
		),
		'hr' => array( // Claude Opus 5.5
			'' => 'Odabir stranog ključa u obrascu za uređivanje iz pretraživog popisa po stranicama s podacima referenciranih redaka',
			'Search in %s…' => 'Pretraži %s…',
			'No rows.' => 'Nema redaka.',
			'previous' => 'prethodna',
			'next' => 'sljedeća',
			'page' => 'stranica',
		),
		'ja' => array( // Claude Opus 5.5
			'' => '編集フォームで、参照先の行のデータを表示する検索・ページ送り可能な一覧から外部キーを選択',
			'Search in %s…' => '%s を検索…',
			'No rows.' => '行がありません。',
			'previous' => '前へ',
			'next' => '次へ',
			'page' => 'ページ',
		),
		'pl' => array( // Claude Opus 5.5
			'' => 'Wybór klucza obcego w formularzu edycji z przeszukiwalnej, stronicowanej listy z danymi wskazywanych wierszy',
			'Search in %s…' => 'Szukaj w %s…',
			'No rows.' => 'Brak wierszy.',
			'previous' => 'poprzednia',
			'next' => 'następna',
			'page' => 'strona',
		),
		'ro' => array( // Claude Opus 5.5
			'' => 'Selectarea cheii străine în formularul de editare dintr-o listă paginată, cu căutare, care afișează datele rândurilor referite',
			'Search in %s…' => 'Caută în %s…',
			'No rows.' => 'Niciun rând.',
			'previous' => 'anterioara',
			'next' => 'următoarea',
			'page' => 'pagina',
		),
		'sk' => array( // Claude Opus 5.5
			'' => 'Výber cudzieho kľúča v editačnom formulári z vyhľadávacieho stránkovaného zoznamu s údajmi odkazovaných riadkov',
			'Search in %s…' => 'Hľadať v %s…',
			'No rows.' => 'Žiadne riadky.',
			'previous' => 'predchádzajúca',
			'next' => 'ďalšia',
			'page' => 'strana',
		),
		'zh' => array( // Claude Opus 5.5
			'' => '在编辑表单中从可搜索、分页的列表中选择外键，列表显示被引用行的数据',
			'Search in %s…' => '在 %s 中搜索…',
			'No rows.' => '没有行。',
			'previous' => '上一页',
			'next' => '下一页',
			'page' => '页',
		),
	);
}
