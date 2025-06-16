export function showMessage(msg, type = 'info', elementId = null) {
  let element;
  if (elementId) {
    element = document.getElementById(elementId);
  } else {
    element = document.createElement('div');
    element.style.position = 'fixed';
    element.style.top = '10px';
    element.style.right = '10px';
    element.style.zIndex = 9999;
    document.body.appendChild(element);
  }

  element.innerHTML = `<div class="alert alert-${type}" role="alert">${msg}</div>`;

  setTimeout(() => {
    element.innerHTML = '';
    if (!elementId) element.remove();
  }, 3000);
}

export function formatCPF(cpf) {
  if (!cpf) return '';
  cpf = cpf.replace(/\D/g, '');
  return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, '$1.$2.$3-$4');
}

export function formatDate(dateStr) {
  if (!dateStr) return '';
  const d = new Date(dateStr);
  return d.toLocaleDateString('pt-BR');
}