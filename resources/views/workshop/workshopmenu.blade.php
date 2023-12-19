 <?php
if( Route::currentRouteName() =='workshop.dashboard' )
{
  $aciveclass = 'item_active';
}else{
  $aciveclass = 'item_active';
}

 ?>
 <!-- mobile menu -->
 <div class="d-lg-none d-block">
        <div class="db_menu_btn">
          <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
            <i class="fa fa-bars" aria-hidden="true">&nbsp;&nbsp;</i>Menu
          </button>
        </div>
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" style="background-color: #F1F1F1;">
          <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Menu</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
          </div>
          <div class="offcanvas-body">
            <div class="tab_buttons">
              <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingOne0">
                    <button class="accordion-button accordion_item_no_arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne0" aria-expanded="false" aria-controls="collapseOne0">
                      My Profile
                    </button>
                  </h2>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed accordion_item_no_arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                      Mechanic Management
                    </button>
                  </h2>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                      Training
                    </button>
                  </h2>
                  <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                      <div class="accordion" id="accordionExample2">
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingSub1">
                            <button class="accordion-button collapsed accordion_item_no_arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSub1" aria-expanded="false" aria-controls="collapseSub1">
                              Calendar
                            </button>
                          </h2>
                        </div>
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingSub2">
                            <button class="accordion-button collapsed accordion_item_no_arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSub2" aria-expanded="false" aria-controls="collapseSub2">
                              Upcoming Sessions
                            </button>
                          </h2>
                        </div>
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingSub3">
                            <button class="accordion-button collapsed accordion_item_no_arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSub3" aria-expanded="true" aria-controls="collapseSub3">
                              Your Sessions
                            </button>
                          </h2>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed accordion_item_no_arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                      Scanning Products for points
                    </button>
                  </h2>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingFour">
                    <a href="{{ route('workshop.exam_calendar'); }}" class="accordion-button collapsed accordion_item_no_arrow" > Testing module (exams)</a>
                  </h2>
                </div>
                <div class="accordion-item">
                  <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                      Product warranty details
                    </button>
                  </h2>
                  <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                      <div class="accordion" id="accordionExample3">
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingSub4">
                            <button class="accordion-button collapsed accordion_item_no_arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSub4" aria-expanded="false" aria-controls="collapseSub4">
                              Create new incident
                            </button>
                          </h2>
                        </div>
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="headingSub5">
                            <button class="accordion-button collapsed accordion_item_no_arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSub5" aria-expanded="false" aria-controls="collapseSub5">
                              Download Reports
                            </button>
                          </h2>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      <br>
  </div>
    <!-- end of mobile menu -->
        <!-- left menu -->
        <div class="col-lg-3 d-lg-block d-none">
          <div class="tab_buttons">
            <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne0">
                  <a href="{{ route('workshop.dashboard'); }}" class="accordion-button accordion_item_no_arrow @if( Route::currentRouteName() =='workshop.dashboard' ) item_active @endif" >Dashboard</a>
                
                </h2>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne0">
                  <a href="{{ route('workshop.profile'); }}" class="accordion-button accordion_item_no_arrow @if( Route::currentRouteName() =='workshop.profile' ) item_active @endif"  >My Profile</a>
                
                </h2>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                <a href="{{ route('workshop.michanics'); }}" class="accordion-button accordion_item_no_arrow @if( Route::currentRouteName() =='workshop.michanics' || Route::currentRouteName() =='workshop.add-michanics' ) item_active @endif" >Mechanic Management</a>
                 
                </h2>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                  <!-- <a href="{{ route('workshop.michanics'); }}" class="accordion-button collapsed">  Training</a> -->
                  <button class="accordion-button collapsed @if( Route::currentRouteName() =='workshop.session_calendar' ||  Route::currentRouteName() =='workshop.upcomming_sessions' || Route::currentRouteName() =='workshop.past_session' || Route::currentRouteName() =='workshop.exam_calendar') item_active @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                    Training
                  </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                    <div class="accordion" id="accordionExample2">
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSub1">
                          <a href="{{ route('workshop.session_calendar')}}" class="accordion-button collapsed accordion_item_no_arrow @if( Route::currentRouteName() =='workshop.session_calendar' ) item_active @endif" >Training Calendar</a>
                          
                        </h2>
                      </div>
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSub2">
                          <a href="{{ route('workshop.upcomming_sessions')}}" class="accordion-button collapsed accordion_item_no_arrow @if( Route::currentRouteName() =='workshop.upcomming_sessions' ) item_active @endif" >Upcomming Sessions</a>
                         
                        </h2>
                      </div>
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSub3">
                        <a href="{{ route('workshop.past_session')}}" class="accordion-button collapsed accordion_item_no_arrow @if( Route::currentRouteName() =='workshop.past_session' ) item_active @endif" >Your Previous Sessions</a> 
                        </h2>
                      </div>
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSub4">
                        <a href="{{ route('workshop.exam_calendar'); }}" class="accordion-button collapsed accordion_item_no_arrow @if( Route::currentRouteName() =='workshop.exam_calendar' ) item_active @endif" > Exams Calendar</a>
                        </h2>
                      </div>
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSub4">
                        <a href="{{ route('workshop.exam'); }}" class="accordion-button collapsed accordion_item_no_arrow @if( Route::currentRouteName() =='workshop.exam_calendar' ) item_active @endif" > Exam</a>
                        </h2>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                  <a href="{{ route('workshop.scan-for-points') }}" class="accordion-button collapsed accordion_item_no_arrow @if( Route::currentRouteName() =='workshop.scan-for-points' ) item_active @endif" >Scanning Products for points</a>
                  
                </h2>

              </div>
              <!-- <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                  <a href="{{ route('workshop.exam_calendar'); }}" class="accordion-button collapsed accordion_item_no_arrow @if( Route::currentRouteName() =='workshop.exam_calendar' ) item_active @endif" > Testing module (exams)</a>
                  
                </h2>
              </div> -->
              <div class="accordion-item">
                <h2 class="accordion-header" id="headingFive">
                  <button class="accordion-button collapsed @if( Route::currentRouteName() =='workshop.report-incedent' || Route::currentRouteName() =='workshop.incedents-list' ) item_active @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                    Product warranty details
                  </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                    <div class="accordion" id="accordionExample3">
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSub4">
                          <a href="{{ route('workshop.report-incedent'); }}" class="accordion-button collapsed accordion_item_no_arrow" >Create new incident</a>
                          <!-- <button class="accordion-button collapsed accordion_item_no_arrow" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSub4" aria-expanded="false" aria-controls="collapseSub4">
                            Create new incident
                          </button> -->
                        </h2>
                      </div>
                      <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSub5">
                        <a href="{{ route('workshop.incedents-list'); }}" class="accordion-button collapsed accordion_item_no_arrow" >incidents List</a>
                         
                        </h2>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- left menu -->