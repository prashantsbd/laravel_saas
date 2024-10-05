@php
$moveClass = '';
@endphp
@if ($draggable == 'false')
    @php
        $moveClass = 'move-disable';
    @endphp
@endif

<style>
    .projectNameSpan {
        white-space: normal
    }
</style>

@php
    $priorityColor = ($subtask->task->priority == 'high' ? '#dd0000' : ($subtask->task->priority == 'medium' ? '#ffc202' : '#0a8a1f'));
@endphp
<div class="card rounded bg-white border-grey b-shadow-4 m-1 mb-2 {{ $moveClass }} task-card"
    data-task-id="{{ $subtask->id }}" id="drag-task-{{ $subtask->id }}" style="border-left: 3px solid {{ $priorityColor }}; background-color: {{ $priorityColor.'08 !important;' }}">
    <div class="card-body p-2">
        <div class="d-flex justify-content-between mb-1">
            <a href="{{ route('tasks.show', [$subtask->id ]) }}"
                class="f-12 f-w-500 text-dark mb-0 text-wrap openRightModal">{{ $subtask->title }}</a>
            <p class="f-12 font-weight-bold text-dark-grey mb-0">
                #{{ $key+1 }}
            </p>
        </div>

        <div class="d-flex mb-1 justify-content-between">
            @if ($subtask->task)
                <div>
                    <i class="fa fa-layer-group f-11 text-lightest"></i><span
                        class="ml-2 f-11 text-lightest projectNameSpan">{{ $subtask->task->heading }}</span>
                </div>
            @endif

            @if ($subtask->exp_work_hrs > 0)
                <div  data-toggle="tooltip" data-original-title="@lang('app.estimate'): {{ $task->estimate_hours }} @lang('app.hrs') {{ $task->estimate_minutes }} @lang('app.mins')">
                    <i class="fa fa-hourglass-half f-11 text-lightest"></i><span
                        class="ml-2 f-11 text-lightest">{{ $subtask->exp_work_hrs }} Hrs</span>
                </div>
            @endif
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex flex-wrap">
                @if ($subtask->assigned_to)
                    <div class="avatar-img mr-1 rounded-circle">
                        <a href="{{ route('employees.show', $subtask->assigned_to) }}" alt="{{ $subtask->assignedTo->name }}"
                            data-toggle="tooltip" data-original-title="{{ $subtask->assignedTo->name }}"
                            data-placement="right"><img src="{{ $subtask->assignedTo->image_url }}"></a>
                    </div>
                @endif
            </div>
            @if (!is_null($subtask->due_date))
                @if ($subtask->due_date->endOfDay()->isPast())
                    <div class="d-flex text-red">
                        <span class="f-12 ml-1"><i class="f-11 bi bi-calendar align-self-center"></i> {{ $subtask->due_date->translatedFormat(company()->date_format) }}</span>
                    </div>
                @elseif($subtask->due_date->setTimezone(company()->timezone)->isToday())
                    <div class="d-flex text-dark-green">
                        <i class="fa fa-calendar-alt f-11 align-self-center"></i><span class="f-12 ml-1">@lang('app.today')</span>
                    </div>
                @else
                    <div class="d-flex text-lightest">
                        <i class="fa fa-calendar-alt f-11 align-self-center"></i><span
                            class="f-12 ml-1">{{ $subtask->due_date->translatedFormat(company()->date_format) }}</span>
                    </div>
                @endif
            @endif

        </div>
    </div>
</div><!-- div end -->
