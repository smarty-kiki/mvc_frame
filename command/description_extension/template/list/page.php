<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>{{ $entity_info['display_name'] }}</title>
    <style>
     table {
         font-family: verdana,arial,sans-serif;
         font-size:11px;
         color:#333333;
         border-width: 1px;
         border-color: #666666;
         border-collapse: collapse;
         width: 100%;
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
@foreach ($relationship_infos['relationships'] as $attribute_name => $relationship)
@if ($relationship['relationship_type'] === 'belongs_to')
        <th>{{ $relationship['entity_display_name'] }}</th>
@foreach ($relationship['snaps'] as $structs)
@foreach ($structs as $struct_name => $struct)
        <th>{{ $struct['display_name'] }}</th>
@endforeach
@endforeach
@endif
@endforeach
@foreach ($entity_info['structs'] as $struct_name => $struct)
        <th>{{ $struct['display_name'] }}</th>
@endforeach
        <th>
            <a href='/{{ english_word_pluralize($entity_name) }}/add'>添加</a>
        </th>
    </tr>
</thead>
    @^^foreach (${{ english_word_pluralize($entity_name) }} as $id => ${{ $entity_name }})
    <tr>
        <td>^^{^^{ $id ^^}^^}</td>
@foreach ($relationship_infos['relationships'] as $attribute_name => $relationship)
@if ($relationship['relationship_type'] === 'belongs_to')
        <td>^^{^^{ ${{ $entity_name }}->{{ $attribute_name }}->display_for_{{ $relationship['self_attribute_name'] }}_{{ $attribute_name }}() ^^}^^}</td>
@foreach ($relationship['snaps'] as $structs)
@foreach ($structs as $struct_name => $struct)
        <td>
            {{ blade_eval(_generate_template_data_type_list($struct['data_type']), ['entity_name' => $entity_name, 'struct_name' => $struct_name, 'struct' => $struct]) }}
        </td>
@endforeach
@endforeach
@endif
@endforeach
@foreach ($entity_info['structs'] as $struct_name => $struct)
        <td>
            {{ blade_eval(_generate_template_data_type_list($struct['data_type']), ['entity_name' => $entity_name, 'struct_name' => $struct_name, 'struct' => $struct]) }}
        </td>
@endforeach
        <td>
            <a href='/{{ english_word_pluralize($entity_name) }}/detail/^^{^^{ ${{ $entity_name }}->id ^^}^^}'>详情</a>
            <a href='/{{ english_word_pluralize($entity_name) }}/update/^^{^^{ ${{ $entity_name }}->id ^^}^^}'>修改</a>
            <a href='javascript:delete_^^{^^{ ${{ $entity_name }}->id ^^}^^}.submit();'>删除</a>
            <form id='delete_^^{^^{ ${{ $entity_name }}->id ^^}^^}' action='/{{ english_word_pluralize($entity_name) }}/delete/^^{^^{ ${{ $entity_name }}->id ^^}^^}' method='POST'></form>
        </td>
    </tr>
    @^^endforeach
<tbody>
</tbody>
</table>
</body>
</html>
