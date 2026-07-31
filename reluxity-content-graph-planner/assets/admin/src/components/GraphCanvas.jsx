import ReactFlow, { Background, Controls } from 'reactflow';
import 'reactflow/dist/style.css';
import { GraphNode } from './GraphNode';
import { toFlowElements } from '../utils/graphLayout';

const nodeTypes = { reluxityNode: GraphNode };

export const GraphCanvas = ({ graph, onFocus }) => {
  const { nodes, edges } = toFlowElements(graph, onFocus);
  return <ReactFlow nodes={nodes} edges={edges} nodeTypes={nodeTypes} fitView><Background /><Controls /></ReactFlow>;
};
