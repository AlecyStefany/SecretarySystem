import { showAlertModal } from '../modal.js';

export function loadGenericPage({
  htmlFile,
  fetchFunction,
  renderFunction,
  openModalFunction,
  getFiltersFunction,
  initFormFunction,
  key
}) {
  let currentPage = 1;
  const pageSize = 10;
  let totalPages = 1;

  return async function () {
    try {
      const res = await fetch(`../${htmlFile}`);
      if (!res.ok) throw new Error('Página não encontrada');
      const html = await res.text();
      document.getElementById('content').innerHTML = html;

      const btnPrev = document.getElementById('btnPrev');
      const btnNext = document.getElementById('btnNext');
      const btnFilter = document.getElementById('btnFilter');
      const btnClear = document.getElementById('btnClear');
      const btnNewStudent = document.getElementById('btnNewStudent');

      btnNewStudent?.addEventListener('click', () => {
        openModalFunction();
      });

      await initFormFunction();

      async function loadData() {
        try {
          const filters = getFiltersFunction();
          filters.page = currentPage;

          const result = await fetchFunction(filters);

          const resultData = result.INFO?.[key];

          if (Array.isArray(resultData)) {
            resultData.sort((a, b) => {
              const nameA = (a.name || '').toLowerCase();
              const nameB = (b.name || '').toLowerCase();
              return nameA.localeCompare(nameB);
            });
          }

          renderFunction(resultData || []);

          totalPages = Math.ceil(result.total / pageSize) || 1;

          const paginationInfo = document.getElementById('paginationInfo');
          if (paginationInfo) {
            paginationInfo.textContent = `Página ${currentPage} de ${totalPages}`;
          }

          if (btnPrev) btnPrev.disabled = currentPage <= 1;
          if (btnNext) btnNext.disabled = currentPage >= totalPages;

        } catch (error) {
          console.error(error);
          showAlertModal('Erro', error.message, 'error');
        }
      }

      if (btnPrev) {
        btnPrev.addEventListener('click', () => {
          if (currentPage > 1) {
            currentPage--;
            loadData();
          }
        });
      }

      if (btnNext) {
        btnNext.addEventListener('click', () => {
          if (currentPage < totalPages) {
            currentPage++;
            loadData();
          }
        });
      }

      if (btnFilter) {
        btnFilter.addEventListener('click', () => {
          currentPage = 1;
          loadData();
        });
      }

      if (btnClear) {
        btnClear.addEventListener('click', () => {
          const inputs = document.querySelectorAll('.filter-input');
          inputs.forEach(input => (input.value = ''));
          currentPage = 1;
          loadData();
        });
      }

      loadData();

    } catch (error) {
      document.getElementById('content').innerHTML = `<p>Erro ao carregar página: ${error.message}</p>`;
    }
  };
}