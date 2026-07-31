import apiFetch from '@wordpress/api-fetch';

if (window.reluxityCgpSettings?.nonce) {
  apiFetch.use(apiFetch.createNonceMiddleware(window.reluxityCgpSettings.nonce));
}

export const request = (path, options = {}) => apiFetch({ path: `/reluxity-cgp/v1${path}`, ...options });
