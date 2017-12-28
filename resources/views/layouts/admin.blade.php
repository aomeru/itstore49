<!doctype html>
<html class="no-js" lang="en">

    @include('partials.admin.head')

    <body class="fixedMenu_left fixedNav_position">
        {{-- <div class="preloader" style=" position: fixed; width: 100%; height: 100%; top: 0; left: 0; z-index: 100000; backface-visibility: hidden; background: #ffffff;">
        </div>
        <div class="preloader_img" style="width: 200px; height: 200px; position: absolute; left: 48%; top: 48%; background-position: center; z-index: 999999">
            <img src="{{asset('assets/img/Preloader_8.gif')}}" style=" width: 64px;" alt="loading...">
        </div> --}}

        <div id="wrap" class="body-04">
            @include('partials.admin.top-nav')
			<div class="wrapper fixedNav_top" style="background-color: rgba(240,240,240,.85)">
                @include('partials.admin.nav')
                @include('partials.admin.main')
            </div>
        </div>


        @yield('page_footer')




        @include('partials.admin.scripts')

        <script>
            $(function(){

                function getErrorMessage(jqXHR, exception)
                {
                    var msg = '';
                    if (jqXHR.responseJSON) {
                        var errors = (jqXHR.responseJSON.errors);
                        $.each(errors, function(key, value){
                            msg = value[0];
                        })
                    } else if(jqXHR['errors']) {
                        msg = jqXHR['errors'];
                    } else if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network. <br>Please Contact Support Team.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]. <br>Please Contact Support Team.';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500]. <br>Please Contact Support Team.\n' + jqXHR.responseText;
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed. <br>Please Contact Support Team.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error';
                    } else if (exception === 'abort') {
                        msg = 'Request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    return msg;
                }

            });
        </script>
        @yield('footer')
    </body>

</html>
