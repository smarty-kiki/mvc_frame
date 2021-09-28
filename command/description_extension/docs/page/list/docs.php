




### {{ $entity_info['display_name'] }}列表页面  
----
**功能：**通过多个筛选条件来查询{{ $entity_info['display_name'] }}的页面  
**请求方式：**`GET`  
**页面地址：**  
```
/{{ english_word_pluralize($entity_name) }}  
```
**参数：**  

|参数键名|类型|必传|描述|
|----|----|----|----|
@foreach ($entity_info['structs'] as $struct_name => $struct)
|{{ $struct_name }}|{{ $struct['data_type'] }}|可选|通过{{ $struct['display_name'] }}筛选|
@endforeach
@foreach ($relationship_infos['relationships'] as $attribute_name => $relationship)
@if ($relationship['relationship_type'] === 'belongs_to')
|{{ $attribute_name.'_id' }}|id|可选|通过关联关系 `{{ $attribute_name.'_id' }}` 筛选|
@endif
@foreach ($relationship['snaps'] as $structs)
@foreach ($structs as $struct_name => $struct)
|{{ $struct_name }}|{{ $struct['data_type'] }}|可选|通过关联关系 `{{ $attribute_name }}` 冗余{{ $struct['display_name'] }}筛选|
@endforeach
@endforeach
@endforeach
@foreach ($entity_info['struct_groups'] as $struct_group)
{{ blade_eval(_generate_docs_api_struct_group_list($struct_group['type']), ['struct_group_info' => $struct_group['struct_group_info'], 'structs' => $struct_group['structs'], 'struct_name_map' => $struct_group['struct_name_maps']]) }}
@endforeach

**示例页面**
<iframe height=550 width=90% src="/{{ english_word_pluralize($entity_name) }}" frameborder=1 allowfullscreen> </iframe>


