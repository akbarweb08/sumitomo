@extends('layouts.app')

@section('title', 'Lot 242 Mezanine')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/242.css') }}">
    <style>
        .fullbody {
            zoom: 60%;
        }
        @media print {
            @page {
                size: A4 portrait;
                height: 100%;
                width: 50%
            }
            #formName {
                display: block;
                margin-top: 10px;
                margin-left: 1485px;
            }
            #container {
                margin-top: 10px;
                margin-left: 50px;
            }
            #kotakAdd {
                display: none;
            }
        }

        @php
            $marginTop = 10;
            $marginLeft = 50;
            $mark4 = 0;
        @endphp
        
        @for ($i = 0; $i < 240; $i++)
            @php
                if ($i % 2 == 0) {
                    $marginTop = 10;
                    $marginLeft = 50;
                } else {
                    $marginTop = -60;
                    $marginLeft = 142;
                }
                if ($i >= 40 && $i < 120) {
                    $mark4 += 1;
                    if ($mark4 == 1) {
                        $marginTop = 10;
                        $marginLeft = 400;
                    } else if ($mark4 == 2) {
                        $marginTop = -60;
                        $marginLeft = 491;
                    } else if ($mark4 == 3) {
                        $marginTop = -60;
                        $marginLeft = 582;
                    } else {
                        $mark4 = 0;
                        $marginTop = -60;
                        $marginLeft = 673;
                    }
                    if ($i == 40) {
                        $marginLeft = 400;
                        $marginTop = -1390;
                    }
                } else if ($i >= 120 && $i < 200) {
                    $mark4 += 1;
                    if ($mark4 == 1) {
                        $marginTop = 10;
                        $marginLeft = 931;
                    } else if ($mark4 == 2) {
                        $marginTop = -60;
                        $marginLeft = 1022;
                    } else if ($mark4 == 3) {
                        $marginTop = -60;
                        $marginLeft = 1113;
                    } else {
                        $mark4 = 0;
                        $marginTop = -60;
                        $marginLeft = 1204;
                    }
                    if ($i == 120) {
                        $marginLeft = 931;
                        $marginTop = -1390;
                    }
                } else if ($i >= 200) {
                    $mark4 += 1;
                    if ($mark4 == 1) {
                        $marginTop = 10;
                        $marginLeft = 1462;
                    } else if ($mark4 == 2) {
                        $mark4 = 0;
                        $marginTop = -60;
                        $marginLeft = 1553;
                    }
                    if ($i == 200) {
                        $marginLeft = 1462;
                        $marginTop = -1390;
                    }
                }
            @endphp
            #kotak{{ $i }} {
                width: 90px;
                height: 60px;
                color: black;
                border: 1px solid black;
                margin-top: {{ $marginTop }}px;
                margin-left: {{ $marginLeft }}px;
                text-align: center;
                font-size: 14px;
                font-weight: bold;
            }
        @endfor
        #kotak0 {
            margin-top: 26px;
        }

        @for ($i = 0; $i <= 480; $i++)
            [data-boxnumber="{{ $i }}"] {
                height: 30px;
                border: 1.5px solid black;
                padding-top: -3px;
                font-size: 20px;
            }
        @endfor

        @php
            $kuningTop = 10;
            $kuningLeft = 1432;
        @endphp
        @for ($i = 80; $i >= 1; $i--)
            #kotakkuning{{ $i }} {
                width: 25px;
                height: 60px;
                color: black;
                margin: 10px;
                border: 1px solid black;
                margin-bottom: 0;
                margin-top: {{ $kuningTop }}px;
                margin-left: {{ $kuningLeft }}px;
                text-align: center;
                font-size: 16px;
                line-height: 60px;
            }
            @php
                if ($i == 61) {
                    $kuningLeft = $kuningLeft - 532;
                    $kuningTop = -1390;
                } else {
                    $kuningTop = 10;
                }
                if ($i == 41) {
                    $kuningLeft = $kuningLeft - 132;
                    $kuningTop = -1390;
                }
                if ($i == 21) {
                    $kuningLeft = $kuningLeft - 532;
                    $kuningTop = -1390;
                }
            @endphp
        @endfor
        #kotakkuning80 {
            margin-top: -1390px;
        }
    </style>
