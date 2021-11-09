@foreach(\App\Service\CategoryService::getSubCategories($parent_id) as $parent) 
<ul class="category-bread">  
        @php
            $subCategories = \App\Service\CategoryService::getSubCategories($parent['id']); 
        @endphp

        @if(in_array($parent['id'], isset($selected_categories) ? $selected_categories :[]))
        @php
            $fullName = $fullName . ' ' . $parent['name'];
        @endphp
        <li>{{ $parent['name'] }}</li>
        @endif
        
        @if(sizeof($subCategories) > 0)
        @include('user.share._get_category_list', ['parent_id' => $parent['id'], 'fullName' => $fullName, 'selected_categories' => $selected_categories])
        @endif   
</ul>
@endforeach