<?php
/**
 * Web service local plugin template external functions and service definitions.
 *
 * @package    block_vitrine
 * @copyright  2019 IFRN <https://ifrn.edu.br>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// We defined the web service functions to install.
$functions = [
        'theme_ead_lanes' => [
                'classname'     => 'theme_ead_external',
                'methodname'    => 'lanes',
                'classpath'     => 'theme/ead/externallib.php',
                'description'   => 'Return Hello World FIRSTNAME. Can change the text (Hello World) sending a new text as parameter',
                'type'          => 'read',
                // 'capabilities'  => 'moodle/course:view, moodle/course:update, moodle/course:viewhiddencourses',
                'ajax'          => true,
                'loginrequired' => false,
                'services'      => ['theme_ead'],
        ],
];

// We define the services to install as pre-build services. A pre-build service is not editable by administrator.
$services = [
        'EaD Theme' => [
                'functions'       => ['theme_ead_lanes'],
                'restrictedusers' => 0,
                'enabled'         => 1,
        ]
];
