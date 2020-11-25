
let selectedTask        = null;
let tasksList           = null;
let tableSelectBackup   = [];
let tdsInRow            = [];


/* Render of checkboxes */
function renderCheckbox(data, type, row, column)
{
    if (type === 'display') {
        let checkFlag = (row[column] === 1) ? ' checked' : '';
        return '<input type="checkbox" onclick="return false;" ' + checkFlag + '>';
    }

    if (type === 'sort') {
        return row[column];
    }

    return data;
}

function renderCompletedCheckbox(data, type, row)
{
    return renderCheckbox(data, type, row, 'completed');
}

function renderAdminEditCheckbox(data, type, row)
{
    return renderCheckbox(data, type, row, 'admin_edit');
}

function addTaskFormSetValues(el)
{
    $("#addTask input[name=id]").val(el[0]);
    $("#formTaskName").val(el[1]);
    $("#formTaskText").val(el[2]);
    $("#formTaskEmail").val(el[3]);
    $("#formTaskCompleted").attr('checked', el[4]);
    $("#addTask, #addTask div.card-header, #addTask__WindowCaption").css(el[5]);
    let addTaskWindowCaption = $("#addTask__WindowCaption");
    if (el[5].color === 'white') {
        addTaskWindowCaption.removeClass("text-primary");
    } else {
        addTaskWindowCaption.addClass("text-primary");
    }
    addTaskWindowCaption.html(el[6]);
    $("#addTask__Submit").val(el[7]);
}

function tableSelect(row)
{
    if (row === null) {
        if (tableSelectBackup.length !== 5) {
            return false;
        }

        addTaskFormSetValues([
            '',
            tableSelectBackup[1],
            tableSelectBackup[2],
            tableSelectBackup[3],
            tableSelectBackup[4],
            {
                'background': '',
                'color': ''
            },
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

    // Get cols
    tdsInRow = $("td", row);
    let el = tdsInRow[4];
    
    // Safe cast
    tdsInRow.each(function(k,v) {
        tdsInRow[k] = v && v.innerHTML ? v.innerHTML : '';
    });
    addTaskFormSetValues([
        tdsInRow[0],
        tdsInRow[1],
        tdsInRow[2],
        tdsInRow[3],
        $('input', el).attr('checked') === 'checked',
        {
            'background': 'cadetblue',
            'color': 'white'
        },
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

    if (type === 'error' || type === 'success') {
        $("#alert-" + type).removeClass('d-none');
    }
}

function showMessage(type, text)
{
    if (type !== 'error' && type !== 'success') {
        return false;
    }

    $("#alert-" + type).html(text);
    showAlert(type);
    return true;
}

function clearFormFields()
{
    $("#formTaskName, #formTaskText, #formTaskEmail").val('');
    $("#formTaskCompleted").prop('checked','')
}

// Call the dataTables jQuery plugin
$(document).ready(function() {
    // DataTable init
    let table = $('#tasks_list');

    table.on('draw.dt', function () {
        onInitPage(table);
    });

    tasksList = table.DataTable({
        columns: [
            { data: 'id' },
            { data: 'task' },
            { data: 'text' },
            { data: 'email' },
            { data: 'completed', render: renderCompletedCheckbox },
            { data: 'admin_edit', render: renderAdminEditCheckbox }
        ],
        ajax: {
            url: '/tasks',
            dataSrc: 'table',
        },
        lengthMenu: [3, 5, 10],
    });


    // Validate fields in the form
    let formAdd = $('form#add-task');
    formAdd.validate();
    formAdd.ajaxForm({
        success: function(responseText) {
            let data = $.parseJSON(responseText);
            if (data.success) {
                // Show message
                showMessage('success', data.message);

                // Clear fields
                clearFormFields();

                // Refresh table
                tasksList.ajax.reload()
            } else {
                // Show message
                showMessage('error', data.message);
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
