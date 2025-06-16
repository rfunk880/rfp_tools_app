var Loader = {};
(function ($, window, document, undefined) {
    Loader.init = function () {
        if ($("#loadingbar").length == 0) {
            $("body").append('<div id="loadingbar" />');
        }
        Loader.$loaderElem = $("#loadingbar");
        Loader.$loaderElem.addClass("waiting").append($("<dt/><dd/>"));
        return this;
    };
    Loader.set = function (percent) {
        Loader.$loaderElem.width(
            percent + Math.random() * (100 - percent) + "%"
        );
        return this;
    };
    Loader.finish = function () {
        Loader.$loaderElem
            .width("101%")
            .delay(200)
            .fadeOut(400, function () {
                $(this).remove();
            });
    };
    window["LOADER"] = Loader;
})(jQuery, window, document);

/* Reusable Dom events and actions scripta

1. AJAX DELETE
-> delete button/link must be inside .deleteArena
-> add .deleteBox class to the element you want to be removed from the DOM. Must be in a parent tree of the clicked button.
-> data-url must contain the link to server url and must return [success => 1] atleast as json response.
-> All options from ajax form can be used in the delete link/btn as well (see below).


2. CASCADING SELECT
-> select element must have class .actionOnChange
-> data-url attribute must contain server side url to fetch the data from and options must be returned in data field of the response json. ex [success => 1, data => <option....]
-> data-change attribute must contain jquery selector for related select.
   for ex  data-change="#task_select" means task_select element will be updated.
-> data-empty (optional) attribute must contain "," separated jquery selector,
   these elements will be emptied out except for first option.
-> data-loading (optional) attribute must contain loading message for the updating select when ajax call
   is being made. defualt 'loading...' is used.
-> data-row (optional) attribute must contain jquery selector. This defines the context of the operation.
-> data-row_data (optional) attribute must contain "," separated jquery selector. Defines the elements inside the
   context defined above. Values of these elements will be sent to the server.
   Naming convention used : row_data_ELEMENT_1_NAME, row_data_ELEMENT_2_NAME ...
-> data-global_data (optional) same as row_data but outside the context.


3. AJAX FORM
-> form must have class .ajaxForm
-> data-form-reset (optional) Will reset the form if server returns [succes => 1]
-> data-notification-area (optional) attribute must contain jquery selector. Defines where to show the server notification.
   default location will be used if omitted. Server must send [notification => NOTIFICATION_BLOCK] in response.
-> data-notification-animation (optional) defines if screen must slide to notification area.
   Useful when form is too long.
-> data-result-container (optional) attribute must contain jquery selector.
   Defines where to (append|prepend|replace) the server [data => CONTENT] value.
-> data-result-action-type (optional) attribute must contain (append|prepend|empty) values. Default append.
   Defines how the content inside the data-result-container is placed.
-> data-close-modal (optional) defines if modal should be closed if server returns [success => 1]
-> data-before-send (optional) must contain json string for an array containing GLOBAL SCOPE JS FUNCTIONS.
-> data-hooks (optional) must contain json string for an array containing GLOBAL SCOPE JS FUNCTIONS.
   Will receive the form element and server response as parameter.
-> data-reload-table (string) will reload the table if the value is valid ajaxtable element.
*/

window["focusInputRelativeTo"] = function (rootEl, input = ".input") {
    let $root = $(rootEl);
    setTimeout(() => {
        // console.log(name, this.$refs[name]);
        // if(name && this.$refs[name]){
        //     this.$refs[name].focus();
        //     return;
        // }

        // if(this.$refs['input']){
        //     this.$refs['input'].focus();
        // }

        let $el = $root.find(input);
        if ($el.length) {
            $el[0].focus();
        }
        // console.log(this.$el);
    }, 1000);
};

window["getTimeZone"] = function () {
    var offset = new Date().getTimezoneOffset(),
        o = Math.abs(offset);
    return (
        (offset < 0 ? "+" : "-") +
        ("00" + Math.floor(o / 60)).slice(-2) +
        ":" +
        ("00" + (o % 60)).slice(-2)
    );
};

window["LIVEWIRE_INLINE"] = {
    focusInput(input = ".input") {
        let $root = $(this.$root);
        setTimeout(() => {
            // console.log(name, this.$refs[name]);
            // if(name && this.$refs[name]){
            //     this.$refs[name].focus();
            //     return;
            // }

            // if(this.$refs['input']){
            //     this.$refs['input'].focus();
            // }

            let $el = $root.find(input);
            if ($el.length) {
                $el[0].focus();
            }
            // console.log(this.$el);
        }, 1000);
    },
};

