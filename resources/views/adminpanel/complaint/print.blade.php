
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Letter - Print</title>
        <style type="text/css">

            body {
                background-color: #fff;

                font: 9px/20px normal Helvetica, Arial, sans-serif;
                color: #4F5155;
                height: 842px;
                width: 595px;
                /* to centre page on screen*/
                margin-top: 30px;
                margin-left: auto;
                margin-right: auto;
                /* width: 210mm;
                height: 297mm; */
            }

            td {
                margin-right: 5% !important;
                margin-left: 10% !important;
            }

            .container{
                margin-bottom: 10px;
                height:auto;
                float:left;
                width:100%;
            }

            .containerHeading{
                margin-top: 8px;
                margin-bottom: 8px;
                height:auto;
                float:left;
                width:100%;
            }

            .columA{
                float:left;
                width: 30%;
            }

            .columB{
                float:right;
                width: 70%;
            }

            .columAH{
                float:left;
                width: 33.3%;
                text-align:left;
            }

            .columBH{
                float:left;
                width: 33.3%;
                text-align:left;
            }

            .columCH{
                float:left;
                width: 33.3%;
                text-align:left;
            }

            .columEH{
                float:left;
                width: 30%;
                text-align:center;
            }

            .columFH{
                float:right;
                width: 30%;
                text-align:center;
            }

            .columTA{
                float:left;
                width: 40%;
            }

            .columTB{
                float:right;
                width: 60%;
            }

            .columTC{
                display: block;
                margin-bottom: 15px;
                line-height: 19px;
                font-weight: 400;
                font-size: 20px;
                border-bottom: 1px solid #000000;
                border-top:1px solid #000000;
            }

            .columTD{
                display: block;
                margin-bottom: 15px;
                line-height: 19px;
                font-weight: 400;
                float:left;
            }
            .columTE{
                display: block;
                margin-bottom: 15px;
                line-height: 19px;
                font-weight: 400;
            }
            .containertopic{
                margin-top: 8px;
                margin-bottom: 8px;
                height:auto;
                float:left;
                width:100%;
                text-align:center;
            }

            .table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
                border: 1px solid #ddd;
                    padding: 5px;

            }

            .table>tbody>tr.active {
                background-color: #f5f5f5;

            }

            table {
                display: table;
                border-collapse: separate;
                border-spacing: 0px !important;
                border-color: grey;
            }

            div.header {
                display: block;
                text-align: center;
                /* position: running(header); */
            }
            div.footer {
                display: block;
                text-align: center;
                /* position: running(footer); */
            }

            @page {
                @top-center { content: element(header) }
            }
            @page {
                @bottom-center { content: element(footer) }
            }

            /* footer {
                font-size: 9px;
                color: #f00;
                text-align: center;
            } */

            /* @page {
                size: A4;
                margin: 11mm 17mm 17mm 17mm;
            } */

            @media print {
                footer {
                    position:absolute;
                    bottom: 0;
                    margin-top: 3% !important;
                }
                @page {
                    size: A4; /* DIN A4 standard, Europe */
                    margin: 11mm 20mm 17mm 17mm;
                }

                /* body {
                    padding-bottom: 2.5cm;
                } */

                .message{
                    margin-bottom: 50px;
                }

                thead {display: table-header-group;}
                tfoot {
                    display: table-footer-group;
                    /* padding-top: 100px !important; */
                }
            }

            .page-header, .page-header-space {
                /* height: 20px; */
            }

            .page-footer, .page-footer-space {
                /* height: 100px; */
                margin-top: 15% !important;
                margin-bottom: 15% !important;
            }

        </style>
    </head>
    <script type="text/javascript">

        function printDiv(print) {
            var printContents = document.getElementById(print).innerHTML;
            var originalContents = document.body.innerHTML;

            document.body.innerHTML = printContents;

            window.print();

            document.body.innerHTML = originalContents;
        }
    </script>

    <table>
        <thead>
            <header>
                <div class="header"><span id="logo"> <img src="{{ asset('storage/app/'.$labourofficedetails->letter_head) }}" alt="CMS" style="margin-bottom: 5px; display: block; margin-left: auto; margin-right: auto;height: 145px;
    width: 594px;"> </span></div>
            </header>
        </thead>

        <tbody>
            <body>

                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

                <html xmlns="http://www.w3.org/1999/xhtml">
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                        <title> Letter Prints</title>
                    </head>

                    <script type="text/javascript">
                        function printDiv(print) {
                            var printContents = document.getElementById(print).innerHTML;
                            var originalContents = document.body.innerHTML;

                            document.body.innerHTML = printContents;

                            window.print();

                            document.body.innerHTML = originalContents;
                        }

                    </script>

                    <body onload="printDiv('powerwidgets')">

                        <div class="row" id="powerwidgets">

                            {{-- <hr /> --}}

                        <div class="col-md-12">
                        </div>
                    </body>


                </html>

                <div class="col-md-12">
                    <table>
                        <tr style="width: 100%;">
                            <td style="width: 15%;">
                                <label class="label">මගේ අංකය</label><br/>
                                <label class="label">எனது இலக்க</label><br/>
                                <label class="label">My Number</label>
                            </td>
                            <td style="font-size: 45px !important; width: 5%;">}</td>
                            <td style="width: 10%;">{{ $complainantdetails->ref_no }}</td>
                            <td style="width: 15%;">
                                <label class="label">ඔබේ අංකය</label><br/>
                                <label class="label">உமது இலக்கம்</label><br/>
                                <label class="label">Your Number</label>
                            </td>
                            <td style="font-size: 45px !important; width: 5%">}</td>
                            <td style="width: 10%;">{{ $complainantdetails->external_ref_no }}</td>
                            <td style="width: 10%;">
                                <section class="columCH">
                                    <label class="label">දිනය</label><br/>
                                    <label class="label">திகதி</label><br/>
                                    <label class="label">Date</label><br/>
                                </section>
                            </td>
                            <td style="font-size: 45px !important; width: 5%;">}</td>
                            <td style="width: 10%;">{{ $todayDate }}</td>
                        </tr>
                    </table>
                    {{-- <section class="columAH" >
                        <label class="label">මගේ අංකය</label><br/>
                        <label class="label">எனது இலக்க</label><br/>
                        <label class="label">My Number</label><br/>
                    </section> --}}
                    {{-- <section class="columBH">
                        <label class="label">{{ $complainantdetails->ref_no }}</label>
                    </section> --}}
                    {{-- <section class="columBH">
                        <label class="label">ඔබේ අංකය</label><br/>
                        <label class="label">உமது இலக்கம்</label><br/>
                        <label class="label">Your Number</label><br/>
                    </section> --}}
                    {{-- <section class="columBH">
                        <label class="label">{{ $complainantdetails->external_ref_no }}</label>
                    </section> --}}
                    {{-- <section class="columCH">
                        <label class="label">දිනය</label><br/>
                        <label class="label">திகதி</label><br/>
                        <label class="label">Date</label><br/>
                    </section>
                    <section class="columBH">
                        <label class="label">{{ $todayDate }}</label>
                    </section> --}}
                </div>

                <table>

                    <thead>
                      <tr>
                        <td>
                          <!--place holder for the fixed-position header-->
                          <div class="page-header-space"></div>
                        </td>
                      </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                <div class="row" id="powerwidgets">
                                    <section class="col col-4 message">
                                            <img src="" alt="" style="width: 592px; display: block; margin-left: auto; margin-right: auto;">
                                            @foreach($mailhistories as $mail)
                                            {{-- <p style="text-align: center;">{!! $mail->subject !!}</p> --}}
                                            {{-- <br>
                                            {!! $mail->sent_to !!} --}}
                                            {{-- <br> --}}
                                            {!! $mail->mail_body !!}
                                            @endforeach
                                    </section>
                                </div>
                            </td>
                        </tr>
                    </tbody>

                    <tfoot>
                        <tr>
                          <td>
                            <!--place holder for the fixed-position footer-->
                            <div class="page-footer-space"></div>
                          </td>
                        </tr>
                      </tfoot>
                </table>
            </body>
        </tbody>
        <tfoot >
            <footer>
                {{-- <span id="logo"> <img src="../../storage/app/{{ $labourofficedetails->letter_head }}" alt="CMS" style="margin-bottom: 5px; display: block; margin-left: auto; margin-right: auto;"> </span> --}}
                <hr>
                <div style="text-align: center; line-height: 1px;">
                    <p>{{ $labourofficedetails->address_sin }}</p>
                    <p>{{ $labourofficedetails->address_tam }}</p>
                    <p>{{ $labourofficedetails->address }}</p>
                </div>
                <div>
                    <table>
                        <tr style="width: 100%;">
                            <td style="line-height: 15px; ">
                                <label class="label">දුරකථන</label><br/>
                                <label class="label">தொலைபேசி</label><br/>
                                <label class="label">Telephone</label>
                            </td>
                            <td style="font-size: 30px !important;">}</td>
                            <td style="width: 10%;"><p>{{ $labourofficedetails->tel }}</p></td>
                            <td style="line-height: 15px; ">
                                <label class="label">ෆැක්ස්</label><br/>
                                <label class="label">தொலைநகல்</label><br/>
                                <label class="label">Fax</label>
                            </td>
                            <td style="font-size: 30px !important;">}</td>
                            <td style="width: 10%;"><p>{{ $labourofficedetails->fax }}</p></td>
                            <td style="line-height: 15px; ">
                                <section class="columCH">
                                    <label class="label">ඊ&nbsp;මේල්</label><br/>
                                    <label class="label">மின்னஞ்சல்</label><br/>
                                    <label class="label">Email</label><br/>
                                </section>
                            </td>
                            <td style="font-size: 30px !important;">}</td>
                            <td style="width: 10%;"><p>{{ $labourofficedetails->email }}</p></td>
                            <td style="line-height: 15px; ">
                                <section class="columCH">
                                    <label class="label">වෙබ්&nbsp;අඩවිය</label><br/>
                                    <label class="label">இணையதளம்</label><br/>
                                    <label class="label">Website</label><br/>
                                </section>
                            </td>
                            <td style="font-size: 30px !important; ">}</td>
                            <td style="width: 10%;"><p>www.labourdept.gov.lk</p></td>
                        </tr>
                    </table>
                </div>
            </footer>
        </tfoot>
    </table>



</html>
