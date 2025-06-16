export const API_BASE_URL = "http://localhost:8000";

export async function fetchWithFilters(resource, filters = {}) {
  const queryParts = [];

  for (const [key, value] of Object.entries(filters)) {
    if (value !== undefined && value !== null && value !== '') {
      queryParts.push(`${encodeURIComponent(key)}=${encodeURIComponent(String(value).trim())}`);
    }
  }

  const query = queryParts.length ? `?${queryParts.join('&')}` : '';

  const response = await fetch(`${API_BASE_URL}/${resource}${query}`);
  const json = await response.json().catch(() => null);

  if (!response.ok) {
    const errorMessage = json?.ERROR || 'Erro desconhecido';
    throw new Error(errorMessage);
  }

  return json;
}