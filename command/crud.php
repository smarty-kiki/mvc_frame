<?php

function _generate_page($action)
{/*{{{*/
    $content = _get_page_template_from_extension($action);

    otherwise($content, '没找到 '.$action.' 的 page 模版');

    return $content;
}/*}}}*/

function _generate_template_data_type_add($data_type)
{/*{{{*/
    $content = _get_data_type_template_from_extension('add', $data_type);

    otherwise($content, '没找到 template/add/data_type/'.$data_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_template_data_type_detail($data_type)
{/*{{{*/
    $content = _get_data_type_template_from_extension('detail', $data_type);

    otherwise($content, '没找到 template/detail/data_type/'.$data_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_template_data_type_update($data_type)
{/*{{{*/
    $content = _get_data_type_template_from_extension('update', $data_type);

    otherwise($content, '没找到 template/update/data_type/'.$data_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_template_data_type_list($data_type)
{/*{{{*/
    $content = _get_data_type_template_from_extension('list', $data_type);

    otherwise($content, '没找到 template/list/data_type/'.$data_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_template_struct_group_add($struct_group_type)
{/*{{{*/
    $content = _get_struct_group_template_from_extension('add', $struct_group_type);

    otherwise($content, '没找到 template/add/struct_group/'.$struct_group_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_template_struct_group_detail($struct_group_type)
{/*{{{*/
    $content = _get_struct_group_template_from_extension('detail', $struct_group_type);

    otherwise($content, '没找到 template/detail/struct_group/'.$struct_group_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_template_struct_group_update($struct_group_type)
{/*{{{*/
    $content = _get_struct_group_template_from_extension('update', $struct_group_type);

    otherwise($content, '没找到 template/update/struct_group/'.$struct_group_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_template_struct_group_list($struct_group_type)
{/*{{{*/
    $content = _get_struct_group_template_from_extension('list', $struct_group_type);

    otherwise($content, '没找到 template/list/struct_group/'.$struct_group_type.'.php 模版');

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

function _generate_controller_file($entity_name, $entity_info, $relationship_infos)
{/*{{{*/
    $content = _get_controller_template_from_extension('list');

    otherwise($content, '没找到 controller 的 list 模版');

    $list_content = blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_controller_template_from_extension('add');

    otherwise($content, '没找到 controller 的 add 模版');

    $add_content = blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_controller_template_from_extension('detail');

    otherwise($content, '没找到 controller 的 detail 模版');

    $detail_content = blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_controller_template_from_extension('update');

    otherwise($content, '没找到 controller 的 update 模版');

    $update_content = blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_controller_template_from_extension('delete');

    otherwise($content, '没找到 controller 的 delete 模版');

    $delete_content = blade_eval($content, [
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

function _generate_controller_data_type_add($data_type)
{/*{{{*/
    $content = _get_data_type_controller_from_extension('add', $data_type);

    otherwise($content, '没找到 controller/add/data_type/'.$data_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_controller_data_type_detail($data_type)
{/*{{{*/
    $content = _get_data_type_controller_from_extension('detail', $data_type);

    otherwise($content, '没找到 controller/detail/data_type/'.$data_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_controller_data_type_update($data_type)
{/*{{{*/
    $content = _get_data_type_controller_from_extension('update', $data_type);

    otherwise($content, '没找到 controller/update/data_type/'.$data_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_controller_data_type_list($data_type)
{/*{{{*/
    $content = _get_data_type_controller_from_extension('list', $data_type);

    otherwise($content, '没找到 controller/list/data_type/'.$data_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_controller_struct_group_add($struct_group_type)
{/*{{{*/
    $content = _get_struct_group_controller_from_extension('add', $struct_group_type);

    otherwise($content, '没找到 controller/add/struct_group/'.$struct_group_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_controller_struct_group_detail($struct_group_type)
{/*{{{*/
    $content = _get_struct_group_controller_from_extension('detail', $struct_group_type);

    otherwise($content, '没找到 controller/detail/struct_group/'.$struct_group_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_controller_struct_group_update($struct_group_type)
{/*{{{*/
    $content = _get_struct_group_controller_from_extension('update', $struct_group_type);

    otherwise($content, '没找到 controller/update/struct_group/'.$struct_group_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_controller_struct_group_list($struct_group_type)
{/*{{{*/
    $content = _get_struct_group_controller_from_extension('list', $struct_group_type);

    otherwise($content, '没找到 controller/list/struct_group/'.$struct_group_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_docs_page_file($entity_name, $entity_info, $relationship_infos)
{/*{{{*/
    $content = _get_docs_page_template_from_extension('list');

    otherwise($content, '没找到 docs/page 的 list 模版');

    $list_content = blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_docs_page_template_from_extension('add');

    otherwise($content, '没找到 docs/page 的 add 模版');

    $add_content = blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_docs_page_template_from_extension('detail');

    otherwise($content, '没找到 docs/page 的 detail 模版');

    $detail_content = blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_docs_page_template_from_extension('update');

    otherwise($content, '没找到 docs/page 的 update 模版');

    $update_content = blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_docs_page_template_from_extension('delete');

    otherwise($content, '没找到 docs/page 的 delete 模版');

    $delete_content = blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $template = "# {$entity_info['display_name']}  
{$entity_info['description']}

%s
%s
%s
%s
%s";

    $content = sprintf($template, $list_content, $add_content, $detail_content, $update_content, $delete_content);

    return str_replace('^^', '', $content);
}/*}}}*/

function _generate_docs_api_file($entity_name, $entity_info, $relationship_infos)
{/*{{{*/
    $content = _get_docs_api_template_from_extension('list');

    otherwise($content, '没找到 docs/api 的 list 模版');

    $list_content = blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_docs_api_template_from_extension('add');

    otherwise($content, '没找到 docs/api 的 add 模版');

    $add_content = blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_docs_api_template_from_extension('detail');

    otherwise($content, '没找到 docs/api 的 detail 模版');

    $detail_content = blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_docs_api_template_from_extension('update');

    otherwise($content, '没找到 docs/api 的 update 模版');

    $update_content = blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $content = _get_docs_api_template_from_extension('delete');

    otherwise($content, '没找到 docs/api 的 delete 模版');

    $delete_content = blade_eval($content, [
        'entity_name' => $entity_name,
        'entity_info' => $entity_info,
        'relationship_infos' => $relationship_infos,
    ]);

    $template = "# {$entity_info['display_name']}  
{$entity_info['description']}

%s
%s
%s
%s
%s";

    $content = sprintf($template, $list_content, $add_content, $detail_content, $update_content, $delete_content);

    return str_replace('^^', '', $content);
}/*}}}*/

function _generate_docs_api_data_type_add($data_type)
{/*{{{*/
    $content = _get_data_type_docs_api_from_extension('add', $data_type);

    otherwise($content, '没找到 docs/api/add/data_type/'.$data_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_docs_api_data_type_detail($data_type)
{/*{{{*/
    $content = _get_data_type_docs_api_from_extension('detail', $data_type);

    otherwise($content, '没找到 docs/api/detail/data_type/'.$data_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_docs_api_data_type_update($data_type)
{/*{{{*/
    $content = _get_data_type_docs_api_from_extension('update', $data_type);

    otherwise($content, '没找到 docs/api/update/data_type/'.$data_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_docs_api_data_type_list($data_type)
{/*{{{*/
    $content = _get_data_type_docs_api_from_extension('list', $data_type);

    otherwise($content, '没找到 docs/api/list/data_type/'.$data_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_docs_api_struct_group_add($data_type)
{/*{{{*/
    $content = _get_struct_group_docs_api_from_extension('add', $data_type);

    otherwise($content, '没找到 docs/api/add/struct_group/'.$data_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_docs_api_struct_group_detail($data_type)
{/*{{{*/
    $content = _get_struct_group_docs_api_from_extension('detail', $data_type);

    otherwise($content, '没找到 docs/api/detail/struct_group/'.$data_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_docs_api_struct_group_update($data_type)
{/*{{{*/
    $content = _get_struct_group_docs_api_from_extension('update', $data_type);

    otherwise($content, '没找到 docs/api/update/struct_group/'.$data_type.'.php 模版');

    return $content;
}/*}}}*/

function _generate_docs_api_struct_group_list($data_type)
{/*{{{*/
    $content = _get_struct_group_docs_api_from_extension('list', $data_type);

    otherwise($content, '没找到 docs/api/list/struct_group/'.$data_type.'.php 模版');

    return $content;
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

command('crud:make-docs-from-description', '通过描述文件生成 CRUD 相关接口文档', function ()
{/*{{{*/
    $entity_names = _get_entity_name_by_command_paramater();

    foreach ($entity_names as $entity_name) {

        $entity_info = description_get_entity($entity_name);

        $relationship_infos = description_get_relationship_with_snaps_by_entity($entity_name);

        $docs_page_file_string = _generate_docs_page_file($entity_name, $entity_info, $relationship_infos);

        $docs_api_file_string = _generate_docs_api_file($entity_name, $entity_info, $relationship_infos);

        // 写文件
        $docs_page_file_relative_path = 'page/'.$entity_name.'.md';
        error_log($docs_page_file_string, 3, $docs_page_file = DOCS_DIR.'/'.$docs_page_file_relative_path);
        echo "generate $docs_page_file success!\n";
        echo "todo ".DOCS_DIR."/sidebar.md include $docs_page_file_relative_path\n";

        $docs_api_file_relative_path = 'api/'.$entity_name.'.md';
        error_log($docs_api_file_string, 3, $docs_api_file = DOCS_DIR.'/'.$docs_api_file_relative_path);
        echo "generate $docs_api_file success!\n";
        echo "todo ".DOCS_DIR."/sidebar.md include $docs_api_file_relative_path\n";
    }
});/*}}}*/
