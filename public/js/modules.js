const moduleFields = JSON.parse($('#module_fields').val() || '[]');
function getConditionalColumn() {
    const isTruthy = (val) => val && val !== 'no';
    return [
        ...(isTruthy($('#is_image').val()) ? [{ data: 'image', name: 'image' }] : []),
        { data: 'title', name: 'title' },
        ...(isTruthy($('#is_category').val()) ? [{ data: 'category', name: 'category' }] : []),
        ...(moduleFields.map(({ field }) => ({ data: field, name: field })) || [{ data: 'created_date', name: 'created_date' }]),
        { data: 'action', name: 'action', orderable: false, searchable: false }
    ];
}

const oTable = $('#table-modules-data').DataTable({
    processing: true,
    serverSide: true,
    stateSave: true,
    searching: false,
    "bPaginate": true,
    "ordering": false,
    "info": false,
    responsive: true,
    lengthChange: false,
    columns: getConditionalColumn(),
    ajax: {
        url: $('#modules_data_fetch').val(),
        data: (d) => {
            d.id = $('#module_id').val();
            d.title = $('input[name=title]').val();
            d.category = $('#category').val();
            d.status = $('#status').val();
            d.selectcontacts = $('#hiddenField').val();
            moduleFields.forEach(({ field }) => {
                d[field] = $('#' + field).val();
            });
        }
    }
});

moduleFields.forEach(({ field }) => {
    var fieldType = $(`#${field}`).prop("type");
    $(`#${field}`).on('input change', () => oTable.draw());
    if(fieldType == 'select'){
        $(`#${field}`).select2();
    }
    
});

$('#title, #category, #status').on('input change', () => oTable.draw());

const update_status = (id) => {
    const currentStatus = $("#sts_" + id + " .status-text").html();
    if (confirm("Are you sure you want to proceed?")) {
        $.get(`${$('#base_url').val()}/admin/data-status/${id}/${currentStatus}`, { '_token': $('input[name=_token]').val() }, (sts) => {
            const classLabel = sts == 'active' ? 'active-btn' : 'blocked-btn';
            const icon = sts == 'active' ? 'check-circle' : 'times-circle';
            const html = `<span class="${classLabel}"><i class="fas fa-${icon} icon"></i>&nbsp<span style="font-size: 12px;" class="status-text">${sts}</span></span>`;
            $("#sts_" + id).html(html);
        });
    }
};

$(document).on('click','#delete',function(event){
    !confirm("Are you sure you want to delete?") && event.preventDefault();
})


$('#category, #status').select2();

const readURL = (input) => {
    if (input.files && input.files[0]) {
        const reader = new FileReader();

        reader.onload = (e) => {
            $('.image-upload-wrap').hide();
            $('.file-upload-image').attr('src', e.target.result);
            $('.file-upload-content').show();
            $('.image-title').html(input.files[0].name);
        };

        reader.readAsDataURL(input.files[0]);
    } else {
        removeUpload();
    }
};

$(".tabledit-delete-button").click(() => confirm('Are you sure you want to delete?'));

const getConditionalValidations = () => [JSON.parse($('#required_json_arr').val()).map(value => ({ [value.field]: { 'required': value.required === 'yes' } }))];

const getConditionalValidationMessages = () => [JSON.parse($('#required_json_arr').val()).map(value => ({ [value.field]: { 'required': value.message } }))];
