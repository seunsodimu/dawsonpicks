<html>
    <head>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" type="text/css" /> 
        <link href="https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet"> 
		<style>
            .google-visualization-orgchart-node{
    min-width: 100px !important;
    background-color: #000000 !important;
}
.google-visualization-orgchart-connrow-medium
{
   height: 50px !important; 
}
#chart_div{
    margin: 50px;
    position: relative;
    width: 1200px !important;
    margin-top: 80px;
}
.zoombar, .infodiv{
    position: fixed;
}
			
			.panel{
  width: 200px;
  height: 200px;
}

        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-4 zoombar pull-left" style="z-index: 988888">
                        <div class="zoom-tool-bar"></div>
                    </div>
                    
                    <div class="col-md-8 infodiv"><p align="center">Click boxes to expand</p></div>
                 
					<div id="chart_div" class="draggable"></div>
					
                </div>
            </div>
        </div>
        

		<script src="https://code.jquery.com/jquery-1.10.2.js"></script> 
		<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>  
       <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script> 
        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">

		
google.charts.load("current", {packages:["orgchart"]});
google.setOnLoadCallback(drawChart1);
function drawChart1() {

    var data = new google.visualization.DataTable();
    data.addColumn('string', 'Name');
    data.addColumn('string', 'Manager');
    data.addColumn('string', 'ToolTip');
        // For each orgchart box, provide the name, manager, and tooltip to show.
        data.addRows(<?= $article['content'] ?>);

   var container = document.getElementById('chart_div');
    var chart = new google.visualization.OrgChart(container);

    container.addEventListener('click', function (e) {
      e.preventDefault();
      if (e.target.tagName.toUpperCase() === 'A') {
        console.log(e.target.href);
        // window.open(e.target.href, '_blank');
        // or
        // location.href = e.target.href;
      } else {
        var selection = chart.getSelection();
        if (selection.length > 0) {
          var row = selection[0].row;
          var collapse = (chart.getCollapsedNodes().indexOf(row) == -1);
          chart.collapse(row, collapse);
        }
      }
      chart.setSelection([]);
    
      return false;
    }, false);
        // Draw the chart, setting the allowHtml option to true for the tooltips.
           chart.draw(data, {'allowHtml':true, 'allowCollapse':true});
   
  window.scrollTo(2400, 0);
}



    

   </script>
   
   
  
    <script src="<?= base_url('assets/js/content-zoom-slider.min.js') ?>"></script>
    <script>
      $(function () {

          $("#chart_div").contentZoomSlider({
             toolContainer: ".zoom-tool-bar",
             step: 10,
             zoom: 100,
             max:500
           });
      });
		
		
  $(function() {  
    $( ".draggable" ).draggable();  
  });  
    </script>
    </body>
</html>                      
   
  