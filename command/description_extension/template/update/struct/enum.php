<select name='{{ $struct_name }}'>
@foreach ($entity_name::struct_formaters($struct_name) as $key => $value)
    <option value='{{ $key }}' ^^{^^{ '{{ $key }}' === ${{ $entity_name }}->{{ $struct_name }}?'selected':'' ^^}^^}>{{ $value }}</option>
@endforeach
</select>
