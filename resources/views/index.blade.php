@extends('layouts.app')
@section('custom-style')
    .form-control:focus{
        border-color: #333 !important;
        box-shadow: none;
    }
@endsection
@section('content')

    <div class="col-md-6 mx-auto">
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="task" placeholder="Write your task ..."
                   aria-label="Write your task"
                   aria-describedby="submit">
            <button class="btn btn-sm btn-default" type="button" id="submit_task" style="color: orangered; border: 1px solid #333;">Add</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center text-white" style="background: orangered;">
                    To Do
                </div>
                <div class="card-body">
                    <div class="list-group" id="order-left">
                    </div>
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center text-white" style="background: orangered;">
                    In Progress
                </div>
                <div class="card-body">
                    <div class="list-group" id="order-mid">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header text-center text-white" style="background: orangered;">
                    Done
                </div>
                <div class="card-body">
                    <div class="list-group" id="order-right">
                    </div>
                </div>
            </div>
        </div>

    </div>


@endsection

@section('script-section')
    <script>
        let todos_list = [];
        let in_progress_list = [];
        let done_list = [];

        $(document).ready(function () {
            fetch_data();

            $('#submit_task').click(function () {
                var task = $('#task').val();
                if (!task) {
                    alert('Task can not be empty');
                    return false;
                }
                $.ajax({
                    url: 'api/store',
                    type: "POST",
                    data: {
                        "token": "{{csrf_token()}}",
                        task: task,
                    },
                    success: function (data) {
                        if (data.responseCode == 1) {
                            $('#task').val('');
                            fetch_data();
                        } else {
                            console.log(data.message);
                        }
                    }
                })
            });
        });

        function fetch_data() {
            $.ajax({
                url: 'api/tasks',
                type: "GET",
                success: function (data) {
                    if (data.responseCode == 1) {
                        if (typeof data.data.todo != "undefined") {
                            let todos = data.data.todo.map((element) => {
                                return '<div class="list-group-item" data-id="' + element.id + '">' + element.task + '</div>'
                            });
                            $('#order-left').html(todos);
                        }
                        if (typeof data.data.inProgress != "undefined") {
                            let progressarr = data.data.inProgress.map((element) => {
                                return '<div class="list-group-item" data-id="' + element.id + '">' + element.task + '</div>'
                            });
                            $('#order-mid').html(progressarr);
                        }
                        if (typeof data.data.done != "undefined") {
                            let donearr = data.data.done.map((element) => {
                                return '<div class="list-group-item" data-id="' + element.id + '">' + element.task + '</div>'
                            });
                            $('#order-right').html(donearr);
                        }
                    } else {
                        console.log(data.message);
                    }
                }
            })
        }

        new Sortable(document.getElementById('order-left'), {
            group: 'Shared',
            animation: 150,
            store: {
                get: function (order) {
                    return todos_list;
                },
                set: function (order) {
                    let prev_count = todos_list.length;
                    todos_list = order.toArray();
                    if (prev_count < todos_list.length) {
                        ajax_call();
                    }
                },
            },
        });
        new Sortable(document.getElementById('order-mid'), {
            group: 'Shared',
            animation: 150,
            store: {
                get: function (order) {
                    return in_progress_list;
                },
                set: function (order) {
                    let prev_count = in_progress_list.length;
                    in_progress_list = order.toArray();
                    if (prev_count < in_progress_list.length) {
                        ajax_call();
                    }
                },
            },
        });
        new Sortable(document.getElementById('order-right'), {
            group: 'Shared',
            animation: 150,
            store: {
                get: function (order) {
                    return done_list;
                },
                set: function (order) {
                    let prev_count = done_list.length;
                    done_list = order.toArray();
                    if (prev_count < done_list.length) {
                        ajax_call();
                    }
                },
            },
        });

        function ajax_call() {
            $.ajax({
                url: 'api/update',
                type: "POST",
                data: {
                    "token": "{{csrf_token()}}",
                    todos_list: todos_list,
                    in_progress_list: in_progress_list,
                    done_list: done_list,
                },
                success: function (data) {
                    if (data.responseCode == 1) {
                        fetch_data();
                    } else {
                        console.log(data.message);
                    }
                }
            })
        }

    </script>
@endsection
