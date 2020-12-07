<!DOCTYPE html>


<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}" >
        <title>Library</title>
        <!-- Styles -->
        <link href="{{ asset('css/app.css?version=5')  }}" rel="stylesheet" >
        
        <script>
        
            const api_url = "{{ config('app.url').'/api' }}";
            const app_url = "{{ config('app.url') }}";
            
            function asset_url(){
                return "{{ config('filesystems.disks.public.url') }}";
            }
            
        </script>
            
    </head>

    <body>
        <div id="app"></div>

        <script src="{{ asset('js/app.js?version=5') }}"></script>
    </body>

</html>