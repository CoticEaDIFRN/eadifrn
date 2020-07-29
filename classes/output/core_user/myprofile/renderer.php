<?php
namespace theme_ead\output\core_user\myprofile;
use \core_user\output\myprofile\category;
use \core_user\output\myprofile\tree;

/*
driver	"pgsql"
host	"172.17.0.15"
database	"lues_homolog"
username	"lues_homolog"
password	"l41s6lues_homolog"
*/

defined('MOODLE_INTERNAL') || die();
class renderer extends \core_user\output\myprofile\renderer {
    function new_course($id, $title, $code, $grade, $progress) {
        return (object)[
            "id"=>$id,
            "title"=>$title,
            "code"=>$code,
            "grade"=>$grade,
            "progress"=>$progress,
        ];
    }

    public function reconfigure_blocks(tree $tree) {
        global $CFG, $OUTPUT, $PAGE, $DB;

        $nodes = [];
        foreach ($tree->categories as $category) {
            foreach ($category->nodes as $node) {
                $nodes[$node->name] = (object)[
                    "name"=>$node->name,
                    "title"=>$node->title,
                    "url"=>$node->url,
                    "content"=>$node->content,
                    "icon"=>$node->icon,
                    "classes"=>$node->classes,
                ];
            }
        };

        function tira(&$a, $k) {
            if (array_key_exists($k, $a)) {
                $result = $a[$k];
                unset($a[$k]);
                return $result;
            }
            return null;
        }
        
        tira($nodes, "custom_field_cidade_codigo");
        tira($nodes, "courseprofiles");
        tira($nodes, "mobileappnode");

        $result = [
            (object)[
                "name"=>"personal_info",
                "title"=>get_string('personal_info', 'theme_ead'),
                "active"=>"active",
                "selected"=>"true",
                "icon"=>"user",
                "nodes"=>[
                    tira($nodes, "email"),
                    tira($nodes, "custom_field_cpf"),
                    tira($nodes, "custom_field_cidade_nome"),
                    tira($nodes, "city"),
                    tira($nodes, "custom_field_uf_sigla"),
                    tira($nodes, "country"),
                    tira($nodes, "editprofile"),
                    tira($nodes, "preferences"),
                    tira($nodes, "loginas"),
                ]
            ],
            (object)[
                "name"=>"academic_evolution",
                "title"=>get_string('academic_evolution', 'theme_ead'),
                "active"=>"",
                "selected"=>"false",
                "icon"=>"graduation-cap",
                "nodes"=>[
                    tira($nodes, "custom_field_polo"),
                    tira($nodes, "custom_field_campus_sigla"),
                    tira($nodes, "custom_field_campus"),
                    tira($nodes, "custom_field_curso_nome"),
                    tira($nodes, "custom_field_curso_codigo"),
                    tira($nodes, "custom_field_turma_codigo"),
                    tira($nodes, "learningplans"),
                    tira($nodes, "mycustomcerts"),
                    tira($nodes, "grades"),
                    tira($nodes, "grade"),
                    tira($nodes, "outline"),
                    tira($nodes, "complete"),
                ]
            ],
            (object)[
                "name"=>"what_i_wrote",
                "title"=>get_string('what_i_wrote', 'theme_ead'),
                "active"=>"",
                "selected"=>"false",
                "icon"=>"file-word-o ",
                "nodes"=>[
                    tira($nodes, "notes"),
                    tira($nodes, "blogs"),
                    tira($nodes, "forumposts"),
                    tira($nodes, "forumdiscussions"),
                ]
            ],
            (object)[
                "name"=>"security_privacy",
                "title"=>get_string('security_privacy', 'theme_ead'),
                "active"=>"",
                "selected"=>"false",
                "icon"=>"shield",
                "nodes"=>[
                    tira($nodes, "firstaccess"),
                    tira($nodes, "lastaccess"),
                    tira($nodes, "lastip"),
                    tira($nodes, "retentionsummary"),
                    tira($nodes, "todayslogs"),
                    tira($nodes, "alllogs"),
                    tira($nodes, "todayslogs"),
                    tira($nodes, "usersessions"),
                    tira($nodes, "custom_field_visibilidade_email"),
                    tira($nodes, "custom_field_visibilidade_necessidades"),
                    tira($nodes, "custom_field_visibilidade_endereco"),
                ]
            ],
            (object)[
                "name"=>"extra",
                "title"=>get_string('extra', 'theme_ead'),
                "active"=>"",
                "selected"=>"false",
                "icon"=>"wpexplorer",
                "no_elements"=>count(array_values($nodes)) == 0,
                "nodes"=>array_values($nodes)
            ]
        ];

        // dump($result);

        return $result;
    }

    public function render_tree(tree $tree) {
        global $CFG, $OUTPUT, $PAGE, $DB, $user, $course;
        // https://www.php.net/manual/en/function.ob-get-contents.php
        // $PAGE->set_pa

        $sql = "select   c.id        id,
                        c.fullname  title, 
                        c.shortname code,
                        c.id        grade,
                        c.id * 10   progress
                from    {course} c 	
                            inner join {enrol} e on (e.courseid=c.id and e.roleid=5)
                                inner join {user_enrolments} ue on (ue.enrolid=e.id) 
                where    ue.userid = ?";
        $params = [$user->id];
        if ($course != null) {
            $sql .= " and e.courseid = ?";
            $params[] = $course->id;
        }
        $courses = array_values($DB->get_records_sql($sql, $params));

        $context = [
            "is_siteadmin"=>is_siteadmin(),
            "profile"=> (object)[
                // "photo"=>"http://preview.byaviators.com/template/superlist/assets/img/tmp/agent-2.jpg",
                // "photo"=> "$CFG->wwwroot/theme/image.php/ead/core/1594419754/u/f2",
                "photo"=>(new \user_picture($user))->get_url($PAGE),
                "fullname"=>"$user->firstname $user->middlename $user->lastname ",
                "id"=>$user->id,
                "username"=>$user->username,
                "polo"=>"UFPB - Escola Técnica de Saúde - João Pessoa", // TODO recuperar o polo
                "campus_sigla"=>"ZL", // TODO recuperar a sigla do campus
                "campus"=>"Campus Avançado São Gonçalo do Amarantes", // TODO recuperar o nome do campus
            ],
            // "courses"=> [ // TODO Obter a lista de cursos
            //     $this->new_course(1, "FIC+ Formação em Educação a Distância EaD (CAMPUS AVANÇADO NATAL-ZONA LESTE)", "2016.2.12345.E1.TSUB123", 9.0, 100),
            //     $this->new_course(2, "Língua portuguesa", "2016.2.12345.E1.TSUB234", null, '30'),
            //     $this->new_course(3, "Cidadania", "2016.2.12345.E1.TSUB567", '7.0', '70'),
            //     $this->new_course(5, "Matemática financeira", "2016.2.12345.E1.TSUB098", null, null),
            // ],
            // "courses"=>array_values($DB->get_records_sql($sql)),
            "courses"=>$courses,
            "courses_count"=>count($courses),
            "user_blocks"=>$this->reconfigure_blocks($tree),
        ];
        // dump($context['courses2']);
        // dumpd($context['courses']);
        $return = $this->render_from_template('theme_ead/user/profile/view', $context);
        return $return;
    }
}