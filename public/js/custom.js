const nibras = {};

$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        }
    });

    nibras.config = {
        validator: {
            ignore: 'input[type=hidden], .select2-search__field', // ignore hidden fields
            errorClass: 'small text-danger',
            successClass: 'small text-success',
            validClass: 'small text-danger',
            highlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },
            unhighlight: function (element, errorClass) {
                $(element).removeClass(errorClass);
            },
            // success: function(label) {
            //     label.addClass('validation-valid-label').text('Success.'); // remove to hide Success message
            // },
            // Different components require proper error label placement
            errorPlacement: function (error, element) {

                // Unstyled checkboxes, radios
                if (element.parents().hasClass('form-check')) {
                    error.appendTo(element.parents('.form-check').parent());
                }

                else if (element.parents().hasClass('custom-control')) {
                    error.appendTo(element.parents('.custom-control').parent().parent().parent());
                }

                // Input with icons and Select2
                else if (element.parents().hasClass('form-group-feedback') || element.hasClass('select2-hidden-accessible')) {
                    error.appendTo(element.parent());
                }

                // Input group, styled file input
                else if (element.parent().is('.uniform-uploader, .uniform-select') || element.parents().hasClass('input-group')) {
                    error.appendTo(element.parent().parent());
                }

                // Other elements
                else {
                    error.insertAfter(element);
                }
            },
        }
    };

    $.validator.setDefaults(nibras.config.validator);

    if (cekElement(".search-keyword")){
        $('.search-keyword').focus();
    }

    if (cekElement(".FormatKey")){
        $('.FormatKey').keyup(function(event){
            // Allow arrow keys & Period
            if (event.which >= 37 && event.which <= 40) return;
            // if(event.which == 190 || event.which == 110) return;

            // Format Number
            $(this).val(function(index, value)
            {
                number = value.replace(/[^0-9'.']/g, "");
                if (number.match(/\./g))
                {
                    if (number.match(/\./g).length > 1) {
                        return;
                    }
                    else {
                        n = number.search(/\./);
                        numberSplit = number.substr(0, n);
                        firstNumber = numberSplit.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        lastNumber = number.substr(n, 3);
                        return firstNumber + lastNumber;
                    }
                }
                else {
                    return number.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }

            });
        });
    }

    if (cekElement(".select2-search")){
        if($().select2){
            $('.select2-search').select2().on('change', function () {
                $(this).valid();
            });
        }
    }

});

function myAlert(type, message, url){
    if(url=="" || url=="undefined"){
        var url="";
    }

    if (typeof swal == 'undefined'){
        console.warn('Warning - sweetalert2.min.js is not loaded.');
        return;
    }

    if (type == "success"){
        Swal.fire({
            icon: 'success',
            html: message,
            showConfirmButton: false,
            timer: 1500
        }).then(function() {
            if(url){
                window.location.href = url;
            }
        });
    }
    else if (type == "error" || type == "failed" || type == 'unauthorized'){
        Swal.fire({
            icon: 'error',
            html: message,
            showConfirmButton: false,
            timer: 1500
        }).then(function() {
            if(url){
                window.location.href = url;
            }
        });
    }
    else if (type == "info"){
        Swal.fire({
            icon: 'info',
            html: message,
            showConfirmButton: false,
            timer: 1500
        }).then(function() {
            if(url){
                window.location.href = url;
            }
        });
    }
    else if (type == "warning"){
        Swal.fire({
            icon: 'warning',
            html: message,
            showConfirmButton: false,
            timer: 1500
        }).then(function() {
            if(url){
                window.location.href = url;
            }
        });
    }
}

function cekTypeInput(form,isType)
{
    var hasil = 0;
    $(form+' :input').each(function() {
        if(typeof($(this).attr('type'))!== undefined){
            if($(this).attr('type')==isType){
                hasil++;
            }
        }
    });

    if(hasil*1>0){
        return true;
    }
    else{
        return false;
    }
}

function Capitalize(str)
{
    return str.replace (/\w\S*/g,
        function(txt)
        {  return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase(); } );
}

function requestAjax(method, action, data, div=null)
{
    let isMethod = method.toUpperCase();

    if(cekTypeInput(div,'file'))
    {
        return $.ajax({
            url: action,
            type: isMethod,
            data: data,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                if(notEmpty(div)){
                    myLoad('start',div);
                }
            }
        });
    }
    else
    {
        return $.ajax({
            url: action,
            type: isMethod,
            data: data,
            dataType: 'json',
            cache: false,
            beforeSend: function(){
                if(notEmpty(div)){
                    myLoad('start',div);
                }
            }
        });
    }
}

function cekElement(param)
{
    if($(param).length > 0){
        return true;
    }
    return false
}

function myModal(modal)
{
    if (notEmpty(modal)) {
        $(modal).modal({
            keyboard: false,
            backdrop: 'static'
        });
    }
    else {
        errorMessage('modal is empty');
    }
}

function notEmpty(string)
{
    //debugger
    var v = false;
    if (string != null && string != '' && string != 'undefined') {
        v = true;
    }
    return v;
}

function myLoad(mode, param)
{
    if(mode == 'start'){
        $(param).block({
            message: '<div class="spinner-border text-danger" role="status"><span class="visually-hidden">Loading...</span></div>',
            overlayCSS: {
                backgroundColor: '#fff',
                opacity: 0.8,
                cursor: 'wait'
            },
            css: {
                border: 0,
                padding: 0,
                backgroundColor: 'transparent'
            }
        });
    }
    else{
        $(param).unblock();
    }
}

function errorValidation(errors)
{
    var title = 'Plase check your field';
    var message = '<ul>';
    response  = JSON.parse(errors.responseText);
    jQuery.each(response .errors, function(key, value){
        message = message + '<li>' + value + '</li>';
    });
    message = message + '</ul>';

    myAlert('warning',message);
}

function errorMessage(error)
{
    if(document.readyState == 'complete' ){
        if(notEmpty(error.status))
        {
            if(error.status == 422)
            {
                //validation
                errorValidation(error);
            }
            else
            {
                if (error.status != 0) {
                    var msg = "SOMETHING WENT WRONG<br /> PLEASE TRY AGAIN...";
                    myAlert('failed', msg);
                    console.error(error);
                }
            }
        }
        else
        {
            if (error.status != 0) {
                var msg = "SOMETHING WENT WRONG<br /> PLEASE TRY AGAIN...";
                myAlert('failed', msg);
                console.error(error);
            }
        }
    }
}

function objectifyForm(formArray)
{
    var returnArray = {};
    for (var i = 0; i < formArray.length; i++){
        returnArray[formArray[i]['name']] = formArray[i]['value'];
    }
    return returnArray;
}

function reformJson(dataArray)
{
    if (typeof dataArray !== "undefined") {
        return JSON.parse(dataArray.replace(/&quot;/g,'"'));
    }
    else{
        return [];
    }
}

function FormatNumber(harga,desimal=0){
	harga=parseFloat(harga);
	harga=harga.toFixed(desimal);

	s = addSeparatorsNF(harga, '.', '.', ',');
	return s;
}

function addSeparatorsNF(nStr, inD, outD, sep){
	nStr += '';
	var dpos = nStr.indexOf(inD);
	var nStrEnd = '';
	if (dpos != -1) {
		nStrEnd = outD + nStr.substring(dpos + 1, nStr.length);
		nStr = nStr.substring(0, dpos);
	}
	var rgx = /(\d+)(\d{3})/;
	while (rgx.test(nStr)) {
		nStr = nStr.replace(rgx, '$1' + sep + '$2');
	}
	return nStr + nStrEnd;
}
