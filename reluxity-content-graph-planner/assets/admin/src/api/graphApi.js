import { request } from './client';

export const fetchGraph = ({ view = 'overview', postId = 0 } = {}) => {
  const query = new URLSearchParams({ view });
  if (postId) query.set('post_id', postId);
  return request(`/graph?${query.toString()}`);
};
