@include('component.header')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header row mx-0 px-3 pb-0">
                    <div class="col-6  ps-0">
                        <h6>List Item</h6>
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
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Category
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Unit
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Price
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
    <form>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <div class="mb-3">
                        <label class="form-label">SKU Number</label>
                        <input required id="sku_number" type="text" name="sku_number" class="form-control"
                            aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Item Code</label>
                        <input required id="item_code" type="text" name="item_code" class="form-control"
                            aria-describedby="emailHelp">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Item Name</label>
                        <input required id="item_name" type="text" name="item_name" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Category Item</label>
                        <select required id="item_category_id" type="text" name="item_category_id"
                            class="form-control">
                            <option selected disabled value="">Select Category</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Unit</label>
                        <select required id="unit_id" type="text" name="unit_id" class="form-control">
                            <option selected disabled value="">Select Unit</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input required id="price" type="number" name="price" class="form-control">
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
            "item_code": $('#item_code').val(),
            "item_name": $('#item_name').val(),
            "item_category_id": $('#item_category_id').val(),
            "unit_id": $('#unit_id').val(),
            "sku_number": $('#sku_number').val(),
            // "price": $('#price').val()
        }
        if (ajaxType == "POST") {
            $.post("{{ route('item.index') }}",
                request,
                function(data, status) {
                    myModal.hide()
                    $('#itemTable').DataTable().ajax.reload()
                });
        } else {
            $.post("{{ route('item.index') }}/" + submitType,
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
                ajax: "{{ route('item.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'item_name',
                        name: 'item_name'
                    },
                    {
                        data: 'category.category_name',
                        name: 'category.category_name'
                    },
                    {
                        data: 'unit.unit_name',
                        name: 'category.unit_name'
                    },
                    // {
                    //     data: 'price',
                    //     name: 'price'
                    // },
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
                url: base_url + "/item/" + id,
                success: function(res) {
                    const data = JSON.parse(res)
                    $('#item_code').val(data.item_code)
                    $('#item_name').val(data.item_name)
                    $('#item_category_id').val(data.item_category_id)
                    $('#unit_id').val(data.unit_id)
                    $('#sku_number').val(data.sku_number)
                }
            });
        } else {
            $('#modalLabel').text('Add')
            $('#item_code').val('')
            $('#item_name').val('')
            $('#item_category_id').val('')
            $('#unit_id').val('')
            $('#sku_number').val('')
        }
        myModal.show()
    }

    function fetchData() {
        $.ajax({
            type: "GET",
            headers: {
                "Content-Type": "application/json"
            },
            url: '{{ route('fetch.category') }}',
            success: function(res) {
                const data = JSON.parse(res)
                data.forEach(element => {
                    $('#item_category_id').append('<option value="' + element.id + '">' + element
                        .category_name + '</option>')
                })
            }
        });
        $.ajax({
            type: "GET",
            headers: {
                "Content-Type": "application/json"
            },
            url: '{{ route('fetch.unit') }}',
            success: function(res) {
                const data = JSON.parse(res)
                data.forEach(element => {
                    $('#unit_id').append('<option value="' + element.id + '">' + element.unit_name +
                        '</option>')
                });
            }
        });
    }

    function deleteData(id) {
        $.post("{{ route('item.index') }}/" + id + '/delete',
            {"_token": "{{ csrf_token() }}"},
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