(function ($, window, undefined) {
    $(function () {
        $(document).on("click", ".confirm", function (e) {
            if (!confirm($(this).attr("data-message"))) {
                e.preventDefault();
                return false;
            }

            return true;
        });

        /*low lets update the lazy selectors*/
        $(".lazySelector").each(function (i, v) {
            var selectedVal = $(this).attr("data-selected");
            //   console.log(selectedVal);
            $(this)
                .find("option")
                .each(function (i, v) {
                    if ($(this).val() == selectedVal) {
                        $(this)
                            .attr("selected", "selected")
                            .prop("selected", true);
                    }
                    return true;
                });
        });

        $(document).on("click", "a.ajaxdelete", function (e) {
            e.stopPropagation();
            var confirms;
            if (typeof $(this).attr("data-noconfirm") === "undefined") {
                if ($(this).attr("data-confirm")) {
                    var confirmText = $(this).attr("data-confirm");
                    const el = document.createElement("div");
                    el.innerHTML = confirmText;
                    confirms = Swal.fire({
                        title: "Be careful!!",
                        html: el,
                        confirmButtonText: "Confirm",
                        showCloseButton: true,
                        showCancelButton: true,
                    });
                } else {
                    confirms = Swal.fire({
                        title: "Alert",
                        text: "Are you sure you want to remove this item? ",
                        confirmButtonText: "Confirm",
                        // dangerMode: true,
                        showCloseButton: true,
                        showCancelButton: true,
                    });
                }
            } else {
                confirms = Promise.resolve({ isConfirmed: true });
            }

            confirms.then((result) => {
                if (result.isConfirmed) {
                    var config = {
                        url: $(this).attr("data-url"),
                        data: {
                            _delete_token: $(this).attr("data-token"),
                        },
                        type: "GET",
                    };
                    var $this = $(this);
                    var $cloned = $this.clone();

                    $.ajax(config)
                        .done(function (response) {
                            if (response["success"] == "1") {
                                if ($this.attr("data-remove")) {
                                    $($this.attr("data-remove"))
                                        .addClass("deleting")
                                        .hide(500);
                                } else {
                                    $this
                                        .closest(".deleteBox")
                                        .addClass("deleting")
                                        .hide(500);
                                }
                                processResponse(response, $cloned);
                            } else {
                                alert("Delete Failed");
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            console.log(textStatus);
                            console.log(errorThrown);
                        });
                }
            });

            e.preventDefault();

            /* e.stopPropagation();*/
        });
        /* ajax delete with text confirm */
        $(document).on("click", "a.ajaxdeleteconfirm", function (e) {
            e.stopPropagation();
            var confirms;
            var text = $(this).attr("data-confirmtext") || "delete";
            confirms = Swal.fire({
                title: 'Enter "' + text + '" to confirm.',
                input: "text",
                // inputLabel: 'Type Here',
                // inputValue: inputValue,
                inputPlaceholder: "Type Here",
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value || value != text) {
                        return "Please type " + text + " to proceed";
                    }
                },
            });

            confirms.then((result) => {
                // console.log(result);
                if (
                    result.isConfirmed &&
                    result.value &&
                    result.value == text
                ) {
                    var config = {
                        url: $(this).attr("data-url"),
                        data: {
                            _delete_token: $(this).attr("data-token"),
                        },
                        type: "GET",
                    };
                    var $this = $(this);
                    var $cloned = $this.clone();

                    $.ajax(config)
                        .done(function (response) {
                            if (response["success"] == "1") {
                                if ($this.attr("data-remove")) {
                                    $($this.attr("data-remove"))
                                        .addClass("deleting")
                                        .hide(500);
                                } else {
                                    $this
                                        .closest(".deleteBox")
                                        .addClass("deleting")
                                        .hide(500);
                                }
                                processResponse(response, $cloned);
                            } else {
                                alert("Delete Failed");
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            console.log(textStatus);
                            console.log(errorThrown);
                        });
                }
            });

            e.preventDefault();

            /* e.stopPropagation();*/
        });

        /* dynamic modal */
        $(document).on("click", ".modalFetcher", function (e) {
            e.preventDefault();

            $this = $(this);
            //console.log('modal fetcher clicked');
            var hideOld = $this.attr("data-hideold") ? true : false;
            if (hideOld) {
                //$(".modal").modal('hide');
                hideModalAsync($(".modal.show")).then(function (v) {
                    // console.log('hiding from async', v);
                    //console.log(v);
                    fetchModal($this);
                });
            } else {
                fetchModal($this);
            }
            return false;
        });

        $(document) /* .off('click', '[data-dismiss=modal]') */
            .on("click", "[data-dismiss=modal]", function (e) {
                console.log("CLOSE CLICKED");

                var modal = $(this).closest(".modal");
                console.log(modal.data("bs.modal"));
                if ((modal.data("bs.modal") || {})._isShown) {
                    // console.log(modal);
                    hideModalAsync(modal).then(function (v) {
                        console.log(v);
                    });
                } else {
                    // console.log(modal, 'notShown')
                    hideModal(modal);
                }
                e.preventDefault();
                return;
            });

        function fetchModal($elm) {
            var modal = $($elm.attr("data-target"));
            // console.log(modal);
            var data = $elm.data();
            var backDrop = $elm.attr("data-nobackdrop") ? false : true;
            // modal.empty().append($("#skeleton_modal_content").html());
            delete data["url"];

            $.get($elm.attr("href"), data, function (response) {
                // console.log(response);
                //console.log(modal);
                modal.empty().append(response);
                modal.modal("show");

                // if (window["updateAfterAjaxLoad"]) {
                //     window["updateAfterAjaxLoad"]();
                // }
            });
        }

        $(document).on("change", ".actionOnChange", function (e) {
            var config = {
                url: $(this).attr("data-url"),
                method: "GET",
                data: {
                    _value: $(this).val(),
                },
            };
            var This = $(this);
            var rowClass = This.attr("data-row") || ".row";

            var items = This.attr("data-row_data")
                ? This.attr("data-row_data").split(",")
                : [];
            //console.log(items);
            if (items.length > 0) {
                for (var i in items) {
                    var item = This.closest(rowClass).find(items[i]);
                    if (item) {
                        config.data["row_data_" + item.attr("name")] =
                            item.val();
                    }
                }
            }
            var globalItems = This.attr("data-global_data")
                ? This.attr("data-global_data").split(",")
                : [];
            //console.log(globalItems);
            if (globalItems.length > 0) {
                for (var i in globalItems) {
                    config.data["global_data_" + i] = $(globalItems[i]).val();
                }
            }

            var emptyElsString = This.attr("data-empty");
            /* if(emptyElsString){
                $(emptyElsString).empty();
            } */
            var emptyEls = emptyElsString ? emptyElsString.split(",") : [];
            if (emptyEls.length > 0) {
                for (var i in emptyEls) {
                    var emptyEl = $(emptyEls[i]);
                    emptyEl.find("option:not(:first)").remove();
                }
            }
            //console.log(config);
            var valueContainer = This.closest(rowClass).find(
                This.attr("data-change")
            );
            var loadingText = This.attr("data-loading")
                ? This.attr("data-loading")
                : "loading...";
            valueContainer
                .empty()
                .append("<option>" + loadingText + "</option>");
            $.ajax(config)
                .done(function (response) {
                    console.log(response);
                    valueContainer.empty().append(response["data"]);
                })
                .fail(function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus);
                    console.log(errorThrown);
                });
        });

        $(document).on("click", ".get_action_button", function (e) {
            let $btn = $(this);
            $btn.addClass("disabled").prop("disabled", true);
            let text = $btn.text();
            $btn.text($btn.attr("data-loading") || "Processing...");
            setTimeout(function () {
                $btn.removeClass("disabled").prop("disabled", false);
                $btn.text(text);
            }, 10000);
        });

        /* ajax form */
        $(document).on("submit", ".ajaxForm", function (e) {
            e.preventDefault();

            var $this = $(this);

            //   console.log('check validity', $this.attr('data-check-validity'));
            if ($this.attr("data-check-validity")) {
                if (!$this[0].checkValidity()) {
                    e.preventDefault();
                    e.stopPropagation();
                    return;
                }
            }

            var confirms;

            if ($this.attr("data-confirm")) {
                confirms = Swal.fire({
                    title: "Be Carefull!!",
                    text:
                        $this.attr("data-confirmmessage") ||
                        "Are you sure you want to update this item ",
                    confirmButtonText: "Confirm",
                    // dangerMode: true,
                    showCloseButton: true,
                    showCancelButton: true,
                });
            } else {
                confirms = Promise.resolve({ isConfirmed: true });
            }

            confirms.then((result) => {
                if (result.isConfirmed) {
                    if ($this.data("submitting")) {
                        return false;
                    }

                    $this.data("submitting", true);

                    var submitBtn = $(this).find('[type="submit"]');
                    //   console.log(submitBtn.first());
                    //   console.log(submitBtn.text());
                    var formData = new FormData($(this)[0]);
                    var config = {
                        url: $(this).attr("action"),
                        data: formData,
                        method: $(this).attr("method"),
                        /* dataType : 'json', */
                        processData: false,
                        contentType: false,
                    };
                    Loader.init();
                    Loader.set(80);
                    submitBtn.addClass("disabled").prop("disabled", true);

                    if ($(this).data("disableAfterSubmit")) {
                        $this
                            .find($(this).data("disableAfterSubmit"))
                            .addClass("disabled")
                            .prop("disabled", true);
                    }

                    /* run before send hooks if present */
                    if (typeof $this.attr("data-before-send") !== "undefined") {
                        var beforeSendHooks = JSON.parse(
                            $this.attr("data-before-send")
                        );
                        console.log(beforeSendHooks);
                        for (var i in beforeSendHooks) {
                            runHook(
                                beforeSendHooks[i],
                                $this,
                                null,
                                $.extend({}, $this.data())
                            );
                        }
                    }
                    $.post(config, function (response) {
                        processResponse(response, $this);
                        if (window["updateAfterAjaxLoad"]) {
                            window["updateAfterAjaxLoad"]();
                        }
                    })
                        .catch(function (jqXHR, textStatus, errorThrown) {
                            if (
                                typeof $this.attr("data-error-hooks") !==
                                "undefined"
                            ) {
                                var errorHooks = JSON.parse(
                                    $this.attr("data-before-send")
                                );
                                for (var i in errorHooks) {
                                    runHook(
                                        errorHooks[i],
                                        $this,
                                        null,
                                        $.extend({}, $this.data())
                                    );
                                }
                            }

                            //limit message only for permission for cost higher than projects budget
                            if (
                                jqXHR.status === 400 &&
                                jqXHR.responseText.indexOf(
                                    "you do not have permission to enter a cost that has exceeded this item's budget"
                                ) > -1
                            ) {
                                try {
                                    let text = JSON.parse(
                                        jqXHR.responseText
                                    ).error;

                                    var errorDiv =
                                        document.createElement("div");
                                    errorDiv.innerHTML = text;

                                    swal({
                                        title: "Permission issue.",
                                        content: errorDiv,
                                        icon: "warning",
                                        button: "Close!",
                                        dangerMode: true,
                                    });
                                } catch (e) {
                                    console.log(e);
                                }
                            }
                        })
                        .always(() => {
                            Loader.finish();
                            setTimeout(() => {
                                submitBtn
                                    .removeClass("disabled")
                                    .prop("disabled", false);
                            }, 500);
                            $this.data("submitting", false);
                        });
                }
            });

            return false;
        });

        /* check all */
        $(document).on("click", ".checkbox-master", function (e) {
            var container = $(this).attr("data-container") || document;
            var $container = $(document);
            if (container != document) {
                var $container = $(this).closest(container);
            }
            // console.log($container);

            $container
                .find(".checkbox-slaves")
                .prop("checked", $(this).is(":checked"));

            e.stopPropagation();
        });

        $(document).on("click", ".copyToClipboard", function (e) {
            var text = $(this).attr("data-copy-text");

            var $this = $(this);

            $this.popover("show");
            /* $this.popover({
                content: 'Copied to Clipboard',
            }); */
            setTimeout(function () {
                $this.popover("hide");
            }, 2000);
            copyToClipboard(text);

            e.stopPropagation();
            e.preventDefault();
            return false;
        });

        $(document).on("change", ".ajaxSubmitOnChange", function (e) {
            var $el = $(this);
            var $form = $el.closest(".ajaxForm");
            if ($form.data("submitting")) {
                return;
            }
            window["debounce"](function () {
                if ($form.data("submitting")) {
                    return;
                }
                $form.trigger("submit");
            }, 250)();
        });

        $(document).on("click", ".ajaxReset", function (e) {
            var $form = $(this).closest("form");
            if ($form.length) {
                $form[0].reset();
                window["userDropdownReset"](
                    $form.find(".custom_user_dropdown")
                );

                $form.trigger("submit");
                e.preventDefault();
            }
            return false;
        });

        $(document).on("click", ".formTrigger", function (e) {
            var form = $(this).attr("data-form");
            console.log(form);
            $(form).trigger("submit");
        });

        $(document).on("click", ".ajaxLinkTrigger", function (e) {
            e.preventDefault();

            var $elm = $(this);

            var data = JSON.parse($elm.attr("data-params") || null);

            $elm.addClass("running");
            $.post($elm.attr("href"), data, function (response) {
                // console.log('processing_response', response, $elm);
                processResponse(response, $elm);

                if (window["updateAfterAjaxLoad"]) {
                    window["updateAfterAjaxLoad"]();
                }
                $elm.removeClass("running");
                // $(".dynamic_editable").editable('hide');
                // $(".dynamic_editable_server_display").editable('hide');
            });
            return false;
        });

        /* Ghost Link Click Handler */
        /* dynamic modal */
        $(document).on("click", ".ghostEl", function (e) {
            e.preventDefault();

            // console.log('GHOST EL CLICKED');
            var $elm = $(this);

            var data = JSON.parse($elm.attr("data-params"));
            delete data["url"];
            // console.log($elm);
            data["path"] = window.location.pathname;
            $elm.addClass("running");
            $.post($elm.attr("href"), data, function (response) {
                // console.log('processing_response', response, $elm);
                processResponse(response, $elm);

                if (window["updateAfterAjaxLoad"]) {
                    window["updateAfterAjaxLoad"]();
                }
                $elm.removeClass("running");
                if (window["removeSmoothStateCache"]) {
                    window["removeSmoothStateCache"]();
                }
                // $(".dynamic_editable").editable('hide');
                // $(".dynamic_editable_server_display").editable('hide');
            });
            return false;
        });

        /* $(document).off('contextmenu', '.context_menu_handler', function(e){
            console.log('left clicked');
        }); */
        /* context menu */
        $(document).on(
            "contextmenu",
            ".context_menu_handler",
            function (event) {
                //alert("no right click");
                //console.log($(this).closest('.context_menu_container'));
                // console.log(e.pageX, ($(window).height() - (e.pageY - $(window).scrollTop())));

                var screen_width = $(window).width();
                if (screen_width <= 767) {
                    return;
                }
                open_context_menu($(this), event);
            }
        );

        $(document).on("click", ".col_mobile_actions", function (event) {
            open_context_menu_mobile($(this), event);
        });

        $(document).on("blur", ".formatMoneyOnBlur", function (e) {
            var val = parseFloat($(this).val().replace("$", ""));
            // console.log("FORMATTING MONEY", $(this).val(), val);
            $(this).val(window.my_money_format(val));
        });

        function open_context_menu(el, event) {
            var $handler = el;
            var containerClass =
                el.attr("data-context_container") || ".context_menu_container";
            var $container = $handler.closest(containerClass);
            // console.log(containerClass, $container);
            var menuClass = el.attr("data-context_menu") || ".context_menu";
            //remove all open context menu first
            $(".context_menu_container").removeClass("context_menu_activated");
            $(menuClass).hide();

            var $menu = $container.find(menuClass);
            if ($menu.length > 0) {
                var position = elMouseRelativePosition($container, event);
                // console.log(position);
                var x = position.x;
                var y = position.y;

                /* viewport visibility fix */
                if ($(window).width() < x + 300) {
                    x -= $menu.width();
                }
                if (
                    $(window).height() - (event.pageY - $(window).scrollTop()) <
                    $menu.height()
                ) {
                    y -= $menu.height();
                }
                /* viewport visibility fix end */

                $menu.css({
                    left: x,
                    top: y,
                });
                $menu.show();
                $container.addClass("context_menu_activated");
                event.preventDefault();
            }
        }

        function open_context_menu_mobile(el, event) {
            var $handler = el;

            var containerClass =
                el.attr("data-context_container") || ".context_menu_container";
            var $container = $handler.closest(containerClass);
            var $mobileMenuContainer = $container.closest(
                "#general_working_area_wrapper"
            );
            console.log($mobileMenuContainer);
            var menuClass = el.attr("data-context_menu") || ".context_menu";
            //remove all open context menu first
            $(".context_menu_container").removeClass("context_menu_activated");
            $(".context_menu").hide();

            var $menu = $container.find(menuClass);
            if ($mobileMenuContainer.find(menuClass).length == 0) {
                $mobileMenuContainer.prepend($menu);
            }
            var $mobileMenu = $mobileMenuContainer.find(menuClass);
            if ($mobileMenu.length > 0) {
                var offset = el.offset();
                console.log(offset);
                $mobileMenu
                    .css({
                        position: "fixed",
                        left: offset.left - 100,
                        top: offset.top - $(document).scrollTop(),
                    })
                    .show();
                /* viewport visibility fix end */

                $container.addClass("context_menu_activated");
                event.preventDefault();
            }
        }

        /* CLEAN UP UI HERE
        Clean all ui with this listener, when click is happening outside of the container.
        */
        $(document).on("mousedown", function (e) {
            var $el = $(e.target);
            // console.log($el);
            // console.log(e.which);

            if (
                e.which == 3 &&
                ($el.closest(".context_menu_handler").length ||
                    $el.closest(".context_menu_container").length)
            ) {
                return;
            }
            if ($el.closest(".col_mobile_actions").length && e.which == 1) {
                return;
            }
            setTimeout(function () {
                if (
                    !$el.hasClass("context_menu_link") ||
                    ($el.hasClass("context_menu_link") &&
                        (!$el.attr("data-autohide") ||
                            $el.attr("data-autohide") != "0"))
                ) {
                    $(".context_menu_container").removeClass(
                        "context_menu_activated"
                    );
                    $(".context_menu").hide();
                }
            }, 500);
            /* clean context menu */
        });

        $(document).on("change", ".submitOnChange", function (e) {
            $(this).closest("form")[0].submit();
        });

        $(document).on("change", ".perPageSelector", function (e) {
            let $form = $($(this).attr("data-form"));
            console.log($form);
            if ($form.length) {
                $form.find("[name=per_page]").val($(this).val());
                $form.submit();
            }
        });

        // Jquery Dependency

        $(document).on("keyup", "input[data-type='currency']", function (e) {
            this.value = this.value.replace(/[^\d\$\.\,]/gi, "");
        });

        $(document).on("blur", "input[data-type='currency']", function (e) {
            formatCurrency($(this), "blur");
        });

        function formatNumber(n) {
            // format number 1000000 to 1,234,567
            return n.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function formatCurrency(input, blur) {
            // appends $ to value, validates decimal side
            // and puts cursor back in right position.

            // get input value
            var input_val = input.val();

            // don't validate empty input
            if (input_val === "") {
                return;
            }

            // original length
            var original_len = input_val.length;

            // initial caret position
            var caret_pos = input.prop("selectionStart");

            // check for decimal
            if (input_val.indexOf(".") >= 0) {
                // get position of first decimal
                // this prevents multiple decimals from
                // being entered
                var decimal_pos = input_val.indexOf(".");

                // split number by decimal point
                var left_side = input_val.substring(0, decimal_pos);
                var right_side = input_val.substring(decimal_pos);

                // add commas to left side of number
                left_side = formatNumber(left_side);

                // validate right side
                right_side = formatNumber(right_side);

                // On blur make sure 2 numbers after decimal
                if (blur === "blur") {
                    right_side += "00";
                }

                // Limit decimal to only 2 digits
                right_side = right_side.substring(0, 2);

                // join number by .
                input_val = "$" + left_side + "." + right_side;
            } else {
                // no decimal entered
                // add commas to number
                // remove all non-digits
                input_val = formatNumber(input_val);
                input_val = "$" + input_val;

                // final formatting
                if (blur === "blur") {
                    input_val += ".00";
                }
            }

            // send updated string to input
            input.val(input_val);

            // put caret back in the right position
            var updated_len = input_val.length;
            caret_pos = updated_len - original_len + caret_pos;
            input[0].setSelectionRange(caret_pos, caret_pos);
        }

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener("mouseenter", Swal.stopTimer);
                toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
        });
        const SuccessToast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            background: "#d4e9e3",
            didOpen: (toast) => {
                toast.addEventListener("mouseenter", Swal.stopTimer);
                toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
            icon: "success",
        });
        const ErrorToast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 5000,
            timerProgressBar: true,
            background: "#f1c6ca",
            didOpen: (toast) => {
                toast.addEventListener("mouseenter", Swal.stopTimer);
                toast.addEventListener("mouseleave", Swal.resumeTimer);
            },
            icon: "error",
        });
    });

    window["updateAfterAjaxLoad"] = function () {
        if (window["loadAjaxTable"]) {
            window["loadAjaxTable"]();
        }
    };

    window["isJSON"] = function (something) {
        if (typeof something != "string") something = JSON.stringify(something);

        try {
            JSON.parse(something);
            return true;
        } catch (e) {
            return false;
        }
    };

    window["dropDownHook"] = function () {
        $(".custom_user_dropdown").off("shown.bs.dropdown");
        $(".custom_user_dropdown").on("shown.bs.dropdown", function (e) {
            // console.log("dropdown clicked");
            var input = $(this).find(".dropdown-menu-search");
            if (input.length) {
                input.trigger("focus");
            }
        });
    };

    window["debounce"] = function (func, threshold, execAsap) {
        var timeout;

        return function debounced() {
            var obj = this,
                args = arguments;

            function delayed() {
                if (!execAsap) func.apply(obj, args);
                timeout = null;
            }

            if (timeout) clearTimeout(timeout);
            else if (execAsap) func.apply(obj, args);

            timeout = setTimeout(delayed, threshold || 100);
        };
    };

    window["delayExecution"] = function (callback, ms) {
        var timer = 0;
        return function () {
            var context = this,
                args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            }, ms || 0);
        };
    };

    window["initAjaxSelect2"] = function () {
        $(".select2-remote").each(function (i, v) {
            console.log(Number($(v).attr("data-mininput") || 3));
            $(v).select2({
                placeholder: $(v).attr("data-placeholder") || "Search ",
                minimumInputLength: Number($(v).attr("data-mininput") || 3),
                ajax: {
                    delay: 250,
                    url: $(v).attr("data-url"),
                    data: function (params) {
                        var query = {
                            keyword: params.term,
                            page: params.page || 1,
                            type: "public",
                        };

                        // Query parameters will be ?search=[term]&type=public
                        return query;
                    },
                    processResults: function (data, params) {
                        // console.log(data);
                        params.page = params.page || 1;
                        // Transforms the top-level key of the response object from 'items' to 'results'
                        return {
                            results: data.items,
                            pagination: {
                                more:
                                    params.page * data.pagination.per_page <
                                    data.pagination.total,
                            },
                        };
                    },
                },
            });
        });
    };

    window["mappedUserSelect2Str"] = {};

    function submitForm(form) {
        //get the form element's document to create the input control with
        //(this way will work across windows in IE8)
        var button = form.ownerDocument.createElement("input");
        //make sure it can't be seen/disrupts layout (even momentarily)
        button.style.display = "none";
        //make it such that it will invoke submit if clicked
        button.type = "submit";
        //append it and click it
        form.appendChild(button).click();
        //if it was prevented, make sure we don't get a build up of buttons
        form.removeChild(button);
    }

    function copyToClipboard(content) {
        // Create a "hidden" input
        var aux = document.createElement("input");

        // Assign it the value of the specified element
        aux.setAttribute("value", content);

        // Append it to the body
        document.body.appendChild(aux);

        // Highlight its content
        aux.select();

        // Copy the highlighted text
        document.execCommand("copy");

        // Remove it from the body
        document.body.removeChild(aux);
    }

    window["processResponse"] = function (responseData, $elem) {
        // console.log(responseData, $elem);
        var defaults = {
            postProcessing: false,
            notificationArea: "#notificationArea",
            notificationAnimation: false,
            formReset: false,
        };

        /* clear all auto clearable fields */
        $elem.find(".auto_clear").empty();

        var processOrders = $.extend({}, defaults, $elem.data());
        // console.log(processOrders);
        var resultContainer = processOrders["resultContainer"];
        // console.log('is json', window['isJSON'](responseData));
        if (!window["isJSON"](responseData)) {
            return;
        }

        var responseObj =
            typeof responseData != "object"
                ? JSON.parse(responseData)
                : responseData;
        if (typeof responseObj == "object") {
            /*in case we are asked for a redirect*/
            if (responseObj.redirect) {
                /*lets check if there is a empty redirect as well*/
                if (responseObj.redirect != "") {
                    location.href = responseObj.redirect;
                    return;
                }
            }

            /*if we get data from server*/
            if (responseObj.data) {
                /* send element identifier to replace from server */
                if (responseObj.replaceWith) {
                    $(responseObj.replaceWith).replaceWith(responseObj.data);
                }
                if (resultContainer) {
                    /*if we have a container to show data to*/
                    if (processOrders["resultActionType"]) {
                        /*in case there is specific request to append or replace data*/
                        switch (processOrders["resultActionType"]) {
                            case "empty":
                                $(resultContainer)
                                    .empty()
                                    .append(responseObj.data);
                                break;
                            case "prepend":
                                $(resultContainer).prepend(responseObj.data);
                                break;
                            case "replace":
                                $(resultContainer).replaceWith(
                                    responseObj.data
                                );
                                break;
                            default:
                                $(resultContainer).append(responseObj.data);
                        }
                    } else {
                        $(resultContainer).append(responseObj.data);
                    }
                }
                /*no result container then do nothing for now*/

                if (processOrders["selectReload"]) {
                    $(processOrders["selectReload"]).trigger("change");
                }
            }
            /*no data do nothing for now*/

            /* if ajaxtable is linked */
            if (processOrders["reloadTable"]) {
                $(processOrders["reloadTable"]).trigger("ajaxtable:reload");
            }

            /* NEW CONTENT ADDITION */
            if (responseObj["content"] && processOrders["contentUpdate"]) {
                var contents = processOrders["contentUpdate"];
                for (var i in contents) {
                    var content = contents[i];
                    // console.log(content);
                    var parts = content.split(":");
                    var serverKey = parts[0];
                    var elementKey = parts[1];
                    if (responseObj["content"][serverKey]) {
                        var serverContent = responseObj["content"][serverKey];
                        // console.log(serverContent);
                        var elementParts = elementKey.split("|");
                        //console.log(elementParts);
                        var $el = $(elementParts[0]);
                        //  console.log($el);
                        var action = "append";
                        if (typeof elementParts[1] !== "undefined") {
                            action = elementParts[1];
                        }
                        // console.log(action);
                        switch (action) {
                            case "empty":
                                $el.empty().append(serverContent);
                                break;
                            case "prepend":
                                $el.prepend(serverContent);
                                break;
                            case "replace":
                                $el.replaceWith(serverContent);
                                break;

                            default:
                                $el.append(serverContent);
                        }
                    }
                }
            }

            if (responseObj.quickNotify) {
                // we will implement some kind of alert notification here
            }

            /*if we get notification form server*/
            if (responseObj.notification) {
                var notificationArea = $(processOrders["notificationArea"]);
                notificationArea.empty().append(responseObj.notification);

                setTimeout(function () {
                    notificationArea.empty();
                }, 7000);

                /*check if animation is on. Useful when form is long and notification appears on top of the page.*/
                if (
                    processOrders["notificationAnimation"] &&
                    notificationArea
                ) {
                    $("html, body").animate(
                        {
                            scrollTop: notificationArea.offset().top - 100,
                        },
                        500
                    );
                }
            }

            // console.log(processOrders, responseObj)

            if (processOrders["closeModal"] && responseObj["success"] == "1") {
                if (processOrders["closeModal"] == "1") {
                    setTimeout(function () {
                        $(".modal").modal("hide");
                        hideModal($(".modal"));
                    }, 500);
                } else {
                    setTimeout(function () {
                        // let myModal = new bootstrap.Modal($(processOrders["closeModal"])[0], {
                        //     keyboard: false
                        //   });
                        //   myModal.hide();
                        $(processOrders["closeModal"]).modal("hide");
                        hideModal($(processOrders["closeModal"]));
                    }, 500);
                }
            }

            /*finally when everything is done check if we need to reset the form*/
            if (processOrders["formReset"] && responseObj["success"] == "1") {
                $elem[0].reset();
                $elem.find(".editor_1").trumbowyg("empty");
            }
            //we reached till here so we return as it came ;D

            // console.log(processOrders['hooks']);
            /* run global hook functions if it is not a redirect. */
            if (
                typeof processOrders["hooks"] !== "undefined" &&
                typeof responseObj["success"] !== "undefined"
            ) {
                for (var i in processOrders["hooks"]) {
                    runHook(
                        processOrders["hooks"][i],
                        $elem,
                        responseObj,
                        processOrders
                    );
                }
            }
        } else {
            console.log(responseObj);
        }

        /* blur inputs */
        $elem.find(".blurOnSubmit").blur();
    };

    function runHook(hookName, $elm, response, processOrders) {
        if (
            typeof window[hookName] !== "undefined" &&
            typeof window[hookName] === "function"
        ) {
            window[hookName]($elm, response, processOrders);
        }
    }

    function elMouseRelativePosition(el, event) {
        // console.log(el.position());
        var left = el.offsetWithMarginPadding().left;
        var top = el.offsetWithMarginPadding().top;

        x = event.pageX - left;
        y = event.pageY - top;

        return {
            x: x,
            y: y,
        };
    }

    function hideModalAsync($elm) {
        // console.log($elm);

        return new Promise(function (resolve, reject) {
            // $elm.modal('hide');
            $elm.removeClass("show");
            $elm.data("bs.modal", null);
            $(".modal-backdrop:last").remove();
            //to prevent scroll
            $("body").removeClass("modal-open");

            // $elm.modal('hide');
            //hideModal($elm);
            $elm.hide();
            setTimeout(function () {
                resolve(true);
            }, 100);
        });
    }

    window["hideModal"] = function ($elm) {
        // $elm.modal('hide');
        $elm.removeClass("show");
        $(".modal-backdrop:last").remove();
        $elm.data("bs.modal", null);
        //to prevent scroll
        $("body").removeClass("modal-open");

        // $elm.modal('hide');
        $elm.hide();
    };

    window["getSelectedFilesList"] = function (inp) {
        var files = [];
        for (var i = 0; i < inp.files.length; ++i) {
            files.push(inp.files.item(i).name);
        }
        return files;
    };

    customElements.whenDefined("ts-medialibrary").then((app) => {
        const el = document.querySelector("ts-medialibrary");

        if (el) {
            $("ts-medialibrary").on("updated", function (e) {
                const $el = $(this).attr("data-input");
                const payload = e.detail[0];
                console.log(payload);
                $($el).html(payload.html);

                // document.getElementById("fileinput").innerHTML = payload.html;
            });
        }
    });
})(jQuery, window);
