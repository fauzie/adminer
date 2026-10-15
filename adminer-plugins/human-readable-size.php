<?php
/**
 * Converts size values to human-readable format, configurable through AdminerConfig
 * @category Plugin
 * @author Shocker
 * @license https://www.apache.org/licenses/LICENSE-2.0 Apache-2.0
*/
class AdminerHumanReadableSize extends Adminer\Plugin {
    function formatSizeValue($val) {
        if (!Adminer\get_setting("human-readable-size-values", "adminer_config", true)) {
            return null;
        }

        if ($val <= 0) {
            return '0 B';
        }

        $base = 1024;
        $units = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB'];

        $exponent = min((int) floor(log($val, $base)), count($units) - 1);
        if ($exponent < 0) {
            $exponent = 0;
        }

        $value = $val / ($base ** $exponent);
        $unit = $units[$exponent];

        // emphasize unit label based on exponent threshold if enabled
        if (Adminer\get_setting("human-readable-size-emphasize", "adminer_config", false)) {
            if ($exponent >= 4) // TB+
                $unit = "<strong style='color: #dc2626;'>$unit</strong>"; // bold red
            elseif ($exponent === 3) // GB
                $unit = "<strong style='color: #d97706;'>$unit</strong>"; // bold orange
            elseif ($exponent === 2) // MB
                $unit = "<span style='font-weight: 600;'>$unit</span>"; // bold default
        }

        $precision = Adminer\get_setting('human-readable-size-precision', 'adminer_config', 2);

        return round($value, $precision) . " $unit";
    }

	function config() {
		$options = array(
			true => $this->lang('Yes'),
			false => $this->lang('No'),
		);
        $enabled = Adminer\get_setting("human-readable-size-values", "adminer_config", true);
        $emphasizeEnabled = Adminer\get_setting("human-readable-size-emphasize", "adminer_config", false);

        $precision = Adminer\get_setting('human-readable-size-precision', 'adminer_config', 2);
        $precisionInput = '<input type="number" name="config[human-readable-size-precision]" value="' . Adminer\h($precision) . '" min="0" max="6" step="1" size="3">';

		return array(
            $this->lang('Human-readable size values') => Adminer\html_radios('config[human-readable-size-values]', $options, $enabled, "<br>"),
            $this->lang('Human-readable size values precision') => $precisionInput,
            $this->lang('Human-readable size values emphasize') => Adminer\html_radios('config[human-readable-size-emphasize]', $options, $emphasizeEnabled, "<br>"),
        );
	}
    
    // AI translations
    protected $translations = array(
        'cs' => array(
            '' => 'Převede hodnoty velikosti do čitelného formátu, konfigurovatelné přes AdminerConfig',
            'Human-readable size values' => 'Hodnoty velikosti v čitelném formátu',
            'Human-readable size values precision' => 'Přesnost hodnot velikosti v čitelném formátu',
            'Yes' => 'Ano',
            'No' => 'Ne',
        ),
        'de' => array(
            '' => 'Konvertiert Größenwerte in ein menschenlesbares Format, konfigurierbar über AdminerConfig',
            'Human-readable size values' => 'Menschenlesbare Größenangaben',
            'Human-readable size values precision' => 'Genauigkeit der menschenlesbaren Größenangaben',
            'Yes' => 'Ja',
            'No' => 'Nein',
        ),
        'hr' => array(
            '' => 'Pretvara vrijednosti veličine u čitljiv format, konfigurabilno putem AdminerConfiga',
            'Human-readable size values' => 'Veličine u čitljivom formatu',
            'Human-readable size values precision' => 'Preciznost veličina u čitljivom formatu',
            'Yes' => 'Da',
            'No' => 'Ne',
        ),
        'ja' => array(
            '' => 'サイズ値を人間が読みやすい形式に変換（AdminerConfig で設定可能）',
            'Human-readable size values' => '人間が読みやすいサイズ値',
            'Human-readable size values precision' => '人間が読みやすいサイズ値の精度',
            'Yes' => 'はい',
            'No' => 'いいえ',
        ),
        'pl' => array(
            '' => 'Konwertuje wartości rozmiarów do formatu czytelnego dla człowieka, konfigurowalne przez AdminerConfig',
            'Human-readable size values' => 'Czytelne dla człowieka wartości rozmiaru',
            'Human-readable size values precision' => 'Precyzja czytelnych dla człowieka wartości rozmiaru',
            'Yes' => 'Tak',
            'No' => 'Nie',
        ),
        'ro' => array(
            '' => 'Convertește valorile dimensiunilor într-un format ușor de citit, configurabil prin AdminerConfig',
            'Human-readable size values' => 'Valori ale dimensiunii ușor de citit',
            'Human-readable size values precision' => 'Precizia valorilor dimensiunii ușor de citit',
            'Yes' => 'Da',
            'No' => 'Nu',
        ),
        'sk' => array(
            '' => 'Konvertuje hodnoty veľkosti do čitateľného formátu, konfigurovateľné cez AdminerConfig',
            'Human-readable size values' => 'Hodnoty veľkosti v čitateľnom formáte',
            'Human-readable size values precision' => 'Presnosť hodnôt veľkosti v čitateľnom formáte',
            'Yes' => 'Áno',
            'No' => 'Nie',
        ),
        'zh' => array(
            '' => '将大小数值转换为人类可读格式，可通过 AdminerConfig 进行配置',
            'Human-readable size values' => '人类可读的大小数值',
            'Human-readable size values precision' => '人类可读大小数值的精度',
            'Yes' => '是',
            'No' => '否',
        ),
    );    
}
