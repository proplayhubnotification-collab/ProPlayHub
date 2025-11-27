// Live Chat CSR behavior: theme toggle + simple client-side messaging
(function(){
  const root = document.documentElement;
  const toggle = document.getElementById('theme-toggle');
  const input = document.getElementById('composer-input');
  const sendBtn = document.getElementById('send-btn');
  const messages = document.getElementById('messages');

  function setTheme(dark){
    if(dark){
      root.classList.add('dark-theme');
      toggle && toggle.setAttribute('aria-pressed','true');
    } else {
      root.classList.remove('dark-theme');
      toggle && toggle.setAttribute('aria-pressed','false');
    }
    try{ localStorage.setItem('pph.dark', dark ? '1' : '0'); }catch(e){}
  }

  // init
  try{
    const stored = localStorage.getItem('pph.dark');
    if(stored === null){
      const prefers = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
      setTheme(prefers);
    } else setTheme(stored === '1');
  }catch(e){ setTheme(false); }

  if(toggle) toggle.addEventListener('click', ()=> setTheme(!document.documentElement.classList.contains('dark-theme')));

  function appendMessage(text, cls){
    const d = document.createElement('div');
    d.className = 'message ' + cls;
    d.textContent = text;
    messages.appendChild(d);
    messages.scrollTop = messages.scrollHeight;
  }

  function send(){
    const v = input.value && input.value.trim();
    if(!v) return;
    appendMessage(v, 'user');
    input.value = '';
    // show typing indicator
    const typing = document.createElement('div');
    typing.className = 'message agent';
    typing.textContent = 'Đang xử lý...';
    messages.appendChild(typing);
    messages.scrollTop = messages.scrollHeight;

    // call backend proxy to Gemini
    fetch('../../backend/gemini_proxy.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ message: v, payload: { prompt: v } })
    }).then(async res => {
      const text = await res.text();
      let reply = '';
      try {
        const j = JSON.parse(text);
        // try common response shapes
        if (j.output && typeof j.output === 'string') reply = j.output;
        else if (j.output && Array.isArray(j.output) && j.output[0] && j.output[0].content) reply = j.output[0].content;
        else if (j.candidates && j.candidates[0] && j.candidates[0].content) reply = j.candidates[0].content;
        else if (j.choices && j.choices[0] && (j.choices[0].text || j.choices[0].message)) reply = j.choices[0].text || j.choices[0].message;
        else if (j.generated_text) reply = j.generated_text;
        else reply = JSON.stringify(j);
      } catch(e){
        // not JSON, use plain text
        reply = text;
      }
      typing.remove();
      appendMessage(reply || 'Không có phản hồi từ server', 'agent');
    }).catch(err => {
      typing.remove();
      appendMessage('Lỗi khi gọi API: ' + (err && err.message ? err.message : err), 'agent');
    });
  }

  sendBtn && sendBtn.addEventListener('click', send);
  input && input.addEventListener('keydown', (e)=>{ if(e.key === 'Enter') send(); });

})();
