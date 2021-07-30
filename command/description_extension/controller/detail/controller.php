if_get('/{{ english_word_pluralize($entity_name) }}/detail/*', function (${{ $entity_name }}_id)
{/*{^^{^^{*/
    ${{ $entity_name }} = dao('{{ $entity_name }}')->find(${{ $entity_name }}_id);
    otherwise_error_code('{{ strtoupper($entity_name.'_NOT_FOUND') }}', ${{ $entity_name }}->is_not_null());

    return render('{{ $entity_name }}/detail', [
        '{{ $entity_name }}' => ${{ $entity_name }},
    ]);
});/*}}}*/
