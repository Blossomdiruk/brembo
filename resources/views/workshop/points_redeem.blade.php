<x-workshop-layout>
<div class="container">
      <div class="row">
      @include('workshop.workshopmenu') 
        
        <div class="col-lg-9">
          <div class="padding_sec">
            <h1>Points Redemption List</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>
            
            <div class="dashboard_table mt-4">
              <div class="table-responsive">
              <table id="redeem_list" class="db_tables table table-striped" style="width:100%">
                    <thead>
                        <tr>
                          <th scope="col">ID</th>
                          <th scope="col">PRODUCT NAME</th>
                          <th scope="col">PRODUCT DESCRIPTION</th>
                          <th scope="col">REDEEM DATE</th>
                          <th scope="col">POINTS</th>
                          
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
                    ajax: "{{ route('workshop.redeem-list') }}",
                    order: [ 1, 'asc' ],
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'id'
                        },
                        {
                            data: 'part_number',
                            name: 'name'
                        },
                        {
                            data: 'part_discription',
                            name: 'email'
                        },
                        {
                            data: 'created_at',
                            name: 'phone'
                        },
                        {
                            data: 'points',
                            name: 'phone'
                        },
                       
                    ]
                });

            });
        </script>

</x-slot>
</x-workshop-layout>