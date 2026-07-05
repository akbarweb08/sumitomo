@extends('layouts.app')

@section('title', 'Master Data - ' . date('Y-m-d'))

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap5.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Master Data</li>
    </ol>
</nav>

<div class="row">
    <div class="col-12">
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <div>
                    <a href="{{ route('mastersupplier.index') }}" class="btn btn-secondary me-2">Supplier</a>
                </div>
                <div>
                    <button type="button" class="btn btn-info me-2 text-white" onclick="toggleAdd()">Add New</button>
                    <a href="#" class="btn btn-danger">Deleted Data</a>
                </div>
            </div>
            
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-bordered table-striped text-center align-middle" style="width:100%;">
                        <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>Id</th>
                                <th>Prefiks</th>
                                <th>Invoice Number</th>
                                <th>LotNumber</th>
                                <th>Color</th>
                                <th>Background Color</th>
                                <th>Supplier</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $i = 0; @endphp
                            @foreach($colors as $row)
                                @php $i++; @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $row->Id }}</td>
                                    <td>{{ $row->Prefiks }}</td>
                                    <td>{{ $row->InvoiceNumber }}</td>
                                    <td>{{ $row->LotPlace }}</td>
                                    <td style="font-weight:bold; background-color: {{ $row->ColorHex }}; color: {{ $row->ColorText }}">
                                        {{ $row->Prefiks }}&nbsp;{{ $row->ColorText }}
                                    </td>
                                    <td style="font-weight:bold;">{{ $row->ColorHex }}</td>
                                    <td>{{ $row->supply_name }}</td>
                                    <td>{{ $row->total }}</td>
                                    <td style="width:150px;">
                                        <button type="button" class="btn btn-primary btn-sm" 
                                            data-colors="{{ $row->Id }};{{ $row->InvoiceNumber }};{{ $row->ColorHex }};{{ $row->ColorText }};{{ $row->LotPlace }};{{ $row->Prefiks }};{{ $row->supply }};" 
                                            onclick="toggleEditModal(this)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteData('{{ $row->Id }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" title="Batch Print QR"
                                            data-colors="{{ $row->Id }};{{ $row->InvoiceNumber }};{{ $row->ColorHex }};{{ $row->ColorText }};{{ $row->LotPlace }};{{ $row->Prefiks }};{{ $row->supply }};" 
                                            onclick="showBatchQRModal(this)">
                                            <i class="fas fa-qrcode"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                            @foreach($colorsOther as $row)
                                @php $i++; @endphp
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $row->Id }}</td>
                                    <td>{{ $row->Prefiks }}</td>
                                    <td>{{ $row->InvoiceNumber }}</td>
                                    <td>{{ $row->LotPlace }}</td>
                                    <td style="font-weight:bold; background-color: {{ $row->ColorHex }}; color: {{ $row->ColorText }}">
                                        {{ $row->Prefiks }}&nbsp;{{ $row->ColorText }}
                                    </td>
                                    <td style="font-weight:bold;">{{ $row->ColorHex }}</td>
                                    <td>{{ $row->supply_name }}</td>
                                    <td>{{ $row->total }}</td>
                                    <td style="width:150px;">
                                        @if($lot == 'super')
                                        <button type="button" class="btn btn-primary btn-sm" 
                                            data-colors="{{ $row->Id }};{{ $row->InvoiceNumber }};{{ $row->ColorHex }};{{ $row->ColorText }};{{ $row->LotPlace }};{{ $row->Prefiks }};{{ $row->supply }};" 
                                            onclick="toggleEditModal(this)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="deleteData('{{ $row->Id }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button type="button" class="btn btn-success btn-sm" title="Batch Print QR"
                                            data-colors="{{ $row->Id }};{{ $row->InvoiceNumber }};{{ $row->ColorHex }};{{ $row->ColorText }};{{ $row->LotPlace }};{{ $row->Prefiks }};{{ $row->supply }};" 
                                            onclick="showBatchQRModal(this)">
                                            <i class="fas fa-qrcode"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add -->
<div class="modal fade" id="masterdata" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="myFormId" onsubmit="event.preventDefault(); submitData();" autocomplete="off">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Input Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" name="Prefiks" class="form-control" id="Prefiks" placeholder="Enter Prefiks, Ex: DA,DS,RA" autofocus required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="InvoiceNumber" class="form-control" id="InvoiceNumber" placeholder="Enter Invoice No" required>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" id="LotPlace" name="LotPlace" required>
                            <option selected disabled value="">Choose LotNumber</option>
                            @if(in_array(session('permit'), ['7', 'super']))
                                <option value="7">7</option>
                            @endif
                            @if(in_array(session('permit'), ['206', 'super']))
                                <option value="206">206</option>
                                <option value="TURUNAN206">TURUNAN206</option>
                                <option value="REPACK">REPACK</option>
                            @endif
                            @if(in_array(session('permit'), ['Grace', 'GRACE', 'super']))
                                <option value="GRACE">GRACE</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" id="SupplyName" name="SupplyName" disabled>
                            <option selected value="">Default</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Background-Color</label>
                        <input type="color" name="ColorHex" class="form-control form-control-color w-100" id="ColorHex" value="#ffffff">
                    </div>
                    <div class="mb-3">
                        <label>ColorText</label>
                        <input type="color" name="ColorText" class="form-control form-control-color w-100" id="ColorText">
                        <input type="hidden" name="type" value="insert">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editdata" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="myFormId1" onsubmit="event.preventDefault(); editData();" autocomplete="off">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Prefiks</label>
                        <input type="text" name="Prefiks" class="form-control" id="editPrefiks" autofocus required>
                    </div>
                    <div class="mb-3">
                        <input type="hidden" name="Id" id="editId">
                        <label>Invoice Number</label>
                        <input type="text" name="InvoiceNumber" class="form-control" id="editInvoiceNumber" required>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" id="editLotPlace" name="LotPlace" required>
                            <option selected disabled value="">Choose LotNumber</option>
                            @if(in_array(session('permit'), ['7', 'super']))
                                <option value="7">7</option>
                            @endif
                            @if(in_array(session('permit'), ['206', 'super']))
                                <option value="206">206</option>
                                <option value="TURUNAN206">TURUNAN206</option>
                                <option value="REPACK">REPACK</option>
                            @endif
                            @if(in_array(session('permit'), ['Grace', 'GRACE', 'super']))
                                <option value="242">242</option>
                                <option value="GRACE">GRACE</option>
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <select class="form-select" id="editSupply" name="supplyEdit" required>
                            <option disabled value="">Choose Supplier</option>
                            @foreach($supplies as $supply)
                                <option value="{{ $supply->id }}">{{ $supply->id }} - {{ $supply->LotPlace }} - {{ $supply->supplier }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Background-Color</label>
                        <input type="color" name="ColorHex" class="form-control form-control-color w-100" id="editColorHex">
                    </div>
                    <div class="mb-3">
                        <label>ColorText</label>
                        <input type="color" name="ColorText" class="form-control form-control-color w-100" id="editColorText">
                        <input type="hidden" name="type" value="edit">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<form id="deleteForm" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#example').DataTable({
            stateSave: true,
            "ordering": true,
            "bPaginate": false,
            "aaSorting": []
        });
    });

    let addModal = new bootstrap.Modal(document.getElementById('masterdata'));
    let editModal = new bootstrap.Modal(document.getElementById('editdata'));

    function toggleAdd() {
        addModal.show();
    }

    function submitData() {
        let data = $('#myFormId').serialize();
        $.ajax({
            type: "POST",
            url: '{{ route('masterdata.store') }}',
            data: data,
            success: function(response) {
                if (response.status === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    });
                } else {
                    location.reload();
                }
            }
        });
    }

    function toggleEditModal(btn) {
        let data = $(btn).attr("data-colors").split(';');
        
        $("#editId").val(data[0]);
        $("#editInvoiceNumber").val(data[1]);
        $("#editColorHex").val(data[2]);
        $("#editColorText").val(data[3]);
        $("#editLotPlace").val(data[4]);
        $("#editPrefiks").val(data[5]);
        $("#editSupply").val(data[6]);
        
        editModal.show();
    }

    function editData() {
        let data = $('#myFormId1').serialize();
        $.ajax({
            type: "POST",
            url: '{{ route('masterdata.update') }}',
            data: data,
            success: function(response) {
                if (response.status === 'error') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: response.message,
                    });
                } else {
                    location.reload();
                }
            }
        });
    }

    function deleteData(id) {
        if(confirm('Are you sure you want to delete this invoice?')) {
            let form = document.getElementById('deleteForm');
            form.action = '/masterdata/' + id;
            form.submit();
        }
    }

    function showBatchQRModal(btn) {
        let data = $(btn).attr("data-colors").split(';');
        let id = data[0];
        let invoice = data[1];
        let prefiks = data[5];

        let htmlContent = `
            <div id="qr-input-container">
                <div class="input-group mb-2 qr-input-row">
                    <input type="text" class="form-control qr-pallet-input" placeholder="Nomor Pallet (contoh: 001)">
                    <button class="btn btn-success" type="button" onclick="addQrInputRow()"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        `;

        window.addQrInputRow = function() {
            let rowHtml = `
                <div class="input-group mb-2 qr-input-row">
                    <input type="text" class="form-control qr-pallet-input" placeholder="Nomor Pallet (contoh: 002)">
                    <button class="btn btn-danger" type="button" onclick="this.parentElement.remove()"><i class="fas fa-minus"></i></button>
                </div>
            `;
            $('#qr-input-container').append(rowHtml);
        };

        Swal.fire({
            title: 'Batch Print QR',
            html: htmlContent,
            showCancelButton: true,
            confirmButtonText: 'Print Batch',
            cancelButtonText: 'Batal',
            didOpen: () => {
                $('.qr-pallet-input').first().focus();
            },
            preConfirm: () => {
                let pallets = [];
                $('.qr-pallet-input').each(function() {
                    let val = $(this).val().trim();
                    if(val) pallets.push(val);
                });
                if(pallets.length === 0) {
                    Swal.showValidationMessage('Minimal masukkan 1 nomor pallet');
                    return false;
                }
                return pallets;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let pallets = result.value;
                printBatchQR(id, prefiks, invoice, pallets);
            }
        });
    }

    function printBatchQR(id, prefiks, invoice, pallets) {
        let printContent = `
            <html>
                <head>
                    <title>Batch Print QR</title>
                    <style>
                        body { text-align: center; font-family: sans-serif; padding: 0; margin: 0; }
                        .qr-page { 
                            page-break-after: always; 
                            display: flex;
                            flex-direction: column;
                            align-items: center;
                            justify-content: center;
                            height: 100vh;
                        }
                        img { width: 250px; height: 250px; margin-bottom: 15px; border: 1px solid #ddd; padding: 10px; }
                        h1 { margin: 10px 0 5px 0; font-size: 24px; }
                        p { margin: 0; font-size: 18px; color: #555; }
                        @media print {
                            .qr-page { height: 100vh; }
                        }
                    </style>
                </head>
                <body>
        `;

        pallets.forEach(nomor => {
            let qrText = id + " - " + nomor;
            let qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" + encodeURIComponent(qrText);
            
            printContent += `
                <div class="qr-page">
                    <img src="${qrUrl}" onload="window.qrImagesLoaded = (window.qrImagesLoaded || 0) + 1;">
                    <h1>${qrText}</h1>
                    <p>(${prefiks}) ${invoice} - Pallet: ${nomor}</p>
                </div>
            `;
        });

        printContent += `
                    <script>
                        // Wait for images to load before printing
                        let totalImages = ${pallets.length};
                        window.qrImagesLoaded = 0;
                        let checkLoad = setInterval(function() {
                            if (window.qrImagesLoaded >= totalImages) {
                                clearInterval(checkLoad);
                                window.print();
                                setTimeout(function(){ window.close(); }, 500);
                            }
                        }, 200);
                        
                        // Fallback in case image load fails
                        setTimeout(function() {
                            clearInterval(checkLoad);
                            window.print();
                            setTimeout(function(){ window.close(); }, 500);
                        }, 5000);
                    <\/script>
                </body>
            </html>
        `;

        let printWin = window.open('', '', 'width=800,height=600');
        printWin.document.write(printContent);
        printWin.document.close();
    }
</script>
@endpush
