<x-workshop-layout>
<div class="container">
      <div class="row">
      @include('workshop.workshopmenu') 
        
        <div class="col-lg-9">
          <div class="padding_sec">
            <div class="row">
              <div class="col-lg-9 col-md-8 col-12">
                <h1>Mechanics List</h1>
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>
              </div>
              <div class="col-lg-3 col-md-4 col-12">
                <div class="text-md-end">
                  <a href="{{ route('workshop.add-michanics'); }}" class="main_btn">add new</a>
                </div>  
              </div>
            </div>
            
            <div class="dashboard_table mt-4">
              <div class="table-responsive">
                  <table id="redeem_list" class="db_tables table table-striped" style="width:100%">
                    <thead>
                        <tr>
                          <th scope="col">NAME</th>
                          <th scope="col">NAME</th>
                          <th scope="col">EMAIL</th>
                          <th scope="col">PHONE</th>
                          <th scope="col">EDIT</th>
                          <th scope="col">STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                      </tbody>
                  </table>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
    <x-slot name="scripts">
        <script src="{{ asset('public/back/js/plugin/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatables/dataTables.colVis.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatables/dataTables.tableTools.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatables/dataTables.bootstrap.min.js') }}"></script>
        <script src="{{ asset('public/back/js/plugin/datatable-responsive/datatables.responsive.min.js') }}"></script>
        <script type="text/javascript">
            $(function() {

                var table = $('#redeem_list').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: "{{ route('workshop.michanics-list') }}",
                    order: [ 1, 'asc' ],
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'id'
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'email',
                            name: 'email'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: 'edit',
                            name: 'edit',
                            "className": "text-center",
                            orderable: false,
                            searchable: false
                        },
                        {
                            data: 'activation',
                            name: 'activation',
                            "className": "text-center",
                            orderable: false,
                            searchable: false
                        },
                    ]
                });

            });
        </script>
    </x-slot>
</x-workshop-layout>