<!DOCTYPE html>
<html>
    <head>
        <title>Kvällsmat Oktorp</title>

        <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">
        <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: left;
                display: inline-block;
            }

            .vecka {
                font-size: 128px;
                font-weight: 900;
            }
            .mat {
                font-size: 48px;
            }
            
            .idag {
                color: #ff6666;
                font-weight: 900;
            }

        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="vecka">v. {{ $week }}</div>
                @foreach($veckoMeny as $meny)
                    <div class="mat @if ($today == $meny->datum) idag @endif">
                        {{ $weekdays[strftime("%A", strtotime($meny->datum))] }} | {{ $meny->matratt->namn }}
                    </div>
                @endforeach
                <a href="/{{ $week - 1 }}" class="btn btn-default">Förra veckan</a> <a href="/{{ $week + 1 }}" class="btn btn-primary pull-right">Nästa vecka</a>
            </div>
        </div>
    </body>
</html>
