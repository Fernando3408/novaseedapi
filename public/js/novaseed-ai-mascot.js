(function(window, document){
  'use strict';
  var states = ['idle','thinking','typing','success','warning','offline','analyzing','processing','responding','no-data'];
  var labels = {
    idle:'Disponible', thinking:'Analizando datos', typing:'Generando respuesta',
    success:'Resultado listo', warning:'Información parcial', offline:'Sin conexión o no disponible',
    analyzing:'Analizando datos', processing:'Procesando', responding:'Respondiendo', 'no-data':'Datos insuficientes'
  };
  var imageStates = {analyzing:'thinking', processing:'thinking', responding:'typing', 'no-data':'warning', offline:'warning'};
  function setState(element, state, label){
    if (!element || states.indexOf(state) === -1) return;
    states.forEach(function(name){ element.classList.remove('ns-mascot--state-' + name); });
    element.classList.add('ns-mascot--state-' + state);
    element.setAttribute('data-state', state);
    element.setAttribute('aria-label', label || labels[state]);
    var referenceArt = element.querySelector('.ns-mascot__reference-art');
    if (referenceArt) referenceArt.src = '/img/novaseed-ai/mascot/papa-cientifica-' + (imageStates[state] || state) + '.png?v=20260925-6';
  }
  function setChatState(chat, state, text){
    if (!chat) return;
    chat.querySelectorAll('[data-ns-mascot]').forEach(function(mascot){ setState(mascot, state); });
    var status = chat.querySelector('[data-ns-chat-status]');
    if (status) status.textContent = text || labels[state];
    var dot = chat.querySelector('.ns-ai-chat__status i');
    if (dot) dot.className = state === 'no-data' ? 'is-warning' : '';
  }
  function openChat(chat){
    if (!chat) return;
    chat.classList.add('is-open');
    chat.setAttribute('aria-hidden','false');
    document.body.classList.add('ns-ai-chat-open');
    var input = chat.querySelector('[data-ns-chat-input]');
    if (input) window.setTimeout(function(){ input.focus(); }, 420);
  }
  function closeChat(chat){
    if (!chat) return;
    chat.classList.remove('is-open');
    chat.setAttribute('aria-hidden','true');
    document.body.classList.remove('ns-ai-chat-open');
    var launcher = document.querySelector('[data-ns-mascot-launcher]');
    if (launcher) launcher.focus();
  }
  function escapeHtml(value){
    return String(value == null ? '' : value).replace(/[&<>\"']/g, function(character){
      return {'&':'&amp;','<':'&lt;','>':'&gt;','\"':'&quot;',"'":'&#39;'}[character];
    });
  }
  function safeUrl(value){
    try {
      var url = new URL(String(value), window.location.origin);
      return /^https?:$/.test(url.protocol) ? url.href : '';
    } catch (error) { return ''; }
  }
  function renderPlainText(text){
    return escapeHtml(text).replace(/\n/g, '<br>');
  }
  function normalizeCompactTables(text){
    var source = String(text || '').replace(/\r\n/g, '\n').replace(/\\\|/g, '|');
    return source.split('\n').map(function(line){
      if (!line.includes('|')) return line;
      var cells = line.split('|').map(function(cell){ return cell.trim(); });
      var separatorIndex = cells.findIndex(function(cell, index){ return index > 0 && /^:?-{3,}:?$/.test(cell); });
      if (separatorIndex < 2) return line;
      var header = cells.slice(1, separatorIndex).filter(Boolean);
      var count = header.length;
      var separators = cells.slice(separatorIndex, separatorIndex + count).filter(Boolean);
      var body = cells.slice(separatorIndex + count).filter(Boolean);
      if (count < 2 || separators.length !== count || !separators.every(function(cell){ return /^:?-{3,}:?$/.test(cell); }) || body.length < count) return line;
      var rows = [];
      for (var i = 0; i + count <= body.length; i += count) rows.push(body.slice(i, i + count));
      return '|' + header.join('|') + '|\n|' + separators.join('|') + '|\n' + rows.map(function(row){ return '|' + row.join('|') + '|'; }).join('\n');
    }).join('\n');
  }
  function inlineMarkdown(text){
    return escapeHtml(text)
      .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
      .replace(/`([^`]+)`/g, '<code>$1</code>');
  }
  function isTableSeparator(line){
    var cells = line.trim().replace(/^\|/, '').replace(/\|$/, '').split('|').map(function(cell){ return cell.trim(); });
    return cells.length >= 2 && cells.every(function(cell){ return /^:?-{3,}:?$/.test(cell); });
  }
  function tableCells(line){
    return line.trim().replace(/^\|/, '').replace(/\|$/, '').split('|').map(function(cell){ return cell.trim(); });
  }
  function renderTable(lines){
    var header = tableCells(lines[0]);
    var rows = lines.slice(2).map(tableCells);
    var html = '<div class="ns-ai-chat__table-wrap"><table class="ns-ai-chat__table"><thead><tr>';
    html += header.map(function(cell){ return '<th>' + inlineMarkdown(cell) + '</th>'; }).join('');
    html += '</tr></thead><tbody>';
    html += rows.map(function(row){ return '<tr>' + header.map(function(_, index){ return '<td>' + inlineMarkdown(row[index] || '') + '</td>'; }).join('') + '</tr>'; }).join('');
    return html + '</tbody></table></div>';
  }
  function renderMarkdownFallback(text){
    var lines = normalizeCompactTables(text).split('\n');
    var html = [], paragraph = [];
    function flush(){ if (paragraph.length) { html.push('<p>' + inlineMarkdown(paragraph.join(' ')) + '</p>'); paragraph = []; } }
    for (var i = 0; i < lines.length; i++) {
      var line = lines[i];
      if (line.includes('|') && i + 1 < lines.length && isTableSeparator(lines[i + 1])) {
        flush();
        var table = [line, lines[++i]];
        while (i + 1 < lines.length && lines[i + 1].includes('|') && lines[i + 1].trim()) table.push(lines[++i]);
        html.push(renderTable(table));
      } else if (/^###\s+/.test(line)) { flush(); html.push('<h4>' + inlineMarkdown(line.replace(/^###\s+/, '')) + '</h4>'); }
      else if (/^##\s+/.test(line)) { flush(); html.push('<h3>' + inlineMarkdown(line.replace(/^##\s+/, '')) + '</h3>'); }
      else if (/^[-*]\s+/.test(line)) { flush(); html.push('<div class="ns-ai-chat__list-item">• ' + inlineMarkdown(line.replace(/^[-*]\s+/, '')) + '</div>'); }
      else if (!line.trim()) flush();
      else paragraph.push(line);
    }
    flush();
    return html.join('');
  }
  function renderBadges(metadata){
    var badges = [];
    if (!metadata) return '';
    if (metadata.language) badges.push(metadata.language);
    if (metadata.intent) badges.push(metadata.intent);
    if (metadata.model) badges.push(metadata.model);
    if (metadata.novaseed_context) badges.push('ERP consultado');
    var evidence = metadata.evidence_classification || {};
    if (evidence.inferred) badges.push('inferencia');
    else if (evidence.calculated) badges.push('cálculo');
    else if (evidence.observed) badges.push('observado');
    var trace = metadata.erp_trace || {};
    if (trace.results_count != null) badges.push(String(trace.results_count) + ' resultados');
    if (trace.parents_count != null) badges.push(String(trace.parents_count) + ' padres');
    if (trace.evidence_confidence) badges.push('confianza ' + trace.evidence_confidence);
    var quality = trace.data_quality || {};
    var warnings = trace.warnings || {};
    var missing = Number(quality.missing_count || 0);
    var warningCount = Number(warnings.count || (Array.isArray(warnings) ? warnings.length : 0));
    if (missing) badges.push('faltan ' + missing + ' campos');
    if (warningCount) badges.push(warningCount + ' alertas');
    if (metadata.prediction_scope && metadata.prediction_scope.extrapolation_warning) badges.push('validar por ambiente');
    return badges.length ? '<div class="ns-ai-chat__badges">' + badges.map(function(badge){ return '<span class="ns-ai-chat__badge">' + escapeHtml(badge) + '</span>'; }).join('') + '</div>' : '';
  }
  function renderPresentation(metadata, content){
    var presentation = metadata && metadata.presentation;
    if (!presentation || !Array.isArray(presentation.blocks)) return renderMarkdownFallback(content || '');
    var html = renderBadges(metadata) + '<div class="ns-ai-chat__presentation">';
    if (presentation.title) html += '<h3 class="ns-ai-chat__presentation-title">' + escapeHtml(presentation.title) + '</h3>';
    presentation.blocks.forEach(function(block){
      if (!block || !block.type) return;
      if (block.type === 'markdown') html += '<div class="ns-ai-chat__presentation-markdown">' + renderMarkdownFallback(block.content || '') + '</div>';
      if (block.type === 'metrics' && Array.isArray(block.items)) {
        html += '<div class="ns-ai-chat__metrics">' + block.items.map(function(item){
          return '<div class="ns-ai-chat__metric"><small>' + escapeHtml(item.label || '') + '</small><strong>' + escapeHtml(item.value == null ? '' : item.value) + '</strong>' + (item.description ? '<em>' + escapeHtml(item.description) + '</em>' : '') + '</div>';
        }).join('') + '</div>';
      }
      if (block.type === 'notice') html += '<div class="ns-ai-chat__notice"><strong>' + escapeHtml(block.title || 'Aviso') + '</strong><div>' + renderPlainText(block.content || '') + '</div></div>';
      if (block.type === 'sources' && Array.isArray(block.items)) {
        html += '<div class="ns-ai-chat__sources">' + block.items.map(function(item){ var url = safeUrl(item); return url ? '<a href="' + escapeHtml(url) + '" target="_blank" rel="noopener">Fuente externa</a>' : ''; }).join('') + '</div>';
      }
      if (block.type === 'table' && Array.isArray(block.columns) && Array.isArray(block.rows)) {
        html += '<div class="ns-ai-chat__table-wrap"><table class="ns-ai-chat__table"><thead><tr>' + block.columns.map(function(column){ return '<th>' + escapeHtml(column) + '</th>'; }).join('') + '</tr></thead><tbody>' + block.rows.map(function(row){ return '<tr>' + block.columns.map(function(_, index){ return '<td>' + escapeHtml(row && row[index] != null ? row[index] : '') + '</td>'; }).join('') + '</tr>'; }).join('') + '</tbody></table></div>';
      }
    });
    if (presentation.split_candidate) html += '<button type="button" class="ns-ai-chat__expand" data-ns-expand-presentation>Mostrar todos los datos</button>';
    return html + '</div>';
  }
  function appendMessage(container, type, text, metadata){
    var message = document.createElement('div');
    message.className = 'ns-ai-chat__message ns-ai-chat__message--' + type;
    var body = type === 'user' ? renderPlainText(text) : renderPresentation(metadata || {}, text);
    message.innerHTML = '<span class="ns-ai-chat__message-meta">' + (type === 'user' ? 'Tú' : 'Genetista IA') + '</span>' + body;
    container.appendChild(message);
    container.parentElement.scrollTop = container.parentElement.scrollHeight;
  }
  window.NovaSeedAiMascot = { setState:setState, states:states, labels:labels };
  var initialized = false;
  function init(){
    if (initialized) return;
    initialized = true;
    document.querySelectorAll('[data-ns-mascot]').forEach(function(element){
      if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) element.classList.add('ns-mascot--static');
    });
    var chat = document.querySelector('[data-ns-ai-chat]');
    var conversationId = null;
    document.addEventListener('click', function(event){
      var launcher = event.target.closest && event.target.closest('[data-ns-mascot-launcher]');
      if (launcher) {
        launcher.classList.add('is-pressed');
        window.setTimeout(function(){ launcher.classList.remove('is-pressed'); }, 420);
        openChat(chat);
        document.dispatchEvent(new CustomEvent('novaseed:assistant-open', {detail:{source:'mascot'}}));
        return;
      }
      var close = event.target.closest && event.target.closest('[data-ns-chat-close]');
      if (close) { closeChat(chat); return; }
      var suggestion = event.target.closest && event.target.closest('[data-ns-chat-suggestion]');
      if (suggestion && chat) {
        var input = chat.querySelector('[data-ns-chat-input]');
        if (input) { input.value = suggestion.getAttribute('data-ns-chat-suggestion'); input.focus(); }
      }
      var expand = event.target.closest && event.target.closest('[data-ns-expand-presentation]');
      if (expand && chat) {
        var expanded = chat.classList.toggle('ns-ai-chat--expanded');
        expand.textContent = expanded ? 'Ocultar datos completos' : 'Mostrar todos los datos';
      }
    });
    document.addEventListener('keydown', function(event){
      if (event.key === 'Escape' && chat && chat.classList.contains('is-open')) closeChat(chat);
    });
    if (chat) {
      var form = chat.querySelector('[data-ns-chat-form]');
      var input = chat.querySelector('[data-ns-chat-input]');
      var empty = chat.querySelector('[data-ns-chat-empty]');
      var messages = chat.querySelector('[data-ns-chat-messages]');
      if (input) input.addEventListener('keydown', function(event){
        if (event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); form.dispatchEvent(new Event('submit', {cancelable:true})); }
      });
      if (form) form.addEventListener('submit', function(event){
        event.preventDefault();
        var value = input.value.trim();
        if (!value) return;
        if (empty) empty.style.display = 'none';
        appendMessage(messages, 'user', value);
        input.value = '';
        setChatState(chat, 'analyzing', 'Analizando datos');
        var csrf = document.querySelector('meta[name="csrf-token"]');
        window.setTimeout(function(){ setChatState(chat, 'processing', 'Procesando'); }, 260);
        fetch('/novaseed-ai/chat', {
          method: 'POST',
          headers: {'Content-Type':'application/json', 'Accept':'application/json', 'X-Requested-With':'XMLHttpRequest', 'X-CSRF-TOKEN': csrf ? csrf.getAttribute('content') : ''},
          body: JSON.stringify({message:value, conversation_id:conversationId, module:document.body.getAttribute('data-ns-module') || 'erp', route:window.location.pathname})
        }).then(function(response){
          return response.json().then(function(data){ return {ok:response.ok, data:data}; });
        }).then(function(result){
          if (!result.ok || !result.data.ok) throw new Error(result.data.message || 'No se pudo contactar al Genetista.');
          setChatState(chat, 'responding', 'Respondiendo');
          conversationId = result.data.conversation_id || conversationId;
          appendMessage(messages, 'assistant', result.data.message || '', result.data.metadata || {});
          setChatState(chat, 'success', 'Análisis listo');
        }).catch(function(error){
          setChatState(chat, 'offline', 'Sin conexión o no disponible');
          appendMessage(messages, 'assistant', error.message || 'No se pudo contactar al Genetista Estadístico. Intenta nuevamente.');
        }).then(function(){
          window.setTimeout(function(){ setChatState(chat, 'idle', 'Disponible'); }, 800);
        });
      });
    }
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, {once:true});
  } else {
    init();
  }
})(window, document);
