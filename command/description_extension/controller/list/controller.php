if_get('/{{ english_word_pluralize($entity_name) }}', function ()
{/*{^^{^^{*/
@php
$inputs = [];

foreach ($entity_info['structs'] as $struct_name => $struct) {
    $inputs[] = $struct_name;
}

foreach ($relationship_infos['relationships'] as $attribute_name => $relationship) {

    if ($relationship['relationship_type'] === 'belongs_to') {
        $inputs[] = $attribute_name.'_id';
    }

    foreach ($relationship['snaps'] as $structs) {
        foreach ($structs as $struct_name => $struct) {
            $inputs[] = $struct_name;
        }
    }
}
@endphp
@if (! empty($inputs))
    $inputs = [];

    list(
        {{ implode(', ', array_map(function($v) { return "\$inputs['".$v."']"; }, $inputs)) }}

    ) = input_list(
        {{ implode(', ', array_map(function($v) { return "'".$v."'"; }, $inputs)) }}

    );

@endif
@foreach ($entity_info['struct_groups'] as $struct_group)
{{ blade_eval(_generate_controller_struct_group_list($struct_group['type']), ['struct_group_info' => $struct_group['struct_group_info'], 'structs' => $struct_group['structs'], 'struct_name_map' => $struct_group['struct_name_maps']]) }}

@endforeach
@if (! empty($inputs))
    $inputs = array_filter($inputs, 'not_null');

@endif
    return render('{{ $entity_name }}/list', [
@if (! empty($inputs))
        '{{ english_word_pluralize($entity_name) }}' => dao('{{ $entity_name }}')->find_all_by_column($inputs),
@else
        '{{ english_word_pluralize($entity_name) }}' => dao('{{ $entity_name }}')->find_all(),
@endif
    ]);
});/*}}}*/
