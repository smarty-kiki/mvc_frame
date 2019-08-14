<?php

function _generate_controller_file($entity_name, $entity_structs, $entity_relationships)
{/*{{{*/
    $resource_plural = english_word_pluralize($entity_name);
    $resource_id_key = $entity_name.'_id';

    $list_str = [];
    $input_str = [];
    foreach ($entity_structs as $struct) {
        $struct_name = $struct['name'];

        $list_str[] = "\$inputs['$struct_name']";
        $input_str[] = "'$struct_name'";
    }

    foreach ($entity_relationships as $relationship) {

        $relationship_type = $relationship['type'];
        $relationship_name = $relationship['relation_name'];

        if ($relationship_type !== 'has_many') {

            $list_str[] = "\$inputs['".$relationship_name."_id']";
            $input_str[] = "'".$relationship_name."_id'";
        }
    }

    $input_content = "\$inputs = [];
    list(
        ".implode(",\n        ", $list_str)."
    ) = input_list(
        ".implode(",\n        ", $input_str)."
    );
    \$inputs = array_filter(\$inputs, 'not_null');";

    $template = "<?php

if_get('/%s', function ()
{/*{{{*/
    %s

    return render('%s/list', [
        '%s' => dao('%s')->find_all_by_column(\$inputs),
    ]);
});/*}}}*/

if_get('/%s/add', function ()
{/*{{{*/
    return render('%s/add');
});/*}}}*/

if_post('/%s/add', function ()
{/*{{{*/
    %s

    $%s = %s::create();

    foreach (\$inputs as \$property => \$value) {
        $%s->{\$property} = \$value;
    }

    return redirect('/%s');
});/*}}}*/

if_get('/%s/update/*', function ($%s)
{/*{{{*/
    $%s = dao('%s')->find($%s);
    otherwise($%s->is_not_null(), '%s not found');

    return render('%s/update', [
        '%s' => $%s,
    ]);
});/*}}}*/

if_post('/%s/update/*', function ($%s)
{/*{{{*/
    $%s = dao('%s')->find($%s);
    otherwise($%s->is_not_null(), '%s not found');

    %s

    foreach (\$inputs as \$property => \$value) {
        $%s->{\$property} = \$value;
    }

    redirect('/%s');
});/*}}}*/

if_post('/%s/delete/*', function ($%s)
{/*{{{*/
    $%s = dao('%s')->find($%s);
    otherwise($%s->is_not_null(), '%s not found');

    $%s->delete();

    redirect('/%s');
});/*}}}*/";

    return sprintf($template, 

        // if_get_all
        $resource_plural,
        $input_content,
        $entity_name,
        $resource_plural, $entity_name,

        // if_get_add
        $resource_plural,
        $entity_name,

        // if_post_add
        $resource_plural,
        $input_content,
        $entity_name, $entity_name,
        $entity_name,
        $resource_plural,

        // if_get_update
        $resource_plural, $resource_id_key,
        $entity_name, $entity_name, $resource_id_key,
        $entity_name, $entity_name,
        $entity_name,
        $entity_name, $entity_name,

        // if_post_update
        $resource_plural, $resource_id_key,
        $entity_name, $entity_name, $resource_id_key,
        $entity_name, $entity_name,
        $input_content,
        $entity_name,
        $resource_plural,

        // if_post_delete
        $resource_plural, $resource_id_key,
        $entity_name, $entity_name, $resource_id_key,
        $entity_name, $entity_name,
        $entity_name,
        $resource_plural
    );
}/*}}}*/

function _generate_view_add_file($entity_name)
{/*{{{*/
    $template = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>{{ \$entity_name::\$entity_display_name }}添加</title>
    <style>
     table {
         font-family: verdana,arial,sans-serif;
         font-size:11px;
         color:#333333;
         border-width: 1px;
         border-color: #666666;
         border-collapse: collapse;
         width: 100%%;
     }
     table th {
         border-width: 1px;
         padding: 8px;
         border-style: solid;
         border-color: #666666;
         background-color: #dedede;
         text-align: center;
     }
     table td {
         border-width: 1px;
         padding: 8px;
         border-style: solid;
         border-color: #666666;
         background-color: #ffffff;
         text-align: center;
     }
    </style>
</head>
<body>
<table>
<tbody>

    <form action='' method='POST'>
@foreach (\$entity_name::\$struct_types as \$struct => \$type)
    <tr>
        <td>{{ array_key_exists(\$struct, \$entity_name::\$struct_display_names)? \$entity_name::\$struct_display_names[\$struct]: \$struct }}</td>
        <td>
@if (\$type === 'enum')
            <select name='{{ \$struct }}'>
@foreach (\$entity_name::\$struct_formats[\$struct] as \$key => \$value)
                <option value='{{ \$key }}'>{{ \$value }}</option>
@endforeach
            </select>
@else
            <input type='{{ \$type }}' name='{{ \$struct }}'>
@endif
        </td>
    </tr>
@endforeach
    <tr>
        <td>
            <a href='javascript:window.history.back(-1);'>取消</a>
        </td>
        <td>
            <input type='submit' value='保存'>
        </td>
    </tr>
    </form>
</tbody>
</table>
</body>
<script>
</script>
</html>";

    return blade_eval($template, [
        'entity_name' => $entity_name,
    ]);
}/*}}}*/

