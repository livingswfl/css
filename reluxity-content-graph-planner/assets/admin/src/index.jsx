import { createRoot } from '@wordpress/element';
import App from './App';
import './styles/admin.scss';

const root = document.getElementById('reluxity-content-graph-app');
if (root) {
  createRoot(root).render(<App />);
}
