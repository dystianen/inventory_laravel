@include('component.header')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header row mx-0 px-3 pb-0">
                    <div class="col-6  ps-0">
                        <h6>List User</h6>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <button class="btn btn-primary" onclick="showModal('add')">Add</button>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-3">
                        <table id="itemTable" class="table align-items-center mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7">NO</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Name
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">NIK</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Email
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Phone
                                        Number</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Position
                                    </th>
                                    <th class="text-end text-uppercase text-secondary font-weight-bolder opacity-7">
                                    </th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <form autocomplete="off">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input required id="name" type="text" name="name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input required id="username" type="text" name="username" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input required id="email" type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIK</label>
                        <input required id="nik" type="text" name="nik" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gender</label>
                        <select required id="gender" type="text" name="gender" class="form-control">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Address
                        </label>
                        <input required id="address" type="text" name="address" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Phone
                        </label>
                        <input onkeypress="return isNumber(event)" required id="phoneNumber" type="tel"
                            name="phoneNumber" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Job</label>
                        <select required id="job_title_id" type="text" name="job_title_id" class="form-control">

                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warehouse</label>
                        <select required id="warehouse_id" type="text" name="warehouse_id" class="form-control">

                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            Password
                        </label>
                        <input autocomplete="off" required id="password" type="password" name="password"
                            class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </form>
</div>
@include('component.footer')
<script>
    function isNumber(evt) {
        evt = (evt) ? evt : window.event;
        var charCode = (evt.which) ? evt.which : evt.keyCode;
        if (charCode > 31 && (charCode < 48 || (charCode > 57 || (charCode == 189)))) {
            return false;
        }
        return true;
    }
    $(document).ready(function() {
        $('input').attr('autocomplete', 'off');
    });
    const myModal = new bootstrap.Modal(document.getElementById('modal'))
    let base_url = window.location.origin
    getData()
    fetchData()
    let submitType = 'add'

    function submit() {
        let ajaxType = 'POST';
        let url = '';
        if (submitType == 'add') {
            ajaxType = 'POST'
        } else {
            ajaxType = 'PUT'
        }
        request = {
            "_token": "{{ csrf_token() }}",
            "email": $('#email').val(),
            "name": $('#name').val(),
            "gender": $('#gender').val(),
            "warehouse_id": $('#warehouse_id').val(),
            "job_title_id": $('#job_title_id').val(),
            "name": $('#name').val(),
            "username": $('#username').val(),
            "nik": $('#nik').val(),
            "address": $('#address').val(),
            "phoneNumber": $('#phoneNumber').val(),
            "gender": $('#gender').val(),
            "password": $('#password').val(),
        }
        if (ajaxType == "POST") {
            $.post("{{ route('user.index') }}",
                request,
                function(data, status) {
                    myModal.hide()
                    $('#itemTable').DataTable().ajax.reload()
                });
        } else {
            $.post("{{ route('user.index') }}/" + submitType,
                request,
                function(data, status) {
                    myModal.hide()
                    $('#itemTable').DataTable().ajax.reload()
                });
        }

    }

    function fetchData() {
        $.ajax({
            type: "GET",
            headers: {
                "Content-Type": "application/json"
            },
            url: '{{ route('fetch.JobTitle') }}',
            success: function(res) {
                const data = JSON.parse(res)
                data.forEach(element => {
                    $('#job_title_id').append('<option value="' + element.id + '">' + element
                        .job_title_name + '</option>')
                })
            }
        });
        $.ajax({
            type: "GET",
            headers: {
                "Content-Type": "application/json"
            },
            url: '{{ route('fetch.warehouse') }}',
            success: function(res) {
                const data = JSON.parse(res)
                data.forEach(element => {
                    $('#warehouse_id').append('<option value="' + element.id + '">' + element
                        .warehouse_name +
                        '</option>')
                });
            }
        });
    }

    function getData() {
        $(function() {
            let table = $('#itemTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('user.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'nik',
                        name: 'nik'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number'
                    },
                    {
                        data: 'role',
                        name: 'roe',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'dt-body-right'
                    },
                ]
            });
        });
    }

    function showModal(id) {
        submitType = id
        if (id !== "add") {
            $('#modalLabel').text('Edit')
            $.ajax({
                type: "GET",
                headers: {
                    "Content-Type": "application/json"
                },
                url: "{{ route('user.index') }}/" + id,

                success: function(res) {
                    const data = JSON.parse(res)
                    $('#email').val(data.email)
                    $('#name').val(data.name)
                    $('#nik').val(data.nik)
                    $('#job_title_id').val(data.job_title_id)
                    $('#warehouse_id').val(data.warehouse_id)
                    $('#address').val(data.address)
                    $('#phoneNumber').val(data.phone_number)
                    $('#gender').val(data.gender)
                }
            });
        } else {
            $('#modalLabel').text('Add')
            $('#user_code').val('')
            $('#user_name').val('')
        }
        myModal.show()
    }

    function deleteData(id) {
        $.post("{{ route('user.index') }}/" + id + '/delete', {
                "_token": "{{ csrf_token() }}"
            },
            function(data, status) {
                myModal.hide()
                $('#itemTable').DataTable().ajax.reload()
            });
    }

    $("form").submit(function(e) {
        e.preventDefault();
        submit();
    });
</script>
