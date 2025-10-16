(function () {
  const base = '/Cognate3/';                 // adjust if your site path differs
  const API  = base + 'api/products.php';

  // Require admin session immediately (hard guard)
  (function guard() {
    // If your server uses PHP sessions, this guard should be server-side.
    // Client-side, keep your existing check if you want:
    const token = localStorage.getItem('nexora_token');
    const lastU = (localStorage.getItem('nexora_last_username')||'').toLowerCase();
    if (!token || lastU !== 'admin') location.replace(base + 'login.html?next=products.html');
  })();

  const $ = (s, r=document) => r.querySelector(s);
  const $$ = (s, r=document) => Array.from(r.querySelectorAll(s));

  const rowsEl = $('#adminRows');
  const form   = $('#adminProductForm');

  const nameEl  = $('#adminName');
  const skuEl   = $('#adminSku');
  const priceEl = $('#adminPrice');
  const addedEl = $('#adminAdded');
  const descEl  = $('#adminDesc');
  const specsEl = $('#adminSpecs');
  const videoEl = $('#adminVideo');
  const idEl    = $('#adminProdId');

  function getCategories() {
    return $$('#adminProductForm .form-check-input:checked').map(c => c.value).join(',');
  }
  function setCategories(csv) {
    const set = new Set((csv||'').split(',').map(s=>s.trim()).filter(Boolean));
    $$('#adminProductForm .form-check-input').forEach(c => c.checked = set.has(c.value));
  }

  async function list(q='') {
    const url = q ? `${API}?q=${encodeURIComponent(q)}` : API;
    const res = await fetch(url, { credentials: 'include' });
    if (!res.ok) throw new Error('List failed');
    return await res.json();
  }

  function render(products) {
    if (!products.length) {
      rowsEl.innerHTML = `<tr><td colspan="7" class="text-center text-muted">No products yet</td></tr>`;
      return;
    }
    rowsEl.innerHTML = products.map((p,i)=>`
      <tr>
        <td>${i+1}</td>
        <td>${escapeHtml(p.name)}</td>
        <td>${escapeHtml(p.sku)}</td>
        <td>₱${Number(p.price).toLocaleString()}</td>
        <td>${escapeHtml(p.categories||'')}</td>
        <td>${p.added ? escapeHtml(p.added) : ''}</td>
        <td class="text-end">
          <button class="btn btn-sm btn-outline-primary me-1" data-edit="${p.id}"><i class="bi bi-pencil"></i></button>
          <button class="btn btn-sm btn-outline-danger" data-del="${p.id}"><i class="bi bi-trash"></i></button>
        </td>
      </tr>
    `).join('');
  }

  function escapeHtml(s){ return (s??'').replace(/[&<>"']/g,m=>({ '&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;' }[m])); }

  async function reload() {
    const q = $('#adminQ')?.value?.trim() || '';
    const data = await list(q);
    render(data);
  }

  rowsEl?.addEventListener('click', async (e) => {
    const btn = e.target.closest('button');
    if (!btn) return;
    if (btn.dataset.edit) {
      const id = +btn.dataset.edit;
      const data = await list(); // simple: get all, pick one
      const p = data.find(x => +x.id === id);
      if (!p) return;
      idEl.value = p.id;
      nameEl.value = p.name; skuEl.value = p.sku; priceEl.value = p.price;
      addedEl.value = p.added || '';
      descEl.value = p.description || '';
      specsEl.value = p.specs || '';
      videoEl.value = p.video_url || '';
      setCategories(p.categories || '');
      window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    if (btn.dataset.del) {
      const id = +btn.dataset.del;
      if (!confirm('Delete this product?')) return;
      const res = await fetch(`${API}?id=${id}`, { method: 'DELETE', credentials: 'include' });
      if (!res.ok) return alert('Delete failed');
      await reload();
    }
  });

  form?.addEventListener('submit', async (e) => {
    e.preventDefault();
    const payload = {
      id: idEl.value ? +idEl.value : undefined,
      name: nameEl.value.trim(),
      sku: skuEl.value.trim(),
      price: Number(priceEl.value || 0),
      categories: getCategories(),
      added: addedEl.value || null,
      description: descEl.value.trim(),
      specs: (specsEl.value||'').trim(),
      image_url: null, // you can add upload later
      video_url: (videoEl.value||'').trim()
    };
    const method = payload.id ? 'PUT' : 'POST';
    const res = await fetch(API, {
      method,
      credentials: 'include',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    });
    if (!res.ok) {
      const err = await res.json().catch(()=>({}));
      return alert(err.error || 'Save failed');
    }
    form.reset();
    $('#adminBtnReset')?.click(); // if you wire clearing preview
    idEl.value = '';
    await reload();
  });

  $('#adminBtnReset')?.addEventListener('click', () => {
    form.reset(); idEl.value=''; setCategories('');
    $('#adminPreview')?.classList.add('d-none');
  });

  $('#adminQ')?.addEventListener('input', () => reload());
  $('#adminBtnExport')?.addEventListener('click', async () => {
    const data = await list();
    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = 'products.json';
    a.click();
    URL.revokeObjectURL(a.href);
  });

  // initial load
  document.addEventListener('DOMContentLoaded', reload);
})();