function _generate_view_update_file($entity_name)
{/*{{{*/

    $template = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>{{ \$entity_name::\$entity_display_name }}[@{{ \$%s->id }}]修改</title>
    <style>
     table {
         font-family: verdana,arial,sans-serif;
         font-size:11px;
         color:#333333;
         border-width: 1px;
         border-color: #666666;
         border-collapse: collapse;
         width: 100%%;
     }
     table th {
         border-width: 1px;
         padding: 8px;
         border-style: solid;
         border-color: #666666;
         background-color: #dedede;
         text-align: center;
     }
     table td {
         border-width: 1px;
         padding: 8px;
         border-style: solid;
         border-color: #666666;
         background-color: #ffffff;
         text-align: center;
     }
    </style>
</head>
<body>
<table>
<tbody>

    <form action='' method='POST'>
@foreach (\$entity_name::\$struct_types as \$struct => \$type)
    <tr>
        <td>{{ array_key_exists(\$struct, \$entity_name::\$struct_display_names)? \$entity_name::\$struct_display_names[\$struct]: \$struct }}</td>
        <td>
@if (\$entity_name::\$struct_types[\$struct] === 'enum')
            <select name='{{ \$struct }}'>
@foreach (\$entity_name::\$struct_formats[\$struct] as \$key => \$value)
                <option value='{{ \$key }}' ^{^{ \$key === \$%s->{{\$struct}}?'selected':'' ^}^}>{{ \$value }}</option>
@endforeach
            </select>
@else
            <input type='{{ \$type }}' name='{{ \$struct }}' value='@{{ \$%s->{\$struct} }}'>
@endif
        </td>
    </tr>
@endforeach
    <tr>
        <td>
            <a href='javascript:window.history.back(-1);'>取消</a>
        </td>
        <td>
            <input type='submit' value='保存'>
        </td>
    </tr>
    </form>

</tbody>
</table>
</body>
<script>
</script>
</html>";

    $template =  sprintf($template, $entity_name, $entity_name, $entity_name);

    $content = blade_eval($template, [
        'entity_name' => $entity_name,
    ]);

    return str_replace('^', '', $content);
}/*}}}*/

function _generate_view_list_file($entity_name)
{/*{{{*/
    $resource_plural = english_word_pluralize($entity_name);

    $template = "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>{{ %s::\$entity_display_name }}</title>
    <style>
     table {
         font-family: verdana,arial,sans-serif;
         font-size:11px;
         color:#333333;
         border-width: 1px;
         border-color: #666666;
         border-collapse: collapse;
         width: 100%%;
     }
     table th {
         border-width: 1px;
         padding: 8px;
         border-style: solid;
         border-color: #666666;
         background-color: #dedede;
         text-align: center;
     }
     table td {
         border-width: 1px;
         padding: 8px;
         border-style: solid;
         border-color: #666666;
         background-color: #ffffff;
         text-align: center;
     }
    </style>
</head>
<body>
<table>
<thead>
    <tr>
        <th>ID</th>
@foreach (%s::\$struct_types as \$struct => \$type)
        <th>{{ array_key_exists(\$struct, %s::\$struct_display_names)? %s::\$struct_display_names[\$struct]: \$struct }}</th>
@endforeach
        <th>
            <a href='/%s/add'>添加</a>
        </th>
    </tr>
</thead>
    @^foreach (\$%s as \$id => \$%s)
    <tr>
        <td>@{{ \$id }}</td>
@foreach (%s::\$struct_types as \$struct => \$type)
@if (%s::\$struct_types[\$struct] === 'enum')
        <td>{^{ \$%s->get_{{\$struct}}_description() }^}</td>
@else
        <td>{^{ \$%s->{{\$struct}} }^}</td>
@endif
@endforeach
        <td>
            <a href='/%s/update/@{{ \$%s->id }}'>修改</a>
            <a href='javascript:delete_@{{ \$%s->id }}.submit();'>删除</a>
            <form id='delete_@{{ \$%s->id }}' action='/%s/delete/@{{ \$%s->id }}' method='POST'></form>
        </td>
    </tr>
    @^endforeach
<tbody>
</tbody>
</table>
</body>
</html>";

    $template = sprintf($template,
        $entity_name,
        $entity_name,
        $entity_name, $entity_name,
        $resource_plural,
        $resource_plural, $entity_name,
        $entity_name,
        $entity_name,
        $entity_name,
        $entity_name,
        $resource_plural,
        $entity_name,
        $entity_name,
        $entity_name, $resource_plural, $entity_name
    );

    $content = blade_eval($template, [
        'entity_name' => $entity_name,
    ]);

    return str_replace('^', '', $content);
}/*}}}*/

