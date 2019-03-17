<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="_token" content="<?= csrf_token(); ?>">
        <title>CDA Downloader</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
        <link href="/css/app.css" rel="stylesheet">
        <!--Scripts-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="/js/app.js"></script>
        
        <style>
            html,body{
                height:100%;
                color:#454545;
                background: #fff;
                font-family: 'Raleway', sans-serif;
            }
            .card {
                box-shadow: 1px 1px 4px #0000005c;
                border: 0;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid h-100">
            <div class="row h-100 align-items-center">
            <div class="col-lg-5 col-sm-12  mx-auto" >
                <h1 class="card-title text-primary">CDA downloader</h1>
                <div class="card mx-auto bg-light" style="min-width: 35rem;">
                    <div class="card-body">
                        <div class="form-group">
                            <h3>Jak to działa?</h3>
                            <p> Wklej link do filmu z CDA.pl i kliknij pobierz</p>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <?= Form::text('url', old('url'), ['placeholder' => 'Link cda :https://www.cda.pl/video/2562765db', 'class' => 'form-control']); ?>
                                <div class="input-group-append">
                                    <?= Form::submit('pobierz', ['class' => 'btn-download btn btn-outline-secondary px-5 mx-auto']); ?>
                                   
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="title m-b-md">

                </div>
            </div>
            </div>
        </div>
        <div class="modal fade show"  tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ups... Coś poszło nie tak :(</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="loader text-center">
                            <img src="/img/loader.svg" alt="">
                        </div>
                        <div class="error">
                            <h2 class="" style="color: #F44336;font-size: 4em;text-align: center;">ERROR</h2>
                            <p>Podany link nie jest obsługiwany, jest premium lub mamy problem z pobieraniem.</p>
                        </div>
                        <div class="success">
                            <img src="" alt="" class="img-fluid">
                            <a href="" download="true"  target="_blank" class="download btn btn-default d-none">pobierz</a> 
                            <ul class="quality"></ul>
                        </div>
                    </div>
                    <div class="modal-footer">

                    </div>
                </div>
            </div>
        </div>

    </body>
</html>
