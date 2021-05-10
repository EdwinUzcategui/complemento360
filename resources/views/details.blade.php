@extends('layouts.main');

@section('content')
    <div class="container">
        <a href="{{ url("/") }}" class="btn btn-primary">Go back</a>
        <div class="row">
            <div class="col-4">
                <h3>User information</h3>
                <div class="card" style="width: 18rem;">
                    <img src="{{ $user->url_photo }}" class="card-img-top" alt="...">
                    <div class="card-body">
                      <h5 class="card-title text-center">{{ $user->name }} {{ $user->lastname }}</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Phone: </strong>{{ $user->phone }}</li>
                            <li class="list-group-item"><strong>Email: </strong>{{ $user->email }}</li>
                            <li class="list-group-item"><strong>Address: </strong>{{ $user->address }}</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-8">
                <h3>User's travels</h3>
                @if (count($user->travels))
                    <table class="table table-warning table-striped">
                        <thead>
                            <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Travel date</th>
                            <th scope="col">Country</th>
                            <th scope="col">City</th>
                            <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($user->travels as $travel)
                                <tr class="table-secondary">
                                    <td>{{ $travel->id }}</td>
                                    <td>{{ $travel->travel_date }}</td>
                                    <td>{{ $travel->country }}</td>
                                    <td>{{ $travel->city }}</td>
                                    <td>
                                        <form action="{{ route('travel.destroy',$travel->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger ">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p class="text-center">No travels found</p>
                @endif

            </div>
        </div>
        @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <strong>{{ $message }}</strong>
        </div>
    @endif
    </div>
@endsection
