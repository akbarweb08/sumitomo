@extends('layouts.app')

@section('title', 'Lot 245 Warehouse')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/245.css') }}">
    <style>
        .fullbody {
            zoom: 73%;
        }

        #formName {
            display: none;
        }

        @media print {
            @page {
                size: A4 landscape;
                height: 100%;
                width: 100%
            }
            #formName {
                display: block;
            }
        }

        @for ($i = 1; $i <= 816; $i++)
            [data-boxnumber="{{ $i }}"] {
                height: 29px;
                border: 1.5px solid black;
            }
            @php $i++; @endphp
            [data-boxnumber="{{ $i }}"] {
                height: 31px;
                border: 1.5px solid black;
                padding-top: -3px;
                padding-top: 2px;
                padding-right: 45px;
            }
            @php $i++; @endphp
            [data-boxnumber="{{ $i }}"] {
                height: 31px;
                border: 1.5px solid black;
                padding-top: -3px;
                padding-right: 1px;
                padding-top: 2px;
                margin-left: 49px;
                margin-top: -31px;
            }
        @endfor

        @php
            $marginTop = 2;
            $marginLeft = 25;
        @endphp
        @for ($i = 0; $i <= 143; $i++)
            @php
                if ($i % 6 == 0 && $i != 0) {
                    $marginTop = -370;
                    $marginLeft += 105;
                } else {
                    $marginTop = 2;
                }
            @endphp
            #kotak{{ $i }} {
                width: 99px;
                height: 60px;
                color: black;
                border: 1px solid black;
                margin-bottom: 0;
                margin-top: {{ $marginTop }}px;
                margin-left: {{ $marginLeft }}px;
                text-align: center;
                font-size: 14px;
                font-weight: bold;
            }
        @endfor

        @php
            $marginTop = 2;
            $marginLeft = 25;
        @endphp
        @for ($i = 144; $i <= 263; $i++)
            @php
                if ($i % 6 == 0 && $i != 144) {
                    $marginTop = -370;
                    $marginLeft += 105;
                } else {
                    $marginTop = 2;
                }
            @endphp
            #kotak{{ $i }} {
                width: 99px;
                height: 60px;
                color: black;
                border: 1px solid black;
                margin-bottom: 0;
                margin-top: {{ $marginTop }}px;
                margin-left: {{ $marginLeft }}px;
                text-align: center;
                font-size: 14px;
                font-weight: bold;
            }
        @endfor

        @php $marginLeft = 2125; @endphp
        @for ($i = 264; $i <= 271; $i++)
            @php
                if ($i % 2 == 0 && $i != 264) {
                    $marginTop = -246;
                    $marginLeft += 105;
                } else {
                    $marginTop = 126;
                }
            @endphp
            #kotak{{ $i }} {
                width: 99px;
                height: 60px;
                color: black;
                border: 1px solid black;
                margin-bottom: 0;
                margin-top: {{ $marginTop }}px;
                margin-left: {{ $marginLeft }}px;
                text-align: center;
                font-size: 14px;
                font-weight: bold;
            }
        @endfor

        @php
            $kuningTop = -30;
            $kuningLeft = 25;
        @endphp
        @for ($i = 0; $i <= 23; $i++)
            @php
                if ($i != 0) {
                    $kuningTop = -30;
                    $kuningLeft += 105;
                }
            @endphp
            #kotakkuning{{ $i }} {
                padding-bottom: -10px;
                width: 99px;
                height: 30px;
                color: black;
                margin: 10px;
                border: 1px solid black;
                margin-bottom: 0;
                margin-top: {{ $kuningTop }}px;
                margin-left: {{ $kuningLeft }}px;
                text-align: center;
                font-size: 14px;
                padding-top: -10px;
            }
        @endfor

        @php
            $kuningTop = 98;
            $kuningLeft = 25;
        @endphp
        @for ($i = 24; $i <= 44; $i++)
            @php
                if ($i != 24) {
                    $kuningTop = -30;
                    $kuningLeft += 105;
                }
            @endphp
            #kotakkuning{{ $i }} {
                padding-bottom: -10px;
                width: 99px;
                height: 30px;
                color: black;
                margin: 10px;
                border: 1px solid black;
                margin-bottom: 0;
                margin-top: {{ $kuningTop }}px;
                margin-left: {{ $kuningLeft }}px;
                text-align: center;
                font-size: 14px;
                padding-top: -10px;
            }
        @endfor

        #kotakkuning0 {
            margin-top: 5px;
            margin-left: 25px;
        }

        #kotak0 {
            margin-top: 70px;
            margin-left: 25px;
        }

        #kotak144 {
            margin-top: 5px;
            margin-left: 25px;
        }

        #kotak264 {
            margin-top: -370px;
            margin-left: 2125px;
        }
    </style>
@endpush

