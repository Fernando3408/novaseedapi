(function(){
  let dayDT = null;

  async function openDay(el){
    const date = el.getAttribute('data-date');
    if(!date) return;

    const res = await fetch(`/journal/day/${date}`, {
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    });
    const data = await res.json();

    document.getElementById('dayModalTitle').textContent = `Detalle día ${data.date}`;

    const s = data.summary;
    document.getElementById('daySummary').innerHTML = `
      <div style="display:flex;gap:16px;flex-wrap:wrap;">
        <div><b>Profit:</b> ${s.profit_pct}%</div>
        <div><b>WR:</b> ${s.wr}%</div>
        <div><b>TP:</b> ${s.tp}</div>
        <div><b>SL:</b> ${s.sl}</div>
        <div><b>BE:</b> ${s.be}</div>
        <div><b>Ops:</b> ${s.trades}</div>
      </div>
    `;

    if(dayDT){
      dayDT.destroy();
      dayDT = null;
    }

    const tbody = document.querySelector('#dayTable tbody');
    tbody.innerHTML = '';

    (data.items || []).forEach(it => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${it.player1 ?? ''}</td>
        <td>${it.player2 ?? ''}</td>
        <td>${it.team1 ?? ''}</td>
        <td>${it.team2 ?? ''}</td>
        <td>${it.ht1 ?? ''}</td>
        <td>${it.ht2 ?? ''}</td>
        <td>${it.odds ?? ''}</td>
        <td>${it.parcial1 ?? ''}</td>
        <td>${it.parcial2 ?? ''}</td>
        <td>${it.ft1 ?? ''}</td>
        <td>${it.ft2 ?? ''}</td>
        <td>${it.sp ?? ''}</td>
        <td>${it.stake ?? ''}</td>
        <td>${(it.wl ?? '').toUpperCase()}</td>
      `;
      tbody.appendChild(tr);
    });

    // DataTables (ya lo tienes en Metronic)
    if (window.jQuery && jQuery.fn && jQuery.fn.DataTable) {
      dayDT = jQuery('#dayTable').DataTable({
        pageLength: 10,
        order: [[0,'asc']],
        destroy: true,
        responsive: true,
      });
    }

    const modalEl = document.getElementById('dayModal');
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
  }

  // Exponer global
  window.Journal = { openDay };
})();
