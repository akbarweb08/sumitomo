<div class="modal fade" id="editcolor" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="myFormId1" autocomplete="off" method="POST" name="FormColorEdit">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Edit Data</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group mb-2">
            <label for="">Prefiks</label>
            <input type="text" name="Prefiks" class="form-control" id="editPrefiks" autofocus>
          </div>
          <div class="form-group mb-2">
            <input type="hidden" name="Id" class="form-control" id="editId">
            <label for="">Invoice Number</label>
            <input type="text" name="InvoiceNumber" class="form-control" id="editInvoiceNumber">
          </div>
          <div class="form-group mb-2">
            <label for="">Lot Place</label>
            <select class="form-control" id="editLotPlace" name="LotPlace">
              <option selected value="{{ $lotPlace }}">{{ $lotPlace }}</option>
            </select>
          </div>
          <div class="form-group mb-2">
            <label for="">From</label>
            <div class="d-flex justify-content-between align-items-center">
              <div style="width: 70%;">
                <select class="form-control" id="editSupply" name="SupplyName">
                  @foreach($modalSuppliers as $row)
                    <option value='{{ $row->id }}'>{{ $row->supplier == '' ? 'Default' : $row->supplier }}</option>
                  @endforeach
                </select>
              </div>
              <div style="width: 25%;">
                <a href="{{ route('mastersupplier.index') }}" class="btn btn-dark w-100">Add</a>
              </div>
            </div>
          </div>
          <div class="form-group mb-2">
            <label for="">Background-Color</label>
            <input type="color" name="ColorHex" class="form-control" id="editColorHex">
          </div>
          <div class="form-group mb-2">
            <label for="">ColorText</label>
            <input type="color" name="ColorText" class="form-control" id="editColorText">
            <input type="hidden" name="type" value="edit" class="form-control" id="Type">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="showQRModal()" class="btn btn-success me-auto" style="float: left;">Show QR</button>
          <button type="button" name="type" value="delete" onclick="deleteColor()" class="btn btn-danger">Delete Data</button>
          <button type="button" name="type" value="edit" onclick="colorEdit()" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </form>
  </div>
</div>

@if(session('role') == 'admin' || session('role') == 'super')
<div class="modal fade" id="colorData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form action="#" id="ColorForm" method="POST" autocomplete="off" name="FormColorInput">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Input Data</h5>
          <!--button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button-->
        </div>
        <div class="modal-body">
          <div class="form-group mb-2">
            <label>Prefiks</label>
            <input type="text" name="Prefiks" class="form-control" id="editPrefiks" placeholder="Enter Prefiks, Ex: DA,DS,RA" autofocus>
          </div>
          <div class="form-group mb-2">
            <label>Invoice Number</label>
            <input type="text" name="InvoiceNumber" class="form-control" id="editInvoiceNumber" placeholder="Enter Invoice No">
          </div>
          <div class="form-group mb-2">
            <label for="">Lot Place</label>
            <select class="form-control" id="editLotPlace" name="LotPlace">
              <option selected value="{{ $lotPlace }}">{{ $lotPlace }}</option>
            </select>
          </div>
          <div class="form-group mb-2">
            <label for="">From</label>
            <div class="d-flex justify-content-between align-items-center">
              <div style="width: 70%;">
                <select class="form-control" id="inputSupply" name="SupplyName">
                  <option selected value=''>Default</option>
                  @foreach($modalSuppliers as $row)
                    @if($row->supplier != '')
                      <option value='{{ $row->id }}'>{{ $row->supplier }}</option>
                    @endif
                  @endforeach
                </select>
              </div>
              <div style="width: 25%;">
                <a href="{{ route('mastersupplier.index') }}" class="btn btn-dark w-100">Add</a>
              </div>
            </div>
          </div>
          <div class="form-group mb-2">
            <label for="">Background-Color</label>
            <input type="color" name="ColorHex" class="form-control" id="editColorHex" value="#ffffff">
          </div>
          <div class="form-group mb-2">
            <label for="">ColorText</label>
            <input type="color" name="ColorText" class="form-control" id="editColorText" value="#000000">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" onclick="colorSave()" class="btn btn-primary">Save changes</button>
        </div>
      </div>
    </form>
  </div>
