@include('admin.layouts.flash-message')
@isset($store)
    <input type="hidden" name="id" value="{{ $store->id }}">
@endisset

<table class="normal">  
    <tr>
        <th>消費税</th>
        <td>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
            <input type="text" name="tax_rate" class="input-text w100 text-right" value="{{ old('tax_rate', isset($configuration) && isset($configuration->tax_rate) ? $configuration->tax_rate : '') }}">&nbsp;%
            
        </td>
    </tr>

    <tr>
        <th>手数料</th> 
        
        <td>
            月額:&nbsp;&nbsp;
            <input type="text" name="fee_fix" class="input-text w100 text-right" value="{{ old('fee_fix', isset($configuration) && isset($configuration->fee_fix) ? $configuration->fee_fix : '' )}}">&nbsp;円
             
            &nbsp;&nbsp;+&nbsp;&nbsp;手数料:      &nbsp;      
            <input type="text" name="fee_percent" class="input-text w100 text-right" value="{{ old('fee_percent', isset($configuration) && isset($configuration->fee_percent) ? $configuration->fee_percent : '' )}}"> &nbsp;%
        </td>
 
    </tr>
 

</table>

@section('page_css')
    <link href="{{ asset('assets/vendor/dropzone/dist/basic.css') }}" rel="stylesheet"> 
    <link rel="stylesheet" href="{{ asset('assets/admin/css/pages/stores_form.css') }}"> 
   
@endsection


@section('page_js') 
<script> 
    $(document).ready(function () {
       
    }); 
</script>
 
@endsection


