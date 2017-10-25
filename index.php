<?php
function adminer_object() {
    // required to run any plugin
    include_once "./plugins/plugin.php";

    // autoloader
    foreach (glob("plugins/*.php") as $filename) {
        include_once "./$filename";
    }

    $plugins = array(
        new AdminerDatabaseHide(array('information_schema','performance_schema','mysql')),
        new AdminerDumpZip,
        new AdminerEditForeign,
        new AdminerEditTextarea,
        new AdminerEnumOption,
        new AdminerLoginServers(array('localhost','127.0.0.1')),
        new AdminerQuickFilterTables
    );

    /* It is possible to combine customization and plugins:
    class AdminerCustomization extends AdminerPlugin {
    }
    return new AdminerCustomization($plugins);
    */

    return new AdminerPlugin($plugins);
}

// include original Adminer or Adminer Editor
include "./adminer.php";
