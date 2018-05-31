<?php
/**
 * Packaged by Rizal Fauzie <rizal@fauzie.my.id>
 */
function adminer_object() {
    // required to run any plugin
    include_once "./plugins/plugin.php";

    // autoloader
    foreach (glob("plugins/*.php") as $filename) {
        include_once "./$filename";
    }

    $plugins = array(
		new AdminerLoginServers(array('localhost','127.0.0.1')),
        new AdminerDatabaseHide(array('information_schema','performance_schema','mysql','sys')),
        new AdminerDumpZip,
        new AdminerEditForeign,
        new AdminerEditTextarea,
        new AdminerEnumOption,
        new AdminerQuickFilterTables,
		new AdminerJsonColumn,
		new AdminerVersionNoverify
    );

    return new AdminerPlugin($plugins);
}

// include original Adminer or Adminer Editor
include "./adminer.php";
