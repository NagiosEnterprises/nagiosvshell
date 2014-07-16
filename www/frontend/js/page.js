// Messages

    var messages = (function($){

        var add = function(type, text){
            var container = $('#messages'),
                message;

            message = [
                '<div class="message ' + type + '">',
                '<strong>Error</strong> ' + text,
                '</div>'
            ].join('\n');

            $(message).appendTo(container);
        }

        return {
            error: function(text){
                add('error', text);
            }
        }

    })(jQuery);


// Quicksearch

    var quicksearch = (function($){

        var test_data = ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
            'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
            'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
            'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
            'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
            'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
            'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
            'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
            'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
        ];

        var init = function(){

            $('#quicksearch .input').typeahead({
                hint: true,
                highlight: true,
                minLength: 1
            },
            {
                name: 'quicksearch',
                displayKey: 'value',
                source: matcher(test_data),
                templates: {
                    empty: [
                        '<div class="empty-message">',
                        'No quicksearch matches found',
                        '</div>'
                    ].join('\n')
                }
            });

        }

        var matcher = function(strs){

            return function(q, cb) {
                var matches, substrRegex;

                // an array that will be populated with substring matches
                matches = [];

                // regex used to determine if a string contains the substring `q`
                substrRegex = new RegExp(q, 'i');

                // iterate through the pool of strings and for any string that
                // contains the substring `q`, add it to the `matches` array
                $.each(strs, function(i, str) {
                    if (substrRegex.test(str)) {
                        // the typeahead jQuery plugin expects suggestions to a
                        // JavaScript object, refer to typeahead docs for more info
                        matches.push({ value: str });
                    }
                });

                cb(matches);
            };

        }

        return {
            init: function(){
                init();
            }
        }

    })(jQuery);


// FooTable

    var tables = {};

    tables.pagesize = {};

    tables.pagesize.bind = function(){
        $('body').on('click', '.pagesize-container a', function(e){
            var pagesize = $(this).data('page-size'),
                options = $(this).siblings('a'),
                table = $(this).parents('.table-container').find('.footable');

            e.preventDefault();

            $(this).addClass('active');
            options.removeClass('active');

            table
                .data('page-size', pagesize)
                .data('footable')
                .redraw();
        });
    }


// Nav

    var nav = (function($){

        var cookie_name = 'vshell2_nav',
            mobile_breakpoint = 580,
            button_open_name = '#nav-button-open',
            button_close_name = '#nav-button-close';

        var open = function(){
            $('body')
                .removeClass('sidebar-closed')
                .addClass('sidebar-open');
        }

        var close = function(){
            $('body')
                .removeClass('sidebar-open')
                .addClass('sidebar-closed');
        }

        var get_cookie = function(){
            return $.cookie(cookie_name);
        }

        var set_cookie = function(value){
            $.cookie(cookie_name, value, { expires: 365, path: '/' });
        }

        var is_mobile = function(){
            return $(window).width() < mobile_breakpoint
        }

        var was_open = function(){
            return get_cookie() === 'open';
        }

        var load = function(){
            if( is_mobile() ){
                close();
            }else{
                if( was_open() ){
                    open();
                }else{
                    close();
                }
            }
        }

        var bind = function(){
            $('body').on('click', button_open_name, function(e){
                e.preventDefault();
                set_cookie('open');
                open();
            });

            $('body').on('click', button_close_name, function(e){
                e.preventDefault();
                set_cookie('close');
                close();
            });
        }

        return {
            init: function(){
                load();
                bind();
            }
        }

    })(jQuery);


// Document ready

    $(document).ready(function(){
        nav.init();
        quicksearch.init();
        tables.pagesize.bind();
    });
