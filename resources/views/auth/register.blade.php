@extends('layouts.public')

@section('content')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="import_bot_bet" enctype="multipart/form-data">
                @csrf
                <div class="form-group row">
                    <div class="col-md-12">
                        <input type="file" id="file" name="file" accept=".txt, .csv">
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Importar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
