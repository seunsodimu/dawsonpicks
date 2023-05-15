<!DOCTYPE html>
<html lang="en">
<head>
  <title>Dawson KPI <?= !empty($title) ? "- ".$title : "" ?></title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link href="https://cdn.datatables.net/v/dt/dt-1.13.4/b-2.3.6/b-html5-2.3.6/cr-1.6.2/fc-4.2.2/fh-3.3.2/sp-2.1.2/datatables.min.css" rel="stylesheet"/>

  <style type="text/css">
    .num-count {
      background-color: green;
      color: #ffffff;
      font-weight: bold;
      text-align: center;
    }
    .zerocount{
      background-color: red;
      color: #FFFFFF;
    }
  </style>
</head>
<body>

   <?= $this->renderSection("body") ?>

 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script><script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/js/all.min.js" integrity="sha512-8pHNiqTlsrRjVD4A/3va++W1sMbUHwWxxRPWNyVlql3T+Hgfd81Qc6FC5WMXDC+tSauxxzp1tgiAvSKFu1qIlA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.27/build/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/v/dt/dt-1.13.4/b-2.3.6/b-html5-2.3.6/cr-1.6.2/fc-4.2.2/fh-3.3.2/sp-2.1.2/datatables.min.js"></script>
<script type="text/javascript" src="//cdn.datatables.net/plug-ins/1.10.19/sorting/time.js"></script>
<script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.4.0/js/buttons.print.min.js"></script>
  <script type="text/javascript">

$(document).ready(function() {

    $('#rowtbl1').DataTable( {
        iDisplayLength: 200,
        fixedColumns: {
            leftColumns: 1
        },
        scrollX: false,
        dom: "Bfrtip",
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );

    

    $('#rowtbl3').DataTable( {
        iDisplayLength: 200,
        scrollX: false,
        dom: "Bfrtip",
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        columnDefs: [
                         { type: 'time-uni', targets: 0 }
                    ],
        fixedHeader: true,
        fixedColumns: true
    } );

        $('#rowtbl2').DataTable( {
        iDisplayLength: 200,
        sPaginationType: "full_numbers",
        fixedColumns: {
            leftColumns: 1
        },
        scrollX: true,
        dom: "Bfrtip",
        order: [[0, 'desc']],
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    } );

    $("#upload-form").submit(function (e) {     
          $("#submit-btn").prop("disabled",true);
          $("#upldn-msg").show();
          $("#upload-form").submit();
    }); 

  var table = $('#rowtbl').DataTable( {
    "iDisplayLength": 200,
    "bDestroy": true,
    "bJQueryUI": true,
    "sPaginationType": "full_numbers",
    "bAutoWidth": false,
    
    // initComplete: function(settings, json) {
    //   // calculate the sum when table is first created:
    //   doSum();
    // }
  } );

  $('#rowtbl').on( 'draw.dt', function () {
    // re-calculate the sum whenever the table is re-displayed:
    doSum();
  } );

  // This provides the sum of all records:
  function doSum() {
    // get the DataTables API object:
    var table = $('#rowtbl').DataTable();
    // set up the initial (unsummed) data array for the footer row:
    var totals = ['Totals',0,0,0];
    // iterate all rows - use table.rows( {search: 'applied'} ).data()
    // if you want to sum only filtered (visible) rows:
    totals = table.rows( ).data()
      // sum the amounts:
      .reduce( function ( sum, record ) {
        for (let i = 1; i <=3 ; i++) {
          sum[i] = sum[i] + numberFromString(record[i]);
        } 
        return sum;
      }, totals ); 
    // place the sum in the relevant footer cell:
     for (let i = 1; i <= 3; i++) {
      var column = table.column( i );
      $( column.footer() ).html( numFormat(totals[i]));
    }
  }

  function numberFromString(s) {
    return typeof s === 'string' ?
      s.replace(/[\$,]/g, '') * 1 :
      typeof s === 'number' ?
      s : 0;
  }

function numFormat(n)
{
  return Math.round(n * 10) / 10;
}

$("#reset-btn-view").click(function(){
    document.getElementById('displayDate').valueAsDate = new Date();
    $("#displayform").find('input:text, input:password, input:file, select, textarea').val('');
    $("#displayform").find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
    console.log('show');
});
  



 $("#reportType").change(function(){
     var typ = this.value;
   if(typ=="Week"){
     $("#fromDate").show();
     $('#fromDate').prop('required', true);
     $("#fromMonth").hide();
     $("fromMonth"). removeAttr('required');
   }else{
     $("#fromMonth").show();
     $('#fromMonth').prop('required', true);
     $("#fromDate").hide();
     $("fromDate"). removeAttr('required');
   }
   
  });

  $('#btn-refresh').click(function() {
    $("#btn-refresh").attr("disabled", true);
    $("#btn-refresh").toggleClass('btn-primary btn-gray');
    $("#refresh-icon").addClass('fa-spin');
    $("#refresh-txt").text('Refreshing...');
    location.reload();
});

} );
</script>
</body>
</html>