var general = {};

$(document).ready(function () {
    general.form = 'ModalFormAction';
    general.module = 'master_ukuran';
    general.url = route('ukuran_action');

    _page.init();

});

const _page = {

    _masking : () => {

        if (cekElement('.pagination a')){
            $(document).on('click', '.pagination a', function(event){
                event.preventDefault();
                var page=$(this).prop('href').split('page=')[1];
                _page._event.search(page);
            });
        }

        if (cekElement('#'+general.form)){
            let settingValidation = {};
            settingValidation.submitHandler = function(form){

                var formData = $(form).serialize();
                var promise = requestAjax('post',general.url, formData, '#'+general.form);
                promise.then((response) => {

                    myLoad('end', '#'+general.form);
                    myAlert(response.respon, response.message);

                    if (response.respon == 'success') {
                        $('#modal-form').modal('hide');
                        _page._event.search();
                    }

                })
                .fail((response) => {
                    $('#modal-form').modal('hide');
                    myLoad('end', '#'+general.form);
                    errorValidation(response);
                });

                return false;

            }
            $('#'+general.form).validate(settingValidation);
        }
    },

    _searchTable : (isUrl) => {
        $.ajax({
            type: "GET",
            url: isUrl,
            beforeSend: () => {
                myLoad('start', '.table-data');
            },
        })
        .done((data) => {
            myLoad('end', '.table-data');
            $('.content-table').empty().html(data);
        })
        .fail((response) => {
            myLoad('end', '.table-data');
            errorMessage(response);
        });
    },

    _formReset : () => {

        myLoad('end', '#'+general.form);

        $('.modal-title').html('');
        $('.module').val('');
        $('.act').val('');
        $('.kode_ukuran').val('');
        $('.v_ukuran_old').val('');
        $('.v_ukuran').val('');
    },

    _getData : (id, callback) => {
        var data = {'module':general.module,'act':'modalEdit','id':id};
        var promise = requestAjax('get',general.url,data,'.table-data');
        promise.then((response) => {
            myLoad('end','.table-data');
            callback(response);
        });
    },

    _deleteData : (id, callback) => {
        var data = {'module':general.module,'act':'delete','id':id};
        var promise = requestAjax('post',general.url,data,'.table-data');
        promise.then((response) => {
            myLoad('end','.table-data');
            callback(response);
        });
    },

    _event : {
        search : (page) => {
            var isPage = page;
            if(page === undefined){
                isPage = 1;
            }

            var url = "";
            url += "?page="+isPage;
            url += "&Searchkeyword="+$('.search-keyword').val();

            _page._searchTable(url);
        },

        form : (element) => {
            switch ($(element).attr('data-action')){
                case 'add':

                        $('#modal-form').modal('show');

                        $('#modal-form').on('shown.bs.modal', function () {

                            _page._formReset();

                            $('.module').val(general.module);

                            $('.modal-title').html('ADD UKURAN');
                            $('.module').val(general.module);
                            $('.act').val('add');

                            $('.v_ukuran').focus();
                        });

                    break;
                case 'edit':

                        var id = $(element).attr('data-id');

                        _page._getData(id, function(response){

                            if (response.respon == 'success') {

                                $('#modal-form').modal('show');

                                $('#modal-form').on('shown.bs.modal', function () {

                                    _page._formReset();

                                    $('.module').val(general.module);

                                    $('.modal-title').html('EDIT UKURAN');
                                    $('.module').val(general.module);
                                    $('.act').val('edit');
                                    $('.v_ukuran').focus();

                                    $('.kode_ukuran').val(id);
                                    $('.v_ukuran').val(response.data.ukuran);
                                    $('.v_ukuran_old').val(response.data.ukuran);
                                });
                            }

                        });

                    break;
                case 'delete':

                        var id = $(element).attr('data-id');
                        var name = $(element).attr('data-name');

                        Swal.fire({
                            title: 'Are you sure detele '+name+'?',
                            text: "You won't be able to revert this!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                _page._deleteData(id, function(response){
                                    myAlert(response.respon, response.message);
                                    _page._event.search();
                                });
                            }
                        });

                    break;
                default:
                        myAlert('error','ACTION NOT DECLARED');
                    break;
            }
        }
    },

    init: () => {
        _page._masking();
    }
};
