import { setupListPage } from '../js/common/listPage.js';
import { fetchClasses } from './get.js';
import { renderClasses } from './table.js';
import { openClassModalForCreate } from './form.js';

setupListPage({
  htmlFile: './front/classes.html',
  fetchData: fetchClasses,
  renderTable: renderClasses,
  filterFields: ['Id', 'Name', 'Period'],
  onNewItem: openClassModalForCreate,
  sortFunction: (a, b) => a.name.localeCompare(b.name)
});