@can('establishment-delete')  
 @if($status == '1')
   @php ($status_class ='fa fa-ban')
 
 @else
   @php ($status_class ='fa fa-trash')
 
 @endif
   <button class="btn-delete" value="{{ $id }}"><i class="{{ $status_class }}"></i></button>
 @endcan
 