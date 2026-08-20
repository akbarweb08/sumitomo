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
                    <button type="button" class="btn btn-primary me-2 text-white" onclick="showGlobalBatchQRModal()"><i class="fas fa-qrcode"></i> Global Batch QR</button>
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
<script>
    window.allMasterDataColors = {!! json_encode(collect($colors)->merge($colorsOther ?? [])->map(function($c) {
        return [
            'Id' => $c->Id,
            'Prefiks' => $c->Prefiks,
            'InvoiceNumber' => $c->InvoiceNumber,
            'LotPlace' => $c->LotPlace
        ];
    })->values()) !!};
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
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
                <div class="mb-3">
                    <label for="import-qr-file" class="form-label text-start d-block" style="font-weight:bold; font-size: 14px;">Import dari CSV / Excel</label>
                    <div class="d-flex align-items-center">
                        <input type="file" id="import-qr-file" class="form-control" accept=".csv, .xlsx, .xls">
                        <button class="btn btn-info ms-2 text-white" type="button" onclick="processImportQR()"><i class="fas fa-file-import"></i> Import</button>
                    </div>
                    <small class="text-muted d-block text-start mt-1">Pastikan data nomor pallet ada di <b>kolom pertama (Kolom A)</b>.</small>
                </div>
                <hr>
                <div class="input-group mb-2 qr-input-row">
                    <input type="text" class="form-control qr-pallet-input" placeholder="Nomor Pallet (contoh: 001)">
                    <button class="btn btn-success" type="button" onclick="addQrInputRow()"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        `;

        window.processImportQR = function() {
            let fileInput = document.getElementById('import-qr-file');
            if(!fileInput.files.length) {
                Swal.showValidationMessage('Pilih file terlebih dahulu');
                return;
            }
            let file = fileInput.files[0];
            let reader = new FileReader();
            reader.onload = function(e) {
                try {
                    let data = new Uint8Array(e.target.result);
                    let workbook = XLSX.read(data, {type: 'array'});
                    let firstSheetName = workbook.SheetNames[0];
                    let worksheet = workbook.Sheets[firstSheetName];
                    let excelData = XLSX.utils.sheet_to_json(worksheet, {header: 1});
                    
                    $('#qr-input-container .qr-input-row').remove();

                    let added = 0;
                    excelData.forEach(function(row) {
                        if(row.length > 0 && row[0] != null) {
                            let val = row[0].toString().trim();
                            if(val) {
                                let rowHtml = `
                                    <div class="input-group mb-2 qr-input-row">
                                        <input type="text" class="form-control qr-pallet-input" value="${val}">
                                        <button class="btn btn-danger" type="button" onclick="this.parentElement.remove()"><i class="fas fa-minus"></i></button>
                                    </div>
                                `;
                                $('#qr-input-container').append(rowHtml);
                                added++;
                            }
                        }
                    });
                    
                    let addRowHtml = `
                        <div class="input-group mb-2 qr-input-row">
                            <input type="text" class="form-control qr-pallet-input" placeholder="Nomor Pallet">
                            <button class="btn btn-success" type="button" onclick="addQrInputRow()"><i class="fas fa-plus"></i></button>
                        </div>
                    `;
                    $('#qr-input-container').append(addRowHtml);
                    
                    if(added > 0) {
                        Swal.resetValidationMessage();
                        // Reset file input
                        fileInput.value = '';
                    } else {
                        Swal.showValidationMessage('Tidak ada data yang ditemukan di kolom pertama.');
                    }
                } catch (error) {
                    Swal.showValidationMessage('Gagal membaca file. Pastikan format benar.');
                }
            };
            reader.readAsArrayBuffer(file);
        };

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
                        body { font-family: Arial, sans-serif; padding: 15px; margin: 0; background: #fff; text-align: center; }
                        .qr-grid { display: flex; flex-wrap: wrap; gap: 15px; justify-content: flex-start; }
                        .qr-card { 
                            width: calc(33.333% - 10px); 
                            box-sizing: border-box; 
                            border: 1px dashed #666; 
                            border-radius: 6px; 
                            padding: 10px; 
                            text-align: center; 
                            page-break-inside: avoid; 
                            break-inside: avoid;
                            margin-bottom: 10px;
                        }
                        .qr-card img { width: 150px; height: 150px; margin-bottom: 5px; }
                        .qr-card h2 { margin: 4px 0; font-size: 16px; font-weight: bold; }
                        .qr-card p { margin: 2px 0; font-size: 12px; color: #444; }
                        @media print {
                            body { padding: 0; }
                            .qr-card { page-break-inside: avoid; break-inside: avoid; }
                        }
                    </style>
                </head>
                <body>
                    <div class="qr-grid">
        `;

        pallets.forEach(nomor => {
            let qrText = id + " - " + nomor;
            let qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" + encodeURIComponent(qrText);
            
            printContent += `
                <div class="qr-card">
                    <img src="${qrUrl}" onload="window.qrImagesLoaded = (window.qrImagesLoaded || 0) + 1;">
                    <h2>${qrText}</h2>
                    <p>(${prefiks}) ${invoice} - Pallet: ${nomor}</p>
                </div>
            `;
        });

        printContent += `
                    </div>
                    <script>
                        let hasPrinted = false;
                        function triggerPrint() {
                            if (hasPrinted) return;
                            hasPrinted = true;
                            if (window.checkLoad) clearInterval(window.checkLoad);
                            if (window.fallbackTimer) clearTimeout(window.fallbackTimer);
                            window.print();
                            setTimeout(function() { window.close(); }, 500);
                        }

                        let totalImages = ${pallets.length};
                        window.qrImagesLoaded = 0;
                        
                        window.checkLoad = setInterval(function() {
                            if (window.qrImagesLoaded >= totalImages) {
                                triggerPrint();
                            }
                        }, 200);
                        
                        window.fallbackTimer = setTimeout(function() {
                            triggerPrint();
                        }, 4000);
                    <\/script>
                </body>
            </html>
        `;

        let printWin = window.open('', '', 'width=800,height=600');
        printWin.document.write(printContent);
        printWin.document.close();
    }

    function showGlobalBatchQRModal() {
        let htmlContent = `
            <div id="global-qr-container">
                <div class="mb-3">
                    <label for="global-import-qr" class="form-label text-start d-block" style="font-weight:bold; font-size: 14px;">Import dari CSV / Excel (Global)</label>
                    <div class="d-flex align-items-center">
                        <input type="file" id="global-import-qr" class="form-control" accept=".csv, .xlsx, .xls">
                    </div>
                    <small class="text-muted d-block text-start mt-2">
                        Format file (3 Kolom):<br>
                        <b>Kolom A:</b> Lot Place (Contoh: 7, 206, GRACE)<br>
                        <b>Kolom B:</b> Invoice Number<br>
                        <b>Kolom C:</b> Nomor Pallet<br>
                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="downloadGlobalTemplate()"><i class="fas fa-download"></i> Download Template Excel</button>
                    </small>
                </div>
                <div id="global-qr-results" class="text-start mt-3" style="max-height: 200px; overflow-y: auto;">
                </div>
            </div>
        `;

        window.downloadGlobalTemplate = function() {
            let wb = XLSX.utils.book_new();
            let ws_data = [
                ["Lot Place", "Invoice Number", "Nomor Pallet"],
                ["7", "INV-12345", "001"],
                ["206", "INV-67890", "002"]
            ];
            let ws = XLSX.utils.aoa_to_sheet(ws_data);
            XLSX.utils.book_append_sheet(wb, ws, "Template");
            XLSX.writeFile(wb, "Template_Global_Batch_QR.xlsx");
        };

        Swal.fire({
            title: 'Global Batch Print QR',
            html: htmlContent,
            showCancelButton: true,
            confirmButtonText: 'Print Batch',
            cancelButtonText: 'Batal',
            didOpen: () => {
                document.getElementById('global-import-qr').addEventListener('change', function(e) {
                    let file = e.target.files[0];
                    if(!file) return;
                    
                    let reader = new FileReader();
                    reader.onload = function(e) {
                        try {
                            let data = new Uint8Array(e.target.result);
                            let workbook = XLSX.read(data, {type: 'array'});
                            let firstSheetName = workbook.SheetNames[0];
                            let worksheet = workbook.Sheets[firstSheetName];
                            let excelData = XLSX.utils.sheet_to_json(worksheet, {header: 1});
                            
                            let validItems = [];
                            let errors = [];
                            
                            excelData.forEach(function(row, index) {
                                if(row.length >= 3 && row[0] != null && row[1] != null && row[2] != null) {
                                    let lot = row[0].toString().trim();
                                    let inv = row[1].toString().trim();
                                    let pallet = row[2].toString().trim();
                                    
                                    if(lot && inv && pallet) {
                                        let foundColor = window.allMasterDataColors.find(c => c.LotPlace == lot && c.InvoiceNumber == inv);
                                        if(foundColor) {
                                            validItems.push({
                                                id: foundColor.Id,
                                                prefiks: foundColor.Prefiks,
                                                invoice: foundColor.InvoiceNumber,
                                                lot: foundColor.LotPlace,
                                                pallet: pallet
                                            });
                                        } else {
                                            errors.push(`Baris ${index+1}: Lot ${lot}, Inv ${inv} tidak ditemukan.`);
                                        }
                                    }
                                }
                            });
                            
                            window.globalQrItemsToPrint = validItems;
                            
                            let resHtml = `<b>Berhasil dicocokkan: ${validItems.length} Pallet.</b><br>`;
                            if(errors.length > 0) {
                                resHtml += `<span class="text-danger">Ada ${errors.length} baris tidak valid/ditemukan.</span><br>`;
                                resHtml += `<small>` + errors.slice(0, 5).join('<br>') + (errors.length > 5 ? '<br>...' : '') + `</small>`;
                            }
                            document.getElementById('global-qr-results').innerHTML = resHtml;
                            
                        } catch (error) {
                            document.getElementById('global-qr-results').innerHTML = `<span class="text-danger">Gagal membaca file.</span>`;
                        }
                    };
                    reader.readAsArrayBuffer(file);
                });
            },
            preConfirm: () => {
                if(!window.globalQrItemsToPrint || window.globalQrItemsToPrint.length === 0) {
                    Swal.showValidationMessage('Tidak ada data valid untuk di-print.');
                    return false;
                }
                return window.globalQrItemsToPrint;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let items = result.value;
                printGlobalBatchQR(items);
            }
        });
    }

    function printGlobalBatchQR(items) {
        let printContent = `
            <html>
                <head>
                    <title>Global Batch Print QR</title>
                    <style>
                        body { font-family: Arial, sans-serif; padding: 15px; margin: 0; background: #fff; text-align: center; }
                        .qr-grid { display: flex; flex-wrap: wrap; gap: 15px; justify-content: flex-start; }
                        .qr-card { 
                            width: calc(33.333% - 10px); 
                            box-sizing: border-box; 
                            border: 1px dashed #666; 
                            border-radius: 6px; 
                            padding: 10px; 
                            text-align: center; 
                            page-break-inside: avoid; 
                            break-inside: avoid;
                            margin-bottom: 10px;
                        }
                        .qr-card img { width: 150px; height: 150px; margin-bottom: 5px; }
                        .qr-card h2 { margin: 4px 0; font-size: 16px; font-weight: bold; }
                        .qr-card p { margin: 2px 0; font-size: 12px; color: #444; }
                        @media print {
                            body { padding: 0; }
                            .qr-card { page-break-inside: avoid; break-inside: avoid; }
                        }
                    </style>
                </head>
                <body>
                    <div class="qr-grid">
        `;

        items.forEach(item => {
            let qrText = item.id + " - " + item.pallet;
            let qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" + encodeURIComponent(qrText);
            
            printContent += `
                <div class="qr-card">
                    <img src="${qrUrl}" onload="window.qrImagesLoaded = (window.qrImagesLoaded || 0) + 1;">
                    <h2>${qrText}</h2>
                    <p>(${item.prefiks}) ${item.invoice} - Pallet: ${item.pallet}</p>
                </div>
            `;
        });

        printContent += `
                    </div>
                    <script>
                        let hasPrinted = false;
                        function triggerPrint() {
                            if (hasPrinted) return;
                            hasPrinted = true;
                            if (window.checkLoad) clearInterval(window.checkLoad);
                            if (window.fallbackTimer) clearTimeout(window.fallbackTimer);
                            window.print();
                            setTimeout(function() { window.close(); }, 500);
                        }

                        let totalImages = ${items.length};
                        window.qrImagesLoaded = 0;
                        
                        window.checkLoad = setInterval(function() {
                            if (window.qrImagesLoaded >= totalImages) {
                                triggerPrint();
                            }
                        }, 200);
                        
                        window.fallbackTimer = setTimeout(function() {
                            triggerPrint();
                        }, 4000);
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
