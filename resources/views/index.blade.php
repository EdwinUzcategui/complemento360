@extends('layouts.main');

@section('content')
    @php
        $session = Session::get('tab');

        if ($session) {
            if ($session == 'user') {
                $tab = 'user';
            }

            if ($session == 'travel') {
                $tab = 'travel';
            }
        } else {
            $tab = 'user';
        }
    @endphp
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
          <button class="nav-link active" id="nav-users-tab" data-bs-toggle="tab" data-bs-target="#nav-users" type="button" role="tab" aria-controls="nav-users" aria-selected="true">Users</button>
          <button class="nav-link" id="nav-travels-tab" data-bs-toggle="tab" data-bs-target="#nav-travels" type="button" role="tab" aria-controls="nav-travels" aria-selected="false">Travels</button>
        </div>
    </nav>
    <div class="tab-content" id="nav-tabContent">
        <div class="tab-pane fade show @if ($tab == 'user') active @endif " id="nav-users" role="tabpanel" aria-labelledby="nav-users-tab">
            <table class="table table-info table-striped">
                <thead>
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">Name</th>
                      <th scope="col">Lastname</th>
                      <th scope="col">Phone</th>
                      <th scope="col">Email</th>
                      <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr class="table-secondary">
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->lastname }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <form action="{{ route('user.destroy',$user->id) }}" method="POST">
                                    <a href="{{ route('user.show',$user->id) }}" class="btn btn-success">Details</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger ">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="tab-pane fade @if ($tab == 'travel') active show @endif" id="nav-travels" role="tabpanel" aria-labelledby="nav-travels-tab">
            <table class="table table-warning table-striped">
                <thead>
                    <tr>
                      <th scope="col">ID</th>
                      <th scope="col">Travel date</th>
                      <th scope="col">Country</th>
                      <th scope="col">City</th>
                      <th scope="col">User email</th>
                      <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($travels as $travel)
                        <tr class="table-secondary">
                            <td>{{ $travel->id }}</td>
                            <td>{{ $travel->travel_date }}</td>
                            <td>{{ $travel->country }}</td>
                            <td>{{ $travel->city }}</td>
                            <td>{{ $travel->user_email }}</td>
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
        </div>
    </div>
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <strong>{{ $message }}</strong>
        </div>
    @endif
@endsection

