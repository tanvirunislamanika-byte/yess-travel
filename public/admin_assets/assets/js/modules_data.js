$(function(){
    "use strict";

function update_status(id) {
    var current_status = $("#sts_" + id + " span").html();
    $.ajax({
        type: 'GET',
        url: '{{url("/admin")}}/data-status/' + id + '/' + current_status,
        data: {
            '_token': $('input[name=_token]').val(),
        },
        success: function(sts) {
            var class_label = 'success';
            if (sts != 'active')
                var class_label = 'warning';
            $("#sts_" + id).html('<span style="font-size:12px" class="btn btn-' + class_label + '">' + sts + '</span>');
        }
    });

}

$('#category').select2();
$('#status').select2();


$("#title").keyup(function(){
    var Text = $(this).val();
    Text = Text.toLowerCase();
    Text = Text.replace(/[^a-zA-Z0-9]+/g,'-');
    $("#slug").val(Text);        
});


});