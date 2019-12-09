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

function _generate_template_struct_add($struct_type)
{/*{{{*/
    $content = _get_struct_template_from_extension('add', $struct_type);

    otherwise($content, '没找到 '.$struct_type.' 的 add 模版');

    return $content;
}/*}}}*/

function _generate_template_struct_detail($struct_type)
{/*{{{*/
    $content = _get_struct_template_from_extension('detail', $struct_type);

    otherwise($content, '没找到 '.$struct_type.' 的 detail 模版');

    return $content;
}/*}}}*/

function _generate_template_struct_update($struct_type)
{/*{{{*/
    $content = _get_struct_template_from_extension('update', $struct_type);

    otherwise($content, '没找到 '.$struct_type.' 的 update 模版');

    return $content;
}/*}}}*/

function _generate_template_struct_list($struct_type)
{/*{{{*/
    $content = _get_struct_template_from_extension('list', $struct_type);

    otherwise($content, '没找到 '.$struct_type.' 的 list 模版');

    return $content;
}/*}}}*/

function _generate_controller_struct_add($struct_type)
{/*{{{*/
    $content = _get_struct_controller_from_extension('add', $struct_type);

    otherwise($content, '没找到 '.$struct_type.' 的 add 模版');

    return $content;
}/*}}}*/

function _generate_controller_struct_detail($struct_type)
{/*{{{*/
    $content = _get_struct_controller_from_extension('detail', $struct_type);

    otherwise($content, '没找到 '.$struct_type.' 的 detail 模版');

    return $content;
}/*}}}*/

function _generate_controller_struct_update($struct_type)
{/*{{{*/
    $content = _get_struct_controller_from_extension('update', $struct_type);

    otherwise($content, '没找到 '.$struct_type.' 的 update 模版');

    return $content;
}/*}}}*/

function _generate_controller_struct_list($struct_type)
{/*{{{*/
    $content = _get_struct_controller_from_extension('list', $struct_type);

    otherwise($content, '没找到 '.$struct_type.' 的 list 模版');

    return $content;
}/*}}}*/

function _generate_view_add_file($entity_name)
{/*{{{*/
    $template = _generate_page('add');

    $content = blade_eval($template, [
        'entity_name' => $entity_name,
    ]);

    return str_replace('^^', '', $content);
}/*}}}*/

function _generate_view_update_file($entity_name)
{/*{{{*/
    $template = _generate_page('update');

    $content = blade_eval($template, [
        'entity_name' => $entity_name,
    ]);

    return str_replace('^^', '', $content);
}/*}}}*/

function _generate_view_list_file($entity_name)
{/*{{{*/
    $template = _generate_page('list');

    $content = blade_eval($template, [
        'entity_name' => $entity_name,
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
        $view_add_file_string = _generate_view_add_file($entity_name);
        $view_update_file_string = _generate_view_update_file($entity_name);
        $view_list_file_string = _generate_view_list_file($entity_name);

        // 写文件
        error_log($controller_file_string, 3, $controller_file = CONTROLLER_DIR.'/'.$entity_name.'.php'); echo $controller_file."\n";
        error_log($view_add_file_string, 3, $file = $dir_name.'/add.php'); echo $file."\n";
        error_log($view_update_file_string, 3, $file = $dir_name.'/update.php'); echo $file."\n";
        error_log($view_list_file_string, 3, $file = $dir_name.'/list.php'); echo $file."\n";
        echo "\n将 $controller_file 加入到 public/index.php 即可响应请求\n";
    }
});/*}}}*/
