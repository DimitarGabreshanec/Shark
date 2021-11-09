<script src="{{ asset('assets/vendor/simple-tree-table/jquery-simple-tree-table.js') }}"></script>

<div id="btn_tree_operation" class="mb10 ml5 w600">
    <div class="btn_expand"><a id="tree_expander">Expand</a></div>
    <div class="btn_collapse"><a id="tree_collapser">Collapse</a></div>
</div>

<table id="category_tree" class="normal tree-table w600 ml5">
    <tr>
        <th class="bold w300">カテゴリー名</th>
    </tr>
    @foreach(\App\Service\CategoryService::getAllCategories() as $parent)
        <tr data-node-id="{{ $parent['id'] }}" data-node-pid="{{ $parent['parent_id'] }}">
            <td >
                <div class="checkbtn-wrap tree-node-check">
                    <input type="checkbox" class="check-parent node-parent-{{ $parent['parent_id'] }}" id="category_{{ $parent['id'] }}" data-parent="{{ $parent['parent_id'] }}" value="{{ $parent['id'] }}" name="category[{{ $parent['id'] }}]" {{ in_array($parent['id'], old('category', isset($selected_categories) ? $selected_categories :[])) ? 'checked' : '' }}>
                    <label for="category_{{ $parent['id'] }}" class="check_label {{ $parent['layer'] == 0 ? 'bold' : ''}}">{{ $parent['name'] }}</label>
                </div>
            </td>
        </tr>
    @endforeach 
</table>
<script>
 
    $('#category_tree').simpleTreeTable({
        expander: $('#tree_expander'),
        collapser: $('#tree_collapser'),
        store: 'session',
        storeKey: 'category-tree-data'
    });
 
    $(document).ready(function(){ 
        $(".check-parent").change(function () {  
            var parent_id = $(this).val();  
            $("input.node-parent-" + parent_id + ":checkbox").prop('checked', $(this).prop("checked"));  
            $("input.node-parent-" + parent_id + ":checkbox").change();  

            var parent_id = $(this).data('parent'); 
            if($("input.node-parent-" + parent_id + ":checked").length == $("input.node-parent-" + parent_id).length){
                $("input#category_" + parent_id + ":checkbox").prop('checked',true);   
                selChanged($("input#category_" + parent_id + ":checkbox"));
            }else{
                $("input#category_" + parent_id + ":checkbox").prop('checked',false);
                selChanged($("input#category_" + parent_id + ":checkbox"));
            }   
             
        });

        function selChanged(category){
            var parent_id = category.data('parent');  
            if($("input.node-parent-" + parent_id + ":checked").length == $("input.node-parent-" + parent_id).length){
                $("input#category_" + parent_id + ":checkbox").prop('checked',true);   
            }else{
                $("input#category_" + parent_id + ":checkbox").prop('checked',false);
            } 
        } 
         
    });
</script>

<style>
    table#category_tree tbody td label{
        font-size: 12px;
    }
    table#category_tree tbody td{
        padding: 5px 10px !important;
    }
    td.td-child-node {
        padding-left: 30px !important;
    }
    div.tree-node-check {
        margin-left: 10px;
        display: inline;
    }
</style>

