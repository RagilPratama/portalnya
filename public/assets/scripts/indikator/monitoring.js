"use strict";

var MonIndikator = function () {

    var gridSarpras;
    var initTableSarpras = function (param) {
        var header = {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf_token,
        };
        if (gridSarpras != null) {
            $('#tablesarpras').DataTable().clear().destroy();
            $('#tablesarpras').empty();
        }
        var btnTrue = '<span class="kt-badge kt-badge--success kt-badge--sm">&nbsp;</span>';
        var btnFalse = '<span class="kt-badge kt-badge--danger kt-badge--sm">&nbsp;</span>';
        gridSarpras = $('#tablesarpras').DataTable({
            responsive: true,
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            ordering: false,
            ajax: {
                url: base_url + '/indikator/mondata',
                type: 'GET',
                headers: header,
                data: param,
            },
            columns: [{
                title: 'Provinsi',
                data: 'nama_provinsi',
            }, {
                title: 'Kabupaten',
                data: 'nama_kabupaten',
            }, {
                title: 'Kecamatan',
                data: 'nama_kecamatan',
            }, {
                title: 'Peng.',
                data: 'pengadaan_1',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 1) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Dist.',
                data: 'distribusi_1',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 1) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Peng.',
                data: 'pengadaan_2',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data == 1 ? btnTrue : btnFalse;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Dist.',
                data: 'distribusi_2',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data == 1 ? btnTrue : btnFalse;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Peng.',
                data: 'pengadaan_3',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data == 1 ? btnTrue : btnFalse;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Dist.',
                data: 'distribusi_3',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data == 1 ? btnTrue : btnFalse;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Peng.',
                data: 'pengadaan_4',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data == 1 ? btnTrue : btnFalse;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Dist.',
                data: 'distribusi_4',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data == 1 ? btnTrue : btnFalse;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Peng.',
                data: 'pengadaan_5',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data == 1 ? btnTrue : btnFalse;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Dist.',
                data: 'distribusi_5',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data == 1 ? btnTrue : btnFalse;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Peng.',
                data: 'pengadaan_6',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data == 1 ? btnTrue : btnFalse;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Dist.',
                data: 'distribusi_6',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data == 1 ? btnTrue : btnFalse;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Peng.',
                data: 'pengadaan_7',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data == 1 ? btnTrue : btnFalse;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Dist.',
                data: 'distribusi_7',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data == 1 ? btnTrue : btnFalse;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Peng.',
                data: 'pengadaan_8',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data == 1 ? btnTrue : btnFalse;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Dist.',
                data: 'distribusi_8',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data == 1 ? btnTrue : btnFalse;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, ],
        });

    }

    var gridPelatihan;
    var initTablePelatihan = function (param) {
        var header = {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf_token,
        };
        if (gridPelatihan != null) {
            $('#tablepelatihan').DataTable().clear().destroy();
            $('#tablepelatihan').empty();
        }
        var btnTrue = '<span class="kt-badge kt-badge--success kt-badge--sm">&nbsp;</span>';
        var btnFalse = '<span class="kt-badge kt-badge--danger kt-badge--sm">&nbsp;</span>';
        gridPelatihan = $('#tablepelatihan').DataTable({
            responsive: true,
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            ordering: false,
            ajax: {
                url: base_url + '/indikator/mondatalatih',
                type: 'GET',
                headers: header,
                data: param,
            },
            columns: [{
                title: 'Provinsi',
                data: 'nama_provinsi',
            }, {
                title: 'Kabupaten',
                data: 'nama_kabupaten',
            }, {
                title: 'Kecamatan',
                data: 'nama_kecamatan',
            }, {
                title: 'Status',
                data: 'status_proses_1',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 1) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Jml.',
                data: 'jml_peserta_1',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 1) {
                        stat = row.status_proses_1 ? data : '-';
                    }
                    return stat;
                }
            }, {
                title: 'Status',
                data: 'status_proses_2',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Jml.',
                data: 'jml_peserta_2',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data;
                    } else if (row.tingkat == 3) {
                        stat = row.status_proses_2 ? data : '-';
                    }
                    return stat;
                }
            }, {
                title: 'Status',
                data: 'status_proses_3',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'Jml.',
                data: 'jml_peserta_3',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data;
                    } else if (row.tingkat == 3) {
                        stat = row.status_proses_3 ? data : '-';
                    }
                    return stat;
                }
            }, ],
        });
    }

    var gridKelengkapan;
    var initTableKelengkapan = function (param) {
        var header = {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrf_token,
        };
        if (gridKelengkapan != null) {
            $('#tablekelengkapan').DataTable().clear().destroy();
            $('#tablekelengkapan').empty();
        }
        var btnTrue = '<span class="kt-badge kt-badge--success kt-badge--sm">&nbsp;</span>';
        var btnFalse = '<span class="kt-badge kt-badge--danger kt-badge--sm">&nbsp;</span>';
        gridKelengkapan = $('#tablekelengkapan').DataTable({
            responsive: true,
            searchDelay: 1000,
            processing: true,
            serverSide: true,
            ordering: false,
            ajax: {
                url: base_url + '/indikator/mondatalengkap',
                type: 'GET',
                headers: header,
                data: param,
            },
            columns: [{
                title: 'Provinsi',
                data: 'nama_provinsi',
            }, {
                title: 'Kabupaten',
                data: 'nama_kabupaten',
            }, {
                title: 'Kecamatan',
                data: 'nama_kecamatan',
            }, {
                title: 'SK Pengorganisasian Lapangan',
                data: 'organisasi',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, {
                title: 'SK Posko',
                data: 'posko',
                class: 'text-center',
                render: function (data, type, row) {
                    var stat = '';
                    if (row.tingkat == 2) {
                        stat = data;
                    } else if (row.tingkat == 3) {
                        stat = data ? btnTrue : btnFalse;
                    }
                    return stat;
                }
            }, ],
        });
    }

    return {
        init: function () {

            initTableSarpras();
            initTablePelatihan();
            initTableKelengkapan();
        }
    };
}();

jQuery(document).ready(function () {
    MonIndikator.init();
});
