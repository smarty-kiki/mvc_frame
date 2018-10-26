<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $entity_display_name }}管理</title>
</head>
<body>

<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css" />
<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css" />

<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>

<div id="{{ $entity_name }}_js_grid"></div>

<script>
$(function() {

    $("#{{ $entity_name }}_js_grid").jsGrid({
        height: "90%",
        width: "100%",

        filtering: true,
        editing: true,
        sorting: true,
        paging: true,
        autoload: true,
        inserting: true,

        pageSize: 15,
        pageButtonCount: 5,

        deleteConfirm: "{{ $delete_confirm_message }}",

        controller: {
            loadData: function(filter) {
                return $.ajax({
                    type: "GET",
                    url: "{{ $load_data_url }}",
                    data: filter
                });
            },

            insertItem: function(item) {
                return $.ajax({
                type: "POST",
                    url: "{{ $insert_data_url }}",
                    data: item
                });
            },

            updateItem: function(item) {
                return $.ajax({
                    type: "POST",
                    url: "{{ $update_data_url }}" + item.id,
                    data: item
                });
            },

            deleteItem: function(item) {
                return $.ajax({
                    type: "POST",
                    url: "{{ $delete_data_url }}" + item.id,
                    data: item
                });
            },
        },

        fields: {{ $fields_data }}
    });
});
</script>

</body>
</html>
