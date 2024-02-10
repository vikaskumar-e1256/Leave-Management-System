@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h1>Employee Management</h1>
                <a class="btn btn-block btn-info" href="{{ route('employee.create') }}">Create New Employee</a>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Join Date</th>
                            <th>Annual Leave Balance</th>
                            <th>Casual Leave Balance</th>
                            <th>Sick Leave Balance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td>{{ $employee->name }}</td>
                                <td>{{ $employee->join_date }}</td>
                                <td>{{ $employee->annual_leave_balance }}</td>
                                <td>{{ $employee->casual_leave_balance }}</td>
                                <td>{{ $employee->sick_leave_balance }}</td>
                                <td>
                                    <a href="#" class="btn btn-primary">Edit</a>
                                    <!-- Add delete functionality if needed -->
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
