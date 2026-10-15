<?php

// autoloader
// foreach (glob("adminer-plugins/*.php") as $filename) {
//     include_once "./$filename";
// }

// defaults
$logo = null;
$servers = array();
$hiddens = array('information_schema','performance_schema','mysql','sys','test');
$loginip = array('127.0.0.*');
$proxyip = array();
$passwrd = '';

if (file_exists("./config.ini")) {
    $configs = parse_ini_file("./config.ini", true);
    if (isset($configs['hidden'])) {
        $hiddens = array_values($configs['hidden']);
    }
    if (!empty($configs['login'])) {
        if (isset($configs['login']['ips'])) {
            $loginip = explode(',', $configs['login']['ips']);
        }
        if (isset($configs['login']['forwarded_for'])) {
            $proxyip = explode(',', $configs['login']['forwarded_for']);
        }
        if (!empty($configs['login']['logo']) && filter_var($configs['login']['logo'], FILTER_VALIDATE_URL)) {
            $logo = $configs['login']['logo'];
        }
    }
    if (isset($configs['servers'])) {
        foreach ($configs['servers'] as $name => $host) {
            $servers[ $host ] = $name;
        }
    }
}

return array(
    new AdminerDatabaseHide($hiddens),
    new AdminerLoginIp($loginip, $proxyip),
    new AdminerLoginServers($servers),
    new Adminer\CopyStructureExport(),
    new AdminerEditCalendar(true),
    new AdminerEditForeignSearch(50),
    new AdminerEditForeign(10),
    new AdminerDotJs(),
    new AdminerDumpZip(),
    new AdminerEditTextarea(),
    new AdminerEnumOption(),
    new AdminerForeignTooltip(),
    new AdminerFormatSql(),
    new AdminerHumanReadableSize(),
    new AdminerJsonPreview(),
    new AdminerPrettyJsonColumn(),
    new AdminerTableStructure(),
    new AdminerTablesFilter(),
    new AdminerVersionNoverify(),
);
