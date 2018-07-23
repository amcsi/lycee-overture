@extends('layouts.app')

@section('content')
<div id="app" class="flex-center position-ref full-height"></div>
<script>
  window.vars = {!! json_encode($jsVars) !!};
</script>
<script defer type="text/javascript" src="{{ mix('js/app.js') }}"></script>
@endsection
