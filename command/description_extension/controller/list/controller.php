if_get('/{{ english_word_pluralize($entity_name) }}', function ()
{/*{^^{^^{*/
@php
$inputs = [];

foreach ($entity_info['structs'] as $struct_name => $struct) {
    $inputs[] = $struct_name;
}

foreach ($relationship_infos['relationships'] as $attritube_name => $relationship) {

    if ($relationship['relationship_type'] === 'belongs_to') {
        $inputs[] = $attritube_name.'_id';
    }

    foreach ($relationship['snaps'] as $structs) {
        foreach ($structs as $struct_name => $struct) {
            $inputs[] = $struct_name;
        }
    }
}
@endphp
    list(
        {{ implode(', ', array_map(function($v) { return "\$inputs['".$v."']"; }, $inputs)) }}

    ) = input_list(
        {{ implode(', ', array_map(function($v) { return "'".$v."'"; }, $inputs)) }}

    );
    $inputs = array_filter($inputs, 'not_null');

    return render('{{ $entity_name }}/list', [
        '{{ english_word_pluralize($entity_name) }}' => dao('{{ $entity_name }}')->find_all_by_column($inputs),
    ]);
});/*}}}*/
