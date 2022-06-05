@include('component.header')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header row mx-0 px-3 pb-0">
                    <div class="col-6  ps-0">
                        <h6>List Of Stock</h6>
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
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Stock
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Unit
                                    </th>
                                    <th class="text-uppercase text-secondary font-weight-bolder opacity-7 ps-2">Minimum
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

@include('component.footer')
<script>
    let base_url = window.location.origin
    getData()
    fetchData()
    let submitType = 'add'

    function getData() {
        $(function() {
            let table = $('#transactionTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('transaction.index') }}",
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'item.item_name',
                        name: 'item.item_name',
                        render: function(data, type, row) {;
                            if(row.calculated.stock > row.calculated.safetyStock){
                                return data + ' <i class="fas fa-thumbs-up text-success"></i>';
                            }else{
                                return data + ' <i class="fas fa-exclamation-triangle text-danger"></i>';
                            }
                        },
                    },
                    {
                        data: 'calculated.stock',
                        name: 'calculated.stock',
                    },
                    {
                        data: 'item.unit.unit_code',
                        name: 'item.unit.unit_code',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'calculated.safetyStock',
                        name: 'calculated.safetyStock',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
        });
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
</script>
