<select name='{{ $struct_name }}'>
@foreach ($struct['validator'] as $value => $description)
    <option value='^^{^^{ {{ $entity_name }}::{{ strtoupper($struct_name.'_'.$value) }} ^^}^^}'>^^{^^{ {{ $entity_name }}::{{ strtoupper($struct_name) }}_MAPS[{{ $entity_name }}::{{ strtoupper($struct_name.'_'.$value) }}] ^^}^^}</option>
@endforeach
</select>
