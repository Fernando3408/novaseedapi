@php
    $variant = $variant ?? 'blob';
    $state = $state ?? 'idle';
    $size = $size ?? 'md';
    $animated = isset($animated) ? filter_var($animated, FILTER_VALIDATE_BOOLEAN) : true;
    $interactive = isset($interactive) ? filter_var($interactive, FILTER_VALIDATE_BOOLEAN) : false;
    $placement = $placement ?? 'inline';
    $labels = ['idle' => 'Disponible', 'thinking' => 'Analizando datos', 'typing' => 'Generando respuesta', 'success' => 'Resultado listo', 'warning' => 'Información parcial', 'offline' => 'Sin conexión o no disponible'];
    $label = $label ?? ($labels[$state] ?? 'Asistente NovaSeed');
    $uid = 'nova-mascot-' . substr(md5(uniqid('', true)), 0, 8);
    $imageState = ['analyzing' => 'thinking', 'processing' => 'thinking', 'responding' => 'typing', 'no-data' => 'warning', 'offline' => 'warning'][$state] ?? $state;
@endphp

<span id="{{ $uid }}" class="ns-mascot ns-mascot--{{ $variant }} ns-mascot--{{ $size }} ns-mascot--{{ $placement }} ns-mascot--state-{{ $state }}{{ $animated ? '' : ' ns-mascot--static' }}{{ $interactive ? ' ns-mascot--interactive' : '' }}" data-ns-mascot data-state="{{ $state }}" data-animated="{{ $animated ? 'true' : 'false' }}" role="img" aria-label="{{ $label }}" @if(!$interactive) aria-hidden="true" @endif>
    <span class="ns-mascot__glow" aria-hidden="true"></span>
    <svg class="ns-mascot__art" viewBox="0 0 160 180" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
        <defs>
            <linearGradient id="{{ $uid }}-body" x1="34" y1="24" x2="132" y2="145" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#FFF0C9"/><stop offset=".42" stop-color="#E8BD82"/><stop offset="1" stop-color="#B8774F"/></linearGradient>
            <linearGradient id="{{ $uid }}-coat" x1="52" y1="112" x2="111" y2="169" gradientUnits="userSpaceOnUse"><stop stop-color="#FFFFFF"/><stop offset="1" stop-color="#DCE8E5"/></linearGradient>
            <linearGradient id="{{ $uid }}-sprout" x1="92" y1="38" x2="123" y2="10" gradientUnits="userSpaceOnUse"><stop stop-color="#258E68"/><stop offset="1" stop-color="#B4E48F"/></linearGradient>
            <linearGradient id="{{ $uid }}-eye" x1="48" y1="63" x2="111" y2="105" gradientUnits="userSpaceOnUse"><stop stop-color="#214651"/><stop offset="1" stop-color="#071D28"/></linearGradient>
            <radialGradient id="{{ $uid }}-cheek"><stop stop-color="#F6A694" stop-opacity=".7"/><stop offset="1" stop-color="#D88372" stop-opacity="0"/></radialGradient>
        </defs>
        <ellipse class="ns-mascot__shadow" cx="82" cy="169" rx="43" ry="7"/>
        <g class="ns-mascot__character">
            <path class="ns-mascot__sprout ns-mascot__sprout--back" d="M87 42C84 28 89 17 103 10C107 23 103 35 94 44" fill="url(#{{ $uid }}-sprout)"/>
            <path class="ns-mascot__sprout" d="M99 42C101 27 112 19 126 21C122 34 113 41 100 46" fill="url(#{{ $uid }}-sprout)"/>
            <path class="ns-mascot__body" d="M79 32C109 29 136 49 137 82C139 113 123 141 96 149C67 157 31 143 25 113C19 82 29 51 53 39C61 35 70 33 79 32Z" fill="url(#{{ $uid }}-body)"/>
            <path class="ns-mascot__highlight" d="M45 60C54 45 69 39 82 40" fill="none" stroke="#FFF5D8" stroke-width="7" stroke-linecap="round" opacity=".76"/>
            <ellipse class="ns-mascot__cheek ns-mascot__cheek--left" cx="44" cy="103" rx="14" ry="10" fill="url(#{{ $uid }}-cheek)"/>
            <ellipse class="ns-mascot__cheek ns-mascot__cheek--right" cx="116" cy="103" rx="14" ry="10" fill="url(#{{ $uid }}-cheek)"/>
            <path class="ns-mascot__coat" d="M53 124C61 137 73 144 82 145C92 144 103 137 111 124C122 132 127 145 128 161C116 169 101 173 82 173C63 173 48 169 36 161C37 145 42 132 53 124Z" fill="url(#{{ $uid }}-coat)"/>
            <path class="ns-mascot__collar" d="M60 126L82 146L104 126L96 151L82 159L68 151Z" fill="#E5F2EE"/>
            <path class="ns-mascot__tie" d="M79 147L85 147L89 170L82 174L75 170Z" fill="#5EC8BC"/>
            <path class="ns-mascot__arm ns-mascot__arm--left" d="M48 139C38 136 31 142 32 152C33 159 40 160 47 155" fill="none" stroke="#D49B6C" stroke-width="9" stroke-linecap="round"/>
            <path class="ns-mascot__arm ns-mascot__arm--right" d="M116 139C126 136 133 142 132 152C131 159 124 160 117 155" fill="none" stroke="#D49B6C" stroke-width="9" stroke-linecap="round"/>
            <g class="ns-mascot__eyes"><ellipse cx="61" cy="78" rx="16" ry="20" fill="#FFFDF6"/><ellipse cx="101" cy="78" rx="16" ry="20" fill="#FFFDF6"/><ellipse class="ns-mascot__pupil" cx="64" cy="81" rx="9" ry="12" fill="url(#{{ $uid }}-eye)"/><ellipse class="ns-mascot__pupil" cx="98" cy="81" rx="9" ry="12" fill="url(#{{ $uid }}-eye)"/><circle cx="67" cy="76" r="3.5" fill="#B8FFF1"/><circle cx="101" cy="76" r="3.5" fill="#B8FFF1"/></g>
            <g class="ns-mascot__face ns-mascot__face--idle"><path d="M72 105C78 113 87 113 94 105" fill="none" stroke="#653F37" stroke-width="4" stroke-linecap="round"/></g>
            <g class="ns-mascot__face ns-mascot__face--thinking"><path d="M73 108C79 103 86 103 93 108" fill="none" stroke="#653F37" stroke-width="4" stroke-linecap="round"/></g>
            <g class="ns-mascot__face ns-mascot__face--typing"><path d="M75 108H91" fill="none" stroke="#653F37" stroke-width="4" stroke-linecap="round"/></g>
            <g class="ns-mascot__face ns-mascot__face--success"><path d="M71 103C78 114 89 114 96 103" fill="none" stroke="#653F37" stroke-width="4" stroke-linecap="round"/><path d="M117 61l4 5 8-10" fill="none" stroke="#3FAF83" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></g>
            <g class="ns-mascot__face ns-mascot__face--warning"><path d="M74 109C80 106 87 106 93 109" fill="none" stroke="#653F37" stroke-width="4" stroke-linecap="round"/><circle cx="124" cy="54" r="9" fill="#F6CA70"/><path d="M124 49V55M124 59v1" stroke="#653F37" stroke-width="2.5" stroke-linecap="round"/></g>
            <g class="ns-mascot__face ns-mascot__face--offline"><path d="M54 73L68 86M68 73L54 86M94 73L108 86M108 73L94 86" stroke="#718C91" stroke-width="4" stroke-linecap="round"/><path d="M75 109H91" fill="none" stroke="#718C91" stroke-width="4" stroke-linecap="round"/></g>
            <g class="ns-mascot__activity" aria-hidden="true"><circle cx="65" cy="121" r="3.5"/><circle cx="78" cy="121" r="3.5"/><circle cx="91" cy="121" r="3.5"/></g>
        </g>
    </svg>
    <img class="ns-mascot__reference-art" src="/img/novaseed-ai/mascot/papa-cientifica-{{ $imageState }}.png?v=20260925-6" alt="" aria-hidden="true">
</span>
