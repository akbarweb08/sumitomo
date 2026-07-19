@extends('layouts.app')

@section('title', 'REPACK Warehouse Sketch')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/repack.css') }}">
    <style>
        .fullbody {
            zoom: 100%;
        }

        #formName {
            display: none;
        }

        @media print {
            @page {
                size: A4 portrait;
                height: 100%;
                width: 50%
            }
            #formName {
                display: block;
            }
        }
    </style>
@endpush

@section('content')
<div class="fullbody" @if(isset($isRecord) && $isRecord) style="pointer-events: none;" @endif>
    <div class="card-body">
        <h1 style="margin-top: 20px;margin-left: -54px;margin-bottom: -140px; text-align:center;">
            <b>WAREHOUSE SKETCH REPACK </b>
            @if(isset($isRecord) && $isRecord) <b style="color:red">{{ $recordDate }}</b> @endif
        </h1>
        
        @foreach($lastinfo as $row)
        <div>
            <h5 style="margin-top: 70px;margin-left: -10px;margin-bottom: -140px;">Last Change : <b>{{ $row->lastDate }}</b></h5>
        </div>
        @endforeach
    </div>

    <!--kolom-->
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

    @for ($j = 1; $j <= 47; $j++)
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

    <div id="kotakkuning" style="font-size: 12px;background-color: yellow;">
      <b style="display: block;">01</b>
    </div>
    <div id="kotakkuning1" style="font-size: 12px;background-color: yellow;">
      <b style="display: block;">02</b>
    </div>
    <div id="kotakkuning2" style="font-size: 12px;background-color: yellow;">
      <b style="display: block;">03</b>
    </div>
    <div id="kotakkuning3" style="font-size: 12px;background-color: yellow;">
      <b style="display: block;">04</b>
    </div>

    <div id="container" style="margin-left: 530px;">
      <a style="color:black;font-size:16px; cursor:pointer;" onclick="toggleColorInput()">
        <div id="kotaket" style="border-style: solid; border-width:3px; border-color: black;width:200px; background-color: grey; color: white; margin-top: -728px;">
          &nbsp;&nbsp;&nbsp;ADD INVOICE&nbsp;&nbsp;&nbsp;
        </div>
      </a>
    </div>
    
    <div id="container" style="margin-top: 5px; position: absolute; margin-left: 530px;">
        @foreach($suppliers as $sup)
            @php 
                $supNm = $sup->supplier; 
                $colors = $colors_by_supplier[$sup->id] ?? [];
            @endphp
            <div style="margin-top: 5px">
                <div id="kotaket" style="width:170px ; text-align: left;margin-top: 5px ;font-size:12px; float: left; font-size:14px; border-style: solid; border-width:3px; border-color: black; cursor:pointer;" onclick="toggleFromSupply(this)" supplier="{{ $sup->id }};{{ $supNm }}">
                    &nbsp;&nbsp;&nbsp;{{ $supNm }}&nbsp;&nbsp;&nbsp;
                </div>
                @foreach($colors as $row1)
                    <div data-colors="{{ $row1->Id }};{{ $row1->InvoiceNumber }};{{ $row1->ColorHex }};{{ $row1->ColorText }};{{ $row1->LotPlace }};{{ $row1->Prefiks }};{{ $row1->supply }};" onclick="toggleColor(this)" id="kotaket" style="cursor:pointer; float: left; background-color:{{ $row1->ColorHex }};color: {{ $row1->ColorText }};font-size:14px;font-weight:bold; width: 170px; text-align: left; {!! !$row1->receiptExists ? 'border: 5px solid red;' : '' !!}">
                        &nbsp;({{ $row1->Prefiks }}) {{ $row1->InvoiceNumber }} - {{ $row1->total }}&nbsp;
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

</div>

@include('sketch.components.sketchfunction')
@endsection
