<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>{{ $entity_info['display_name'] }}[{^^{ ${{ $entity_name }}->id }^^}]修改</title>
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
<tbody>

@foreach ($relationship_infos['relationships'] as $attribute_name => $relationship)
@if ($relationship['relationship_type'] === 'belongs_to')
    <tr>
        <td>{{ $relationship['entity_display_name'] }}</td>
        <td>
            ^^{^^{ ${{ $entity_name }}->{{ $attribute_name }}->display_for_{{ $relationship['self_attribute_name'] }}_{{ $attribute_name }}() ^^}^^}
        </td>
    </tr>
@endif
@endforeach
@foreach ($entity_info['struct_groups'] as $struct_group)
{{ blade_eval(_generate_template_struct_group_detail($struct_group['type']), ['entity_name' => $entity_name, 'struct_group_info' => $struct_group['struct_group_info'], 'struct_group_structs' => $struct_group['structs'], 'struct_name_map' => $struct_group['struct_name_maps'], 'structs' => $entity_info['structs']]) }}
@endforeach
@foreach ($entity_info['structs'] as $struct_name => $struct)
@if (! isset($struct['struct_group_type']))
    <tr>
        <td>{{ $struct['require']?'<span style="color:red;">*</span>':'' }}{{ $struct['display_name'] }}</td>
        <td>
            {{ blade_eval(_generate_template_data_type_detail($struct['data_type']), ['entity_name' => $entity_name, 'struct_name' => $struct_name, 'struct' => $struct]) }}
        </td>
    </tr>
@endif
@endforeach
</tbody>
</table>
</body>
<script>
</script>
</html>
