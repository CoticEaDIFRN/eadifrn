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

    public function render_tree(tree $tree) {
        global $CFG, $USER, $OUTPUT, $PAGE, $DB;

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

        $user_blocks = [
            (object)[
                "name"=>"personal_info",
                "title"=>"Minhas informações pessoais",
                "active"=>"active",
                "selected"=>"true",
                "icon"=>"user",
                "nodes"=>[
                    tira($nodes, "editprofile"),
                    tira($nodes, "email"),
                ]
            ],
            (object)[
                "name"=>"academic_evolution",
                "title"=>"Evolução acadêmica",
                "active"=>"",
                "selected"=>"false",
                "icon"=>"graduation-cap",
                "nodes"=>[
                    tira($nodes, "learningplans"),
                    tira($nodes, "mycustomcerts"),
                    tira($nodes, "grades"),
                    tira($nodes, "grade"),
                    tira($nodes, "outline"),
                    tira($nodes, "complete"),
                ]
            ],
            (object)[
                "name"=>"what_i_whote",
                "title"=>"Onde eu escrevi",
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
                "title"=>"Segurança e privacidade",
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
                ]
            ]
        ];

        $context = [
            "is_siteadmin"=>is_siteadmin(),
            "profile"=> (object)[
                "photo"=>"http://preview.byaviators.com/template/superlist/assets/img/tmp/agent-2.jpg",
                // "photo"=> "$CFG->wwwroot/theme/image.php/ead/core/1594419754/u/f2",
                "photo"=>(new \user_picture($USER))->get_url($PAGE),
                "fullname"=>"$USER->firstname $USER->middlename $USER->lastname ",
                "id"=>$USER->id,
                "username"=>$USER->username,
                "polo"=>"UFPB - Escola Técnica de Saúde - João Pessoa", // TODO recuperar o polo
                "campus_sigla"=>"ZL", // TODO recuperar a sigla do campus
                "campus"=>"Campus Avançado São Gonçalo do Amarantes", // TODO recuperar o nome do campus
            ],
            "courses"=> [ // TODO Obter a lista de cursos
                $this->new_course(1, "FIC+ Formação em Educação a Distância EaD (CAMPUS AVANÇADO NATAL-ZONA LESTE)", "2016.2.12345.E1.TSUB123", 9.0, 100),
                $this->new_course(2, "Língua portuguesa", "2016.2.12345.E1.TSUB234", null, '30'),
                $this->new_course(3, "Cidadania", "2016.2.12345.E1.TSUB567", '7.0', '70'),
                $this->new_course(5, "Matemática financeira", "2016.2.12345.E1.TSUB098", null, null),
            ],
            "user_blocks"=>$user_blocks,
            "rest_nodes"=>$nodes,
        ];
        $return = $this->render_from_template('theme_ead/user/profile/view', $context);
        return $return;
    }
}