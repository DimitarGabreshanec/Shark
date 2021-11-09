@extends('admin.layouts.app')

@section('content')
    <h2 class="title">カテゴリー管理</h2>
    @include('admin.layouts.flash-message')
    <div id="btn_tree_operation" class="mb10 ml5 w540">
        <div class="btn_expand"><a id="tree_expander">Expand</a></div>
        <div class="btn_collapse"><a id="tree_collapser">Collapse</a></div>
        <div class="add_fieldbtn"><a id="add_parent_node" data-type="parent" class="add-node">追加</a></div>
    </div>
    {{ Form::open(["route"=>"admin.category.sequence_update", "method"=>"POST"]) }}
    <table id="category_tree" class="normal tree-table w540 ml5">
        <tr>
            <th class="bold w300">カテゴリー名</th>
            <th class="w70">表示順序</th>
            <th class="bold w250">アクション</th>
        </tr> 
        @foreach(\App\Service\CategoryService::getAllCategories() as $parent) 
        <tr data-node-id="{{ $parent['id'] }}" data-node-pid="{{ $parent['parent_id'] }}">
            <td ><span class="bold">{{ $parent['name'] }}</td>
            <td><input id="sequence_{{ $parent['id'] }}" type="text" class="input-text3 w50 bold" name="sequence[{{ $parent['id'] }}]" value="{{ $parent['sequence'] }}" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');"></td>
            <td >
                @if ($parent['layer'] < config('const.CATEGORY_LAYER_MAX'))
                    <div class="btn_add_tree_node"><a class="add-node" id="{{ $parent['id'] }}" data-type="child" data-parent_id="{{ $parent['id'] }}" data-layer="{{ $parent['layer'] }}">追加</a></div>
                @endif
                <div class="btn_edit_tree_node"><a class="edit-node" data-cate_name="{{ $parent['name'] }}" data-type="child" data-parent_id="{{ $parent['id'] }}" data-sequence_id="{{ $parent['sequence'] }}">編集</a></div>
                <div class="btn_remove_tree_node"><a class="remove-node" data-type="child" data-pk="{{ $parent['id'] }}" data-layer="{{ $parent['layer'] }}">削除</a></div>
            </td>
        </tr>
        @endforeach 
    </table> 

    <script>
        $('#category_tree').simpleTreeTable({
            expander: $('#tree_expander'),
            collapser: $('#tree_collapser'),
            store: 'session',
            storeKey: 'simple-tree-table-basic'
        });
        $('#open1').on('click', function() {
            $('#basic').data('simple-tree-table').openByID("1");
        });
        $('#close1').on('click', function() {
            $('#basic').data('simple-tree-table').closeByID("1");
        });

    </script>

    <div class="center-submit double">
        <input type="submit" id="save" value="表示順序更新">
    </div>
    {{ Form::close() }}
    <div id="dialog-input" style="display:none">
        <p>
            <a id="label">カテゴリー名: </a><input type="text" class="w160" required name="category_name" id="category_name"> <br>
            <a id="label">表示順序: </a><input type="text" class="w80" name="sequence_new" required id="sequence_new" maxlength="5" oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/(\..*)\./g, '$1');">
            <input type="hidden" name="parent_id">
        </p>
    </div>
@endsection

@section('page_css')
    <link rel="stylesheet" href="{{ asset('assets/admin/css/pages/category.css') }}">
@endsection