</div>
@endif

<div class="modal fade" id="modal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Input Data</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="myFormId" method="POST" autocomplete="off">
          @csrf
          <div class="modal-body">
            <div class="row">
              <div class="col-md-6 mb-2">
                <label class="form-label mb-1">Lot Place</label>
                <select class="form-select" id="inputLotPlace" name="LotPlace">
                  @foreach($availableLotPlaces ?? [] as $lp)
                    <option value="{{ $lp }}" {{ ($lotPlace ?? '') == $lp ? 'selected' : '' }}>{{ $lp }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6 mb-2">
                <label class="form-label mb-1">Lot Number</label>
                <select class="form-select" id="inputLotNumber" name="LotNumber">
                  @foreach($availableLotNumbers ?? [] as $ln)
                    <option value="{{ $ln }}" {{ ($lotNumber ?? '') == $ln ? 'selected' : '' }}>{{ $ln }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group mb-2">
              <input type="hidden" name="IdPallet" class="form-control" id="inputIdPallet">
              <label class="form-label mb-1">Box Number</label>
              <input type="text" name="BoxNumber" class="form-control" id="inputBoxNumber" readonly>
            </div>
            <div class="form-group mb-2">
                <label class="form-label mb-1">Scan Barcode</label>
                <input type="text" 
                    class="form-control" 
                    id="inputScan" 
                    placeholder="Scan Barcode (Format: ID - Nomor Pallet)" 
                    autofocus>
            </div>
            <div class="row">
              <div class="col-md-5 mb-2">
                <label class="form-label mb-1">Prefiks</label>
                <select class="form-select" id="inputScanPrefiks" name="Prefiks">
                  <option value="">-- Semua Prefiks --</option>
                  @foreach($availablePrefixes ?? [] as $p)
                    <option value="{{ $p }}">{{ $p }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-7 mb-2">
                <label class="form-label mb-1">Invoice Number</label>
                <select required 
                        class="form-select" 
                        name="ColorId" 
                        id="inputColorId" 
                        style="width: 100%;">
                  <option value="">Choose Invoice Number</option>
                  @foreach($modalColors as $row)
                    <option data-prefiks="{{ $row->Prefiks }}" style="background-color:{{ $row->ColorHex }};color: {{ $row->ColorText }};" value="{{ $row->Id }}" {{ ($lastInvoice == $row->Id) ? 'selected' : '' }}>
                      ({{ $row->Prefiks }}) {{ $row->InvoiceNumber }} - Total : {{ $row->total }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="form-group mb-2">
              <label class="form-label mb-1">Pallet Number</label>
              <input type="text" 
                     name="PalletNumber" 
                     required 
                     class="form-control" 
                     id="inputPalletNumber" 
                     placeholder="Enter Pallet No">
            </div>
            <i><div name="labelError" id="labelErrorMsg" style='color: red;'></div></i>
          </div>
          <div class="modal-footer d-flex flex-wrap justify-content-center">
            
            @if(auth()->user()->role === 'user' || auth()->user()->permit === 'all')
            <div class="btn-group m-1" role="group">
                <button type="button" id="deleteModal" onclick="deleteData(this)" class="btn btn-danger">Delete</button>
                <div class="btn-group" role="group">
                    <button id="btnGroupDropDelete" type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDropDelete">
                        <a class="dropdown-item" onclick="deleteGroup(this)" style="cursor: pointer;">Delete Group</a>
                        <a class="dropdown-item" onclick="deleteLine(this)" style="cursor: pointer;">Delete Line</a>
                        <a class="dropdown-item" onclick="deleteFront(this)" style="cursor: pointer;">Delete Front</a>
                        <a class="dropdown-item" onclick="deleteBack(this)" style="cursor: pointer;">Delete Back</a>
                    </div>
                </div>
            </div>

            <div class="btn-group m-1" role="group">
                <button type="button" id="returnModal" onclick="returnData(this)" class="btn btn-warning">Return</button>
                <div class="btn-group" role="group">
                    <button id="btnGroupDropReturn" type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDropReturn">
                        <a class="dropdown-item" onclick="returnGroup(this)" style="cursor: pointer;">Return Group</a>
                        <a class="dropdown-item" onclick="returnLine(this)" style="cursor: pointer;">Return Line</a>
                        <a class="dropdown-item" onclick="returnFront(this)" style="cursor: pointer;">Return Front</a>
                        <a class="dropdown-item" onclick="returnBack(this)" style="cursor: pointer;">Return Back</a>
                    </div>
                </div>
            </div>

            <button type="button" onclick="editData()" class="btn btn-secondary m-1" id="editButtonEdit">Edit</button>
            <button type="button" onclick="submitData()" class="btn btn-primary m-1" id="saveButtonSubmit">Save</button>
            @endif

            <div class="w-100 mt-2 mb-2"></div>

            <!-- Assign Driver Button -->
            @if(session('role') == 'admin' || session('role') == 'superadmin')
            <button type="button" onclick="openAssignDriverModal()" class="btn btn-warning m-1 text-dark">Assign Driver</button>
            @endif

            @if(auth()->user()->role === 'user' || auth()->user()->permit === 'all')
            <div class="btn-group m-1" role="group">
                <button type="button" onclick="moveDataa(this)" class="btn btn-info text-white">Move</button>
                <div class="btn-group" role="group">
                    <button id="btnGroupDropMove" type="button" class="btn btn-info text-white dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDropMove">
                        <a class="dropdown-item" onclick="moveGroupp(this)" style="cursor: pointer;">Move Group</a>
                        <a class="dropdown-item" onclick="moveLinee(this)" style="cursor: pointer;">Move Line</a>
                    </div>
                </div>
            </div>

            <div class="btn-group m-1" role="group">
                <button id="btnGroupDropColor" type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Color Action
                </button>
                <div class="dropdown-menu" aria-labelledby="btnGroupDropColor">
                    <h6 class="dropdown-header">Line</h6>
                    <a class="dropdown-item" onclick="lineColorr(this)" style="cursor: pointer;">Color Line</a>
                    <a class="dropdown-item" onclick="colorToFront(this)" style="cursor: pointer;">To Front</a>
                    <a class="dropdown-item" onclick="colorToBack(this)" style="cursor: pointer;">To Back</a>
                    <div class="dropdown-divider"></div>
                    <h6 class="dropdown-header">Group</h6>
                    <a class="dropdown-item" onclick="groupColorr(this)" style="cursor: pointer;">Color Group</a>
                    <a class="dropdown-item" onclick="groupToFront(this)" style="cursor: pointer;">To Front</a>
                    <a class="dropdown-item" onclick="groupToBack(this)" style="cursor: pointer;">To Back</a>
                </div>
            </div>
            @endif

          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Assign Driver Modal -->
<div class="modal fade" id="assignDriverModal" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1060;">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Assign Driver</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="assignDriverForm">
            <input type="hidden" name="boxNumber" id="assignBoxNumber">
            <input type="hidden" name="lotNumber" id="assignLotNumber">
            
            <div class="form-group mb-2">
                <label>Select Driver</label>
                <select class="form-control" name="driver_id" id="assignDriverId" required>
                    <option value="">Loading...</option>
                </select>
            </div>
            <div class="form-group mb-2">
                <label>Notes</label>
                <textarea class="form-control" name="note" id="assignNote" rows="3" placeholder="Masukkan instruksi..."></textarea>
            </div>
        </form>
      </div>
      <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
         <button type="button" class="btn btn-primary" onclick="submitAssignDriver()">Assign</button>
      </div>
    </div>
  </div>
</div>

<script>
    var lotNumber = "{{ $lotNumber }}";

    function deleteColor() {
        if(confirm("Delete this invoice?")) {
            var queryString = $('#myFormId1').serializeArray();
            $.ajax({
                type: "POST",
                url: '{{ route("sketch.deleteInvoice") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: queryString.find(q => q.name === 'Id').value
                },
                success: function(response) {
                    Swal.fire('Success', 'Invoice deleted', 'success').then(() => {
                        location.reload();
                    });
                },
                error: function(xhr, status, error) {
                    Swal.fire('Error!', 'Terjadi kesalahan: ' + error, 'error');
                }
            });
        }
    }

    function colorEdit() {
        var data = $('#myFormId1').serialize();
        $.ajax({
            type: "POST",
            url: '{{ route("sketch.editColor") }}',
            data: data,
            success: function(response) {
                if (response.status == 'error') {
                    Swal.fire('Oops...', response.message, 'error');
                } else {
                    Swal.fire('Success', 'Color updated', 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error!', 'Terjadi kesalahan: ' + error, 'error');
            }
        });
    }

    function colorSave() {
        var data = $('#ColorForm').serialize();
        $.ajax({
            type: "POST",
            url: '{{ route("sketch.saveColor") }}',
            data: data,
            success: function(response) {
                if (response.status == 'error') {
                    Swal.fire('Oops...', response.message, 'error');
                } else {
                    Swal.fire('Success', 'Color saved', 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error!', 'Terjadi kesalahan: ' + error, 'error');
            }
        });
    }

    function showQRModal() {
        var id = $("#editId").val();
        var prefiks = $("#editPrefiks").val();
        var invoice = $("#editInvoiceNumber").val();
        

        
        // Menutup modal bootstrap agar input pada SweetAlert bisa di-klik (menghindari focus trap)
        $('#editcolor').modal('hide');
        
        Swal.fire({
            title: 'Masukkan Nomor Pallet',
            text: 'QR Code akan digenerate dengan format: ID - Nomor Pallet',
            input: 'text',
            inputPlaceholder: 'Contoh: 001',
            showCancelButton: true,
            confirmButtonText: 'Generate QR',
            cancelButtonText: 'Batal',
            preConfirm: (nomor) => {
                if(!nomor) {
                    Swal.showValidationMessage('Nomor pallet tidak boleh kosong');
                }
                return nomor;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                var nomor = result.value;
                var qrText = id + " - " + nomor;
                var displayText = `(${prefiks}) ${invoice} <br> Pallet: ${nomor}`;
                var qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" + encodeURIComponent(qrText);
                
                Swal.fire({
                    title: 'Print QR Code',
                    html: `<div style="text-align:center;">
                              <img src="${qrUrl}" alt="QR Code" style="margin-bottom:15px; border: 1px solid #ddd; padding: 10px;">
                              <h5 style="margin: 0; font-weight: bold;">${qrText}</h5>
                              <p style="margin: 0;">${displayText}</p>
                           </div>`,
                    showCancelButton: true,
                    confirmButtonText: 'Print',
                    cancelButtonText: 'Tutup'
                }).then((printResult) => {
                    if (printResult.isConfirmed) {
                        var printWin = window.open('', '', 'width=600,height=600');
                        printWin.document.write(`
                            <html>
                                <head>
                                    <title>Print QR - ${qrText}</title>
                                    <style>
                                        body { text-align: center; font-family: sans-serif; padding-top: 50px; }
                                        img { width: 250px; height: 250px; }
                                        h1 { margin: 10px 0 5px 0; font-size: 24px; }
                                        p { margin: 0; font-size: 18px; color: #555; }
                                    </style>
                                </head>
                                <body>
                                    <img src="${qrUrl}">
                                    <h1>${qrText}</h1>
                                    <p>(${prefiks}) ${invoice} - Pallet: ${nomor}</p>
                                    <script>
                                        window.onload = function() { 
                                            window.print(); 
                                            setTimeout(function(){ window.close(); }, 500);
                                        }
                                    <\/script>
                                </body>
                            </html>
                        `);
                        printWin.document.close();
                    }
                });
            }
        });
    }

    function toggleColorInput() {
        $('#colorData').modal('toggle');
    }

    function toggleFromSupply(event) {
        var splitted = $(event).attr("supplier").split(';');
        if (splitted[1] === "") {
            splitted[0] = "";
        }
        $("#inputSupply").val(splitted[0]);
        $('#colorData').modal('toggle');
    }

    function toggleColor(event) {
        $("#inputLotNumber").val(lotNumber);
        $("#inputBoxNumber").val($(event).attr("data-boxnumber"));
        var splitted = $(event).attr("data-colors").split(';');
        $("#editId").val(splitted[0]);
        $("#editInvoiceNumber").val(splitted[1]).change();
        $("#editColorHex").val(splitted[2]);
        $("#editColorText").val(splitted[3]);
        $("#editLotPlace").val(splitted[4]);
        $("#editPrefiks").val(splitted[5]);
        $("#editSupply").val(splitted[6]);
        $('#editcolor').modal('toggle');
    }

    function toggleModal(event, type = 'input') {
        var dataPallets = $(event).attr("data-pallets");
        if(dataPallets) {
            var splitted = dataPallets.split(';');
            $("#inputIdPallet").val(splitted[0]);
            var colId = splitted[2] ? splitted[2] : 1;
            $("#inputColorId").val(colId);
            $("#inputPalletNumber").val(splitted[3]);
            $("#inputLotNumber").val(lotNumber);
            $("#inputBoxNumber").val(splitted[4]);
            if (typeof lotPlace !== 'undefined' && lotPlace) {
                $("#inputLotPlace").val(lotPlace);
            }
            var optPref = $("#inputColorId option:selected").data('prefiks');
            if (optPref) {
                $("#inputScanPrefiks").val(optPref);
            }
        } else {
            $("#inputIdPallet").val("");
            $("#inputColorId").val(1);
            $("#inputPalletNumber").val("");
            $("#inputLotNumber").val(lotNumber);
            $("#inputBoxNumber").val($(event).attr("data-boxnumber"));
            if (typeof lotPlace !== 'undefined' && lotPlace) {
                $("#inputLotPlace").val(lotPlace);
            }
            $("#inputScanPrefiks").val("");
        }
        $("#inputColorId option").show();
        $("#inputScan").val("");
        $('#modal2').modal('toggle');
        setTimeout(function() {
            $('#inputScan').focus();
        }, 500);
    }

    $('#inputScanPrefiks').on('change', function() {
        var selectedPref = $(this).val();
        $('#inputColorId option').each(function() {
            if (!$(this).val()) {
                $(this).show();
                return;
            }
            var pref = $(this).data('prefiks');
            if (!selectedPref || pref == selectedPref) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        // If current selection is hidden, select empty or first visible
        if (selectedPref && $('#inputColorId option:selected').data('prefiks') !== selectedPref) {
            var firstVisible = $('#inputColorId option').filter(function() {
                return $(this).css('display') !== 'none' && $(this).val() !== '';
            }).first().val();
            if (firstVisible) {
                $('#inputColorId').val(firstVisible);
            }
        }
    });

    $('#inputColorId').on('change', function() {
        var optPref = $(this).find('option:selected').data('prefiks');
        if (optPref) {
            $('#inputScanPrefiks').val(optPref);
        }
    });

    $('#inputPalletNumber').on('keypress', function (e) {
        if(e.which === 13){
            e.preventDefault();
            submitData();
        }
    });

    $('#inputScan').on('keypress', function (e) {
        if(e.which === 13){
            e.preventDefault();
            var scanText = $(this).val();
            if(scanText) {
                var parts = scanText.split('-');
                if(parts.length >= 2) {
                    var idPart = parts[0].trim();
                    var nomorPart = parts.slice(1).join('-').trim();
                    
                    // Validasi: cek apakah idPart ada di window.perLotColors
                    var foundInvoice = window.perLotColors.find(function(c) {
                        return (c.id == idPart) || (c.Id == idPart);
                    });

                    if (foundInvoice) {
                        var targetId = String(foundInvoice.id !== undefined ? foundInvoice.id : foundInvoice.Id);

                        // 1. Set value native HTML select
                        $('#inputColorId').val(targetId);

                        // 2. Jika menggunakan library Select2 / Bootstrap-Select, paksa refresh tampilan
                        if ($.fn.select2 && $('#inputColorId').data('select2')) {
                            $('#inputColorId').val(targetId).trigger('change.select2');
                        } else {
                            $('#inputColorId').trigger('change');
                        }

                        // 3. Fallback: Paksa ubah attribute selected pada option secara langsung
                        $('#inputColorId option').prop('selected', false);
                        $('#inputColorId option[value="' + targetId + '"]').prop('selected', true);

                        // Sync combo box prefiks
                        if (foundInvoice.prefiks || foundInvoice.Prefiks) {
                            $('#inputScanPrefiks').val(foundInvoice.prefiks || foundInvoice.Prefiks);
                        }

                        // Isi nomor pallet dan reset input scanner
                        $('#inputPalletNumber').val(nomorPart);
                        $(this).val('');
                    }
                    else {
                        Swal.fire({
                            title: 'Akses Ditolak',
                            text: 'ID Invoice ' + idPart + ' tidak ditemukan di lot ini. Pastikan Anda men-scan QR dari lot yang sesuai.',
                            icon: 'error'
                        });
                        $(this).val(''); // Reset input
                    }
                } else {
                    Swal.fire('Error', 'Format barcode tidak valid. Gunakan format ID - Nomor Pallet (contoh: 25 - 001)', 'error');
                }
            }
        }
    });

    function doPalletAction(type) {
        var data = $('#myFormId').serialize() + '&type=' + type;
        $.ajax({
            type: "POST",
            url: '{{ route("sketch.palletAction") }}',
            data: data,
            success: function(response) {
                if (response.status == 'error') {
                    Swal.fire('Oops...', response.message, 'error');
                } else {
                    Swal.fire('Success', 'Pallet action saved', 'success').then(() => {
                        location.reload();
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire('Error AJAX!', 'Terjadi kesalahan: ' + error + '\nDetail: ' + xhr.responseText.substring(0, 100), 'error');
            }
        });
    }

    function submitData() { doPalletAction('save'); }
    function editData() { doPalletAction('edit'); }
    function deleteData() { doPalletAction('delete'); }
    function deleteGroup() { doPalletAction('deleteGroup'); }
    function deleteLine() { doPalletAction('deleteLine'); }
    function deleteFront() { doPalletAction('deleteFront'); }
    function deleteBack() { doPalletAction('deleteBack'); }
    function returnData() { doPalletAction('return'); }
    function returnGroup() { doPalletAction('returnGroup'); }
    function returnLine() { doPalletAction('returnLine'); }
    function returnFront() { doPalletAction('returnFront'); }
    function returnBack() { doPalletAction('returnBack'); }
    function groupColorr() { doPalletAction('groupcolor'); }
    function groupToFront() { doPalletAction('groupFront'); }
    function groupToBack() { doPalletAction('groupBack'); }
    function lineColorr() { doPalletAction('color'); }
    function colorToFront() { doPalletAction('colorFront'); }
    function colorToBack() { doPalletAction('colorBack'); }

    function moveDataa() {
        var id = $("#inputIdPallet").val();
        if(id) {
            window.location.href = window.location.pathname + '?id=' + id + '&type=move';
        }
    }
    
    function moveGroupp() {
        var id = $("#inputIdPallet").val();
        if(id) {
            window.location.href = window.location.pathname + '?id=' + id + '&type=moveGroup';
        }
    }

    function moveLinee() {
        var id = $("#inputIdPallet").val();
        if(id) {
            window.location.href = window.location.pathname + '?id=' + id + '&type=moveLine';
        }
    }

    function openAssignDriverModal() {
        var box = $("#inputBoxNumber").val();
        var lot = $("#inputLotNumber").val();
        
        $("#assignBoxNumber").val(box);
        $("#assignLotNumber").val(lot);
        $("#assignNote").val("Tolong periksa box " + box + " di lot " + lot + ".");
        
        // Fetch drivers
        $.get('{{ route("driver.fetch") }}', function(data) {
            var options = '<option value="">Pilih Driver...</option>';
            data.forEach(function(driver) {
                options += '<option value="'+driver.id+'">'+driver.name+' ('+driver.role+')</option>';
            });
            $("#assignDriverId").html(options);
            
            // hide modal2 temporarily if needed, but easier is just show on top
            $('#assignDriverModal').modal('show');
        });
    }

    function submitAssignDriver() {
        var driver_id = $("#assignDriverId").val();
        var note = $("#assignNote").val();
        
        if(!driver_id) {
            Swal.fire('Error', 'Silakan pilih driver terlebih dahulu', 'error');
            return;
        }
        
        $.ajax({
            type: "POST",
            url: '{{ route("driver.assign") }}',
            data: {
                _token: '{{ csrf_token() }}',
                driver_id: driver_id,
                note: note
            },
            success: function(res) {
                $('#assignDriverModal').modal('hide');
                Swal.fire('Success', res.message, 'success');
            },
            error: function(err) {
                var msg = (err.responseJSON && err.responseJSON.message) ? err.responseJSON.message : 'Gagal assign driver';
                Swal.fire('Peringatan', msg, 'warning');
            }
        });
    }

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    window.perLotColors = {!! json_encode($modalColors->map(function($c) {
        return [
            'Id' => $c->Id,
            'Prefiks' => $c->Prefiks,
            'InvoiceNumber' => $c->InvoiceNumber,
            'LotPlace' => $c->LotPlace
        ];
    })->values()) !!};

    function findMatchingColor(colorList, invInput) {
        if (!invInput) return null;
        let target = invInput.toString().trim().toLowerCase();
        
        // 1. Direct match with InvoiceNumber
        let match = colorList.find(c => c.InvoiceNumber && c.InvoiceNumber.toString().trim().toLowerCase() === target);
        if (match) return match;
        
        // 2. Cleaned match (ignoring symbols)
        let cleanTarget = target.replace(/[^a-z0-9]/g, '');
        if (cleanTarget) {
            match = colorList.find(c => {
                if (!c.InvoiceNumber) return false;
                let cleanInv = c.InvoiceNumber.toString().trim().toLowerCase().replace(/[^a-z0-9]/g, '');
                return cleanInv === cleanTarget;
            });
            if (match) return match;
        }
        
        // 3. Prefiks + InvoiceNumber combinations
        match = colorList.find(c => {
            if (!c.InvoiceNumber) return false;
            let prefiks = c.Prefiks ? c.Prefiks.toString().trim().toLowerCase() : '';
            let inv = c.InvoiceNumber.toString().trim().toLowerCase();
            
            let combo1 = (prefiks + inv).replace(/[^a-z0-9]/g, '');
            let combo2 = (prefiks + " " + inv).toLowerCase();
            let combo3 = (`(${prefiks}) ${inv}`).toLowerCase();
            
            return combo1 === cleanTarget || combo2 === target || combo3 === target;
        });
        if (match) return match;
        
        // 4. Numeric match (e.g. 00123 -> 123)
        let numTarget = parseInt(target, 10);
        if (!isNaN(numTarget)) {
            match = colorList.find(c => {
                if (!c.InvoiceNumber) return false;
                let numInv = parseInt(c.InvoiceNumber, 10);
                return !isNaN(numInv) && numInv === numTarget;
            });
        }
        
        return match;
    }

    function showPerLotBatchQRModal() {
        let lotName = '{{ $lotPlace }}';
        let htmlContent = `
            <div id="perlot-qr-container">
                <div class="mb-3">
                    <label for="perlot-import-qr" class="form-label text-start d-block" style="font-weight:bold; font-size: 14px;">Import dari CSV / Excel (Lot ${lotName})</label>
                    <div class="d-flex align-items-center">
                        <input type="file" id="perlot-import-qr" class="form-control" accept=".csv, .xlsx, .xls">
                    </div>
                    <small class="text-muted d-block text-start mt-2">
                        Format file (2 Kolom):<br>
                        <b>Kolom A:</b> Invoice Number<br>
                        <b>Kolom B:</b> Nomor Pallet<br>
                        <i>Sistem otomatis mencari di dalam Lot ${lotName}</i><br>
                        <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="downloadPerLotTemplate()"><i class="fas fa-download"></i> Download Template Excel</button>
                    </small>
                </div>
                <div id="perlot-qr-results" class="text-start mt-3" style="max-height: 200px; overflow-y: auto;">
                </div>
            </div>
        `;

        window.downloadPerLotTemplate = function() {
            let wb = XLSX.utils.book_new();
            let ws_data = [
                ["Invoice Number", "Nomor Pallet"],
                ["INV-12345", "001"],
                ["INV-12345", "002"]
            ];
            let ws = XLSX.utils.aoa_to_sheet(ws_data);
            XLSX.utils.book_append_sheet(wb, ws, "Template");
            XLSX.writeFile(wb, "Template_PerLot_Batch_QR.xlsx");
        };

        Swal.fire({
            title: 'Batch Print QR (Excel)',
            html: htmlContent,
            showCancelButton: true,
            confirmButtonText: 'Print Batch',
            cancelButtonText: 'Batal',
            didOpen: () => {
                document.getElementById('perlot-import-qr').addEventListener('change', function(e) {
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
                                if(row.length >= 2 && row[0] != null && row[1] != null) {
                                    let inv = row[0].toString().trim();
                                    let pallet = row[1].toString().trim();
                                    
                                    // Skip header row if detected
                                    if(index === 0 && inv.toLowerCase().includes('invoice')) {
                                        return;
                                    }

                                    if(inv && pallet) {
                                        let foundColor = findMatchingColor(window.perLotColors, inv);
                                        if(foundColor) {
                                            validItems.push({
                                                id: foundColor.Id,
                                                prefiks: foundColor.Prefiks,
                                                invoice: foundColor.InvoiceNumber,
                                                lot: foundColor.LotPlace,
                                                pallet: pallet
                                            });
                                        } else {
                                            errors.push(`Baris ${index+1}: Inv ${inv} tidak ditemukan di lot ini.`);
                                        }
                                    }
                                }
                            });
                            
                            window.perLotQrItemsToPrint = validItems;
                            
                            let resHtml = `<b>Berhasil dicocokkan: ${validItems.length} Pallet.</b><br>`;
                            if(errors.length > 0) {
                                resHtml += `<small>` + errors.slice(0, 5).join('<br>') + (errors.length > 5 ? '<br>...' : '') + `</small>`;
                            }
                            document.getElementById('perlot-qr-results').innerHTML = resHtml;
                            
                        } catch (error) {
                            document.getElementById('perlot-qr-results').innerHTML = `<span class="text-danger">Gagal membaca file.</span>`;
                        }
                    };
                    reader.readAsArrayBuffer(file);
                });
            },
            preConfirm: () => {
                if(!window.perLotQrItemsToPrint || window.perLotQrItemsToPrint.length === 0) {
                    Swal.showValidationMessage('Tidak ada data valid untuk di-print.');
                    return false;
                }
                return window.perLotQrItemsToPrint;
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let items = result.value;
                printPerLotBatchQR(items);
            }
        });
    }

    function printPerLotBatchQR(items) {
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
