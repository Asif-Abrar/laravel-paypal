<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head>
<body>
    <a href="{{ route('processTransaction') }}" class="btn btn-success">Pay $1000</a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        toastr.options = {
            "closeButton" : true,
            "progressBar" : true
        }

        @if( Session::has('success') )
        toastr.success("{{ session('success') }}");
        @endif

        @if( Session::has('error') )
        toastr.error("{{ session('error') }}");
        @endif

        @if( Session::has('info') )
        toastr.info("{{ session('info') }}");
        @endif

        @if( Session::has('warning') )
        toastr.warning("{{ session('warning') }}");
        @endif
    </script>
</body>
</html>