@section('page_js')
    <script src="{{ asset('assets/vendor/simple-tree-table/jquery-simple-tree-table.js') }}"></script>
    <script>
        $(document).on('click', '.add-node', function(e){
            let parent_id = $(this).data("parent_id");
            let msg = 'サブカテゴリー';  
            let layer = $(this).data("layer") + 1; 
            if(parent_id == null){
                msg = '大カテゴリー';
            }
            helper_confirm("dialog-input", msg, "", 300, "追加", "閉じる", function(){
                let category_name = $('#category_name').val();
                let sequence = $('#sequence_new').val();  
                if(category_name == ''){
                    alert('カテゴリー名を入力してください。');
                    return false;
                } 
                if(sequence == ''){
                    alert('表示順序を入力してください。');
                    return false;
                } 
                if(parent_id == null){
                    parent_id = 0;
                } 
                $.ajax({
                    type: "post",
                    url : '{{ route("admin.category.add") }}',
                    data: {
                        parent_id: parent_id,
                        name:category_name,
                        sequence:sequence,
                        layer:layer,
                        _token: '{{ csrf_token() }}',
                    },
                    dataType: "json",
                    success: function(result) { 
                        location.reload();
                        return true;
                    },
                    error: function(){
                        alert('お気に入りを設定することが出来ません。');
                    },
                });
            });

            $('parent_id').val(null);
            $('#category_name').val(null);
            $('#sequence_new').val(null); 

        });

        $(document).on('click', '.remove-node', function(e){
            let remove_id = $(this).data("pk");
            let layer = $(this).data('layer');
            let msg = ''; 
            if(layer < {{ config('const.CATEGORY_LAYER_MAX') }}){
                msg = '子カテゴリーも削除されます。';
            }
            helper_confirm("dialog-confirm", "削除", "カテゴリーを削除します。<br />" + msg + "<br />よろしいですか？", 250, "確認", "閉じる", function(){
                $.ajax({
                    type: "post",
                    url : '{{ route("admin.category.delete") }}',
                    data: {
                        id: remove_id,
                        _token: '{{ csrf_token() }}',
                    },
                    dataType: "json",
                    success: function(result) {
                        location.reload();
                        return true;
                    },
                    error: function(){
                        alert('お気に入りを設定することが出来ません。');
                    },
                });
            });

            $('parent_id').val(null);
            $('#category_name').val(null);
            $('#sequence_new').val(null);

        });

        $(document).on('click', '.edit-node', function(e){
            let update_id = $(this).data('parent_id');
            let category_name = $(this).data('cate_name');
            let sequence = $(this).data('sequence_id');
            $('#category_name').val(category_name);
            $('#sequence_new').val(sequence); 
            let msg = 'サブカテゴリー';
            if(update_id == null){
                msg = '大カテゴリー';
            }
            helper_confirm("dialog-input", msg, "", 300, "更新", "閉じる", function(){
                let category_name = $('#category_name').val();
                let sequence = $('#sequence_new').val();
                if(category_name == ''){
                    alert('カテゴリー名を入力してください。');
                    return false;
                }  
                if(sequence == ''){
                    alert('表示順序を入力してください。');
                    return false;
                }
                $.ajax({
                    type: "post",
                    url : '{{ route("admin.category.update") }}',
                    data: {
                        id: update_id,
                        name: category_name,
                        sequence: sequence,
                        _token: '{{ csrf_token() }}',
                    },
                    dataType: "json",
                    success: function(result) {
                        location.reload();
                        return true;
                    },
                    error: function(){
                        alert('お気に入りを設定することが出来ません。');
                    },
                });
            });

        });

        function helper_confirm(id, title, content, width, btnConfirm, btnClose, callback, callback_cancel) {
            let dialog = $("#" + id);
            dialog.attr('title', title);
            let titleId = document.getElementById('ui-id-1'); 
            if(titleId != null){
                titleId.innerHTML= title;
            }
            
            $("#confirm_text", dialog).html(content);
            let confirm_buttons = {};
            confirm_buttons[btnConfirm] = function() { 
                if (callback()) { 
                    $(this).dialog("close");
                } 
            };
            confirm_buttons[btnClose] = function() {
                
                //callback_cancel();  
                $(this).dialog("close");
            };

            dialog.dialog({
                resizable: false,
                height: "auto",
                width: width,
                modal: true,
                buttons: confirm_buttons
            });
        }


    </script>
@endsection
