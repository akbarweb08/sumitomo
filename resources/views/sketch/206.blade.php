@php
    $Date = date('Y-m-d');
    $perm = session('role');
    $newuri = '206';
@endphp

@extends('layouts.sketch')

@section('title', '206 Warehouse')

@section('content')
    <div class="card-body">
      @if (!request()->has('type'))
        <h1 style="text-align:center;margin-top: 30px;margin-left: 120px;margin-bottom: -140px;"><b>WAREHOUSE LOT 206 </b></h1>
      @else
        <h1 style="text-align:center;margin-top: 30px;margin-left: 120px;margin-bottom: -140px;"><b style="color:red">PINDAH DATA LOT 206 SKETCH</b></h1>
      @endif
      
      @foreach ($lastinfo as $row)
        <div>
          <h5 style="margin-top: 50px;margin-left: ;margin-bottom: -140px;">
            Last Change : <b>{{ $row->lastDate }} </b>
            @if(in_array(session('role'), ['206', 'super', 'admin']))
              <button onclick="recordData('206')" class="btn btn-success" style="margin-left: 20px; padding: 5px 15px; font-weight: bold; border-radius: 5px;">Record Data</button>
            @endif
          </h5>
        </div>
      @endforeach
    </div>

    <!--kolom-->
    <div id="kotak">
      @for ($i = 1; $i <= 3; $i++)
        @php $box = $box_array[$i] ?? null; @endphp
        <div onclick="toggleModal(this)" data-boxnumber='{{ $i }}' title="{{ $box->InvoiceNumber ?? '' }}"
             {!! $box ? 'data-pallets="'.$box->Id.';'.$box->InvoiceNumber.';'.$box->ColorId.';'.$box->PalletNumber.';'.$box->BoxNumber.';'.$box->errMsg.';'.'" data-available="1"' : 'data-available="0"' !!} 
             style="cursor: cell; {{ $box ? 'background-color: '.$box->ColorHex.';color:'.$box->ColorText : '' }}; {{ ($box && $box->errMsg != NULL) ? 'border: 5px solid red;' : '' }}">
            {{ ($box->Prefiks ?? '') . ($box->PalletNumber ?? '') }}
        </div>
      @endfor
    </div>
    
    @php $i = 4; @endphp
    @for ($j = 1; $j <= 271; $j++)
      @php $mark = $i; @endphp
      <div id="kotak{{ $j }}">
        @for ($i = $mark; $i <= $mark + 2; $i++)
          @php $box = $box_array[$i] ?? null; @endphp
          <div onclick="toggleModal(this)" data-boxnumber='{{ $i }}' title="{{ $box->InvoiceNumber ?? '' }}"
               {!! $box ? 'data-pallets="'.$box->Id.';'.$box->InvoiceNumber.';'.$box->ColorId.';'.$box->PalletNumber.';'.$box->BoxNumber.';'.$box->errMsg.';'.'" data-available="1"' : 'data-available="0"' !!} 
               style="cursor: cell; {{ $box ? 'background-color: '.$box->ColorHex.';color:'.$box->ColorText : '' }}; {{ ($box && $box->errMsg != NULL) ? 'border: 5px solid red;' : '' }}">
              {{ ($box->Prefiks ?? '') . ($box->PalletNumber ?? '') }}
          </div>
        @endfor
      </div>
    @endfor

    <!--kuning-->
    <div id="kotakkuning" onclick="lineControl(this)" data-linenumber='1' style="font-size: 12px;background-color: yellow;">
      <b style="display: block;">01</b>
    </div>
    <div id="kotakkuning1" onclick="lineControl(this)" data-linenumber='2' style="font-size: 12px;background-color: yellow;">
      <b>02</b>
    </div>
    <div id="kotakkuning2" onclick="lineControl(this)" data-linenumber='3' style="font-size: 12px;background-color: yellow;">
      <b>03</b>
    </div>
    <div id="kotakkuning3" onclick="lineControl(this)" data-linenumber='4' style="font-size: 12px;background-color: yellow;">
      <b>04</b>
    </div>
    <div id="kotakkuning4" onclick="lineControl(this)" data-linenumber='5' style="font-size: 12px;background-color: yellow;">
      <b>05</b>
    </div>
    <div id="kotakkuning5" onclick="lineControl(this)" data-linenumber='6' style="font-size: 12px;background-color: yellow;">
      <b>06</b>
    </div>

    @php $kuning = 21; @endphp
    @for ($z = 6; $z <= 20; $z++)
      <div id="kotakkuning{{ $z }}" onclick="lineControl(this)" data-linenumber='{{ $kuning }}' style="font-size: 12px;background-color: yellow;">
        <b>{{ $kuning }}</b>
      </div>
      @php $kuning--; @endphp
    @endfor

    @php $kuning = 22; @endphp
    @for ($z = 21; $z <= 35; $z++)
      <div id="kotakkuning{{ $z }}" onclick="lineControl(this)" data-linenumber='{{ $kuning }}' style="font-size: 12px;background-color: yellow;">
        <b>{{ $kuning }}</b>
      </div>
      @php $kuning++; @endphp
    @endfor

    <a class="btn btn-default" href="#" style="margin-top:-2085px;margin-left:1852px;color:blue"></a>
    
    <div id="container">
      <a style="color:black;font-size:16px; " onclick="toggleColorInput()">
        <div id="kotaket" style="border-style: solid; border-width:5px; border-color: black; background-color: grey; color: white; width: 200px">
          &nbsp;&nbsp;&nbsp;ADD INVOICE&nbsp;&nbsp;&nbsp;
        </div>
      </a>
    </div>
    
    <div id="container" style="margin-top: 15px; position: relative; ">
      @foreach ($supplies as $sup)
        <div style="margin-top: 5px">
          <div id="kotaket" style="width: 200px ; float: left; font-size:14px; border-style: solid; border-width:5px; border-color: black; " onclick="toggleFromSupply(this)" supplier="{{ $sup->id }};{{ $sup->supplier }}">&nbsp;&nbsp;&nbsp;{{ $sup->supplier }}&nbsp;&nbsp;&nbsp;</div>
          <div style="margin-top: 35px">
            @foreach ($sup->colors as $color)
              <div data-colors="{{ $color->Id }};{{ $color->InvoiceNumber }};{{ $color->ColorHex }};{{ $color->ColorText }};{{ $color->LotPlace }};{{ $color->Prefiks }};{{ $color->supply }};" onclick="toggleColor(this)" id="kotaket" style=" background-color:{{ $color->ColorHex }};color: {{ $color->ColorText }};font-size:16px;font-weight:bold; width: 200px; {{ $color->receipt_count == 0 ? 'border: 5px solid red;' : '' }}">&nbsp;({{ $color->Prefiks }}) {{ $color->InvoiceNumber }} - {{ $color->total }}&nbsp;</div>
            @endforeach
          </div>
        </div>
      @endforeach
    </div>

    <div id="formName" style="bottom: 0; width: 200px; font-size: 17px; margin-left: 2200px; margin-top: 451px;">
      MC/FORM/012
    </div>
