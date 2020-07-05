<?php
/**
 * Theme EaD - Columns 2 layout
 *
 * @package    theme_ead
 * @author     Sueldo Sales <sueldosales@gmail.com>
 * @author     Kelson C. Medeiros <kelsoncm@gmail.com>
 * @package    theme_ead
 * @copyright  2017 IFRN <https://ifrn.edu.br>
 * @license    MIT https://opensource.org/licenses/MIT
 * @link       https://github.com/CoticEaDIFRN/moodle_theme_ead
 */
defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot.'/calendar/lib.php');

// $PAGE->set_context(context_system::instance());
$PAGE->requires->js('/theme/ead/js/vue.min.js', true);
$PAGE->requires->js_call_amd('theme_ead/frontpage', 'init');

// require_once(__DIR__ . "/../externallib.php");dumpd(ead_frontpage_lanes());

$OUTPUT->doctype();
echo $OUTPUT->render_from_template('theme_ead/columns2', get_ead_template_context());
