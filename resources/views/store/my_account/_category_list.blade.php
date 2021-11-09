@foreach(\App\Service\CategoryService::getSubCategories($parent_id) as $parent) 
<li>
    <dl>
        <dt class="checkbtn-wrap tree-node-check">   
            <input type="checkbox" class="check-parent node-parent-{{ $parent['parent_id'] }}" id="category_{{ $parent['id'] }}" data-parent="{{ $parent['parent_id'] }}" value="{{ $parent['id'] }}" name="category[{{ $parent['id'] }}]" {{ in_array($parent['id'], old('category', isset($store) ? $store->arr_categories() : [])) ? 'checked' : '' }} {{ $disabled }}>
            <label for="category_{{ $parent['id'] }}" class="check_label bold">{{ $parent['name'] }}</label>
            @php
                $subCategories = \App\Service\CategoryService::getSubCategories($parent['id']);
            @endphp
            @if(sizeof($subCategories) > 0)
                <i>-</i>
            @endif
        </dt> 
        
        @if(sizeof($subCategories) > 0)
        <dd class={{ 'dd'.$parent['layer'] }}>
            @include('store.my_account._category_list', ['parent_id' => $parent['id']])
        </dd>
        @endif 
        
        
    </dl>
</li>
@endforeach