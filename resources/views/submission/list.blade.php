@extends('app')
<?php 
use Illuminate\Http\Response;
use App\Session;
?>
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">List of Submissions</div>
                    <div class="panel-body">
                        @foreach($users as $user)
                            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                        <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#s{{$user->id}}"
                                               aria-expanded="true" aria-controls="collapseOne">
                                                {{$user->name}}
                                            </a>
                                        </h4>
                                    </div>
                                    <div id="s{{$user->id}}" class="panel-collapse collapse" role="tabpanel"
                                         aria-labelledby="headingOne">
                                        <div class="panel-body">
                                            <table class="table table-striped">
                                                <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Time</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php $i = 1; ?>
                                                @foreach($user->sessions as $session)
                                                    <tr>
                                                        <td>{{$i}}</td>
                                                        <td>{{date_diff($session->updated_at,$session->created_at)->format('%im%ss')}}</td>
                                                        <td>
                                                            <table class="table table-striped">
                                                                <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Process Time</th>
                                                                    <th>Quantum Time</th>
                                                                    <th>Arrival Time</th>
                                                                    <th>Method Used</th>
                                                                    <th>Submission Time</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                @foreach($session->submissions as $submission)
                                                                    <tr>
                                                                        <td>{{$submission->process_number}}</td>
                                                                        <td>{{$submission->process_time}}</td>
                                                                        <td>{{$submission->quantum_time}}</td>
                                                                        <td>{{$submission->arrival_time}}</td>
                                                                        <td>{{$submission->method}}</td>
                                                                        <td>{{$submission->updated_at}}</td>
                                                                    </tr>
                                                                @endforeach
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <?php $i++;?>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
@endsection