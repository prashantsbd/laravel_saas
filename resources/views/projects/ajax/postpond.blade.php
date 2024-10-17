<link rel="stylesheet" href="{{ asset('vendor/css/dropzone.min.css') }}">
<div class="row">
    <div class="col-sm-12">
        <x-form id="postpond-project-data-form">
            <div class="bg-white rounded add-client">
                <div class="p-20 row">
                    <div class="col-lg-4 col-md-4">
                        <x-forms.select fieldId="hold_for"
                        :fieldLabel="__('modules.projects.holdFor')"
                        fieldRequired="true"
                        fieldName="hold_for" search="true">
                            <option value="">--</option>
                            <option value="7">7 days</option>
                            <option value="15">15 days</option>
                            <option value="30">30 days</option>
                            <option value="custom">Custom</option>
                        </x-forms.select>
                    </div>

                    <div class="col-lg-4 col-md-4" id="custom_hold_interval" style="display:none;">
                        <x-forms.label class="my-3" fieldId="custom_hold_for"
                            :fieldLabel="__('modules.projects.customHoldFor')" fieldRequired="true"></x-forms.label>
                        <x-forms.input-group>
                            <x-slot name="append">
                                <span class="input-group-text f-14 bg-white-shade">
                                    days
                                </span>
                            </x-slot>

                            <input type="number" step="1" min="0" class="form-control height-35 f-14"
                                name="custom_hold_for" id="custom_hold_for" >
                        </x-forms.input-group>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <x-forms.select fieldId="privilege_task_id" multiple
                        :fieldLabel="__('modules.projects.privilegeTask')"
                        fieldName="privilege_task_id[]" search="true">
                            @foreach ($incompleteTasks as $incompleteTask)
                            <option value="{{ $incompleteTask->id }}">{{ $incompleteTask->heading }}</option>
                            @endforeach
                        </x-forms.select>
                    </div>
                    <div class="doing-task"></div>
                </div> 
            </div>
        </x-form>
    </div>
</div>

<script>
    var project = @json($project);
    $(document).ready(function () {
        $('#hold_for').on('change', function() {
            if($(this).val() == 'custom'){
                $('#custom_hold_interval').show()
            }else{
                $('#custom_hold_interval').hide();
            }
        })
        $('#privilege_task_id').on('change', loadDoingTasks);
        $('.doing-task').on('click', 'button', function() {
            var taskId = $(this).data('task-id');
            var status = $(this).hasClass('todo') ? 'to_do' : 'completed';
            var token = "{{ csrf_token() }}";
            var url = "{{ route('tasks.change_status') }}";
            
            if(taskId != "" && status != ""){
                $.easyAjax({
                    url: url,
                    type: "POST",
                    container: '#postpond-project-data-form',
                    blockUI: true,
                    data: {
                        _token: token,
                        taskId: taskId,
                        status: status,
                        postpond: true
                    },
                    success: function(response) {
                        loadDoingTasks();
                    }
                });
            }
        });
        loadDoingTasks();
    });
    const loadDoingTasks = () => {
        var url = "{{ route('projects.doingTasks', ':id') }}";
        url = url.replace(':id', project.id);

        var token = "{{ csrf_token() }}";
        var privilegeTasks = $('#privilege_task_id').val()

        $.easyAjax({
            url: url,
            type: 'GET',
            data: {
                _token: token,
                privilegeTasks: privilegeTasks
            },
            success: function(response) {
                // Handle the response here
                console.log(response);
                console.log(privilegeTasks, response.data.doing.length);
                var htmlContent = '';
                if(response.data.doing.length){
                    htmlContent += `doing tasks, mark as`
                }else{
                    htmlContent += `No any doing tasks`
                }
                response.data.doing.forEach(task => {
                    htmlContent += `
                        <p>${ task.heading } </p>
                        <button class="todo" data-task-id=${ task.id }>To-Do</button>
                        <button class="Completed" data-task-id=${ task.id }>Completed</button>`;
                });
                $('.doing-task').html(htmlContent);
            },
            error: function(xhr, status, error) {
                // Handle the error here
                console.error(xhr.responseText);
            }
        });
    }
</script>