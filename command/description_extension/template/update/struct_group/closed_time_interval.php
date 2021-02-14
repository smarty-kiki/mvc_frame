<tr>
    <td>{{ $struct_group_info['display_name'] }}时间</td>
    <td>
@php
$struct_name = $struct_name_map['$(name)_start_time'];
$struct = $structs[$struct_name];
@endphp
        {{ $struct['require']?'<span style="color:red;">*</span>':'' }}
        {{ blade_eval(_generate_template_data_type_update($struct['data_type']), ['entity_name' => $entity_name, 'struct_name' => $struct_name, 'struct' => $struct]) }}
        -
@php
$struct_name = $struct_name_map['$(name)_end_time'];
$struct = $structs[$struct_name];
@endphp
        {{ $struct['require']?'<span style="color:red;">*</span>':'' }}
        {{ blade_eval(_generate_template_data_type_update($struct['data_type']), ['entity_name' => $entity_name, 'struct_name' => $struct_name, 'struct' => $struct]) }}
    </td>
</tr>
