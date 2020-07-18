<?php
defined('MOODLE_INTERNAL') || die();
require_once(__DIR__.'/upgradelib.php');
function xmldb_theme_ead_install() {
    return theme_ead_new_field_category(NULL);
}
