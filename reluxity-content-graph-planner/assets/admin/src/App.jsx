import { Button, Card, CardBody, Spinner } from '@wordpress/components';
import { useState } from '@wordpress/element';
import { GraphCanvas } from './components/GraphCanvas';
import { useGraph } from './hooks/useGraph';

export default function App() {
  const [focus, setFocus] = useState({ view: 'overview', postId: 0 });
  const { graph, loading, error } = useGraph(focus);
  const focusNode = (node) => setFocus({ view: node.role, postId: node.postId });

  return (
    <div className="reluxity-cgp-app">
      <header>
        <div><p className="reluxity-cgp-kicker">Reluxity</p><h1>Content Graph Planner</h1><p>Plan pillars, clusters, supporting pages, and cross-pillar relationships using WordPress Pages as the source of truth.</p></div>
        <Button variant="primary" onClick={() => setFocus({ view: 'overview', postId: 0 })}>Website Overview</Button>
      </header>
      <Card><CardBody>{loading ? <Spinner /> : error ? <p>Unable to load graph.</p> : <GraphCanvas graph={graph} onFocus={focusNode} />}</CardBody></Card>
    </div>
  );
}
