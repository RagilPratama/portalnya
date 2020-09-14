// param toastr
toastr.options.timeOut = 1500;
    
// serialize form data into object
$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

// chart color
var chartColors = ['#b2d4f5', '#fcc3a7', '#8fd1bb', '#f8b4c9', '#d3bdeb', '#83d1da', '#99a0f9', '#e597d2', '#d1d9dc', '#fccaca', '#85a1bb'];

