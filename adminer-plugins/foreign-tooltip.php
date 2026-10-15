<?php

/** Display the referenced row in a tooltip when hovering a foreign key value in select, similar to phpPgAdmin
* @link https://github.com/rimuln/adminer-plugins
* @author Lumír Návrat
* @license https://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license https://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
class AdminerForeignTooltip extends Adminer\Plugin {
	protected $maxColumns;
	protected $maxLength;

	/** @var string[][] column => [foreign key value => tooltip text] */
	protected $tooltips = array();
	protected $inside = false;

	/**
	* @param int $maxColumns maximum number of columns of the referenced row printed in the tooltip
	* @param int $maxLength maximum length of each printed value
	*/
	function __construct(int $maxColumns = 20, int $maxLength = 80) {
		$this->maxColumns = $maxColumns;
		$this->maxLength = $maxLength;
	}

	function rowDescriptions($rows, $foreignKeys) {
		if (!$rows) {
			return;
		}
		foreach ($rows[0] as $key => $val) {
			foreach ((array) $foreignKeys[$key] as $foreignKey) {
				if (
					count($foreignKey["source"]) != 1
					|| ($foreignKey["db"] != "" && $foreignKey["db"] != Adminer\DB) // Oracle fills the current owner
				) {
					continue;
				}
				$ids = array();
				foreach ($rows as $row) {
					if (isset($row[$key])) {
						$ids[$row[$key]] = Adminer\q($row[$key]);
					}
				}
				if (!$ids) {
					break;
				}
				$table = $foreignKey["table"];
				$schema = $_GET["ns"];
				$otherSchema = ($foreignKey["ns"] != "" && $foreignKey["ns"] != $schema && function_exists('Adminer\set_schema'));
				if ($otherSchema) {
					Adminer\set_schema($foreignKey["ns"]); // fields() and table() work in the current schema
				}
				$fields = Adminer\fields($table);
				$target = $foreignKey["target"][0];
				$columns = array();
				foreach ($fields as $name => $field) {
					if ($name == $target || (!Adminer\is_blob($field) && count($columns) < $this->maxColumns)) { // the key pairs the rows with the values
						$columns[] = Adminer\idf_escape($name);
					}
				}
				if ($columns) {
					// one query for all rows on the page
					$referenced = Adminer\get_rows(
						"SELECT " . implode(", ", $columns) . " FROM " . Adminer\table($table)
						. " WHERE " . Adminer\idf_escape($target) . " IN (" . implode(", ", $ids) . ")",
						null,
						"" // don't print errors, the tooltip is optional
					);
					foreach ($referenced as $row) {
						$lines = array(($foreignKey["ns"] != "" ? "$foreignKey[ns]." : "") . $table);
						foreach ($row as $name => $v) {
							$v = ($v === null ? "NULL" : preg_replace('~\s+~u', ' ', $v));
							if (preg_match('~^(.{' . $this->maxLength . '}).~su', $v, $match)) {
								$v = "$match[1]…";
							}
							$lines[] = "$name: $v";
						}
						$this->tooltips[$key][$row[$target]] = implode("\n", $lines);
					}
				}
				if ($otherSchema) {
					Adminer\set_schema($schema);
				}
				break;
			}
		}
	}

	function selectVal($val, $link, $field, $original) {
		if ($this->inside || $link == "" || !isset($this->tooltips[$field["field"]])) {
			return;
		}
		// the value can be replaced by a description (e.g. by select-foreign), the key is in the link to the referenced row
		parse_str((string) parse_url($link, PHP_URL_QUERY), $params);
		$id = (isset($params["where"][0]["val"]) ? $params["where"][0]["val"] : $original);
		if ($id === null || !isset($this->tooltips[$field["field"]][$id])) {
			return;
		}
		$tooltip = $this->tooltips[$field["field"]][$id];
		// let Adminer and the other plugins format the value, then add the title to the link
		$this->inside = true;
		$return = Adminer\adminer()->selectVal($val, $link, $field, $original);
		$this->inside = false;
		$return = preg_replace("~^(<a [^>]*?) title='[^']*'~", '\1', $return, 1); // e.g. select-foreign puts the key value to title
		return preg_replace('~^<a ~', "<a title='" . Adminer\h($tooltip) . "' ", $return, 1);
	}

	protected $translations = array(
		'cs' => array('' => 'Při najetí myší na hodnotu cizího klíče zobrazí odkazovaný řádek v bublině, podobně jako phpPgAdmin'),
		'de' => array('' => 'Zeigt beim Überfahren eines Fremdschlüsselwerts die referenzierte Zeile in einem Tooltip an, ähnlich wie phpPgAdmin'), // Claude Opus 5.5
		'hr' => array('' => 'Prikazuje referencirani redak u oblačiću pri prelasku mišem preko vrijednosti stranog ključa, slično kao phpPgAdmin'), // Claude Opus 5.5
		'ja' => array('' => '外部キーの値にマウスを重ねると参照先の行をツールチップに表示、phpPgAdmin と同様'), // Claude Opus 5.5
		'pl' => array('' => 'Po najechaniu na wartość klucza obcego wyświetla wskazywany wiersz w dymku, podobnie jak phpPgAdmin'), // Claude Opus 5.5
		'ro' => array('' => 'Afișează rândul referit într-un tooltip la trecerea cu mouse-ul peste valoarea cheii străine, similar cu phpPgAdmin'), // Claude Opus 5.5
		'sk' => array('' => 'Pri prechode myšou nad hodnotou cudzieho kľúča zobrazí odkazovaný riadok v bubline, podobne ako phpPgAdmin'), // Claude Opus 5.5
		'zh' => array('' => '鼠标悬停在外键值上时在提示框中显示被引用的行，类似于 phpPgAdmin'), // Claude Opus 5.5
	);
}
