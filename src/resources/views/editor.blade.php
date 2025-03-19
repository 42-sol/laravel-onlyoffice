<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">

        <title>{{ __('onlyoffice::package.title') }}</title>

        @if(!isset($error))
            <script type="text/javascript" src="{{$embeddingScript}}"></script>
        @endif
    </head>
    <body>
        @if(isset($error))
            <div>{{ $error }}</div>
        @else
            <iframe id="editor"></iframe>
        @endif

    </body>
    <script>
        @if(!isset($error))
            const config = {
                document: {
                    fileType: "{{ \Illuminate\Support\Arr::get($document, 'fileType') }}",
                    key: "{{ \Illuminate\Support\Arr::get($document, 'key') }}",
                    title: "{{ \Illuminate\Support\Arr::get($document, 'title') }}",
                    url: "{{ \Illuminate\Support\Arr::get($document, 'url') }}",
                },
                token: "{{ $token }}",
                editorConfig: {
                    callbackUrl: "{{ $callbackUrl }}",
                    customization : {
                        forcesave: true // enables manual saving
                    },
                },
                documentType: "{{ $documentType }}",
                height: window.screen.height + "px",
            }

            const docEditor = new DocsAPI.DocEditor("editor", config)
        @endif
    </script>
</html>
