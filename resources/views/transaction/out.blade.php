@include('component.header')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header row mx-0 px-3 pb-0">
                    <div class="col-6  ps-0">
                        <h6>List Transaction In</h6>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        @if (auth()->user()->job_title_id == 0 || auth()->user()->job_title_id == 1)
                            <button class="btn btn-primary" onclick="showModal('add')">Add</button>
                        @endif
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <div class="table-responsive p-3">
                        <table id="transactionTable" class="table align-transactions-center mb-0" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7">NO</th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Item
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Total
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Date
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
                        <label class="form-label">Date</label>
                        <input required id="date" type="date" name="date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Item</label>
                        <select required id="item" name="item" class="form-control">
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warehouse</label>
                        <select required id="warehouse" name="warehouse" class="form-control">
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total</label>
                        <input type="number" required id="total" name="total" class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select required id="type" name="type" class="form-control">
                            <option selected value="out">Out</option>
                        </select>
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
            "date": $('#date').val(),
            "item": $('#item').val(),
            "total": $('#total').val(),
            "warehouse": $('#warehouse').val(),
            "type": $('#type').val()
        }
        if (ajaxType == "POST") {
            $.post("{{ route('transaction.index') }}",
                request,
                function(data, status) {
                    myModal.hide()
                    $('#transactionTable').DataTable().ajax.reload()
                });
        } else {
            $.post("{{ route('transaction.index') }}/" + submitType,
                request,
                function(data, status) {
                    myModal.hide()
                    $('#transactionTable').DataTable().ajax.reload()
                });
        }

    }

    function getData() {
        $(function() {
            let table = $('#transactionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('transaction.outindex') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'item.item_name',
                        name: 'item.item_name'
                    },
                    {
                        data: 'total',
                        name: 'total',
                    },
                    {
                        data: 'date',
                        name: 'date',
                    }
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
                url: base_url + "/transaction/" + id,
                success: function(res) {
                    const data = JSON.parse(res)
                    $('#date').val(data.date)
                    $('#item').val(data.item)
                    $('#warehouse').val(data.warehouse)
                    $('#type').val(data.type)
                    $('#total').val(data.total)
                }
            });
        } else {
            $('#modalLabel').text('Add')
            $('#date').val('')
            $('#item').val('')
            $('#warehouse').val('')
            $('#type').val('out')
            $('#total').val('')
        }
        myModal.show()
    }

    function fetchData() {
        $.ajax({
            type: "GET",
            headers: {
                "Content-Type": "application/json"
            },
            url: '{{ route('fetch.item') }}',
            success: function(res) {
                const data = JSON.parse(res)
                data.forEach(element => {
                    $('#item').append('<option value="' + element.id + '">' + element
                        .item_name + '</option>')
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
                    $('#warehouse').append('<option value="' + element.id + '">' + element
                        .warehouse_name +
                        '</option>')
                });
            }
        });
    }

    function deleteData(id) {
        $.post("{{ route('transaction.index') }}/" + id + '/delete', {
                "_token": "{{ csrf_token() }}"
            },
            function(data, status) {
                myModal.hide()
                $('#transactionTable').DataTable().ajax.reload()
            });
    }
    $("form").submit(function(e) {
        e.preventDefault();
        submit();
    });
</script>
