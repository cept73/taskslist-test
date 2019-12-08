
/* Selected row in the table */
var selectedTask = null;
// Table
var tasksList = null;


/* Render of checkboxes */
function renderCheckbox(data, type, row, column) {
    if (type === 'display') {
        let checkFlag = (row[column] == 1) ? ' checked' : '';
        return '<input type="checkbox" onclick="return false;" ' + checkFlag + '>';
    }

    if (type === 'sort') return row[column];

    return data;
}
    function renderCompletedCheckbox(data, type, row) {
        return renderCheckbox(data, type, row, 'completed')
    }
    function renderAdmineditCheckbox(data, type, row) {
        return renderCheckbox(data, type, row, 'admin_edit')
    }


function addTaskFormSetValues(el)
{
    $("#addTask input[name=id]").val(el[0]);
    $("#formTaskName").val(el[1])
    $("#formTaskText").val(el[2])
    $("#formTaskEmail").val(el[3])
    $("#formTaskCompleted").attr('checked', el[4])
    $("#addTask, #addTask div.card-header, #addTask__WindowCaption").css(el[5]);
    if (el[5].color == 'white')
        $("#addTask__WindowCaption").removeClass("text-primary");
    else
        $("#addTask__WindowCaption").addClass("text-primary");
    $("#addTask__WindowCaption").html(el[6]);
    $("#addTask__Submit").val(el[7]);
}

var tableSelectBackup = [];
function tableSelect(row)
{
    if (row == null) {
        if (tableSelectBackup.length != 5) return false;

        addTaskFormSetValues([
            '',
            tableSelectBackup[1],
            tableSelectBackup[2],
            tableSelectBackup[3],
            tableSelectBackup[4],
            { 'background': '', 'color': '' },
            'Add task',
            'Add task'
        ]);

        return true;
    }

    tableSelectBackup = [
        '',
        $("#formTaskName").val(),
        $("#formTaskText").val(),
        $("#formTaskEmail").val(),
        $("#formTaskComplete").val()
    ];

    let tdsInRow = $("td", row);
    addTaskFormSetValues([
        tdsInRow[0].innerHTML,
        tdsInRow[1].innerHTML,
        tdsInRow[2].innerHTML,
        tdsInRow[3].innerHTML,
        $('input', tdsInRow[4]).attr('checked') == 'checked',
        { 'background': 'cadetblue', 'color': 'white' },
        'Update task',
        'Update'
    ]);
    return true;
}


function hideAlert()
{
    $('div.alert').addClass('d-none');
}


function showAlert(type)
{
    hideAlert();

    if (type == 'error' || type == 'success')
        $("#alert-" + type).removeClass('d-none');
}


// Call the dataTables jQuery plugin
$(document).ready(function() {


    // DataTable init
    var table = $('#tasks_list');
    table.on('draw.dt', function() {
        onInitpage(table);
    });
    tasksList = table.DataTable({
        columns: [
            { data: 'id' },
            { data: 'task' },
            { data: 'text' },
            { data: 'email' },
            { data: 'completed', render: renderCompletedCheckbox },
            { data: 'admin_edit', render: renderAdmineditCheckbox }
        ],
        ajax: {
            url: '/tasks',
            dataSrc: 'table',
        },
        lengthMenu: [ 3, 5, 10 ],
    });


    // Validate fields in the form
    var formAdd = $('form#add-task');
    formAdd.validate();
    formAdd.ajaxForm({
        success: function(responseText, _, _, _) {
            var data = $.parseJSON(responseText);
            if (data.success) {
                // Show message
                $("#alert-success").html(data.message);
                showAlert('success');

                // Refresh table
                tasksList.ajax.reload()
            }
            else {
                // Show message
                $("#alert-error").html(data.message);
                showAlert('error');
            }
        }
    });

    // Hide alert if any
    $('input, textarea', formAdd).on('keydown', hideAlert);
    setTimeout(hideAlert, 5000);

    // Validate
    $.validator.addClassRules({
        'taskName': {
            required: true,
            minlength: 5
        }, 
        'taskText': {
            required: true,
            minlength: 5,
            maxlength: 250
        },
        'taskEmail': {
            required: true,
            email: true
        }
    });


});
