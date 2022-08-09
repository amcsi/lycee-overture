@extends('layouts.app')

@section('content')
<div id="app" class="flex-center position-ref full-height"></div>
<script>
  window.vars = {!! json_encode($jsVars) !!};

  if (window.location.hostname === 'www.lycee-tcg.eu') {
    window.location.hostname = 'lycee-tcg.eu'
  }
</script>

@vite(['resources/js/app.js'])
@endsection
