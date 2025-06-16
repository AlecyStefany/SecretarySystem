import { loadGenericPage } from '../common/pageLoader.js';
import { fetchRegistrations } from './get.js';
import { renderRegistrations } from './table.js';
import { initForm, openRegistrationModalForCreate } from './form.js';
import { fetchCourses } from './get.js';

function getFilters() {
  return {
    course: document.getElementById('filterCourse')?.value.trim() || '',
  };
}

async function populateFilters() {
  try {

    const coursesData = await fetchCourses();
    const courses = coursesData.INFO.CURSOS || coursesData;
    const filterCourse = document.getElementById('filterCourse');
    if (filterCourse) {
      filterCourse.innerHTML = '<option value="">Todos os Cursos</option>';
      courses.forEach(c => {
        const opt = document.createElement('option');
        opt.value = c.id;
        opt.textContent = c.name;
        filterCourse.appendChild(opt);
      });
    }
  } catch (error) {
    console.error('Erro ao carregar filtros:', error);
  }
}

export const loadPage = loadGenericPage({
  htmlFile: './front/registrations.html',
  fetchFunction: fetchRegistrations,
  renderFunction: renderRegistrations,
  openModalFunction: openRegistrationModalForCreate,
  getFiltersFunction: getFilters,
  initFormFunction: async () => {
    await populateFilters();
    await initForm();
  },
  key: 'MATRICULAS',
});