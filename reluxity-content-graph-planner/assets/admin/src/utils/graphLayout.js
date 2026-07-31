export const toFlowElements = (graph, onFocus) => {
  if (!graph) return { nodes: [], edges: [] };
  const root = {
    id: 'root',
    type: 'reluxityNode',
    position: { x: 380, y: 0 },
    data: { label: graph.root.label, role: graph.root.type, metrics: {}, onFocus: null },
  };
  const nodes = graph.nodes.map((node, index) => ({
    id: node.id,
    type: 'reluxityNode',
    position: { x: 40 + (index % 4) * 260, y: 190 + Math.floor(index / 4) * 210 },
    data: { ...node, onFocus: () => onFocus(node) },
  }));
  const rootEdges = graph.view === 'overview' ? graph.nodes.map((node) => ({ id: `root:${node.id}`, source: 'root', target: node.id, label: 'Pillar' })) : [];
  const visibleIds = new Set(nodes.map((node) => node.id).concat('root'));
  const edges = graph.edges.filter((edge) => visibleIds.has(edge.source) && visibleIds.has(edge.target)).map((edge) => ({ ...edge, animated: edge.type === 'cross_pillar' }));
  return { nodes: [root, ...nodes], edges: [...rootEdges, ...edges] };
};
