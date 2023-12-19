 @can('role-edit')   
   <a href="{{ route('pending-approval-status-history',encrypt($id)) }}"><i class="fa fa-comments"></i></a> {{ $statusCount }}
 @endcan
 