@endsection

@push('modals')
  @if (request()->has('color'))
    @include('sketch.components.sketchcolormode')
  @endif
  
  @if (!request()->has('type') && !request()->has('color'))
    @include('sketch.components.sketchfunction')
  @endif
@endpush

@push('scripts')
  @if (request()->has('type'))
    <script type="text/javascript">
      function toggleModal(event, type = 'input') {
        var id = "{{ request('id') }}";
        var type = "{{ request('type') }}";
        var dataPallets = $(event).attr("data-pallets");
        var boxNumber = $(event).attr("data-boxnumber");
        var id2 = '';
        if (dataPallets) {
            var splitted = dataPallets.split(';');
            id2 = splitted[0];
        }
        window.location.href = `{{ route('sketch.move') }}?id=${id}&id2=${id2}&type=${type}&box=${boxNumber}`;
      }
    </script>
  @endif

  <script>
    function recordData(lotNumber) {
        Swal.fire({
            title: 'Konfirmasi',
            text: "Apakah Anda yakin ingin merekap data pallet hari ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Record!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "{{ route('sketch.record') }}",
                    type: 'POST',
                    data: {
                        _token: "{{ csrf_token() }}",
                        LotNumber: lotNumber
                    },
                    success: function(response) {
                        if(response.status == 'success') {
                            Swal.fire(
                                'Berhasil!',
                                response.message,
                                'success'
                            ).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire(
                                'Gagal!',
                                response.message,
                                'error'
                            );
                        }
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'Terjadi kesalahan saat merekap data.',
                            'error'
                        );
                    }
                });
            }
        });
    }
  </script>
@endpush
