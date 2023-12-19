<x-workshop-layout>
<div class="container">
      <div class="row">
      @include('workshop.workshopmenu') 
        
        <div class="col-lg-9">
          <div class="padding_sec">
            <h1>Upcomming sessions</h1>
            <p>Brembo produces high-tech brake calipers installed as original equipment in the leading car models in every category.</p>
            <!-- <h2 class="fw-bold">sessions HISTORY</h2> -->
            @if(!empty($data) && $data->count())
                @foreach($data as $key => $value)
            <div class="card p-3 mb-3" style="border-radius: 10px;">
              <p class="mb-1 fst-italic"><i class="fa fa-calendar-check-o" aria-hidden="true">&nbsp;&nbsp;</i>
              Date -
              <?php 
              $old_date_timestamp = strtotime($value->dStartDate);
              echo $new_date = date('d / m / Y', $old_date_timestamp);   

              //$dateTime = date('Y-m-d', $value->dStartDate); 
              
              ?></p>
              <h5>{{ $value->vName }}</h5>
              <p class="mb-0">{{ $value->tTrainning_description }}</p>
            </div>
            @endforeach
            @else
                <div class="card p-3 mb-3" style="border-radius: 10px;">
                    <h5>There are no data.</h5>
                </div>
            @endif

           

            
            <!-- Pagination Start -->
            <br>
            <nav aria-label="Page navigation example">
             
              {!! $data->links() !!}
              
            </nav>
            <!-- Pagination End -->

          </div>
        </div>
      </div>
    </div>
    </x-workshop-layout>