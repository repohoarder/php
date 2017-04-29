(function () {

    var 
    console = window.console || { log: function() {} };

    /*
    var
    div = document.createElement('div'),
    ifr = document.createElement('iframe'),
    cleared_overlay = false;

    div.id = 'track_overlay';
    div.style.width = "100%";
    div.style.height = "100%";
    div.style.position = 'fixed';
    div.style.background = '#fff';
    div.style.top = '0';
    div.style.left = '0';
    div.style.zIndex = '10000';

    ifr.frameBorder="0";
    ifr.style.height="100%";
    ifr.style.width="100%";
    ifr.style.background="#fff";

    div.appendChild(ifr);

    if (document.hasOwnProperty('body') && typeof document.body !== 'undefined'){
        load_overlay();
    }else{
        window.onload = load_overlay;
    }

    function load_overlay()
    {

        if (document.body.firstChild){
            document.body.insertBefore(div, document.body.firstChild);
        }else{
            document.body.appendChild(div);
        }

        setTimeout(function(){kill_overlay()},10000);

    }
    */


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

            jquery_loaded_pre();

        });

    // otherwise if jquery exists, attach function to dom ready
    } else {

        $(document).ready(function(){

            jquery_loaded();

        });
        
    }

    function jquery_loaded_pre()
    {

        if (document.hasOwnProperty('body') && typeof document.body !== 'undefined'){

            jquery_loaded();

        }else{

            $(document).ready(function(){

                jquery_loaded();

            });

        }

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


    /*
    function kill_overlay()
    {

        cleared_overlay = true;

        if ($('#track_overlay').length > 0){

            $('#track_overlay').remove();

        }

    }
    */


    function post_tracking(track_data)
    {

        /*
        if ( ! track_data.hasOwnProperty('success') || ! track_data.success || ! track_data.hasOwnProperty('data')){
            kill_overlay();
            return;
        }
        */

        var track = track_data.data;

        if (track.hasOwnProperty('split_test') && track.split_test.hasOwnProperty('redirect')){

            if (track.split_test.redirect){

                window.onunload = window.onbeforeunload = function(){};
                window.location.replace(track.split_test.redirect_url);
                return;

            }

        }

        /*
        kill_overlay();
        */
       


    

        /************
        var params = {};

        // get session info back
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