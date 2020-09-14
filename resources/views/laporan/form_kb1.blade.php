@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.app')

@section('title', 'Form KB1')

@section('content')
@php
$blank_ans = ' _ ';
@endphp
<style>

</style>


<div class="row">
    <div class="col-sm-12">

        <!-- #01 -->
        @if (!empty($frmkb[1]))
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refkb[1]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refkb[1]['question_text'] }} <span class="frm-answer">{{ $frmkb[1]['varnum1'] ?? $blank_ans }}</span></p>

                <p>{{ $refanswer[1][1]['id_answer'] }}. {{ $refanswer[1][1]['answer_text'] }} Laki-laki <span class="frm-answer">{{ $frmkbanswer[1][1]['varnum1'] ?? $blank_ans }}</span> Perempuan <span class="frm-answer">{{ $frmkbanswer[1][1]['varnum2'] ?? $blank_ans }}</span></p>

                <p>{{ $refanswer[1][2]['id_answer'] }}. {{ $refanswer[1][2]['answer_text'] }} Laki-laki <spanclass="frm-answer">{{ $frmkbanswer[1][2]['varnum1'] ?? $blank_ans }}</span> Perempuan <span class="frm-answer">{{ $frmkbanswer[1][2]['varnum2'] ?? $blank_ans }}</span></p>

            </div>
        </div>
        @endif

        <!-- #02 -->
        @if (!empty($frmkb[2]))
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refkb[2]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refkb[2]['question_text'] }} <span class="frm-answer">{{ $frmkb[2]['varnum1'] ?? $blank_ans }}</span></p>

            </div>
        </div>
        @endif
        
        <!-- #03 -->
        @if (!empty($frmkb[3]))
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refkb[3]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refkb[3]['question_text'] }}</p>
                
                @if (!empty($frmkbanswer[3][1]))
                    <p>{{ $refanswer[3][1]['answer_text'] }}, Usia Kehamilan <span class="frm-answer">{{ $frmkbanswer[3][1]['varnum1'] ?? $blank_ans }}</span> Minggu</p>

                    <p>{{ $refanswer[3][101]['answer_text'] }}</p>

                    <p>{{ $refanswer[3]['101'.$frmkbanswer[3][1]['pilihankb']]['answer_text'] }}</p>

                @endif
                
                
                @if (!empty($frmkbanswer[3][2]))
                    <p>{{ $refanswer[3][2]['answer_text'] }}</p>

                    <p>{{ $refanswer[3][201]['answer_text'] }}</p>

                    <p>{{ $refanswer[3]['201'.$frmkbanswer[3][2]['pilihankb']]['answer_text'] }}</p>
                @endif
            
            </div>
        </div>
        @endif
        
        <!-- #04 -->
        @if (!empty($frmkb[4]))
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refkb[4]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refkb[4]['question_text'] }}</p>

                @if (!empty($frmkbanswer[4][1]))
                    <p>{{ $refanswer[4][1]['answer_text'] }}</p>

                    <p>{{ $refanswer[4][101]['answer_text'] }}</p>

                    <p>Bulan {{ $frmkbanswer[4][1]['varnum1'] }} Tahun {{ $frmkbanswer[4][1]['varnum2'] }}</p>
                    
                @endif

                @if (!empty($frmkbanswer[4][2]))
                    <p>{{ $refanswer[4][2]['answer_text'] }}</p>
                @endif

            </div>
        </div>
        @endif
        
        <!-- #05 -->
        @if (!empty($frmkb[5]))
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refkb[5]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refkb[5]['question_text'] }}</p>
               
                @if (!empty($frmkbanswer[5][1]))
                    <p>{{ $refanswer[5][1]['answer_text'] }}</p>

                    <p>{{ $refanswer[5][101]['answer_text'] }}</p>

                    <p>Bulan {{ $frmkbanswer[5][1]['varnum1'] }} Tahun {{ $frmkbanswer[5][1]['varnum2'] }}</p>

                    <p>{{ $refanswer[5][102]['answer_text'] }}</p>

                    <p>Bulan {{ $frmkbanswer[5][1]['varnum3'] }} Tahun {{ $frmkbanswer[5][1]['varnum4'] }}</p>
                @endif

                @if (!empty($frmkbanswer[5][2]))
                    <p>{{ $refanswer[5][2]['answer_text'] }}</p>
                @endif
                
            </div>
        </div>
        @endif
        
        <!-- #06 -->
        @if (!empty($frmkb[6]))
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refkb[6]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refkb[6]['question_text'] }}</p>
                
                @foreach ($refanswer[6] as $ref)
                    @if (!empty($frmkbanswer[6][$ref['id_answer']]))
                        <p>- {{ $ref['answer_text'] }}</p>
                    @endif
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- #07 -->
        @if (!empty($frmkb[7]))
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refkb[7]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refkb[7]['question_text'] }}</p>
                
                @foreach ($refanswer[7] as $ref)
                    @if (!empty($frmkbanswer[7][$ref['id_answer']]))
                        <p>- {{ $ref['answer_text'] }}</p>
                    @endif
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- #08 -->
        @if (!empty($frmkb[8]))
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refkb[8]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refkb[8]['question_text'] }}</p>
               
                @foreach ($refanswer[8] as $ref)
                    @if (!empty($frmkbanswer[8][$ref['id_answer']]))
                        <p>- {{ $ref['answer_text'] }}
                            @if ($ref['id_answer']==10)
                            : {{ $frmkbanswer[8][10]['othertext'] }}
                            @endif
                        </p>
                    @endif
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- #09 -->
        @if (!empty($frmkb[9]))
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refkb[9]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refkb[9]['question_text'] }}</p>
                
                <p>- {{ $refanswer[9][1]['answer_text'] }} {{ $refanswer[9]['10'.$frmkbanswer[9][1]['pilihankb']]['answer_text'] }} </p>
                <p>- {{ $refanswer[9][2]['answer_text'] }} {{ $refanswer[9]['20'.$frmkbanswer[9][1]['pilihankb']]['answer_text'] }} </p>
                <p>- {{ $refanswer[9][3]['answer_text'] }} {{ $refanswer[9]['30'.$frmkbanswer[9][1]['pilihankb']]['answer_text'] }} </p>
            </div>
        </div>
        @endif

    </div>
</div>


@endsection
