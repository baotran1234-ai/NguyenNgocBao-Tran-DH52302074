  </main>
</div>

<?php
$_flash = getFlash();
?>
<script>
function showToast(msg,type='info',dur=5000){
  const icons={success:'✅',error:'❌',warning:'⚠️',info:'ℹ️'};
  const colors={success:'#4caf50',error:'#f44336',warning:'#ff9800',info:'#2196f3'};
  const t=document.createElement('div');
  t.style.cssText=`display:flex;align-items:center;gap:12px;padding:14px 18px;background:#fff;border-radius:10px;box-shadow:0 8px 24px rgba(0,0,0,0.15);border-left:4px solid ${colors[type]};min-width:280px;animation:toastIn 0.4s ease;font-size:0.875rem;color:#333;max-width:400px`;
  t.innerHTML=`<span style="font-size:1.1rem">${icons[type]}</span><span style="flex:1">${msg}</span><button onclick="this.parentElement.remove()" style="opacity:0.4;font-size:1rem;cursor:pointer;border:none;background:none;padding:0 4px">×</button>`;
  document.getElementById('toast-container').appendChild(t);
  setTimeout(()=>{t.style.opacity='0';t.style.transition='opacity 0.3s';setTimeout(()=>t.remove(),300)},dur);
}
<?php if ($_flash): ?>
showToast(<?= json_encode($_flash['message']) ?>, <?= json_encode($_flash['type']) ?>);
<?php endif; ?>
</script>
<style>
@keyframes toastIn{from{transform:translateX(120%);opacity:0}to{transform:none;opacity:1}}
</style>
</body></html>