@endpush

@section('content')
<div class="fullbody" @if(isset($isRecord) && $isRecord) style="pointer-events: none;" @endif>
    <div class="card-body">
        <h1 style="margin-top: 10px;margin-left: 10px;margin-bottom: -140px;text-align:center">
            <b>WAREHOUSE LOT 242 SKETCH</b>
            @if(isset($isRecord) && $isRecord) <b style="color:red">{{ $recordDate }}</b> @endif
        </h1>
        
        @foreach($lastinfo as $row)
        <div>
            <h5 style="margin-top: 70px;margin-left: -10px;margin-bottom: -140px;">Last Change : <b>{{ $row->lastDate }}</b></h5>
        </div>
        @endforeach
    </div>

    @php $i = 1; @endphp
    @for ($j = 0; $j <= 240; $j++)
        @php $mark = $i; @endphp
        <div id="kotak{{ $j }}">
            @for ($i = $mark; $i <= $mark + 1; $i++)
                @php $box = $box_array[$i] ?? null; @endphp
                <div onclick="toggleModal(this)" data-boxnumber='{{ $i }}' 
                     {!! $box ? 'data-pallets="'.$box['Id'].';'.($box['InvoiceNumber']??'').';'.($box['ColorId']??'').';'.($box['PalletNumber']??'').';'.$box['BoxNumber'].';'.($box['errMsg']??'').';"' . ' data-available="1"' : 'data-available="0"' !!} 
                     style="cursor: cell; {!! $box ? 'background-color: '.$box['ColorHex'].';color:'.$box['ColorText'] : '' !!}; {!! ($box && isset($box['errMsg']) && $box['errMsg'] != NULL) ? 'border: 5px solid red;' : '' !!}">
                    {{ ($box['Prefiks'] ?? '') . ($box['PalletNumber'] ?? '') }}
                </div>
            @endfor
        </div>
    @endfor

    @for ($z = 80; $z >= 1; $z--)
        <div id="kotakkuning{{ $z }}" data-linenumber='{{ $z }}' style="background-color: yellow;">
            <b>{{ $z }}</b>
        </div>
    @endfor

    <div id="container">
      <a style="color:black;font-size:16px; cursor:pointer;" onclick="toggleColorInput()">
        <div id="kotakAdd" style="border-style: solid; border-width:5px; border-color: black; background-color: grey; color: white">
          &nbsp;&nbsp;&nbsp;ADD INVOICE&nbsp;&nbsp;&nbsp;
        </div>
      </a>
    </div>
    
    <div id="container" style="margin-top: 15px; position: relative; ">
        @foreach($suppliers as $sup)
            @php 
                $supNm = $sup->supplier; 
                $colors = $colors_by_supplier[$sup->id] ?? [];
            @endphp
            <div style="margin-top: 5px">
                <div id="kotaket" style="width: 230px ; float: left; font-size:17px; border-style: solid; border-width:5px; border-color: black; cursor:pointer;" onclick="toggleFromSupply(this)" supplier="{{ $sup->id }};{{ $supNm }}">
                    &nbsp;&nbsp;&nbsp;{{ $supNm }}&nbsp;&nbsp;&nbsp;
                </div>
                <div style="margin-top: 35px">
                    @foreach($colors as $row1)
                        <div data-colors="{{ $row1->Id }};{{ $row1->InvoiceNumber }};{{ $row1->ColorHex }};{{ $row1->ColorText }};{{ $row1->LotPlace }};{{ $row1->Prefiks }};{{ $row1->supply }};" onclick="toggleColor(this)" id="kotaket" style="cursor:pointer; background-color:{{ $row1->ColorHex }};color: {{ $row1->ColorText }};font-size:17px;font-weight:bold; width: 230px; {!! !$row1->receiptExists ? 'border: 5px solid red;' : '' !!}">
                            &nbsp;({{ $row1->Prefiks }}) {{ $row1->InvoiceNumber }} - {{ $row1->total }}&nbsp;
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div id="formName" style="position: fixed; bottom: 0; width: 200px; font-size: 17px">
        MC/FORM/012 Rev. 1
    </div>
</div>

@include('sketch.components.sketchfunction')
@endsection
