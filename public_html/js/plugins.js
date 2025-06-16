(function ($, window, document, undefined) {

    "use strict";
    // JQUERY PLUGIN BOILERPLATE
    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.

    // window and document are passed through as local variable rather than global
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially when both are regularly referenced in your plugin).
    // Create the defaults once

    var pluginName = "ajaxtable";
    var $reqParam = {
    };

    var $urlVars = getUrlVars();
    var Loader = window['LOADER'];

    $.fn.offsetWithMarginPadding = function () {
        var offset = this.offset()
        offset.top += parseInt(this.css("margin-top"))
        offset.top += parseInt(this.css("padding-top"))
        offset.left += parseInt(this.css("margin-left"))
        offset.left += parseInt(this.css("padding-left"))
        return offset
    }

    function Plugin(element, options)
    {
        this.element = element;
        var defaults = {
            url: '/', //ajax url
            lazyload: true, //try to load data when plugin loads,
            // will skip if the  body already contains data for the first time
            body: 'tbody', //list body element
            sortClass: '.sortableHeading', //class which will trigger the sort request
            caretUp: '<span class="ajaxCaret fa fa-caret-up"></span>', // sort caret up icon
            caretDown: '<span class="ajaxCaret fa fa-caret-down"></span>', // sort caret down icon
            ascendingClass: 'ascending', //sort class for ascending state
            descendingClass: 'descending', // sort class for descending state
            pageNo: 'page', // query pagination page parameter
            orderBy: 'orderBy', // query order by parameter
            orderType: 'orderType', // query order type parameter
            quickSearch: 'quickSearch',
            loadMore: 0, //paging type page more or default
            loadMorePrepend: 0,
            paginationLink: 'a', // pagination link selector
            paginationWrapper: '#paginationWrapper', // pagination container selector
            form: '.ajaxtableForm', // linked form selector
            formRestBtn: '.ajaxtableFormReset', // reset btn selector (OMITTED FOR NOW)
            history: 1, // should the request be added to the browser history
            processQuery: 1, //show initial load use the url parameters as well.
            callback: false // callback after success fetching the result
        };
        this.settings = $.extend(defaults, options, $(this.element).data());
        this.settings['firstRequest'] = true;
        this.settings['formSubmitted'] = false;
        this.settings.history = parseInt(this.settings.history);
        this.settings.processQuery = parseInt(this.settings.processQuery);
        this.settings.loadMorePrepend = parseInt(this.settings.loadMorePrepend);
        this._defaults = defaults;
        this._name = pluginName;
        $urlVars = this.settings.processQuery ? $urlVars : {};
        this.init();
    }

    // Avoid Plugin.prototype conflicts
    $.extend(Plugin.prototype, {
        init: function () {
            this.initConfig(this.element, this.settings);
            this.wrapTable(this.element, this.settings);
            this.registerEvents(this.element, this.settings);
            if (this.settings.lazyload) {
                fetchResults(this.element, this.settings);
            }
        },
        initConfig: function ($element, $settings) {
        },
        wrapTable: function ($element, $settings) {
            $($element).addClass('ajaxtable-initialized');
            $($element).wrap('<div class="ajaxtable-wrapper" />');
            var orderBy = $urlVars['orderBy'];
            if (orderBy) {
                var caret = $urlVars['orderType'] == 'DESC' ? $settings.caretDown : $settings.caretUp;
                var sortableHeading = $($element).find("th[data-orderBy='" + orderBy + "']");
                if (sortableHeading.find('span').length == 0) {
                    sortableHeading.append(caret);
                }
            }
        },

        registerEvents: function ($element, $settings) {
            $($element).on('ajaxtable:reload', function(e){
                $settings['firstRequest'] = false;
                $settings['formSubmitted'] = true;
                fetchResults($element, $settings);
            });
            var $form = $($settings.form);
            if ($form.length > 0) {
                $(document).off('submit', $settings.form);
                $(document).on('submit', $settings.form, function(e){
                    $reqParam = objectifyForm($form.find(":input:visible, [type=hidden]").serializeArray());
                    $reqParam['page'] = 1;
                    $settings['firstRequest'] = false;
                    $settings['formSubmitted'] = true;
                    fetchResults($element, $settings);
                    e.preventDefault();
                });
                $(document).on('click', $settings.formRestBtn, function (e) {
                    e.preventDefault();
                    var parentForm = $(this).closest('form' + $settings.form);
                    if (parentForm.length > 0) {
                        $form.find('select, .form-control').val('').trigger('change');
                    }
                });
            }
            $($element).off('click', $settings.sortClass);
            $($element).on('click', $settings.sortClass, function (e) {
                e.preventDefault();
                var $newClass = $settings.ascendingClass;
                var caret = $settings.caretUp;
                var $orderType = $settings.orderType;
                $reqParam[$settings.orderBy] = $(this).attr('data-orderBy');
                $reqParam[$orderType] = 'ASC';

                if ($(this).hasClass($settings.ascendingClass)) {
                    $(this).removeClass($settings.ascendingClass);
                    $newClass = $settings.descendingClass;
                    caret = $settings.caretDown;
                    $reqParam[$orderType] = 'DESC';
                } else {
                    $(this).removeClass($settings.descendingClass);
                }
                $($element).find('th').removeClass($settings.ascendingClass).removeClass($settings.descendingClass).find('span').remove();
                $(this).addClass($newClass).find('span').remove();
                $(this).append(caret);
                $settings['firstRequest'] = false;
                $settings['formSubmitted'] = true;
                $reqParam['page'] = 1;
                fetchResults($element, $settings);
            });
            $(document).off('click', $settings.paginationWrapper + ' ' + $settings.paginationLink);
            $(document).on('click', $settings.paginationWrapper + ' ' + $settings.paginationLink, function (e) {
                e.preventDefault();

                if ($(this).parent('li').hasClass('active')) {
                    return false;
                }
                $reqParam[$settings.pageNo] = getParameterByName($settings.pageNo, $(this).attr('href'));
                $settings['firstRequest'] = false;
                $($settings.paginationWrapper + ' ' + $settings.paginationLink).addClass('disabled');
                fetchResults($element, $settings);
                return false;
            });
            window.onpopstate = function (e) {
                if (e.state) {
                    var response = e.state['response'];
                    if (response) {
                        var tbody = $($element).find($settings.body);
                        var pagWrapper = $($settings.paginationWrapper);
                        tbody.empty();
                        pagWrapper.empty();
                        tbody.append(response.data);
                        pagWrapper.append(response.pagination);
                    }
                }
            };
        }
    });

    $.fn[pluginName] = function (options) {
        return this.each(function () {
            new Plugin(this, options);
        });
    };

    function fetchResults($element, $settings) {
        var tbody = $($element).find($settings.body);
        var pagWrapper = $($settings.paginationWrapper);
        if (tbody.children().length > 0 && $settings['firstRequest']) {
            return;
        }
        Loader.init();
        Loader.set(15);
        $reqParam = $.extend({}, $urlVars, $reqParam);
        $.ajax({
            type: "GET",
            url: $settings.url,
            data: $reqParam,
            cache: false
        }).done(function (response) {
            Loader.set(40);
            pagWrapper.empty();
            if (!$settings.loadMore || $settings['formSubmitted']) {
                tbody.empty();
                if ($settings.history) {
                    updateHistoryAfterAjax(response, $reqParam);
                }
            }
            if ($settings.loadMore && $settings.loadMorePrepend) {
                tbody.prepend(response.data);
            } else {
                tbody.append(response.data);
            }
            pagWrapper.append(response.pagination);
            window['processResponse'](response, $($element));
            if ($settings.callback) {
                eval($settings.callback + '(response)');
            }
            Loader.finish();
            cleanUpAfterSubmit($element, $settings);
        }).fail(function (jqXHR, textStatus, errorThrown) {
        });
    }

    function cleanUpAfterSubmit($element, $settings) {
        if (window['feather']) {
            window['feather'].replace();
        }
        if (window['embedLightbox']) {
            window['embedLightbox']();
        }
        $(document).trigger('ajaxTable-loaded');
        var $form = $($settings.form);
        $($settings.paginationWrapper + ' ' + $settings.paginationLink).removeClass('disabled');
        if ($form.length > 0) {
            $settings['formSubmitted'] = false;
            $form.find('select, .form-control').removeAttr('disabled');
        }
    }

})(jQuery, window, document);
function getParameterByName(name, $url) {
    var name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
    var results = regex.exec($url);
    return results === null ? "" : results[1].replace(/\+/g, " ");
}

