@extends('layouts.sketch')

@section('title', '07 Warehouse Sketch')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/7.css') }}">
    <style>
        .fullbody {
            zoom: 99%;
        }

        #formName {
            display: none;
        }

        @media print {
            @page {
                size: A4 landscape;
                min-zoom: 70%;
                max-zoom: 70%;
            }
            #formName {
                display: block;
            }
        }
    </style>
@endpush

@section('content')
    <div class="card-body">
        <h1 style="text-align:center;margin-top: 10px;margin-left: -130px;margin-bottom: -140px;">
            <b>WAREHOUSE LOT 7</b>
        </h1>
        
        @foreach($lastinfo as $row)
        <div>
            <h5 style="margin-top: 70px;margin-left: -10px;margin-bottom: -140px;">Last Change : <b>{{ $row->lastDate }}</b></h5>
        </div>
        @endforeach
    </div>

    <div id="kotak">
        @for ($i = 1; $i <= 3; $i++)
            @php $box = $box_array[$i]; @endphp
            <div onclick="toggleModal(this)" data-boxnumber='{{ $i }}' 
                 {!! $box ? 'data-pallets="'.$box['Id'].';'.($box['InvoiceNumber']??'').';'.($box['ColorId']??'').';'.($box['PalletNumber']??'').';'.$box['BoxNumber'].';'.($box['errMsg']??'').';"' . ' data-available="1"' : 'data-available="0"' !!} 
                 style="cursor: cell; {!! $box ? 'background-color: '.$box['ColorHex'].';color:'.$box['ColorText'] : '' !!}; {!! ($box && isset($box['errMsg']) && $box['errMsg'] != NULL) ? 'border: 5px solid red;' : '' !!}">
                {{ ($box['Prefiks'] ?? '') . ($box['PalletNumber'] ?? '') }}
            </div>
        @endfor
    </div>

    @for ($j = 1; $j <= 64; $j++)
        @php $mark = $i; @endphp
        <div id="kotak{{ $j }}">
            @for ($i = $mark; $i <= $mark + 2; $i++)
                @php $box = $box_array[$i]; @endphp
                <div onclick="toggleModal(this)" data-boxnumber='{{ $i }}' 
                     {!! $box ? 'data-pallets="'.$box['Id'].';'.($box['InvoiceNumber']??'').';'.($box['ColorId']??'').';'.($box['PalletNumber']??'').';'.$box['BoxNumber'].';'.($box['errMsg']??'').';"' . ' data-available="1"' : 'data-available="0"' !!} 
                     style="cursor: cell; {!! $box ? 'background-color: '.$box['ColorHex'].';color:'.$box['ColorText'] : '' !!}; {!! ($box && isset($box['errMsg']) && $box['errMsg'] != NULL) ? 'border: 5px solid red;' : '' !!}">
                    {{ ($box['Prefiks'] ?? '') . ($box['PalletNumber'] ?? '') }}
                </div>
            @endfor
        </div>
    @endfor
    @php $i = 198; @endphp

    <div id="kotakkuning" style="font-size: 12px;background-color: yellow;">
        <b style="display: block;">11</b>
    </div>
    @php $kuning = 12; @endphp
    @for ($z = 1; $z <= 12; $z++)
        <div id="kotakkuning{{ $z }}" style="font-size: 12px;background-color: yellow;">
            <b style="display: block;">{{ $kuning }}</b>
        </div>
        @php $kuning++; @endphp
    @endfor

    <div class="card-body" style="margin-top:30px;margin-bottom:-60px;">
        <h6 style="margin-top: -30px;margin-left: 865px;"><b>SKETCH MATERIAL TRANSIT AREA</b></h6>
    </div>

    @php $mark11 = 0; @endphp
    @for ($j = 66; $j <= 100; $j++)
        @php $mark11++; $mark = $i; @endphp
        <div id="kotak{{ $j }}">
            @for ($i = $mark; $i <= $mark + 1; $i++)
                @php $box = $box_array[$i]; @endphp
                <div onclick="toggleModal(this)" data-boxnumber='{{ $i }}' 
                     {!! $box ? 'data-pallets="'.$box['Id'].';'.($box['InvoiceNumber']??'').';'.($box['ColorId']??'').';'.($box['PalletNumber']??'').';'.$box['BoxNumber'].';'.($box['errMsg']??'').';"' . ' data-available="1"' : 'data-available="0"' !!} 
                     style="cursor: cell; {!! $box ? 'background-color: '.$box['ColorHex'].';color:'.$box['ColorText'] : '' !!}; {!! ($box && isset($box['errMsg']) && $box['errMsg'] != NULL) ? 'border: 5px solid red;' : '' !!}">
                    {{ ($box['Prefiks'] ?? '') . ($box['PalletNumber'] ?? '') }}
                </div>
            @endfor
        </div>
        @if ($mark11 == 5)
            @php $mark11 = 0; $j = $j + 1; $i = $i + 2; @endphp
        @endif
    @endfor

    <div id="container">
        <a style="color:black;font-size:16px; cursor:pointer;" onclick="toggleColorInput()">
            <div id="kotaket" style="border-style: solid; border-width:5px; border-color: black; background-color: grey; color: white">
                &nbsp;&nbsp;&nbsp;ADD INVOICE&nbsp;&nbsp;&nbsp;
            </div>
        </a>
    </div>
    
    <div id="container" style="margin-top: 15px; position: relative; ">
        @foreach($supplies as $sup)
            @php 
                $supNm = $sup->supplier; 
                $colors = $colors_by_supplier[$sup->id] ?? [];
            @endphp
            <div style="margin-top: 5px">
                <div id="kotaket" style="width: 170px ; float: left; font-size:12px; border-style: solid; border-width:5px; border-color: black; cursor:pointer;" onclick="toggleFromSupply(this)" supplier="{{ $sup->id }};{{ $supNm }}">
                    &nbsp;&nbsp;&nbsp;{{ $supNm }}&nbsp;&nbsp;&nbsp;
                </div>
                <div style="margin-top: 35px">
                    @foreach($colors as $row1)
                        <div data-colors="{{ $row1->Id }};{{ $row1->InvoiceNumber }};{{ $row1->ColorHex }};{{ $row1->ColorText }};{{ $row1->LotPlace }};{{ $row1->Prefiks }};{{ $row1->supply }};" onclick="toggleColor(this)" id="kotaket" style="cursor:pointer; background-color:{{ $row1->ColorHex }};color: {{ $row1->ColorText }};font-size:12px;font-weight:bold; width: 170px; {!! $row1->receipt_count == 0 ? 'border: 5px solid red;' : '' !!}">
                            &nbsp;({{ $row1->Prefiks }}) {{ $row1->InvoiceNumber }} - {{ $row1->total }}&nbsp;
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <h6 style="margin-top: 307px; margin-left: 751px; margin-bottom: -100px; color:black;">
        <div style="background-color: #e0f5b0; width: 183px; text-align: center; height: 30px; min-height: 10em; display: table-cell; vertical-align: middle; border-style: solid; border: 2px solid black ">
            <b>RACK RX</b>
        </div>
    </h6>

    <h6 style="margin-top: 70px; margin-left: 938px; margin-bottom: -100px; color:black">
        <div style="background-color: yellow; width: 90px; text-align: center; height: 30px; min-height: 10em; display: table-cell; vertical-align: middle; font-size:14px ; border-style: solid;  border: 2px solid black">
            <b>INTRANSIT</b>
        </div>
    </h6>

    <h6 style="margin-top: 70px; margin-left: 1031px; margin-bottom: -100px; color:black">
        <div style="background-color: green; width: 276px; text-align: center; height: 30px; min-height: 10em; display: table-cell; vertical-align: middle; border-style: solid;   border: 2px solid black">
            <b>FINISH GOOD LC</b>
        </div>
    </h6>

<div id="formName" style="bottom: 0; width: 200px; font-size: 17px; margin-left: 1180px; margin-top: 100px;">
    MC/FORM/012
</div>

@include('sketch.components.sketchfunction')
@endsection
