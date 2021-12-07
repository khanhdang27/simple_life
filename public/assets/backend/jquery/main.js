/*function loadTheme() {
    setTheme('Dark');
}

function setTheme(theme) {
    if (theme == 'Dark') {
        $('#current-theme').text(theme);
        $(':root').css('--main-color', '#000000');
        console.log($(':root'));
    }
}*/
$(document).ready(function () {
    /***** Pjax Paginate *****/
    if ($.support.pjax) {
        $(document).on('pjax:timeout', function (event) {
            // Prevent default timeout redirection behavior
            event.preventDefault()
        })

        $(document).on('click', '.pjax-table ul.pagination a', function (event) {
            console.log('.pjax-table')
            $.pjax.click(event, {container: '.pjax-table', push: false})
        })
    }

    //Select2
    $(document).find('.select2').select2();
    $('input.datetime, input.date, input.time, input.month, input.year').attr("autocomplete", "off")
    /***** Action delete *****/
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();
        var action = $(this).attr('href');
        var lang = $('html').attr('lang');
        var title = (lang === 'zh-TW') ? "你確定嗎?" : "Are you sure?";
        var text = (lang === 'zh-TW') ? "您将无法还原此内容!" : "You won't be able to revert this!";

        swal.fire({
            title: title,
            text: text,
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: (lang === 'zh-TW') ? '刪除' : 'Delete',
            confirmButtonColor: "#d33",
            cancelButtonText: (lang === 'zh-TW') ? '取消' : 'Cancel',
        }).then((willDelete) => {
            if (willDelete.isConfirmed) {
                window.location.replace(action);
            }
        });
    });

    /***** Action Clear Search *****/
    $(document).on('click', 'button.clear', function (event) {
        event.preventDefault();
        var form = $(this).parents('form');
        form.find('input[type="number"], input[type="text"], input[type="checkbox"]').attr('disabled', 'disabled');
        form.find('select').attr('disabled', 'disabled');
        form.trigger('submit');
    });

    /***** Action Check all item in table *****/
    $(document).on('click', '.select-all', function () {
        var class_child = $(this).attr('id');
        if (class_child !== '') {
            var child = $('input.' + class_child);
            if (child.length > 0) {
                console.log('cl');
                child.not(this).prop('checked', this.checked);
            } else {
                if (!$(this).hasClass('select-all-with-other-child')) {
                    $('input.checkbox-item').not(this).prop('checked', this.checked);
                }
            }
        } else {
            console.log('ccl');
            $('input.checkbox-item').not(this).prop('checked', this.checked);
        }
    });

    /*********** Datetime Picker *************/
    //VIETNAM CALENDAR
    $.fn.datetimepicker.dates['vn'] = {
        days: ["Chủ nhật", "Thứ hai", "Thứ ba", "Thứ tư", "Thứ năm", "Thứ sáu", "Thứ bảy", "Chủ nhật"],
        daysShort: ["CNhật", "Hai", "Ba", "Tư", "Năm", "Sáu", "Bảy", "CNhật"],
        daysMin: ["CN", "T2", "T3", "T4", "T5", "T6", "T7", "CN"],
        months: ["Tháng một", "Tháng hai", "Tháng ba", "Tháng tư", "Tháng năm", "Tháng sáu", "Tháng bảy", "Tháng tám", "Tháng chín", "Tháng mười", "Tháng mười một", "Tháng mười hai"],
        monthsShort: ["Th. 1", "Th. 2", "Th. 3", "Th. 4", "Th. 5", "Th. 6", "Th. 7", "Th. 8", "Th. 9", "Th. 10", "Th. 11", "Th. 12"],
        today: "Hôm nay",
        meridiem: ['SA', 'CH']
    };

    var lang = $('html').attr('lang');
    $('input.datetime').datetimepicker({
        format: 'dd-mm-yyyy hh:ii',
        fontAwesome: true,
        autoclose: true,
        todayHighlight: true,
        todayBtn: true,
        language: lang,
        //VN Calendar
        /*format: 'dd-mm-yyyy HH:ii P',
        language: 'vn',
        showMeridian: true,*/
    });
    $('input.date').datetimepicker({
        format: 'dd-mm-yyyy',
        fontAwesome: true,
        autoclose: true,
        todayHighlight: true,
        startView: 2, // 0: hour current, 1: time in date current, 2: date
                      // in month current, 3: month in year current, 4 year
                      // in decade current
        minView: 2,
        todayBtn: true,
        language: lang,
    });
    $('input.time').datetimepicker({
        format: 'hh:ii',
        fontAwesome: true,
        autoclose: true,
        startView: 1,
        language: lang,
    });
    $('input.month').datetimepicker({
        format: 'mm-yyyy',
        fontAwesome: true,
        autoclose: true,
        startView: 3,
        minView: 3,
        language: lang,
    });
    $('input.year').datetimepicker({
        format: 'yyyy',
        fontAwesome: true,
        autoclose: true,
        startView: 4,
        minView: 4,
        language: lang,
    });
    /***********************************************************************/

    /** Checkbox Style**/
    $.each($('input[type=checkbox]'), function (i, item) {
        var checkbox_id = $(item).attr('id');
        var parent = $(item).parent();
        if (checkbox_id === null || checkbox_id === undefined) {
            $(item).attr('id', uniqueId());
            checkbox_id = $(item).attr('id');
        }
        if (parent.find('.checkmark').html() === undefined) {
            var checkbox_group;
            if (typeof $(item).attr('disabled') !== typeof undefined && $(item).attr('disabled') !== false) {
                checkbox_group = parent.html() + '<span class="checkmark checkmark-disabled"></span>';
            } else {
                checkbox_group = parent.html() + '<span class="checkmark"></span>';
            }
            parent.html('');
            var check_mark = '<label class="selection-style-label" for="' + checkbox_id + '">' + checkbox_group + '</label>';
            parent.html(check_mark);
        }
    });

    /** Radio Style**/
    $.each($('input[type=radio]'), function (i, item) {
        var radio_id = $(item).attr('id');
        var parent = $(item).parent();
        if (radio_id === null || radio_id === undefined) {
            $(item).attr('id', uniqueId());
            radio_id = $(item).attr('id');
        }

        if (parent.find('.radiomark').html() === undefined) {
            var radio_group = parent.html() + '<span class="radiomark"></span>';
            parent.html('');
            var radio_mark = '<label class="selection-style-label" for="' + radio_id + '">' + radio_group + '</label>';
            parent.html(radio_mark);
        }
    });

    /** Upload Style**/
    $('input[type="file"]').change(function (e) {
        var file_name = e.target.files[0].name;
        $(this).siblings('label#upload-display').html('<i class="fas fa-upload"></i> ' + file_name);
    });
});

// GenarateID
function uniqueId() {
    return Math.round(new Date().getTime() + 1000 + (Math.random() * 100)) + (Math.random() * 100);
}

/***** Show alert *****/
function showAlert(selector) {
    if (selector !== undefined) {
        selector.show().animate({
            right: "10px"
        }, 500);

        setTimeout(function () {
            selector.animate({
                right: "-501px"
            }, 3000);
        }, 10000);
        setTimeout(function () {
            selector.remove();
        }, 14000);
    }
    if ($('.alert-primary').html() !== undefined) {
        $('.alert-danger').css('top', '120px');
    }
    $('.alert-close').click(function () {
        var parent = $(this).parents('.alert-fade-out');
        parent.animate({
            right: "-500px"
        }, 1000);
        setTimeout(function () {
            parent.remove();
        }, 2100);
    });
}

