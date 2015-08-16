<!DOCTYPE html>
<html>
    <head>
        <title>Kvällsmat Oktorp</title>

        <link href="//fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

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
                font-size: 98px;
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
                <div class="vecka">vecka {{ $week }}</div>
                @foreach($veckoMeny as $meny)
                    <div class="mat @if ($today == $meny->datum) idag @endif">
                        {{ strftime("%A", strtotime($meny->datum)) }} | {{ $meny->matratt->namn }}
                    </div>
                @endforeach
            </div>
        </div>
    </body>
</html>
