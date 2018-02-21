<?php
defined('MOODLE_INTERNAL') || die();

if (!function_exists('dump')) {function dump(...$params) { echo '<pre>'; var_dump(func_get_args()); echo '</pre>'; }}
if (!function_exists('dumpd')) {function dumpd(...$params) { echo '<pre>'; var_dump(func_get_args()); echo '</pre>'; die(); }}

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot.'/calendar/lib.php');


echo $OUTPUT->render_from_template('theme_boost_eadifrn/columns2', get_ead_ifrn_template_context());
