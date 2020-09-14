// "use strict";

var Summary = function() {
    
    var grid;
    
	var select2Handler = function() {

		$('#Wilayah').select2({
			placeholder: '--- Pilih Pengelompokan Wilayah ---',
            minimumResultsForSearch: -1,
		});

		$('#PeriodeSensus').select2({
			placeholder: '--- Pilih Periode Pendataan ---',
            minimumResultsForSearch: -1,
		})

	}
    
    var initTable = function (param) {
        $('#tablemon').jqGrid("GridUnload");
        var param = {
            PeriodeSensus: $('#PeriodeSensus').val(),
            groupby: $('#Wilayah').val(),
        };

        var ids = '';
        var ket = '';
        var showKelurahan = true;
        var showRW = true;
        var showRT = true;

        

        var querystr = $.param(param);
        var xparam = [];
        var header = {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf_token,
        };


        switch ($('#Wilayah').val()) {
            case '3':
                ids = 'Id Kecamatan';
                ket = 'Nama Kecamatan';
                showKelurahan = false;
                showRW = false;
                showRT = false;
                xparam = [{
                        dataName: 'nama_kecamatan',
                        label: 'Kecamatan'
                    },
                ];
                break;
            case '4':
                ids = 'Id Kelurahan';
                ket = 'Nama Kelurahan';
                showRW = false;
                showRT = false;

                xparam = [{
                        dataName: 'nama_kelurahan',
                        label: 'Kelurahan'
                    },
                ];
                break;
            case '5':
                ids = 'Id RW';
                ket = 'Nama RW';
                showRT = false;
                xparam = [{
                        dataName: 'nama_kelurahan',
                        label: 'Kelurahan'
                    },{
                        dataName: 'nama_rw',
                        label: 'RW'
                    },
                ];
                break;
            case '6': 
                ids = 'Id RT';
                ket = 'Nama RT';
                xparam = [{
                        dataName: 'nama_kelurahan',
                        label: 'Kelurahan'
                    },{
                        dataName: 'nama_rw',
                        label: 'Rw',
                        hidden: true,
                    },{
                        dataName: 'nama_rt',
                        label: 'RT'
                    },
                ];
                break;
        }
        
   //      grid = $('#tablemon').jqGrid('jqPivot', base_url + '/laporan/summary/data?' + querystr, 
   //          {
   //              frozenStaticCols: true,
   //              skipSortByX: true,
   //              useColSpanStyle: true,
   //              xDimension: xparam,
   //              yDimension: [{dataName: 'status_nama'}],
   //              aggregates: [{
   //                  member: 'qty',
   //                  aggregator: 'sum',
   //                  label: 'Sum',
   //                  formatter: 'integer',
   //                  align: 'right',
   //                  // width: 100,
   //              }],

			// 	}, {
			// 		pager: '#pagermon',
			// 		// shrinkToFit: false,
			// 		// forceFit: true,
			// 		//sortable: true,
			// 		viewrecords: true,
			// 		rownumbers: true,
			// 		autowidth: true,
			// 		threeStateSort: true,
			// 		// rowNum: 20,
			// 		rowList: [20, 30, 50],
			// 		loadComplete: function() {
			// 			KTApp.unblockPage();
			// 		},
			// 	}
			// );

            if (grid != null) {
                $('#tablemon').DataTable().clear().destroy();
                $('#tablemon').empty();
            }

            grid = $('#tablemon').DataTable({
            fnFooterCallback: function(row, data, start, end, display) {
                var api = this.api();
                var footer = $(this).append('<tfoot><tr></tr></tfoot>');
                this.api().columns().every(function () {
                    var sum = this
                    .data()
                    .reduce(function(a, b) {
                    var x = parseFloat(a) || 0;
                    var y = parseFloat(b) || 0;
                    if(x+y >= 1){
                        return x+y;
                    }else{
                        return ' ';
                    }
                    }, 0);
                    console.log(sum);
                $(footer).append('<th class="text-right">'+sum+'</th>');
                });
            },
            responsive: true,
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            ajax: {
                /*url: base_url+'/laporan/targetactual/data',*/
                url: base_url + '/laporan/summary/data?' + querystr,
                type: 'GET',
                headers: header,
                data: xparam,                
            },
            //order: [1, 'asc'],
            columns: [                
                {  // 'Jml KK yg Ada', 'Jml KK yg didata', 'Jml PUS Peserta KB', 'Jml PUS Bukan Peserta KB', 'Jml PUS Hamil', 
                    title: 'Kecamatan',
                    data: 'nama_kecamatan',
                },
                // { 
                //     title: 'Kelurahan',
                //     data: 'nama_kelurahan',
                //     visible : showKelurahan,
                // },
                // { 
                //     title: 'RW',
                //     data: 'nama_rw',
                //     visible : showRW,
                // },
                // { 
                //     title: 'RT',
                //     data: 'nama_rt',
                //     visible : showRT,
                // },
                { 
                    title: 'Jenis valid',
                    data: 'jml_valid',
                    className: "text-right",
                    width: "12%",
                },
                { 
                    title: 'Jenis Not Valid',
                    data: 'jml_notvalid',
                    className: "text-right",
                    width: "12%",
                },
                { 
                    title: 'Jenis Anomali',
                    data: 'jml_anomali',
                    className: "text-right",
                    width: "12%",
                },
                // { 
                //     title: 'Jenis Anulir',
                //     data: 'jml_anulir',
                //     className: "text-right",
                //     width: "12%",
                // },
                // { 
                //     title: 'Jenis Received',
                //     data: 'jml_received',
                //     className: "text-right",
                //     width: "12%",
                // },
            ],
            
            
            }); // end grid datatable...   

            grid.on( 'draw', function () {                
                KTApp.unblockPage();
            } );

           
    }

    
        
    var showReport = function () {
        $('#btnShow').click(function (e) {
            e.preventDefault();
            if ($('#PeriodeSensus').val()=='') {
                swal.fire('', 'Periode Pendataan belum dipilih', 'error');
                return false;
            }
            if ($('#Wilayah').val()=='') {
                swal.fire('', 'Pengelompokan Wilayah belum dipilih', 'error');
                return false;
            }

            var param = {
                PeriodeSensus: $('#PeriodeSensus').val(),
                groupby: $('#Wilayah').val(),
            };
            
            var querystr = $.param(param);

            KTApp.blockPage({
                overlayColor: "#000000",
                state: "primary",
                message: 'Harap tunggu!...'
            }); 
            
            
            $.get(base_url + '/laporan/summary/cekData/', querystr, function( response ) {
                if (response.status) {                  
                    initTable();                   
                } else {    
                    KTApp.unblockPage();                                                
                    Swal.fire( '', response.message, 'alert' );                                                                
                }
            });

            //initTable();
        });
    }
    
    var btnPrintHandler = function () {
        $('#btnPrint').click(function (e) {
            e.preventDefault();
            if ($('#PeriodeSensus').val()=='') {
                swal.fire('', 'Periode Pendataan belum dipilih', 'error');
                return false;
            }
            if ($('#Wilayah').val()=='') {
                swal.fire('', 'Pengelompokan Wilayah belum dipilih', 'error');
                return false;
            }
            
            var param = {
                PeriodeSensus: $('#PeriodeSensus').val(),
                groupby: $('#Wilayah').val(),
                print: 1,
            };
            
            var querystr = $.param( param );
            var url = base_url + '/laporan/summary/data?' + querystr;
            $('<a href="'+url+'" target="_blank">&nbsp;</a>')[0].click();

        });
    }
    
	return {
		init: function() {
            select2Handler();
            showReport();
            btnPrintHandler();
		}
	};
}();

jQuery(document).ready(function() {
	Summary.init();
});