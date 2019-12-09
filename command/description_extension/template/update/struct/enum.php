<select name='{{ $struct }}'>
@foreach ($entity_name::struct_formaters($struct) as $key => $value)
    <option value='{{ $key }}' ^^{^^{ '{{ $key }}' === ${{ $entity_name }}->{{$struct}}?'selected':'' ^^}^^}>{{ $value }}</option>
@endforeach
</select>
