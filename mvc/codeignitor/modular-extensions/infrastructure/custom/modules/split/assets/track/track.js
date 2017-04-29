(function () {

    var 
    console = window.console || { log: function() {} };

    // lazy-load javascript files
    function load_script(url, callback) 
    {

        var script = document.createElement("script")
        script.type = "text/javascript";

        if (script.readyState) { //IE
            script.onreadystatechange = function () {
                if (script.readyState == "loaded" || script.readyState == "complete") {
                    script.onreadystatechange = null;
                    callback();
                }
            };
        } else { //Others
            script.onload = function () {
                callback();
            };
        }

        script.src = url;
        document.getElementsByTagName("head")[0].appendChild(script);
        
    }


    // if jquery isn't around, lazyload it in
    if (typeof(jQuery) === 'undefined'){

        load_script("https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.min.js", function () {

            jquery_loaded();
            
        });

    // otherwise if jquery exists, attach function to dom ready
    } else {

       jquery_loaded();
        
    }


    function jquery_loaded()
    {

        if (typeof track_options === "undefined"){
            track_options = {};
        }

        var host = window.location.hostname.replace('www.',''),
            defaults = {
                screen_height: screen.height,
                screen_width: screen.width,
                viewport_height: $(window).height(),
                viewport_width: $(window).width(),
                url_full: window.location.href,
                url_host: host,
                url_path: window.location.pathname,
                url_query: window.location.search,
                referrer: document.referrer
            }, 
            params = {
                amount: null,
                order_id: null,
                client_id: null,
            };

        options = $.extend({}, defaults, params, track_options); 



        // do dat tracking
        // note: cross-domain jsonp CANNOT be synchronous
        //      must nest any jsonp calls within the previous success() func
        $.ajax({
            type: "GET",
            url: "http://statistics.brainhost.com/split/tracker/track?callback=?",
            dataType: "jsonp",
            data: options,
            success: function (data) {

                post_tracking(data);

            }
        });


    }



    function post_tracking(track_data)
    {

        var track = track_data.data;

        if (track.hasOwnProperty('split_test') && track.split_test.hasOwnProperty('redirect')){

            if (track.split_test.redirect){

                window.onunload = window.onbeforeunload = function(){};
                window.location.replace(track.split_test.redirect_url);
                return;

            }

        }


        /************
        var params = {};

        // get session info back
        // can use this to store visitor_id on a client, for example
        $.ajax({
            type: "GET",
            url: "http://statistics.brainhost.com/split/tracker/get_data?callback=?",
            dataType: "jsonp",
            data: params,
            success: function (sess) {

                console.log(sess);

            }
        });
        */
        

    }


})();