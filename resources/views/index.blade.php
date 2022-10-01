@extends('layouts.app')
@section('content')

    <div class="col-md-6 mx-auto">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Write your task ..." aria-label="Write your task"
                   aria-describedby="submit">
            <button class="btn btn-outline-secondary" type="button" id="submit">Add</button>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
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
                <div class="card-header">
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
                <div class="card-header">
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
        $(document).ready(function () {
            fetch_data();
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
                get: function () {

                },
                set: function (order) {
                    let sortable = order.toArray();
                    console.log(sortable);

                },
            },

        });
        new Sortable(document.getElementById('order-mid'), {
            group: 'Shared',
            animation: 150,
        });
        new Sortable(document.getElementById('order-right'), {
            group: 'Shared',
            animation: 150,
        });


    </script>

@endsection
