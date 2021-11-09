@foreach(\App\Service\CategoryService::getSubCategories($parent_id) as $parent) 
<li>
    <dl>
        <dt class="checkbtn-wrap tree-node-check">   
            <input type="checkbox" class="check-parent node-parent-{{ $parent['parent_id'] }}" id="category_{{ $parent['id'] }}" data-parent="{{ $parent['parent_id'] }}" value="{{ $parent['id'] }}" name="categories[{{ $parent['id'] }}]" {{ in_array($parent['id'], old('category', isset($selected_categories) ? $selected_categories :[])) ? 'checked' : '' }} {{ $disabled }}>
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
            @include('user.share._category_list_li', ['parent_id' => $parent['id'], 'selected_categories' => $selected_categories])
        </dd>
        @endif  
    </dl>
</li>
@endforeach