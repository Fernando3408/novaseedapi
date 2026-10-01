<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#" data-target="#year_0">Año 0</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#year_1">Año 1</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#year_2">Año 2</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#year_3">Año 3</a>
    </li>
    <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#year_4" data-target="#year_4">Año 4</a>
    </li>
</ul>                    
<div class="tab-content">
    <div class="tab-pane " id="year_0" role="tabpanel">
        <div class="row">
            @foreach($multimedias as $m)
                @if($m->year == 0)
                    <div class="col-lg-6">
                        <img src="/{{ $m->link }}" class="fullsize">
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="tab-pane" id="year_1" role="tabpanel">
        <div class="row">
            @foreach($multimedias as $m)
                @if($m->year == 1)
                    <div class="col-lg-6">
                        <img src="/{{ $m->link }}" class="fullsize">
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="tab-pane" id="year_2" role="tabpanel">
        <div class="row">
            @foreach($multimedias as $m)
                @if($m->year == 2)
                    <div class="col-lg-6">
                        <img src="/{{ $m->link }}" class="fullsize">
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="tab-pane" id="year_3" role="tabpanel">
        <div class="row">
            @foreach($multimedias as $m)
                @if($m->year == 3)
                    <div class="col-lg-6">
                        <img src="/{{ $m->link }}" class="fullsize">
                    </div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="tab-pane active" id="year_4" role="tabpanel">
        <div class="row">
            @foreach($multimedias as $m)
                @if($m->year == 4)
                    <div class="col-lg-6">
                        <img src="/{{ $m->link }}" class="fullsize">
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div> 

