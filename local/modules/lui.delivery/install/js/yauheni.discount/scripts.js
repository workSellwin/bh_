$(document).ready(function(){
    var url = $('#visual_discounts').attr('action');
    $(".checkbox-change-js").change(function() {
        var input_name = $(this).attr('name');
        var data_elem_id = $(this).attr('data-elem-id');
        var status_checked = '';
        if(this.checked) {
            status_checked = 'change';
            ajax_discount(url, input_name, data_elem_id, status_checked);
        }else{
            status_checked = 'no_change';
            ajax_discount(url, input_name, data_elem_id, status_checked);
        }
    });

    $('.SELECT_USER_GROUPS').change(function() {
        var ar_elem_id = $(this).attr('data-select-id');
        var ar_option_id = [];
        var name = $('li#'+ar_elem_id).attr('data-name');
        var lid = $('li#'+ar_elem_id).attr('data-lid');
        $(this).find('option:selected').each(function(){
            ar_option_id.push($(this).val());
        });
        if(ar_option_id.length != 0){
            ajax_select_discount(ar_elem_id, ar_option_id,  url, name, lid);
        }
    });
});

function ajax_discount(url, input_name, data_elem_id, status_checked) {
    $.post(
        url,
        {
            input_name: input_name,
            data_elem_id: data_elem_id,
            status_checked: status_checked,
            action_checked_ajax: 'Y'
        },
        onAjaxSuccess
    );
    function onAjaxSuccess(data)
    {
        console.log(data);
    }
}

function ajax_select_discount(ar_elem_id, ar_option_id, url, name, lid) {
    $.post(
        url,
        {
            ar_elem_id: ar_elem_id,
            ar_option_id: ar_option_id,
            name: name,
            lid: lid,
            ajax_select_discount: 'Y'
        },
        onAjaxSuccess
    );
    function onAjaxSuccess(data)
    {
        console.log(data);
    }
}

