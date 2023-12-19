<x-workshop-layout>
<?php
$training_array = array();
foreach($traning_session as $sessions)
{
  $training_array[] =  $sessions->dStartDate;
}


?>
<div class="container">
      <div class="row">
      @include('workshop.workshopmenu') 
        <div class="modal" tabindex="-1">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title">Enroll with <span id="session_name"></span></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              
              <form action="" id="enroll_from" method="POST">
              @csrf
              <div class="modal-body">
                <p id="body_p"><h6>Enrole with Training session.</h6></p>
                <p>
                    Start Date: <span id="start_d"></span>
                    
                </p>
                <p>
                End Date: <span id="end_d"></span>
                </p>
              </div>
              <div class="modal-footer">
                <input type="hidden" name="tsession_id" id="tsession_id" />
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" style="background-color:#E43038;" id="enroll_btn">Enroll Now</button>
              </div>
            </form>
            </div>
          </div>
        </div>
     
        


        <div class="col-lg-9">

        <div class="alert alert-danger alert-dismissible d-none"  role="alert">
          <strong>Already Enrolled to this session!</strong>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

          <div class="padding_sec">
            <h1>Session Calendar</h1>
            <div id='calendar'></div>
          </div>
        </div>
      </div>
    </div>
    <x-slot name="styles">
      <style>
     .fc-license-message{
        display: none !important;
      }
    </style>
    </x-slot>
<x-slot name="scripts">
<script src="{{ asset('public/full-calendar/index.global.js') }}"></script>


    <script>

      $(document).on("click","#enroll_btn",function() {
          var formData = $("#enroll_from").serialize();

          $.ajax({
            type: "POST",
            url: "{{ route('workshop.enroll_session'); }}",
            data: formData,
            success: function(response) {
              // Handle the successful response here
              $('.alert-danger').removeClass('show');
              $('.alert-danger').addClass('d-none');
              $('.modal').modal('hide');
            },
            error: function(xhr, status, error) {
              // Handle errors
              $('.alert-danger').removeClass('d-none');
              $('.alert-danger').addClass('show');
              $('.modal').modal('hide');
            }
          });
            
        });

      document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        const fnd_arr = [];
        var calendar = new FullCalendar.Calendar(calendarEl, {
          timeZone: 'UTC',
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            //right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
          },
          //initialDate: '2023-11-12',
          navLinks: false, // can click day/week names to navigate views
          nowIndicator: true,

          weekNumbers: true,
          weekNumberCalculation: 'ISO',

          editable: true,
          selectable: true,
          dayMaxEvents: true, // allow "more" link when too many events
          eventDidMount: function(info) {
      
        if(info['view']['type']=='timeGridDay')
        {

          $('.fc-event-title-container').html(info.event.title+"<br><b>Description:</b><br>" + info.event.extendedProps['description']);
          //console.log(info.event.title);
        }
        if(info['view']['type']=='listWeek')
        {
          $('.fc-list-event-title').html('<a>'+info.event.title+"<br><b>Description:</b><br>" + info.event.extendedProps['description'] + '<br><button type="button" class="btn btn-primary brown_btn" style="width: 70px; height: 30px; padding: 5px;">Export</button>');
        
        }
        
    
        },
          events: [
            
            <?php
              foreach($exam_sessions as $exam_sess){
                $dt = new DateTime($exam_sess->starting_date);
                
                $date = $dt->format('Y-m-d');
                $start_date = $exam_sess->dStartDate;
                $end_date = $exam_sess->dEndDate;
                if (in_array($start_date, $training_array)) { 
                      $event_color = "#33cc99";
                } else { 
                      $event_color = "#CC9933";
                } 
            ?>
            {
              id: '<?php echo $exam_sess->id; ?>',
              title: '<?php echo $exam_sess->vName; ?>',
              start: '<?php echo  $start_date; ?>',
              end: '<?php echo  $end_date; ?>',
              // description: 'Test1',
              color: '<?php echo $event_color; ?>'
            },
          <?php } ?>
          
          ],
          eventClick: function(info) {
            let date1 = new Date();
            let date2 = new Date(info.event.start);
            let end_date = new Date(info.event.end);
            console.log(date2);
            if (date1 < date2) {
                    $('#start_d').html(info.event.start);
                    $('#end_d').html(info.event.end);
                    $('#tsession_id').val(btoa(info.event.id));
                    $('#session_name').html(info.event.title);
                    $(".modal").modal('show');
            }

            info.el.style.borderColor = 'red';
    }
        
        });

        calendar.render();
      });

    </script>

</x-slot>
</x-workshop-layout>