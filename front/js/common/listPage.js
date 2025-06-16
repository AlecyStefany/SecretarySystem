import { showAlertModal } from './modal.js';

export function setupListPage({
  htmlFile,
  fetchData,
  renderTable,
  filterFields,
  onNewItem,
  sortFunction
}) {
  let currentPage = 1;
  const pageSize = 10;
  let totalPages = 1;

  async function loadPage() {
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

      btnNewStudent?.addEventListener('click', () => onNewItem());

      function getFilters() {
        const filters = {};
        filterFields.forEach(f => {
          const input = document.getElementById(`filter${f}`);
          filters[f.toLowerCase()] = input ? input.value.trim() : '';
        });
        filters.page = currentPage;
        return filters;
      }

      async function loadData() {
        try {
          const filters = getFilters();
          const result = await fetchData(filters);

          const data = result.INFO?.DATA ?? [];

          if (sortFunction) {
            data.sort(sortFunction);
          }

          renderTable(data);

          totalPages = Math.ceil(result.total / pageSize) || 1;
          const paginationInfo = document.getElementById('paginationInfo');
          if (paginationInfo) {
            paginationInfo.textContent = `Página ${currentPage} de ${totalPages}`;
          }

          btnPrev.disabled = currentPage <= 1;
          btnNext.disabled = currentPage >= totalPages;
        } catch (error) {
          console.error(error);
          showAlertModal('Erro', error.message, 'error');
        }
      }

      btnPrev?.addEventListener('click', () => {
        if (currentPage > 1) {
          currentPage--;
          loadData();
        }
      });

      btnNext?.addEventListener('click', () => {
        if (currentPage < totalPages) {
          currentPage++;
          loadData();
        }
      });

      btnFilter?.addEventListener('click', () => {
        currentPage = 1;
        loadData();
      });

      btnClear?.addEventListener('click', () => {
        filterFields.forEach(f => {
          const input = document.getElementById(`filter${f}`);
          if (input) input.value = '';
        });
        currentPage = 1;
        loadData();
      });

      loadData();

    } catch (error) {
      document.getElementById('content').innerHTML = `<p>Erro ao carregar página: ${error.message}</p>`;
    }
  }

  loadPage();
}