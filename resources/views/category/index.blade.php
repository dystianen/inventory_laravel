@include('component.header')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header row mx-0 px-3 pb-0">
                    <div class="col-6  ps-0">
                        <h6>List Category</h6>
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
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">
                                        Category Code</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">
                                        Category Name</th>
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
                        <label class="form-label">Category Code</label>
                        <input required id="category_code" type="text" name="category_code" class="form-control"
                            aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category Name</label>
                        <input required id="category_name" type="text" name="category_name" class="form-control"
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
            "category_code": $('#category_code').val(),
            "category_name": $('#category_name').val()
        }
        if (ajaxType == "POST") {
            $.post("{{ route('category.index') }}",
                request,
                function(data, status) {
                    myModal.hide()
                    $('#itemTable').DataTable().ajax.reload()
                });
        } else {
            $.post("{{ route('category.index') }}/" + submitType,
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
                ajax: "{{ route('category.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'category_code',
                        name: 'category_code'
                    },
                    {
                        data: 'category_name',
                        name: 'category_name'
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
                url: "{{ route('category.index') }}/" + id,
                success: function(res) {
                    const data = JSON.parse(res)
                    $('#category_code').val(data.category_code)
                    $('#category_name').val(data.category_name)
                }
            });
        } else {
            $('#modalLabel').text('Add')
            $('#category_code').val('')
            $('#category_name').val('')
        }
        myModal.show()
    }

    function deleteData(id) {
        $.post("{{ route('category.index') }}/" + id + '/delete', {
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
