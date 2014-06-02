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
        {{ HTML::style('bootstrap-editable/css/bootstrap-editable.css'); }}
        {{ HTML::script('bootstrap-editable/js/bootstrap-editable.js'); }}
        {{ HTML::style('summernote/summernote.css'); }}
        {{ HTML::style('summernote/summernote-bs2.css'); }}
        {{ HTML::script('summernote/summernote.js'); }}
        {{ HTML::script('summernote/summernote-zh-TW.js'); }}
        
        <style type="text/css">
        html {
            overflow-y: scroll; 
        }
        html, body {
            height: 100%;
        }
        body{
            background-color:#fafafa;
            text-align:center;
            font-family: Microsoft JhengHei, verdana, Times New Roman, 新細明體;
        }
        </style>
        <script type="text/javascript">
        //表單驗證
        jQuery(document).ready(function(){
            // binds form submission and fields to the validation engine
            jQuery("#formID").validationEngine();
        });
        //X-editable
        $.fn.editable.defaults.mode = 'inline';
        //時間格式
        // For todays date;
        Date.prototype.today = function () { 
            return this.getFullYear() + "-" + (((this.getMonth()+1) < 10)?"0":"") + (this.getMonth()+1) + "-" + ((this.getDate() < 10)?"0":"") + this.getDate();
        }
        // For the time now
        Date.prototype.timeNow = function () {
             return ((this.getHours() < 10)?"0":"") + this.getHours() +":"+ ((this.getMinutes() < 10)?"0":"") + this.getMinutes() +":"+ ((this.getSeconds() < 10)?"0":"") + this.getSeconds();
        }
        Date.prototype.fullTime = function () {
             return this.today() + " " + this.timeNow();
        }
        </script>
    </head>
    <body>
        <div class="container">
        {{-- 頁首 --}}
        <img src="/resource/pic/banner.jpg" width="1000px" height="192px" />
        </div>
        <div class="container" style="min-height:60%;margin-bottom:60px;">
        {{-- 巡覽列 --}}
        @include('common.navbar')
        @if($subNavbar != null)
            @include('common.subNavbar')
        @endif
        {{-- 內容 --}}
        @yield('content')
        </div>
        <div id="footer">
        {{-- 頁尾 --}}
        <hr />
            <div class="container">
                <div class="row-fluid">
                    <div class="span6">
                        <p align="left" style="color:gray">
                        Powered by <span class="label">TomTiger</span><br />
                        © 2014 <span class="label">{{ $sitename }}</span>
                        </p>
                    </div>
                    <div class="span6">
                        <p align="right" style="color:gray">
                        {{ date('Y-m-d H:i:s') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
