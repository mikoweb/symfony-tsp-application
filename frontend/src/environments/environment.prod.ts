export const environment = {
  production: true,
  defaultLanguage: 'pl',
  googleMapsApiKey: import.meta.env['NG_APP_GOOGLE_MAPS_API_KEY'] ?? '',
  tspApiBaseUrl: import.meta.env['NG_APP_TSP_API_BASE_URL'] ?? '',
};
