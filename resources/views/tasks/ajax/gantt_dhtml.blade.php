<script src="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.js"></script>
<link href="https://cdn.dhtmlx.com/gantt/edge/dhtmlxgantt.css" rel="stylesheet">

    <style>
		.gantt_grid_scale .gantt_grid_head_cell,
		.gantt_task .gantt_task_scale .gantt_scale_cell {
			font-weight: 500;
			font-size: 13px;
		}

        .gantt_row_project {
            font-weight: bold;
        }

		.resource_marker {
			text-align: center;
		}

		.resource_marker div {
			width: 28px;
			height: 28px;
			line-height: 29px;
			display: inline-block;
			border-radius: 15px;
			color: #FFF;
			margin: 3px;
		}

		.resource_marker.workday_ok div {
			background: #51c185;
		}

		.resource_marker.workday_over div {
			background: #ff8686;
		}



		.owner-label {
			width: 20px;
			height: 20px;
			line-height: 20px;
			font-size: 12px;
			display: inline-block;
			border: 1px solid #cccccc;
			border-radius: 25px;
			background: #e6e6e6;
			color: #6f6f6f;
			margin: 0 3px;
			font-weight: bold;
		}

		.gantt_tooltip {
			font-size: 13px;
			line-height: 16px;
		}

        .gantt_tree_content {
            overflow:hidden;
            text-overflow: ellipsis;
        }

        .weekend{
            background: #f4f7f4 !important;
        }

        .gantt_task_link .gantt_line_wrapper div{
            background-color: var(--header_color) !important;
        }

        .gantt_link_arrow_right {
            border-left-color:  var(--header_color);
        }
        
        .gantt_link_arrow_left {
            border-right-color:  var(--header_color);
        }

        .gantt_project .gantt_link_control, .gantt_milestone .gantt_link_control {
            display: none;
        }

        .gantt_right.gantt_side_content {
            top: 0 !important;
        }

	</style>
<!-- ROW START -->
<div class="row py-3 py-lg-5 py-lg-5">
    <div class="col-lg-12 col-md-12 mb-4 mb-xl-0 mb-lg-4">
        <div id="gantt_here" class="mt-4" style='width:100%; height:700px;'></div>
    </div>
</div>
<div id="test"></div>

<script>
    var ganttData = @json($ganttData);

    function linkTypeToString(linkType) {
        switch (linkType) {
            case gantt.config.links.start_to_start:
                return "Start to start";
            case gantt.config.links.start_to_finish:
                return "Start to finish";
            case gantt.config.links.finish_to_start:
                return "Finish to start";
            case gantt.config.links.finish_to_finish:
                return "Finish to finish";
            default:
                return ""
        }
    }

    var daysStyle = function(date){

        var dateToStr = gantt.date.date_to_str("%D");

        if (dateToStr(date) == "Sun"||dateToStr(date) == "Sat")  return "weekend";

        return "";
    };

    var weekScaleTemplate = function (date) {
        var dateToStr = gantt.date.date_to_str("%d %M, %y");
        var endDate = gantt.date.add(gantt.date.add(date, 1, "week"), -1, "day");
        return dateToStr(date) + " - " + dateToStr(endDate);
    };

    gantt.config.scales = [
    // {unit: "month", format: "%F, %Y"},
    {unit: "week", step: 1, format: weekScaleTemplate},
    {unit: "day", step: 1, format: "%j, %D", css:daysStyle},
    ];

    gantt.config.scale_height = 54;

    gantt.plugins({
        tooltip: true,
        marker: true,
        keyboard_navigation: true,
    });
    gantt.templates.tooltip_date_format = gantt.date.date_to_str("%F %j, %Y");
    
    
    gantt.config.columns = [
        { name: "text", label: "Sub Task",  align: "left", tree: true, width: 200, resize: true, },
        { name: "start_date", align: "center", width: 80, resize: true },
        { name: "duration", width: 60, align: "center" }
    ];

    gantt.config.order_branch = false;
    gantt.config.open_tree_initially = false;
    gantt.config.layout = {
        css: "gantt_container",
        rows: [
            {
                cols: [
                    { view: "grid", group: "grids", scrollY: "scrollVer" },
                    { resizer: true, width: 1 },
                    { view: "timeline", scrollX: "scrollHor", scrollY: "scrollVer" },
                    { view: "scrollbar", id: "scrollVer", group: "vertical" }
                ],
                gravity: 2
            },
            { resizer: true, width: 1 },
           
            { view: "scrollbar", id: "scrollHor" }
        ]
    };

    var resourcesStore = gantt.createDatastore({
		name: gantt.config.resource_store,
		type: "treeDatastore",
		initItem: function (item) {
			item.parent = item.parent || gantt.config.root_id;
			item[gantt.config.resource_property] = item.parent;
			item.open = true;
			return item;
		}
	});

    gantt.attachEvent("onTaskCreated", function(task){
        task[gantt.config.resource_property] = [];
        return true;
    });

    gantt.init("gantt_here");

    resourcesStore.attachEvent("onParse", function(){
		var people = [];
		resourcesStore.eachItem(function(res){
			if(!resourcesStore.hasChild(res.id)){
				var copy = gantt.copy(res);
				copy.key = res.id;
				copy.label = res.text;
				people.push(copy);
			}
		});
		gantt.updateCollection("people", people);
	});

    gantt.templates.grid_folder = function(item) {
        return `<div
        class='gantt_tree_icon mr-1 ${(item.$open ? "fas fa-chevron-down" : "fas fa-chevron-right")}'>
        </div>`;
    };

    gantt.templates.grid_file = function(item) {
        return "";
    };

    gantt.templates.tooltip_text = function(start,end,subTask){
        return subTask.view;
    };

    gantt.templates.scale_cell_class = function(date){
        if(date.getDay()==0||date.getDay()==6) {
            return "weekend";
        }
    };
    gantt.templates.timeline_cell_class = function(subTask, date){
        if (date.getDay()==0||date.getDay()==6) {
            return "weekend" ;
        }
    };

    var dateToStr = gantt.date.date_to_str(gantt.config.task_date);
    var markerId = gantt.addMarker({
        start_date: new Date(), //a Date object that sets the marker's date
        css: "today", //a CSS class applied to the marker
        text: "@lang('app.today')", //the marker title
        title: dateToStr( new Date()) // the marker's tooltip
    });

    gantt.templates.task_text = function(start, end, subTask){
        return subTask.text;
    };

    gantt.templates.rightside_text = function(start, end, subTask){
        return subTask.text_user;
    };


    gantt.config.drag_progress = false;
    gantt.config.details_on_dblclick = false;

    gantt.parse(ganttData);
    gantt.showDate(new Date());

</script>