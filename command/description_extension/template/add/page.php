<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <title>{{ $entity_info['display_name'] }}添加</title>
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

    <form action='' method='POST'>
@foreach ($relationship_infos['relationships'] as $attribute_name => $relationship)
@if ($relationship['relationship_type'] === 'belongs_to')
    <tr>
        <td>{{ $relationship['entity_display_name'] }}</td>
        <td>
            <select name='{{ $attribute_name }}_id'>
@if ($relationship['association_type'] === 'aggregation')
                <option value='0'>无</option>
@endif
            @^^foreach (${{ english_word_pluralize($attribute_name) }} as $id => ${{ $attribute_name }})
                <option value='^^{^^{ $id ^^}^^}'>^^{^^{ ${{ $attribute_name }}->display_for_{{ $relationship['self_attribute_name'] }}_{{ $attribute_name }}() ^^}^^}</option>
            @^^endforeach
            </select>
        </td>
    </tr>
@endif
@endforeach
@foreach ($entity_info['structs'] as $struct_name => $struct)
    <tr>
        <td>{{ $struct['display_name'] }}</td>
        <td>
            {{ blade_eval(_generate_template_data_type_add($struct['data_type']), ['entity_name' => $entity_name, 'struct_name' => $struct_name, 'struct' => $struct]) }}
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
</html>
