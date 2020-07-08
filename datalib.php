<?php
/**
 * Theme EaD - Library
 *
 * @category   MoodleTheme
 * @author     Sueldo Sales <sueldosales@gmail.com>
 * @author     Kelson C. Medeiros <kelsoncm@gmail.com>
 * @package    theme_ead
 * @copyright  2017 IFRN <https://ifrn.edu.br>
 * @license    MIT https://opensource.org/licenses/MIT
 * @link       https://github.com/CoticEaDIFRN/moodle_theme_ead
 */


defined('MOODLE_INTERNAL') || die();
function ead_category_array($descrition, $url, $courses) {
    return [
        'description' => $descrition,
        'url' => $url,
        'courses' => $courses
    ];
};

function ead_course_array($course) {
    global $CFG;
    return [
        'id' => $course->course_id,
        'title' => $course->course_title,
        'see' => "$CFG->wwwroot/course/view.php?id=$course->course_see",
        'enrol' => $course->course_enrol ? "$CFG->wwwroot/course/view.php?id=$course->course_enrol" : '',
        'duration' =>  $course->course_duration,
        'thumbnail' => $course->course_thumbnail,
    ];
};

function ead_frontpage_lanes() {
    global $USER, $CFG;
    $not_in = [];
    $categories = [];

    function new_category($description, $url) {
        return (object)['description' => $description, 'url' => $url, 'courses' => []];
    }

    if (isloggedin()) {
        $category = new_category('Continuar estudando', '#continuar');
        foreach (get_frontpage_courses($USER->id, false, []) as $course) {
            array_push($not_in, $course->course_id);
            $course->course_enrol = null;
            array_push($category->courses, ead_course_array($course));
        }
        if (count($category->courses)>0) {
            array_push($categories, $category);
        }
    }

    $category = new_category('Destaques', '#destaques');
    foreach (get_frontpage_courses(null, true, $not_in) as $course) {
        array_push($not_in, $course->course_id);
        array_push($category->courses, ead_course_array($course));
    }
    if (count($category->courses)>0) {
        array_push($categories, $category);
    }

    $category = new_category(null, null);
    $url_prefix = "$CFG->wwwroot/course/index.php?categoryid=";
    foreach (get_frontpage_courses(null, false, $not_in) as $course) {
        if ($category->url != "$url_prefix$course->category_id") {
            $category = new_category($course->category_title, "$url_prefix$course->category_id");
            array_push($categories, $category);
        }
        array_push($category->courses, ead_course_array($course));
    }
    
    return ['home_url' => "$CFG->wwwroot/user/profile.php", 'categories' => $categories];
}

function get_frontpage_courses($userid=null, $featured=false, $not_in=[]){
    global $DB, $CFG;
    
    $outer = "WHERE course_show_in_frontpage = 1\n";    
    if ($userid!=null) {
        $outer .= " AND course_id IN (SELECT e.courseid 
                                       FROM {user_enrolments} u INNER JOIN {enrol} e ON (u.enrolid = e.id)
                                      WHERE u.userid = $userid AND e.roleid = 5)\n";
    } 

    if ($featured) {
        $outer .= " AND course_featured=1\n";
    }

    if (count($not_in)>0) {
        $outer .= " AND course_id NOT IN (" . implode($not_in, ', ') . ")";
    }

     
    
    $sql = "
    SELECT * FROM (
        SELECT  DISTINCT 
                co.id        course_id,
                co.sortorder course_sortorder,
                co.fullname  course_title,
                co.id        course_see,
                co.id        course_enrol,
                ca.id        category_id,
                ca.sortorder category_sortorder,
                ca.name      category_title,
                CASE
                    WHEN fi.id IS  NULL THEN 'theme/ead/pix/course_thumbnail.jpg'
                    ELSE 'pluginfile.php/' || cx.id || '/' || fi.component || '/' || fi.filearea || '/' || fi.filename  
                END course_thumbnail,
                coalesce(
                    (
                        SELECT max(id.value)
                        FROM {customfield_category} ic
                                INNER JOIN {customfield_field} if ON (ic.id = if.categoryid)
                                INNER JOIN {customfield_data} id ON (if.id = id.fieldid)
                        WHERE (ic.component, ic.area) = ('core_course', 'course')
                        AND id.instanceid = co.id
                        AND if.shortname IN ('duration')
                    ), 
                    '*'
                ) course_duration,
                (
                    SELECT id.intvalue
                    FROM {customfield_category} ic
                            INNER JOIN {customfield_field} if ON (ic.id = if.categoryid)
                            INNER JOIN {customfield_data} id ON (if.id = id.fieldid)
                    WHERE (ic.component, ic.area) = ('core_course', 'course')
                    AND id.instanceid = co.id
                    AND if.shortname IN ('featured')
                ) course_featured,
                (
                    SELECT id.intvalue
                    FROM {customfield_category} ic
                            INNER JOIN {customfield_field} if ON (ic.id = if.categoryid)
                            INNER JOIN {customfield_data} id ON (if.id = id.fieldid)
                    WHERE (ic.component, ic.area) = ('core_course', 'course')
                    AND id.instanceid = co.id
                    AND if.shortname IN ('show_in_frontpage')
                ) course_show_in_frontpage
        FROM    {course} co
                    INNER JOIN {course_categories} ca ON (co.category = ca.id)
                    INNER JOIN {context} cx ON (cx.contextlevel=50 AND cx.instanceid=co.id)
                        LEFT JOIN {files} fi ON (cx.id=fi.contextid AND fi.filename!='.' AND fi.component='course' AND fi.filearea='overviewfiles')
        WHERE   co.visible = 1
        AND   co.startdate <= trunc(extract(EPOCH FROM now()))
        AND   (co.enddate >= trunc(extract(EPOCH FROM now())) OR co.enddate = 0)
        ORDER BY ca.sortorder, ca.name, co.sortorder, co.fullname
    ) AS t
    $outer
    ";
    $sql = str_replace('{', $CFG->prefix, $sql);
    $sql = str_replace('}', '', $sql);
    $result = $DB->get_records_sql($sql);
    
    foreach ($result as $course) {
        $course->course_thumbnail = "$CFG->wwwroot/$course->course_thumbnail";
    }

    // var_dump($sql);
    return $result;

}
