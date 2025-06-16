import { fetchWithFilters } from '../common/api.js';

export async function fetchStudents(filters = {}) {
  return await fetchWithFilters('student', filters);
}

