<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>
        {{-- 標題 --}}
        @if (trim($__env->yieldContent('title')))
        @yield('title') - {{ $sitename }}
        @else
        {{ $sitename }}
        @endif
        </title>
        {{-- Bootstrap v2.3.2 --}}
        {{ HTML::script('https://code.jquery.com/jquery.js'); }}
        {{ HTML::script('bootstrap/js/bootstrap.js'); }}
        {{ HTML::style('bootstrap/css/bootstrap.css'); }}
        {{ HTML::style('font-awesome/css/font-awesome.css'); }}
        {{ HTML::style('jQuery-Validation-Engine-master/css/validationEngine.jquery.css'); }}
        {{ HTML::script('jQuery-Validation-Engine-master/js/jquery.validationEngine.js'); }}
        {{ HTML::script('jQuery-Validation-Engine-master/js/languages/jquery.validationEngine-zh_TW.js'); }}
        {{ HTML::script('js/alt.js'); }}
        {{ HTML::style('css/alt.css'); }}
        
        <meta name="viewport" content="width=device-width, initial-scale=0.8, maximum-scale=0.8, user-scalable=no">
        
        <style type="text/css">
        html, body {
            height: 100%;
        }
        body{
            background-color:#fafafa;
            text-align:center;
            font-family: Microsoft JhengHei, verdana, Times New Roman, 新細明體;
        }
        </style>
        <script>
        jQuery(document).ready(function(){
            // binds form submission and fields to the validation engine
            jQuery("#formID").validationEngine();
        });
        </script>
    </head>
    <body>
        <div class="container">
        
        {{-- 頁首 --}}
        
        <img src="/resource/pic/banner.jpg" width="1000px" height="192px" />
        </div>
        <div class="row-fluid">
        <div class="container span4" style="min-height:60%;margin-bottom:60px;">
        
        {{-- 巡覽列 --}}
        <!--
        @include('common.navbar')
        -->
        {{-- 內容 --}}
        @yield('content')
        </div>
        </div>
        
        <div id="footer" style="height:60px;margin-top:-60px;">
        {{-- 頁尾 --}}
        
        <hr />
            <div class="container">
                <div class="row-fluid">
                    <div class="span2">
                        <p align="left" style="color:gray">
                        Powered by <span class="label">TomTiger</span><br />
                        © 2014 <span class="label">{{ $sitename }}</span>
                        </p>
                    </div>
                    <div class="span2">
                        <p align="right" style="color:gray">
                        {{ date('Y-m-d H:i:s') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
    </body>
</html>
