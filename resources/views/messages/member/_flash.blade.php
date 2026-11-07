@if (session('success'))
    <div class="alert alert-success" role="status"><i class="fas fa-check-circle me-2" aria-hidden="true"></i>{{ session('success') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger" role="alert"><ul class="mb-0">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
@endif
