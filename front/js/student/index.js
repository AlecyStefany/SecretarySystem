import { setupListPage } from '../common/listPage.js';
import { fetchStudents } from './get.js';
import { renderStudents } from './table.js';
import { openStudentModalForCreate } from './form.js';

setupListPage({
  htmlFile: './front/students.html',
  fetchData: fetchStudents,
  renderTable: renderStudents,
  filterFields: ['Id', 'Name', 'Document'],
  onNewItem: openStudentModalForCreate,
  sortFunction: (a, b) => a.name.localeCompare(b.name)
});