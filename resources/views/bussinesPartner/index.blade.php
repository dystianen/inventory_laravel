@include('component.header')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header row mx-0 px-3 pb-0">
                    <div class="col-6  ps-0">
                        <h6>List Bussiness Type Partner</h6>
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
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7">No</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Type Partner</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Code</th>
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
    <form>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="mb-3">
                        <label class="form-label">Code</label>
                        <input required id="code" type="text" name="code" class="form-control"
                            aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type Partner</label>
                        <input required id="type" type="text" name="type" class="form-control"
                            aria-describedby="emailHelp">
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
    const myModal = new bootstrap.Modal(document.getElementById('modal'))
    let base_url = window.location.origin
    getData()
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
            "code": $('#code').val(),
            "type": $('#type').val()
        }
        if (ajaxType == "POST") {
            $.post("{{ route('partnertype.index') }}",
                request,
                function(data, status) {
                    myModal.hide()
                    $('#itemTable').DataTable().ajax.reload()
                });
        } else {
            $.post("{{ route('partnertype.index') }}/" + submitType,
                request,
                function(data, status) {
                    myModal.hide()
                    $('#itemTable').DataTable().ajax.reload()
                });
        }

    }

    function getData() {
        $(function() {
            let table = $('#itemTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('partnertype.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'code',
                        name: 'code'
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
                url: "{{ route('partnertype.index') }}/" + id,
                success: function(res) {
                    const data = JSON.parse(res)
                    $('#code').val(data.code)
                    $('#type').val(data.type)
                }
            });
        } else {
            $('#modalLabel').text('Add')
            $('#code').val('')
            $('#type').val('')
        }
        myModal.show()
    }

    function deleteData(id) {
        $.post("{{ route('partnertype.index') }}/" + id + '/delete', {
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
