<?php
defined('MOODLE_INTERNAL') || die();

function theme_ead_new_info_field($categoryid, $shortname, $name, $datatype='text', $visible=1, $param1=NULL, $param2=NULL)
{
    global $DB;
    if ($DB->record_exists('user_info_field', ['shortname'=>$shortname])) {
        echo "<div class='alert alert-success'>Campo de '$shortname' já existe.</div>";
        return;
    }
    $user_info_field = [
        "name" => $name,
        "shortname" => $shortname,
        "categoryid" => $categoryid,
        "datatype" => $datatype,
        "visible" => $visible,
        "param1" => $param1,
        "param2" => $param2
    ];
    echo "<div class='alert alert-info'>Campo de '$shortname' não existe. O campo será criado com o apelido '$name'...</div>";
    return $DB->insert_record("user_info_field", $user_info_field, true);
}

function theme_ead_new_field_category($name="EaD")
{
    global $DB;
    $categoria = $DB->get_record('user_info_category', ['name'=>$name]);
    if (empty($categoria)) {
        echo "<div class='alert alert-success'>A categoria '$name' não existe. A categoria será criada.</div>";
        $ultimo = $DB->get_record_sql('SELECT coalesce(max(sortorder), 0) + 1 as sortorder from {user_info_category}');
        $categoria = ["name" => $name, "sortorder" => $ultimo->sortorder];
        return $DB->insert_record("user_info_category", $categoria);
    } else {
        echo "<div class='alert alert-info'>A categoria '$name' já existe.</div>";
        return $categoria->id; 
    }
}

/**
 * Execute auth_sabia upgrade from the given old version.
 *
 * @param int $oldversion
 * @return bool
 */
function theme_ead_create_info_fields($oldversion) {
    global $DB;
    $c_id = theme_ead_new_field_category("Dados acadêmicos");
    theme_ead_new_info_field($c_id, 'polo', 'Polo');
    theme_ead_new_info_field($c_id, 'campus_sigla', 'Sigla do campus');
    theme_ead_new_info_field($c_id, 'campus', 'Nome do campus');
    theme_ead_new_info_field($c_id, 'curso_nome', 'Nome  do curso');
    theme_ead_new_info_field($c_id, 'curso_codigo', 'Código do curso');
    theme_ead_new_info_field($c_id, 'turma_codigo', 'Código da turma');

    $c_id = theme_ead_new_field_category("Dados pessoais");
    theme_ead_new_info_field($c_id, 'cpf', 'CPF');
    theme_ead_new_info_field($c_id, 'cidade_nome', 'Nome da cidade');
    theme_ead_new_info_field($c_id, 'cidade_codigo', 'Código da cidade');
    theme_ead_new_info_field($c_id, 'uf_sigla', 'Sigla da UF');

    $c_id = theme_ead_new_field_category("Privacidade");
    theme_ead_new_info_field($c_id, 'visibilidade_email', 'Visibilidade do e-mail');
    theme_ead_new_info_field($c_id, 'visibilidade_necessidades', 'Visibilidade das deficiências');
    theme_ead_new_info_field($c_id, 'visibilidade_endereco', 'Visibilidade do endereço');
    return true;
}
