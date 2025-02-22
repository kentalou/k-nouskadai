<form method="{{ $method ?? 'POST' }}" action="{{ $action }}" enctype="multipart/form-data" class="form-horizontal">
    @csrf
    @if (isset($method) && strtolower($method) !== 'post')
        @method($method)
    @endif

    {{ $slot }}
</form>
