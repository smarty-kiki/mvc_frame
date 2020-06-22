<?php

function _generate_controller_file($entity_name, $entity_info, $relationship_infos)
{/*{{{*/
    $content = _get_controller_template_from_extension('list');

    otherwise($content, '没找到 controller 的 list 模版');

    $list_content =  blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_controller_template_from_extension('add');

    otherwise($content, '没找到 controller 的 add 模版');

    $add_content =  blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_controller_template_from_extension('detail');

    otherwise($content, '没找到 controller 的 detail 模版');

    $detail_content =  blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_controller_template_from_extension('update');

    otherwise($content, '没找到 controller 的 update 模版');

    $update_content =  blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_controller_template_from_extension('delete');

    otherwise($content, '没找到 controller 的 delete 模版');

    $delete_content =  blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $template = "<?php

%s
%s
%s
%s
%s";

    $content = sprintf($template, $list_content, $add_content, $detail_content, $update_content, $delete_content);

    return str_replace('^^', '', $content);
}/*}}}*/

function _generate_page($action)
{/*{{{*/
    $content = _get_page_template_from_extension($action);

    otherwise($content, '没找到 '.$action.' 的 page 模版');

    return $content;
}/*}}}*/

function _generate_template_data_type_add($data_type)
{/*{{{*/
    $content = _get_data_type_template_from_extension('add', $data_type);

    otherwise($content, '没找到 '.$data_type.' 的 add 模版');

    return $content;
}/*}}}*/

function _generate_template_data_type_detail($data_type)
{/*{{{*/
    $content = _get_data_type_template_from_extension('detail', $data_type);

    otherwise($content, '没找到 '.$data_type.' 的 detail 模版');

    return $content;
}/*}}}*/

function _generate_template_data_type_update($data_type)
{/*{{{*/
    $content = _get_data_type_template_from_extension('update', $data_type);

    otherwise($content, '没找到 '.$data_type.' 的 update 模版');

    return $content;
}/*}}}*/

function _generate_template_data_type_list($data_type)
{/*{{{*/
    $content = _get_data_type_template_from_extension('list', $data_type);

    otherwise($content, '没找到 '.$data_type.' 的 list 模版');

    return $content;
}/*}}}*/

function _generate_controller_data_type_add($data_type)
{/*{{{*/
    $content = _get_data_type_controller_from_extension('add', $data_type);

    otherwise($content, '没找到 '.$data_type.' 的 add 模版');

    return $content;
}/*}}}*/

function _generate_controller_data_type_detail($data_type)
{/*{{{*/
    $content = _get_data_type_controller_from_extension('detail', $data_type);

    otherwise($content, '没找到 '.$data_type.' 的 detail 模版');

    return $content;
}/*}}}*/

function _generate_controller_data_type_update($data_type)
{/*{{{*/
    $content = _get_data_type_controller_from_extension('update', $data_type);

    otherwise($content, '没找到 '.$data_type.' 的 update 模版');

    return $content;
}/*}}}*/

function _generate_controller_data_type_list($data_type)
{/*{{{*/
    $content = _get_data_type_controller_from_extension('list', $data_type);

    otherwise($content, '没找到 '.$data_type.' 的 list 模版');

    return $content;
}/*}}}*/

function _generate_view_add_file($entity_name, $entity_info, $relationship_infos)
{/*{{{*/
    $template = _generate_page('add');

    $content = blade_eval($template, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    return str_replace('^^', '', $content);
}/*}}}*/

function _generate_view_update_file($entity_name, $entity_info, $relationship_infos)
{/*{{{*/
    $template = _generate_page('update');

    $content = blade_eval($template, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    return str_replace('^^', '', $content);
}/*}}}*/

function _generate_view_list_file($entity_name, $entity_info, $relationship_infos)
{/*{{{*/
    $template = _generate_page('list');

    $content = blade_eval($template, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    return str_replace('^^', '', $content);
}/*}}}*/

command('crud:make-from-description', '通过描述文件生成 CRUD 控制器和页面', function ()
{/*{{{*/
    $entity_names = _get_entity_name_by_command_paramater();

    foreach ($entity_names as $entity_name) {

        $entity_info = description_get_entity($entity_name);

        $relationship_infos = description_get_relationship_with_snaps_by_entity($entity_name);

        $dir_name = VIEW_DIR.'/'.$entity_name;

        otherwise(
            is_dir($dir_name)
            || mkdir($dir_name, 0755),
            "当前用户没有权限创建目录 $dir_name");

        $controller_file_string = _generate_controller_file($entity_name, $entity_info, $relationship_infos);
        $view_add_file_string = _generate_view_add_file($entity_name, $entity_info, $relationship_infos);
        $view_update_file_string = _generate_view_update_file($entity_name, $entity_info, $relationship_infos);
        $view_list_file_string = _generate_view_list_file($entity_name, $entity_info, $relationship_infos);

        // 写文件
        error_log($controller_file_string, 3, $controller_file = CONTROLLER_DIR.'/'.$entity_name.'.php'); echo "generate $controller_file success!\n";
        error_log($view_add_file_string, 3, $file = $dir_name.'/add.php'); echo "generate ".$file." success!\n";
        error_log($view_update_file_string, 3, $file = $dir_name.'/update.php'); echo "generate ".$file." success!\n";
        error_log($view_list_file_string, 3, $file = $dir_name.'/list.php'); echo "generate ".$file." success!\n";

        echo "todo ".ROOT_DIR."/public/index.php include $controller_file\n";
    }
});/*}}}*/
