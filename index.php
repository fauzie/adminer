<?php
/**
 * Packaged by Rizal Fauzie <rizal@fauzie.my.id>
 */
function adminer_object() {
    // required to run any plugin
    include_once "./plugins/plugin.php";
    $adminer = new AdminerPlugin(array());

    // autoloader
    foreach (glob("plugins/*.php") as $filename) {
        include_once "./$filename";
    }
    
    // defaults
    $logo = null;
    $servers = array( '127.0.0.1' => 'localhost' );
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
        if (isset($configs['password'],$configs['password']['hash'])) {
            $passwrd = $configs['password']['hash'];
        }
        if (isset($configs['servers'])) {
            foreach ($configs['servers'] as $name => $host) {
                $servers[ $host ] = $name;
            }
        }
    }

    $adminer->plugins = array(
		new AdminerLoginServers($servers,'server',$logo),
        new AdminerDatabaseHide($hiddens),
        new AdminerLoginIp($loginip,$proxyip),
        new AdminerLoginPasswordLess($passwrd),
        new AdminerPrettyJsonColumn($adminer),
        new AdminerDumpZip,
        new AdminerDumpJson,
        new AdminerEditForeign,
        new AdminerEditTextarea,
        new AdminerEnumOption,
        new AdminerQuickFilterTables,
		new AdminerVersionNoverify
    );

    return $adminer;
}

// include original Adminer or Adminer Editor
include "./adminer.php";
