@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Button Add and Update modal -->
        <button type="button" class="btn btn-primary mb-2" data-bs-toggle="modal" data-bs-target="#exampleModal" id="add">
            Add Users
        </button>
        <div class="card p-4">
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Users</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form">
                        @csrf
                        <input type="hidden" name="id" id="id">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Insert Your Name" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" placeholder="Insert Your Number Phone"
                                name="phone">
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" placeholder="Insert Your Address"
                                name="address"></textarea>
                        </div>
                        <button type="button" class="btn btn-secondary" id="close" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="save">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @push('js')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"
                integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>
        <script>
            $(document).ready(function() {
                isi()
            });

            function isi() {
                $('#table1').DataTable({
                    serverside: true,
                    responseive: true,
                    ajax: {
                        url: "{{ route('data') }}"
                    },
                    columns: [{
                            "data": null,
                            "sortable": false,
                            render: function(data, type, row, meta) {
                                return meta.row + meta.settings._iDisplayStart + 1
                            }
                        },
                        {
                            data: 'name',
                            name: 'name'
                        },
                        {
                            data: 'phone',
                            name: 'phone'
                        },
                        {
                            data: 'address',
                            name: 'address'
                        },
                        {
                            data: 'aksi',
                            name: 'aksi'
                        },
                    ]
                });
            }
        </script>
        <script>
            $('#save').on('click', function() {
                if ($(this).text() === 'Update Data') {
                    edit()
                } else {
                    add()
                }
            })

            $(document).on('click', '.edit', function() {
                let id = $(this).attr('id')
                $('#add').click()
                $('#save').text('Update Data')

                $.ajax({
                    url: "{{ route('data.edit') }}",
                    type: 'post',
                    data: {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        $('#id').val(res.data.id)
                        $('#name').val(res.data.name)
                        $('#phone').val(res.data.phone)
                        $('#address').val(res.data.address)
                    }
                })
            })

            function add() {
                $.ajax({
                    url: "{{ route('data.store') }}",
                    type: "post",
                    data: {
                        name: $('#name').val(),
                        phone: $('#phone').val(),
                        address: $('#address').val(),
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        console.log(res.data)
                        Swal.fire(
                            'Successfully Add Data!',
                            'Click ok to continue',
                            'success'
                        )
                        $('#close').click()
                        $('#form')[0].reset()
                        $('#table1').DataTable().ajax.reload()
                    },
                    error: function(xhr) {
                        alert(xhr.responJson.message)
                    }
                })
            }

            function edit() {
                $.ajax({
                    url: "{{ route('data.update') }}",
                    type: "post",
                    data: {
                        id: $('#id').val(),
                        name: $('#name').val(),
                        phone: $('#phone').val(),
                        address: $('#address').val(),
                        "_token": "{{ csrf_token() }}"
                    },
                    success: function(res) {
                        console.log(res.data)
                        Swal.fire(
                            'Successfully Update Data!',
                            'Click ok to continue',
                            'success'
                        )
                        $('#close').click()
                        $('#form')[0].reset()
                        $('#save').text('submit')
                        $('#table1').DataTable().ajax.reload()
                    },
                    error: function(xhr) {
                        alert(xhr.responJson.message)
                    }
                })
            }

            $(document).on('click', '.delete', function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        let id = $(this).attr('id')
                        $.ajax({
                            url: "{{ route('data.delete') }}",
                            type: 'post',
                            data: {
                                id: id,
                                "_token": "{{ csrf_token() }}"
                            },
                            success: function(res) {
                                Swal.fire(
                                    'Deleted!',
                                    'Your file has been deleted.',
                                    'success'
                                )
                                $('#table1').DataTable().ajax.reload()
                            }
                        })
                    }
                })
            })
        </script>
    @endpush
@endsection
