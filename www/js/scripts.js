(function ($) {

    var Device = {

        url : 'device-status',

        fetch: function () {
            return $.ajax({
                type: 'GET',
                url: this.url,
                dataType: 'json',
                error: function (jxhr, t, thrown) {
                    confirm( window.location.hostname + '/' + this.url + ' is a 404 error');
                }
            });
        },

        getResults : function () {

            var html;

            this.fetch().done(function (result) {

                $.each(result, function (i, row) {

                    var statusColor = (row["Status"] == "OFFLINE") ? 'red' : 'green';

                    html += "<tr><td>" + row["Device ID"] + "</td>" +
                        "<td>" +  row["Device Label"] + "</td>" +
                        "<td>" +  convertToLocalTime(row["Last Reported DateTime"]) + "</td>" +
                        "<td class=" + statusColor +">" + row["Status"] + "</td></tr>";

                    //console.log(row["Device ID"] + row["Device Label"] + convertToLocalTime(row["Last Reported DateTime"]) + row["Status"]);
                });

                $("#device-table tbody").append(html);

            });
        }
    }

    function convertToLocalTime(time) {        
            return new Date(time);
    }

    $(document).ready(function() {

        Device.getResults();
        
    });
})(jQuery);