;
var NotifyTimer;
(function ($, window, document, undefined) {
    $(function () {

        /*//Global Events for our app
         This is to alert when the user clicks the delete button*/
        $(document).on('click', "a.confirm", function (e) {
            e.stopPropagation();
            var confirms = confirm($(this).attr('data-confirm') || 'Are you sure you want to perform this action?');
            if (!confirms) {
                e.stopImmediatePropagation();
                e.preventDefault();
                return false;
            }
        });

        $(".item-arena").on('click', '.item-delete', function (e) {
            $(this).closest($(this).attr('data-row')).remove();
            e.preventDefault();
            return false;
        });

        /*//multiple checkbox select */
        $(".checkbox-boss").click(function (e) {
            if ($(this).is(':checked')) {
                $(".checkbox-slaves").prop('checked', true).attr('checked', 'checked');
                //due to tbeme error we need to loop through every slaves and change its parent span class
                //This really sucks :(
                $(".checkbox-slaves").each(function (i, v) {
                    $(this).parent('span').addClass('checked')
                });
            } else {
                $(".checkbox-slaves").prop('checked', false).removeAttr('checked');
                //due to tbeme error we need to loop through every slaves and change its parent span class
                //This really sucks :(
                $(".checkbox-slaves").each(function (i, v) {
                    $(this).parent('span').removeClass('checked')
                });
            }
        });


        $(".deleteArena").on('click', 'a.ajaxdelete', function (e) {
            e.stopPropagation();
            var $request = new RequestManager.Ajax();
            var confirms;
            if ($(this).attr('data-confirm')) {
                confirms = confirm($(this).attr('data-confirm'));
            } else {
                confirms = confirm('Are you sure you want to delete the selected item ?');
            }

            if (!confirms) {
                e.preventDefault();
                return false;

            }
            var config = {
                url: $(this).attr('data-url'),
                data: {
                    '_delete_token': $(this).attr('data-token')
                },
                type: 'GET'
            };
            var This = $(this);


            $request.Html(config).done(function (response) {
                if (response == '1') {
                    //alert(data);
                    if (This.data('prev')) {
                        /* console.log('should delete previous');
                         console.log(This.closest('.deleteBox').prev('.deleteBox'));*/
                        This.closest('.deleteBox').prev('.deleteBox').addClass('deleting').hide(2000);
                    }
                    This.closest('.deleteBox').addClass('deleting').hide(2000);
                } else {
                    alert('Delete Failed');
                }
                //Loader.finish();
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log(errorThrown)
            });

            e.preventDefault();
            /* e.stopPropagation();*/
        });

        $(document).on('click', '.modalFetcher', function (e) {
            e.preventDefault();
            var modal = $($(this).attr('data-target'));
            var data = $(this).data();
            $.get($(this).attr('href'), data, function (response) {
                modal.empty().append(response);
                modal.modal({
                    show: true
                });
            });
            e.preventDefault();
            return false;
        });

        $(document).on('submit', '.bulk-action-form', function (e) {
            if ($(this).find('.bulk-items:checked').length == 0) {
                alert("Please select at least one item");
                e.preventDefault();
                return false;
            }
        });

        $(document).on('click', '.removeRow', function (e) {
            $(this).closest($(this).attr('data-target')).remove();
            e.preventDefault();
            return false;
        });


        //ajax form submit
        $(document).on('submit', '.ajaxForm', function (e) {
            console.log(e);
            var $request = new RequestManager.Ajax();
            Loader.init();
            var submitBtn = $(this).find('[type="submit"]');

            var formData = new FormData($(this)[0]);
            /*submitBtn.each(function(i, v){
                var el = $(v);
                if(el.attr('name') !== 'undefined'){
                    formData.append(el.attr('name'), el.val());
                }
            });*/
            var config = {
                url: $(this).attr('action'),
                data: formData,
                method: $(this).attr('method'),
                processData: false,
                contentType: false
            };
            submitBtn.addClass('disabled');
            var This = $(this);

            var $loader = $(This.attr('data-loader'));
            if($loader.length){
                $loader.removeClass('d-none');
            }
            if(This.attr('data-disable-input')){
                This.find('input').prop('disabled', true);
            }
            $request.post(config).done(function (response) {
                Loader.set(80);
                $request.processResponse(response, This);
                Loader.finish();
                submitBtn.removeClass('disabled');
                $loader.addClass('d-none');
                if(This.attr('data-disable-input')){
                    This.find('input').prop('disabled', false);
                }

            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log(errorThrown);
                submitBtn.removeClass('disabled');
                $loader.addClass('d-none');
                if(This.attr('data-disable-input')){
                    This.find('input').prop('disabled', false);
                }

            });
            e.preventDefault();
            return false;
        });


        $(document).on('submit', '.simpleAjaxForm', function (e) {
            var $request = new RequestManager.Ajax();
            Loader.init();
            var submitBtn = $(this).find('.submit-btn');
            /*submitBtn.addClass('disabled');*/
            var config = {
                url: $(this).attr('action'),
                data: new FormData($(this)[0]),
                dataType: 'html',
                /*method: $(this).attr('method'),*/
                processData: false,
                contentType: false
            };

            var This = $(this);
            $request.post(config).done(function (response) {
                /*  Loader.set(80);*/
                console.log(response);
                //$request.processResponse(response, This);
                Loader.finish();
                /*submitBtn.removeClass('disabled');*/

            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log(errorThrown)
            });
            e.preventDefault();
            return false;
        });


        $(document).on('change', '.actionOnChange', function (e) {
            var $request = new RequestManager.Ajax();
            var config = {
                url: $(this).attr('data-url'),
                data: {
                    _value: $(this).val()
                }
            };
            var This = $(this);
            $request.get(config).done(function (response) {
                // console.log(response);
                // console.log($(This.attr('data-change')));
                if (This.attr('data-append')) {
                    $(This.attr('data-change')).empty().append(response.data);
                } else {
                    $(This.attr('data-change')).val(response.data);
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log(errorThrown)
            });
        });

        $(document).on('change', '.appendOnChange', function (e) {
            if ($(this).val() == '') {
                return;
            }
            var container = $($(this).attr('data-container'));
            console.log(container);
            var resultContainer = $($(this).attr('data-result-container'));
            var This = $(this);
            var data = {};
            data[This.attr('name')] = This.val();
            if (This.attr('data-items')) {
                var items = JSON.parse($(this).attr('data-items'));
                for (var i in items) {
                    var elem = container.find(items[i]);
                    data[elem.attr('name')] = elem.val();
                }
            }

            console.log(data);
            var $request = new RequestManager.Ajax();
            var config = {
                url: $(this).attr('data-url'),
                data: data
            };

            $request.get(config).done(function (response) {
                /*console.log(response);*/
                if (This.attr('data-empty')) {
                    resultContainer.empty();
                }
                resultContainer.append(response.data);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(textStatus);
                console.log(errorThrown)
            });
        });


        /*low lets update the lazy selectors*/
        $(".lazySelector").each(function (i, v) {
            var selectedVal = $(this).attr('data-selected');
            console.log(selectedVal);
            $(this).find('option').each(function (i, v) {
                if ($(this).val() == selectedVal) {
                    $(this).attr('selected', 'selected')
                }
                return true;
            })
        });
        $("div").on('click', '.close', function (e) {
            $(this).closest('.panel-body').remove();
        });

        $(document).on('keyup', '.quickSearchFormInput', debounce(function (e) {
            $(this).closest('form').submit();
        }, 400));


        var headerSearchContainer = $(".header-search-container");
        $(document).on('keyup', '.ajax-keyword-search', debounce(function (e) {
            headerSearchContainer.empty().addClass('hidden');
            var keyword = $(this).val();
            var url = $(this).attr('data-url');

            $.get(url, {
                keyword: keyword
            }).done(function (response) {
                headerSearchContainer.append(response).removeClass('hidden');

            });

        }, 400));

        $(document).on('blur', '.ajax-keyword-search', function (e) {
            setTimeout(function () {
                headerSearchContainer.empty().addClass('hidden');
            }, 500);
        });

    });




    function debounce(func, wait, immediate) {
        var timeout;
        return function () {
            var context = this,
                args = arguments;
            var later = function () {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            var callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    };
})(jQuery, Window, document);

function validateFloatKeyPress(el, evt) {
    var charCode = (evt.which) ? evt.which : event.keyCode;
    var number = el.value.split('.');
    if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    if (number.length > 1 && charCode == 46) {
        return false;
    }
    var caratPos = getSelectionStart(el);
    var dotPos = el.value.indexOf(".");
    if (caratPos > dotPos && dotPos > -1 && (number[1].length > 1)) {
        return false;
    }
    return true;
}

function getSelectionStart(o) {
    if (o.createTextRange) {
        var r = document.selection.createRange().duplicate()
        r.moveEnd('character', o.value.length)
        if (r.text == '') return o.value.length
        return o.value.lastIndexOf(r.text)
    } else return o.selectionStart
}
