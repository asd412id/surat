$(function () {
  var table_list;
  var language = {
    "decimal": "",
    "emptyTable": "Data tidak tersedia",
    "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
    "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
    "infoFiltered": "(Difilter dari _MAX_ total data)",
    "infoPostFix": "",
    "thousands": ",",
    "lengthMenu": "Menampilkan _MENU_ data",
    "loadingRecords": "Memuat...",
    "processing": "Memproses...",
    "search": "Cari:",
    "zeroRecords": "Pencarian tidak ditemukan",
    "paginate": {
      "first": "Pertama",
      "last": "Terakhir",
      "next": "Selanjutnya",
      "previous": "Sebelumnya"
    }
  }
  var loading = `<div class="h4 text-center"><i class="fas fa-pulse fa-spinner"></i></div>`;
  var bmodal = `<div class="modal fade" data-backdrop="static" id="modal" aria-modal="true" role="dialog"> <div class="modal-dialog"> <div class="modal-content"> <div class="modal-header py-2"> <h4 class="modal-title"></h4> <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">×</span> </button> </div> <div class="modal-body"></div> </div> </div> </div>`;
  var modalLoading = () => {
    $("#modal .modal-dialog").attr('class', 'modal-dialog modal-sm');
    $("#modal .modal-title").html(`<h5>Memuat data ...</h5>`);
    $("#modal .modal-body").html(loading);
    $("#modal").modal('show');
  }
  var loadFormModal = (url, table_list = null, type = null) => {
    modalLoading();
    $.get(url, {}, function (res) {
      if (type != null) {
        $("#modal .modal-dialog").attr('class', 'modal-dialog ' + type);
      } else {
        $("#modal .modal-dialog").attr('class', 'modal-dialog');
      }
      $("#modal .modal-title").html(res.title);
      $("#modal .modal-body").html(res.form);
      var submit = false;
      $(".modal-form").off().on('submit', function (e) {
        e.preventDefault();
        var form = new FormData(this);
        var _this = $(this);
        var btext = _this.find('button[type=submit]').html();
        _this.find('*').prop('disabled', true);
        _this.find('button[type=submit]').text('Silahkan tunggu ...');
        if (!submit) {
          submit = true;
          $.ajax({
            url: _this.data('url'),
            type: 'post',
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            data: form,
            success: function (res) {
              submit = false;
              toastr.remove();
              toastr.success(res.message);
              $("#modal").modal('hide');
              if (res.redirect != undefined) {
                var timeout = res.timeout != undefined ? res.timeout : 25;
                setTimeout(() => {
                  location.href = res.redirect;
                }, timeout);
              } else {
                if (table_list != null) {
                  table_list.ajax.reload();
                }
                _this.find('*').prop('disabled', false);
                _this.find('button[type=submit]').html(btext);
              }
            },
            error: function (err) {
              submit = false;
              toastr.remove();
              try {
                var errors = JSON.parse(err.responseText);
                var errMsg = errors.errors[Object.keys(errors.errors)[0]][0];
                toastr.error(errMsg);
              } catch (error) {
                toastr.error(err.responseJSON.message);
              }
              _this.find('*').prop('disabled', false);
              _this.find('button[type=submit]').html(btext);
            }
          });
        }
      });
      init(table_list);
    }, 'json').fail(function ($err) {
      $("#modal .modal-title").html("Kesalahan");
      $("#modal .modal-body").html("Tidak dapat memuat data!");
    });
  }
  var slect2 = [];
  var _jsl = false;
  var init = (table_list = null) => {
    $(".modal-dialog").draggable({
      cursor: "move",
      handle: ".modal-header,.modal-body",
    });
    if (slect2.length > 0) {
      slect2.forEach((v, i) => {
        v.select2('destroy');
      });
      slect2 = [];
    }
    $(".open-modal").off().on("click", function (e) {
      e.preventDefault();
      let type = $(this).data('type') != undefined ? $(this).data('type') : null;
      loadFormModal($(this).data('url'), table_list, type);
    });
    if ($(".select2").length > 0) {
      $(".select2").each(function () {
        var _this = $(this);
        var options = {
          allowClear: _this.attr('allow-clear') == 'false' ? false : true,
          placeholder: $(this).data('placeholder') != undefined ? $(this).data('placeholder') : 'Pilih'
        };
        if (_this.data('max') != undefined) {
          options.maximumSelectionLength = _this.data('max');
        }
        var sl2 = $(this).off().select2(options);
        slect2.push(sl2);
      });
    }
    if ($(".select2-ajax").length > 0) {
      $(".select2-ajax").each(function () {
        var _this = $(this);
        var options = {
          allowClear: _this.attr('allow-clear') == 'false' ? false : true,
          ajax: {
            url: _this.data('url')
          },
          minimumInputLength: _this.data('length') != undefined ? _this.data('length') : 1,
          placeholder: _this.data('placeholder') != undefined ? _this.data('placeholder') : 'Pilih'
        };
        if (_this.data('max') != undefined) {
          options.maximumSelectionLength = _this.data('max');
        }
        var sl2 = $(this).off().select2(options);
        slect2.push(sl2);
      });
    }
    if ($(".datepicker").length > 0) {
      $(".datepicker").datepicker({
        dateFormat: "dd-mm-yy",
        changeYear: true,
        changeMonth: true,
        yearRange: '1900:' + new Date().getFullYear()
      });
    }
    if ($(".daterange-btn").length > 0) {
      moment.locale('id');
      $('.daterange-btn').daterangepicker(
        {
          ranges: {
            'Hari Ini': [moment(), moment()],
            'Kemarin': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            '7 Hari Terakhir': [moment().subtract(6, 'days'), moment()],
            '30 Hari Terakhir': [moment().subtract(29, 'days'), moment()],
            'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
            'Bulan Lalu': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
          },
          startDate: moment().subtract(29, 'days'),
          endDate: moment()
        },
        function (start, end) {
          $('#reportrange').val(start.format('DD-MM-YYYY') + ' s.d. ' + end.format('DD-MM-YYYY'))
        }
      )
    }
    if ($(".summernote").length > 0) {
      $(".summernote").each(function () {
        var _this = $(this);
        $(this).summernote({
          height: _this.data('height') != undefined ? _this.data('height') : 300
        });
      });
    }
    if ($(".form-ajax").length > 0) {
      $(".form-ajax").each(function () {
        var _this = $(this);
        formAjax(_this, $(_this.data('container')));
      });
    }
    if ($("#jenis-surat").length > 0) {
      bsCustomFileInput.init();
      $("#jenis-surat").on('change', function () {
        var _this = $(this);
        var _kode_depan = $("option:selected", this).data('kode-depan');
        var _kode_belakang = $("option:selected", this).data('kode-belakang');
        if (_this.val() == '') {
          _this.closest('form').find('input,textarea').prop('disabled', true);
        } else {
          setTimeout(() => {
            _this.closest('form').find('#inomor').focus().select();
          }, 100);
          _this.closest('form').find('input,textarea').prop('disabled', false);
        }

        if (_kode_depan != undefined && _kode_depan != null && _kode_depan != '') {
          $("#kode-depan").removeClass('d-none');
          $("#kode-depan span").text(_kode_depan);
          if (_this.closest('form').find('#inomor').val() == '') {
            $.get('/search/nomor', { jenis: _this.val() }, function (res) {
              _this.closest('form').find('#inomor').val(res.nomor);
            }, 'json').fail(function () {
              _this.closest('form').find('#inomor').val(1);
            });
          }
        } else {
          $("#kode-depan").addClass('d-none');
          $("#kode-depan span").empty();
        }
        if (_kode_belakang != undefined && _kode_belakang != null && _kode_belakang != '') {
          $("#kode-belakang").removeClass('d-none');
          $("#kode-belakang span").text(_kode_belakang);
        } else {
          $("#kode-belakang").addClass('d-none');
          $("#kode-belakang span").empty();
        }
      });
    }
    if ($("#suratid").length > 0 && table_list != null) {
      $("#suratid").off().on("change", function () {
        var _sv = $(this).val();
        table_list.search(_sv).draw();
      });
      if (!_jsl) {
        _jsl = true;
        setTimeout(() => {
          $("#suratid").val($("#suratid").data('query')).trigger('change');
        }, 10);
      }
    }
  }
  init(table_list);
  if ($("#study-query").data('role') == 'guest') {
    $("#study-query").submit();
  }
  $('body').append(bmodal);
  if ($(".table-list").length > 0) {
    $(".table-list").each(function (i, v) {
      var _url = $(this).data('url');
      var _columns = $(this).data('cols').split(',');
      var table_columns = [];
      _columns.forEach((v) => {
        var opt = v.split('!');
        var col = {};
        col.data = opt[0];
        col.name = opt[0];
        if (opt[1] != undefined) {
          opt[1].split('|').forEach((v1) => {
            if (v1 == 'order') {
              col.orderable = false;
            }
            if (v1 == 'search') {
              col.searchable = false;
            }
            if (v1 == 'visible') {
              col.visible = false;
            }
          })
        }
        table_columns.push(col);
      });
      table_list = $(this).DataTable({
        language: language,
        processing: true,
        serverSide: true,
        scrollX: true,
        autoWidth: false,
        ajax: _url != undefined ? _url : location.href,
        columns: table_columns,
        'drawCallback': function () {
          init(table_list);
        }
      });
    });
  }
});
function formAjax(_form, _container = null) {
  var submit = false;
  _form.off().on('submit', function (e) {
    e.preventDefault();
    var _this = $(this);
    var _data = new FormData(this);
    var _url = _this.data('url');
    var _method = _this.attr('method');
    var btext = _this.find('button[type=submit]').html();
    _this.find('*').prop('disabled', true);
    _this.find('button[type=submit]').text('Silahkan tunggu ...');
    if (!submit) {
      submit = true;
      $.ajax({
        url: _url,
        type: _method,
        dataType: 'json',
        cache: false,
        contentType: false,
        processData: false,
        data: _data,
        success: function (res) {
          submit = false;
          _this.find('*').prop('disabled', false);
          _this.find('button[type=submit]').html(btext);
          if (res.message != undefined) {
            toastr.remove();
            toastr.success(res.message);
          }
          if (res.view != undefined && _container != null) {
            _container.append(res.view);
          }
          if ($(".summernote").length > 0) {
            $(".summernote").summernote('reset');
          }
        },
        error: function (err) {
          submit = false;
          toastr.remove();
          try {
            var errors = JSON.parse(err.responseText);
            var errMsg = errors.errors[Object.keys(errors.errors)[0]][0];
            toastr.error(errMsg);
          } catch (error) {
            if (err.responseJSON.message != undefined) {
              toastr.error(err.responseJSON.message);
            }
          }
          _this.find('*').prop('disabled', false);
          _this.find('button[type=submit]').html(btext);
          _container.html(_bcontainer);
        }
      });
    }
  });
}
function formAjaxPost(_form = null, res = null) {
  if ($(".dtable").length > 0) {
    $(".dtable").DataTable({
      scrollX: true
    });
  }
}