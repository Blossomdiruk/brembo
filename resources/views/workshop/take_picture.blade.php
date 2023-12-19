<x-workshop-layout>
<div class="container">
      <div class="row">
      @include('workshop.workshopmenu') 
        
        <div class="col-lg-9">
          <div class="padding_sec">
            <h1>scan for reward points</h1>
            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>
            <div class="row">
              <div class="offset-lg-2 col-lg-4 col-md-6 col-12">
                    <div id="my_camera"></div>
                        <br/>
                        
              </div>
              <div class="col-lg-4 col-md-6 col-12">
                    
              </div>
            </div>

          </div>
        </div>

        <div class="modal" tabindex="-1">
            <div class="modal-dialog">
            <div class="modal-content">
            <form action="" id="redeem_pointsfrom" method="POST">
            @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reward Points</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        <strong>Product : </strong><span id="product_p"></span><br>
                        <strong>Points : </strong><span id="product_point"></span>
                    </p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="qr_value" id="qr_value" />
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" id="redeem_btn" class="btn btn-primary">Redeem Points</button>
                </div>
                </form>
            </div>
        </div>
        </div>


      </div>

      

    </div>
    <x-slot name="scripts">
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/webcamjs/1.0.25/webcam.min.js"></script> -->
    <script src="{{ asset('public/js/html5-qrcode.min.js') }}" type="text/javascript"></script>

    <script language="JavaScript">
        $(document).on("click","#redeem_btn",function() {
      var formData = $("#redeem_pointsfrom").serialize();

      $.ajax({
        type: "POST",
        url: "{{ route('workshop.redeem-points'); }}",
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
        function onScanSuccess(decodedText, decodedResult) {
        // handle the scanned code as you like, for example:
            //alert("You Qr is : " + decodedText, decodedResult);
            const myArray = decodedText.split("|");
            $('#qr_value').val(btoa(decodedText));
            $('#product_p').html(myArray[1]);
            $('#product_point').html(myArray[2]);
            $(".modal").modal('show'); 
        
        }

function onScanFailure(error) {
  // handle scan failure, usually better to ignore and keep scanning.
  // for example:
  //console.warn(`Code scan error = ${error}`);
  //return false;
}

let html5QrcodeScanner = new Html5QrcodeScanner(
  "my_camera",
  { fps: 10, qrbox: {width: 250, height: 250} },
  /* verbose= */ false);
html5QrcodeScanner.render(onScanSuccess, onScanFailure);

        

    // $(document).ready(function(){
    //          Webcam.set({
    //             width: 320,
    //             height: 240,
    //             image_format: 'jpeg',
    //             jpeg_quality: 90
    //         });
    //         Webcam.attach( '#my_camera' );
    // });
  
    
    
    
    // function take_snapshot() {
    //     Webcam.snap( function(data_uri) {
    //         $(".image-tag").val(data_uri);
    //         document.getElementById('results').innerHTML = '<img src="'+data_uri+'"/>';
    //     } );
    // }
</script>

    </x-slot>
</x-workshop-layout>