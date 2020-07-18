<?php
defined('MOODLE_INTERNAL') || die();
require_once(__DIR__.'/upgradelib.php');
function xmldb_theme_ead_upgrade($oldversion) {
    return theme_ead_create_info_fields($oldversion);
}
