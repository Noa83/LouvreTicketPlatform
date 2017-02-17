
    $( "#form_visitDate" ).datepicker({
        minDate: 0,
        maxDate: "+1Y",
        beforeShowDay: function(date) {
            var utcDay = date.getUTCDay();
            var day = date.getDate();
            var month = date.getMonth();
            var premierMai = day === 1 && month === 4;
            var premierNov = day === 1 && month === 10;
            var noel = day === 25 && month === 11;

            var disableddates = ["27-03-2017"];
            var dateFormatted = jQuery.datepicker.formatDate('dd-mm-yy', date);

            if (utcDay === 1 || utcDay === 6 || premierMai || premierNov || noel || disableddates.indexOf(dateFormatted) !== -1) {
                return [false];
            } else {
                return [true];
            }
        } });
