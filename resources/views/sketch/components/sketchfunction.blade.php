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
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
            <div class="form-group mb-2">
              <input type="hidden" name="IdPallet" class="form-control" id="inputIdPallet">
              <input type="text" name="LotNumber" class="form-control" id="inputLotNumber" readonly>
            </div>
            <div class="form-group mb-2">
              <input type="text" name="BoxNumber" class="form-control" id="inputBoxNumber" readonly>
            </div>
            <div class="form-group mb-2">
              <input type="text" class="form-control" id="inputScan" placeholder="Scan Barcode (Format: ID - Nomor Pallet)" autofocus>
            </div>
            <div class="form-group mb-2">
              <select required class="form-control" name="ColorId" id="inputColorId" style="width: 100%;">
                <option value="1">Choose Invoice Number</option>
                @foreach($modalColors as $row)
                  <option style="background-color:{{ $row->ColorHex }};color: {{ $row->ColorText }};" value="{{ $row->Id }}" {{ ($lastInvoice == $row->Id) ? 'selected' : '' }}>
                    ({{ $row->Prefiks }}) {{ $row->InvoiceNumber }} - Total : {{ $row->total }}
                  </option>
                @endforeach
              </select>
            </div>
            <div class="form-group mb-2">
              <input type="text" name="PalletNumber" required class="form-control" id="inputPalletNumber" placeholder="Enter Pallet No">
            </div>
            <i><div name="labelError" id="labelErrorMsg" style='color: red;'></div></i>
          </div>
          <div class="modal-footer d-flex flex-wrap justify-content-center">
            
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
            <button type="button" onclick="submitData()" class="btn btn-primary m-1">Save</button>

            <div class="w-100 mt-2 mb-2"></div>

            <button type="button" onclick="moveDataa(this)" class="btn btn-info m-1 text-white">Move</button>

            <!-- Assign Driver Button -->
            @if(session('role') == 'admin' || session('role') == 'superadmin')
            <button type="button" onclick="openAssignDriverModal()" class="btn btn-warning m-1 text-dark">Assign Driver</button>
            @endif

            <div class="btn-group m-1" role="group">
                <button type="button" onclick="moveGroupp(this)" class="btn btn-info text-white">Mv. Group</button>
                <div class="btn-group" role="group">
                    <button id="btnGroupDropMove" type="button" class="btn btn-info text-white dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDropMove">
                        <a class="dropdown-item" onclick="moveLinee(this)" style="cursor: pointer;">Move Line</a>
                    </div>
                </div>
            </div>

            <div class="btn-group m-1" role="group">
                <button type="button" onclick="lineColorr(this)" class="btn btn-success">C. Line</button>
                <div class="btn-group" role="group">
                    <button id="btnGroupDropColor" type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDropColor">
                        <a class="dropdown-item" onclick="colorToFront(this)" style="cursor: pointer;">To Front</a>
                        <a class="dropdown-item" onclick="colorToBack(this)" style="cursor: pointer;">To Back</a>
                    </div>
                </div>
            </div>

            <div class="btn-group m-1" role="group">
                <button type="button" onclick="groupColorr(this)" class="btn btn-success">C. Group</button>
                <div class="btn-group" role="group">
                    <button id="btnGroupDropGroup" type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupDropGroup">
                        <a class="dropdown-item" onclick="groupToFront(this)" style="cursor: pointer;">To Front</a>
                        <a class="dropdown-item" onclick="groupToBack(this)" style="cursor: pointer;">To Back</a>
                    </div>
                </div>
            </div>

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
        
        if(!id) {
            Swal.fire('Error', 'ID Invoice tidak ditemukan', 'error');
            return;
        }
        
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
            $("#inputColorId").val(splitted[2] ? splitted[2] : 1);
            $("#inputPalletNumber").val(splitted[3]);
            $("#inputLotNumber").val(lotNumber);
            $("#inputBoxNumber").val(splitted[4]);
        } else {
            $("#inputIdPallet").val("");
            $("#inputColorId").val(1);
            $("#inputPalletNumber").val("");
            $("#inputLotNumber").val(lotNumber);
            $("#inputBoxNumber").val($(event).attr("data-boxnumber"));
        }
        $("#inputScan").val("");
        $('#modal2').modal('toggle');
        setTimeout(function() {
            $('#inputScan').focus();
        }, 500);
    }

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
                    
                    $('#inputColorId').val(idPart).change();
                    $('#inputPalletNumber').val(nomorPart);
                    $(this).val('');
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
                Swal.fire('Error', 'Gagal assign driver', 'error');
            }
        });
    }

</script>
