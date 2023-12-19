 @can('business-nature-edit')
 @if($status == 'Y')
   @php ($status_class ='fa fa-check')

 @else
   @php ($status_class ='fa fa-remove')

 @endif
   <a href="{{ route('business-nature',encrypt($id)) }}"><i class="{{ $status_class }}"></i></a>
 @endcan
