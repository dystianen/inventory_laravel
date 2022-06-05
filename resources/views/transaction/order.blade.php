@include('component.header')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header row mx-0 px-3 pb-0">
                    <div class="col-6  ps-0">
                        <h6>List Transaction Order</h6>
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
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Invoice
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Item
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Total
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Total
                                        Price
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Date
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Supplier
                                    </th>
                                    <th class="text-end text-uppercase text-secondary font-weight-bolder opacity-7">
                                        Actions
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
                            <option selected value="" price="0" disabled>Select Item</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Supplier</label>
                        <select required id="supplier" name="type" class="form-control">
                            <option selected value="" disabled>Select Supplier</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warehouse</label>
                        <select required id="warehouse" name="warehouse" class="form-control">
                            <option selected value="" disabled>Select Warehouse</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total</label>
                        <input oninput="updatePrice()" type="number" required id="total" name="total"
                            class="form-control" />
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp. </span>
                            <input type="text" required id="total_price" name="total_price"
                                class="form-control currency" readonly />
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select required id="type" name="type" class="form-control">
                            <option selected value="order">Order</option>
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

<div class="modal fade" id="modalAccept" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <form>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabelAccept">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body ">
                    <input id="acceptId" value="" type="hidden">
                    <div class="mb-3">
                        <label class="form-label">Total Accepted</label>
                        <input required id="total_accept" type="number" name="date" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Warehouse</label>
                        <select required id="warehouse2" name="warehouse" class="form-control">
                            <option selected value="" disabled>Selecet Warehouse</option>
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
    let price = 0
    const myModal = new bootstrap.Modal(document.getElementById('modal'))
    const myModalAccept = new bootstrap.Modal(document.getElementById('modalAccept'))
    let base_url = window.location.origin
    getData()
    fetchData()
    let submitType = 'add'
    $("#item").change(function() {
        price = $(this).children(":selected").attr("price");
        updatePrice()
    });

    function submit() {
        let ajaxType = 'POST';
        let url = '';
        if (submitType == 'add') {
            ajaxType = 'POST'
        } else {
            ajaxType = 'PUT'
        }
        if (submitType == 'accept') {
            request = {
                "_token": "{{ csrf_token() }}",
                "total_accept": $('#total_accept').val(),
                "warehouse": $('#warehouse2').val(),
                "accept_id": $('#acceptId').val()
            }
            $.post("{{ route('transaction.index') }}/accept",
                request,
                function(data, status) {
                    myModalAccept.hide()
                    $('#transactionTable').DataTable().ajax.reload()
                });
        } else {
            request = {
                "_token": "{{ csrf_token() }}",
                "date": $('#date').val(),
                "item": $('#item').val(),
                "total": $('#total').val(),
                "supplier_id": $('#supplier').val(),
                "warehouse": $('#warehouse').val(),
                "type": $('#type').val()
            }
            console.log(request);
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

    }

    function getData() {
        $(function() {
            let table = $('#transactionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('transaction.orderindex') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'invoice',
                        name: 'invoice'
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
                        data: 'total_price',
                        name: 'total_price',
                    },
                    {
                        data: 'date',
                        name: 'date',
                    },
                    {
                        data: 'supplier.suppliers_name',
                        name: 'supplier.suppliers_name',
                    },
                    @if (auth()->user()->job_title_id == 0 || auth()->user()->job_title_id == 2)
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false,
                            className: 'dt-body-right'
                        },
                    @endif
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
            $('#modalLabel').text('Create Order')
            $('#date').val('')
            $('#item').val('')
            $('#warehouse').val('')
            $('#type').val('order')
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
                    $('#item').append('<option price="' + element.price + '" value="' + element.id +
                        '">' + element
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
                    $('#warehouse2').append('<option value="' + element.id + '">' + element
                        .warehouse_name +
                        '</option>')
                });
            }
        });
        $.ajax({
            type: "GET",
            headers: {
                "Content-Type": "application/json"
            },
            url: '{{ route('fetch.supplier') }}',
            success: function(res) {
                const data = JSON.parse(res)
                data.forEach(element => {
                    $('#supplier').append('<option value="' + element.id + '">' + element
                        .suppliers_name +
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

    function updatePrice() {
        $('#total_price').val($('#total').val() * price)
        $('#total_price').manageCommas();
    }
    $("form").submit(function(e) {
        e.preventDefault();
        submit();
    });


    $(document).ready(function() {
        //apply on typing and focus
        $('input.currency').on('blur', function() {
            $(this).manageCommas();
        });
        //then sanatize on leave
        // if sanitizing needed on form submission time,
        //then comment beloc function here and call in in form submit function.
        $('input.currency').on('focus', function() {
            $(this).santizeCommas();
        });
    });

    String.prototype.addComma = function() {
        return this.replace(/(.)(?=(.{3})+$)/g, "$1,").replace(',.', '.');
    }
    //Jquery global extension method
    $.fn.manageCommas = function() {
        return this.each(function() {
            $(this).val($(this).val().replace(/(,|)/g, '').addComma());
        });
    }

    $.fn.santizeCommas = function() {
        return $(this).val($(this).val().replace(/(,| )/g, ''));
    }

    function accept(id) {
        $('#modalLabelAccept').text('Accept')
        $('#acceptId').val(id)
        submitType = 'accept'
        $.ajax({
            type: "GET",
            headers: {
                "Content-Type": "application/json"
            },
            url: base_url + "/transaction/" + id,
            success: function(res) {
                const data = JSON.parse(res)
            }
        });
        myModalAccept.show()
    }

    function reject(id) {
        $.post("{{ route('transaction.index') }}/" + id + '/delete', {
                "_token": "{{ csrf_token() }}"
            },
            function(data, status) {
                myModal.hide()
                $('#transactionTable').DataTable().ajax.reload()
            });
    }
</script>
