@extends('adminlte::page')

@section('content')
    <div class="container">
        <div class="row">
<!-- @include('admin.sidebar') -->
            <div class="col-md-9">
                <div class="card">
                    <!-- <div class="card-header">Create New Player</div> -->
                    <div class="card-body">
                       <h3>Add a Player</h3>                        <br />

                        @if ($errors->any())
                            <ul class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ url('/players') }}" accept-charset="UTF-8" class="form-horizontal" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            @include ('players.form', ['formMode' => 'create'])

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
