 @can('original-equipment-edit')  
 @if($status == '1')
   @php ($status_class ='fa fa-check')
   @php ($title ='Click to inactivate')
 
 @else
   @php ($status_class ='fa fa-remove')
   @php ($title ='Click to activate')
 
 @endif
 <a href="{{ route('status-original-equipment-for-vehicles',encrypt($id)) }}"><i class="{{ $status_class }}" title="{{$title}}"></i></a>
 @endcan
 