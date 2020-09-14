@extends(request()->ajax() ? 'layouts.ajax' : 'layouts.app')

@section('title', 'PK02')

@section('content')
@php
$blank_ans = ' _ ';
@endphp

<div class="row">
    <div class="col-sm-12">

        <!-- #19 -->
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[19]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[19]['question_text'] }}</p>
                @if (!empty($frmpk[19]['pilihan']))
                <p>
                    {{ $refanswer[19][$frmpk[19]['pilihan']]['id_answer'] }}.
                    {{ $refanswer[19][$frmpk[19]['pilihan']]['answer_text'] }}
                    @if ($frmpk[19]['pilihan']==7)
                    {{ $frmpk[19]['othertext'] }}
                    @endif
                </p>
                @endif
            </div>
        </div>

        <!-- #20 -->
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[20]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[20]['question_text'] }}</p>
                @if (!empty($frmpk[20]['pilihan']))
                <p>
                    {{ $refanswer[20][$frmpk[20]['pilihan']]['id_answer'] }}.
                    {{ $refanswer[20][$frmpk[20]['pilihan']]['answer_text'] }}
                    @if ($frmpk[20]['pilihan']==5)
                    {{ $frmpk[20]['othertext'] }}
                    @endif
                </p>
                @endif
            </div>
        </div>
        
        <!-- #21 -->
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[21]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[21]['question_text'] }}</p>
                @if (!empty($frmpk[21]['pilihan']))
                <p>
                    {{ $refanswer[21][$frmpk[21]['pilihan']]['id_answer'] }}.
                    {{ $refanswer[21][$frmpk[21]['pilihan']]['answer_text'] }}
                    @if ($frmpk[21]['pilihan']==6)
                    {{ $frmpk[21]['othertext'] }}
                    @endif
                </p>
                @endif
            </div>
        </div>
        
        <!-- #22 -->
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[22]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[22]['question_text'] }}</p>
                @if (!empty($frmpk[22]['pilihan']))
                <p>
                    {{ $refanswer[22][$frmpk[22]['pilihan']]['id_answer'] }}.
                    {{ $refanswer[22][$frmpk[22]['pilihan']]['answer_text'] }}
                </p>
                @endif
            </div>
        </div>
        
        <!-- #23 -->
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[23]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[23]['question_text'] }}</p>
                @if (!empty($frmpk[23]['pilihan']))
                <p>
                    {{ $refanswer[23][$frmpk[23]['pilihan']]['id_answer'] }}.
                    {{ $refanswer[23][$frmpk[23]['pilihan']]['answer_text'] }}
                    @if ($frmpk[23]['pilihan']==8)
                    {{ $frmpk[23]['othertext'] }}
                    @endif
                </p>
                @endif
            </div>
        </div>
        
        <!-- #24 -->
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[24]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[24]['question_text'] }}</p>
                @if (!empty($frmpk[24]['pilihan']))
                <p>
                    {{ $refanswer[24][$frmpk[24]['pilihan']]['id_answer'] }}.
                    {{ $refanswer[24][$frmpk[24]['pilihan']]['answer_text'] }}
                    @if ($frmpk[24]['pilihan']==4)
                    {{ $frmpk[24]['othertext'] }}
                    @endif
                </p>
                @endif
            </div>
        </div>
        
        <!-- #25 -->
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[25]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[25]['question_text'] }}</p>

                <p><span class="frm-answer">{{ $frmpk[25]['varnum1'] ?? $blank_ans }}</span> m²</p>
            </div>
        </div>
        
        <!-- #26 -->
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[26]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[26]['question_text'] }}</p>

                <p><span class="frm-answer">{{ $frmpk[26]['varnum1'] ?? $blank_ans }}</span> orang</p>
            </div>
        </div>
        
        <!-- #27 -->
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[27]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[27]['question_text'] }}</p>
                @if (!empty($frmpk[27]['pilihan']))
                <p>
                    {{ $refanswer[27][$frmpk[27]['pilihan']]['id_answer'] }}.
                    {{ $refanswer[27][$frmpk[27]['pilihan']]['answer_text'] }}
                    @if ($frmpk[27]['pilihan']==4)
                    {{ $frmpk[27]['othertext'] }}
                    @endif
                </p>
                @endif
            </div>
        </div>
        
        <!-- #28 -->
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[28]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[28]['question_text'] }}</p>
                @if (!empty($frmpk[28]['pilihan']))
                <p>
                    {{ $refanswer[28][$frmpk[28]['pilihan']]['id_answer'] }}.
                    {{ $refanswer[28][$frmpk[28]['pilihan']]['answer_text'] }}
                    @if ($frmpk[28]['pilihan']==6)
                    {{ $frmpk[28]['othertext'] }}
                    @endif
                </p>
                @endif
            </div>
        </div>
        
        <!-- #29 -->
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[29]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[29]['question_text'] }}</p>
                @if (!empty($frmpk[29]['pilihan']))
                <p>
                    {{ $refanswer[29][$frmpk[29]['pilihan']]['id_answer'] }}.
                    {{ $refanswer[29][$frmpk[29]['pilihan']]['answer_text'] }}
                </p>
                @endif
            </div>
        </div>
        
        <!-- #30 -->
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[30]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[30]['question_text'] }}</p>
                <p>
                    @foreach ($refanswer[30] as $row)
                    @if (!empty($frmpkanswer[30][$row['id_answer']]))
                    <p>
                        {{ $row['id_answer'].'. '}}
                        {{ $row['answer_text'] }}
                        @if ($row['id_answer']==16 && !empty($frmpkanswer[30][16]['othertext']) )
                        {{ $frmpkanswer[30][16]['othertext'] }}
                        @endif

                    </p>
                    @endif
                    @endforeach
                </p>
            </div>
        </div>
        
        <!-- #31 -->
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[31]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[31]['question_text'] }}</p>
                @if (!empty($frmpk[31]['pilihan']))
                <p>
                    {{ $refanswer[31][$frmpk[31]['pilihan']]['id_answer'] }}.
                    {{ $refanswer[31][$frmpk[31]['pilihan']]['answer_text'] }}
                </p>
                @endif
            </div>
        </div>
        
        <!-- #32 -->
        <div class="row kt-padding-10 kt-b-border">
            <div class="col-sm-1">{{ $refpk[32]['id'] }}</div>
            <div class="col-sm-11">
                <p>{{ $refpk[32]['question_text'] }}</p>
                <p>
                    @foreach ($refanswer[32] as $row)
                    @if (!empty($frmpkanswer[32][$row['id_answer']]))
                    <p>
                        {{ $row['id_answer'].'. '}}
                        {{ $row['answer_text'] }}
                        @if ($row['id_answer']==10 && !empty($frmpkanswer[32][10]['othertext']) )
                        {{ $frmpkanswer[32][10]['othertext'] }}
                        @endif

                    </p>
                    @endif
                    @endforeach
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
