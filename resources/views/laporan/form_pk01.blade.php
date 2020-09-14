@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.app')

@section('title', 'PK01')

@section('content')
@php
function pkanswer($pilihan) {
    if ($pilihan==1) {
        $result = 'Ya';
    } elseif ($pilihan==2) {
        $result = 'Tidak';
    } elseif ($pilihan==3) {
        $result = 'Tidak berlaku';
    } else {
        $result = ' _ ';
    }
    return $result;
}
@endphp


<div class="row">
    <div class="col-sm-12">
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[1]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[1]['question_text'] }}</p>
                
                <p><span class="frm-answer" >{{ pkanswer($frmpk[1]['pilihan']) }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[2]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[2]['question_text'] }}</p>
                <p><span class="frm-answer" >{{ pkanswer($frmpk[2]['pilihan']) }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[3]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[3]['question_text'] }}</p>

                <p>{{ $refanswer[3]['a']['id_answer'] }}. {{ $refanswer[3]['a']['answer_text'] }} <span class="frm-answer" >{{ pkanswer($frmpkanswer[3][1]['pilihanpk'] ?? '') }}</span></p>
                <p>{{ $refanswer[3]['b']['id_answer'] }}. {{ $refanswer[3]['b']['answer_text'] }} <span class="frm-answer" >{{ pkanswer($frmpkanswer[3][2]['pilihanpk'] ?? '') }}</span></p>
                <p>{{ $refanswer[3]['c']['id_answer'] }}. {{ $refanswer[3]['c']['answer_text'] }} <span class="frm-answer" >{{ pkanswer($frmpkanswer[3][3]['pilihanpk'] ?? '') }}</span></p>
                <p>{{ $refanswer[3]['d']['id_answer'] }}. {{ $refanswer[3]['d']['answer_text'] }} <span class="frm-answer" >{{ pkanswer($frmpkanswer[3][4]['pilihanpk'] ?? '') }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[4]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[4]['question_text'] }}</p>
                <p><span class="frm-answer" >{{ pkanswer($frmpk[4]['pilihan']) }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[5]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[5]['question_text'] }}</p>
                <p><span class="frm-answer" >{{ pkanswer($frmpk[5]['pilihan']) }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[6]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[6]['question_text'] }}</p>
                <p><span class="frm-answer" >{{ pkanswer($frmpk[6]['pilihan']) }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[7]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[7]['question_text'] }}</p>
                <p><span class="frm-answer" >{{ pkanswer($frmpk[7]['pilihan']) }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[8]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[8]['question_text'] }}</p>
                <p><span class="frm-answer" >{{ pkanswer($frmpk[8]['pilihan']) }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[9]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[9]['question_text'] }}</p>
                <p><span class="frm-answer" >{{ pkanswer($frmpk[9]['pilihan']) }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[10]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[10]['question_text'] }}</p>
                <p><span class="frm-answer" >{{ pkanswer($frmpk[10]['pilihan']) }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[11]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[11]['question_text'] }}</p>
                <p><span class="frm-answer" >{{ pkanswer($frmpk[11]['pilihan']) }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[12]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[12]['question_text'] }}</p>
                <p><span class="frm-answer" >{{ pkanswer($frmpk[12]['pilihan']) }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[13]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[13]['question_text'] }}</p>
                <p><span class="frm-answer" >{{ pkanswer($frmpk[13]['pilihan']) }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[14]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[14]['question_text'] }}</p>
                <p><span class="frm-answer" >{{ pkanswer($frmpk[14]['pilihan']) }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[15]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[15]['question_text'] }}</p>
                <p><span class="frm-answer" >{{ pkanswer($frmpk[15]['pilihan']) }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[16]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[16]['question_text'] }}</p>
                <p><span class="frm-answer" >{{ pkanswer($frmpk[16]['pilihan']) }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[17]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[17]['question_text'] }}</p>
                <p><span class="frm-answer" >{{ pkanswer($frmpk[17]['pilihan']) }}</span></p>
            </div>
        </div>
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[18]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[18]['question_text'] }}</p>

                <p>{{ $refanswer[3]['a']['id_answer'] }}. {{ $refanswer[3]['a']['answer_text'] }} <span class="frm-answer" >{{ pkanswer($frmpkanswer[3][1]['pilihanpk'] ?? '') }}</span></p>
                <p>{{ $refanswer[3]['b']['id_answer'] }}. {{ $refanswer[3]['b']['answer_text'] }} <span class="frm-answer" >{{ pkanswer($frmpkanswer[3][2]['pilihanpk'] ?? '') }}</span></p>
                <p>{{ $refanswer[3]['c']['id_answer'] }}. {{ $refanswer[3]['c']['answer_text'] }} <span class="frm-answer" >{{ pkanswer($frmpkanswer[3][3]['pilihanpk'] ?? '') }}</span></p>
                <p>{{ $refanswer[3]['d']['id_answer'] }}. {{ $refanswer[3]['d']['answer_text'] }} <span class="frm-answer" >{{ pkanswer($frmpkanswer[3][4]['pilihanpk'] ?? '') }}</span></p>
            </div>
        </div>
    </div>
</div>



@endsection