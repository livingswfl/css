import { useEffect, useState } from '@wordpress/element';
import { fetchGraph } from '../api/graphApi';

export const useGraph = (focus) => {
  const [graph, setGraph] = useState(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);

  useEffect(() => {
    setLoading(true);
    fetchGraph(focus)
      .then(setGraph)
      .catch(setError)
      .finally(() => setLoading(false));
  }, [focus.view, focus.postId]);

  return { graph, loading, error };
};
