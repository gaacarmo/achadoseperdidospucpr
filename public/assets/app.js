// AcheiNaPuc — melhorias progressivas. Tudo funciona sem JS; isto só deixa mais suave.
(() => {
  // Confirmação antes de ações destrutivas
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-confirm]');
    if (btn && !confirm(btn.dataset.confirm)) e.preventDefault();
  });

  // Curtir sem recarregar a página
  document.querySelectorAll('form[action="/curtir.php"]').forEach((form) => {
    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const btn = form.querySelector('button');
      btn.disabled = true;
      try {
        const res = await fetch(form.action, { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json' } });
        const data = await res.json();
        if (res.status === 401 && data.login) { location.href = data.login; return; }
        if (!res.ok) throw new Error();
        btn.classList.toggle('is-on', data.liked);
        btn.setAttribute('aria-pressed', String(data.liked));
        btn.querySelector('span').textContent = data.count;
        if (data.liked) { btn.classList.remove('pop'); void btn.offsetWidth; btn.classList.add('pop'); }
      } catch { form.submit(); }
      finally { btn.disabled = false; }
    });
  });

  // Prévia de imagem e arrastar-e-soltar
  document.querySelectorAll('[data-dropzone]').forEach((zone) => {
    const input = zone.querySelector('input[type=file]');
    const preview = zone.querySelector('[data-preview]');
    const show = () => {
      const file = input.files && input.files[0];
      if (!file) return;
      if (file.size > 3 * 1024 * 1024) { alert('A imagem deve ter no máximo 3 MB.'); input.value = ''; return; }
      preview.src = URL.createObjectURL(file);
      preview.hidden = false;
      zone.classList.add('has-file');
    };
    input.addEventListener('change', show);
    ['dragenter', 'dragover'].forEach((t) => zone.addEventListener(t, (e) => { e.preventDefault(); zone.classList.add('is-over'); }));
    ['dragleave', 'drop'].forEach((t) => zone.addEventListener(t, () => zone.classList.remove('is-over')));
    zone.addEventListener('drop', (e) => {
      e.preventDefault();
      if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; show(); }
    });
  });

  // Busca: envia ao pressionar Enter e mantém os filtros atuais
  const search = document.querySelector('.filters input[type=search]');
  if (search) {
    const params = new URLSearchParams(location.search);
    ['tipo', 'categoria'].forEach((k) => {
      if (params.get(k)) {
        const h = document.createElement('input');
        h.type = 'hidden'; h.name = k; h.value = params.get(k);
        search.form.appendChild(h);
      }
    });
  }
})();
