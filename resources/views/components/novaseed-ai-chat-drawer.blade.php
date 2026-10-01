<div class="ns-ai-chat" data-ns-ai-chat aria-hidden="true">
    <button type="button" class="ns-ai-chat__backdrop" data-ns-chat-close aria-label="Cerrar panel NovaSeed IA"></button>
    <section class="ns-ai-chat__panel" role="dialog" aria-modal="true" aria-labelledby="ns-ai-chat-title">
        <header class="ns-ai-chat__header">
            <div class="ns-ai-chat__identity">
                @include('components.novaseed-ai-mascot', ['variant' => 'blob', 'state' => 'idle', 'size' => 'sm', 'animated' => true, 'interactive' => false, 'placement' => 'header', 'label' => 'Genetista IA disponible'])
                <div>
                    <h2 id="ns-ai-chat-title">Genetista Estadístico</h2>
                    <p>Director de decisión genética</p>
                    <span class="ns-ai-chat__status"><i></i><span data-ns-chat-status>Disponible</span></span>
                </div>
            </div>
            <button type="button" class="ns-ai-chat__close" data-ns-chat-close aria-label="Cerrar chat">Cerrar</button>
        </header>
        <div class="ns-ai-chat__body">
            <div class="ns-ai-chat__empty" data-ns-chat-empty>
                @include('components.novaseed-ai-mascot', ['variant' => 'blob', 'state' => 'idle', 'size' => 'lg', 'animated' => true, 'interactive' => false, 'placement' => 'empty', 'label' => 'Genetista IA disponible'])
                <h3>Hola, soy tu asistente de genética.</h3>
                <p>Puedo ayudarte a analizar datos, comparar variedades, revisar cruces y generar respuestas a partir de la información cargada en NovaSeed.</p>
                <div class="ns-ai-chat__suggestions">
                    <button type="button" data-ns-chat-suggestion="Comparar variedades">Comparar variedades</button>
                    <button type="button" data-ns-chat-suggestion="Analizar cruces">Analizar cruces</button>
                    <button type="button" data-ns-chat-suggestion="Revisar resultados">Revisar resultados</button>
                    <button type="button" data-ns-chat-suggestion="Explicar diferencias">Explicar diferencias</button>
                </div>
            </div>
            <div class="ns-ai-chat__messages" data-ns-chat-messages></div>
        </div>
        <form class="ns-ai-chat__composer" data-ns-chat-form>
            <textarea name="message" data-ns-chat-input rows="2" placeholder="Escribe una instrucción o consulta..." aria-label="Consulta para Genetista IA"></textarea>
            <div class="ns-ai-chat__composer-row">
                <span>Enter para enviar · Shift+Enter para salto de línea</span>
                <button type="submit">Enviar</button>
            </div>
        </form>
    </section>
</div>
