<?php
/**
 * Theme EaD - Library
 *
 * @category  MoodleTheme
 * @author    Sueldo Sales <sueldosales@gmail.com>
 * @author    Kelson C. Medeiros <kelsoncm@gmail.com>
 * @package    theme_ead
 * @copyright 2017 IFRN <https://ifrn.edu.br>
 * @license    MIT https://opensource.org/licenses/MIT
 * @link      https://github.com/CoticEaDIFRN/moodle_theme_ead
 */


defined('MOODLE_INTERNAL') || die();

if (!function_exists('dump')) {function dump(...$params) { echo '<pre>'; var_dump(func_get_args()); echo '</pre>'; }}
if (!function_exists('dumpd')) {function dumpd(...$params) { echo '<pre>'; var_dump(func_get_args()); echo '</pre>'; die(); }}

/**
 * Returns the main SCSS content.
 *
 * @param theme_config $theme The theme config object.
 * @return string
 */
function theme_ead_get_main_scss_content($theme) {
    $choices = [];
    $choices['abacate.scss'] = 'Verde - Abacate';
    $choices['alface.scss'] = 'Verde - Alface';
    $choices['oliva.scss'] = 'Verde - Oliva';
    $choices['alto-contraste-claro.scss'] = 'Alto contrate - claro';
    $choices['alto-contraste-escuro.scss'] = 'Alto contrate - escuro';
    $choices['anil.scss'] = 'Azul - Anil';
    $choices['safira.scss'] = 'Azul - Safira';
    $choices['ipe.scss'] = 'Vívido - Ipê';
    $choices['jerimum.scss'] = 'Vívido - Jerimum';
    $choices['solar.scss'] = 'Vívido - Solar';
    
    $filename = !empty($theme->settings->preset) ? $theme->settings->preset : null;
    if (array_key_exists($filename, $choices)) {
        return file_get_contents(__DIR__ . "/scss/preset/$filename");
    } else {
        return file_get_contents(__DIR__ . "/scss/preset/abacate.scss");
    }
}

/**
 * Override to add CSS values from settings to pre scss file.
 *
 * Get SCSS to prepend.
 *
 * @param theme_config $theme The theme config object.
 * @return array
 */
function theme_ead_get_pre_scss($theme) {
    global $CFG;
    // MODIFICATION START.
    require_once($CFG->dirroot . '/theme/ead/locallib.php');
    // MODIFICATION END.

    $scss = '';
    $configurable = [
        // Config key => [variableName, ...].
        'brandcolor' => ['brand-primary'],
        // MODIFICATION START: Add own variables.
        'section0title' => ['section0title'],
        'showswitchedroleincourse' => ['showswitchedroleincourse'],
        'loginform' => ['loginform'],
        'footerhidehelplink' => ['footerhidehelplink'],
        'footerhidelogininfo' => ['footerhidelogininfo'],
        'footerhidehomelink' => ['footerhidehomelink'],
        'blockicon' => ['blockicon'],
        'brandsuccesscolor' => ['brand-success'],
        'brandinfocolor' => ['brand-info'],
        'brandwarningcolor' => ['brand-warning'],
        'branddangercolor' => ['brand-danger'],
        'darknavbar' => ['darknavbar'],
        'footerblocks' => ['footerblocks'],
        'imageareaitemsmaxheight' => ['imageareaitemsmaxheight'],
        'showsettingsincourse' => ['showsettingsincourse'],
        'incoursesettingsswitchtorole' => ['incoursesettingsswitchtorole']
        // MODIFICATION END.
    ];

    // Prepend variables first.
    foreach ($configurable as $configkey => $targets) {
        $value = isset($theme->settings->{$configkey}) ? $theme->settings->{$configkey} : null;
        if (empty($value)) {
            continue;
        }
        array_map(function($target) use (&$scss, $value) {
            $scss .= '$' . $target . ': ' . $value . ";\n";
        }, (array) $targets);
    }

    // MODIFICATION START: Add login background images that are uploaded to the setting 'loginbackgroundimage' to CSS.
    // $scss .= theme_ead_get_loginbackgroundimage_scss();
    // MODIFICATION END.

    // Prepend pre-scss.
    if (!empty($theme->settings->scsspre)) {
        $scss .= $theme->settings->scsspre;
    }

    return $scss;
}

/**
 * Implement pluginfile function to deliver files which are uploaded in theme settings
 *
 * @param stdClass $course course object
 * @param stdClass $cm course module
 * @param stdClass $context context object
 * @param string $filearea file area
 * @param array $args extra arguments
 * @param bool $forcedownload whether or not force download
 * @param array $options additional options affecting the file serving
 * @return bool
 */
