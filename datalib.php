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

    if (isloggedin()) {
        $category = (object)['description' => 'Continuar estudando', 'url' => '#continuar', 'courses' => []];
        foreach (get_frontpage_courses($USER->id, $not_in) as $course) {
            array_push($not_in, $course->course_id);
            $course->course_enrol = null;
            array_push($category->courses, ead_course_array($course));
        }
        if (count($category->courses)>0) {
            array_push($categories, $category);
        }
    }

    if (count($categories)==0) {
        $category = (object)['description' => 'Destaques', 'url' => '#destaques', 'courses' => []];
        foreach (get_frontpage_courses(null, $not_in) as $course) {
            array_push($not_in, $course->course_id);
            array_push($category->courses, ead_course_array($course));
        }
        array_push($categories, $category);
    }

    $categoria = (object)['description' => null, 'url' => null,'courses' => []];
    foreach (get_frontpage_courses(null, $not_in) as $course) {
        if ($categoria->url != "#$course->category_id") {
            $categoria = (object)['description' => $course->category_title, 
                                  'url' => "$CFG->wwwroot/course/index.php?categoryid=$course->category_id",
                                  'courses' => []];
            array_push($categories, $categoria);
        }
        array_push($categoria->courses, ead_course_array($course));
    }

    return ['home_url' =>  $USER->id, 'categories' => $categories];
}

function get_frontpage_courses($userid=null, $not_in=[]){
    global $DB;
    
    $in_front = "AND EXISTS (SELECT 1
    FROM {customfield_category} c
             INNER JOIN {customfield_field} f ON (c.id = f.categoryid)
             INNER JOIN {customfield_data} d ON (f.id = d.fieldid)
  WHERE d.instanceid=co.id and c.component='core_course' and c.area='course' and f.shortname = 'show_in_frontpage' and d.intvalue = 1)";
    $outer = '';
    if (count($not_in)>0) {
        $outer .= "where course_id NOT IN (" . implode($not_in, ', ') . ")";
    } else if ($userid==null) {
        $outer .= "where course_featured = '1'";
    } else {
        $in_front = '';
        $outer .= "where course_id in (select e.courseid 
                                        from   {user_enrolments} u inner join {enrol} e on (u.enrolid = e.id)
                                        where u.userid = $userid and e.roleid = 5)";
    }
    
    $sql = "
    SELECT *
    FROM (
             SELECT DISTINCT co.id                                                                       course_id,
                             co.fullname                                                                 course_title,
                             ca.id                                                                       category_id,
                             ca.name                                                                     category_title,
                             co.id                                                                       course_see,
                             co.id                                                                       course_enrol,
                             'https://cdn.pixabay.com/photo/2017/12/30/20/59/report-3050965_960_720.jpg' course_thumbnail,
                             coalesce(
                                (SELECT max(id.value)
                                   FROM {customfield_category} ic
                                            INNER JOIN {customfield_field} if ON (ic.id = if.categoryid)
                                            INNER JOIN {customfield_data} id ON (if.id = id.fieldid)
                                  WHERE (ic.component, ic.area) = ('core_course', 'course')
                                    AND if.shortname IN ('duration')
                                    AND id.instanceid = co.id)
                                    , '*')                                                               course_duration,
                             (
                                 SELECT id.value
                                   FROM {customfield_category} ic
                                            INNER JOIN {customfield_field} if ON (ic.id = if.categoryid)
                                            INNER JOIN {customfield_data} id ON (if.id = id.fieldid)
                                  WHERE (ic.component, ic.area) = ('core_course', 'course')
                                    AND if.shortname IN ('featured')
                                    AND id.instanceid = co.id
                             )                                                                           course_featured
               FROM {course} co
                        INNER JOIN {course_categories} ca ON (co.category = ca.id)
              WHERE  (
                            co.visible = 1
                            AND (
                                    (trunc(extract(EPOCH FROM now())) BETWEEN co.startdate AND co.enddate)
                                    OR (co.enddate = 0 AND trunc(extract(EPOCH FROM now())) >= co.startdate)
                                )
                     )
              $in_front
              ORDER BY ca.name, co.fullname) AS t  
              $outer
";
// var_dump($sql);
    return $DB->get_records_sql($sql);

}