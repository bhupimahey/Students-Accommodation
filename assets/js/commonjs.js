var fortnightAway = new Date(Date.now() + 12096e5);
$(".datepicker-vacating").datepicker({
    dateFormat: 'yy-mm-dd',minDate: fortnightAway
});
$(".datepicker").datepicker({
    dateFormat: 'yy-mm-dd'
});