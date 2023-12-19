
 @if($warrenty_status == 'P')
   @php ($status_class ='war_progress')
   @php ($title ='IN PROGRESS')
 @elseif($warrenty_status == 'A')
   @php ($status_class ='war_approved')
   @php ($title ='APPROVED')
 @elseif($warrenty_status == 'C')
   @php ($status_class ='war_closed')
   @php ($title ='CLOSED')
 
 @endif
 <span class="badge mb-0 fw-bold {{ $status_class }}">{{ $title }}</span>

 