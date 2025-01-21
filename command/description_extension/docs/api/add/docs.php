




### 添加{{ $entity_info['display_name'] }} 
----
**功能：**添加{{ $entity_info['display_name'] }}  
@if ($entity_info['repeat_check_structs'])
@php
$repeat_check_structs = $entity_info['repeat_check_structs'];
$msg_infos = [];
foreach ($repeat_check_structs as $struct_name) {
    $msg_infos[] = $entity_info['structs'][$struct_name]['display_name'];
}
@endphp
**注：**不能同时存在{{ implode('和', $msg_infos) }}相同的{{ $entity_info['display_name'] }}  
@endif
**请求方式：**`POST`  
**请求地址：**  
```
/{{ english_word_pluralize($entity_name) }}/add  
```
**参数：**  

|参数键名|类型|必传|描述|
|----|----|----|----|
@foreach ($relationship_infos['relationships'] as $attribute_name => $relationship)
@if ($relationship['relationship_type'] === 'belongs_to')
|{{ $attribute_name.'_id' }}|id|{{ $relationship['require']? '必传': '可选' }}|设置{{ $entity_info['display_name'] }}所属的{{ $relationship['entity_display_name'] }}，此处传{{ $relationship['entity_display_name'] }}的`id`|
@endif
@endforeach
@foreach ($entity_info['structs'] as $struct_name => $struct)
|{{ $struct_name }}|{{ $struct['data_type'] }}|{{ $struct['require']? '必传':'可选' }}|{{ $struct['display_name'] }}|
@endforeach

**返回值：**  
```json
{
    "code": 0,
    "msg": "",
    "data": [
        "id": 1 //{{ $entity_info['display_name'] }} id
    ]
}
```



