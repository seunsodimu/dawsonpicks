<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <title>My Organization Chart</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/orgchart') ?>/css/jquery.orgchart.css">
  <link rel="stylesheet" href="<?= base_url('assets/orgchart') ?>/css/style.css">
  <style type="text/css">
    #chart-container { height:  950px; }
    .orgchart { background: white; }
  </style>
</head>
<body>
  <div id="chart-container"></div>

  <script type="text/javascript" src="<?= base_url('assets/orgchart') ?>/js/jquery.min.js"></script>
  <script type="text/javascript" src="<?= base_url('assets/orgchart') ?>/js/maketree.js"></script>
  <script type="text/javascript" src="<?= base_url('assets/orgchart') ?>/js/jquery.orgchart.js"></script>
  <script type="text/javascript">
    var orgJson = [];
    var sortedJson = [];
    var i = 0;
    var datasource = {};
    var oc;
    $(function() {
	var json_url = '<?= base_url('api/updateorgchart') ?>';
    $.getJSON(json_url, function(orgData){
      for (i=0; i<orgData.length; i++){
        orgJson.push({ id: orgData[i][0].employee_id, name: orgData[i][0].name, title: orgData[i][0].role, parent: orgData[i][0].supervisor_id});
        if (orgJson[i].parent.length == 0){
          console.log(i);
          orgJson[i].parent = "0000";
        }
      }
      sortedJson = getSortedJson(orgJson, "0000");
      
      datasource = sortedJson[0];
      console.log(datasource);
      drawChart(datasource);
    });

    

    $(window).resize(function() {
      var width = $(window).width();
      if(width > 1024) {
        oc.init({'verticalLevel': undefined});
      } else {
        oc.init({'verticalLevel': 2});
      }
    });
    
  });

  function drawChart(datasource) {
    oc = $('#chart-container').orgchart({
      'data' : datasource,
      'nodeContent': 'title',
      'visibleLevel': 3,
    });
  }
  </script>
  </body>
</html>