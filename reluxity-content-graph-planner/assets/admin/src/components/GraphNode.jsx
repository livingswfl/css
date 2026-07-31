import { Button } from '@wordpress/components';
import { Handle, Position } from 'reactflow';

export const GraphNode = ({ data }) => {
  const metrics = data.metrics || {};
  return (
    <div className={`reluxity-cgp-node reluxity-cgp-node--${data.role}`}>
      <Handle type="target" position={Position.Top} />
      <div className="reluxity-cgp-node__eyebrow">{data.role}</div>
      <strong>{data.label}</strong>
      {data.purpose ? <p>{data.purpose}</p> : <p className="is-muted">No purpose set yet.</p>}
      <dl>
        <div><dt>Incoming</dt><dd>{metrics.incoming || 0}</dd></div>
        <div><dt>Outgoing</dt><dd>{metrics.outgoing || 0}</dd></div>
        <div><dt>Cross-pillar</dt><dd>{metrics.crossPillar || 0}</dd></div>
      </dl>
      {data.onFocus ? <Button variant="secondary" onClick={data.onFocus}>Focus</Button> : null}
      <Handle type="source" position={Position.Bottom} />
    </div>
  );
};
