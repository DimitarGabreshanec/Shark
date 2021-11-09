@if ($paginator->hasPages())
    <div class="table_head_wrap table_foot_wrap">
        <div class="pager">
            <span class="pager_text">全{{ $paginator->total() }}件中 {{ ($paginator->currentPage()-1) * $paginator->perPage() + 1 }}件〜 @if ( $paginator->currentPage() * $paginator->perPage() > $paginator->total()) {{ $paginator->total() }} @else {{ $paginator->currentPage() * $paginator->perPage() }} @endif 件を表示</span>
            <ul>
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li aria-label="@lang('pagination.previous')">
                        <a href="#" data-index="{{ isset($i) ? $i : '' }}" data-part="{{ $system_part }}" data-type="{{ $type }}" data-search="{{ isset($search_type)?$search_type:'' }}" onclick="event.preventDefault();">&lsaquo;</a>
                    </li>
                @else
                    <li>
                        <a href="{{ $paginator->previousPageUrl() }}" data-index="{{ isset($i) ? $i : '' }}" data-part="{{ $system_part }}" data-type="{{ $type }}" data-search="{{ isset($search_type)?$search_type:'' }}" class="product_page" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo;</a>
                    </li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li >{{ $element }}</li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li ><a class="current" href="#" data-index="{{ isset($i) ? $i : '' }}" data-part="{{ $system_part }}" data-type="{{ $type }}"  data-search="{{ isset($search_type)?$search_type:'' }}" onclick="event.preventDefault();">{{ $page }}</a></li>
                            @else
                                <li><a class="product_page" data-index="{{ isset($i) ? $i : '' }}" data-part="{{ $system_part }}" data-type="{{ $type }}" data-search="{{ isset($search_type)?$search_type:'' }}" href="{{ $url }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <li>
                        <a href="{{ $paginator->nextPageUrl() }}" data-index="{{ isset($i) ? $i : '' }}" data-part="{{ $system_part }}" data-type="{{ $type }}" data-search="{{ isset($search_type)?$search_type:'' }}" rel="next" class="product_page" aria-label="@lang('pagination.next')">&rsaquo;</a>
                    </li>
                @else
                    <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                        <a href="#" data-index="{{ isset($i) ? $i : '' }}" data-part="{{ $system_part }}" data-type="{{ $type }}" data-search="{{ isset($search_type)?$search_type:'' }}" onclick="event.preventDefault();">&rsaquo;</a>
                    </li>
                @endif

            </ul>
        </div>
    </div>
    <script>
        var prev_page = 0;
        $(document).on('click', '.product_page',function(event)
        {
            event.preventDefault();
            $('li').removeClass('active');
            $(this).parent('li').addClass('active');
            var myurl = $(this).attr('href');
            var page=$(this).attr('href').split('page=')[1];
            var index = $(this).attr('data-index');
            var data_part = $(this).attr('data-part');
            var data_type = $(this).attr('data-type');
            var search_type = $(this).attr('data-search');
            if(prev_page != page)
            {
                prev_page = page;
                getData(page, myurl, index, data_part, data_type, search_type);
            }
        });

        function getData(page, myurl, index, data_part, data_type, search_type)
        {
            var partner_id;
            var system_part;
            var post_data;
            var post_url = '';
            if (data_type == 'partner')
            {
                post_url = '{{ route("backend.ajax.search_partner") }}?page=' + page;
                if (data_part != '')
                {
                    partner_id = $("#partner2_id").val();
                    post_data = {
                        index: index,
                        partner_id: partner_id,
                        system_part: data_part,
                        _token:'{{ csrf_token() }}',
                    };
                } else {
                    if (index == '')
                        partner_id = $("#partner_id").val();
                    else
                        partner_id = $("#bill_dest_id").val();
                    post_data = {
                        index: index,
                        partner_id: partner_id,
                        _token:'{{ csrf_token() }}',
                    };
                }
            }
            else if (data_type == 'vehicle')
            {
                var vehicle_search = $("#vehicle_search" + index).val();
                var vehicle_id = $("#vehicle_input_" + index).val();
                var register_no = $("#search_register_no" + index).val();
                if (typeof vehicle_id == "undefined")
                {
                    vehicle_id = $("#vehicle_id" + index).val();
                }
                post_url = '{{ route("backend.ajax.search_vehicle") }}?page=' + page;
                post_data = {
                    'index':index,
                    'vehicle_search': vehicle_search,
                    'vehicle_id': vehicle_id,
                    'register_no':register_no,
                    'search_type': search_type,
                    _token: '{{ csrf_token() }}',
                };
            } else if(data_type == 'work_code'){
                var process_type = $("#current_process_type" + index).val();
                var vehicle_size = $("#vehicle_size" + index).val();
                var vehicle_shape = $("#vehicle_shape" + index).val();
                var process_mount_id = $("#process_mount_id" + index).val();
                var work_id = $("#work_code_id" + index).val();
                var refrigerator_maker = $("#refrigerator_maker" + index).val();
                var refrigerator_type = $("#refrigerator_type" + index).val();
                var process_electric_id = $("#process_electric_id" + index).val();
                var paint_kind = $("#paint_kind" + index).val();
                var paint_body_kind = $("#paint_body_kind" + index).val();
                var paint_skin_kind = $("#paint_skin_kind" + index).val();
                var paint_body_length = $("#paint_body_length" + index).val();
                var cab_vehicle_class = $("#cab_vehicle_class" + index).val();
                var cab_vehicle_size = $("#cab_vehicle_size" + index).val();
                var cab_length = $("#cab_length" + index).val();
                var cab_kind = $("#cab_kind" + index).val();
                var engine_type = $("#engine_type" + index).val();
                var vehicle_type = $("#vehicle_type" + index).val();
                var maker_id = $("#maker_id" + index).val();
                var vehicle_name = $("#vehicle_name" + index).val();
                var work_name = $("#work_name" + index).val();
                var work_set_name = $("#work_set_name" + index).val();
                var work_set = $("#work_set" + index).val();
                var work_code = $("#work_code_search" + index).val();
                var paint_color_kind = $("#paint_color_kind" + index).val();
                post_url = '{{ route("backend.ajax.search_work_code") }}?page=' + page;
                post_data = {
                    'process_type': process_type,
                    'vehicle_size':vehicle_size,
                    'vehicle_shape':vehicle_shape,
                    'process_mount_id':process_mount_id,
                    'refrigerator_maker':refrigerator_maker,
                    'refrigerator_type':refrigerator_type,
                    'process_electric_id':process_electric_id,
                    'paint_kind':paint_kind ,
                    'paint_body_kind':paint_body_kind ,
                    'paint_skin_kind':paint_skin_kind ,
                    'paint_body_length':paint_body_length ,
                    'cab_vehicle_class':cab_vehicle_class ,
                    'cab_vehicle_size':cab_vehicle_size ,
                    'cab_length':cab_length ,
                    'cab_kind':cab_kind ,
                    'paint_color_kind':paint_color_kind ,
                    'engine_type':engine_type,
                    'vehicle_type':vehicle_type,
                    'maker_id':maker_id,
                    'vehicle_name':vehicle_name,
                    'work_set':work_set,
                    'work_name':work_name,
                    'work_set_name':work_set_name,
                    'work_id': work_id,
                    'index':index,
                    'work_code':work_code,
                    _token: '{{ csrf_token() }}',
                };
            }
            $.ajax({
                url: post_url,
                type: 'post',
                datatype: 'html',
                data : post_data,
            }).done(function(data){
                if (data_type == 'partner')
                {
                    if (data_part == '')
                        $("#partner_table_section" + index).empty().html(data);
                    else
                        $("#partner_table_section2").empty().html(data);
                }
                else if (data_type == 'vehicle')
                {
                    $('#vehicle_table_section' + index).empty().html(data);
                } else if(data_type == 'work_code'){
                    $('#work_code_table_section_' + index).empty().html(data);
                }
            }).fail(function(jqXHR, ajaxOptions, thrownError){
                console.log(thrownError);
            });
        }
    </script>

@endif