@section('content')
<div class="fullbody">
    <div class="card-body">
        <h1 style="margin-top: 10px;margin-left: 10px;margin-bottom: -140px;text-align:center">
            <b>WAREHOUSE LOT 245 SKETCH</b>
        </h1>
        
        @foreach($lastinfo as $row)
        <div>
            <h5 style="margin-top: 70px;margin-left: -10px;margin-bottom: -140px;">Last Change : <b>{{ $row->lastDate }}</b></h5>
        </div>
        @endforeach
    </div>

    @php $i = 1; @endphp
    @for ($j = 0; $j <= 143; $j++)
        @php $mark = $i; @endphp
        <div id="kotak{{ $j }}">
            @for ($i = $mark; $i <= $mark + 2; $i++)
                @php $box = $box_array[$i] ?? null; @endphp
                <div onclick="toggleModal(this)" data-boxnumber='{{ $i }}' 
                     {!! $box ? 'data-pallets="'.$box['Id'].';'.($box['InvoiceNumber']??'').';'.($box['ColorId']??'').';'.($box['PalletNumber']??'').';'.$box['BoxNumber'].';'.($box['errMsg']??'').';"' . ' data-available="1"' : 'data-available="0"' !!} 
                     style="cursor: cell; {!! $box ? 'background-color: '.$box['ColorHex'].';color:'.$box['ColorText'] : '' !!}; {!! ($box && isset($box['errMsg']) && $box['errMsg'] != NULL) ? 'border: 5px solid red;' : '' !!}">
                    {{ ($box['Prefiks'] ?? '') . ($box['PalletNumber'] ?? '') }}
                </div>
            @endfor
        </div>
    @endfor

    @for ($z = 0; $z <= 23; $z++)
        <div id="kotakkuning{{ $z }}" onclick="lineControl(this)" data-linenumber='{{ $z + 1 }}' style="font-size: 17px;background-color: yellow;">
            <b style="display: block;">{{ $z + 1 }}</b>
        </div>
    @endfor

    @php $y = 45; @endphp
    @for ($z = 24; $z <= 43; $z++)
        @php $y--; @endphp
        <div id="kotakkuning{{ $z }}" onclick="lineControl(this)" data-linenumber='{{ $y }}' style="font-size: 17px;background-color: yellow;">
            <b style="display: block;">{{ $y }}</b>
        </div>
    @endfor

    @for ($j = 144; $j <= 271; $j++)
        @php $mark = $i; @endphp
        <div id="kotak{{ $j }}">
            @for ($i = $mark; $i <= $mark + 2; $i++)
                @php $box = $box_array[$i] ?? null; @endphp
                <div onclick="toggleModal(this)" data-boxnumber='{{ $i }}' 
                     {!! $box ? 'data-pallets="'.$box['Id'].';'.($box['InvoiceNumber']??'').';'.($box['ColorId']??'').';'.($box['PalletNumber']??'').';'.$box['BoxNumber'].';'.($box['errMsg']??'').';"' . ' data-available="1"' : 'data-available="0"' !!} 
                     style="cursor: cell; {!! $box ? 'background-color: '.$box['ColorHex'].';color:'.$box['ColorText'] : '' !!}; {!! ($box && isset($box['errMsg']) && $box['errMsg'] != NULL) ? 'border: 5px solid red;' : '' !!}">
                    {{ ($box['Prefiks'] ?? '') . ($box['PalletNumber'] ?? '') }}
                </div>
            @endfor
        </div>
    @endfor

    <div id="container">
      <a style="color:black;font-size:16px; cursor:pointer;" onclick="toggleColorInput()">
        <div id="kotaket" style="border-style: solid; width: 200px; background-color: grey; color: white; border:3px solid black;margin-top: -60px;">
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
            <div style="margin-top: -50px">
                <div id="kotaket" style="width: 250px ; float: left; font-size:14px; border-style: solid; border-width:5px; border-color: black; cursor:pointer;" onclick="toggleFromSupply(this)" supplier="{{ $sup->id }};{{ $supNm }}">
                    &nbsp;&nbsp;&nbsp;{{ $supNm }}&nbsp;&nbsp;&nbsp;
                </div>
                <div style="margin-top: 35px">
                    @foreach($colors as $row1)
                        <div data-colors="{{ $row1->Id }};{{ $row1->InvoiceNumber }};{{ $row1->ColorHex }};{{ $row1->ColorText }};{{ $row1->LotPlace }};{{ $row1->Prefiks }};{{ $row1->supply }};" onclick="toggleColor(this)" id="kotaket" style="cursor:pointer; background-color:{{ $row1->ColorHex }};color: {{ $row1->ColorText }};font-size:16px;font-weight:bold; width: 250px; {!! !$row1->receiptExists ? 'border: 5px solid red;' : '' !!}">
                            &nbsp;({{ $row1->Prefiks }}) {{ $row1->InvoiceNumber }} - {{ $row1->total }} / {{ $row1->total2 }} / {{ $row1->total3 }}&nbsp;
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div id="formName" style="bottom: 0; width: 200px; font-size: 17px; margin-left: 2400px; margin-top: 350px;">
      MC/FORM/012
    </div>
</div>

@include('sketch.components.sketchfunction')
@endsection
