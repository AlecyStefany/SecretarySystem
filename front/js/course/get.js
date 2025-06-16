import { fetchWithFilters } from '../common/api.js';

export async function fetchCourses(filters = {}) {
  return await fetchWithFilters('course', filters);
}
