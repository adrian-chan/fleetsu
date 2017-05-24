(function ($) {

    var Device = {

        url : 'device-status',

        fetch: function () {
            return $.ajax({
                type: 'GET',
                url: this.url,
                dataType: 'json'
            });
        },

        getResults : function () {

            var html;

            this.fetch().done(function (result) {

                $.each(result, function (i, row) {

                    var statusColor = (row["Status"] == "OFFLINE") ? 'red' : 'green';

                    html += "<tr><td>" + row["Device ID"] + "</td>" +
                        "<td>" +  row["Device Label"] + "</td>" +
                        "<td>" +  row["Last Reported DateTime"] + "</td>" +
                        "<td class=" + statusColor +">" + row["Status"] + "</td></tr>";

                    console.log(row["Device ID"] + row["Device Label"] + row["Last Reported DateTime"] + row["Status"]);
                });

                $("#device-table tbody").append(html);

            });

        }
    }

    $(document).ready(function() {

        Device.getResults();
        
    });
})(jQuery);