function theme_ead_pluginfile($course, $cm, $context, $filearea, $args, $forcedownload, array $options = array()) {
    if ($context->contextlevel == CONTEXT_SYSTEM) {
        $theme = theme_config::load('ead');
        if ($filearea === 'favicon') {
            return $theme->setting_file_serve('favicon', $args, $forcedownload, $options);
        } else if ($filearea === 'loginbackgroundimage') {
            return $theme->setting_file_serve('loginbackgroundimage', $args, $forcedownload, $options);
        } else if ($filearea === 'fontfiles') {
            return $theme->setting_file_serve('fontfiles', $args, $forcedownload, $options);
        } else if ($filearea === 'imageareaitems') {
            return $theme->setting_file_serve('imageareaitems', $args, $forcedownload, $options);
        } else {
            send_file_not_found();
        }
    } else {
        send_file_not_found();
    }
}

/**
 * If setting is updated, use this callback to clear the theme_ead' own application cache.
 */
function theme_ead_reset_app_cache() {
    // Get the cache from area.
    $theme_ead_cache = cache::make('theme_ead', 'imagearea');
    // Delete the cache for the imagearea.
    $theme_ead_cache->delete('imageareadata');
    // To be safe and because there can only be one callback function added to a plugin setting,
    // we also delete the complete theme cache here.
    theme_reset_all_caches();
}

function get_ead_calendario() {
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

function get_ead_course_content_actions()
{
    global $PAGE, $COURSE;
    if ($PAGE->pagelayout == "course" || $PAGE->pagelayout == "incourse") {
        $flatnav = [];
        foreach ($PAGE->flatnav as $child_key) {
            if ($child_key->type == 30) {
                $flatnav[] = $child_key;
            }
        }
        return new ArrayIterator($flatnav);
    }
}
    
function get_ead_course_common_actions() 
{
    global $PAGE, $COURSE;
    if ($PAGE->pagelayout == "course" || $PAGE->pagelayout == "incourse") {
        $extraflatnav = [];
        
        // Notas
        $notas = new stdClass();
        $notas->action_url = new moodle_url("/grade/report/index.php", ['id'=>$COURSE->id]);
        $notas->icon = "table";
        $notas->label = "Notas";
        $extraflatnav[] = $notas;

        // Participantes
        $participantes = new stdClass();
        $participantes->action_url = new moodle_url("/user/index.php", ['id'=>$COURSE->id]);
        $participantes->icon = "users";
        $participantes->label = "Participantes";
        $extraflatnav[] = $participantes;
     
        // Emblemas
        $emblemas = new stdClass();
        $emblemas->action_url = new moodle_url("/badges/mybadges.php", ['type'=>2, 'id'=>$COURSE->id]);
        $emblemas->icon = "shield";
        $emblemas->label = "Emblemas";
        $extraflatnav[] = $emblemas;
    
        //// Competências
        //$competencias = new stdClass();
        //$competencias->action_url = new moodle_url("/admin/tool/lp/coursecompetencies.php", ['courseid'=>$COURSE->id]);
        //$competencias->icon = "check-square-o";
        //$competencias->label = "Competências";
        //$extraflatnav[] = $competencias;
   
        return new ArrayIterator($extraflatnav);
    }
}

function get_ead_template_context()
{
    global $OUTPUT, $PAGE, $COURSE, $SITE;

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
    $in_course_page = $PAGE->pagelayout == "course";
    $not_in_course_page = $PAGE->pagelayout != "course";
    $within_course_page = $PAGE->pagelayout == "incourse";
    $not_within_course_page = $PAGE->pagelayout != "incourse";
    $course_name = $COURSE->fullname;
    $course_code = $COURSE->shortname;
    $is_siteadmin = is_siteadmin();
    $inte_suap = $is_siteadmin ? "show_suap" : "";
    $inte_admin = $is_siteadmin ? "show_admin" : "";
    $course_content_actions = ($in_course_page || $within_course_page) ? get_ead_course_content_actions() : [];
    $course_common_actions = ($in_course_page || $within_course_page) ? get_ead_course_common_actions() : [];

    return [
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
        'link_suap' => (new moodle_url('/suap'))->out(),
        'link_mural' => (new moodle_url('/mural'))->out(),
        'link_secretaria' => (new moodle_url('/secretaria'))->out(),
        'link_admin' => (new moodle_url('/admin/search.php'))->out(),
        'in_course_page' => $in_course_page,
        'not_in_course_page' => $not_in_course_page,
        'incourse' => $COURSE,
        'course' => $COURSE,
        'within_course_page' => $within_course_page,
        'not_within_course_page' => $not_within_course_page,
        'show_button' => "$not_in_course_page && $not_within_course_page",
        'course_name' => $course_name,
        'inte_suap' => $inte_suap,
        'inte_admin' => $inte_admin,
        'is_siteadmin' => $is_siteadmin,
        'nosso_calendario' => get_ead_calendario(),
        'course_content_actions' => $course_content_actions,
        'course_common_actions' => $course_common_actions,
    ];
};