function getUrlVars() {
    var vars = {},
        hash;
    if (window.location.href.indexOf('?') !== -1) {
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        if (hashes.length > 0) {
            for (var i = 0; i < hashes.length; i++) {
                hash = hashes[i].split('=');
                if (typeof hash[0] === 'undefined' || hash[0] == '') {
                    continue;
                }
                if (vars[decodeURIComponent(hash[0])] != null) {
                    if (!vars[decodeURIComponent(hash[0])].push) {
                        vars[decodeURIComponent(hash[0])] = [vars[decodeURIComponent(hash[0])]];
                    }
                    vars[decodeURIComponent(hash[0])].push(decodeURIComponent(hash[1]));
                } else {
                    vars[decodeURIComponent(hash[0])] = decodeURIComponent(hash[1]);
                }
            }
        }
    }
    return vars;
}

function getActivePage($pageNo, $element) {
    $($element).parent('li').removeClass('active');
    $($element).each(function (i, v) {
        if ($(this).text() == $pageNo) {
            $(this).parent('li').addClass('active');
            return true;
        }
    });
}

function updateHistoryAfterAjax(response, parameters) {
    var params = decodeURIComponent($.param(parameters));
    var url = window.location.href.split("?");
    window.history.pushState({
        "response": response
    }, document.title, url[0] + "?" + params);
}

function objectifyForm(formArray) {
    var arrayData, objectData;
    arrayData = formArray;
    objectData = {};
    $.each(arrayData, function () {
        var value;
        if (this.value != null) {
            value = this.value;
        } else {
            value = '';
        }
        if (objectData[this.name] != null) {
            if (!objectData[this.name].push) {
                objectData[this.name] = [objectData[this.name]];
            }
            objectData[this.name].push(value);
        } else {
            objectData[this.name] = value;
        }
    });
    return objectData;
}

window['loadAjaxTable'] = function () {
    $(".ajaxtable:not(.ajaxtable-initialized)").ajaxtable();
}
