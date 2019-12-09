<select name='{{ $struct }}'>
@foreach ($entity_name::struct_formaters($struct) as $key => $value)
    <option value='{{ $key }}'>{{ $value }}</option>
@endforeach
</select>
