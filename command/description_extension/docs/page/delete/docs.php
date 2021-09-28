




### 删除{{ $entity_info['display_name'] }}提交  
----
**功能：**删除{{ $entity_info['display_name'] }}的表单提交地址  
**请求方式：**`POST`  
**页面地址：**  
```
/{{ english_word_pluralize($entity_name) }}/delete/{^^{^^{{ $entity_name }}_id}^^}^^  
```
**`URL`中的变量：**  

|变量键名|类型|必传|描述|
|----|----|----|----|
|{{ $entity_name }}_id|id|必传|{{ $entity_info['display_name'] }}的主键，`id`|
|refer_url|string|可选|处理完后跳转的链接，不传则跳转 `/{{ english_word_pluralize($entity_name) }}`|




