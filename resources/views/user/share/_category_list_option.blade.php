@foreach(\App\Service\CategoryService::getSubCategories($parent_id) as $parent)  
    @php 
        if($selected_categories== $parent['id']){
            $category_value = $parent['name'];
            $category_id = $parent['id'];
        } 
        $subCategories = \App\Service\CategoryService::getSubCategories($parent['id']);
    @endphp 
    <option value="{{ $parent['id'] }}" class="{{ $parent['layer'] == 0 ? 'bold' : '' }}" {{ $selected_categories == $parent['id'] ? "selected" : "" }}>
        @for($space = 0; $space < $parent['layer']; $space++) 
            &nbsp;&nbsp;&nbsp;
        @endfor
        {{ $parent['name'] }}
    </option>
    @if(sizeof($subCategories) > 0)
    @include('user.share._category_list_option', ['parent_id' => $parent['id']])
    @endif 
         
@endforeach
 