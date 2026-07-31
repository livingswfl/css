(function(wp){
  const el = wp.element.createElement;
  const {useEffect,useState} = wp.element;
  const {Button,Spinner} = wp.components;
  const apiFetch = wp.apiFetch;
  if (window.reluxityCgpSettings && window.reluxityCgpSettings.nonce) {
    apiFetch.use(apiFetch.createNonceMiddleware(window.reluxityCgpSettings.nonce));
  }
  function metric(node,key){return node.metrics&&node.metrics[key]?node.metrics[key]:0;}
  function NodeCard({node,onFocus}){
    return el('div',{className:'reluxity-cgp-node reluxity-cgp-node--'+node.role},
      el('div',{className:'reluxity-cgp-node__eyebrow'},node.role),
      el('strong',null,node.label),
      el('p',{className:node.purpose?'':'is-muted'},node.purpose||'No purpose set yet.'),
      el('dl',null,
        el('div',null,el('dt',null,'Incoming'),el('dd',null,metric(node,'incoming'))),
        el('div',null,el('dt',null,'Outgoing'),el('dd',null,metric(node,'outgoing'))),
        el('div',null,el('dt',null,'Cross-pillar'),el('dd',null,metric(node,'crossPillar')))
      ),
      el(Button,{variant:'secondary',onClick:()=>onFocus(node)},'Focus')
    );
  }
  function App(){
    const [focus,setFocus]=useState({view:'overview',postId:0});
    const [graph,setGraph]=useState(null); const [loading,setLoading]=useState(true); const [error,setError]=useState(null);
    useEffect(()=>{setLoading(true); const qs=new URLSearchParams({view:focus.view}); if(focus.postId) qs.set('post_id',focus.postId); apiFetch({path:'/reluxity-cgp/v1/graph?'+qs.toString()}).then(setGraph).catch(setError).finally(()=>setLoading(false));},[focus.view,focus.postId]);
    return el('div',{className:'reluxity-cgp-app'},
      el('header',null,el('div',null,el('p',{className:'reluxity-cgp-kicker'},'Reluxity'),el('h1',null,'Content Graph Planner'),el('p',null,'Plan pillars, clusters, supporting pages, and cross-pillar relationships using WordPress Pages as the source of truth.')),el(Button,{variant:'primary',onClick:()=>setFocus({view:'overview',postId:0})},'Website Overview')),
      el('div',{className:'reluxity-cgp-panel'}, loading?el(Spinner):error?el('p',null,'Unable to load graph.'):el('div',{className:'reluxity-cgp-graph'},
        el('div',{className:'reluxity-cgp-row'},el('div',{className:'reluxity-cgp-node reluxity-cgp-node--website'},el('strong',null,graph.root.label),el('p',null,graph.view+' view'))),
        el('div',{className:'reluxity-cgp-edges'},'│\n├────────────────────────────────────┤'),
        el('div',{className:'reluxity-cgp-row'}, graph.nodes.map(node=>el(NodeCard,{key:node.id,node,onFocus:(n)=>setFocus({view:n.role,postId:n.postId})})), el('div',{className:'reluxity-cgp-node reluxity-cgp-node--add'},'+ Plan new page'))
      ))
    );
  }
  const root = document.getElementById('reluxity-content-graph-app');
  if (root) { wp.element.createRoot(root).render(el(App)); }
})(window.wp);
