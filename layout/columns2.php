<?php
defined('MOODLE_INTERNAL') || die();

function dump(...$params) { echo '<pre>'; var_dump(func_get_args()); echo '</pre>'; }
function dumpd(...$params) { echo '<pre>'; var_dump(func_get_args()); echo '</pre>'; die(); }

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once($CFG->libdir . '/behat/lib.php');
require_once($CFG->dirroot.'/calendar/lib.php');

global $PAGE;

if (isloggedin()) {
    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
} else {
    $navdraweropen = false;
}
$extraclasses = [];
if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}
$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
$templatecontext = [
    'sitename' => format_string($SITE->shortname, true, ['context' => context_course::instance(SITEID), "escape" => false]),
    'output' => $OUTPUT,
    'sidepreblocks' => $blockshtml,
    'hasblocks' => $hasblocks,
    'bodyattributes' => $bodyattributes,
    'navdraweropen' => $navdraweropen,
    'regionmainsettingsmenu' => $regionmainsettingsmenu,
    'hasregionmainsettingsmenu' => !empty($regionmainsettingsmenu),
    'link_calendar' => (new moodle_url('/calendar/view.php?view=month'))->out(),
    'link_sala_aula' => (new moodle_url('/my'))->out(),
    'link_mural' => (new moodle_url('/mural'))->out(),
    'link_secretaria' => (new moodle_url('/secretaria'))->out(),
];

if ($PAGE->pagelayout == "course") {
    $flatnav = [];
    foreach ($PAGE->flatnav as $child_key) {
        if ($child_key->type == 30) {
            $flatnav[] = $child_key;
        }
    }
    $templatecontext['flatnavigation'] = new ArrayIterator($flatnav);
    $templatecontext['course'] = $COURSE;
}

function get_nosso_calendario() {
    global $CFG, $COURSE;
    $calendar = \calendar_information::create(time(), $COURSE->id, $COURSE->category);
    list($data, $template) = calendar_get_view($calendar, 'upcoming_mini');
    if (sizeof($data->events) == 0) {
        return false;
    }
    $result = [];
    foreach ($data->events as $key => $value) {
        $shortdate = date('d M', $value->timestart);
        if (!array_key_exists($shortdate, $result)) {
            $result[$shortdate] = new stdClass();
            $result[$shortdate]->shortdate = $shortdate;

            $data_mes = explode(" ", $shortdate);

            $result[$shortdate]->shortdate_dia = $data_mes[0];
            $result[$shortdate]->shortdate_mes = $data_mes[1];
            $result[$shortdate]->viewurl = $value->viewurl;
            $result[$shortdate]->events = [];
        }
        $result[$shortdate]->events[] = $value;
    }
    return new ArrayIterator($result);
}


$templatecontext['in_course_page'] = $PAGE->pagelayout == "course";
$templatecontext['nosso_calendario'] = get_nosso_calendario();

echo $OUTPUT->render_from_template('theme_boost_eadifrn/columns2', $templatecontext);