command('crud:make-from-description', '通过描述文件生成 CRUD 控制器和页面', function ()
{/*{{{*/

    $entity_name = command_paramater('entity_name');

    $description = _get_value_from_description_file($entity_name);

    $structs = array_get($description, 'structs', []);
    $entity_display_name = array_get($description, 'display_name', '');
    $entity_description = array_get($description, 'description', '');

    $entity_structs = [];
    foreach ($structs as $column => $struct) {

        $tmp = [
            'name' => $column,
            'datatype' => $struct['type'],
            'display_name' => $struct['display_name'],
            'description' => $struct['description'],
            'format' => array_get($struct, 'format', null),
            'format_description' => array_get($struct, 'format_description', null),
            'allow_null' => array_get($struct, 'allow_null', false),
        ];

        if (array_key_exists('default', $struct)) {
            $tmp['default'] = $struct['default'];
        }

        $entity_structs[] = $tmp;
    }

    $relationships = array_get($description, 'relationships', []);

    $entity_relationships = [];
    foreach ($relationships as $relation_name => $relationship) {

        $relation_entity_name = $relationship['entity'];
        $relation_type = $relationship['type'];

        $entity_relationships[] = [
            'type' => $relation_type,
            'relate_to' => $relation_entity_name,
            'relation_name' => $relation_name,
        ];

        if ($relation_type !== 'has_many') {

            _get_value_from_description_file($relation_entity_name);
        }
    }

    $snaps = array_get($description, 'snaps', []);

    foreach ($snaps as $snap_relation_to_with_dot => $snap) {

        $parent_description = $description;

        $snap_relation_name = '';

        foreach (explode('.', $snap_relation_to_with_dot) as $snap_relation_to) {

            $snap_relation = array_get($parent_description, "relationships.".$snap_relation_to, false);

            otherwise($snap_relation, "与冗余的 $snap_relation_to 没有关联关系");
            otherwise($snap_relation['type'] !== 'has_many', "冗余的 $snap_relation_to 为 has_many 关系，无法冗余字段");

            $parent_description = _get_value_from_description_file($snap_relation['entity']);
            $snap_relation_name = $snap_relation_to;
        }

        $snap_relation_to_structs = $parent_description['structs'];

        foreach ($snap['structs'] as $column) {

            otherwise(array_key_exists($column, $snap_relation_to_structs), "需要冗余的字段 $column 在 $snap_relation_to_with_dot 中不存在");

            $struct = $snap_relation_to_structs[$column];

            $tmp = [
                'name' => 'snap_'.$snap_relation_name.'_'.$column,
                'datatype' => $struct['type'],
                'display_name' => $struct['display_name'],
                'description' => $struct['description'],
                'format' => array_get($struct, 'format', null),
                'format_description' => array_get($struct, 'format_description', null),
                'allow_null' => array_get($struct, 'allow_null', false),
            ];

            if (array_key_exists('default', $struct)) {
                $tmp['default'] = $struct['default'];
            }

            $entity_structs[] = $tmp;
        }
    }

    $dir_name = VIEW_DIR.'/'.$entity_name;

    otherwise(
        is_dir($dir_name)
        || mkdir($dir_name, 0755),
        "当前用户没有权限创建目录 $dir_name");

    $controller_file_string = _generate_controller_file($entity_name, $entity_structs, $entity_relationships);
    $view_add_file_string = _generate_view_add_file($entity_name);
    $view_update_file_string = _generate_view_update_file($entity_name);
    $view_list_file_string = _generate_view_list_file($entity_name);


    // 写文件
    error_log($controller_file_string, 3, $controller_file = CONTROLLER_DIR.'/'.$entity_name.'.php');
    echo $controller_file."\n";
    error_log($view_add_file_string, 3, $file = $dir_name.'/add.php');
    echo $file."\n";
    error_log($view_update_file_string, 3, $file = $dir_name.'/update.php');
    echo $file."\n";
    error_log($view_list_file_string, 3, $file = $dir_name.'/list.php');
    echo $file."\n";
    echo "\n将 $controller_file 加入到 public/index.php 即可响应请求\n";
});/*}}}*/
