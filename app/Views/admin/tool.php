<!DOCTYPE html>
<html>
<head>
    <title>KPI Tool</title>
    <!-- Include the necessary CSS and JS files -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>KPI Tool</h2>
        <form id="kpiForm">
            <div class="form-group">
                <label for="interval">Interval:</label>
                <select class="form-control" id="interval" name="interval">
                    <option value="10">10 minutes</option>
                    <option value="15">15 minutes</option>
                    <option value="30">30 minutes</option>
                    <option value="45">45 minutes</option>
                    <option value="60">60 minutes</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" class="form-control" id="date" name="date">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <br>
        <table id="kpiTable" class="display" style="width:100%">
            <thead>
                <tr>
                    <th>Pickers</th>
                    <!-- Columns for time intervals will be dynamically generated -->
                </tr>
            </thead>
            <tbody>
                <!-- Rows will be dynamically generated -->
            </tbody>
            <tfoot>
                <tr>
                    <th>Total</th>
                    <!-- Footer cells for each time interval will be dynamically generated -->
                </tr>
            </tfoot>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            var table = $('#kpiTable').DataTable({
                searching: false,
                paging: false,
                ordering: false,
                info: false,
                columns: [
                    { data: 'picker' }, // Column for picker names will be dynamically generated
                    // Columns for time intervals will be dynamically generated
                ]
            });

            $('#kpiForm').on('submit', function (event) {
                event.preventDefault();
                var formData = $(this).serialize();

                $.ajax({
                    url: "kpi-data",
                    method: "GET",
                    data: formData,
                    dataType: "json",
                    success: function (data) {
                        // Clear the table
                        table.clear().draw();

                        // Add rows to the table
                        $.each(data, function (index, row) {
                            var rowData = [row.picker];
                            // Add cells for time intervals
                            // Calculate the start and end time based on the selected interval
                            var startTime = moment('00:00:00', 'HH:mm:ss');
                            var endTime = moment('23:59:59', 'HH:mm:ss');
                            var interval = $('#interval').val();
                            var increments = [10, 15, 30, 45, 60]; // Available time intervals

                           $.each(increments, function (i, inc) {
                                if (interval == inc) {
                                    endTime = moment('00:' + inc + ':00', 'HH:mm:ss');
                                    return false; // Break the loop
                                }
                            });

                            var currTime = moment(startTime);
                            while (currTime.isSameOrBefore(endTime)) {
                                var count = 0;
                                $.each(row.pick_count, function (i, pick) {
                                    var pickTime = moment(pick.start_time, 'HH:mm:ss');
                                    if (pickTime.isSame(currTime, 'minute')) {
                                        count = pick.pick_count;
                                        return false; // Break the loop
                                    }
                                });
                                rowData.push(count);
                                currTime.add(interval, 'minutes');
                            }

                            // Add total for each row
                            rowData.push(row.total);

                            // Add the row to the table
                            table.row.add(rowData).draw();
                        });

                        // Add footer cells for each time interval
                        var footerData = ['Total'];
                        var currTime = moment('00:00:00', 'HH:mm:ss');
                        while (currTime.isSameOrBefore(endTime)) {
                            var count = 0;
                            $.each(data, function (index, row) {
                                $.each(row.pick_count, function (i, pick) {
                                    var pickTime = moment(pick.start_time, 'HH:mm:ss');
                                    if (pickTime.isSame(currTime, 'minute')) {
                                        count += pick.pick_count;
                                        return false; // Break the loop
                                    }
                                });
                            });
                            footerData.push(count);
                            currTime.add(interval, 'minutes');
                        }

                        // Add the footer row to the table
                        table.row.add(footerData).draw();
                    }
                });
            });
        });
    </script>
</body>
</html>

                                   
