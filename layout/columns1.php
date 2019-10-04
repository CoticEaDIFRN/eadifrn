<?php
defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot.'/calendar/lib.php');


$OUTPUT->doctype();
echo $OUTPUT->render_from_template('theme_boost_eadifrn/columns1', get_ead_ifrn_template_context());
