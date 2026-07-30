@extends('layouts.app')
<script>
    window.Laravel = <?php echo json_encode(['api_token' => (Auth::user())->api_token]); ?>
</script>
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="">
                    <div class="">
                        <span class="text-white display-4 pr-340 ">Simple Todo </span>
                        <form method="POST" action="{{ route('logout') }}" accept-charset="UTF-8" name="logout-form" id="logout-form" class="d-inl">
                            {{ csrf_field() }}
                            <button class="btn navbar-btn btn-light navbar-right pull-right" type="submit">Log Out</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center mt-20">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <todo-component></todo-component>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection
