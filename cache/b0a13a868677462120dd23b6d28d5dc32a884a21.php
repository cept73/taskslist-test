<?php $__env->startSection('title', 'Table'); ?>

<?php $__env->startSection('js'); ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
<script>
<?php if($user['logged']): ?>

function onInitpage(table)
{
    $('tr', table).on('click', function() {
        
        if ($(this).hasClass('selected')) {
            // Remove selected
            $(this).removeClass('selected');
            tableSelect(null);
        }
        else {
            // Remove old
            $('tr.selected', table).removeClass('selected');
            // Set new
            if (tableSelect($(this)))
                $(this).addClass('selected');
        }
    });
}

<?php else: ?>

function onInitpage(table)
{

}

<?php endif; ?>
</script>
<script src="public/assets/js/homepage.js"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

    <!-- DataTale -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Tasks list</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
            <table class="table table-bordered" id="tasks_list" width="100%" cellspacing="0">
                <thead>
                <tr>
                    <th class="column-mini">#</th>
                    <th class="w-25">Title</th>
                    <th class="w-75">Task</th>
                    <th><nobr>E-mail</nobr></th>
                    <th class="column-mini"><i class="fas fa-check-circle" title="Completed"></i></th>
                    <th class="column-mini"><i class="fas fa-highlighter" title="Admin edited"></i></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            </div>
        </div>
    </div>


    <!-- Add task -->
    <div id="addTask" class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary" id="addTask__WindowCaption">Add task</h6>
        </div>
        <div class="card-body">
            <?php if(isset($addFormError)): ?> <div class="alert alert-danger text-center" role="alert">
                <?php echo e($addFormError); ?></div> <?php endif; ?>
            <?php if(isset($addFormSuccess)): ?> <div class="alert alert-success text-center" role="alert">
                <?php echo e($addFormSuccess); ?></div> <?php endif; ?>

            <form id="add-task" action="/" method="POST">
                <input type="hidden" name="path" value="task">
                <input type="hidden" name="id" value="">

                <div class="form-group row">
                    <div class="col-sm-9 mb-3 mb-sm-0">
                        <input type="text" class="taskName form-control form-control-email required w-100" 
                            id="formTaskName" name="taskName"
                            value="<?php if(isset($taskName)): ?><?php echo e($taskName); ?><?php endif; ?>"
                            placeholder="Task name"
                            tabindex="1">
                    </div>
                    <div class="form-check col-sm-3 text-right">
                        <?php if($user['logged']): ?>
                        <input type="checkbox" class="taskCompleted form-check-input"
                            id="formTaskCompleted" name="taskCompleted" onclick=false
                            <?php if(isset($taskCompleted) and $taskCompleted): ?> checkbox <?php endif; ?> 
                            title="Completed"
                            tabindex="4">
                        <label class="form-check-label d-sm-none d-md-inline"
                            for="formTaskCompleted">Completed</label>
                        <?php endif; ?>
                    </div>

                </div>
                <div class="form-group">
                    <textarea class="taskText form-control required w-100" rows="5"
                        id="formTaskText" name="taskText"
                        placeholder="Describe task..."
                        tabindex="2"><?php if(isset($taskText)): ?><?php echo e($taskText); ?><?php endif; ?></textarea>
                </div>
                <div class="form-group row">
                    <div class="col-sm-8 mb-3 mb-sm-0">
                        <input type="email" class="taskEmail form-control form-control-email required w-100"
                            id="formTaskEmail" name="taskEmail"
                            value="<?php if(isset($taskEmail)): ?> <?php echo e($taskEmail); ?> <?php endif; ?>" 
                            placeholder="E-mail"
                            tabindex="3">
                    </div>
                    <div class="col-sm-4 text-right">
                        <input type="submit" class="btn btn-primary" value="Add task"
                            id="addTask__Submit"
                            tabindex="5">
                    </div>
                </div>
            </form>

        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/views/homepage.blade.php ENDPATH**/ ?>